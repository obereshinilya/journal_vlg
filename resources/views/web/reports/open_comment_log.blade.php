@extends('layouts.app')
@section('title')
    Журнал замечаний
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
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{asset('assets/css/table.css')}}">
        <link rel="stylesheet" href="{{asset('assets/libs/tooltip/tooltip.css')}}">
    @endpush
    <div style="width: 100%; display: inline-flex; justify-content: space-between">
        <h3>Журнал замечаний</h3>
        <button id="add_record" onclick="window.location.href='/create_comment_log'" class="button button1"
                style="float: left; margin-top: 1%">Создать замечание
        </button>
        {{--        <button id="print" class="button button1" style="float: left; margin-top: 1%">Печать</button>--}}
    </div>

    <div id="content-header" style="display: inline-flex; width: 100%">
    </div>

    <div id="tableDiv" style="width: 100%; overflow-y: auto">
        <table id="statickItemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table">

            <thead>
            <tr>
                <th style="text-align: center; width: 3%">№</th>
                <th style="text-align: center; width: 40%">Замечание</th>
                <th style="text-align: center">Создатель</th>
                <th style="text-align: center">Дата создания</th>
                <th style="text-align: center">Статус</th>
                <th style="text-align: center">Дата изменения</th>
                <th style="text-align: center; width: 3%"></th>
            </tr>

            </thead>
            <tbody id="table_body">
            <tr>
                <td colspan="7"><span>Данных нет</span></td>
            </tr>
            </tbody>
        </table>

    </div>



    <script>

        $(document).ready(function () {
            get_table()
            // $('#year').change(function () {
            //     get_table($('#year').val())
            // })
            // $('#print').click(function () {
            //     window.location.href = '/print_ppr/' + $('#year').val()
            // });
        })

        function get_table() {
            $.ajax({
                url: '/get_comment_log/',
                method: 'GET',
                success: function (res) {
                    var body = document.getElementById('table_body')
                    body.innerText = ''
                    let font_size = localStorage.getItem('font') * 14;
                    var lenght = res.length
                    let i = 1;
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['id'])
                        tr.innerHTML += `<td style="text-align: center; font-size:${font_size}px " class="id">${i}</td>`
                        if (row['remark']) {
                            tr.innerHTML += `<td style="text-align: center; font-size:${font_size}px" class="object">${row['remark']}</td>`
                        } else {
                            tr.innerHTML += `<td style="text-align: center; font-size:${font_size}px" class="object">-</td>`
                        }
                        tr.innerHTML += `<td style=" text-align: center; font-size:${font_size}px">${row['creator']}</td>`
                        tr.innerHTML += `<td style=" text-align: center; font-size:${font_size}px"><input disabled class="creation_date" style="border: none; width: 95%; font-size:${font_size}px; text-align: center" type="date" value="${row['created_at'].split('T')[0]}"<br> <input disabled  style="border: none; width: 95%; text-align: center; margin-top: 3px" type="time" value=${row['created_at'].split('T')[1].split('.')[0]} /></td>`
                        tr.innerHTML += `<td style=" text-align: center; font-size:${font_size}px">${row['status']}</td>`
                        tr.innerHTML += `<td style=" text-align: center; font-size:${font_size}px"><input disabled class="creation_date" style="border: none; width: 95%; font-size:${font_size}px; text-align: center" type="date" value="${row['updated_at'].split('T')[0]}"<br> <input disabled  style="border: none; width: 95%; text-align: center; margin-top: 3px" type="time" value=${row['updated_at'].split('T')[1].split('.')[0]} /></td>`

                        tr.innerHTML += `<td style="text-align: center"><img onclick="window.location.href='/edit_comment_log/'+this.parentNode.parentNode.dataset['id']" style="width: 15px; margin-right:10px" src="{{asset('assets/images/icons/search.svg')}}"> <img onclick="remove_record(this.parentNode.parentNode.getAttribute('data-id'))" style="width: 15px" src="assets/images/icons/ober_trash.png"></td>`
                        body.appendChild(tr);
                        i++
                    }
                },
                async: false
            })
        }

        function remove_record(id) {
            $.ajax({
                url: '/delete_record_comment_logs/' + id,
                method: 'get',
                success: function () {
                    get_table()
                },
                async: false
            })
        }


    </script>
    @include('include.font_size-change')
    <style>
        .itemInfoTable span {
            text-align: center;
        }

        tr:hover,
        tr:hover input {
            font-weight: bold;
        }

        .itemInfoTable tr:nth-child(odd) {
            background: #e9effc;
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

        input[type=date]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            display: none;
        }

        input[type=date]::-webkit-clear-button {
            -webkit-appearance: none;
            display: none;
        }

        .date_input:placeholder-shown ~ .form__label {
            font-size: 1.3rem;
            cursor: text;
            top: 20px;
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

        .itemInfoTable thead th {
            width: auto;
        }

        img:hover {
            transform: scale(1.3);
        }

        td {
            white-space: pre-wrap; /* css-3 */
            white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
            white-space: -pre-wrap; /* Opera 4-6 */
            white-space: -o-pre-wrap; /* Opera 7 */
            word-wrap: break-word;
        / word-break: break-all;
        }

    </style>


@endsection
