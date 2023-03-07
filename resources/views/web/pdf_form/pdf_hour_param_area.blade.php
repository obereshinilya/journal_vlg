<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px
    }

    .itemInfoTable th, .itemInfoTable td {
        border: 1px solid black;
        margin: 0;
        padding: 0;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    table {
        font-size: 12px;
        -webkit-print-color-adjust: exact; /* благодаря этому заработал цвет*/
        border-spacing: 0px;
        border-collapse: collapse;
        /* благодаря этому строки переносятся*/
        /*white-space: pre-wrap; !* css-3 *!*/
        white-space: -moz-pre-wrap; /* Mozilla, начиная с 1999 года */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        word-wrap: break-word;
    / word-break: break-all;
    }

    table th {
        background-color: darkgrey;
    }
</style>

<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
@stack('scripts')
@stack('styles')
<script src="{{ asset('js/app.js') }}"></script>

<p id="date" style="display: none">{{$date}}</p>

<div style="display: inline-flex; width: 100%;">
    <h3 style="width: 100%; text-align: center">Часовые показатели за {{$date}}</h3>
</div>
<style>
    .choice-period-btn {
        display: none;
    }
</style>
<div id="content-header"></div>


<div id="redirect">
    <div id="tableDiv" style="width: auto; text-align: center">
        <table class="itemInfoTable">
            <thead>
            <tr>
                <th>Наименование параметра</th>
                <th>Ед.изм.</th>
                @foreach($data['time'] as $time)
                    <th>{{$time}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody id="tbody_table">
            @foreach($data['namepar1'] as $key=>$value)
                <tr>
                    <td>{{$value}}</td>
                    <td>{{$data['shortname'][$key]}}</td>
                    @foreach($data['params'][$key] as $param)
                        <td>{{$param}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 40px">
        <span style="text-decoration:underline; float: right; font-size: 20px">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; / &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
    </div>
</div>
<script>

    $(document).ready(function () {
        get_table_data(document.getElementById('date').textContent)

    })


    setTimeout(function () {
        window.print();
    }, 1500)
    var div = document.getElementById("redirect")
    div.onclick = function () {
        document.location.href = "/"
    }


</script>
<style>
    td {
        text-align: center;
    }

    .itemInfoTable span {
        text-align: center;
    }

</style>


