@extends('layouts.app')
@section('title')
    Потери на ГПА
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
        <div style="display: inline-flex; width: 100%">

            <h3>Парамемтры работы ДКС</h3>
            <div class="date-input-group" style="margin-left: 2%">
                <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
                <label for="table_date_start" class="table_date_label">Дата</label>
            </div>
            <div style="position: absolute; right: 11%">
                <button  id="print" class="button button1">Печать</button>
            </div>
        </div>
{{--    @include('include.choice_date')--}}
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>



    <div id="tableDiv" style="width: 80%; margin-bottom: 0px">
        <table id="statickItemInfoTable" class="itemInfoTable" style="display: table; table-layout: fixed">
            <thead>
            <tr>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Наименование ДКС</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Давление(вых)</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Степень сжатия</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Всего</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В работе</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В резерве</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В ремонте</th>
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
            if (today.getHours() < 13){
                today.setDate(today.getDate() -1)
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

            $('#print').click(function() {
                window.location.href = '/print_param_dks/'+$('#table_date_start').val()
            });
        })

         function get_table_data() {

             $.ajax({
                 url: '/get_param_dks/'+$('#table_date_start').val(),
                 method: 'GET',
                 success: function (res) {
                     var body = document.getElementById('time_id')
                     body.innerText = ''
                     for (var i=1; i<=Object.values(res['name']).length; i++){
                         var tr=document.createElement('tr')
                         tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res['name'][i]}</p></td>`
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res['pressure'][i]['val']}</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res['stepen'][i]['val']}</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>6</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['job'][i]['val']).toFixed(0)}</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['reserv'][i]['val']).toFixed(0)}</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         try {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['repair'][i]['val']).toFixed(0)}</p></td>`
                         }catch (e){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                         }
                         body.appendChild(tr);
                     }

                 },
                 async:true
             })
        }




    </script>
    <style>
        .create_td{
            background-color: white;
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

        input{

        }

        thead th {
            position: sticky;
        }
        .date_input{
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

        .date-input-group{
            /*width: 30%;*/
            margin: 8px 5px;
            display: table-cell;
            padding-left: 5px;
            padding-right: 10px;
            float:left;
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
