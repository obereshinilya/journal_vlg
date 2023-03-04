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
        <table style="display: table; width: 100%; table-layout: fixed">
            <colgroup>
                <col style="width: 330px">
                <col style="width: calc(35% - 330px)">
                <col style="width: 65%">
            </colgroup>
            <tbody>
            <tr>
                <td>@include('include.choice_date_time')</td>
                <td style="vertical-align: bottom">@include('include.search_row')</td>
                <td>
                    <button class="button button1" style="float: right; margin-top: 1%"
                            onclick="localStorage.setItem('to_sut', $('#table_date_start').val()); window.location.href = '/'">
                        К часовым
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div id="content-header"></div>

    <div id="tableDiv" style="height:710px">
        <table id="statickItemInfoTable" class="itemInfoTable"
               style="width: 25%; float:left; direction:rtl; table-layout: fixed">
            <thead>
            <tr>
                <th class="objCell" style="width: 2%; padding-left: 0px; padding-right: 0px; text-align: center"><h4>Ед.
                        изм</h4></th>
                <th class="objCell" style="z-index: 3"><h4>Наименование параметра</h4></th>
            </tr>
            <tbody>

            </tbody>
        </table>
        <table id="itemInfoTable" class="itemInfoTable" style="width: 74%; float:left; table-layout: fixed">
            <thead>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>0`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>5`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>10`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>15`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>20`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>25`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>30`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>35`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>40`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>45`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>50`</h4></th>
            <th class="timeCell" style="text-align: center; width: 5%"><h4>55`</h4></th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>


    <style>
        .content {
            width: calc(100% - 40px);
        }

    </style>

    <script>
        var header_content = 'Показатели РВ.  ';
        var datatable = null;
        $(document).ready(function () {
/////Объединяем скролы двух таблиц
            $("#itemInfoTable").scroll(function () {
                $('#statickItemInfoTable').scrollTop($("#itemInfoTable").scrollTop());
            });
            $("#statickItemInfoTable").scroll(function () {
                $('#itemInfoTable').scrollTop($("#statickItemInfoTable").scrollTop());
            });
/////

            var today = new Date();
            $('#table_date_start').val(today.toISOString().substring(0, 10))
            document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));
            var optionList = document.getElementById("select_interval").getElementsByTagName('option');
            if ($('#table_date_start').val() === today.toISOString().substring(0, 10)) {
                for (var i of optionList) {
                    if (i.value > today.getHours()) {
                        i.setAttribute('style', 'display:none')
                    }
                }
                document.getElementById('select_interval').value = today.getHours()
            }
/////Проверяем заходим ли мы по ссылке
            if (localStorage.getItem('full_date')) {
                $('#table_date_start').val(localStorage.getItem('full_date'))
                document.getElementById('select_interval').value = localStorage.getItem('hour')
                for (let localStorageKey in localStorage) {
                    if (localStorageKey != 'font') {
                        localStorage.removeItem(localStorageKey)
                    }
                }
            } else {

            }
            get_table_data()

            $('#table_date_start').change(function () {
                if ($('#table_date_start').val() === today.toISOString().substring(0, 10)) {
                    for (var i of optionList) {
                        if (i.value >= today.getHours()) {
                            i.setAttribute('style', 'display:none')
                        }
                    }
                } else {
                    for (var i of optionList) {
                        i.setAttribute('style', 'display:block')
                    }
                }
                get_table_data()
            });
        })


        function get_table_data(data_id) {
            //сбрасываем то, что наворотил фильтр
            var content_header = document.getElementById('content-header');
            content_header.innerHTML = `<h3>${header_content}</h3>`
            $('.tableItem').removeClass('choiced');
            $('.tableItemInfoChart').remove();

            $.ajax({
                url: '/minutes_param/' + $('#table_date_start').val() + '/' + document.getElementById("select_interval").value,
                method: 'GET',
                // data: data,
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
                        static_tr.innerHTML += `<td ><span style="background-color: rgba(0, 0, 0, 0); text-align: center">${row['shortname']}</span></td>`
                        static_tr.innerHTML += `<td onmouseover="get_parent_name(this)" data-name="namepar1">${row['namepar1']}</td>`
                        var visible_chart = false
                        var data = [];
                        var xaxis = [];
                        for (var id = 0; id < row['min_params'].length; id++) {
                            if (row['min_params'][id] !== null) {
                                console.log(row['min_params'][id])
                                tr.innerHTML += `<td data-time-id="${id}" class="hour-value-${row['hfrpok']}" data-time="${Number(5 * id)}"><span class="changeable_td" style="background-color: rgba(0, 0, 0, 0)" oncopy="return false" oncut="return false" onpaste="return false" data-column="val" numbercolumn="${id}" hfrpok="${row['hfrpok']}" data-row-id="${id}" spellcheck="false" data-type="float" >${row['min_params'][id]}</span></td>`
                                xaxis.push(Number(id * 5))
                                data.push(parseFloat(row['min_params'][id]))
                                visible_chart = true
                            } else {
                                tr.innerHTML += `<td data-time-id="${id}" class="hour-value-${row['hfrpok']}" ><span class="changeable_td" style="background-color: rgba(0, 0, 0, 0)" oncopy="return false" oncut="return false" onpaste="return false" data-column="val"   numbercolumn="${id}" hfrpok="${row['hfrpok']}" spellcheck="false" data-type="float">...</span></td>`
                            }
                        }
                        static_table_body.appendChild(static_tr);
                        table_body.appendChild(tr);
                        if (visible_chart) {
                            jQuery('<div>', {
                                id: `chart${id}`,
                                class: 'tableItemInfoChart',
                            }).appendTo('body');
                            var options = {
                                series: [{
                                    name: row['namepar1'],
                                    data: data
                                }],
                                xaxis: {
                                    categories: xaxis
                                },
                                chart: {
                                    type: 'line',
                                    height: 350,
                                },
                                stroke: {
                                    curve: 'smooth',
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                title: {
                                    text: `${row['namepar1']}`,
                                    align: 'left'
                                },
                                markers: {
                                    hover: {
                                        sizeOffset: 4
                                    }
                                },
                                tooltip: {
                                    custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                        return (
                                            '<div class="arrow_box">' +
                                            "<span>" +
                                            w.globals.seriesNames[seriesIndex] +
                                            ": " +
                                            series[seriesIndex][dataPointIndex] +
                                            "</span>" +
                                            "</div>"
                                        );
                                    }
                                }
                            };

                            var chart = new ApexCharts(document.getElementById(`chart${id}`), options);

                            chart.render();

                            tippy.createSingleton(tippy(`.hour-value-${row['hfrpok']}`, {
                                content: document.getElementById(`chart${id}`),

                            }), {
                                placement: 'left',
                                maxWidth: 650,
                                width: 650,
                                delay: 100, // ms
                                moveTransition: 'transform 0.2s ease-out',
                                allowHTML: true,
                                // interactive: true,
                            });
                        }


                    }

                    // link_to_changeable('/changetimeparams/sutki');
                    // link_to_create('/createtimeparams');

                },
                async: false

            })
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


    </script>
    <style>
        .tooltip:before {
            width: auto;
            bottom: 0px;
            padding: 0px 3px 10px 3px;
            margin: 0px;
            margin-left: auto;
            height: auto;
            z-index: 1;
        }

        .tooltip:hover:before {
            opacity: 0.8;
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
