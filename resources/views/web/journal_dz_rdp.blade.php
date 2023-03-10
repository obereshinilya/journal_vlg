@extends('layouts.app')
@section('title')
    Журнал диспетчерских заданий
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

    <div id="content-header"><h3>Журнал диспетчерских заданий</h3></div>


    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="display: table; table-layout: fixed">
            <colgroup>
                <col style="width: 15%"/>
                <col style="width: 25%"/>
                <col style="width: 20%"/>
                <col style="width: 20%"/>
                <col style="width: 20%"/>
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center">Дата (местн)</th>
                <th style="text-align: center">Новое задание</th>
                <th style="text-align: center">Автор</th>
                <th style="text-align: center">Комментарий</th>
                <th style="text-align: center">Сведения</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        {{--        <a class="paginate_button first disabled" aria-controls="itemInfoTable" style="float: right" data-dt-idx="0" tabindex="-1" id="to_print">Печать</a>--}}
    </div>

    <script>

        $(document).ready(function () {

            getTableData();

        });

        function getTableData(type = null, data = null) {
            document.body.className = '';
            $.ajax({
                url: '/get_journal_dz',
                data: 1,
                type: 'GET',
                success: (res) => {
                    if ($.fn.dataTable.isDataTable('#itemInfoTable')) {
                        $('#itemInfoTable').DataTable().destroy();
                    }
                    var result = Object.keys(res);

                    var table_body = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    table_body.innerText = ''
                    for (var i = 0; i < res.length; i++) {
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['create'].split('.')[0]}</span></td>`
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['dz']}</span></td>`
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['autor']}</span></td>`
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center" contenteditable="true" onblur="save_comment(${res[i]['id']}, this.textContent)">${res[i]['comment']}</span></td>`
                        if (res[i]['check']) {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['info']}</span></td>`
                        } else {
                            tr.innerHTML += `<td style="text-align: center; font-size:${14 * localStorage.getItem('font')}px"></td>`
                        }
                        table_body.appendChild(tr);
                    }
                    $('#itemInfoTable').DataTable({
                        "pagingType": "full_numbers",
                        destroy: true,
                        order: [[0, 'desc']],

                    });

                    window.setTimeout(function () {
                        document.body.classList.add('loaded');
                        document.body.classList.remove('loaded_hiding');
                    }, 500);
                }
            })
        }

        function save_comment(id, text) {
            $.ajax({
                url: '/save_comment_dz/' + id + '/' + text,
                type: 'GET',
                success: (res) => {

                },
                async: true
            })
        }

        function confirm_dz(id) {
            $.ajax({
                url: '/confirm_dz/' + id,
                type: 'GET',
                success: (res) => {

                },
                async: true
            })
            getTableData();
        }

    </script>
    @include('include.font_size-change')

@endsection


