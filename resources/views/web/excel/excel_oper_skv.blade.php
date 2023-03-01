<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px
    }

    .table th,
    .table td {
        padding: 5px;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        border: 1px solid black; /* Параметры рамки */
        text-align: center;
    }

    .table-hover tbody tr:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0.075);
    }
    table.iksweb{text-decoration: none;border-collapse:collapse;width:100%;text-align:center;}
    table.iksweb th{font-weight:normal;font-size:12px; color:#ffffff;background-color:#347c99;}
    table.iksweb td{font-size:13px;color:#347c99;}
    table.iksweb td,table.iksweb th{white-space:pre-wrap;padding:5px 2px;line-height:13px;vertical-align: middle;border: 1px solid black;}
    table.iksweb tr:hover{background-color:#f9fafb}
    table.iksweb tr:hover td{color:#347c99;cursor:default;}
</style>
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
                        @if(array_key_exists($row['short'].'_'.$i, $data))
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">
                                {{$data[$row['short'].'_'.$i]}}</td>
                        @else
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                        @endif
                    @endfor
                    @if(array_key_exists($row['short'].'_7', $data))
                        <td contenteditable="true" id="{{$row['short'].'_7'}}" onblur="save_td(this)">{{$data[$row['short'].'_7']}}</td>

                    @else
                        <td contenteditable="true" id="{{$row['short'].'_7'}}" onblur="save_td(this)">...</td>
                    @endif
                    @if(array_key_exists($row['short'].'_8', $data))
                        <td contenteditable="true" id="{{$row['short'].'_8'}}" onblur="save_td(this)">{{$data[$row['short'].'_8']}}</td>

                    @else
                        <td contenteditable="true" id="{{$row['short'].'_8'}}" onblur="save_td(this)">...</td>
                    @endif
                @else
                    <td style="text-align: right">{{$row['name']}}</td>
                    @for($i=1; $i<8; $i++)
                        @if(array_key_exists($row['short'].'_'.$i, $data))
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">
                                {{$data[$row['short'].'_'.$i]}}</td>
                        @else
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                        @endif
                    @endfor
                @endif
            @else
                @if($row['th'])
                    <th>{{$row['name']}}</th>
                    @for($i=1; $i<9; $i++)
                        @if(array_key_exists($row['short'].'_'.$i, $data))
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">
                                {{$data[$row['short'].'_'.$i]}}</td>
                        @else
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                        @endif
                    @endfor
                @else
                    @for($i=1; $i<10; $i++)
                        @if(array_key_exists($row['short'].'_'.$i, $data))
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">
                                {{$data[$row['short'].'_'.$i]}}</td>
                        @else
                            <td contenteditable="true" id="{{$row['short'].'_'.$i}}" onblur="save_td(this)">...</td>
                        @endif
                    @endfor
                @endif
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<script>

    $(document).ready(function () {
        getTableData();
    })

    function getTableData(){
        $.ajax({
            url: '/get_data_oper_skv/'+document.getElementById('timestamp').textContent,
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
