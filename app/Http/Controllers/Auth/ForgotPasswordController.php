<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Twilio\Rest\Client;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');
        /** @noinspection PhpUndefinedFieldInspection */
        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate token
        $token = Str::random(60);
        $user->reset_token = $token;
        $user->reset_token_expires_at = now()->addHour();
        $user->save();

        // Send email using a Mailable
        Mail::to($user->email)->send(new PasswordResetMail($user, $token));

        // Send WhatsApp message if phone is present
        if (!empty($user->phone)) {
            try {
                $sid = env('TWILIO_SID');
                $token_twilio = env('TWILIO_AUTH_TOKEN');
                $twilio = new Client($sid, $token_twilio);
                $from = env('TWILIO_WHATSAPP_FROM');
                $phone = $user->phone;
                $resetUrl = url('/password/reset/' . $token);
                $twilio->messages->create("whatsapp:$phone", [
                    'from' => $from,
                    'body' => "Reset your password: $resetUrl"
                ]);
            } catch (\Exception $e) {
                // Optionally log error
            }
        }

        return back()->with('status', 'We have emailed your password reset link! If you provided a WhatsApp number, you will also receive it there.');
    }

    public function showResetForm(string $token): View
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        /** @noinspection PhpUndefinedFieldInspection */
        $user = User::query()
            ->where('email', $email)
            ->where('reset_token', $token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        $user->password = bcrypt($request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return redirect('/login')->with('status', 'Password has been reset!');
    }

    public function showPhoneResetForm(): View
    {
        return view('auth.passwords.phone');
    }

    public function sendResetCode(Request $request): RedirectResponse
    {
        $request->validate(['phone' => 'required|string']);

        $phone = $request->input('phone');
        /** @noinspection PhpUndefinedFieldInspection */
        $user = User::query()->where('phone', $phone)->first();
        if (!$user) {
            return back()->withErrors(['phone' => 'We can\'t find a user with that phone number.']);
        }

        // Generate a 6-digit code
        $code = random_int(100000, 999999);
        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Send via Twilio
        try {
            $sid = env('TWILIO_SID');
            $token_twilio = env('TWILIO_AUTH_TOKEN');
            $twilio = new Client($sid, $token_twilio);
            $from = env('TWILIO_WHATSAPP_FROM');
            $phone = $user->phone;
            $twilio->messages->create("whatsapp:$phone", [
                'from' => $from,
                'body' => "Your password reset code is: $code"
            ]);
        } catch (\Exception $e) {
            // Log error
        }

        return back()->with('status', 'Reset code sent to your WhatsApp!');
    }

    public function verifyResetCode(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|numeric',
            'password' => 'required|confirmed|min:8',
        ]);

        $phone = $request->input('phone');
        $code = $request->input('code');
        /** @noinspection PhpUndefinedFieldInspection */
        $user = User::query()
            ->where('phone', $phone)
            ->where('reset_code', $code)
            ->where('reset_code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        $user->password = bcrypt($request->password);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->save();

        return redirect('/login')->with('status', 'Password has been reset!');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        // This is an alias for verifyResetCode for backward compatibility
        return $this->verifyResetCode($request);
    }
}
