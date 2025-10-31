<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de ELECCIONES - I.E.</title>

    <!-- Fonts -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .fa-btn {
            margin-right: 6px;
        }

        .d-none {
            display: none;
        }

        .navbar-brand {
            height: auto;
        }

        .navbar-default {
            background: linear-gradient(to right, #2980b9, #3498db);
        }

        .navbar-default .navbar-brand,
        .navbar-default .navbar-brand:hover {
            color: #fff;
        }

        .title_login {
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Login Form Styling */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px);
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 7px 20px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, #2980b9, #3498db);
            padding: 30px 20px;
            text-align: center;
        }

        .login-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 24px;
            margin: 0;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0 0;
            font-size: 14px;
        }

        .login-body {
            padding: 30px;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .login-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .login-input:focus {
            border-color: #3498db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2980b9, #3498db);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 20px;
        }

        <blade media|%20(max-width%3A%20480px)%20%7B>.login-container {
            padding: 10px;
        }

        .login-body {
            padding: 20px;
        }
        }
    </style>
</head>

<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed d-none" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand title_login" href="{{ url('/') }}">
                    Sistema de Votación
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav d-none">
                    <li><a href="{{ url('/home') }}">Inicio</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right d-none">
                    <!-- Authentication Links -->
                    @if(Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i
                                            class="fa fa-btn fa-sign-out"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script> -->
    <script src="{{ asset('/store/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/store/js/bootstrap.min.js') }}"></script>

    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>

</html>