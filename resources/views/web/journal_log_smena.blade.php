@extends('layouts.app')
@section('title')
    Журнал принятия смены
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

    <div id="content-header" style="margin-top: 10px"><h3 style="width: 30%; display: inline-block">Журнал принятия смены</h3>
    </div>
    <style>
        .content {
            width: 98%;
        }
    </style>

    <div id="tableDiv">
        <table id="itemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed">
            <colgroup>
                <col style="width: 25%"/>
                <col style="width: 25%"/>
                <col style="width: 25%"/>
                <col style="width: 25%"/>
            </colgroup>
            <thead style="width: 100%">
            <tr>
                <th style="text-align: center">Пользователь</th>
                <th style="text-align: center">Объект</th>
                <th style="text-align: center">Время принятия</th>
                <th style="text-align: center">Время сдачи</th>
            </tr>
            </thead>
            <tbody style="width: 100%">

            </tbody>
        </table>
    </div>

    <script>

        $(document).ready(function () {

            getTableData();

        });

        function getTableData() {
            document.body.className = '';
            $.ajax({
                url: '/get_journal_log_smena',
                data: 1,
                type: 'GET',
                success: (res) => {
                    if ($.fn.dataTable.isDataTable('#itemInfoTable')) {
                        $('#itemInfoTable').DataTable().destroy();
                    }
                    var table_body = document.getElementById('itemInfoTable').getElementsByTagName('tbody')[0]
                    table_body.innerText = ''
                    for (var i = 0; i < res.length; i++) {
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['name_user']}</span></td>`
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['full_name']}</span></td>`
                        tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['start_smena']}</span></td>`
                        if (res[i]['stop_smena']){
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center">${res[i]['stop_smena']}</span></td>`
                        }else {
                            tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px"><span data-type="text" style="text-align: center"></span></td>`
                        }
                        table_body.appendChild(tr);
                    }
                    $('#itemInfoTable').DataTable({
                        "pagingType": "full_numbers",
                        destroy: true,
                        order: [[1, 'desc']],

                    });

                    window.setTimeout(function () {
                        document.body.classList.add('loaded');
                        document.body.classList.remove('loaded_hiding');
                    }, 500);
                }
            })
        }

    </script>
    @include('include.font_size-change')

@endsection


