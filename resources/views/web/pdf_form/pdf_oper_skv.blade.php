<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 14px}

    table{
        font-size: 12px;
        -webkit-print-color-adjust: exact; /* благодаря этому заработал цвет*/
        border-spacing: 0px;
        border-collapse: collapse;
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /
    word-break: break-all;
    }
    table th{
        background-color: darkgrey;
    }
    table.iksweb{text-decoration: none;border-collapse:collapse;width:100%;text-align:center;}
    table.iksweb th{font-weight:normal;font-size:12px; color:#ffffff;background-color:#347c99;}
    table.iksweb td{font-size:13px;color:#347c99;}
    table.iksweb td,table.iksweb th{white-space:pre-wrap;padding:5px 2px;line-height:13px;vertical-align: middle;border: 1px solid black;}
    table.iksweb tr:hover{background-color:#f9fafb}
    table.iksweb tr:hover td{color:#347c99;cursor:default;}
    /*.table_scroll{overflow-y: scroll; height: calc(100% - 80px)}*/
</style>

<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
@stack('scripts')
@stack('styles')
<script src="{{ asset('js/app.js') }}"></script>

<p id="date" style="display: none">{{$timestamp}}</p>

<div style="width: 100%; text-align: center">
    <h3 >Оперативное состоние скважин на {{$timestamp}} </h3>
</div>


<div id="redirect">
    <p id="head" style="display: none" contenteditable="true"></p>
    <div class="table_scroll">
        <table class="iksweb">
            <tbody>
            <tr>
                <td id="timestamp">{{$timestamp}}</td>
                <th colspan="2" rowspan="2">ТТР H2O на ГИС, град. С</th>
                <th colspan="5">Отбор</th>
                <th rowspan="3">Рпл, кгс/см2</th>
            </tr>
            <tr>
                <th rowspan="2">УПХГ</th>
                <th colspan="2">Темп отбора, тыс. м3/час</th>
                <th colspan="2">Давление на ГИС/ПЗРГ, кгс/см2</th>
                <th rowspan="2">Давления по ТС, кг/см2</th>
            </tr>
            <tr>
                <th>2ч</th>
                <th>5мин</th>
                <th>2ч</th>
                <th>5мин</th>
                <th>2ч</th>
                <th>5м</th>
            </tr>
            @foreach($uphg as $row)
                <tr>
                    @if($row['main'])
                        @if($row['th'])
                            <th>{{$row['name']}}</th>
                            @for($i=1; $i<7; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                            <td rowspan="3" contenteditable="true" id="{{$row['short'].'_7'}}" onblur="save_td(this)">...</td>
                            <td contenteditable="true" id="{{$row['short'].'_8'}}" onblur="save_td(this)">...</td>
                        @else
                            <td style="text-align: right">{{$row['name']}}</td>
                            @for($i=1; $i<8; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @endif
                    @else
                        @if($row['th'])
                            <th>{{$row['name']}}</th>
                            @for($i=1; $i<9; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @else
                            @for($i=1; $i<10; $i++)
                                <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                            @endfor
                        @endif
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="">
        <span style="text-decoration:underline; float: right; font-size: 20px; margin-top: 50px; margin-right: 50px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
</div>

<script>

    $(document).ready(function () {
        getTableData();
        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/report_oper_skv_main"
        }
    })

    function getTableData(){
        $.ajax({
            url: '/get_data_oper_skv/'+document.getElementById('date').textContent,
            method: 'get',
            async:false,
            success: function (res) {
                for (var td of res){
                    if (td['content_editable']){
                        document.getElementById(td['id_td']).textContent = td['text']
                    }else {
                        document.getElementById(td['id_td']).textContent = td['text']
                        document.getElementById(td['id_td']).removeAttribute("contenteditable")
                        console.log(document.getElementById(td['id_td']))
                    }
                }
            },
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

