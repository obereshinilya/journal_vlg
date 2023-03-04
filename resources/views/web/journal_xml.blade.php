@extends('layouts.app')
@section('title')
    Журнал отправки XML
@endsection


@section('content')
    @push('scripts')
        <script src="{{asset('assets/js/moment-with-locales.min.js')}}"></script>
        <script src="{{asset('assets/libs/changeable_td.js')}}"></script>
        <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/libs/modal-windows/modal_windows.js')}}"></script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{asset('assets/css/table.css')}}">
        <link rel="stylesheet" href="{{asset('assets/libs/modal-windows/modal_windows.css')}}">
    @endpush

    <div id="content-header" style="margin-top: 10px"><h3 style="width: 30%; display: inline-block">Журнал событий по
            отправке XML</h3>

        <button class="button button1" style="float: right; display: inline-block" onclick="xml_masdu(1)">Отправка СД.
            Час
        </button>
        <button class="button button1" style="float: right; display: inline-block" onclick="xml_masdu(24)">Отправка СД.
            Сутки
        </button>
        {{--        <button class="button button1" style="float: right; display: inline-block" onclick="sut_xml()">Отправка СД. Месяц</button>--}}

    </div>
    <style>
        .content {
            width: 98%;
        }
    </style>

    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed">
            <colgroup>
                <col style="width: 15%"/>
                <col style="width: 55%"/>
                <col style="width: 30%"/>
            </colgroup>
            <thead style="width: 100%">
            <tr>
                <th style="text-align: center">Дата (местн)</th>
                <th style="text-align: center">Событие</th>
                <th style="text-align: center">Статус</th>
            </tr>
            </thead>
            <tbody style="width: 100%">

            </tbody>
        </table>
        {{--        <a class="paginate_button first disabled" aria-controls="itemInfoTable" style="float: right" data-dt-idx="0" tabindex="-1" id="to_print">Печать</a>--}}
    </div>

    <script>

        $(document).ready(function () {

            getTableData();

        });

        function getTableData(type = null, data = null) {
            document.body.className = '';
            $.ajax({
                url: '/get_journal_xml_data',
                data: 1,
                type: 'GET',
                success: (res) => {
                    if ($.fn.dataTable.isDataTable('#itemInfoTable')) {
                        $('#itemInfoTable').DataTable().destroy();
                    }
                    var result = Object.keys(res);

                    var table_body = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    table_body.innerText = ''
                    for (var i = 0; i < res.length; i++) {
                        var tr = document.createElement('tr')
                        if (res[i]['option'] === 'Отсутствие связи с sftp-сервером!') {
                            tr.style.background = 'yellow'
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['timestamp']}</span></td>`
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['event']}</span></td>`
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center; width: 50%">${res[i]['option']}</span>
                            <button class="button button1" style="float: right; margin-right: 5%" onclick="hand_xml(${res[i]['id']})">Повторить</button>
                            </td>`
                        } else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['timestamp'].split('.')[0]}</span></td>`
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['event']}</span></td>`
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['option']}</span></td>`
                        }
                        table_body.appendChild(tr);
                    }
                    $('#itemInfoTable').DataTable({
                        "pagingType": "full_numbers",
                        destroy: true,
                        order: [[0, 'desc']],

                    });

                    window.setTimeout(function () {
                        document.body.classList.add('loaded');
                        document.body.classList.remove('loaded_hiding');
                    }, 500);
                }
            })
        }

        function hand_xml(id) {
            document.body.classList.remove('loaded');
            document.body.classList.add('loaded_hidind');
            $.ajax({
                url: '/create_xml_hand/' + id,
                data: 1,
                type: 'GET',
                success: (res) => {
                    getTableData()
                    document.body.classList.add('loaded');
                    document.body.classList.remove('loaded_hidind');
                },
                async: true
            })
        }

        function xml_masdu(type) {
            $.ajax({
                url: '/hand_for_masdu/' + type,
                data: 1,
                type: 'GET',
                success: (res) => {
                    getTableData()
                },
                async: true
            })
        }

        function sut_xml() {
            $.ajax({
                url: '/create_month_xml',
                data: 1,
                type: 'GET',
                success: (res) => {
                    getTableData()
                },
                async: true
            })
        }

    </script>
    @include('include.font_size-change')

@endsection


