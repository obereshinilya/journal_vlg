<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 14px}

    .itemInfoTable th, .itemInfoTable td{
        border: 1px solid black;
        margin: 0;
        padding: 0;
        border-spacing: 0px;
        border-collapse: collapse;
    }
    table{
        font-size: 12px;
        -webkit-print-color-adjust: exact; /* благодаря этому заработал цвет*/
        border-spacing: 0px;
        border-collapse: collapse;
        /* благодаря этому строки переносятся*/
        /*white-space: pre-wrap; !* css-3 *!*/
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /
    word-break: break-all;
    }
    table th{
        background-color: darkgrey;
    }
</style>

<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
@stack('scripts')
@stack('styles')
<script src="{{ asset('js/app.js') }}"></script>

<p id="date" style="display: none">{{$date}}</p>

    <div style="display: inline-flex; width: 100%">
            <h3 style="width: 100%; text-align: center">Данные от смежных систем за {{$date}} </h3>

    </div>
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>


    <div id="redirect">

        <div id="tableDiv" style="width: 100%">
            <table id="statickItemInfoTable" class="itemInfoTable" style="display: table; table-layout: fixed; width: 60%; margin-left: 20%">
                <colgroup>
                    <col style="width: 70%">
                    <col style="width: 30%">
                </colgroup>
                <thead>
                <tr>
                    <th style="text-align: center">Наименование параметра</th>
                    <th style="text-align: center">Значение</th>
                </tr>
                </thead>
                <tbody id="time_id">

                </tbody>
            </table>

        </div>
    <div style="margin-top: 25%">
        <span style="text-decoration:underline; margin-left: 45%; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
    </div>


    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>

        $(document).ready(function () {
            get_table_data();
        })

        function get_table_data() {

            $.ajax({
                url: '/get_balans/'+document.getElementById('date').textContent,
                method: 'GET',
                success: function (res) {
                    var body = document.getElementById('time_id')
                    body.innerText = ''

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td><p>Потери</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['poteri']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p>Расход валовый</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['rash_val']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p>Расход газа (объем)</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['rash_gaz']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p>Расход на собственные нужды</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['rash_sobstv']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p>Расход сторонние организации</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['stor']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p>Расход товарный</p></td>`
                    tr.innerHTML+=`<td style="text-align: center"> <p>${Number(res['rash_tov']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p><b>Итого</b></p></td>`
                    tr.innerHTML+=`<td style="text-align: center"><p>${Number(res['sum']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td colspan="2" style="text-align: center; padding: 0px; min-width: 20px"><p>Расход ТЭР</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход метанола</p></td>`
                    tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_metanol']).toFixed(3)}</p></td>`
                    body.appendChild(tr);

                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td style="text-align: left; padding: 0px; min-width: 20px"><p>Расход ТЭГ</p></td>`
                    tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['rash_teg']).toFixed(3)}</p></td>`
                    body.appendChild(tr);
                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td ><p><b>Итого ТЭР</b></p></td>`
                    tr.innerHTML+=`<td style="text-align: center"><p>${Number(res['sum_ter']).toFixed(3)}</p></td>`
                    body.appendChild(tr);
                },
                async:true
            })
        }

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_balans"
        }






    </script>
    <style>
        .create_td{
            background-color: white;
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

        input{

        }
    </style>


