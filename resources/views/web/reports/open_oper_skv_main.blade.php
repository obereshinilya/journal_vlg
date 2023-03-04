@extends('layouts.app')
@section('title')
    Журнал отправки XML
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

    <div id="content-header" style="margin-top: 10px"><h3 style="width: 30%; display: inline-block">Реестр отчетов</h3>
        <button class="button button1" style="float: right; display: inline-block; margin-top: 1%"
                onclick="create_record()">Добавить запись
        </button>
    </div>
    <style>
        .content {
            width: 98%;
        }
    </style>

    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed">
            <colgroup>
                <col style="width: 33%"/>
                <col style="width: 33%"/>
                <col style="width: 33%"/>
            </colgroup>
            <thead style="width: 100%">
            <tr>
                <th style="text-align: center">По состоянию на</th>
                <th style="text-align: center">Последнее изменение</th>
                <th style="text-align: center">Действия</th>
            </tr>
            </thead>
            <tbody style="width: 100%">
            @foreach($to_table as $row)
                <tr>
                    <td class="timestamp" style="text-align: center">{{$row['timestamp']}}</td>
                    <td style="text-align: center">{{$row['edit_at']}}</td>
                    @if($row['timestamp'] == 'Данные отсутствуют')
                        <td style="text-align: center"></td>
                    @else
                        @if($row['content_editable'])
                            <td style="text-align: center">
                                <button class="button button1"
                                        onclick="window.location.href = '/report_oper_skv/'+this.parentNode.parentNode.getElementsByClassName('timestamp')[0].textContent">
                                    Просмотр
                                </button>
                                <button class="button button1" onclick="editable(false, this)"
                                        style="background: #20B2AA">Запретить редактирование
                                </button>
                                <button class="button button1" onclick="remove(this)" style="background: #F08080">
                                    Удалить
                                </button>
                            </td>
                        @else
                            <td style="text-align: center">
                                <button class="button button1"
                                        onclick="window.location.href = '/report_oper_skv/'+this.parentNode.parentNode.getElementsByClassName('timestamp')[0].textContent">
                                    Просмотр
                                </button>
                                <button class="button button1" onclick="editable(true, this)"
                                        style="background: #98FB98">Разрешить редактирование
                                </button>
                                <button class="button button1" onclick="remove(this)" style="background: #F08080">
                                    Удалить
                                </button>
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('include.font_size-change')
    <script>

        $(document).ready(function () {
            $('#itemInfoTable').DataTable({
                "pagingType": "full_numbers",
                destroy: true,
                order: [[0, 'desc']],

            });
        });

        function create_record() {
            $.ajax({
                url: '/create_record_oper_skv',
                method: 'get',
                async: false,
                timeout: 300,
                success: function (res) {
                    window.location.href = '/report_oper_skv_main'
                },
            })
        }

        function remove(button) {
            $.ajax({
                url: '/remove_record_ope_skv/' + button.parentNode.parentNode.getElementsByClassName('timestamp')[0].textContent,
                method: 'get',
                async: false,
                timeout: 300,
                success: function (res) {
                    window.location.href = '/report_oper_skv_main'
                },
            })
        }

        function editable(bool, button) {
            $.ajax({
                url: '/editable_record_ope_skv/' + bool + '/' + button.parentNode.parentNode.getElementsByClassName('timestamp')[0].textContent,
                method: 'get',
                async: false,
                timeout: 300,
                success: function (res) {
                    window.location.href = '/report_oper_skv_main'
                },
            })
        }


    </script>


@endsection


