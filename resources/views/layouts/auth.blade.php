<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
        
        <title> Solo Sauce </title>
        
        <!-- Fonts and icons -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        
        <!-- Nucleo Icons -->
        <link href="/" rel="stylesheet" />
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        
        <!-- CSS Files -->
        <link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.css') }}" rel="stylesheet" />
    </head>

    <body class="g-sidenav-show bg-gray-100">

        @yield('content')
    
        <!-- Core JS Files -->
        <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
        <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    
        <!-- Plugin for the charts, full documentation here: https://www.chartjs.org/ -->
        <script src="{{asset('assets/js/plugins/chartjs.min.js')}}"></script>
        <script src="{{asset('assets/js/plugins/Chart.extension.js')}}"></script>
    
        <!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{asset('assets/js/corporate-ui-dashboard.min.js')}}"></script>
    </body>

</html>