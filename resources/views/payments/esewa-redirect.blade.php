<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to eSewa...</title>
</head>
<body onload="document.getElementById('esewa-form').submit();">
    <p>Redirecting to eSewa payment gateway, please wait...</p>
    <form id="esewa-form" action="{{ $actionUrl }}" method="POST">
        @foreach($formData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>
</html>