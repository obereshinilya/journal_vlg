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

    <div style="width: 100%; text-align: center">
            <h3 >Часовая сводка за {{$date}}</h3>

    </div>
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>


    <div id="redirect">

        <div id="tableDiv" style="width: 100%">
            <table id="statickItemInfoTable" class="itemInfoTable" style=" display: table; table-layout: fixed; width: 100%">
                <thead style="width: 100%">
                <tr>
                    <th rowspan="2" style="text-align: center; position: sticky; top: 0">Час</th>
                    <th colspan="2" style=" text-align: center; position: sticky; top: 0">Волгоградское ПХГ</th>
                    <th colspan="3" style=" text-align: center; position: sticky; top: 0">Скважины</th>
                    <th colspan="7" style=" text-align: center; position: sticky; top: 0">КЦ-1</th>
                </tr>
                <tr>
                    <th style="text-align: center; position: sticky; top: 25px">Закачка (тыс.м<sup>3</sup>)</th>
                    <th style="text-align: center; position: sticky; top: 25px">Отбор (тыс.м<sup>3</sup>)</th>
                    <th style="text-align: center; position: sticky; top: 25px">в работе</th>
                    <th style="text-align: center; position: sticky; top: 25px">в резерве</th>
                    <th style="text-align: center; position: sticky; top: 25px">в ремонте</th>
                    <th style="text-align: center; position: sticky; top: 25px">Т вх</th>
                    <th style="text-align: center; position: sticky; top: 25px">Т вых</th>
                    <th style="text-align: center; position: sticky; top: 25px">Р вх</th>
                    <th style="text-align: center; position: sticky; top: 25px">Р вых</th>
                    <th style="text-align: center; position: sticky; top: 25px">ГПА в раб</th>
                    <th style="text-align: center; position: sticky; top: 25px">ГПА в рез</th>
                    <th style="text-align: center; position: sticky; top: 25px">ГПА в рем</th>
                </tr>
                </thead>
                <tbody id="time_id" style="width: 100%">

                </tbody>
            </table>

        </div>
    <div style="margin-top: 50px">
        <span style="text-decoration:underline; margin-right: 50px; float: right; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
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
                 url: '/get_svodniy/' + document.getElementById('date').textContent,
                 method: 'GET',
                 success: function (res) {
                     var body = document.getElementById('time_id')
                     body.innerText = ''
                     var start_hour = 10
                     var param_array = ['in_gas', 'out_gas', 'skv_job', 'skv_res', 'skv_rem', 't_in', 't_out', 'p_in', 'p_out', 'gpa_job', 'gpa_res', 'gpa_rem']
                     for (var i = 0; i < 24; i++) {
                         if (start_hour == 24)
                             start_hour = 0
                         var hour = start_hour
                         if (hour < 10)
                             hour = '0' + hour
                         var tr = document.createElement('tr')
                         tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${hour + ':00'}</p></td>`
                         for (var param_name of param_array){
                             console.log(res[i][param_name]['xml_create'])
                             if (res[i][param_name]['xml_create']){
                                 tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p style="background-color: #1ab585">${res[i][param_name]['val']}</p></td>`
                             }else {
                                 tr.innerHTML += `<td style="font-size:${14 * localStorage.getItem('font')}px ;text-align: center; padding: 0px; min-width: 20px"><p>${res[i][param_name]['val']}</p></td>`
                             }
                         }
                         start_hour++
                         body.appendChild(tr);
                     }

                 },
                 async: true
             })
        }

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_svodniy"
        }

    </script>



