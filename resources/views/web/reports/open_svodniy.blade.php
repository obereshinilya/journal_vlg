@extends('layouts.app')
@section('title')
    Часовая сводка
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
    <div style="display: inline-flex; width: 100%; margin-top: 10px">

        <h3>Часовая сводка за </h3>
        <div class="date-input-group" style="margin-left: 2%">
            <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
            <label for="table_date_start" class="table_date_label">Дата</label>
        </div>
        <div style="position: absolute; right: 50px">
            <button class="button button1" style="float: right" onclick="open_modal_export_ober()">Экспорт</button>
            <button id="setting" class="button button1">Настройка</button>
        </div>
    </div>
    {{--    @include('include.choice_date')--}}
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>



    <div id="tableDiv" style="height: calc(100% - 160px); margin-bottom: 0px">
        <table id="statickItemInfoTable" class="itemInfoTable" style=" display: table; table-layout: fixed">
            <colgroup>
                <col style="width: 5%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
                <col style="width: 10%"/>
            </colgroup>
            <thead>
            <tr>
                <th rowspan="2" style="width: 5%; text-align: center; position: sticky; top: 0">Час</th>
                <th colspan="2" style=" text-align: center; position: sticky; top: 0">Волгоградское ПХГ</th>
                <th colspan="3" style=" text-align: center; position: sticky; top: 0">Скважины</th>
                <th colspan="7" style=" text-align: center; position: sticky; top: 0">КЦ-1</th>
            </tr>
            <tr>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Закачка (тыс.м<sup>3</sup>)</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Отбор (тыс.м<sup>3</sup>)</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">в работе</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">в резерве</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">в ремонте</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Т вх</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Т вых</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Р вх</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">Р вых</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">ГПА в раб</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">ГПА в рез</th>
                <th style="width: 10%; text-align: center; position: sticky; top: 25px">ГПА в рем</th>
            </tr>
            </thead>
            <tbody id="time_id">

            </tbody>
        </table>

    </div>


    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>

        $(document).ready(function () {
            var today = new Date();
            if (today.getHours() < 13) {
                today.setDate(today.getDate() - 1)
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            } else {
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            }
            document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));

            get_table_data()
            document.getElementById('table_date_start')
            $('#table_date_start').change(function () {
                get_table_data();
            })


            $('#setting').click(function () {
                window.location.href = '/svodniy_setting'
            });
        })

        function CallPrint() {
            window.location.href = '/print_svodniy/' + $('#table_date_start').val()
        }

        function CallExcel() {
            window.location.href = '/excel_svodniy/' + $('#table_date_start').val()
            document.getElementById('modal_export_ober').style.display = 'none'
        }

        function get_table_data() {

            $.ajax({
                url: '/get_svodniy/' + $('#table_date_start').val(),
                method: 'GET',
                success: function (res) {
                    var body = document.getElementById('time_id')
                    body.innerText = ''
                    for (var i = 0; i < 24; i++) {
                        var hour = Number(res[i]['hours'])
                        if (hour < 10) {
                            hour = '0' + hour
                        }
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${hour + ':00'}</p></td>`
                        if (res[i]['in_gas'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Math.round(Number(res[i]['in_gas'])).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['in_gas']}</p></td>`
                        }
                        if (res[i]['out_gas'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['out_gas']).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['out_gas']}</p></td>`
                        }
                        if (res[i]['skv_job'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_job']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_job']}</p></td>`
                        }
                        if (res[i]['skv_res'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_res']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_res']}</p></td>`
                        }
                        if (res[i]['skv_rem'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_rem']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_rem']}</p></td>`
                        }
                        if (res[i]['t_in'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['t_in']).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['t_in']}</p></td>`
                        }
                        if (res[i]['t_out'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['t_out']).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['t_out']}</p></td>`
                        }
                        if (res[i]['p_in'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['p_in']).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['p_in']}</p></td>`
                        }
                        if (res[i]['p_out'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['p_out']).toFixed(3)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['p_out']}</p></td>`
                        }

                        if (res[i]['gpa_job'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_job']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_job']}</p></td>`
                        }
                        if (res[i]['gpa_res'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_res']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_res']}</p></td>`
                        }
                        if (res[i]['gpa_rem'] != '...') {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_rem']).toFixed(0)}</p></td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_rem']}</p></td>`
                        }

                        body.appendChild(tr);
                    }

                },
                async: true
            })
        }


    </script>
    @include('include.font_size-change')
    <style>
        .create_td {
            background-color: white;
        }

        tr:hover {
            font-weight: bold;
        }

        #time_id tr:nth-child(odd){background: #e9effc;}
        #time_id tr:hover{background:#fff}

        .itemInfoTable thead th{
		padding-top: 3px;
		padding-bottom: 3px;
	}
        p{
            margin: 3px;
        }

        .button {
            background-color: #4CAF50;
            border: none;
            border-radius: 6px;
            color: white;
            height: 3%;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1 {
            background-color: white;
            color: black;
            border: 2px solid #008CBA;
        }

        .button1:hover {
            background-color: #008CBA;
            color: white;
        }

        input {

        }

        thead th {
            position: sticky;
        }

        .date_input {
            font-family: inherit;
            width: 100%;
            border: 0;
            border-bottom: 2px solid #9b9b9b;
            outline: 0;
            font-size: 1.3rem;
            color: black;
            padding: 7px 0;
            background: transparent;
            transition: border-color 0.2s;
        }

        .date-input-group {
            /*width: 30%;*/
            margin: 8px 5px;
            display: table-cell;
            padding-left: 5px;
            padding-right: 10px;
            float: left;
        }

        input[type=date]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            display: none;
        }

        input[type=date]::-webkit-clear-button {
            -webkit-appearance: none;
            display: none;
        }


        .date_input::placeholder {
            color: transparent;
        }

        .date_input:placeholder-shown ~ .form__label {
            font-size: 1.3rem;
            cursor: text;
            top: 20px;
        }

        .table_date_label {
            position: absolute;
            top: 0;
            display: block;
            transition: 0.2s;
            font-size: 1rem;
            color: #9b9b9b;
        }

        .date_input:focus {
            /*padding-bottom: 6px;*/
            font-weight: 700;
            /*border-width: 3px;*/
            border-image: linear-gradient(to right, black, gray);
            border-image-slice: 1;
        }

        .date_input:focus ~ .table_date_label {
            position: absolute;
            top: 0;
            display: block;
            transition: 0.2s;
            font-size: 1rem;
            color: black;
            font-weight: 700;
        }
    </style>


@endsection
