@extends('layouts.app')
@section('title')
    Настройка отчета
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
    <p style="display: none" id="alarm">{{$data}}</p>
    <p style="display: none" id="check_param"></p>
    <div style="width: 100%; margin-top: 10px">
        <table style="display: table; table-layout: fixed; width: 100%">
            <colgroup>
                <col style="width: 20%">
                <col style="width: 80%">
            </colgroup>
            <tr>
                <td >
                    <h3 style="margin: 10px">Выбор сигнала-источника данных</h3>
                </td>
                <td style="width: 40%; float: right; margin-right: 40px">
                    @include('include.search_row')
                </td>
            </tr>
        </table>


        <div id="content-header" style="display: none"></div>
        <div style="display:inline-block">
            <button class="button button1 param_button" onclick="get_checked(this)" id="in_gas">Закачка</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="out_gas">Отбор</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="skv_job">СКВ в раб</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="skv_res">СКВ в рез</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="skv_rem">СКВ в рем</button>

            <button class="button button1 param_button" onclick="get_checked(this)" id="t_in">Т вх</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="t_out">Т вых</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="p_in">P вх</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="p_out">P вых</button>

            <button class="button button1 param_button" onclick="get_checked(this)" id="gpa_job">ГПА в раб</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="gpa_res">ГПА в рез</button>
            <button class="button button1 param_button" onclick="get_checked(this)" id="gpa_rem">ГПА в рем</button>
            <button class="button button1 param_button" id="redirect" style="float: right">Вернуться к отчету</button>

        </div>

    </div>

    <div id="tableDiv" style="margin-top: 20px; height: calc(100% - 140px)">
        <table id="statickItemInfoTable" class="itemInfoTable" style="width: 40%; float:left; direction:rtl; table-layout: fixed">
            <thead>
            <tr>
                <th class="objCell" style="width: 10px"><h4></h4></th>
                <th class="objCell" ><h4>Наименование параметра</h4></th>
            </tr>
            <tbody>

            </tbody>
        </table>
    </div>

    <button class="button button1" id="submit_signal" style="display: none; margin-left: 46%">Сохранить</button>


    <script>
        var header_content = 'Настройка';

        $(document).ready(function () {
            if (document.getElementById('alarm').textContent === 'false'){
                open_modal_ober('Выберите все сигналы!')
            }
            click_side_menu_func = show_hide;
            $.ajax({
                url: '/get_all_params',
                method: 'GET',
                success: function (res) {
                    var static_table_body=document.getElementById('statickItemInfoTable').getElementsByTagName('tbody')[0]
                    static_table_body.innerHTML=''
                    static_table_body.classList.add('tbody_for_search');  //для поиска
                    for (var row of res) {
                        var static_tr = document.createElement('tr')
                        static_tr.setAttribute('data-id', row['hfrpok'])
                        static_tr.innerHTML += `<td data-name="namepar1" style="text-align: center"><button class="button button1" select-hfrpok="${row['hfrpok']}">Выбрать</button></td>`
                        static_tr.innerHTML += `<td data-name="namepar1">${row['namepar1']}</td>`
                        static_table_body.appendChild(static_tr);
                    }
                },
                async:false
            })

            $('#redirect').click(function () {
                window.location.href = '/open_svodniy'
            })

            $('.button').click(function () {
                var check_param = document.getElementById('check_param').textContent
                if (!check_param ){
                    if (this.id !== 'redirect')
                        open_modal_ober('Не выбран параметр!')
                }
                if (this.getAttribute('select-hfrpok')){
                    $.ajax({
                        url: '/save_param_svodniy/'+ check_param + '/'+ this.getAttribute('select-hfrpok'),
                        method: 'GET',
                        success: function (res) {
                            mark_checked_params(check_param)
                            open_modal_ober('Параметр обновлен!')
                        },
                        async:false
                    })
                }
            })
        })
        function get_checked(button){
            var id = button.id
            document.getElementById('check_param').textContent = id
            mark_checked_params(id)
            for (var one_button of document.getElementsByClassName('param_button')){
                one_button.style.background = 'white'
                button.style.background = '#1ab585'
            }
        }

        function mark_checked_params(param_name){
            $.ajax({
                url: '/get_setting_svodniy',
                method: 'GET',
                success: function (res) {
                    var btn = ''
                    if (res[param_name] === null){
                        var table = document.getElementById('statickItemInfoTable')
                        var rows = table.getElementsByTagName('tr')
                        for (var row of rows){
                            btn = row.getElementsByTagName('button')[0]
                            try {
                                btn.removeAttribute('disabled')
                                btn.innerText = 'Выбрать'
                                btn.style.background = 'white'
                            } catch (e){
                            }
                        }
                    } else {
                        var hfrpok = res[param_name].split('.')[0]
                        var table = document.getElementById('statickItemInfoTable')
                        var rows = table.getElementsByTagName('tr')
                        for (var row of rows){
                            if (row.getAttribute('data-id') === hfrpok){
                                btn = row.getElementsByTagName('button')[0]
                                btn.setAttribute('disabled', true)
                                btn.innerText = 'Выбрано'
                                btn.style.background = '#d8d8d8'
                            } else {
                                btn = row.getElementsByTagName('button')[0]
                                if (btn !== undefined){
                                    btn.removeAttribute('disabled')
                                    btn.innerText = 'Выбрать'
                                    btn.style.background = 'white'

                                }
                            }
                        }
                    }
                },
                async:false
            })
        }

        function show_hide() {
        }

    </script>

    <style>
        .text{
            text-overflow: ellipsis
        }
        .row{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .create_td{
            background-color: white;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            border-radius: 6px;
            color: white;
            height: 5%;
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
        textarea { font-family: HeliosCond; }
        input[type=text] { font-family: HeliosCond; }
    </style>

@endsection
