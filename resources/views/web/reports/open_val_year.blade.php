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
        <h3>Балансовый отчет ПХГ (за год)</h3>
        @include('include.choice_year_for_val')

        <button id="setting" class="button button1" style=" margin-top: 1%">Настройка</button>
    </div>

    <div id="content-header" style="display: inline-flex; width: 100%;">
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
    <div id="tableDiv_yams" style="display: none;  overflow-x: auto; width: 100%">

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
        <table id="itemInfoTable_yams" class="itemInfoTable"
               style="width: calc(100% - 185px); float:left; overflow-x: auto; display: block; white-space: nowrap">
            <thead>
            <tr>
                <th class="timeCell" style="width: 8%"><h4>Январь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Февраль</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Март</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Апрель</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Май</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Июнь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Июль</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Август</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Сентябрь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Октябрь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Ноябрь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Декабрь</h4></th>
                <th class="timeCell" style="width: 8%"><h4>Год</h4></th>
            </tr>
            </thead>
            <tbody>

                <tr id="fakt_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                    onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(1)').style.fontWeight='normal'; this.style.fontWeight='normal'">
                </tr>

                <tr id="out_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='bold'; this.style.fontWeight='bold'"
                    onmouseleave="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(2)').style.fontWeight='normal'; this.style.fontWeight='normal'">
                </tr>

                <tr id="self_yams_tr" onmouseover="document.querySelector('#statickItemInfoTable_yams tbody tr:nth-child(3)').style.fontWeight='bold'; this.style.fontWeight='bold'"
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

            $('#year').change(function () {
                get_table_data();
                $('#table_yams').trigger('click')
                // remove_chart()
            })
            get_table_data();
            $('#print_yams').click(function () {
                window.location.href = '/print_val/' + $('#year').val() + '/year/yams'
            });

            $('#setting').click(function () {
                window.location.href = '/valoviy_setting'
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

        function save_month_plan(id, text) {
            var month = Number(id.split('_')[0])
            var obj = id.split('_')[1]
            if (month < 10) {
                month = '0' + month
            }
            if (isNaN(Number(text))) {
                open_modal_ober('Неверный формат числа!')
                get_table_data()
            } else {
                $.ajax({
                    url: '/save_plan_month/' + $('#year').val() + '-' + month + '/' + Number(text) + '/' + obj,
                    method: 'GET',
                    success: function (res) {
                        get_table_data()
                    },
                    async: false
                })
            }
        }

        function save_month_fact(span) {
            var month = span.getAttribute('data-month')
            var obj = span.getAttribute('data-obj')
            if (isNaN(Number(span.textContent))) {
                open_modal_ober('Неверный формат!')
            } else {
                $.ajax({
                    url: '/save_fact_month/' + $('#year').val() + '/' + month + '/' + obj + '/' + span.textContent,
                    method: 'GET',
                    success: function (res) {
                        get_table_data()
                    },
                    async: false
                })
            }
        }

        function get_table_data() {

            $.ajax({
                url: '/get_val/' + $('#year').val() + '/year',
                method: 'GET',
                success: function (res) {
                    console.log(res)
                    var type = 'yams'
                    $.ajax({
                        url: '/get_plan/' + $('#year').val() + '/year/' + type,
                        method: 'GET',
                        success: function (res) {
                            var tr_plan = document.getElementById('plan_' + type + '_tr')
                            tr_plan.innerText = ''
                            var buff_plan = 0
                            for (var i = 1; i < 13; i++) {
                                var td_plan = document.createElement('td')
                                td_plan.innerHTML += `<span class="plan_month" id="${i + '_' + type}" contenteditable="true" style="background-color: white">${Number(res[i]).toFixed(3)}</span>`
                                buff_plan += Number(res[i])
                                tr_plan.appendChild(td_plan);
                            }
                            td_plan = document.createElement('td')
                            td_plan.innerHTML += `<span id="year_${type}">${buff_plan.toFixed(3)}</span>`
                            tr_plan.appendChild(td_plan);
                        },
                        async: false
                    })

                    ///заполнение таблицы факт
                    var tr = document.getElementById('fakt_' + type + '_tr')
                    var tr_out = document.getElementById('out_' + type + '_tr')
                    var tr_self = document.getElementById('self_' + type + '_tr')
                    var tr_lost = document.getElementById('lost_' + type + '_tr')
                    var tr_tovar = document.getElementById('tovar_' + type + '_tr')
                    var tr_otkl = document.getElementById('otkl_' + type + '_tr')
                    tr.innerText = ''
                    tr_out.innerText = ''
                    tr_self.innerText = ''
                    tr_lost.innerText = ''
                    tr_tovar.innerText = ''
                    tr_otkl.innerText = ''
                    var buff_fakt = 0
                    var buff_out = 0
                    var buff_self = 0
                    var buff_lost = 0
                    var buff_tovar = 0
                    for (var j = 1; j <= Object.keys(res[type]).length; j++) {
                        var last_fakt = 0
                        var last_out = 0
                        var last_self = 0
                        var last_lost = 0
                        var last_tovar = 0
                        var td = document.createElement('td')
                        if (res[type][j] !== '...') {
                            buff_fakt += Number(res[type][j])
                            last_fakt = Number(res[type][j])
                            td.innerHTML += `<span contenteditable="true" style="background-color: white" data-obj="fact_${type}" data-month="${j}" onblur="save_month_fact(this)">${res[type][j]}</span>`
                        } else {
                            td.innerHTML += `<span contenteditable="true" style="background-color: white" data-obj="fact_${type}"  data-month="${j}" onblur="save_month_fact(this)">0.000</span>`
                        }
                        tr.appendChild(td);
                        var td_out = document.createElement('td')
                        if (res[type + '_out'][j] !== '...') {
                            buff_out += Number(res[type + '_out'][j])
                            last_out = Number(res[type + '_out'][j])
                            td_out.innerHTML += `<span>${Number(res[type + '_out'][j]).toFixed(3)}</span>`
                        } else {
                            td_out.innerHTML += `<span>${res[type + '_out'][j]}</span>`
                        }
                        tr_out.appendChild(td_out)

                        var td_self = document.createElement('td')
                        if (res[type + '_self'][j] !== '...') {
                            buff_self += Number(res[type + '_self'][j])
                            last_self = Number(res[type + '_self'][j])
                            td_self.innerHTML += `<span>${Number(res[type + '_self'][j]).toFixed(3)}</span>`
                        } else {
                            td_self.innerHTML += `<span>${res[type + '_self'][j]}</span>`
                        }
                        tr_self.appendChild(td_self)
                        var td_lost = document.createElement('td')
                        if (res[type + '_lost'][j] !== '...') {
                            buff_lost += Number(res[type + '_lost'][j])
                            last_lost = Number(res[type + '_lost'][j])
                            td_lost.innerHTML += `<span>${Number(res[type + '_lost'][j]).toFixed(3)}</span>`
                        } else {
                            td_lost.innerHTML += `<span>${res[type + '_lost'][j]}</span>`
                        }
                        tr_lost.appendChild(td_lost)
                        var td_tovar = document.createElement('td')
                        last_tovar = Number(last_fakt - last_lost - last_self - last_out)
                        buff_tovar += last_tovar
                        td_tovar.innerHTML += `<span>${last_tovar.toFixed(3)}</span>`
                        tr_tovar.appendChild(td_tovar);
                        var td_otkl = document.createElement('td')
                        td_otkl.innerHTML += `<span>${(Number(last_tovar) - Number(document.getElementById(j + '_' + type).textContent)).toFixed(3)}</span>`
                        tr_otkl.appendChild(td_otkl);

                        //Добавление всплывашек ППР
                        if (res[type + '_tooltip'][j] !== '') {
                            var span_plan = document.getElementById(j + '_' + type)
                            var id = span_plan.id
                            var text = span_plan.textContent
                            span_plan.parentNode.innerHTML = `<span class="plan_month tooltip" id="${id}" contenteditable="true" data-title="${'График ППР: &#13 &#10' + res[type + '_tooltip'][j].substring(0, res[type + '_tooltip'][j].length - 12)}" style="background-color: white">${Number(text).toFixed(3)}</span>`
                        }
                    }
                    td = document.createElement('td')
                    td.innerHTML += `<span>${buff_fakt.toFixed(3)}</span>`
                    tr.appendChild(td);
                    td_out = document.createElement('td')
                    td_out.innerHTML += `<span>${buff_out.toFixed(3)}</span>`
                    tr_out.appendChild(td_out)
                    td_self = document.createElement('td')
                    td_self.innerHTML += `<span>${buff_self.toFixed(3)}</span>`
                    tr_self.appendChild(td_self);
                    td_lost = document.createElement('td')
                    td_lost.innerHTML += `<span>${buff_lost.toFixed(3)}</span>`
                    tr_lost.appendChild(td_lost);
                    td_tovar = document.createElement('td')
                    td_tovar.innerHTML += `<span>${buff_tovar.toFixed(3)}</span>`
                    tr_tovar.appendChild(td_tovar);
                    td_otkl = document.createElement('td')
                    td_otkl.innerHTML += `<span>${(buff_tovar - Number(document.getElementById('year_' + type).textContent)).toFixed(3)}</span>`
                    tr_otkl.appendChild(td_otkl);

                    document.getElementById('tableDiv_yams').style.display = 'inline-block'
                },
                async: false
            })
            $(".plan_month").on('focusout', function () {
                save_month_plan(this.id, this.textContent);
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
            for (var i = 0; i < 12; i++) {
                if (tr_fakt[i].textContent === '...') {
                    data_fact.push('0')
                } else {
                    data_fact.push(tr_fakt[i].textContent)
                }
            }
            var data_plan = []
            var tr_plan = document.getElementById('plan_' + mesto + '_tr').getElementsByTagName('span')
            for (var i = 0; i < 12; i++) {
                if (tr_plan[i].textContent === '...') {
                    data_plan.push('0')
                } else {
                    data_plan.push(tr_plan[i].textContent)
                }
            }
            var x_axix = []
            var th_table = document.getElementById('itemInfoTable_' + mesto).getElementsByTagName('th')
            for (var i = 0; i < 12; i++) {
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
                    height: 316,
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
    </style>


@endsection
