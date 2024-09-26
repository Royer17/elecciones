<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistema de Votación</title>
    <link rel="shortcut icon" type="image/x-icon" href="/store/img/" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('/store/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/store/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/store/css/elegant-icons.css') }}" type="text/css">
    <!-- <link rel="stylesheet" href="{{ asset('/store/css/nice-select.css') }}" type="text/css"> -->
    <link rel="stylesheet" href="{{ asset('/store/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/store/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/store/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/store/css/style.css') }}" type="text/css">
    {{-- Sweet alert 2 --}}
    <link href="{{ URL::asset('/plugins/sweet-alert-v2/sweetalert2.min.css') }}" rel="stylesheet">

    {{-- Select2 --}}
    <link href="{{ URL::asset('/plugins/select2/select2-madmin.css') }}" rel="stylesheet">

    <style type="text/css">
        @import url("https://fonts.googleapis.com/css?family=Lato");
        html,
        body {
        height: 100%;
        margin: 0;
        font-family: Lato, sans-serif;
        background-color: #e1e2e1;
        }
        header {
        background: #3498db; /* fallback for old browsers */
        background: -webkit-linear-gradient(
            to right,
            #2980b9,
            #3498db
        ); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(
            to right,
            #2980b9,
            #3498db
        ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        .card-header {
        background: #3498db; /* fallback for old browsers */
        background: -webkit-linear-gradient(
            to right,
            #2980b9,
            #3498db
        ); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(
            to right,
            #2980b9,
            #3498db
        ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        color: white;
        }

      .navbar {
        background:  linear-gradient(
            to right,
            #2980b9,
            #3498db) !important;
        }


    </style>

    <link rel="stylesheet" href="{{asset('/css/custom_style.css')}}">

    @yield('plugins-css')

</head>

<body class="body_2022">

    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('store.layouts.sections.header')

    @yield('content')

    <!-- Page content -->

    <!-- Footer Section Begin -->
    @include('store.layouts.sections.footer')
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{{ asset('/store/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/store/js/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('/store/js/jquery.nice-select.min.js') }}"></script> -->
    <script src="{{ asset('/store/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/store/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('/store/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('/store/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/store/js/main.js') }}"></script>

    {{-- Sweet alert 2 --}}
    <script type="text/javascript" src="{{ URL::asset('/plugins/sweet-alert-v2/sweetalert2.min.js') }}"></script>

    {{-- Axios --}}
    <script src="{{ asset('/plugins/axios/axios.js') }}"></script>


    {{-- select2 --}}
    <script type="text/javascript" src="{{ URL::asset('/plugins/select2/select2.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          $('.select_2').select2();
      });
    </script>

    {{-- Jquery BlockUI --}}
    <!-- <script type="text/javascript" src="https://malsup.github.io/jquery.blockUI.js"></script> -->
    <script type="text/javascript" src="{{ URL::asset('/js/jquery.blockUI.js') }}"></script>

    {{-- help functions --}}
    <script src="{{ asset('/js/some_functions.js') }}"></script>

    <script type="text/javascript">

    </script>


    @yield('plugins-js')



</body>

</html>
