<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showPhoneResetForm()
    {
        return view('auth.passwords.phone');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);

        $code = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        $this->sendWhatsAppMessage($request->phone, "Your password reset code is: $code");

        return redirect()->route('password.verify')->with('phone', $request->phone);
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required',
        ]);

        $record = DB::table('password_resets')->where('phone', $request->phone)->first();

        if (!$record || !Hash::check($request->code, $record->token)) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        return view('auth.passwords.reset', ['phone' => $request->phone]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        User::where('phone', $request->phone)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')->where('phone', $request->phone)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset.');
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $twilio->messages->create("whatsapp:$phone", [
            'from' => env('TWILIO_WHATSAPP_FROM'),
            'body' => $message,
        ]);
    }
}