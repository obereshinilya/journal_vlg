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

<p id="date" style="display: none">{{$year}}</p>

<div style="width: 100%; text-align: center">
    <h3 >График проведения ППР объектов ООО "Газпром добыча Надым" за {{$year}} </h3>
</div>


<div id="redirect">

    <div id="tableDiv" style="width: 100%; overflow-y: auto">
        <table id="statickItemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table">
            <colgroup>
                <col style="width: 3%">
                <col style="width: 13%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 12%">
                <col style="width: 10%">
                <col style="width: 21%">
            </colgroup>
            <thead>
            <tr>
                <th style="text-align: center" rowspan="2">П/п</th>
                <th style="text-align: center" rowspan="2">Объект ППР</th>
                <th style="text-align: center" colspan="2">Согласованные сроки</th>
                <th style="text-align: center" colspan="2">Фактические сроки</th>
                <th style="text-align: center" rowspan="2">Вид ремонта</th>
                <th style="text-align: center" rowspan="2">Примечание</th>
            </tr>
            <tr>
                <th style="text-align: center">Начало</th>
                <th style="text-align: center">Окончание</th>
                <th style="text-align: center">Начало</th>
                <th style="text-align: center">Окончание</th>
            </tr>
            </thead>
            <tbody id="table_body">
            <tr>
                <td colspan="9" ><span>Данных нет</span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="">
        <span style="text-decoration:underline; float: right; font-size: 20px; margin-top: 50px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
</div>

<script>

    $(document).ready(function () {
        get_table($('#year').val())
        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_ppr"
        }
    })

    function get_table(year){
        $.ajax({
            url: '/get_ppr/'+document.getElementById('date').textContent,
            method: 'GET',
            success: function (res) {
                var body = document.getElementById('table_body')
                body.innerText = ''
                var lenght = res.length
                for (var row of res){
                    var tr = document.createElement('tr')
                    tr.setAttribute('data-id', row['id'])
                    if (!row['type_job'])
                        row['type_job'] = '-'
                    if (!row['comment'])
                        row['comment'] = '-'
                    if (!row['fact_begin'])
                        row['fact_begin'] = '-'
                    if (!row['fact_end'])
                        row['fact_end'] = '-'
                    tr.innerHTML += `<td style="text-align: center">${lenght}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['object']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['plan_begin']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['plan_end']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['fact_begin']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['fact_end']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['type_job']}</td>`
                    tr.innerHTML += `<td style="text-align: center">${row['comment']}</td>`
                    body.appendChild(tr);
                    lenght = lenght-1
                }
            },
            async:false
        })
    }
</script>
<style>
    td{
        white-space: pre-wrap; /* css-3 */
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /
        word-break: break-all;
    }

    span{
        outline: none !important;
    }
    table.iksweb{
        width: 100%;
        border-collapse:collapse;
        border-spacing:0;
        height: auto;
        table-layout: fixed;
    }
    table.iksweb td, table.iksweb th {
        border: 1px solid #595959;
    }
    table.iksweb td,table.iksweb th {
        min-height:20px;
        padding: 1px;
        /*width: 30px;*/
        /*height: 40px;*/
    }
    table.iksweb th {

        background: #347c99 !important;
        color: #fff;
        font-weight: normal;
    }


    input[type=date]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        display: none;
    }
    input[type=date]::-webkit-clear-button {
        -webkit-appearance: none;
        display: none;
    }

    .date_input:placeholder-shown ~ .form__label {
        font-size: 1.3rem;
        cursor: text;
        top: 20px;
    }

    .date_input:focus ~ .table_date_label {
        position: absolute;
        top: 0;
        display: block;
        transition: 0.2s;
        font-size: 1rem;
        color: black;
        font-weight: 700;
    }


</style>

