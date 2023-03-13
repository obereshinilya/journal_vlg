@extends('layouts.app')
@section('title')
    Исходящие сообщения
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

    <div id="content-header"
         style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center"><h3
            style="width: 30%; display: inline-block">Исходящие
            сообщения</h3>
        <button class="button button1" onclick="window.location.href='/new_mail'">Новое сообщение</button>
    </div>
    <style>
        .content {
            width: 98%;
        }
    </style>

    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table">
            <colgroup>
                <col style="width: 10%"/>
                <col style="width: 15%"/>
                <col style="width: 15%"/>
                <col style="width: 15%"/>
                <col style="width: 45%"/>
            </colgroup>
            <thead style="width: 100%">
            <tr style="width: 100%">
                <th style="text-align: center; width: 10%">Дата</th>
                <th style="text-align: center; width: 15%;">Отправитель</th>
                <th style="text-align: center; width: 15%">Получатель</th>
                <th style="text-align: center; width: 15%">Тема</th>
                <th style="text-align: center; ">Текст сообщения</th>
            </tr>
            </thead>
            <tbody id="tbody" style="width: 100%">


            </tbody>
        </table>
        {{--        <a class="paginate_button first disabled" aria-controls="itemInfoTable" style="float: right" data-dt-idx="0" tabindex="-1" id="to_print">Печать</a>--}}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', get_table_data)

        function get_table_data() {
            let table_body = document.getElementById('tbody')
            table_body.innerHTML = '';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let font_size = localStorage.getItem('font') * 14;
            $.ajax({
                    url: '/get_mail_info',
                    type: 'GET',
                    success: (res) => {
                        console.log(res)
                        var num = 1
                        var keys = Object.keys(res)
                        // console.log(keys)
                        for (var key of keys) {
                            var i = 1
                            for (var row of res[key]) {
                                var tr = document.createElement('tr')
                                let date = new Date(row['timestamp']);
                                let dd = date.getDate();
                                if (dd < 10) dd = '0' + dd;
                                let mm = date.getMonth() + 1;
                                if (mm < 10) mm = '0' + mm;
                                let yyyy = date.getFullYear();
                                let hh = date.getHours();
                                if (hh < 10) hh = '0' + hh;
                                let min = date.getMinutes();
                                if (min < 10) min = '0' + min;
                                tr.innerHTML += `<td style="text-align: center"><p style="margin: 0; display: inline; font-size:${font_size}px  ">${dd}.${mm}.${yyyy} ${hh}:${min}</p></td>`

                                {{--            console.log(res)--}}
                                    tr.innerHTML += `<td style="text-align: center"><p style="margin: 0; display: inline;font-size:${font_size}px ">${row['sender']}</p></td>`
                                tr.innerHTML += `<td style="text-align: center"><p style="margin: 0; display: inline;font-size:${font_size}px ">${row['recepient']}</p></td>`
                                if (i == 1) {
                                    tr.innerHTML += `<td rowspan="${res[key].length}" style="text-align: center"><p style="margin: 0; display: inline;font-size:${font_size}px ">${key}</p></td>`
                                }
                                tr.innerHTML += `<td style="text-align: center"><p style="margin: 0; display: inline;font-size:${font_size}px ">${row['message']}</p></td>`


                                table_body.appendChild(tr)
                                num += 1
                                i++
                            }
                        }
                    }
                    ,
                    error: function (error) {
                        var table_body = document.getElementById('body_table')
                        table_body.innerText = ''
                    }
                    ,
                }
            )
        }
    </script>

    @include('include.font_size-change')

@endsection
