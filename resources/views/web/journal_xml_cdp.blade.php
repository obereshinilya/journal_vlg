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

        <button class="button button1" style="float: right; display: inline-block" onclick="open_modal_choice_hour()">
            Отправка СД.
            Час
        </button>
        <button class="button button1" style="float: right; display: inline-block" onclick="open_modal_choice_sut()">
            Отправка СД.
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
    <div class="modal_choice_sut" id="modal_choice_sut">
        <div class="modal-window-choice_sut">
            <p style="float: right; top: 0px; margin: 0px; opacity: 0.5"
               onclick="document.getElementById('modal_choice_sut').style.display = 'none'">x</p>
            <table style="display: table; table-layout: fixed">
                <tbody>
                <tr>
                    <h2>Выберите дату</h2>
                </tr>
                <tr>
                    <span style="display: flex; justify-content: space-between; flex-direction: column">
                    <input type="date" class="date_input" max="{{date('Y-m-d')}}" id="choice_sut">
                    <button class="button button1"
                            onclick="xml_masdu(24); document.getElementById('modal_choice_sut').style.display='none'">Отправить</button>
</span>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="overlay_choice_sut" id="overlay_choice_sut">

        </div>
    </div>

    <div class="modal_choice_hour" id="modal_choice_hour">
        <div class="modal-window-choice_hour">
            <p style="float: right; top: 0px; margin: 0px; opacity: 0.5"
               onclick="document.getElementById('modal_choice_hour').style.display = 'none'">x</p>
            <table style="display: table; table-layout: fixed">
                <tbody>
                <tr>
                    <h2>Выберите дату и время</h2>
                </tr>
                <tr>
                    <span style="display: flex; justify-content: space-between; flex-direction: column">
                    <input type="date" class="date_input" max="{{date('Y-m-d')}}" onchange="select_hour(this)"
                           id="choice_hour">
                        <select disabled class="date_input" id="select_hour">

                        </select>
                    <button class="button button1"
                            onclick="xml_masdu(1);  document.getElementById('modal_choice_hour').style.display='none'">Отправить</button>
</span>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="overlay_choice_hour" id="overlay_choice_hour">

        </div>
    </div>
    <script>

        function select_hour(el) {
            let date = new Date(el.value);
            let today = new Date();
            let select = document.getElementById('select_hour');
            let option
            select.innerHTML = '';
            if (date.getFullYear() === today.getFullYear() &&
                date.getMonth() === today.getMonth() &&
                date.getDate() === today.getDate()) {
                for (i = 0; i < today.getHours(); i++) {
                    option = document.createElement('option')
                    option.value = i;
                    if (i < 10) {
                        option.textContent = '0' + i + ':00'
                    } else {
                        option.textContent = i + ':00'
                    }
                    select.append(option)
                }
            } else {
                for (i = 0; i <= 23; i++) {
                    option = document.createElement('option')
                    option.value = i;
                    if (i < 10) {
                        option.textContent = '0' + i + ':00'
                    } else {
                        option.textContent = i + ':00'
                    }
                    select.append(option)
                }

            }
            select.removeAttribute('disabled')
        }

        function open_modal_choice_sut() {
            document.getElementById('modal_choice_sut').style.display = 'flex'
        }

        function open_modal_choice_hour() {
            document.getElementById('modal_choice_hour').style.display = 'flex'
        }

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
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center; width: 50%">${res[i]['option']}</span></td>`
                                // <button class="button button1" style="float: right; margin-right: 5%" onclick="hand_xml(${res[i]['id']})">Повторить</button>
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
            let hour, date
            if (type > 1) {
                hour = false;
                date = document.getElementById('choice_sut').value
            } else {
                hour = document.getElementById('select_hour').value
                date = document.getElementById('choice_hour').value
            }
            $.ajax({
                url: '/hand_for_masdu/' + type + '/' + date + '/' + hour,
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
    <style>
        /*.none div{*/
        /*    display: none;*/
        /*}*/

        .date_div {
            position: relative;
            /*padding: 15px 0 0;*/
            /*margin-top: 10px;*/
            width: 100%;
            display: table;
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

        .choice-period-btn {
            /*box-sizing: border-box;*/
            display: inline-block;
            min-width: 1.5em;
            padding: .5em 1em;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: #0079c2;
            /*margin-left: 15px;*/
            /*margin-right: 5px;*/
            /*position: absolute;*/
            /*top: calc(50% - 8px);*/
            width: 110px;
        }

        .period-btns {
            display: inline-block;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            position: absolute;
            top: calc(50% - 8px);
        }

    </style>
    @include('include.font_size-change')

@endsection


