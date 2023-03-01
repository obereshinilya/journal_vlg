@extends('layouts.app')
@section('title')
    Отчеты
@endsection

@section('side_menu')
    @include('web.reports.side_menu_reports')
@endsection


@section('content')

    @push('scripts')
        <script src="{{asset('assets/js/moment-with-locales.min.js')}}"></script>
        <script src="{{asset('assets/libs/changeable_td.js')}}"></script>
        <script src="{{asset('assets/libs/tooltip/popper.min.js')}}"></script>
        <script src="{{asset('assets/libs/tooltip/tippy-bundle.umd.min.js')}}"></script>

        <script src="{{asset('assets/libs/apexcharts.js')}}"></script>
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>

    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{asset('assets/css/table.css')}}">
        <link rel="stylesheet" href="{{asset('assets/libs/tooltip/tooltip.css')}}">
    @endpush




    <div id="default">
        <h1 style="text-align: center">Выберите отчет</h1>
    </div>


    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>


    </script>



@endsection
