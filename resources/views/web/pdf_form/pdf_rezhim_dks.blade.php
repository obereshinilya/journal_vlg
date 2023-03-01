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

<div id="redirect">
    <p id="dks_number" style="display: none">{{$dks}}</p>
    <p id="date" style="display: none">{{$date}}</p>
    <h3 style="text-align: center">Режимы работы турбоагрегатов на ДКС ГКП-{{$gkp}} за {{$date}}</h3>
    <div>
        <table id="statickItemInfoTable" class="itemInfoTable" style="">
            <thead>
            <tr>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>№ ГПА</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Режим<br> работы</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Мокв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Мокв <br>общ</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Об ТВД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Прив <br>об ТВД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Об ТНД</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвх</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвых</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твозд<br> ВхДв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рвозд<br> ВхДв</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твх</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твых</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Qтг</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Ст<br> Сж</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Qцбн</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Таво</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Твозд</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Q</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Ркол</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Тподш<br> ЦБН</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Т <br>г/г</h4></th>
                <th style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Запас</h4></th>
                <th class="buff" style="width: 2%; text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><h4>Рбуф</h4></th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th colspan="24" style="text-align: left">16:00</th>
            </tr>
            </thead>
            <tbody id="data16">

            </tbody>
            <thead>
            <th colspan="24" style="text-align: left">20:00</th>
            </thead>
            <tbody id="data20">

            </tbody>
            <thead>
            <th colspan="24" style="text-align: left">00:00</th>
            </thead>
            <tbody id="data00">

            </tbody>
            <thead>
            <th colspan="24" style="text-align: left">04:00</th>
            </thead>
            <tbody id="data04">

            </tbody>
            <thead>
            <th colspan="24" style="text-align: left">08:00</th>
            </thead>
            <tbody id="data08">

            </tbody>
            <thead>
            <th colspan="24" style="text-align: left">12:00</th>
            </thead>
            <tbody id="data12">

            </tbody>

        </table>
    </div>
    <div style="margin-top: 5%">
        <span style="text-decoration:underline; float: right; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>

</div>


<script>
    $(document).ready(function () {
        get_table_data();

    })


    function get_table_data() {

        $.ajax({
            url: '/get_gpa_rezhim_report_data/'+document.getElementById('date').textContent+'/'+document.getElementById('dks_number').textContent,
            method: 'GET',
            success: function (res) {
                var time_id = ''
                for (var j = 1; j<7; j++){
                    if (j === 1){
                        time_id = 'data16'
                    } else if (j === 2){
                        time_id = 'data20'
                    }else if (j === 3){
                        time_id = 'data00'
                    }else if (j === 4){
                        time_id = 'data04'
                    }else if (j === 5){
                        time_id = 'data08'
                    }else if (j === 6){
                        time_id = 'data12'
                    }

                    var body = document.getElementById(time_id)
                    body.innerText = ''
                    if (res[time_id].length){
                        for (var i of res[time_id]) {
                            var tr=document.createElement('tr')
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['number_gpa']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['rezhim']}<br>${i['time_rezhim']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['mokveld_status']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['mokveld_zadanie']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['tvd']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['priv_tvd']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['tnd']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pin']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pout']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tvdv']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pvdv']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tin']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tout']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Qtg']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['St_sj']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Qcbn']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tavo']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tvozd']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['q']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pkol']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tpodsh']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Tgg']}</p></td>`
                            tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Zapas']}</p></td>`
                            tr.innerHTML+=`<td class="buff" style="text-align: center; padding: 0px; min-width: 20px; font-size: 12px"><p>${i['Pbuf']}</p></td>`
                            body.appendChild(tr);
                        }
                    } else {
                        var tr=document.createElement('tr')
                        tr.innerHTML+=`<td colspan="24">Данных нет</td>`
                        body.appendChild(tr);
                    }
                }
                if (document.getElementById('dks_number').textContent === '2'){
                    var buff = document.getElementsByClassName('buff')
                    for(var i=0; i<buff.length; i++)buff[i].style.display='none';
                } else {
                    var buff = document.getElementsByClassName('buff')
                    for(var i=0; i<buff.length; i++)buff[i].style.display='';
                }
            },
            async:true
        })
    }
    setTimeout(function() {
        window.print();
    }, 500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
        document.location.href = "/get_gpa_rezhim_report/"+document.getElementById('dks_number').textContent
    }
</script>
