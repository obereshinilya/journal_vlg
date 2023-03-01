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
<p id="type" style="display: none">{{$type}}</p>
<p id="yams_yub" style="display: none">{{$yams_yub}}</p>

    <div style="display: inline-flex; width: 100%;">
            <h3 style="width: 100%; text-align: center">Отчет по ТЭР Уренгойского ГПУ за {{$date}}</h3>
    </div>
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
    <div id="content-header"></div>


    <div id="redirect">

        <div id="tableDiv" style="width: auto; text-align: center">
            <table id="statickItemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table; overflow-y: auto">

                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <col style="width: 11%;">
                <thead>
                <tr>
                    <th style="text-align: center" id="th_text_time">Время</th>
                    <th style="text-align: center">Метанол запас (на начало)</th>
                    <th style="text-align: center">Метанол расход</th>
                    <th style="text-align: center">Метанол приход</th>
                    <th style="text-align: center">Метанол запас (на конец)</th>
                    <th style="text-align: center">ТЭГ запас (на начало)</th>
                    <th style="text-align: center">ТЭГ расход</th>
                    <th style="text-align: center">ТЭГ приход</th>
                    <th style="text-align: center">ТЭГ запас (на конец)</th>
                </tr>
                </thead>
                <tbody id="table_body">
                <tr>
                    <td colspan="9" ><span>Данных нет</span></td>
                </tr>
                </tbody>
            </table>

        </div>
    <div style="margin-top: 5%">
        <span style="text-decoration:underline; float: right; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
    </div>


    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>

        $(document).ready(function () {
            get_table_data(document.getElementById('type').textContent, document.getElementById('date').textContent,  document.getElementById('yams_yub').textContent )
        })

        function get_table_data(type, date, yams_yub) {
            $.ajax({
                url: '/get_ter/'+date+'/'+type+'/'+yams_yub,
                method: 'GET',
                success: function (res) {
                    if (type==='day')
                        document.getElementById('th_text_time').textContent = 'Время'
                    if (type==='month')
                        document.getElementById('th_text_time').textContent = 'День'
                    if (type==='year')
                        document.getElementById('th_text_time').textContent = 'Месяц'
                    var table_body = document.getElementById('table_body')
                    table_body.innerText = ''
                    if (res.length){
                        var i = 0;
                        var j = 0;
                        var begin_year_metanol = 0;
                        var last_year_metanol = 0;
                        var begin_year_teg = 0;
                        var last_year_teg = 0;
                        var buff_metanol_rash = 0;
                        var buff_teg_rash = 0;
                        var buff_metanol_prih = 0;
                        var buff_teg_prih = 0;
                        for (var row of res){
                            var tr = document.createElement('tr')
                            try {
                                tr.innerHTML += `<td><span style="text-align: center">${row['timestamp'].split('.')[0]}</span></td>`
                            }catch (e){
                                tr.innerHTML += `<td><span style="text-align: center">${row['timestamp']}</span></td>`
                            }
                            if (Number (row['metanol_rashod']) || Number (row['metanol_prihod'])|| Number (row['metanol_zapas'])){
                                tr.innerHTML += `<td><span style="text-align: center">${Number(Number (row['metanol_zapas']) + Number (row['metanol_rashod']) - Number (row['metanol_prihod'])).toFixed(3)}</span></td>`
                                if (i===0){
                                    begin_year_metanol = Number(Number (row['metanol_zapas']) + Number (row['metanol_rashod']) - Number (row['metanol_prihod'])).toFixed(3)
                                    i++
                                }
                            }else{
                                tr.innerHTML += `<td><span style="text-align: center"></span></td>`
                            }
                            if (row['metanol_rashod']){
                                tr.innerHTML += `<td><span>${Number(row['metanol_rashod']).toFixed(3)}</span></td>`
                                buff_metanol_rash +=row['metanol_rashod']
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['metanol_prihod']){
                                buff_metanol_prih+=row['metanol_prihod']
                                tr.innerHTML += `<td><span>${Number(row['metanol_prihod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['metanol_zapas']){
                                last_year_metanol = row['metanol_zapas']
                                tr.innerHTML += `<td><span>${Number(row['metanol_zapas']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (Number (row['teg_rashod']) || Number (row['teg_prihod']) || Number (row['teg_zapas'])){
                                tr.innerHTML += `<td><span style="text-align: center">${Number(Number (row['teg_zapas']) + Number (row['teg_rashod']) - Number (row['teg_prihod'])).toFixed(3)}</span></td>`
                                if (j===0){
                                    begin_year_teg = Number(Number (row['teg_zapas']) + Number (row['teg_rashod']) - Number (row['teg_prihod'])).toFixed(3)
                                    j++
                                }
                            }else {
                                tr.innerHTML += `<td><span style="text-align: center"></span></td>`

                            }
                            if (row['teg_rashod']){
                                buff_teg_rash+=row['teg_rashod']
                                tr.innerHTML += `<td><span>${Number(row['teg_rashod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['teg_prihod']){
                                buff_teg_prih+=row['teg_prihod']
                                tr.innerHTML += `<td><span>${Number(row['teg_prihod']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            if (row['teg_zapas']){
                                last_year_teg = Number(row['teg_zapas']).toFixed(3)
                                tr.innerHTML += `<td><span>${Number(row['teg_zapas']).toFixed(3)}</span></td>`
                            } else {
                                tr.innerHTML += `<td><span></span></td>`
                            }
                            table_body.appendChild(tr);
                        }
                        if (type === 'year' || type === 'month'){
                            var tr = document.createElement('tr')
                            tr.innerHTML += `<td><span style="text-align: center"><b>Итого за период:</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(begin_year_metanol).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_metanol_rash).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_metanol_prih).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(last_year_metanol).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(begin_year_teg).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_teg_rash).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(buff_teg_prih).toFixed(3)}</b></span></td>`
                            tr.innerHTML += `<td><span style="text-align: center"><b>${Number(last_year_teg).toFixed(3)}</b></span></td>`
                            table_body.appendChild(tr);
                        }
                    } else {
                        var tr = document.createElement('tr')
                        tr.innerHTML += `<td colspan="9" style="text-align: center"><span  style="text-align: left">Данных нет</span></td>`
                        table_body.appendChild(tr);
                    }
                },
                async:false
            })
        }

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_ter/"+document.getElementById('yams_yub').textContent
        }






    </script>
    <style>
        td{
            text-align: center;
        }
        .itemInfoTable span{
            text-align: center;
        }
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


