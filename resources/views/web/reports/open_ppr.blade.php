@extends('layouts.app')
@section('title')
    Отчет ППР
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
    <div style="width: 100%; display: inline-flex">
        <h3>График проведения ППР объектов ООО "Газпром ПХГ" за</h3>
        @include('include.choice_year_for_val')
        <button id="add_record" onclick="add_record(); this.style.display = 'none'" class="button button1"
                style="float: left; margin-top: 1%">Добавить ППР
        </button>
        <button id="print" class="button button1" style="float: left; margin-top: 1%">Печать</button>
    </div>

    <div id="content-header" style="display: inline-flex; width: 100%">
    </div>

    <div id="tableDiv" style="width: 100%; overflow-y: auto">
        <table id="statickItemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table">
            <colgroup>
                <col style="width: 3%">
                <col style="width: 13%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 10%">
                <col style="width: 21%">
                <col style="width: 5%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center" rowspan="2">П/п</th>
                <th style="text-align: center" rowspan="2">Объект ППР</th>
                <th style="text-align: center" colspan="2">Согласованные сроки</th>
                <th style="text-align: center" colspan="2">Фактические сроки</th>
                <th style="text-align: center" rowspan="2">Вид ремонта</th>
                <th style="text-align: center" rowspan="2">Примечание</th>
                <th style="text-align: center" rowspan="2"></th>
            </tr>
            <tr>
                <th style="text-align: center">Начало</th>
                <th style="text-align: center">Окончание</th>
                <th style="text-align: center">Начало</th>
                <th style="text-align: center">Окончание</th>
            </tr>
            </thead>
            <tbody id="table_body">
            <tr>
                <td colspan="9"><span>Данных нет</span></td>
            </tr>
            </tbody>
        </table>

    </div>



    <script>

        $(document).ready(function () {
            get_table($('#year').val())
            $('#year').change(function () {
                get_table($('#year').val())
            })
            $('#print').click(function () {
                window.location.href = '/print_ppr/' + $('#year').val()
            });
        })

        function get_table(year) {
            $.ajax({
                url: '/get_ppr/' + year,
                method: 'GET',
                success: function (res) {
                    var body = document.getElementById('table_body')
                    body.innerText = ''
                    var lenght = res.length
                    for (var row of res) {
                        var tr = document.createElement('tr')
                        tr.setAttribute('data-id', row['id'])
                        if (!row['type_job'])
                            row['type_job'] = '-'
                        if (!row['comment'])
                            row['comment'] = '-'
                        tr.innerHTML += `<td style="text-align: center; font-size:${14 * localStorage.getItem('font')}px " class="id">${lenght}</td>`
                        tr.innerHTML += `<td style="text-align: center; font-size:${14 * localStorage.getItem('font')}px" class="object">${row['object']}</td>`
                        tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input disabled class="plan_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date" value="${row['plan_begin'].split(' ')[0]}" /><br><input disabled class="plan_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time" value="${row['plan_begin'].split(' ')[1]}" /></td>`
                        tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input disabled class="plan_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date" value="${row['plan_end'].split(' ')[0]}" /><br><input disabled class="plan_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time" value="${row['plan_end'].split(' ')[1]}" /></td>`
                        if (row['fact_begin']) {
                            tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input disabled class="fact_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date" value="${row['fact_begin'].split(' ')[0]}" /><br><input disabled class="fact_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time" value="${row['fact_begin'].split(' ')[1]}" /></td>`
                        } else {
                            tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input onchange="change_fact_begin(this, 'date')" class="fact_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date" /><br><input onchange="change_fact_begin(this, 'time')" class="fact_begin" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time"/></td>`
                        }
                        if (row['fact_end']) {
                            tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input disabled class="fact_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date" value="${row['fact_end'].split(' ')[0]}" /><br><input disabled class="fact_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time" value="${row['fact_end'].split(' ')[1]}" /></td>`
                        } else {
                            tr.innerHTML += `<td style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px"><input disabled onchange="change_fact_end(this)" class="fact_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center" type="date"/><br><input disabled onchange="change_fact_end(this)" class="fact_end" style="border: none; width: 95%; font-size:${14 * localStorage.getItem('font')}px; text-align: center; margin-top: 3px" type="time" /></td>`
                        }
                        tr.innerHTML += `<td onblur="update_record(this.parentNode)" style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px" contenteditable="true" class="type_job">${row['type_job']}</td>`
                        tr.innerHTML += `<td onblur="update_record(this.parentNode)" style="background: white; text-align: center; font-size:${14 * localStorage.getItem('font')}px" contenteditable="true" class="comment">${row['comment']}</td>`
                        tr.innerHTML += `<td style="text-align: center"><img onclick="remove_record(this.parentNode.parentNode.getAttribute('data-id'))" style="width: 15px" src="assets/images/icons/ober_trash.png"></td>`
                        body.appendChild(tr);
                        lenght = lenght - 1
                    }
                },
                async: false
            })
        }

        function change_fact_begin(input, type) {
            if (type === 'date') {
                var fact_end = input.parentNode.parentNode.getElementsByClassName(input.classList[0].split('_')[0] + '_end')[0]
                fact_end.setAttribute('min', input.value)
                fact_end.removeAttribute('disabled')
                input.setAttribute('disabled', 'true')
            } else {
                var fact_end = input.parentNode.parentNode.getElementsByClassName(input.classList[0].split('_')[0] + '_end')[1]
                fact_end.setAttribute('min', input.value)
                fact_end.removeAttribute('disabled')
                input.setAttribute('disabled', 'true')
            }
        }

        function change_fact_end(input) {
            if (input.parentNode.parentNode.getElementsByClassName('fact_end')[0].value && input.parentNode.parentNode.getElementsByClassName('fact_end')[1].value) {
                update_record(input.parentNode.parentNode)
            }
        }

        function update_record(tr) {
            var arr = new Map()
            arr.set('id', tr.getAttribute('data-id'))
            arr.set('object', tr.getElementsByClassName('object')[0].textContent)
            arr.set('fact_begin', tr.getElementsByClassName('fact_begin')[0].value + ' ' + tr.getElementsByClassName('fact_begin')[1].value)
            arr.set('fact_end', tr.getElementsByClassName('fact_end')[0].value + ' ' + tr.getElementsByClassName('fact_end')[1].value)
            arr.set('type_job', tr.getElementsByClassName('type_job')[0].textContent)
            arr.set('comment', tr.getElementsByClassName('comment')[0].textContent)
            var data = Object.fromEntries(arr)
            if (data['fact_begin'] !== '') {
                if (data['fact_end'] === '') {
                    open_modal_ober('Не заполнена фактическая дата окончания!')
                } else if (Date.parse(data['fact_begin']) > Date.parse(data['fact_end'])) {
                    open_modal_ober('Неверно заполнены фактические сроки!')
                    get_table($('#year').val())

                } else {
                    $.ajax({
                        url: '/update_record_ppr/' + tr.getAttribute('data-id'),
                        method: 'POST',
                        data: data,
                        success: function (res) {
                            if (typeof res === "object") {
                                open_modal_ober('Указанный период пересекается с уже имеющимся!')
                                get_table($('#year').val())
                            }
                        },
                        async: false
                    })
                }
            } else {
                $.ajax({
                    url: '/update_record_ppr/' + tr.getAttribute('data-id'),
                    method: 'POST',
                    data: data,
                    success: function (res) {
                        if (typeof res === "object") {
                            open_modal_ober('Указанный период пересекается с уже имеющимся!')
                            get_table($('#year').val())
                        }
                    },
                    async: false
                })
            }
        }

        function remove_record(id) {
            $.ajax({
                url: '/delete_record_ppr/' + id,
                method: 'get',
                success: function () {
                    get_table($('#year').val())
                },
                async: false
            })
        }

        function add_record() {
            var body = document.getElementById('table_body')
            body.innerText = ''
            var tr = document.createElement('tr')
            tr.innerHTML += `<td style="text-align: center" class="id">-</td>`
            tr.innerHTML += `<td style="text-align: center; background-color: white">
                <select class="object" style="padding: 0px; vertical-align: middle; text-align: center">
                    <option value="Волгоградское ПХГ">Волгоградское ПХГ</option>
                    <option value="Юбилейное ГКМ">П.Уметское ПХГ</option>
                    <option value="Юбилейное ГКМ">Елшанское ПХГ</option>
                    <option value="Юбилейное ГКМ">Степновское ПХГ</option>
                    <option value="Юбилейное ГКМ">Похвостневское УПХГ</option>
                    <option value="Юбилейное ГКМ">Похвостневская пром.пл.</option>
                    <option value="Юбилейное ГКМ">Отрадневская пром.пл.</option>
                    <option value="Юбилейное ГКМ">Щелковское ПХГ</option>
                    <option value="Юбилейное ГКМ">Калужское ПХГ</option>
                    <option value="Юбилейное ГКМ">Касимовское УПХГ</option>
                    <option value="Юбилейное ГКМ">Невское ПХГ</option>
                    <option value="Юбилейное ГКМ">Гатчинское ПХГ</option>
                    <option value="Юбилейное ГКМ">Калининградское ПХГ</option>
                    <option value="Юбилейное ГКМ">с.Ставропольское ПХГ</option>
                    <option value="Юбилейное ГКМ">Краснодарское ПХГ</option>
                    <option value="Юбилейное ГКМ">Кущевское ПХГ</option>
                    <option value="Юбилейное ГКМ">Канчуринское ПХГ</option>
                    <option value="Юбилейное ГКМ">Пунгинское ПХГ</option>
                    <option value="Юбилейное ГКМ">Карашурское ПХГ</option>
                    <option value="Юбилейное ГКМ">Совхозное ПХГ</option>
                </select>
            </td>`
            tr.innerHTML += `<td style="background: white; text-align: center"><input onchange="change_fact_begin(this, 'date')" class="plan_begin" style="border: none; width: 95%; text-align: center" type="date" /><br><input onchange="change_fact_begin(this, 'time')" class="plan_begin" style="border: none; width: 95%; text-align: center; margin-top: 3px" type="time"/></td>`
            tr.innerHTML += `<td style="background: white; text-align: center"><input onchange="change_fact_begin(this, 'date')" class="plan_end" style="border: none; width: 95%; text-align: center" type="date" /><br><input onchange="change_fact_begin(this, 'time')" class="plan_end" style="border: none; width: 95%; text-align: center; margin-top: 3px" type="time"/></td>`
            tr.innerHTML += `<td style="text-align: center"></td>`
            tr.innerHTML += `<td style="text-align: center"></td>`
            tr.innerHTML += `<td style="background: white; text-align: center" contenteditable="true" class="type_job"></td>`
            tr.innerHTML += `<td style="background: white; text-align: center" contenteditable="true" class="comment"></td>`
            tr.innerHTML += `<td style="text-align: center"><img onclick="save_new_record(this.parentNode.parentNode)" style="width: 15px" src="assets/images/icons/ober_send.png"><img onclick="get_table($('#year').val()); document.getElementById('add_record').style.display = ''" style="width: 15px; margin-left: 15%" src="assets/images/icons/ober_trash.png"></td>`
            body.appendChild(tr);
        }

        function save_new_record(tr) {
            var arr = new Map()
            var select = tr.getElementsByTagName('select')[0]
            arr.set('object', select.options[select.selectedIndex].textContent)
            arr.set('plan_begin', tr.getElementsByClassName('plan_begin')[0].value + ' ' + tr.getElementsByClassName('plan_begin')[1].value)
            arr.set('plan_end', tr.getElementsByClassName('plan_end')[0].value + ' ' + tr.getElementsByClassName('plan_end')[1].value)
            arr.set('type_job', tr.getElementsByClassName('type_job')[0].textContent)
            arr.set('comment', tr.getElementsByClassName('comment')[0].textContent)
            var data = Object.fromEntries(arr)
            if (tr.getElementsByClassName('plan_begin')[0].value !== '' && tr.getElementsByClassName('plan_begin')[1].value !== '' && tr.getElementsByClassName('plan_end')[0].value !== '' && tr.getElementsByClassName('plan_end')[1].value !== '') {
                if (Date.parse(data['plan_begin']) > Date.parse(data['plan_end'])) {
                    open_modal_ober('Неверно заполнены плановые сроки!')
                    document.getElementById('add_record').style.display = ''

                    get_table($('#year').val())
                } else {
                    $.ajax({
                        url: '/create_record_ppr',
                        method: 'POST',
                        data: data,
                        success: function (res) {
                            if (typeof res === "object") {
                                open_modal_ober('Указанный период пересекается с уже имеющимся!')
                            }
                            get_table($('#year').val())
                            document.getElementById('add_record').style.display = ''
                        },
                        async: false
                    })
                }
            } else {
                open_modal_ober('Не все поля заполнены!')
            }
        }

    </script>
    @include('include.font_size-change')
    <style>
        .itemInfoTable span {
            text-align: center;
        }
        tr:hover{
            font-weight: bold;
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
