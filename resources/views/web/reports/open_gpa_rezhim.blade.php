@extends('layouts.app')
@section('title')
    Режим работы ГПА
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
        <h3 >Режим работы ГПА</h3>
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>



    <div id="tableDiv" style="width: 1500px">
        <table id="statickItemInfoTable" class="itemInfoTable" style="">
            <thead>
            <tr><th style="text-align: center" colspan="6">ГКП-2</th></tr>
            <tr>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-1</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-2</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-3</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-4</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-5</h4></th>
                <th style="width: 17%; text-align: center"><h4>ГПА-6</h4></th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td style="width: 17%; text-align: center"><select id="gpa11"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select id="gpa12"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select id="gpa13"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select id="gpa14"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select id="gpa15"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select id="gpa16"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
            </tr>
            </tbody>
            <thead>
            <tr><th style="text-align: center" colspan="6">ГКП-2В</th></tr>
            <tr>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-1</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-2</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-3</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-4</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-5</h4></th>
                <th style="width: 17%; text-align: center; padding: 0px"><h4>ГПА-6</h4></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="width: 17%; text-align: center"><select  id="gpa21"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select  id="gpa22"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select  id="gpa23"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select  id="gpa24"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select  id="gpa25"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
                <td style="width: 17%; text-align: center"><select  id="gpa26"><option value="Горячий резерв">Горячий резерв</option><option value="Холодный резерв">Холодный резерв</option><option value="Кольцо">Кольцо</option><option value="Магистраль">Магистраль</option><option value="Нет режима">Нет режима</option></select></td>
            </tr>
            </tbody>
        </table>
        <button  id="solve" class="button button1" style="margin-left: 26%">Сохранить</button>
        <div style="overflow-y: auto; height: 500px" id="tableDiv">
            <table id="itemInfoTable"  class="itemInfoTable" style="display: table; table-layout: fixed; margin-top: 15px; overflow-y: auto">
                <thead>
                <th style="text-align: center">№ ГПА</th>
                <th style="text-align: center">Статус</th>
                <th style="text-align: center">Дата перехода</th>
                </thead>
                <tbody id="rezhim_table">

                </tbody>
            </table>
        </div>
    </div>
    <div>


    </div>



    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>

        var datatable = null;
        $(document).ready(function () {
            get_table_data()
            data_rezhim_table()
            $('#solve').click(function() {
                post_table_data();
            });

        })

        function data_rezhim_table(){
        get_table_data()
            $.ajax({
                url: '/get_rezhim_table',
                method: 'GET',
                success: function (res) {
                    var static_table_body = document.getElementById('rezhim_table')
                    static_table_body.innerText = ''
                    for (var row of res){
                        var tr = document.createElement('tr')
                        if (String(row['number_gpa'])[0] == '1'){
                            tr.innerHTML += `<td style="text-align: center">ГКП-2 ГПА-${String(row['number_gpa'])[1]}</td>`
                        }else {
                            tr.innerHTML += `<td style="text-align: center">ГКП-2В ГПА-${String(row['number_gpa'])[1]}</td>`
                        }
                        tr.innerHTML += `<td style="text-align: center">${row['rezhim']}</td>`
                        tr.innerHTML += `<td style="text-align: center">${row['timestamp']}</td>`
                        static_table_body.appendChild(tr);
                    }
$('#itemInfoTable').DataTable({
                        "pagingType": "full_numbers",
                        destroy: true,
                        order: [[2, 'desc']],

                    });


                },
                async:true
            })
        }

         function get_table_data() {

             $.ajax({
                url: '/get_gpa_rezhim/',
                method: 'GET',
                success: function (res) {
                    for (var i = 11; i<27; i++){
                        if (res[i]){
                            var select = document.getElementById('gpa'+res[i]['number_gpa'])
                            select.value = res[i]['rezhim']
                        }
                    }
                },
                async:true
            })
        }

        function post_table_data() {
            var arr = new Map()
            for (var i = 11; i<27; i++){
                if (document.getElementById('gpa'+i)){
                    arr.set('gpa'+i, document.getElementById('gpa'+i).value)
                }
            }
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/post_gpa_rezhim',
                data: data,
                method: 'POST',
                success: function (res) {
                    open_modal_ober('Данные сохранены!')
                    data_rezhim_table()

                },
                async:true
            })
            get_table_data()
        }





    </script>
    <style>
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
    </style>


@endsection
