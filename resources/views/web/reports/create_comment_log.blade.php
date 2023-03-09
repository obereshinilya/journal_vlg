@extends('layouts.app')
@section('title')
    Создание записи в журнале замечаний
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
        <h3>Создание записи в журнале замечаний</h3>
        <button id="add_record" onclick="save_new_record()" class="button button1"
                style="float: left; margin-top: 1%">Сохранить
        </button>
    </div>
    <table class="itemInfoTable" style="width: 100%; float:left">
        <tbody>
        <tr>
            <th style="text-align: left; width: 40%">Текст замечания</th>
            <td><input type="text" style="width: 95%; padding: 5px" id="remark" placeholder="Введите текст замечания">
            </td>
        </tr>
        <tr>
            <th style="text-align: left; width: 40%">Комментарий</th>
            <td><input type="text" style="width: 95%; padding: 5px" id="comment" placeholder="Введите комментарий"></td>
        </tr>
        </tbody>
    </table>
    <div style="width: 100%; display: inline-flex; justify-content: space-between">
        <h3>Мероприятия</h3>
        <button id="add_event" onclick="add_event();" class="button button1"
                style="float: left; margin-top: 1%">Добавить мероприятие
        </button>
    </div>


    <table class="itemInfoTable" style="width: 100%; float:left">
        {{--        <colgroup>--}}
        {{--            <col style="width: 3%">--}}
        {{--            <col style="width: 21%">--}}
        {{--            <col style="width: 19%">--}}
        {{--            <col style="width: 19%">--}}
        {{--            <col style="width: 19%">--}}
        {{--            <col style="width: 19%">--}}

        <thead>
        <tr>
            <th style="width: 3%">№</th>
            <th style="width: 24%">Мероприятие</th>
            <th style="width: 22%">Ответственный</th>
            <th style="width: 22%">Отметка об устранении</th>
            <th style="width: 22%">Комментарий</th>
            <th></th>
        </tr>

        </thead>
        <tbody id="event_body"></tbody>
    </table>



    <script>
        function add_event() {
            var body = document.getElementById('event_body')
            let i = body.getElementsByTagName('tr').length + 1
            var tr = document.createElement('tr')
            tr.innerHTML += `<td style="text-align: center" class="id">${i}</td>`
            tr.innerHTML += `<td style="background: white; text-align: center" contenteditable="true" class="event"></td>`
            tr.innerHTML += `<td style="background: white; text-align: center"><select class="person" style="text-align:center">
@foreach(\App\Models\UserAuth::distinct('username')->select('username')->get() as $user)
            <option style:"text-align:center">{{$user->username}}</option>
            @endforeach
            </select></td>`
            tr.innerHTML += `<td style="background: white; text-align: center"> <label class="switch_new">
                                                <input class="completion_mark"
                                                       type="checkbox">

                                            </label></td>`
            tr.innerHTML += `<td style="background: white; text-align: center" contenteditable="true" class="comment_event"></td>`

            tr.innerHTML += `<td style="text-align: center"><img onclick="this.parentNode.parentNode.remove()" style="width: 15px; margin-left: 15%" src="assets/images/icons/ober_trash.png"></td>`
            body.appendChild(tr);
        }

        function save_new_record() {
            let data = {
                'remark': '',
                'comment': '',
                'event': [],
                'comment_event': [],
                'person': [],
                'completion_mark': []
            };
            data['remark'] = document.getElementById('remark').value;
            data['comment'] = document.getElementById('comment').value;
            params = ['event', 'comment_event']
            params.forEach((el) => {
                document.querySelectorAll('.' + el).forEach((els) => {
                    data[el].push(els.textContent)
                });
            })
            document.querySelectorAll('.person').forEach((el) => {
                data['person'].push(el.value)
            });
            document.querySelectorAll('.completion_mark').forEach((el) => {
                if (el.checked) {
                    data['completion_mark'].push(1);
                } else {
                    data['completion_mark'].push(0);
                }
            })

            $.ajax({
                url: '/save_comment_log/',
                method: 'POST',
                data: data,
                success: function (res) {
                    window.location.href = '/open_comment_log'
                }

            })
        }
    </script>
    <style>
        .itemInfoTable thead th {
            text-align: center;
        }

        .itemInfoTable tbody th {
            font-weight: bold;
            text-align: left;
            border: none;
            padding: 10px 15px;
            background: #d8d8d8;
            font-size: 14px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            width: 100%;
            position: sticky;
            top: 0;
        }

        .completion_mark {
            position: relative;
            width: 30px;
            height: 15px;
            -webkit-appearance: none;
            background: #c6c6c6;
            outline: none;
            border-radius: 15px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, .2);
            transition: .5s
        }

        .completion_mark:checked {
            background: #4bd562;
        }

        .completion_mark::before {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            border-radius: 20px;
            top: 0;
            left: 0;
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
        }

        .completion_mark:checked[type="checkbox"]::before {
            left: 15px
        }

        img:hover {
            transform: scale(1.3);
        }
    </style>

@endsection
