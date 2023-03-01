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

<div style="display: inline-flex; width: 100%; margin-left: 33%">
    <h3 >Сменный журнал ООО "Газпром ПХГ" за {{$date}} </h3>
</div>
<div id="content-header"></div>


<div id="redirect">

    <div id="tableDiv" style="width: 100%; font-size: 14px">
        <div style="width: 100%; margin: 0px; padding: 0px">
            <table class="iksweb" id="shapka" style="table-layout: fixed; width: 100%; display: table; padding: 0px; margin: 0px">
                <tbody>
                <tr style="border: 2px solid black">
                    <th colspan="2">Выдано для ООО "Газпром ПХГ"</th>
                    <td  id="cpdd_all" style="background: rgba(0, 0, 0, 0)">0</td>
                    <th colspan="2">Распределено по УПХГ</th>
                    <td id="cpdd_spend" style="background: rgba(0, 0, 0, 0)">0</td>
                    <th colspan="2">Осталось распределить</th>
                    <td id="cpdd_left" style="background: rgba(0, 0, 0, 0)">0</td>
                    <th>Комментарий</th>
                    <td colspan="2" id="comment_cpdd"></td>
                </tr>
                <tr>
                    <th rowspan="2">ПХГ</th>
                    <th rowspan="2">Задание</th>
                    <th rowspan="2">Факт</th>
                    <th rowspan="2">Отклонение</th>
                    <th colspan="2">Смена</th>
                    <th rowspan="2">ПХГ</th>
                    <th rowspan="2">Задание</th>
                    <th rowspan="2">Факт</th>
                    <th rowspan="2">Отклонение</th>
                    <th colspan="2">Смена</th>
                </tr>
                <tr>
                    <th>08:00-20:00</th>
                    <th>20:00-08:00</th>
                    <th>08:00-20:00</th>
                    <th>20:00-08:00</th>
                </tr>
                <tr>
                    <th>П.Уметское ПХГ</th>
                    <td id="header_1"></td>
                    <td id="header_2"></td>
                    <td id="header_3"></td>
                    <td id="header_4"></td>
                    <td id="header_5"></td>
                    <th>Невское ПХГ</th>
                    <td id="header_6"></td>
                    <td id="header_7"></td>
                    <td id="header_8"></td>
                    <td id="header_9"></td>
                    <td id="header_10"></td>
                </tr>
                <tr>
                    <th>Елшанское ПХГ</th>
                    <td id="header_11"></td>
                    <td id="header_12"></td>
                    <td id="header_13"></td>
                    <td id="header_14"></td>
                    <td id="header_15"></td>
                    <th>Гатчинское ПХГ</th>
                    <td id="header_16"></td>
                    <td id="header_17"></td>
                    <td id="header_18"></td>
                    <td id="header_19"></td>
                    <td id="header_20"></td>
                </tr>
                <tr>
                    <th>Степновское ПХГ</th>
                    <td id="header_21"></td>
                    <td id="header_22"></td>
                    <td id="header_23"></td>
                    <td id="header_24"></td>
                    <td id="header_25"></td>
                    <th>Калининградское ПХГ</th>
                    <td id="header_26"></td>
                    <td id="header_27"></td>
                    <td id="header_28"></td>
                    <td id="header_29"></td>
                    <td id="header_30"></td>
                </tr>
                <tr>
                    <th>Похвостневское УПХГ</th>
                    <td id="header_31"></td>
                    <td id="header_32"></td>
                    <td id="header_33"></td>
                    <td id="header_34"></td>
                    <td id="header_35"></td>
                    <th>Волгоградское УПХГ</th>
                    <td id="header_36"></td>
                    <td id="header_37"></td>
                    <td id="header_38"></td>
                    <td id="header_39"></td>
                    <td id="header_40"></td>
                </tr>
                <tr>
                    <th>Похвостневская пром.пл.</th>
                    <td id="header_41"></td>
                    <td id="header_42"></td>
                    <td id="header_43"></td>
                    <td id="header_44"></td>
                    <td id="header_45"></td>
                    <th>с.Ставропольское ПХГ</th>
                    <td id="header_46"></td>
                    <td id="header_47"></td>
                    <td id="header_48"></td>
                    <td id="header_49"></td>
                    <td id="header_50"></td>
                </tr>
                <tr>
                    <th>Отрадневская пром.пл.</th>
                    <td id="header_51"></td>
                    <td id="header_52"></td>
                    <td id="header_53"></td>
                    <td id="header_54"></td>
                    <td id="header_55"></td>
                    <th>Краснодарское ПХГ</th>
                    <td id="header_56"></td>
                    <td id="header_57"></td>
                    <td id="header_58"></td>
                    <td id="header_59"></td>
                    <td id="header_60"></td>
                </tr>
                <tr>
                    <th>Щелковское ПХГ</th>
                    <td id="header_61"></td>
                    <td id="header_62"></td>
                    <td id="header_63"></td>
                    <td id="header_64"></td>
                    <td id="header_65"></td>
                    <th>Кущевское ПХГ</th>
                    <td id="header_66"></td>
                    <td id="header_67"></td>
                    <td id="header_68"></td>
                    <td id="header_69"></td>
                    <td id="header_70"></td>
                </tr>
                <tr>
                    <th>Калужское ПХГ</th>
                    <td id="header_71"></td>
                    <td id="header_72"></td>
                    <td id="header_73"></td>
                    <td id="header_74"></td>
                    <td id="header_75"></td>
                    <th>Канчуринское ПХГ</th>
                    <td id="header_76"></td>
                    <td id="header_77"></td>
                    <td id="header_78"></td>
                    <td id="header_79"></td>
                    <td id="header_80"></td>
                </tr>
                <tr>
                    <th>Касимовское УПХГ</th>
                    <td id="header_81"></td>
                    <td id="header_82"></td>
                    <td id="header_83"></td>
                    <td id="header_84"></td>
                    <td id="header_85"></td>
                    <th>Пунгинское ПХГ</th>
                    <td id="header_86"></td>
                    <td id="header_87"></td>
                    <td id="header_88"></td>
                    <td id="header_89"></td>
                    <td id="header_90"></td>
                </tr>
                <tr>
                    <th>Совхозное ПХГ</th>
                    <td id="header_91"></td>
                    <td id="header_92"></td>
                    <td id="header_93"></td>
                    <td id="header_94"></td>
                    <td id="header_95"></td>
                    <th>Карашурское ПХГ</th>
                    <td id="header_96"></td>
                    <td id="header_97"></td>
                    <td id="header_98"></td>
                    <td id="header_99"></td>
                    <td id="header_100"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 100%; margin: 0px; padding: 0px">
            <table class="iksweb" style="table-layout: fixed; padding: 0px; margin: 0px; padding-left: 1px">
                <col style="width: 8%">
                <col style="width: 5%">
                <col style="width: 8%">
                <col style="width: 8%">
                <col style="width: 69.5%">
                <tbody>
                <tr style="">
                    <th colspan="2">Объект</th>
                    <th colspan="3">
                        <table style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">

                            <tbody>
                            <tr style="position: sticky; top: 0">
                                <td style=" text-align: center; border: 0px">Оборудование</td>
                                <td style=" text-align: center; border: 0px">Статус</td>
                                <td style=" text-align: center; border: 0px">Дата/Время/Описание работ</td>
                                <td style=" text-align: center; border: 0px"></td>
                            </tr>
                            </tbody>
                        </table>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">П.Уметское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg1_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Елшанское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg2_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Степновское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg3_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Похвостневское УПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg4_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Похвостневская пром.пл.
                    </td>
                    <td colspan="3">
                        <table id="phg5_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Отрадневская пром.пл.
                    </td>
                    <td colspan="3">
                        <table id="phg6_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Щелковское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg7_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">П.Калужское  ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg8_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Касимовское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg9_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Совхозное ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg10_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Невское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg11_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Гатчинское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg12_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Калининградское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg13_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Волгоградское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg14_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">с.Ставропольское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg15_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Краснодарское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg16_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Кущевское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg17_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Канчуринское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg18_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Пунгинское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg19_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Карашурское ПХГ
                    </td>
                    <td colspan="3">
                        <table id="phg1_table" style="width: 100%; table-layout: fixed; margin-bottom: 0px; padding: 0px; display: table; border-collapse: collapse">
                            <col style="width: 7%">
                            <col style="width: 7%">
                            <col style="width: 78%">


                            <tbody>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 50px">
        <span style="text-decoration:underline; float: right; margin-right: 50px font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
