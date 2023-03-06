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
<p id="parent" style="display: none">{{$parent}}</p>
<p id="search" style="display: none">{{$search}}</p>

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
        get_table_data(document.getElementById('date').textContent, document.getElementById('parent').textContent)
        if (document.getElementById('parent').textContent !== 'undefined') {
            hide_row()
        }
        search_result()
    })
    //
    // function get_table_data(date, parentId) {
    //
    //
    //
    //     $.ajax({
    //         url: '/hours_param_area/'+date,
    //         method: 'Post',
    //         data: data,
    //         success: function (res) {
    //             var table_body = document.getElementById('tbody_table')
    //             table_body.innerText = ''
    //             for (var row of res) {
    //                 var tr = document.createElement('tr')
    //                 tr.setAttribute('data-id', row['hfrpok'])
    //                 tr.innerHTML += `<td><span style="background-color: rgba(0, 0, 0, 0)">${row['namepar1']}</span></td>`
    //                 tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)">${row['shortname']}</span></td>`
    //                 for (var id = 1; id <= 24; id++) {
    //                     if (row[id]['id']){
    //                         tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)" data-type="float">${row[id]['val']}</span></td>`
    //                     }else {
    //                         tr.innerHTML += `<td><spanstyle="background-color: rgba(0, 0, 0, 0)" data-type="float">...</span></td>`
    //                     }
    //                 }
    //
    //                 table_body.appendChild(tr);
    //             }
    //         },
    //         async:false
    //     })
    // }
    //
    // function hide_row(){
    //     $.ajax({
    //         url:'/get_parent/'+document.getElementById('parent').textContent,
    //         type:'GET',
    //         success:(res)=>{
    //             var un_visible_rows = Object.values(res)
    //             var all_trs = document.querySelectorAll('tbody tr')
    //             for (var one_tr of all_trs){
    //                 if (un_visible_rows.includes(Number (one_tr.getAttribute('data-id'))) ){ //если данную строку надо скрыть
    //                     one_tr.style.display = 'none'
    //                     one_tr.classList.add('hidden_rows')
    //                 } else {    //если строку надо показать
    //                     one_tr.style.display = ''
    //                 }
    //             }
    //         }, async: false
    //     })
    // }

    function search_result() {
        if (document.getElementById('search').textContent !== 'false') {
            var search_text = new RegExp(document.getElementById('search').textContent, 'i');
            var body = document.getElementById('tbody_table')
            var all_tr = body.getElementsByTagName('tr')
            for (var i = 0; i < all_tr.length; i++) {
                if (!all_tr[i].classList.contains('hidden_rows')) {
                    if (all_tr[i].getElementsByTagName('td')[0].textContent.match(search_text)) {
                        all_tr[i].style.display = ''
                    } else {
                        all_tr[i].style.display = 'none'
                    }
                }
            }
        }
    }

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


