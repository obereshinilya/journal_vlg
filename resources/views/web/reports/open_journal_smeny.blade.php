@extends('layouts.app')
@section('title')
    Журнал смены
@endsection
@section('side_menu')
    @include('web.reports.side_menu_reports')
@endsection
@section('content')

    @push('scripts')
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
    <style>
        .insert_table .col1 {

        }
    </style>
    <style>
        .modal_print {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal_print .overlay_print {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000;
            opacity: 0.7;
            z-index: 9999899;
        }

        .modal-window-print {
            z-index: 9999999;
            position: relative;
            width: 500px;
            border-radius: 10px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, .4);
            background-color: #fff;
            padding: 20px;
        }
    </style>
    <div style="display: inline-flex; width: 100%">
        <h3>Сменный журнал ООО "Газпром ПХГ" за диспетчерские сутки</h3>
        <div class="date-input-group" style="margin-left: 2%">
            <input type="date" id="table_date_start" class="date_input" required onkeydown="return false">
            <label for="table_date_start" class="table_date_label">Дата</label>
        </div>
        <h5 style="opacity: 0.6; margin-left: 20px">Диспетчерское задание:</h5>
        <h5 style="opacity: 0.6; margin-left: 10px" id="dz"></h5>
        <div style="position: absolute; right: 50px; margin-top: 10px">
            <button onclick="window.location.href = '' " class="button button1" style="float: left; margin-top: 1%">Журнал принятия смены</button>
            <button id="print" class="button button1" style="float: left; margin-top: 1%">Печать</button>
            <button id="dz" class="button button1" style="float: left; margin-top: 1%">Журнал ДЗ</button>
        </div>

    </div>

    <div id="content-header" style="display: inline-flex; width: 100%">
    </div>
    <div id="tableDiv" style="width: 100%; font-size: 14px">
        <div style="width: 100%; margin: 0px; padding: 0px">
            <table class="iksweb" id="shapka"
                   style="table-layout: fixed; width: 100%; display: table; padding: 0px; margin: 0px">
                <tbody>
                <tr style="border: 2px solid black">
                    <th colspan="2">Выдано для ООО "Газпром ПХГ"</th>
                    <td contenteditable="true" class="changeble_td cpdd_all" ondblclick="change_color_td(this)"
                        old-data="0" onblur="update_math(this.id, this.textContent)" id="cpdd_all"
                        style="background: rgba(0, 0, 0, 0)">0
                    </td>
                    <th colspan="2">Распределено по УПХГ</th>
                    <td id="cpdd_spend" style="background: rgba(0, 0, 0, 0)">0</td>
                    <th colspan="2">Осталось распределить</th>
                    <td id="cpdd_left" style="background: rgba(0, 0, 0, 0)">0</td>
                    <th>Комментарий</th>
                    <td colspan="2" id="comment_cpdd" contenteditable="true" class="changeble_td"
                        ondblclick="change_color_td(this)" oncontextmenu="replace(this.id, 'td')"
                        onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th rowspan="2">ПХГ</th>
                    <th rowspan="2">Задание</th>
                    <th rowspan="2">Факт</th>
                    <th rowspan="2">Отклонение</th>
                    <th colspan="2">Смена</th>
                    <th rowspan="2">ПХГ</th>
                    <th rowspan="2">Задание</th>
                    <th rowspan="2">Факт</th>
                    <th rowspan="2">Отклонение</th>
                    <th colspan="2">Смена</th>
                </tr>
                <tr>
                    <th>08:00-20:00</th>
                    <th>20:00-08:00</th>
                    <th>08:00-20:00</th>
                    <th>20:00-08:00</th>
                </tr>
                <tr>
                    <th>П.Уметское ПХГ</th>
                    <td id="header_1" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_2" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_3" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_4" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_5" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Невское ПХГ</th>
                    <td id="header_6" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_7" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_8" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_9" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_10" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Елшанское ПХГ</th>
                    <td id="header_11" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_12" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_13" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_14" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_15" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Гатчинское ПХГ</th>
                    <td id="header_16" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_17" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_18" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_19" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_20" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Степновское ПХГ</th>
                    <td id="header_21" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_22" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_23" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_24" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_25" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Калининградское ПХГ</th>
                    <td id="header_26" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_27" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_28" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_29" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_30" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Похвостневское УПХГ</th>
                    <td id="header_31" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_32" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_33" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_34" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_35" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Волгоградское УПХГ</th>
                    <td id="header_36" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_37" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_38" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_39" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_40" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Похвостневская пром.пл.</th>
                    <td id="header_41" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_42" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_43" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_44" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_45" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>с.Ставропольское ПХГ</th>
                    <td id="header_46" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_47" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_48" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_49" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_50" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Отрадневская пром.пл.</th>
                    <td id="header_51" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_52" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_53" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_54" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_55" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Краснодарское ПХГ</th>
                    <td id="header_56" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_57" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_58" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_59" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_60" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Щелковское ПХГ</th>
                    <td id="header_61" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_62" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_63" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_64" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_65" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Кущевское ПХГ</th>
                    <td id="header_66" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_67" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_68" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_69" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_70" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Калужское ПХГ</th>
                    <td id="header_71" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_72" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_73" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_74" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_75" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Канчуринское ПХГ</th>
                    <td id="header_76" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_77" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_78" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_79" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_80" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Касимовское УПХГ</th>
                    <td id="header_81" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_82" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_83" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_84" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_85" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Пунгинское ПХГ</th>
                    <td id="header_86" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_87" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_88" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_89" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_90" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                <tr>
                    <th>Совхозное ПХГ</th>
                    <td id="header_91" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_92" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_93" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_94" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_95" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <th>Карашурское ПХГ</th>
                    <td id="header_96" contenteditable="true" class="changeble_td zadanie"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_97" contenteditable="true" class="changeble_td fact"
                        ondblclick="change_color_td(this)" old-data="0"
                        onblur="update_math(this.id, this.textContent)"></td>
                    <td id="header_98" class="changeble_td" ondblclick="change_color_td(this)"
                        onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_99" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                    <td id="header_100" contenteditable="true" class="changeble_td" ondblclick="change_color_td(this)"
                        oncontextmenu="replace(this.id, 'td')" onblur="save_td(this.id, this.textContent)"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 100%; margin: 0px; padding: 0px">
            <table class="iksweb" style="table-layout: fixed; padding: 0px; margin: 0px; padding-left: 1px">
                <col style="width: 8%">
                <col style="width: 5%">
                <col style="width: 8%">
                <col style="width: 8%">
                <col style="width: 69.5%">
                <tbody>
                <tr style="">
                    <th colspan="2">Объект</th>
                    <th colspan="3">
                        <table
                            style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 5%">
                            <tbody>
                            <tr style="position: sticky; top: 0">
                                <td style=" text-align: center; border: 0px">Оборудование</td>
                                <td style=" text-align: center; border: 0px">Статус</td>
                                <td style=" text-align: center; border: 0px">Дата/Время/Описание работ</td>
                                <td style=" text-align: center; border: 0px"></td>
                            </tr>
                            </tbody>
                        </table>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">П.Уметское ПХГ
                        <img id="phg1_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg1_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Елшанское ПХГ
                        <img id="phg2_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg2_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Степновское ПХГ
                        <img id="phg3_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg3_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Похвостневское УПХГ
                        <img id="phg4_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg4_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Похвостневская пром.пл.
                        <img id="phg5_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg5_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Отрадневская пром.пл.
                        <img id="phg6_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg6_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Щелковское ПХГ
                        <img id="phg7_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg7_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">П.Калужское ПХГ
                        <img id="phg8_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg8_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Касимовское ПХГ
                        <img id="phg9_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg9_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Совхозное ПХГ
                        <img id="phg10_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg10_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Невское ПХГ
                        <img id="phg11_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg11_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Гатчинское ПХГ
                        <img id="phg12_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg12_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Калининградское ПХГ
                        <img id="phg13_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg13_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Волгоградское ПХГ
                        <img id="phg14_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg14_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">с.Ставропольское ПХГ
                        <img id="phg15_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg15_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Краснодарское ПХГ
                        <img id="phg16_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg16_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Кущевское ПХГ
                        <img id="phg17_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg17_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Канчуринское ПХГ
                        <img id="phg18_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg18_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Пунгинское ПХГ
                        <img id="phg19_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg19_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Карашурское ПХГ
                        <img id="phg1_plus" src="assets/images/icons/ober_plus.png"
                             style="width: 12%; float: right; margin-right: 5%" onclick="add_new_record(this.id)"/>
                    </td>
                    <td colspan="3">
                        <table id="phg1_table"
                               style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">
                            <col style="width: 3%">
                            <col style="width: 5%">
                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal_print" id="modal_print">
        <div class="modal-window-print">
            <p style="float: right; top: 0px; margin: 0px; opacity: 0.5"
               onclick="document.getElementById('modal_print').style.display = 'none'">x</p>
            <h1 id="text_modal">Печать: </h1>
            <div style="display: flex; align-items: center; flex-direction: column">
                <select class="date_input" id="print_type" onchange="selection_change(this)">
                    <option value="day">За день</option>
                    <option value="period">За Период</option>
                </select>
                <div id="print_period" style="display: none; justify-content: space-between; width: 100%">
                    <input type="date" id="table_print_start" class="date_input" style="width: 45%" required
                           onkeydown="return false">
                    <input type="date" id="table_print_end" class="date_input" style="width: 45%" required
                           onkeydown="return false">
                </div>
                <button class="button button1" onclick="print_pdf()">Печать</button>
            </div>
        </div>
        <div class="overlay_print" onclick="this.parentNode.style.display = 'none'">
        </div>
    </div>


    <script>

        function print_pdf() {
            switch (document.getElementById('print_type').value) {
                case 'day':
                    window.location.href = '/print_journal_smeny/' + $('#table_date_start').val()
                    break;
                case 'period':
                    window.location.href = '/print_journal_smeny/' + $('#table_print_start').val() + '/' + $('#table_print_end').val()
                    break;
            }
        }

        function selection_change(el) {
            switch (el.value) {
                case 'day':
                    document.getElementById('print_period').style.display = 'none';
                    break;
                case 'period':
                    document.getElementById('print_period').style.display = 'flex';
                    break;
            }
        }

        $(document).ready(function () {
            var today = new Date();
            if (today.getHours() < 8) {
                today.setDate(today.getDate() - 1)
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            } else {
                $('#table_date_start').val(today.toISOString().substring(0, 10))
            }
            document.getElementById("table_date_start").setAttribute("max", today.toISOString().substring(0, 10));
            document.getElementById("table_print_start").setAttribute("max", today.toISOString().substring(0, 10));
            document.getElementById("table_print_end").setAttribute("max", today.toISOString().substring(0, 10));
            get_insert_tables()
            get_tds()
            check_last_dz()
            $('#print').click(function () {
                document.getElementById('modal_print').style.display = 'flex'
                // window.location.href = '/print_journal_smeny/' + $('#table_date_start').val()
            });
            $('#dz').click(function () {
                window.location.href = '/journal_dz'
            });

            $('#table_date_start').change(function () {
                var tds = document.getElementsByClassName('changeble_td')
                for (var td of tds) {
                    td.textContent = ''
                }
                var trs = document.getElementsByClassName('changeble_tr')
                for (var tr of trs) {
                    td.textContent = ''
                }
                get_insert_tables()
                get_tds()
            })
        })
        function check_last_dz(){
            $.ajax({
                url: '/last_DZ/' + $('#table_date_start').val(),
                method: 'get',
                success: function (res) {
                    document.getElementById('dz').textContent = res
                },
                async: true
            })
        }
        function get_tds() {
            $.ajax({
                url: '/get_tds/' + $('#table_date_start').val(),
                method: 'get',
                success: function (tds) {
                    for (var td of tds) {
                        try {
                            var one_td = document.getElementById(td['id_record'])
                            one_td.setAttribute('old-data', td['val'])
                            one_td.textContent = td['val']
                            one_td.style.color = td['color_text']
                            one_td.style.fontWeight = td['text_weight']
                        } catch (e) {

                        }
                    }
                },
                async: false
            })
        }

        function save_td(id, text) {
            var arr = new Map()
            arr.set('id_record', id)
            arr.set('val', text)
            arr.set('date', $('#table_date_start').val())
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/save_td',
                data: data,
                method: 'POST',
                success: function (res) {
                },
                async: true
            })
        }

        function update_math(id, text) {
            document.getElementById(id).textContent = text.replace(",", ".")
            text = text.replace(",", ".")
            if (isNaN(Number(text))) {
                open_modal_ober('Неверный формат числа!')
                document.getElementById(id).style.background = 'red'
            } else {
                document.getElementById(id).style.background = 'white'
                if (document.getElementById(id).classList.contains('zadanie')) {
                    var zadanie = Number(text)
                    var old_zadanie = Number(document.getElementById(id).getAttribute('old-data'))
                    document.getElementById(id).setAttribute('old-data', text)
                    save_td(id, text)
                    document.getElementById('cpdd_spend').textContent = Number(document.getElementById('cpdd_spend').textContent) - old_zadanie + zadanie
                    save_td('cpdd_spend', document.getElementById('cpdd_spend').textContent)
                    document.getElementById('cpdd_left').textContent = Number(document.getElementById('cpdd_all').textContent) - Number(document.getElementById('cpdd_spend').textContent)
                    save_td('cpdd_left', document.getElementById('cpdd_left').textContent)
                    var fact_id = id.split('_')[0] + '_' + Number(Number(id.split('_')[1]) + 1)
                    var otkl_id = id.split('_')[0] + '_' + Number(Number(id.split('_')[1]) + 2)
                    document.getElementById(otkl_id).textContent = Number(document.getElementById(fact_id).textContent) - zadanie
                    save_td(otkl_id, document.getElementById(otkl_id).textContent)
                } else if (document.getElementById(id).classList.contains('fact')) {
                    var fact = Number(text)
                    save_td(id, text)
                    var zadanie_id = id.split('_')[0] + '_' + Number(Number(id.split('_')[1]) - 1)
                    var otkl_id = id.split('_')[0] + '_' + Number(Number(id.split('_')[1]) + 1)
                    document.getElementById(otkl_id).textContent = fact - Number(document.getElementById(zadanie_id).textContent)
                    save_td(otkl_id, document.getElementById(id).textContent)
                } else if (document.getElementById(id).classList.contains('cpdd_all')) {
                    var zadanie = Number(text)
                    save_td(id, text)
                    document.getElementById('cpdd_left').textContent = zadanie - Number(document.getElementById('cpdd_spend').textContent)
                    save_td('cpdd_left', document.getElementById('cpdd_left').textContent)
                }
            }
        }

        function add_new_record(id) {
            var object = id.split('_')[0]
            var table_id = object + '_table'
            var body = document.getElementById(table_id).getElementsByTagName('tbody')[0]
            var tr = document.createElement('tr')
            tr.innerHTML += `<td class="oborudovanie" style="text-align: center; background-color: white" contenteditable="true"></td>`
            tr.innerHTML += `<td class="status" style="text-align: center; background-color: white" contenteditable="true"></td>`
            tr.innerHTML += `<td colspan="2" class="date" style="auto; text-align: center; background-color: white" contenteditable="true"></td>`
            tr.innerHTML += `<td style="text-align: center; padding: 0px">
                                <img id="${object + '_save'}" src="assets/images/icons/ober_send.png" style="height: 20px; margin-right: 15%" onclick="save_new_record(this.id)"/>
                                <img id="${object + '_cancel'}" src="assets/images/icons/ober_plus.png" style="height: 20px; transform: rotate(45deg)"  onclick="cancel(this.id)"/>
                            </td>`
            body.appendChild(tr);
            document.getElementById(id).style.display = 'none'
        }

        function cancel(id) {
            var object = id.split('_')[0]
            var trs = document.getElementById(object + '_table').getElementsByTagName('tbody')[0].getElementsByTagName('tr')
            document.getElementById(object + '_plus').style.display = ''
            trs[trs.length - 1].remove()
        }

        function save_new_record(id) {
            var table = document.getElementById(id.split('_')[0] + '_table')
            var arr = new Map()
            arr.set('oborudovanie', table.getElementsByClassName('oborudovanie')[table.getElementsByTagName('tr').length - 1].textContent)
            arr.set('status', table.getElementsByClassName('status')[table.getElementsByTagName('tr').length - 1].textContent)
            arr.set('date', table.getElementsByClassName('date')[table.getElementsByTagName('tr').length - 1].textContent)
            arr.set('name_table', id.split('_')[0] + '_table')
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/save_journal_smeny/' + $('#table_date_start').val(),
                data: data,
                method: 'POST',
                success: function (res) {
                    get_insert_tables(id.split('_')[0])
                },
                async: false
            })
            document.getElementById(id.split('_')[0] + '_plus').style.display = ''
        }

        function get_insert_tables(name_table) {
            if (name_table) { //при добавлении записи, отмене
                $.ajax({
                    url: '/get_insert_tabels/' + $('#table_date_start').val() + '/' + name_table + '_table',
                    method: 'get',
                    success: function (tables) {
                        var body = document.getElementById(name_table + '_table').getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        for (var row of tables[0]['rows']) {
                            var tr = document.createElement('tr')
                            tr.id = 'record_' + row['id']
                            tr.className = 'changeble_tr'
                            tr.style.minHeight = '20px'
                            var perenos = ''
                            var today = new Date()
                            if (today.getHours() < 8) {
                                today.setDate(today.getDate() - 1)
                                var dispatcher_sutki = today.toISOString().substring(0, 10)
                            } else {
                                var dispatcher_sutki = today.toISOString().substring(0, 10)
                            }
                            if (row['timestamp'] === dispatcher_sutki) {
                                perenos = 'none'
                            }
                            tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="oborudovanie" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['oborudovanie']}</td>`
                            tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="status" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['status']}</td>`
                            tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="date" style="text-align: left; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['date']}</td>`
                            if (row['on_print'] === true)
                                tr.innerHTML += `<td style="text-align: center; vertical-align: center; background-color: white"><input data-id="${row['id']}" onclick="this.value = 'false'; update_record('record_' + this.getAttribute('data-id'))" type="checkbox" checked="checked" value="true" "></td>`
                            else
                                tr.innerHTML += `<td style="text-align: center; vertical-align: center; background-color: white"><input data-id="${row['id']}" onclick="this.value = 'true'; update_record('record_' + this.getAttribute('data-id'))" type="checkbox" value="false" "></td>`

                            tr.innerHTML += `<td style="text-align: center; background-color: white" contenteditable="false">
                                            <img data-id="${row['id']}" table-name="${name_table + '_table'}" src="assets/images/icons/ober_trash.png" style="height: 15px; margin-right: 10%;"  onclick="delete_record(this.getAttribute('data-id'), this.getAttribute('table-name'))"/>
                                            <img data-id="${row['id']}" table-name="${name_table + '_table'}" src="assets/images/icons/ober_strelka.png" style="width: 15px; display: ${perenos}" onclick="replace(this.getAttribute('data-id'), 'tr'); this.style.display = 'none'"/>
                                        </td>`
                            body.appendChild(tr);
                        }
                    },
                    async: false
                })
            } else {   //при запуске
                $.ajax({
                    url: '/get_insert_tabels/' + $('#table_date_start').val() + '/all',
                    method: 'get',
                    success: function (tables) {
                        for (var table of tables) {
                            try {
                                var body = document.getElementById(table['name_table']).getElementsByTagName('tbody')[0]
                                body.innerText = ''
                                for (var row of table['rows']) {
                                    var tr = document.createElement('tr')
                                    tr.style.minHeight = '20px'
                                    tr.id = 'record_' + row['id']
                                    tr.className = 'changeble_tr'
                                    var perenos = ''
                                    var today = new Date()
                                    if (today.getHours() < 8) {
                                        today.setDate(today.getDate() - 1)
                                        var dispatcher_sutki = today.toISOString().substring(0, 10)
                                    } else {
                                        var dispatcher_sutki = today.toISOString().substring(0, 10)
                                    }
                                    if (row['timestamp'] === dispatcher_sutki) {
                                        perenos = 'none'
                                    }
                                    tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="oborudovanie" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['oborudovanie']}</td>`
                                    tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="status" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['status']}</td>`
                                    tr.innerHTML += `<td data-id="${row['id']}" onblur="update_record('record_' + this.getAttribute('data-id'))" oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="date" style="text-align: left; background-color: ${row['color_back']}; color: ${row['color_text']}" contenteditable="true">${row['date']}</td>`
                                    if (row['on_print'] === true)
                                        tr.innerHTML += `<td style="text-align: center; vertical-align: center; background-color: white"><input data-id="${row['id']}" onclick="this.value = 'false'; update_record('record_' + this.getAttribute('data-id'))" type="checkbox" checked="checked" value="true" "></td>`
                                    else
                                        tr.innerHTML += `<td style="text-align: center; vertical-align: center; background-color: white"><input data-id="${row['id']}" onclick="this.value = 'true'; update_record('record_' + this.getAttribute('data-id'))" type="checkbox" value="false" "></td>`

                                    tr.innerHTML += `<td style="text-align: center" contenteditable="false">
                                                <img data-id="${row['id']}" table-name="${table['name_table']}" src="assets/images/icons/ober_trash.png" style="height: 15px; margin-right: 10%;"  onclick="delete_record(this.getAttribute('data-id'), this.getAttribute('table-name') )"/>
                                                <img data-id="${row['id']}" table-name="${name_table + '_table'}" src="assets/images/icons/ober_strelka.png" style="width: 15px; display: ${perenos}" onclick="replace(this.getAttribute('data-id'), 'tr'); this.style.display = 'none'"/>
                                            </td>`
                                    body.appendChild(tr);
                                }
                            } catch (e) {

                            }
                        }
                    },
                    async: false
                })
            }

        }

        function delete_record(id_row, name_table) {
            $.ajax({
                url: '/delete_record/' + id_row,
                method: 'get',
                success: function (tables) {
                    document.getElementById(name_table).getElementsByTagName('tbody')[0].innerText = ''
                    get_insert_tables(name_table.split('_')[0])
                },
                async: false
            })
        }

        function update_record(id_row) {
            var tr = document.getElementById(id_row)
            var arr = new Map()
            arr.set('oborudovanie', tr.getElementsByClassName('oborudovanie')[0].textContent)
            arr.set('status', tr.getElementsByClassName('status')[0].textContent)
            arr.set('date', tr.getElementsByClassName('date')[0].textContent)
            arr.set('on_print', tr.getElementsByTagName('input')[0].value)
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/update_record/' + id_row.split('_')[1],
                method: 'POST',
                data: data,
                success: function (res) {
                },
                async: false
            })
        }

        function change_color(td) {
            var tds = td.parentNode.getElementsByTagName('td')
            if (td.style.backgroundColor === 'white') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.backgroundColor = 'rgb(211, 211, 211)'
                }
            } else if (td.style.backgroundColor === 'rgb(211, 211, 211)') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.backgroundColor = 'rgb(144, 238, 144)'
                }
            } else if (td.style.backgroundColor === 'rgb(144, 238, 144)') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.backgroundColor = 'rgb(255, 255, 0)'
                }
            } else if (td.style.backgroundColor === 'rgb(255, 255, 0)') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.backgroundColor = 'white'
                }
            }
            var id_row = td.parentNode.id
            var arr = new Map()
            arr.set('color_back', td.style.backgroundColor)
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/update_record/' + id_row.split('_')[1],
                method: 'POST',
                data: data,
                success: function (res) {
                },
                async: false
            })
        }

        function change_color_td(td) {
            save_td(td.id, td.textContent)
            if (getComputedStyle(td).getPropertyValue("color") === 'rgb(0, 0, 0)') {   //норм это 400, а жирный 700
                if (getComputedStyle(td).getPropertyValue("font-weight") === '400') {
                    td.style.fontWeight = '700'
                } else {
                    td.style.color = 'rgb(255, 0, 0)'
                    td.style.fontWeight = '700'
                }
            } else if (getComputedStyle(td).getPropertyValue("color") === 'rgb(255, 0, 0)') {
                td.style.color = 'rgb(0, 0, 255'
                td.style.fontWeight = '700'
            } else if (td.style.color === 'rgb(0, 0, 255)') {
                td.style.color = 'rgb(0, 0, 0)'
                td.style.fontWeight = '400'
            }
            var arr = new Map()
            arr.set('color_text', getComputedStyle(td).getPropertyValue("color"))
            arr.set('text_weight', getComputedStyle(td).getPropertyValue("font-weight"))
            arr.set('id_record', td.id)
            arr.set('date', $('#table_date_start').val())
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/change_color_td',
                method: 'POST',
                data: data,
                success: function (res) {
                },
                async: false
            })
        }

        function change_color_text(td) {
            var tds = td.parentNode.getElementsByTagName('td')
            if (td.style.color === 'black') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.color = 'red'
                }
            } else if (td.style.color === 'red') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.color = 'blue'
                }
            } else if (td.style.color === 'blue') {
                for (var i = 0; i < tds.length - 1; i++) {
                    tds[i].style.color = 'black'
                }
            }
            var id_row = td.parentNode.id
            var arr = new Map()
            arr.set('color_text', td.style.color)
            var data = Object.fromEntries(arr)
            $.ajax({
                url: '/update_record/' + id_row.split('_')[1],
                method: 'POST',
                data: data,
                success: function (res) {
                },
                async: false
            })
        }

        function replace(id_row, type) {
            var today = new Date()
            if (today.getHours() < 8) {
                today.setDate(today.getDate() - 1)
                var dispatcher_sutki = today.toISOString().substring(0, 10)
            } else {
                var dispatcher_sutki = today.toISOString().substring(0, 10)
            }
            if (type === 'tr') {
                $.ajax({
                    url: '/replace_record/' + id_row + '/' + dispatcher_sutki,
                    method: 'get',
                    success: function (res) {
                    },
                    async: false
                })
            } else {
                if (dispatcher_sutki !== $('#table_date_start').val()) {
                    var data = []
                    data['id_row'] = id_row
                    data['dispatcher_sutki'] = dispatcher_sutki
                    open_modal_confirm_ober("Перенести запись на текущие сутки?", data)    //открываем алерт
                }
            }
        }

        function confirm_request(data) {   //функция, которую вызываем при подтверждении
            $.ajax({
                url: '/replace_record/' + data['id_row'] + '/' + data['dispatcher_sutki'] + '_' + $('#table_date_start').val(),
                method: 'get',
                success: function (res) {
                },
                async: false
            })

        }


    </script>
    @include('include.font_size-change')
    <style>
        .iksweb tr:not(:first-child):nth-child(odd){
            background: #e9effc;
        }

        #shapka td{
            background-color: white;
            text-align: center;
        }

        td.changeble_td, th.changeble_td, td.oborudovanie, td.status, td.date {
            white-space: pre-wrap; /* css-3 */
            white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
            white-space: -pre-wrap; /* Opera 4-6 */
            white-space: -o-pre-wrap; /* Opera 7 */
            word-wrap: break-word;
        / word-break: break-all;
        }

        img:hover {
            transform: scale(1.3);
        }

        span {
            outline: none !important;
        }

        table.iksweb {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            height: auto;
            table-layout: fixed;
        }

        table.iksweb td, table.iksweb th {
            border: 1px solid #595959;
        }

        table.iksweb td, table.iksweb th {
            min-height: 20px;
            padding: 1px;
            /*width: 30px;*/
            /*height: 40px;*/
        }

        table.iksweb th {
            background: #347c99;
            color: #fff;
            font-weight: normal;
        }

        .content {
            overflow-x: hidden;
            width: 100%;
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


    </style>


@endsection