</div>

<script>

    $(document).ready(function () {
        get_insert_tables()
        get_tds()

        setTimeout(function() {
            window.print();
        }, 1500)
        var div = document.getElementById("redirect")
        div.onclick = function(){
            document.location.href = "/open_journal_smeny"
        }
    })

    function get_tds(){
        $.ajax({
            url: '/get_tds/'+document.getElementById('date').textContent,
            method: 'get',
            success: function (tds) {
                for (var td of tds){
                    try {
                        var one_td = document.getElementById(td['id_record'])
                        one_td.textContent = td['val']
                        one_td.style.color = td['color_text']
                        one_td.style.fontWeight = td['text_weight']
                    }catch (e){

                    }
                }
                // var default_tds = new Map([
                //     ['row1_td1', 'Задание ЦПДД (Вал):'],
                //     ['row1_td2', '(задание) Q вал/сут']
                // ]);
                // for (var default_td of default_tds){
                //     if (!document.getElementById(default_td[0]).textContent){
                //         document.getElementById(default_td[0]).textContent = default_td[1]
                //     }
                // }
            },
            async:false
        })
    }



    function get_insert_tables(){
        $.ajax({
            url: '/get_insert_tabels/'+document.getElementById('date').textContent+'/all',
            method: 'get',
            success: function (tables) {
                for (var table of tables){
                    try {
                        var body = document.getElementById(table['name_table']).getElementsByTagName('tbody')[0]
                        body.innerText = ''
                        for (var row of table['rows']){
                            if (row['on_print'] == true){
                                var tr = document.createElement('tr')
                                tr.style.minHeight = '20px'
                                tr.id = 'record_'+row['id']
                                tr.className = 'changeble_tr'
                                tr.innerHTML += `<td oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="oborudovanie" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}">${row['oborudovanie']}</td>`
                                tr.innerHTML += `<td oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="status" style="text-align: center; background-color: ${row['color_back']}; color: ${row['color_text']}">${row['status']}</td>`
                                tr.innerHTML += `<td oncontextmenu="change_color_text(this)" ondblclick="change_color(this)" onclick="this.parentNode.getElementsByClassName('disketa')[0].style.display = ''" class="date" style="text-align: left; background-color: ${row['color_back']}; color: ${row['color_text']}">${row['date']}</td>`
                                body.appendChild(tr);
                            }
                        }
                    }catch (e){

                    }
                }
            },
            async:false
        })
    }

</script>
<style>
    #shapka td{
        text-align: center;
    }
    td.changeble_td, th.changeble_td, td.oborudovanie, td.status, td.date{
        white-space: pre-wrap; /* css-3 */
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word; /
        word-break: break-all;
    }
    img:hover{
        transform: scale(1.3);
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
        background: #E7E6E6 !important;
        color: black;
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

