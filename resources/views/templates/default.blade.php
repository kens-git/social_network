<!doctype html>
<html>
    <head>
        <meta chartset="utf-8">
        <title>Social Network</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body>
        @include('templates.navigation')
        @yield('content')
    </body>
</html>
