@extends('layouts.app')
@section('title')
    Создание нового сообщения
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
    <style>
        .content {
            width: 98%;
        }
    </style>

    <div style="width: 100%; display: inline-flex; justify-content: space-between">
        <h3>Создание нового сообщения</h3>
        <button id="add_record" onclick="save_new_record()" class="button button1"
                style="float: left; margin-top: 1%">Сохранить
        </button>
    </div>
    <table class="itemInfoTable" style="width: 100%; float:left; display: table">
        <tbody>
        <tr>
            <th style="text-align: left; width: 40%">Получатель</th>
            <td id="emails"><input type="email" style="width: 95%; padding: 5px" class="emails"
                                   placeholder="Введите email получателя">
            </td>
            <td style="text-align:center; width: 20%">
                <button class="button button1" onclick="add_new_mail()">

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         style="fill: rgba(24, 235, 24, 1);transform: ;msFilter:;">
                        <path
                            d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 8a3.91 3.91 0 0 0 4 4 3.91 3.91 0 0 0 4-4 3.91 3.91 0 0 0-4-4 3.91 3.91 0 0 0-4 4zm6 0a1.91 1.91 0 0 1-2 2 1.91 1.91 0 0 1-2-2 1.91 1.91 0 0 1 2-2 1.91 1.91 0 0 1 2 2zM4 18a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3v1h2v-1a5 5 0 0 0-5-5H7a5 5 0 0 0-5 5v1h2z"></path>
                    </svg>
            </td>

            </button>
        </tr>
        <tr>
            <th style="text-align: left; width: 40%">Тема</th>
            <td colspan="2"><input type="text" style="width: 95%; padding: 5px" id="theme"
                                   placeholder="Укажите тему сообщения"></td>
        </tr>
        <tr>
            <th style="text-align: left; width: 40%">Текст сообщения</th>
            <td colspan="2"><input type="textarea" class="input_date" style="width: 95%; padding: 5px" id="message"
                                   placeholder="Введите сообщение"></td>
        </tr>
        </tbody>
    </table>


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

    <script>
        function add_new_mail() {
            let email = document.createElement('input')
            email.type = 'email';
            email.style.width = '95%';
            email.style.padding = '5px';
            email.classList.add('emails');
            email.placeholder = 'Введите email получателя'
            document.getElementById('emails').append(email);
        }

        const EMAIL_REGEXP = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;

        function isEmailValid(value) {
            return EMAIL_REGEXP.test(value);
        }

        function save_new_record() {
            let i = 1;
            let data = {
                'recepient': [],
            };
            document.querySelectorAll('.emails').forEach((el) => {
                if (!isEmailValid(el.value)) {
                    open_modal_ober(`Введен некорректный email у ${i} получателя`)
                } else {
                    data.recepient.push(el.value);
                }
                i++
            })
            console.log(data.recepient.length);
            if (data.recepient.length != i - 1) {
                return false
            }
            if (document.getElementById('theme').value) {
                data.theme = document.getElementById('theme').value
            } else {
                open_modal_ober('Вы не указали тему сообщения!')
                return false
            }
            if (document.getElementById('message').value) {
                data.message = document.getElementById('message').value
            } else {
                open_modal_ober('Вы не указали текст сообщения!')
                return false
            }

            if (data.recepient && data.theme && data.message) {
                $.ajax({
                    url: '/save_mail',
                    method: 'POST',
                    data: data,
                    success: function (res) {
                        window.location.href = '/send_mail_view'
                    }

                })
            }
        }


    </script>

@endsection

