<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title')</title>

    <meta property="og:title" content="" />
    <meta property="og:image" content="{{asset('assets/preview.jpg')}}" />
    <meta property="og:description" content=""/>

    <!-- Scripts -->
    <script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    @stack('scripts')
    @stack('styles')
    <script src="{{ asset('js/app.js') }}"></script>

<!-- Styles -->

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
    <link href="{{ asset('assets/favicon/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/utils.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}">



</head>
<body>
@include('include.modal_window')
@include('include.modal_window_smena')
@include('include.modal_window_confirm')
@include('include.modal_window_graph')
@include('include.modal_window_export')
@include('include.messenger')

@yield('side_menu')

@include('include.header')

@include('include.pre_loader')

<div class="content" id="main_content" >

    @yield('content')
</div>





<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });


    window.onload = function () {
        document.body.classList.add('loaded_hiding');
        window.setTimeout(function () {
            document.body.classList.add('loaded');
            document.body.classList.remove('loaded_hiding');
        }, 1000);
    }
</script>

</body>
</html>
