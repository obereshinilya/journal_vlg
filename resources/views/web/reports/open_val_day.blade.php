@extends('layouts.app')
@section('title')
    Балансовый отчет
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
        <h3>Балансовый отчет ПХГ (за сутки)</h3>
        <div class="date-input-group" style="margin-left: 2%">
            <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
            <label for="table_date_start" class="table_date_label">Дата</label>
        </div>
    </div>
    {{--    @include('include.choice_date')--}}
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <p id="plan_yams" style="display: none"></p>
    <p id="plan_yub" style="display: none"></p>
    <p id="plan_nngdu" style="display: none"></p>

    <div id="content-header" style="display: inline-flex; width: 100%">
        <h4 style="width: 30%">Показатели Волгоградского ПХГ</h4>
        <button id="graph_yams" class="button button1" style="margin-left: 40%">Графический вид</button>
        <button id="table_yams" class="button button1" disabled="true" style="background-color: rgb(26, 181, 133)">
            Табличный вид
        </button>
        <button id="print_yams" class="button button1" style="">Печать</button>

    </div>
    <div id="chart_yams" style="display: none; width: 100%">
        <div id="timeline-chart" style="width: 100%"></div>
    </div>
    <div id="tableDiv_yams" style="display: none; overflow-x: auto; width: 100%">

        <table id="statickItemInfoTable_yams" class="itemInfoTable"
               style="width: 185px; float:left; table-layout: fixed; display: block; overflow-x: auto; white-space: nowrap">
            <thead>
            <tr>
                <th class="objCell"><h4>Параметр</h4></th>
            </tr>
            <tbody>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Закачка</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Отбор</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(3)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(3)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Собств.нужды</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(4)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(4)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Тех.потери</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(5)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(5)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Товарный газ</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(6)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(6)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">План</span></td></tr>
            <tr onmouseover="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(7)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#itemInfoTable_yams tbody tr:nth-child(7)').style.fontWeight='normal'; this.style.fontWeight='normal'"><td><span style="text-align: left">Отклонение</span></td></tr>
            </tbody>
        </table>
        <table id="itemInfoTable_yams" class="itemInfoTable" style="width: calc(100% - 185px); float:left; overflow-x: auto; display: block; white-space: nowrap">
            <thead>
            <tr id="thead_yams">
                <th class="timeCell" style="width: 2%"><h4>13:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>14:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>15:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>16:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>17:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>18:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>19:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>20:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>21:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>22:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>23:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>00:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>01:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>02:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>03:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>04:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>05:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>06:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>07:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>08:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>09:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>10:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>11:00</h4></th>
                <th class="timeCell" style="width: 2%"><h4>12:00</h4></th>
            </tr>
            </thead>
            <tbody>
            <tr id="fakt_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="out_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="self_yams_tr"  onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(3)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(3)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="lost_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(4)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(4)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="tovar_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(5)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(5)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="plan_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(6)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(6)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            <tr id="otkl_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(7)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(7)').style.fontWeight='normal'; this.style.fontWeight='normal'">

            </tr>
            </tbody>
        </table>
    </div>

    <style>
        .content {
            overflow-x: hidden;
            width: 100%;
        }

    </style>

    <script>

        $(document).ready(function () {
            var today = new Date();
            if (today.getHours() < 12) {
                today.setDate(today.getDate() - 1)
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            } else {
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            }

            document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));
            $('#table_date_start').change(function () {
                get_plan()
                get_table_data();
                $('#table_yams').trigger('click')
            })
            get_plan()
            get_table_data();
            $('#print_yams').click(function () {
                window.location.href = '/print_val/' + $('#table_date_start').val() + '/day/yams'
            });


            $('#graph_yams').click(function () {
                document.getElementById('table_yams').style.background = 'white'
                document.getElementById('graph_yams').style.background = 'rgb(26, 181, 133)'
                document.getElementById('graph_yams').setAttribute('disabled', 'true')
                document.getElementById('table_yams').removeAttribute('disabled')

                document.getElementById('statickItemInfoTable_yams').style.display = 'none'
                document.getElementById('itemInfoTable_yams').style.display = 'none'
                document.getElementById('chart_yams').style.display = ''
                document.getElementById('chart_yams').style.maxWidth = '100%'
                document.getElementById('chart_yams').style.minHeight = '10%'
                create_chart('yams')
            });
            $('#table_yams').click(function () {
                document.getElementById('graph_yams').style.background = 'white'
                document.getElementById('table_yams').style.background = 'rgb(26, 181, 133)'
                document.getElementById('table_yams').setAttribute('disabled', 'true')
                document.getElementById('graph_yams').removeAttribute('disabled')

                document.getElementById('statickItemInfoTable_yams').style.display = 'block'
                document.getElementById('itemInfoTable_yams').style.display = 'block'
                document.getElementById('chart_yams').style.display = 'none'
                remove_chart('yams')
            });
        })

        function get_plan() {
            $.ajax({
                url: '/get_plan/' + $('#table_date_start').val().split('-')[0] + '-' + $('#table_date_start').val().split('-')[1] + '/month/yams_yub',
                method: 'GET',
                success: function (res) {
                    document.getElementById('plan_yams').textContent = Number(res['yams'])
                    document.getElementById('plan_yub').textContent = Number(res['yub'])
                    document.getElementById('plan_nngdu').textContent = Number(res['yams']) + Number(res['yub'])
                },
                async: false
            })
        }

        function get_table_data() {

            $.ajax({
                url: '/get_val/' + $('#table_date_start').val() + '/day',
                method: 'GET',
                success: function (res) {
                    var type = 'yams'
                    var plan = Number(document.getElementById('plan_' + type).textContent)
                    var kol_days = new Date($('#table_date_start').val().split('-')[0], $('#table_date_start').val().split('-')[1], 0).getDate()
                    plan = Math.round((100 * plan) / (kol_days * 24)) / 100
                    ///заполнение таблицы факт
                    var tr = document.getElementById('fakt_' + type + '_tr')
                    var tr_out = document.getElementById('out_' + type + '_tr')
                    var tr_self = document.getElementById('self_' + type + '_tr')
                    var tr_lost = document.getElementById('lost_' + type + '_tr')
                    var tr_tovar = document.getElementById('tovar_' + type + '_tr')
                    var tr_plan = document.getElementById('plan_' + type + '_tr')
                    var tr_otkl = document.getElementById('otkl_' + type + '_tr')
                    tr.innerText = ''
                    tr_out.innerText = ''
                    tr_plan.innerText = ''
                    tr_otkl.innerText = ''
                    tr_self.innerText = ''
                    tr_lost.innerText = ''
                    tr_tovar.innerText = ''
                    for (var j = 0; j < 24; j++) {
                        var last_fakt = 0
                        var last_out = 0
                        var last_self = 0
                        var last_lost = 0
                        var last_tovar = 0
                        //заполнение факта
                        var td = document.createElement('td')
                        tr.appendChild(td);
                        if (res[type][j] !== '...') {
                            last_fakt = Number(res[type][j]).toFixed(3)
                            td.innerHTML += `<span>${Number(res[type][j]).toFixed(3)}</span>`
                        } else {
                            td.innerHTML += `<span>${res[type][j]}</span>`
                        }

                        var td_out = document.createElement('td')
                        if (res[type + '_out'][j] !== '...') {
                            last_out = Number(res[type + '_out'][j]).toFixed(3)
                            td_out.innerHTML += `<span>${Number(res[type + '_out'][j]).toFixed(3)}</span>`
                        } else {
                            td_out.innerHTML += `<span>${res[type + '_out'][j]}</span>`
                        }
                        tr_out.appendChild(td_out)
                        //заполнение на собств
                        var td_self = document.createElement('td')
                        if (res[type + '_self'][j] !== '...') {
                            last_self = Number(res[type + '_self'][j]).toFixed(3)
                            td_self.innerHTML += `<span>${Number(res[type + '_self'][j]).toFixed(3)}</span>`
                        } else {
                            td_self.innerHTML += `<span>${res[type + '_self'][j]}</span>`
                        }
                        tr_self.appendChild(td_self)
                        var td_lost = document.createElement('td')
                        if (res[type + '_lost'][j] !== '...') {
                            td_lost.innerHTML += `<span>${Number(res[type + '_lost'][j]).toFixed(3)}</span>`
                            last_lost = Number(res[type + '_lost'][j])
                        } else {
                            td_lost.innerHTML += `<span>${res[type + '_lost'][j]}</span>`
                        }
                        tr_lost.appendChild(td_lost)
                        var td_tovar = document.createElement('td')
                        last_tovar = Number(last_fakt - last_lost - last_self - last_out)
                        td_tovar.innerHTML += `<span>${last_tovar.toFixed(3)}</span>`
                        tr_tovar.appendChild(td_tovar);
                        //заполнение плана
                        var td_plan = document.createElement('td')
                        var last_plan = Number(res['plan'][type]['hour'][j + 1]).toFixed(3)
                        td_plan.innerHTML += `<span>${last_plan}</span>`
                        tr_plan.appendChild(td_plan);

                        var td_otkl = document.createElement('td')
                        td_otkl.innerHTML += `<span>${Number(last_tovar - last_plan).toFixed(3)}</span>`
                        tr_otkl.appendChild(td_otkl);

                    }
                    document.getElementById('tableDiv_yams').style.display = 'inline-block'
                },
                async: false
            })
        }

        function remove_chart(type) {
            if (type === 'yams') {
                try {
                    chart_yams.destroy()
                } catch (e) {
                }
            } else if (type === 'yub') {
                try {
                    chart_yub.destroy()
                } catch (e) {

                }
            } else {
                try {
                    chart_nngdu.destroy()
                } catch (e) {

                }
            }
        }

        function create_chart(mesto) {

            var data_fact = []
            var tr_fakt = document.getElementById('fakt_' + mesto + '_tr').getElementsByTagName('span')
            for (var i = 0; i < tr_fakt.length; i++) {
                if (tr_fakt[i].textContent === '...') {
                    data_fact.push('0')
                } else {
                    data_fact.push(tr_fakt[i].textContent)
                }
            }
            var data_plan = []
            var tr_plan = document.getElementById('plan_' + mesto + '_tr').getElementsByTagName('span')
            for (var i = 0; i < tr_fakt.length; i++) {
                if (tr_plan[i].textContent === '...') {
                    data_plan.push('0')
                } else {
                    data_plan.push(tr_plan[i].textContent)
                }
            }
            var x_axix = []
            var th_table = document.getElementById('itemInfoTable_' + mesto).getElementsByTagName('th')
            for (var i = 0; i < th_table.length; i++) {
                x_axix.push(th_table[i].textContent)
            }

            var options = {
                series: [{
                    name: 'Закачка',
                    data: data_fact
                }, {
                    name: 'План',
                    data: data_plan
                }],
                chart: {
                    height: 326,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'string',
                    categories: x_axix
                },
            };
            if (mesto === 'yams') {
                chart_yams = new ApexCharts(document.querySelector("#chart_" + mesto), options);
                chart_yams.render();
            } else if (mesto === 'yub') {
                chart_yub = new ApexCharts(document.querySelector("#chart_" + mesto), options);
                chart_yub.render();
            } else {
                chart_nngdu = new ApexCharts(document.querySelector("#chart_" + mesto), options);
                chart_nngdu.render();
            }

            // document.getElementById('chart').style.margin = ''
        }


    </script>
    @include('include.font_size-change')
    <style>

        #statickItemInfoTable_yams tr:nth-child(odd){background: #e9effc;}
        #itemInfoTable_yams tr:nth-child(odd){background: #e9effc;}

        h4{
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .itemInfoTable span, .itemInfoTable thead th {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .create_td {
            background-color: white;
        }
        tr:hover{
            font-weight: bold;
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

        #chart {
            max-width: 760px;
            margin: 35px auto;
            opacity: 0.9;
        }

        #timeline-chart .apexcharts-toolbar {
            opacity: 1;
            border: 0;
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
