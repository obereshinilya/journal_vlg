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
<p id="mesto" style="display: none">{{$mesto}}</p>

<div style="display: inline-flex; width: 100%">
    <h3 style="text-align: center; width: 100%">Балансовый отчет за {{$date}}</h3>
</div>
    <style>
        .choice-period-btn {
            display: none;
        }
    </style>
{{--    <div id="content-header"></div>--}}
    <div id="redirect" style="text-align: center">
        <div style="width: 100%">
            <table id="statickItemInfoTable_yams" class="itemInfoTable" style="width: 100%; float:left; table-layout: fixed; display: table; align-content: center">
                <thead>
                <tr>
                    <th class="objCell" >День</th>
                    <th class="objCell" >Закачка</th>
                    <th class="objCell" >Отбор</th>
                    <th class="objCell" >Собств. нужды</th>
                    <th class="objCell" >Тех.потери</th>
                    <th class="objCell" >Товарный газ</th>
                    <th class="objCell" >План</th>
                    <th class="objCell" >Отклонение</th>
                </tr>
                </thead>
                <tbody id="tbody_yams">

                </tbody>
            </table>
        </div>
        <div style="display: block">
            <span style="text-decoration:underline; float: right;font-size: 20px; margin-top: 50px; margin-right: 50px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        </div>


    </div>

    <style>
        .content {
            width: calc(100% - 40px);
        }
    </style>

    <script>

        $(document).ready(function () {
            // get_plan()
            get_table_data();
        })

        function get_table_data() {
            var month =document.getElementById('date').textContent
            $.ajax({
                url: '/get_val/'+month+'/month',
                method: 'GET',
                success: function (res) {
                    var type = document.getElementById('mesto').textContent
                    // for (var i=0; i<2; i++){
                    //     if (i === 0){
                    //         type = 'yams'
                    //     } else {
                    //         type = 'yub'
                    //     }
                        ///заполнение таблицы факт
                        var tbody = document.getElementById('tbody_yams')
                        tbody.innerText = ''
                        var buff_fakt = 0
                        var buff_out = 0
                        var buff_plan = 0
                        var buff_self = 0
                        var buff_lost = 0
                        var buff_tovar = 0
                        var buff_otkl = 0
                        for (var j=1; j<=Object.keys(res[type]).length; j++){
                            var last_fakt = 0
                            var last_out = 0
                            var last_self = 0
                            var last_lost = 0
                            var last_tovar = 0
                            var last_plan = 0
                            var tr = document.createElement('tr')
                            tr.style.padding = '2px'
                            tr.style.textAlign= 'center'
                            var td_day = document.createElement('td')
                            td_day.innerHTML = `${j+'.'+month.split('-')[1]}`
                            var td_fakt = document.createElement('td')
                            if (res[type][j] !== '...'){
                                buff_fakt += Number (res[type][j])
                                last_fakt = Number (res[type][j])
                                td_fakt.innerHTML = `<span>${Number(res[type][j]).toFixed(3)}</span>`

                            }else {
                                td_fakt.innerHTML = `<span>${res[type][j]}</span>`

                            }
                            var td_out = document.createElement('td')
                            if (res[type+'_out'][j] !== '...'){
                                buff_out += Number (res[type+'_out'][j])
                                last_out = Number (res[type+'_out'][j])
                                td_out.innerHTML = `<span>${Number(res[type+'_out'][j]).toFixed(3)}</span>`

                            }else {
                                td_out.innerHTML = `<span>${res[type+'_out'][j]}</span>`
                            }
                            var td_self = document.createElement('td')
                            if (res[type+'_self'][j] !== '...'){
                                buff_self += Number (res[type+'_self'][j])
                                last_self = Number (res[type+'_self'][j])
                                td_self.innerHTML = `<span>${Number(res[type+'_self'][j]).toFixed(3)}</span>`
                            }else {
                                td_self.innerHTML = `<span>${res[type+'_self'][j]}</span>`
                            }
                            var td_lost = document.createElement('td')
                            if (res[type+'_lost'][j] !== '...'){
                                buff_lost += Number (res[type+'_lost'][j])
                                last_lost = Number (res[type+'_lost'][j])
                                td_lost.innerHTML = `<span>${Number(res[type+'_lost'][j]).toFixed(3)}</span>`
                            }else {
                                td_lost.innerHTML = `<span>${res[type+'_lost'][j]}</span>`
                            }
                            var td_tovar=document.createElement('td')
                            last_tovar = Number (last_fakt - last_lost - last_self)
                            buff_tovar += Number(last_tovar)
                            td_tovar.innerHTML = `<span>${last_tovar.toFixed(3)}</span>`

                            var td_plan = document.createElement('td')
                            var last_plan = Number(res['plan'][type][j]).toFixed(3)
                            if (last_plan === 'NaN'){
                                last_plan = Number(0).toFixed(3)
                            }
                            td_plan.innerHTML+=`<span>${last_plan}</span>`
                            buff_plan += Number(last_plan)
                            // if (res['plan'][type]['prostoy'][j]*res['plan'][type]['weight']){
                            //     var plan = res['plan'][type]['prostoy'][j]*res['plan'][type]['weight']
                            //     td_plan.innerHTML = `<span>${(plan).toFixed(2)}</span>`
                            //     last_plan = Number(plan)
                            //     buff_plan += Number(plan)
                            // }else {
                            //     td_plan.innerHTML = `<span>0.000</span>`
                            // }
                            var td_otkl=document.createElement('td')
                            td_otkl.innerHTML = `<span>${(Number(last_tovar)-Number(last_plan) - Number(last_out)).toFixed(3)}</span>`
                            buff_otkl+=Number((Number(last_tovar-last_plan-last_out)).toFixed(3))

                            tr.appendChild(td_day)
                            tr.appendChild(td_fakt)
                            tr.appendChild(td_out)
                            tr.appendChild(td_self)
                            tr.appendChild(td_lost)
                            tr.appendChild(td_tovar)
                            tr.appendChild(td_plan)
                            tr.appendChild(td_otkl)
                            tbody.appendChild(tr);
                        }
                        var tr = document.createElement('tr')
                        tr.style.textAlign = 'center'
                        var td_day=document.createElement('td')
                        td_day.innerHTML = `<span><b>Итого:</b></span>`
                        var td_fakt=document.createElement('td')
                        td_fakt.innerHTML = `<span>${buff_fakt.toFixed(3)}</span>`
                        var td_out=document.createElement('td')
                        td_out.innerHTML = `<span>${buff_out.toFixed(3)}</span>`
                        var td_self=document.createElement('td')
                        td_self.innerHTML = `<span>${buff_self.toFixed(3)}</span>`
                        var td_lost=document.createElement('td')
                        td_lost.innerHTML = `<span>${buff_lost.toFixed(3)}</span>`
                        var td_tovar=document.createElement('td')
                        td_tovar.innerHTML = `<span>${buff_tovar.toFixed(3)}</span>`
                        var td_plan=document.createElement('td')
                        td_plan.innerHTML = `<span>${buff_plan.toFixed(3)}</span>`
                        var td_otkl=document.createElement('td')
                        td_otkl.innerHTML = `<span>${buff_otkl.toFixed(3)}</span>`
                        tr.appendChild(td_day)
                        tr.appendChild(td_fakt)
                        tr.appendChild(td_out)
                        tr.appendChild(td_self)
                        tr.appendChild(td_lost)
                        tr.appendChild(td_tovar)
                        tr.appendChild(td_plan)
                        tr.appendChild(td_otkl)
                        tbody.appendChild(tr);
                    // }
                    // document.getElementById('statickItemInfoTable_yub').style.display = 'inline-block'
                    // document.getElementById('tableDiv_yams').style.display = 'inline-block'
                    // $.ajax({
                    //     url:'/get_level',
                    //     type:'GET',
                    //     success:(res)=>{
                    //         try {
                    //             document.getElementById('yub_text').style.display = 'none'
                    //             document.getElementById('statickItemInfoTable_yub').style.display = 'none'
                    //
                    //         }catch (e){
                    //             try {
                    //                 setTimeout( function() {
                    //                     document.getElementById('yub_text').style.display = 'none'
                    //                     document.getElementById('statickItemInfoTable_yub').style.display = 'none'
                    //                 }, 200 );
                    //
                    //             } catch (ee){
                    //                 setTimeout( function() {
                    //                     document.getElementById('yub_text').style.display = 'none'
                    //                     document.getElementById('statickItemInfoTable_yub').style.display = 'none'
                    //                 }, 200 );
                    //             }
                    //         }
                    //     },
                    //     async:false
                    // })
                },
                async:false
            })
        }

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_val_month"
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


