<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ get_setting('app_name', config('app.name')) }}</title>
    <link rel="icon" type="image/x-icon" href="{{ get_setting('app_favicon') ? asset('storage/' . get_setting('app_favicon')) : asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="font-nunito text-sm antialiased bg-white dark:bg-[#0e1726] text-gray-600 dark:text-white-dark min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg mx-auto text-center">
        @yield('content')
    </div>
</body>
</html>