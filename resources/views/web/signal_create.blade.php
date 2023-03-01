@extends('layouts.app')
@section('title')
    Редактирование сигналов ОЖД
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
    <div>
            <h3>Добавление объектов и сигналов ОЖД</h3>
        <div id="content-header" style="display: none"></div>
        <div style="display:inline-block">
            <button class="button button1" id="create_obj">Добавление объекта</button>
            <button class="button button1" id="create_signal">Добавление сигнала</button>
        </div>

    </div>

<p style="display: none" id="parentId_buff"></p>
    <div id="object_div" style="margin-top: 2%; display: none; width: 100%">
            <table class="itemInfoTable" style="width: 100%; float:left">
                <thead>
                <tr>
                    <th  class="timeCell"  style="width: 50%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Родительский<br>объект</h4></th>
                    <th  class="timeCell"  style="width: 50%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Наименование<br>нового объекта</h4></th>
                </tr>
                </thead>
                <tbody>
                <tr style="width: 15px">
                    <td class="row" style="text-align: center"><input style="width: 80%; text-align: center" type="text" class="parent"  disabled></td>
                    <td class="row" style="text-align: center"><input style="width: 80%; text-align: center" type="text" id="child_name" ></td>
                </tr>
                </tbody>
            </table>
    </div>
    <button class="button button1" id="submit_object" style="display: none; margin-left: 46%">Сохранить</button>

    <div id="signal_div_1" style="margin-top: 2%; display: none; width: 100%">
        <table class="itemInfoTable" style="width: 100%; float:left">
            <thead>
            <tr>
                <th  class="timeCell"  style="width: 25%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Родительский<br>объект</h4></th>
                <th  class="timeCell"  style="width: 25%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Наименование<br>нового сигнала</h4></th>
                <th  class="timeCell"  style="width: 25%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Ед.изм</h4></th>
                <th  class="timeCell"  style="width: 25%; text-align: center; padding-bottom: 0px; padding-top: 0px"><h4>Имя тега</h4></th>
            </tr>
            </thead>
            <tbody>
            <tr style="width: 15px">
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" class="parent"  disabled></td>
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="child_name_signal" ></td>
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="shortname" ></td>
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="tagname" ></td>
            </tr>
            </tbody>

        </table>
    </div>
    <div id="signal_div_2" style="margin-top: 2%; display: none; width: 100%">
        <table class="itemInfoTable" style="width: 100%; float:left">
            <thead>
            <tr>
                <th colspan="3" style="width: 60%; text-align: center">Идентификатор М АСДУ ЕСГ (при необходимости)</th>
                <th colspan="3" style="width: 40%; text-align: center">Отображение в ОЖД</th>
            </tr>
            <tr>
                <th style="text-align: center; width: 20%">РВ</th>
                <th style="text-align: center; width: 20%">Часовой</th>
                <th style="text-align: center; width: 20%">Суточный</th>
                <th style="text-align: center; width: 13%">РВ</th>
                <th style="text-align: center; width: 13%">Часовой</th>
                <th style="text-align: center; width: 14%">Суточный</th>
            </tr>
            </thead>
            <tbody>
            <tr style="width: 15px">
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="masdu_rv" ></td>
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="masdu_hour" ></td>
                <td class="row" style="text-align: center"><input style="width: 90%; text-align: center" type="text" id="masdu_day" ></td>
                <td class="row" style="text-align: center; padding: 0px; margin: 0px"><label class="switch" style="padding: 0px; margin: 0px"><input id="ojd_rv" type="checkbox"><span class="slider"></span></label></td>
                <td class="row" style="text-align: center; padding: 0px; margin: 0px"><label class="switch" style="padding: 0px; margin: 0px"><input id="ojd_hour" type="checkbox"><span class="slider"></span></label></td>
                <td class="row" style="text-align: center; padding: 0px; margin: 0px"><label class="switch" style="padding: 0px; margin: 0px"><input id="ojd_day" type="checkbox"><span class="slider"></span></label></td>
{{--                <td class="row" style="text-align: center"> <input type="checkbox" id="ojd_rv"> </td>--}}
{{--                <td class="row" style="text-align: center"> <input type="checkbox" id="ojd_hour"> </td>--}}
{{--                <td class="row" style="text-align: center"> <input type="checkbox" id="ojd_day"> </td>--}}
            </tr>
            </tbody>

        </table>
    </div>
    <button class="button button1" id="submit_signal" style="display: none; margin-left: 46%">Сохранить</button>


    <script>
        var header_content = 'Создание объектов ОЖД. ';
        // function showMe(box){
        //     var vis = (box.checked) ? "block" : "none";
        //     document.getElementById(box.classList[0]).style.display = vis;
        // }


        $(document).ready(function () {
            click_side_menu_func = show_hide;
            $('#create_obj').click(function () {
                document.getElementById('create_obj').style.background = '#1ab585'
                document.getElementById('create_signal').style.background = 'white'
                document.getElementById('object_div').style.display = 'flex'
                document.getElementById('submit_object').style.display = ''
                document.getElementById('submit_signal').style.display = 'none'
                document.getElementById('signal_div_1').style.display = 'none'
                document.getElementById('signal_div_2').style.display = 'none'
            })
            $('#create_signal').click(function () {
                document.getElementById('create_signal').style.background = '#1ab585'
                document.getElementById('create_obj').style.background = 'white'
                document.getElementById('signal_div_1').style.display = 'flex'
                document.getElementById('signal_div_2').style.display = 'flex'
                document.getElementById('submit_signal').style.display = ''
                document.getElementById('object_div').style.display = 'none'
                document.getElementById('submit_object').style.display = 'none'
            })

            $('#submit_object').click(function () {
                var name_new_obj = document.getElementById('child_name').value
                var parentId = document.getElementById('parentId_buff').textContent
                if (name_new_obj !== '' && parentId !== ''){
                    var data = {parentId: parentId, namepar1: name_new_obj}
                    $.ajax({
                        url:'/store_object',
                        type:'POST',
                        data: data,
                        success:(res)=>{
                            open_modal_ober('Данные успешно сохранены!')
                        },
                        async: true
                    });
                    document.location.href = '/signal_create'
                } else {
                    open_modal_ober('Неверно заполнена форма!')

                }
            })

            $('#submit_signal').click(function () {
                var name_new_obj = document.getElementById('child_name_signal').value
                var shortname = document.getElementById('shortname').value
                var tagname = document.getElementById('tagname').value
                var parentId = document.getElementById('parentId_buff').textContent
                var masdu_day = document.getElementById('masdu_day').value
                var masdu_hour = document.getElementById('masdu_hour').value
                var masdu_rv = document.getElementById('masdu_rv').value
                if (document.getElementById('ojd_day').checked){
                    var ojd_day = true
                } else {
                    var ojd_day = false
                }
                if (document.getElementById('ojd_hour').checked){
                    var ojd_hour = true
                } else {
                    var ojd_hour = false
                }
                if (document.getElementById('ojd_rv').checked){
                    var ojd_rv = true
                } else {
                    var ojd_rv = false
                }
                var data = {name_new_obj: name_new_obj, shortname: shortname, tagname: tagname, parentId: parentId, masdu_day: masdu_day, masdu_hour: masdu_hour,
                    masdu_rv: masdu_rv, ojd_day: ojd_day, ojd_hour: ojd_hour, ojd_rv: ojd_rv,
                    }
                console.log(data)
                if (name_new_obj !== '' && parentId !== '' && tagname !== ''){
                    $.ajax({
                        url:'/store_signal',
                        type:'POST',
                        data: data,
                        success:(res)=>{
                            open_modal_ober('Данные успешно сохранены!')
                        },
                        async: true
                    });
                    document.location.href = '/signal_create'
                } else {
                    open_modal_ober('Неверно заполнена форма!')
                }
            })
        })

        function show_hide() {
        }

    </script>

    <style>
        input:checked + .slider{
            background-color: #2FD059;
        }
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
