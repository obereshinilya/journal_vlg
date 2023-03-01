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
            <h3 >Данные от смежных систем</h3>
            <div style="position: absolute; right: 11%">
                <button  id="print" class="button button1">Печать</button>
            </div>
        </div>
    @include('include.choice_month')
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>



    <div id="tableDiv" style="width: 40%">
        <table id="statickItemInfoTable" class="itemInfoTable" style="">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center">Наименование параметра</th>
                    <th style="width: 20%; text-align: center">Значение</th>
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
            $('#table_date_start').change(function () {
                get_table_data();
            })
            get_table_data()

		setInterval(get_table_data, 10000);
            $('#print').click(function() {
                window.location.href = '/print_balans/'+$('#table_date_start').val()
            });
        })

         function get_table_data() {
             $.ajax({
                 url: '/get_balans/'+$('#table_date_start').val(),
                 method: 'GET',
                 success: function (res) {
                     var body = document.getElementById('time_id')
                     body.innerText = ''

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Потери</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['poteri']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход валовый</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_val']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход газа (объем)</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_gaz']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход на собственные нужды</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_sobstv']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход сторонние организации</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['stor']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход товарный</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_tov']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p><b>Итого</b></p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['sum']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td colspan="2" style="text-align: center; padding: 0px; min-width: 20px"><p>Расход ТЭР</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход метанола</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_metanol']).toFixed(3)}</p></td>`
                     body.appendChild(tr);

                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход ТЭГ</p></td>`
                     tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_teg']).toFixed(3)}</p></td>`
                     body.appendChild(tr);
                     var tr=document.createElement('tr')
                     tr.innerHTML+=`<td ><p><b>Итого ТЭР</b></p></td>`
                     tr.innerHTML+=`<td style="text-align: center"><p>${Number(res['sum_ter']).toFixed(3)}</p></td>`
                     body.appendChild(tr);
                     //
                     // var tr=document.createElement('tr')
                     // tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Потери газа при добыче</p></td>`
                     // tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res['lost']}</p></td>`
                     // body.appendChild(tr);
                     //
                     // var tr=document.createElement('tr')
                     // tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p><b>Всего добыто</b></p></td>`
                     // tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p><b>${res['all']}</b></p></td>`
                     // body.appendChild(tr);



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
    </style>


@endsection
