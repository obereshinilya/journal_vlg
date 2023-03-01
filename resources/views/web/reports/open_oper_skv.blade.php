@extends('layouts.app')
@section('title')
    Оперативное состояние режима работы ПХГ
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

    <div style="display: inline-flex; width: 100%">
        <h3>Оперативное состояние режима работы ПХГ</h3>

        <div style="position: absolute; right: 50px; margin-top: 10px">
            <button class="button button1" style="float: right; margin-top: 1%" onclick="open_modal_export_ober()">Экспорт</button>
            <button  id="setting" class="button button1" style="margin-top: 1%; float: right">На главную</button>
        </div>

    </div>



    <style>
        .content{ width: calc(100% - 40px); overflow-y: hidden; height: calc(100% - 90px);}
        table.iksweb{text-decoration: none;border-collapse:collapse;width:100%;text-align:center;}
        table.iksweb th{font-weight:normal;font-size:12px; color:#ffffff;background-color:#347c99;}
        table.iksweb td{font-size:13px;color:#347c99;}
        table.iksweb td,table.iksweb th{white-space:pre-wrap;padding:5px 2px;line-height:13px;vertical-align: middle;border: 1px solid black;}
        table.iksweb tr:hover{background-color:#f9fafb}
        table.iksweb tr:hover td{color:#347c99;cursor:default;}
        .table_scroll{overflow-y: scroll; height: calc(100% - 80px)}
    </style>

    <p id="head" style="display: none" contenteditable="true"></p>
    <div class="table_scroll">
        <table class="iksweb">
            <tbody>
            <tr>
                <td id="timestamp">{{$timestamp}}</td>
                <th colspan="2" rowspan="2">ТТР H2O на ГИС, град. С</th>
                <th colspan="5">Отбор</th>
                <th rowspan="3">Рпл, кгс/см2</th>
            </tr>
            <tr>
                <th rowspan="2">УПХГ</th>
                <th colspan="2">Темп отбора, тыс. м3/час</th>
                <th colspan="2">Давление на ГИС/ПЗРГ, кгс/см2</th>
                <th rowspan="2">Давления по ТС, кг/см2</th>
            </tr>
            <tr>
                <th>2ч</th>
                <th>5мин</th>
                <th>2ч</th>
                <th>5мин</th>
                <th>2ч</th>
                <th>5м</th>
            </tr>
            @foreach($uphg as $row)
                <tr>
                    @if($row['main'])
                        @if($row['th'])
                            <th>{{$row['name']}}</th>
                            @for($i=1; $i<7; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                            <td rowspan="3" contenteditable="true" id="{{$row['short'].'_7'}}" onblur="save_td(this)">...</td>
                            <td contenteditable="true" id="{{$row['short'].'_8'}}" onblur="save_td(this)">...</td>
                        @else
                            <td style="text-align: right">{{$row['name']}}</td>
                            @for($i=1; $i<8; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @endif
                    @else
                        @if($row['th'])
                            <th>{{$row['name']}}</th>
                            @for($i=1; $i<9; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @else
                            @for($i=1; $i<10; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @endif
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script>

        $(document).ready(function (){
            getTableData();
            $('#setting').click(function() {
                window.location.href = '/report_oper_skv_main'
            });
            $('.overlay_ober').click(function() {
                location.reload()
            });

        });
        function getTableData(){
            $.ajax({
                url: '/get_data_oper_skv/'+document.getElementById('timestamp').textContent,
                method: 'get',
                async:false,
                success: function (res) {
                    for (var td of res){
                        if (td['content_editable']){
                            document.getElementById(td['id_td']).textContent = td['text']
                        }else {
                            document.getElementById(td['id_td']).textContent = td['text']
                            document.getElementById(td['id_td']).removeAttribute("contenteditable")
                            console.log(document.getElementById(td['id_td']))
                        }
                    }
                },
            })
        }

        function save_td(td){
            if (document.getElementById('head').getAttribute('contenteditable')){
                $.ajax({
                    url: '/save_td_oper/'+td.id+'/'+td.textContent+'/'+document.getElementById('head').textContent,
                    method: 'get',
                    async:false,
                    timeout:300,
                    success: function (res) {

                    },
                })
            }else {
                open_modal_ober('Редактирование запрещено!')
            }
        }

        function CallPrint(){
            window.location.href = '/print_oper_skv/'+document.getElementById('timestamp').textContent
        }
        function CallExcel(){
            window.location.href = '/excel_oper_skv/'+document.getElementById('timestamp').textContent
            document.getElementById('modal_export_ober').style.display = 'none'
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


