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
</style>
<table style="border-collapse: collapse;" class="table table-hover">
    <thead>
    <tr>
        <th colspan="{{$num_days+2}}"><h2 class="text-muted" style="text-align: center">{{$title}}</h2></th>
    </tr>
    <tr>
        <th>Наименование параметра</th>
        <th>Ед.изм.</th>
        @for($i=1; $i<=$num_days; $i++)
            @if($i<10)
                <th>{{'0'.$i.'-'.$month}}</th>
            @else
                <th>{{$i.'-'.$month}}</th>
            @endif
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$row['namepar1']}}</td>
            <td>{{$row['shortname']}}</td>
            @for($i=1; $i<=$num_days; $i++)
                @if(!$row[$i]['id'])
                <td>...</td>
                @else
                    <td>{{$row[$i]['val']}}</td>
                @endif
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
