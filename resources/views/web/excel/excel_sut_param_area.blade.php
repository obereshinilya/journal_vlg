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
        @foreach($data['time'] as $time)
            <th>{{$time}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
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
