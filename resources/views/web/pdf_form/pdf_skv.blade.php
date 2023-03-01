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
    <h3 >Состояние скважин на {{$date}} </h3>
</div>


<div id="redirect">

    <div id="tableDiv" style="width: 100%; overflow-y: auto">
        <table id="statickItemInfoTable" class="itemInfoTable" style="width: 100%; table-layout: fixed; display: table">
{{--            <colgroup>--}}
{{--                <col style="width: 3%">--}}
{{--                <col style="width: 13%">--}}
{{--                <col style="width: 12%">--}}
{{--                <col style="width: 12%">--}}
{{--                <col style="width: 12%">--}}
{{--                <col style="width: 12%">--}}
{{--                <col style="width: 10%">--}}
{{--                <col style="width: 21%">--}}
{{--            </colgroup>--}}
            <thead>
            <tr>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Месторождение</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Всего</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В работе</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">Простой</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В т.ч. в резерве</th>
                <th style="width: 20%; text-align: center; position: sticky; top: 0">В т.ч. ремонте</th>
            </tr>
            </thead>
            <tbody id="time_id">

            </tbody>
        </table>
    </div>
    <div style="">
        <span style="text-decoration:underline; float: right; font-size: 20px; margin-top: 50px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
</div>

<script>

    $(document).ready(function () {
        get_table()
        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/report_skv"
        }
    })

    function get_table(date){
        $.ajax({
            url: '/get_skv/'+document.getElementById('date').textContent,
            method: 'GET',
            success: function (res) {
                var body = document.getElementById('time_id')
                body.innerText = ''
                for (var i=1; i<=Object.values(res['name']).length; i++){
                    var tr=document.createElement('tr')
                    tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${res['name'][i]}</p></td>`

                    try {
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['all'][i]['val']).toFixed(0)}</p></td>`
                    }catch (e){
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                    }
                    try {
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['job'][i]['val']).toFixed(0)}</p></td>`
                    }catch (e){
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                    }
                    try {
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['prostoy'][i]['val']).toFixed(0)}</p></td>`
                    }catch (e){
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                    }
                    try {
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['reserv'][i]['val']).toFixed(0)}</p></td>`
                    }catch (e){
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                    }
                    try {
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>${Number(res['repair'][i]['val']).toFixed(0)}</p></td>`
                    }catch (e){
                        tr.innerHTML+=`<td style="text-align: center; padding: 0px; min-width: 20px"><p>...</p></td>`
                    }
                    body.appendChild(tr);
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

