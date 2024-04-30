<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Church Community</title>
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
<div class="container mx-auto mt-5">
    <div class="flex justify-between items-center">
        <div class="flex-grow text-center">
            <h1 class="text-4xl font-bold inline-block">Welcome to Our Church Community!</h1>
        </div>
        <div class="flex items-center justify-center space-x-4"> <!-- Flex container with spacing -->
            <a href="{{ route('login') }}" class="flex items-center justify-center rounded">
                <img src="{{ asset('images/login.webp') }}" alt="Login" class="h-20 w-20 mr-2" style="width: 45px; height: 45px;"> <!-- Corrected alt text -->
                <span>Login</span>
            </a>
            <a href="{{ route('register') }}" class="flex items-center justify-center rounded">
                <img src="{{ asset('images/register.webp') }}" alt="Register" class="h-20 w-20 mr-2" style="width: 45px; height: 45px;">
                <span>Register</span>
            </a>
        </div>
    </div>
    <p class="text-center mb-4">Connecting, growing, and serving together.</p>

    <div class="flex justify-center mt-4">
        <img src="{{ asset('images/church - homepage .webp') }}" alt="Modern Church" class="max-w-full h-auto">
    </div>
    <br/>
    <br/>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
