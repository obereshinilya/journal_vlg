@extends('layouts.app')
@section('title')
    Отчет по ТЭР
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
	<style>
		.table_ober tbody td span {
			padding-top:1px; padding-bottom: 1px;
		}
	</style>
    <p style="display: none" id="yams_yub">{{$yams_yub}}</p>
    <div style="display: inline-flex; width: 100%">
        <h3 style="width: 20%">Отчет по ТЭР Уренгойского ГПУ</h3>
        <button  id="day" onclick="choice_period('day')" class="button button1" style="margin-left: 5%; margin-top: 1%">Сутки</button>
        <button  id="month" onclick="choice_period('month')" class="button button1" style="margin-left: 1%; margin-top: 1%">Месяц</button>
        <button  id="year" onclick="choice_period('year')" class="button button1" style="margin-left: 1%; margin-top: 1%">Год</button>

        <button  id="print" class="button button1" style="margin-left: 52%; margin-top: 1%">Печать</button>
    </div>
    <div style="display: inline-flex; width: 100%">
        <div class="date-input-group" style="display: none">
            <input type="date" id="table_date_start_day" class="date_input" required onkeydown="return false">
        </div>
        <div class="date-input-group"  style="display: none">
            <input type="month" id="table_date_start_month" class="date_input" required onkeydown="return false">
        </div>
        <div class="date-input-group" style="display: none">
            <select class="date_input" id="table_date_start_year"></select>
        </div>
    </div>
    <div style="display:inline-flex; width: 80%; margin-top: 1%">
        <div>
            <table class="itemInfoTable">
                <tbody>
                <tr>
                    <th><span>Расход метанола</span></th>
                    <td><span id="rash_met" contenteditable="true" style="background-color: white"></span></td>
                    <th><span>Приход метанола</span></th>
                    <td><span id="prih_met" contenteditable="true" style="background-color: white"></span></td>
                    <th><span>Расход ТЭГ</span></th>
                    <td><span id="rash_teg" contenteditable="true" style="background-color: white"></span></td>
                    <th><span>Приход ТЭГ</span></th>
                    <td><span id="prih_teg" contenteditable="true" style="background-color: white"></span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <button  id="save" class="button button1" style="margin-left: 2%; margin-top: 0.7%">Сохранить</button>
    </div>



    <div id="content-header" style="display: inline-flex; width: 100%">
    </div>

    <div id="tableDiv" style="height: 640px; width: 100%; overflow-y: auto">
        <table id="statickItemInfoTable" class="itemInfoTable table_ober" style="width: 100%; table-layout: fixed; display: table; overflow-y: auto">

            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <thead>
            <tr>
                <th style="text-align: center" id="th_text_time">Время</th>
                <th style="text-align: center">Метанол запас (на начало)</th>
                <th style="text-align: center">Метанол расход</th>
                <th style="text-align: center">Метанол приход</th>
                <th style="text-align: center">Метанол запас (на конец)</th>
                <th style="text-align: center">ТЭГ запас (на начало)</th>
                <th style="text-align: center">ТЭГ расход</th>
                <th style="text-align: center">ТЭГ приход</th>
                <th style="text-align: center">ТЭГ запас (на конец)</th>
            </tr>
            </thead>
            <tbody id="table_body">
            <tr>
                <td colspan="10" ><span>Данных нет</span></td>
            </tr>
            </tbody>
        </table>

    </div>



    <script>

        $(document).ready(function () {
            //для выбора даты
            var today_day = new Date();
            $('#table_date_start_day').val(today_day.toISOString().substring(0, 10))
            document.getElementById("table_date_start_day").setAttribute("max", today_day.toISOString().substring(0, 10));
            var today_month = moment(new Date()).format('YYYY-MM');
            $('#table_date_start_month').val(today_month);
            document.getElementById("table_date_start_month").setAttribute("max", today_month);
            var start_date =moment(new Date()).format('YYYY')
            for (let year = 2021; year <= start_date; year++) {
                let options = document.createElement("OPTION");
                if (year == start_date){
                    options.setAttribute ("selected", true);
                }
                document.getElementById("table_date_start_year").appendChild(options).innerHTML = year;
            }

            document.getElementById('month').click()

            $('#table_date_start_day').change(function () {
                get_table_data('day', $('#table_date_start_day').val(), document.getElementById('yams_yub').textContent);
            })
            $('#table_date_start_month').change(function () {
                get_table_data('month', $('#table_date_start_month').val(), document.getElementById('yams_yub').textContent);
            })
            $('#table_date_start_year').change(function () {
                get_table_data('year', $('#table_date_start_year').val(), document.getElementById('yams_yub').textContent);
            })
            $('#print').click(function() {
                if (document.getElementById('day').style.background != 'white'){
                    window.location.href = '/print_ter/'+$('#table_date_start_day').val()+'/day/'+document.getElementById('yams_yub').textContent
                }
                if (document.getElementById('month').style.background != 'white'){
                    window.location.href = '/print_ter/'+$('#table_date_start_month').val()+'/month/'+document.getElementById('yams_yub').textContent
                }
                if (document.getElementById('year').style.background != 'white'){
                    window.location.href = '/print_ter/'+$('#table_date_start_year').val()+'/year/'+document.getElementById('yams_yub').textContent
                }
            });
            $('#save').click(function() {
                var arr = new Map()
                arr.set('metanol_prihod', document.getElementById('prih_met').textContent)
                arr.set('metanol_rashod', document.getElementById('rash_met').textContent)
                arr.set('teg_prihod', document.getElementById('prih_teg').textContent)
                arr.set('teg_rashod', document.getElementById('rash_teg').textContent)
                arr.set('yams_yub', 'yams')
                var data = Object.fromEntries(arr)
                $.ajax({
                    url: '/save_ter/'+document.getElementById('yams_yub').textContent,
                    data: data,
                    method: 'POST',
                    success: function (res) {
                        document.getElementById('prih_met').textContent = ''
                        document.getElementById('rash_met').textContent = ''
                        document.getElementById('prih_teg').textContent = ''
                        document.getElementById('rash_teg').textContent = ''
                        // get_table_data('day', $('#table_date_start_day').val())
                        document.getElementById('day').click()
                        open_modal_ober('Данные сохранены!')
                    },
                    async:true
                })
            });

        })


        function choice_period(period){
            if (period === 'day'){
                document.getElementById('day').style.background = '#1ab585'
                document.getElementById('month').style.background = 'white'
                document.getElementById('year').style.background = 'white'
                document.getElementById('table_date_start_day').parentNode.style.display = ''
                document.getElementById('table_date_start_month').parentNode.style.display = 'none'
                document.getElementById('table_date_start_year').parentNode.style.display = 'none'
                get_table_data('day', $('#table_date_start_day').val(), document.getElementById('yams_yub').textContent)
            }
            if(period === 'month'){
                document.getElementById('day').style.background = 'white'
                document.getElementById('month').style.background = '#1ab585'
                document.getElementById('year').style.background = 'white'
                document.getElementById('table_date_start_day').parentNode.style.display = 'none'
                document.getElementById('table_date_start_month').parentNode.style.display = ''
                document.getElementById('table_date_start_year').parentNode.style.display = 'none'
                get_table_data('month', $('#table_date_start_month').val(), document.getElementById('yams_yub').textContent)
            }
            if (period === 'year'){
                document.getElementById('day').style.background = 'white'
                document.getElementById('month').style.background = 'white'
                document.getElementById('year').style.background = '#1ab585'
                document.getElementById('table_date_start_day').parentNode.style.display = 'none'
                document.getElementById('table_date_start_month').parentNode.style.display = 'none'
                document.getElementById('table_date_start_year').parentNode.style.display = ''
                get_table_data('year', $('#table_date_start_year').val(), document.getElementById('yams_yub').textContent)
            }
        }



        function get_table_data(type, date, yams_yub) {
            $.ajax({
                url: '/get_ter/'+date+'/'+type+'/'+yams_yub,
                method: 'GET',
                success: function (res) {
                    if (type==='day')
                        document.getElementById('th_text_time').textContent = 'Время'
                    if (type==='month')
                        document.getElementById('th_text_time').textContent = 'День'
                    if (type==='year')
                        document.getElementById('th_text_time').textContent = 'Месяц'
                    var table_body = document.getElementById('table_body')
                    table_body.innerText = ''
                    if (res.length){
                        var i = 0;
                        var j = 0;
                        var begin_year_metanol = 0;
                        var last_year_metanol = 0;
                        var begin_year_teg = 0;
                        var last_year_teg = 0;
                        var buff_metanol_rash = 0;
                        var buff_teg_rash = 0;
                        var buff_metanol_prih = 0;
                        var buff_teg_prih = 0;
                        for (var row of res){
                            var tr = document.createElement('tr')
                            try {
                                tr.innerHTML += `<td><span style="text-align: center">${row['timestamp'].split('.')[0]}</span></td>`
                            }catch (e){
                                tr.innerHTML += `<td><span style="text-align: center">${row['timestamp']}</span></td>`
                            }
                            if (Number (row['metanol_rashod']) || Number (row['metanol_prihod'])|| Number (row['metanol_zapas'])){
                                tr.innerHTML += `<td><span style="text-align: center">${Number(Number (row['metanol_zapas']) + Number (row['metanol_rashod']) - Number (row['metanol_prihod'])).toFixed(3)}</span></td>`
                                if (i===0){
                                    begin_year_metanol = Number(Number (row['metanol_zapas']) + Number (row['metanol_rashod']) - Number (row['metanol_prihod'])).toFixed(3)
                                    i++
                                }
                            }else{
                                tr.innerHTML += `<td><span style="text-align: center"></span></td>`
                            }
                            if (row['metanol_rashod']){
                                tr.innerHTML += `<td><span>${Number(row['metanol_rashod']).toFixed(3)}</span></td>`
                                buff_metanol_rash +=row['metanol_rashod']
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['metanol_prihod']){
                                buff_metanol_prih+=row['metanol_prihod']
                                tr.innerHTML += `<td><span>${Number(row['metanol_prihod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['metanol_zapas']){
                                last_year_metanol = row['metanol_zapas']
                                tr.innerHTML += `<td><span>${Number(row['metanol_zapas']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (Number (row['teg_rashod']) || Number (row['teg_prihod']) || Number (row['teg_zapas'])){
                                tr.innerHTML += `<td><span style="text-align: center">${Number(Number (row['teg_zapas']) + Number (row['teg_rashod']) - Number (row['teg_prihod'])).toFixed(3)}</span></td>`
                                if (j===0){
                                    begin_year_teg = Number(Number (row['teg_zapas']) + Number (row['teg_rashod']) - Number (row['teg_prihod'])).toFixed(3)
                                    j++
                                }
                            }else {
                                tr.innerHTML += `<td><span style="text-align: center"></span></td>`

                            }
                            if (row['teg_rashod']){
                                buff_teg_rash+=row['teg_rashod']
                                tr.innerHTML += `<td><span>${Number(row['teg_rashod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['teg_prihod']){
                                buff_teg_prih+=row['teg_prihod']
                                tr.innerHTML += `<td><span>${Number(row['teg_prihod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['teg_zapas']){
                                last_year_teg = Number(row['teg_zapas']).toFixed(3)
                                tr.innerHTML += `<td><span>${Number(row['teg_zapas']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (type==='day'){
                                for (var td of tr.getElementsByTagName('td')){
                                    td.classList.add('tooltip')
                                    td.style.zIndex = '100'
                                    if (res['user']){
                                        td.setAttribute('data-title', row['user'])
                                    }else {
                                        td.setAttribute('data-title', 'AdminQA')
                                    }
                                }
                            }
                            table_body.appendChild(tr);
                        }
                        if (type === 'year' || type === 'month'){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td><span style="text-align: center"><b>Итого за период:</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(begin_year_metanol).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_metanol_rash).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_metanol_prih).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(last_year_metanol).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(begin_year_teg).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_teg_rash).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_teg_prih).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(last_year_teg).toFixed(3)}</b></span></td>`
                            table_body.appendChild(tr);
                        }
                    } else {
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td colspan="9"><span  style="text-align: center">Данных нет</span></td>`
                        table_body.appendChild(tr);
                    }
                },
                async:false
            })
        }

    </script>
    <style>
        .tooltip:before{
            bottom: 0px;
            left: 0%;
            margin-left: -80px;
            padding: 10px;
            width: 160px;
        }
        .itemInfoTable span{
            text-align: center;
        }
        .content {
            overflow-x: hidden;
            width: 100%;
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
