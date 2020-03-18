<!doctype html>
<html>
    <head>
        <meta chartset="utf-8">
        <title>It's Our Kawartha</title>
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    </head>
    <body style="margin-bottom: 100px;">
        <div class="container">
            @include('templates.navigation')
        </div>
        <div class="container">
            @yield('content')
        </div>
        <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
    <footer class="navbar fixed-bottom" style="height: 40px; background-color: #007bff;">
        <div class="container">
            <a style="color: white;" href="{{ route('todo') }}">Make a suggestion</a>
        </div>
    </footer>
    </body>
</html>
