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
                 url: '/get_svodniy/'+document.getElementById('date').textContent,
                 method: 'GET',
                 success: function (res) {
                     var body = document.getElementById('time_id')
                     body.innerText = ''
                     for (var i=0; i<24; i++) {
                         var hour = Number(res[i]['hours'])
                         if (hour < 10){
                             hour = '0'+hour
                         }
                         var tr=document.createElement('tr')
                         tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${hour+':00'}</p></td>`
                         if (res[i]['in_gas'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Math.round(Number(res[i]['in_gas'])).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['in_gas']}</p></td>`
                         }
                         if (res[i]['out_gas'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['out_gas']).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['out_gas']}</p></td>`
                         }
                         if (res[i]['skv_job'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_job']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_job']}</p></td>`
                         }
                         if (res[i]['skv_res'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_res']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_res']}</p></td>`
                         }
                         if (res[i]['skv_rem'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['skv_rem']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['skv_rem']}</p></td>`
                         }
                         if (res[i]['t_in'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['t_in']).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['t_in']}</p></td>`
                         }
                         if (res[i]['t_out'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['t_out']).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['t_out']}</p></td>`
                         }
                         if (res[i]['p_in'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['p_in']).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['p_in']}</p></td>`
                         }
                         if (res[i]['p_out'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['p_out']).toFixed(3)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['p_out']}</p></td>`
                         }

                         if (res[i]['gpa_job'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_job']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_job']}</p></td>`
                         }
                         if (res[i]['gpa_res'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_res']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_res']}</p></td>`
                         }
                         if (res[i]['gpa_rem'] != '...'){
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res[i]['gpa_rem']).toFixed(0)}</p></td>`
                         }else {
                             tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res[i]['gpa_rem']}</p></td>`
                         }

                         body.appendChild(tr);
                     }

                 },
                 async:true
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



