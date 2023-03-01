@extends('layouts.app')
@section('title')
    Данные с АстраГаз
@endsection
@section('side_menu')
    @include('web.reports.side_menu_reports')
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



    <div id="content-header">
        <table style="width: 100%; table-layout: fixed; display: table">
            <colgroup>
                <col style="width: 40%">
                <col style="width: 60%">
            </colgroup>
            <tbody>
                <tr>
                    <td>
                        <table style="width: 100%; table-layout: fixed; display: table">
                                <col width="40%">
                                <col width="30%">
                                <col width="30%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>
                                    <h3>Данные с АстраГаз</h3>
                                </td>
                                <td>
                                    <button  id="setting" class="button button1" style=" margin-top: 1%; float: left">Выбор источников</button>
                                </td>
                                <td>
                                    <button  id="check_new_data" class="button button1" style=" margin-top: 1%; float: left">Считать данные</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table style="width: 100%; table-layout: fixed; display: table; border: solid 1px black">
                            <colgroup>
                                <col style="width: 20%">
                                <col style="width: 20%">
                                <col style="width: 20%">
                                <col style="width: 20%">
                                <col style="width: 20%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>Логин</th>
                                <th>Пароль</th>
                                <th>Адрес</th>
                                <th>Путь</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="padding: 0 5px; margin: 0 5px"><input class="text-field__input" onchange="save_astra_setting()" id="user_astra" type="text"></td>
                                <td class="input-wrapper_type_disc" style="padding: 0 5px; margin: 0 5px"><input class="text-field__input" onchange="save_astra_setting()" id="password_astra" type="text"></td>
                                <td style="padding: 0 5px; margin: 0 5px"><input class="text-field__input" onchange="save_astra_setting()" id="adres_sftp_astra" type="text"></td>
                                <td style="padding: 0 5px; margin: 0 5px"><input class="text-field__input" onchange="save_astra_setting()" id="path_sftp_astra" type="text"></td>
                                <td style="text-align: center"><button id="button_test_sftp_astra" class="button button1" style="font-size: 12px; padding: 3px" onclick="check_sftp_astra(this)">Тестовое подключение</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
   </div>

    <style>
        .content{
            width: calc(100% - 40px);
        }
    </style>

    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="display: table; table-layout: fixed">
            <colgroup>
                <col style="width: 3%">
                <col style="width: 10%">
                <col style="width: 14%">
                <col style="width: 14%">
                <col style="width: 14%">
                <col style="width: 14%">
                <col style="width: 14%">
                <col style="width: 14%">
                <col style="width: 3%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">№</th>
                <th style="text-align: center">Дата <br>расчета</th>
                <th style="text-align: center">КС<br>Расход, тыс. м<sup><small>3</small></sup>/час.</th>
                <th style="text-align: center">КС<br>Температура, град. C</th>
                <th style="text-align: center">КС<br>Давление, (ата)</th>
                <th style="text-align: center">ЛУ<br>Расход, тыс. м<sup><small>3</small></sup>/час.</th>
                <th style="text-align: center">ЛУ<br>Температура, град. C</th>
                <th style="text-align: center">ЛУ<br>Давление, (ата)</th>
                <th style="text-align: center"></th>
            </tr>

            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <script>

        $(document).ready(function (){
            getTableData();
            get_astra_sftp()
            $('#setting').click(function() {
                window.location.href = '/astragaz_setting'
            });
            $('#check_new_data').click(function() {
                $.ajax({
                    url: '/get_result_astragaz',
                    method: 'get',
                    success: function (res) {
                        getTableData()
                    },
                    async:false
                })            });
        });
        function save_astra_setting(){
            var arr = new Map()
            arr.set('type', 'astragaz')
            arr.set('user', document.getElementById('user_astra').value)
            arr.set('password', document.getElementById('password_astra').value)
            arr.set('adres_sftp', document.getElementById('adres_sftp_astra').value)
            arr.set('path_sftp', document.getElementById('path_sftp_astra').value)
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/save_sftp_setting/astragaz',
                data: data,
                method: 'POST',
                success: function (res) {
                    document.getElementById('button_test_sftp_astra').textContent = 'Тестовое подключение'
                },
                async:false
            })
        }
        function get_astra_sftp(){
            $.ajax({
                url: '/get_sftp_setting/astragaz',
                method: 'get',
                success: function (res) {
                    document.getElementById('user_astra').value = res['user']
                    document.getElementById('password_astra').value = res['password']
                    document.getElementById('adres_sftp_astra').value = res['adres_sftp']
                    document.getElementById('path_sftp_astra').value = res['path_sftp']
                },
                error: function (){
                    document.getElementById('user_astra').value = ''
                    document.getElementById('password_astra').value = ''
                    document.getElementById('adres_sftp_astra').value = ''
                    document.getElementById('path_sftp_astra').value = ''
                },
                async:false
            })
        }

        function check_sftp_astra(button){
            $.ajax({
                url: '/test_sftp/astragaz',
                method: 'get',
                async:false,
                timeout:300,
                success: function (res) {
                    button.textContent = res
                },
                error: function (){
                    button.textContent = 'Ошибка!'
                },
            })
        }

        function getTableData() {
            document.body.className = '';

            $.ajax({
                url:'/get_astragaz',
                data: 1,
                type:'GET',
                success:(res)=>{
                    if ($.fn.dataTable.isDataTable('#itemInfoTable')) {
                        $('#itemInfoTable').DataTable().destroy();
                    }
                    var table_body=document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    table_body.innerText=''
                    for (var i = 0; i<res.length; i++){
                        var tr=document.createElement('tr')
                        tr.setAttribute('data-id', res[i]['id'])
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['id']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['date']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['q_ks']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['t_ks']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['p_ks']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['q_lu']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['t_lu']}</span></td>`
                        tr.innerHTML+=`<td><span data-type="text" style="text-align: center">${res[i]['p_lu']}</span></td>`
                        tr.innerHTML+=`<td style="text-align: center"><img src="assets/images/icons/ober_trash.png" style="height: 15px;"  onclick="delete_record(this.parentNode.parentNode.getAttribute('data-id'))"/></td>`
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
                    }, 300);
                }
            })
        }

        function delete_record(id){
            $.ajax({
                url:'/remove_astragaz/'+id,
                data: 1,
                type:'GET',
                success:(res)=>{
                    getTableData()
                }
            })
        }

    </script>

<style>
    .text-field__input {
        display: block;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #bdbdbd;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .text-field__input::placeholder {
        color: #212529;
        opacity: 0.4;
    }

    .text-field__input:focus {
        color: #212529;
        background-color: #fff;
        border-color: #bdbdbd;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(158, 158, 158, 0.25);
    }

    .text-field__input:disabled,
    .text-field__input[readonly] {
        background-color: #f5f5f5;
        opacity: 1;
    }
</style>
@endsection


