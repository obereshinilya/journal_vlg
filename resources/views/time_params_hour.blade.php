@extends('layouts.app')
@section('title')
    Временные показатели
@endsection

@section('side_menu')
    @include('include.side_menu')
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

    <div style="width: 100%">
        <table style="display: table; width: 100%; table-layout: fixed; margin-top: 10px">
            <colgroup>
                <col style="width: 10%">
                <col style="width: 15%">
                <col style="width: 75%">
            </colgroup>
            <tbody>
            <tr>
                <td>@include('include.choice_date')</td>
                <td style="vertical-align: bottom">@include('include.search_row')</td>
                <td>
                    <button class="button button1" style="float: right"
                            onclick="localStorage.setItem('to_month', $('#table_date_start').val().slice(0, -3));  window.location.href = '/sut'">
                        К суточным
                    </button>
                </td>
                <td>
                    <button class="button button1" style="float: right; margin-right: 130px"
                            onclick="open_modal_export_ober()">Экспорт
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="content-header"></div>

    <div id="tableDiv">
        <table id="statickItemInfoTable" class="itemInfoTable"
               style="width: 25%; float:left; direction:rtl; table-layout: fixed">
            <thead>
            <tr>
                <th class="objCell"
                    style="width: 2%; padding-left: 0px; padding-right: 0px; text-align: center; z-index: 2"><h4>Ед.
                        изм</h4></th>
                <th class="objCell" style="z-index: 2"><h4>Наименование параметра</h4></th>
            </tr>
            <tbody class="with_selector_statick">

            </tbody>
        </table>
        <table id="itemInfoTable" class="itemInfoTable" style="width: 74%; float:left">
            <thead>
            <tr>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="10:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="1">
                    <h4>10:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="11:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="2">
                    <h4>11:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="12:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="3">
                    <h4>12:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="13:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="4">
                    <h4>13:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="14:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="5">
                    <h4>14:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="15:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="6">
                    <h4>15:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="16:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="7">
                    <h4>16:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="17:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="8">
                    <h4>17:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="18:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="9">
                    <h4>18:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="19:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="10">
                    <h4>19:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="20:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="11">
                    <h4>20:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="21:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="12">
                    <h4>21:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="22:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="13">
                    <h4>22:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="23:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="14">
                    <h4>23:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="00:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="15">
                    <h4>00:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="01:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="16">
                    <h4>01:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="02:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="17">
                    <h4>02:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="03:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="18">
                    <h4>03:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="04:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="19">
                    <h4>04:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="05:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="20">
                    <h4>05:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="06:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="21">
                    <h4>06:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="07:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="22">
                    <h4>07:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="08:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="23">
                    <h4>08:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
                <th class="timeCell" onclick="goToHour(this)" style="width: 4%" data-time="09:00"
                    oncontextmenu="all_param_accepted(this)" data-time-id="24">
                    <h4>09:00
                        <img src="assets/images/icons/accept_ober.png" style="height: 14px; display: none"/>
                    </h4>
                </th>
            </tr>
            </thead>
            <tbody class="with_selector">

            </tbody>
        </table>
    </div>


    <style>
        #tableDiv {
            height: calc(100% - 125px);
        }

        .itemInfoTable thead th {
            padding: 1px 1px;
            text-align: center;
        }

        #content-header h3 {
            margin: 10px 0px;
        }

        .itemInfoTable span, .itemInfoTable select {
            padding: 5px 5px;
            width: calc(100% - 10px);
        }

        th h4 {
            text-align: center;
            margin-right: 0px;
        }

        .itemInfoTable tbody td {
            min-width: 5px;
        }
    </style>

    <script>
        var header_content = 'Часовые показатели.  ';
        var datatable = null;
        $(document).ready(function () {
            ///Проверка смены
            check_smena()
/////Объединяем скролы двух таблиц
            $("#itemInfoTable").scroll(function () {
                $('#statickItemInfoTable').scrollTop($("#itemInfoTable").scrollTop());
            });
            $("#statickItemInfoTable").scroll(function () {
                $('#itemInfoTable').scrollTop($("#statickItemInfoTable").scrollTop());
            });
/////Выбор диспетчерских суток
            var today = new Date();
            if (today.getHours() < 10) {
                today.setDate(today.getDate() - 1)
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            } else {
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            }
/////Проверяем заходим ли мы по ссылке
            if (localStorage.getItem('day')) {
                $('#table_date_start').val(localStorage.getItem('year') + '-' + localStorage.getItem('month') + '-' + localStorage.getItem('day'))
                for (let localStorageKey in localStorage) {
                    if (localStorageKey != 'font') {
                        localStorage.removeItem(localStorageKey)
                    }
                }

            } else if (localStorage.getItem('to_sut')) {
                $('#table_date_start').val(localStorage.getItem('to_sut'))
                for (let localStorageKey in localStorage) {
                    if (localStorageKey != 'font') {
                        localStorage.removeItem(localStorageKey)
                    }
                }
            } else {

            }
            get_table_data()
            mouseenter_func()

            document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));
            $('#table_date_start').change(function () {
                get_table_data()
                mouseenter_func()
            })
        })

        function mouseenter_func(){
            // $(this).toggleClass('selected_td');   // присваиваем класс

            // $('.selected_td').toggleClass('selected_td')   //очищаем класс у всех

            $('#itemInfoTable td')
                .mousedown(function(){
                    localStorage.setItem('start_td_row', this.parentNode.rowIndex)   //нумерация с 1
                    localStorage.setItem('start_td_cell', this.cellIndex)           //нумерация с 1
                    $('td').on('mouseenter',function(){
                        localStorage.setItem('stop_td_row', this.parentNode.rowIndex)
                        localStorage.setItem('stop_td_cell', this.cellIndex)
                        mark_region()
                    });
                })
                .mouseup(function(){
                    print_region()
                    $('td').off('mouseenter');
                });

            function mark_region(){
                var start_td_cell = Number(localStorage.getItem('start_td_cell'))
                var start_td_row = Number(localStorage.getItem('start_td_row'))
                var stop_td_cell = Number(localStorage.getItem('stop_td_cell'))
                var stop_td_row = Number(localStorage.getItem('stop_td_row'))
                if (start_td_row > stop_td_row){  //ведем свнизу вверх
                    for (var row=stop_td_row; row<=start_td_row; row++){
                        var real_row = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[row-1]
                        if (start_td_cell > stop_td_cell){  ///ведем слева направо
                            for (var cell=stop_td_cell; cell<=start_td_cell; cell++){
                                var real_cell = real_row.getElementsByTagName('td')[cell-1]
                                real_cell.classList.add('selected_td')
                            }
                        }else { ///ведем справа налево
                            for (var cell=start_td_cell; cell<=stop_td_cell; cell++){
                                var real_cell = real_row.getElementsByTagName('td')[cell-1]
                                real_cell.classList.add('selected_td')
                            }
                        }
                    }
                }else {
                    for (var row=start_td_row; row<=stop_td_row; row++){
                        var real_row = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[row-1]
                        if (start_td_cell > stop_td_cell){  ///ведем слева направо
                            for (var cell=stop_td_cell; cell<=start_td_cell; cell++){
                                var real_cell = real_row.getElementsByTagName('td')[cell-1]
                                real_cell.classList.add('selected_td')
                            }
                        }else { ///ведем справа налево
                            for (var cell=start_td_cell; cell<=stop_td_cell; cell++){
                                var real_cell = real_row.getElementsByTagName('td')[cell-1]
                                real_cell.classList.add('selected_td')
                            }
                        }
                    }
                }
            }

            function print_region(){
                open_modal_confirm_ober('Распечатать выделенную область?')
                $('.selected_td').toggleClass('selected_td')
            }
            function confirm_request(){   ///функция, выполняемая при подтверждении
                console.log('подтвердил')
            }
        }
        ///Для подтверждения достоверности
        function all_param_accepted(th) {
            if (th.getElementsByTagName('img')[0].style.display === 'none') {
                open_modal_confirm_ober("Подтвердить достоверность данных за " + th.getAttribute('data-time') + "?", th)    //открываем алерт
            }
        }

        function confirm_request(th) {   //функция, которую вызываем при подтверждении
            th.getElementsByTagName('img')[0].style.display = ''
            $.ajax({
                url: '/accept_time_param/day/' + th.getAttribute('data-time-id') + '/' + $('#table_date_start').val(),
                method: 'GET',
                success: function (res) {
                    open_modal_ober('Данные сохранены!')
                    get_table_data()
                },
                async: false
            })
        }

        ///Для перехода к пятиминуткам
        function goToHour(th) {
            var hour_th = th.getAttribute('data-time').split(':')[0]
            var date = new Date($('#table_date_start').val())
            if (hour_th[0] === '0') {
                date = new Date(date.setDate(date.getDate() + 1))
                var hour = hour_th[1]
            } else {
                var hour = hour_th
            }
            if (document.getElementsByClassName('minute-param-' + hour).length > 0) {
                var old_tds = document.getElementsByClassName('minute-param-' + hour);
                while (old_tds[0]) {
                    old_tds[0].parentNode.removeChild(old_tds[0]);
                }
            } else {
                date = date.getFullYear() + '-' + Number(date.getMonth() + 1) + '-' + date.getDate()
                $.ajax({
                    url: '/hours_param_minutes/' + date + '/' + hour,
                    method: 'GET',
                    success: function (res) {

                        var cellIndex = th.cellIndex
                        var minutes = ''
                        //разбираемся с хедером
                        for (var i = 1; i < 12; i++) {
                            if (Number(55 - (i - 1) * 5) < 10) {
                                minutes = '0' + Number(55 - (i - 1) * 5)
                            } else {
                                minutes = Number(55 - (i - 1) * 5)
                            }
                            th.insertAdjacentHTML('afterend', `<th class="minute-param-${hour} minute_param" data-time="${hour_th + ':' + minutes}">${hour_th + ':' + minutes}</th>`)
                        }
                        //разбираемся с содержимым
                        var trs = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr')
                        var j = 0; //номер строки
                        for (var tr of trs) {
                            for (var td of tr.getElementsByTagName('td')) {
                                if (td.cellIndex === cellIndex) {
                                    for (var i = 11; i >= 1; i--) {
                                        if (res[j][i]) {
                                            td.insertAdjacentHTML('afterend', `<td class="minute-param-${hour} minute_param"><span style="text-align: right">${res[j][i]}</span></td>`)
                                        } else {
                                            td.insertAdjacentHTML('afterend', `<td class="minute-param-${hour} minute_param"><span style="text-align: right">...</span></td>`)
                                        }
                                    }
                                }
                            }
                            j++
                        }
                    }, async: false,
                })
            }
        }

        ///Для создания перекрестия
        function make_paint() {
            var all_td = document.getElementsByClassName('with_selector')[0].querySelectorAll('td');
            var all_th = document.getElementsByClassName('itemInfoTable')[1].getElementsByTagName('th')
            var all_statick_tr = document.getElementsByClassName('with_selector_statick')[0].querySelectorAll('tr');
            for (let i = 0; i < all_td.length; i++) {
                all_td[i].addEventListener('mouseover', () => {
                    for (var one_statick_tr of all_statick_tr) {
                        if (one_statick_tr.rowIndex === all_td[i].parentNode.rowIndex) {
                            one_statick_tr.style.background = '#FFFAF0'
                            for (var one_statick_td of one_statick_tr.getElementsByTagName('td')) {
                                one_statick_td.style.borderColor = 'black'
                            }
                        }
                    }
                    all_td[i].parentNode.style.background = '#FFFAF0'
                    for (var hor_td of all_td[i].parentNode.getElementsByTagName('td')) {
                        hor_td.style.borderColor = 'black'
                    }
                    for (var td of all_td) {
                        if (td.cellIndex === all_td[i].cellIndex) {
                            td.style.background = '#FFFAF0'
                            td.style.borderColor = 'black'
                        }
                    }
                    for (var th of all_th) {
                        if (th.cellIndex === all_td[i].cellIndex) {
                            th.style.background = '#FFFAF0'
                            th.style.borderColor = 'black'
                        }
                    }
                    all_td[i].style.background = '#E5E5E5'
                });
                all_td[i].addEventListener('mouseout', () => {
                    for (var one_statick_tr of all_statick_tr) {
                        if (one_statick_tr.rowIndex === all_td[i].parentNode.rowIndex) {
                            one_statick_tr.style.background = ''
                            for (var one_statick_td of one_statick_tr.getElementsByTagName('td')) {
                                one_statick_td.style.borderColor = ''
                            }
                        }
                    }
                    all_td[i].parentNode.style.background = ''
                    for (var hor_td of all_td[i].parentNode.getElementsByTagName('td')) {
                        hor_td.style.borderColor = ''
                    }
                    for (var hor_td of all_td[i].parentNode.getElementsByTagName('td')) {
                        hor_td.style.borderColor = ''
                    }
                    for (var td of all_td) {
                        if (td.cellIndex === all_td[i].cellIndex) {
                            td.style.background = ''
                            td.style.borderColor = ''
                        }
                    }
                    for (var th of all_th) {
                        if (th.cellIndex === all_td[i].cellIndex) {
                            th.style.background = ''
                            th.style.borderColor = ''
                        }
                    }
                    all_td[i].style.background = ''
                });
            }
        }

        function get_table_data(data_id) {
            //удаляем минутки
            if (document.getElementsByClassName('minute_param').length > 0) {
                var old_tds = document.getElementsByClassName('minute_param');
                while (old_tds[0]) {
                    old_tds[0].parentNode.removeChild(old_tds[0]);
                }
            }
            //сбрасываем то, что наворотил фильтр
            var content_header = document.getElementById('content-header');
            content_header.innerHTML = `<h3>${header_content}</h3>`
            $('.tableItem').removeClass('choiced');

            //Для блокировки уже подтвержденных
            $.ajax({
                url: '/get_accept/day/' + $('#table_date_start').val(),
                method: 'GET',
                success: function (res) {
                    for (var img of document.getElementById('itemInfoTable').getElementsByTagName('img')) {
                        img.style.display = 'none'
                    }
                    if (res) {
                        accept_ids = []
                        for (var i = 0; i < res.length; i++) {
                            accept_ids.push(res[i]['hour_day'])
                            document.getElementById('itemInfoTable').getElementsByTagName('img')[Number(res[i]['hour_day']) - 1].style.display = ''
                        }
                    }
                },
                async: false
            })
            $('.tableItemInfoChart').remove();

            var data = {
                'id': data_id,
                'type': $('#date-type').val()
            }

            data['date'] = $('#table_date_start').val();

            $.ajax({
                url: '/hours_param/' + $('#table_date_start').val(),
                method: 'GET',
                data: data,
                success: function (res) {
                    var static_table_body = document.getElementById('statickItemInfoTable').getElementsByTagName('tbody')[0]
                    var table_body = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    table_body.innerText = ''
                    table_body.classList.add('tbody_dynamic');  //для поиска
                    static_table_body.innerHTML = ''
                    static_table_body.classList.add('tbody_for_search');  //для поиска
                    var charts = {}
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        var static_tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['hfrpok'])
                        static_tr.setAttribute('data-id', row['hfrpok'])

                        static_tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span style="background-color: rgba(0, 0, 0, 0); text-align: center; width: 70%; padding-left: 0px; padding-right: 0px">${row['shortname']}</span><img onclick="get_graph(${row['hfrpok']})" onmouseover="this.style.border = '2px solid black'" onmouseout="this.style.border = 'none'" style="width: 20%; float: right; margin-top: 6px" src="assets/images/icons/ober_graph.png"></td>`
                        static_tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" onmouseover="get_parent_name(this)" data-name="namepar1">${row['namepar1']}</td>`

                        for (var id = 1; id <= 24; id++) {
                            if (row[id]['id']) {
                                if (Boolean(row[id]['xml_create'] === true)) {
                                    if (Boolean(row[id]['manual']) === true) {
                                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}" style="background-color: #1ab585"><span contenteditable="false" style="background-color: #1ab585" class="changeable_td tooltip" xml-create="true" data-column="val" data-row-id="${row[id]['id']}" spellcheck="false" data-type="float" numbercolumn="${id}" data-title="Изменил: ${row[id]['change_by']}">${row[id]['val']}</span></td>`
                                    } else {
                                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}" style="background-color: #1ab585"><span contenteditable="false" style="background-color: #1ab585" class="changeable_td" xml-create="true" data-column="val" data-row-id="${row[id]['id']}" spellcheck="false" data-type="float" numbercolumn="${id}">${row[id]['val']}</span></td>`
                                    }
                                } else {
                                    if (Boolean(row[id]['manual']) === true) {
                                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}" style="background-color: indianred" ><span contenteditable="true" class="changeable_td tooltip" numbercolumn="${id}" style="background-color: indianred" data-column="val" data-row-id="${row[id]['id']}"  spellcheck="false" data-type="float" data-title="Изменил: ${row[id]['change_by']}">${row[id]['val']}</span></td>`
                                    } else {
                                        if (accept_ids.includes(id)) {
                                            if (row[id]['change_by'] != '') {
                                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}"><span contenteditable="false" class="changeable_td tooltip" numbercolumn="${id}" style="background-color: rgba(0, 0, 0, 0)" data-column="val" data-row-id="${row[id]['id']}"  spellcheck="false" data-type="float" data-title="${row[id]['change_by']}" >${row[id]['val']}</span></td>`
                                            } else {
                                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}"><span class="changeable_td" contenteditable="false" style="background-color: rgba(0, 0, 0, 0)" oncopy="return false" numbercolumn="${id}" oncut="return false" onpaste="return false" data-column="val" data-row-id="${row[id]['id']}"  spellcheck="false" data-type="float">${row[id]['val']}</span></td>`
                                            }
                                        } else {
                                            if (row[id]['change_by'] != '') {
                                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}"><span contenteditable="true" class="changeable_td tooltip" numbercolumn="${id}" style="background-color: rgba(0, 0, 0, 0)" data-column="val" data-row-id="${row[id]['id']}"  spellcheck="false" data-type="float" data-title="${row[id]['change_by']}" >${row[id]['val']}</span></td>`
                                            } else {
                                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}"><span class="changeable_td" contenteditable="true" style="background-color: white" oncopy="return false" numbercolumn="${id}" oncut="return false" onpaste="return false" data-column="val" data-row-id="${row[id]['id']}"  spellcheck="false" data-type="float">${row[id]['val']}</span></td>`
                                            }
                                        }
                                    }
                                }

                            } else {
                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" ><span class="create_td" style="background-color: rgba(0, 0, 0, 0)" oncopy="return false" oncut="return false" onpaste="return false" data-column="val"   numbercolumn="${id}" hfrpok="${row['hfrpok']}" spellcheck="false" data-type="float">...</span></td>`
                            }
                        }

                        static_table_body.appendChild(static_tr);
                        table_body.appendChild(tr);
                    }
                    link_to_changeable('/changetimeparams/hour');
                },
                async: false
            })
            make_paint()
            $('#main_content').width($(document.body).width() - $('#side_menu').width() - 50);
        }

        function get_graph(hfrpok) {
            if (document.getElementById('modal_graph').classList.contains('many_param')){ ///если надо отобразить несколько параметров
                document.getElementById('text_graph').textContent += ' '+hfrpok
                document.getElementById('modal_graph').style.display = 'flex'
                get_graph_history()
            }else {
                var data = [];
                var xaxis = [];
                var static_tr = document.getElementById('statickItemInfoTable').querySelector(`tr[data-id="${hfrpok}"]`).querySelector(`td[data-name="namepar1"]`).textContent
                var e_unit = document.getElementById('statickItemInfoTable').querySelector(`tr[data-id="${hfrpok}"]`).getElementsByTagName('span')[0].textContent
                var dymanic_table = document.getElementById('itemInfoTable')
                var dynamic_head = dymanic_table.getElementsByTagName('th')
                var date = new Date($('#table_date_start').val())
                var next_date = new Date($('#table_date_start').val())
                next_date = new Date(next_date.setDate(next_date.getDate() + 1))
                for (var th of dynamic_head) {
                    if (th.getAttribute('data-time').split(':')[0][0] === '0') {
                        next_date.setHours(Number(th.getAttribute('data-time').split(':')[0])+ 3)
                        next_date.setMinutes(th.getAttribute('data-time').split(':')[1])
                        xaxis.push(next_date.getTime())
                    } else {
                        date.setHours(Number(th.getAttribute('data-time').split(':')[0])+ 3)
                        date.setMinutes(th.getAttribute('data-time').split(':')[1])
                        xaxis.push(date.getTime())
                    }
                }
                var dymanic_tr = dymanic_table.querySelector(`tr[data-id="${hfrpok}"]`).getElementsByTagName('td')
                for (var val of dymanic_tr) {
                    var text_span = val.getElementsByTagName('span')[0].textContent
                    if (text_span === '...') {
                        xaxis.splice(data.length, 1)
                    } else {
                        data.push(text_span)
                    }
                }
                open_modal_graph(hfrpok)
                document.getElementById('div_to_graph').innerText = ''
                var options = {
                    series: [{
                        name: static_tr,
                        data: data
                    }],
                    chart: {
                        height: 450,
                        type: 'area',
                        locales: [{
                            name: 'en',
                            options: {
                                months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                                shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Ноя', 'Дек'],
                                days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                                shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                                toolbar: {
                                    download: 'Загрузить SVG',
                                    selection: 'Selection',
                                    selectionZoom: 'Приблизить область',
                                    zoomIn: 'Приблизить',
                                    zoomOut: 'Отдалить',
                                    pan: 'Захват',
                                    reset: 'Сбросить приближение',
                                }
                            }
                        }]
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    title: {
                        text: static_tr,
                        align: 'left'
                    },
                    subtitle: {
                        text: 'Данные за ' + $('#table_date_start').val(),
                        align: 'left'
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: xaxis,
                        // title: {
                        //     text: 'Данные за '+$('#table_date_start').val()
                        // },
                    },
                    yaxis: {
                        title: {
                            text: e_unit
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'HH:mm'
                        },
                    },
                };

                var chart = new ApexCharts(document.querySelector("#div_to_graph"), options);
                chart.render();
            }
        }

        function get_parent_name(td) {
            $.ajax({
                url: '/get_parent_name/' + td.parentNode.getAttribute('data-id'),
                method: 'GET',
                success: function (res) {
                    td.classList.add('tooltip')
                    td.setAttribute('data-title', res)
                },
                async: false
            })
        }


        function CallPrint() {
            if (!document.getElementById('search_row').value) {
                var text = false
            } else {
                var text = document.getElementById('search_row').value
            }
            window.location.href = '/print_hour/' + $('#table_date_start').val() + '/' + $('.tableItem.choiced').attr('data-id') + '/' + text
        }

        function CallExcel() {
            if (!document.getElementById('search_row').value) {
                var text = false
            } else {
                var text = document.getElementById('search_row').value
            }
            window.location.href = '/excel_hour/' + $('#table_date_start').val() + '/' + $('.tableItem.choiced').attr('data-id') + '/' + text
            document.getElementById('modal_export_ober').style.display = 'none'
        }

    </script>

    @include('include.font_size-change')
    <style>
        .selected_td{
            background: yellow;
            background-color: yellow;
        }
        .tooltip:before {
            width: 100px;
            bottom: 35px;
            padding: 0px 3px 3px 3px;
            margin: 0px;
            margin-left: auto;
            height: auto;
            z-index: 100;
            font-size: 12px
        }

        .tooltip:hover:before {
            opacity: 0.8;
        }

        .timeCell {
            z-index: 99
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

        .create_td {
            background-color: white;
        }
    </style>


@endsection
