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
            <p id="dks_number" style="display: none">{{$dks}}</p>
            <h3 >Режимы работы турбоагрегатов на ДКС ГКП-{{$gkp}}</h3>
            <div style="position: absolute; right: 11%">
                <button  id="print" class="button button1">Печать</button>
            </div>
        </div>
    @include('include.choice_date')
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>



    <div id="tableDiv" style="width: 100%; height: 720px">
        <table id="statickItemInfoTable" class="itemInfoTable" style="">
            <thead>
            <tr style="position: sticky; top: 0; z-index: 6">
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>№ ГПА</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Режим<br> работы</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Мокв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Мокв <br>общ</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Об ТВД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Прив <br>об ТВД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Об ТНД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвх</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвых</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твозд<br> ВхДв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвозд<br> ВхДв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твх</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твых</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Qтг</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Ст<br> Сж</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Qцбн</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Таво</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твозд</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Q</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Ркол</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Тподш<br> ЦБН</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Т <br>г/г</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Запас</h4></th>
                <th class="buff" style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рбуф</h4></th>
            </tr>
            </thead>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>16:00</b></td>
            </tr>
            <tbody id="data16">

            </tbody>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>20:00</b></td>
            </tr>
            <tbody id="data20">

            </tbody>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>00:00</b></td>
            </tr>
            <tbody id="data00">

            </tbody>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>04:00</b></td>
            </tr>
            <tbody id="data04">

            </tbody>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>08:00</b></td>
            </tr>
            <tbody id="data08">

            </tbody>
            <tr>
                <td colspan="24" style="background-color: #e4e4e4"><b>12:00</b></td>
            </tr>
            <tbody id="data12">

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
            $('#table_date_start').val(today.toISOString().substring(0, 10))
            get_table_data()
            document.getElementById('table_date_start')
            $('#table_date_start').change(function () {
                get_table_data();
            })

            $('#print').click(function() {
                window.location.href = '/print_gpa_rezhim_report/'+$('#table_date_start').val()+'/'+document.getElementById('dks_number').textContent
            });
        })

         function get_table_data() {

             $.ajax({
                url: '/get_gpa_rezhim_report_data/'+$('#table_date_start').val()+'/'+document.getElementById('dks_number').textContent,
                method: 'GET',
                success: function (res) {
                    var time_id = ''
                    for (var j = 1; j<7; j++){
                        if (j === 1){
                            time_id = 'data16'
                        } else if (j === 2){
                            time_id = 'data20'
                        }else if (j === 3){
                            time_id = 'data00'
                        }else if (j === 4){
                            time_id = 'data04'
                        }else if (j === 5){
                            time_id = 'data08'
                        }else if (j === 6){
                            time_id = 'data12'
                        }

                        var body = document.getElementById(time_id)
                        body.innerText = ''
                        if (res[time_id].length){
                            for (var i of res[time_id]) {
                                var tr=document.createElement('tr')
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['number_gpa']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['rezhim']}<br>${i['time_rezhim']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['mokveld_status']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['mokveld_zadanie']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['tvd']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['priv_tvd']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['tnd']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pin']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pout']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tvdv']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pvdv']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tin']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tout']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Qtg']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['St_sj']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Qcbn']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tavo']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tvozd']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['q']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pkol']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tpodsh']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tgg']}</p></td>`
                                tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Zapas']}</p></td>`
                                tr.innerHTML+=`<td class="buff" style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pbuf']}</p></td>`
                                body.appendChild(tr);
                            }
                        } else {
                            var tr=document.createElement('tr')
                            tr.innerHTML+=`<td colspan="24">Данных нет</td>`
                            body.appendChild(tr);
                        }
                    }
                    if (document.getElementById('dks_number').textContent === '2'){
                        var buff = document.getElementsByClassName('buff')
                        for(var i=0; i<buff.length; i++)buff[i].style.display='none';
                    } else {
                        var buff = document.getElementsByClassName('buff')
                        for(var i=0; i<buff.length; i++)buff[i].style.display='';
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
    </style>


@endsection
