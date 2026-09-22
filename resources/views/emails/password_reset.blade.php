<html>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>You requested a password reset. Click the link below to reset your password:</p>
    <p><a href="{{ $resetUrl }}">Reset Password</a></p>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
