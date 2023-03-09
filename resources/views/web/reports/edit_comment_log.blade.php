@extends('layouts.app')
@section('title')
    Редактирование записи в журнале замечаний
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
        <button id="add_record" onclick="update_record()" class="button button1"
                style="float: left; margin-top: 1%">Сохранить
        </button>
    </div>
    <table class="itemInfoTable" style="width: 100%; float:left">
        <tbody>
        <tr>
            <th style="text-align: left; width: 40%">Текст замечания</th>
            <td><input type="text" style="width: 95%; padding: 5px" id="remark" placeholder="Введите текст замечания"
                       value="{{$data['Comment']['remark']}}">
            </td>
        </tr>
        <tr>
            <th style="text-align: left; width: 40%">Комментарий</th>
            <td><input type="text" style="width: 95%; padding: 5px" id="comment" placeholder="Введите комментарий"
                       value="{{$data['Comment']['comment']}}"></td>
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
        <tbody id="event_body">
        <?php $i = 1?>
        @foreach($data['events'] as $event)
            <tr data-id="{{$event->id}}">
                <td style="text-align: center">
                    {{$i}}
                </td>
                <td style="background: white; text-align: center" contenteditable="true"
                    class="event">{{$event->event}}</td>
                <td style="background: white; text-align: center">
                    <select class="person" style="text-align:center">
                        <option>{{$event->person}}</option>
                        @foreach(\App\Models\UserAuth::distinct('username')->select('username')->get() as $user)
                            @if($event->person!=$user->username)
                                <option>{{$user->username}}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td style="background: white; text-align: center"><label class="switch_new">
                        <input class="completion_mark"
                               type="checkbox" {{$event->completion_mark? 'checked':''}}>
                    </label></td>
                <td style="background: white; text-align: center" contenteditable="true"
                    class="comment_event">{{$event->comment_event}}</td>
                <td style="text-align: center"><img onclick="remove_event(this)" style="width: 15px; margin-left: 15%"
                                                    src="{{asset('assets/images/icons/ober_trash.png')}}"></td>
            </tr>
            <?php $i++?>
        @endforeach
        </tbody>
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

            tr.innerHTML += `<td style="text-align: center"><img onclick="this.parentNode.parentNode.remove()" style="width: 15px; margin-left: 15%" src="{{asset('assets/images/icons/ober_trash.png')}}"></td>`
            body.appendChild(tr);
        }

        function update_record() {
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
                url: '/update_comment_log/' + {{$data['Comment']->id}},
                method: 'POST',
                data: data,
                success: function (res) {
                    window.location.href = '/open_comment_log'
                }

            })
        }

        function remove_event(el) {
            let id = el.parentNode.parentNode.dataset['id'];
            if (id) {
                $.ajax({
                    url: '/delete_event_comment_logs/' + id,
                    method: 'GET',
                    success: function (res) {
                    }
                })
            }
            el.parentNode.parentNode.remove();
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
