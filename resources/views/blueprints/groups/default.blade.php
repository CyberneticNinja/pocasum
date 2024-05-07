<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
<div class="container mx-auto mt-5">
    <h2 class="text-4xl font-bold">@yield('dashboard-title')</h2>
    <div class="py-12 bg-white text-black min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @yield('content')


            </div>
        </div>
    </div>
</div>
</body>
</html>

