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
                <col style="width: 15%">
                <col style="width: 1%">
                <col style="width: 15%">
                <col style="width: 69%">
            </colgroup>
            <tbody>
            <tr>
                <td>@include('include.choice_month')</td>
                <td></td>
                <td style="vertical-align: bottom">@include('include.search_row')</td>
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
                <th class="objCell" style="width: 2%; padding-left: 0px; padding-right: 0px; text-align: center"><h4>Ед.
                        изм</h4></th>
                <th class="objCell" style="z-index: 3;"><h4>Наименование параметра</h4></th>
            </tr>
            <tbody class="with_selector_statick">

            </tbody>
        </table>
        <table id="itemInfoTable" class="itemInfoTable" style="width: 74%; float:left">
            <thead>

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
            padding: 0px 0px;
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
        var header_content = 'Суточные показатели.  ';
        var datatable = null;
        $(document).ready(function () {
/////Проверяем заходим ли мы по ссылке
            if (localStorage.getItem('to_month')) {
                $('#table_date_start').val(localStorage.getItem('to_month'))
                for (let localStorageKey in localStorage) {
                    if (localStorageKey != 'font') {
                        localStorage.removeItem(localStorageKey)
                    }
                }
            } else {

            }
/////Объединяем скролы двух таблиц
            $("#itemInfoTable").scroll(function () {
                $('#statickItemInfoTable').scrollTop($("#itemInfoTable").scrollTop());
            });
            $("#statickItemInfoTable").scroll(function () {
                $('#itemInfoTable').scrollTop($("#statickItemInfoTable").scrollTop());
            });
/////
            get_table_data()

            $('#table_date_start').change(function () {
                get_table_data()
            })
        })

        function goToDay(day) {
            if (day < 10) {
                day = '0' + day
            }
            localStorage.setItem('day', day)
            localStorage.setItem('month', $('#table_date_start').val().split('-')[1])
            localStorage.setItem('year', $('#table_date_start').val().split('-')[0])
            document.location.href = '/'
        }

        ///Для подтверждения достоверности
        function all_param_accepted(th) {
            if (th.getElementsByTagName('img')[0].style.display === 'none') {
                open_modal_confirm_ober("Подтвердить достоверность данных за " + $('#table_date_start').val() + '-' + th.getAttribute('data-time-id') + "?", th)    //открываем алерт
            }
        }

        function confirm_request(th) {   //функция, которую вызываем при подтверждении
            th.getElementsByTagName('img')[0].style.display = ''
            $.ajax({
                url: '/accept_time_param/sutki/' + th.getAttribute('data-time-id') + '/' + $('#table_date_start').val(),
                method: 'GET',
                success: function (res) {
                    open_modal_ober('Данные сохранены!')
                    get_table_data()
                },
                async: false
            })
        }

        function get_table_data(data_id) {
            //сбрасываем то, что наворотил фильтр
            var content_header = document.getElementById('content-header');
            content_header.innerHTML = `<h3>${header_content}</h3>`
            $('.tableItem').removeClass('choiced');

            //Для блокировки уже подтвержденных
            $.ajax({
                url: '/get_accept/sutki/' + $('#table_date_start').val(),
                method: 'GET',
                success: function (res) {
                    for (var img of document.getElementById('itemInfoTable').getElementsByTagName('img')) {
                        img.style.display = 'none'
                    }
                    if (res) {
                        accept_ids = []
                        for (var i = 0; i < res.length; i++) {
                            accept_ids.push(res[i]['hour_day'])
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
                url: '/sut_param/' + $('#table_date_start').val(),
                method: 'GET',
                data: data,
                success: function (res) {
                    var static_table_body = document.getElementById('statickItemInfoTable').getElementsByTagName('tbody')[0]
                    var table_body = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    var table_thead = document.getElementById('itemInfoTable').getElementsByTagName('thead')[0]
                    table_body.innerText = ''
                    table_body.classList.add('tbody_dynamic');  //для поиска
                    table_thead.innerText = ''
                    static_table_body.innerHTML = ''
                    static_table_body.classList.add('tbody_for_search');  //для поиска
                    table_body.style.fontSize = 14 * localStorage.getItem('font') + 'px';
                    var charts = {}
                    var index_thead = false
                    var tr_thead = document.createElement('tr')
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        var static_tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['hfrpok'])
                        static_tr.setAttribute('data-id', row['hfrpok'])
                        static_tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" ><span style="background-color: rgba(0, 0, 0, 0); text-align: center; width: 70%; padding-left: 0px; padding-right: 0px">${row['shortname']}</span><img onclick="get_graph(${row['hfrpok']})" onmouseover="this.style.border = '2px solid black'" onmouseout="this.style.border = 'none'" style="width: 20%; float: right; margin-top: 6px" src="assets/images/icons/ober_graph.png"></td>`
                        static_tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" onmouseover="get_parent_name(this)" data-name="namepar1">${row['namepar1']}</td>`

                        // var data = [];
                        // var xaxis = [];

                        // console.log(row[0]['timestamp'])
                        var month = $('#table_date_start').val().split('-')[1]
                        for (var id = 1; id <= Object.keys(row).length - 4; id++) {
                            if (!index_thead) {
                                if (id < 10) {
                                    var day = '0' + id
                                } else {
                                    var day = id
                                }
                                tr_thead.innerHTML += `<th style="font-size:${14 * localStorage.getItem('font')}px"  class="timeCell" onclick="goToDay(${id})" style="width: 2%; text-align: center" data-time-id="${id}" oncontextmenu="all_param_accepted(this)" ><h4>${day + '.' + month} <img src=\"assets/images/icons/accept_ober.png\" style="height: 14px; display: none"/> </h4></th>`
                            }
                            if (row[id]['id']) {
                                if (Boolean(row[id]['xml_create'] === true)) {
                                    if (Boolean(row[id]['manual']) === true) {
                                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}" style="background-color: #1ab585"><span contenteditable="false" style="background-color: #1ab585" class="changeable_td tooltip" xml-create="true" data-column="val" data-row-id="${row[id]['id']}" spellcheck="false" data-type="float" numbercolumn="${id}" data-title="Изменил: ${row[id]['change_by']}">${row[id]['val']}</span></td>`
                                    } else {
                                        tr.innerHTML += `<td  style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${row[id]['timestamp']}" style="background-color: #1ab585"><span contenteditable="false" style="background-color: #1ab585" class="changeable_td" xml-create="true" data-column="val" data-row-id="${row[id]['id']}" spellcheck="false" data-type="float" numbercolumn="${id}">${row[id]['val']}</span></td>`
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
                                // xaxis.push(id)
                                // data.push(parseFloat(row[id]['val']))
                            } else {
                                tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px" data-time-id="${id}" class="hour-value-${row['hfrpok']}" ><span class="create_td" style="background-color: rgba(0, 0, 0, 0)" oncopy="return false" oncut="return false" onpaste="return false" data-column="val"   numbercolumn="${id}" hfrpok="${row['hfrpok']}" spellcheck="false" data-type="float">...</span></td>`
                            }
                        }
                        index_thead = true
                        static_table_body.appendChild(static_tr);
                        table_body.appendChild(tr);
                        table_thead.appendChild(tr_thead);
                        for (var j = 0; j < accept_ids.length; j++) {

                            document.getElementById('itemInfoTable').getElementsByTagName('img')[Number(accept_ids[j]) - 1].style.display = ''
                        }
                    }

                    link_to_changeable('/changetimeparams/sutki');
                    link_to_create('/createtimeparams');

                },
                async: false

            })
            make_paint()
            $('#main_content').width($(document.body).width() - $('#side_menu').width() - 50);
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
                var dynamic_head = dymanic_table.getElementsByTagName('h4')
                for (var th of dynamic_head) {
                    xaxis.push(th.textContent)
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
                // document.getElementById('text_graph').textContent = static_tr
                document.getElementById('div_to_graph').innerText = ''
                var options = {
                    series: [{
                        name: static_tr,
                        data: data
                    }],
                    chart: {
                        height: 450,
                        type: 'area'
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
                        // type: 'timestamp',
                        categories: xaxis
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


        function CallPrint() {
            if (!document.getElementById('search_row').value) {
                var text = false
            } else {
                var text = document.getElementById('search_row').value
            }
            window.location.href = '/print_sut/' + $('#table_date_start').val() + '/' + $('.tableItem.choiced').attr('data-id') + '/' + text
        }

        function CallExcel() {
            if (!document.getElementById('search_row').value) {
                var text = false
            } else {
                var text = document.getElementById('search_row').value
            }
            window.location.href = '/excel_sut/' + $('#table_date_start').val() + '/' + $('.tableItem.choiced').attr('data-id') + '/' + text
            document.getElementById('modal_export_ober').style.display = 'none'
        }

    </script>
    <style>
        .tooltip:hover:before {
            opacity: 0.8;
        }

        .create_td {
            background-color: white;
        }

        .timeCell {
            z-index: 99
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

    </style>
    @include('include.font_size-change')

@endsection
