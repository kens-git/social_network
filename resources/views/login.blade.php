<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Employee Login</title>
        <style>
            html {
                background-color: #555555;
            }

            h2 {
                font-size: 22px;
                text-align: center;
            }

            form {
                margin: 200px auto 0px auto;
                width: 400px;
            }

            form input {
                border: 1px solid black;
                display: block;
                margin: 10px auto 10px auto;
                padding: 5px;
            }

            form input[type="submit"] {
                background-color: #999999;
                color: black;
            }
        </style>
    </head>
    <body>
        <form method="post" action="{{ route('login') }}">
            @if($errors->any())
                <h2>Invalid login details.</h2>
            @endif
        @csrf()
            <input type="text" name="username" placeholder="id">
            <input type="password" name="password" placeholder="password">
            <input type="submit" value="Login">
        </form>
    </body>
</html>
