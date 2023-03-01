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
    <thead style="width: 100%">
    <tr>
        <td colspan="13">{{$title}}</td>
    </tr>
    <tr>
        <th rowspan="2" style="text-align: center; position: sticky; top: 0">Час</th>
        <th colspan="2" style=" text-align: center; position: sticky; top: 0">Волгоградское ПХГ</th>
        <th colspan="3" style=" text-align: center; position: sticky; top: 0">Скважины</th>
        <th colspan="7" style=" text-align: center; position: sticky; top: 0">КЦ-1</th>
    </tr>
    <tr>
        <th style="text-align: center; position: sticky; top: 25px">Закачка (тыс.м<sup>3</sup>)</th>
        <th style="text-align: center; position: sticky; top: 25px">Отбор (тыс.м<sup>3</sup>)</th>
        <th style="text-align: center; position: sticky; top: 25px">в работе</th>
        <th style="text-align: center; position: sticky; top: 25px">в резерве</th>
        <th style="text-align: center; position: sticky; top: 25px">в ремонте</th>
        <th style="text-align: center; position: sticky; top: 25px">Т вх</th>
        <th style="text-align: center; position: sticky; top: 25px">Т вых</th>
        <th style="text-align: center; position: sticky; top: 25px">Р вх</th>
        <th style="text-align: center; position: sticky; top: 25px">Р вых</th>
        <th style="text-align: center; position: sticky; top: 25px">ГПА в раб</th>
        <th style="text-align: center; position: sticky; top: 25px">ГПА в рез</th>
        <th style="text-align: center; position: sticky; top: 25px">ГПА в рем</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data_to_report as $row)
        <tr>
            @if($row['hours']<10)
                <td>{{'0'.$row['hours'].':00'}}</td>
            @else
                <td>{{$row['hours'].':00'}}</td>
            @endif
            <td>{{$row['in_gas']}}</td>
            <td>{{$row['out_gas']}}</td>
            <td>{{$row['skv_job']}}</td>
            <td>{{$row['skv_res']}}</td>
            <td>{{$row['skv_rem']}}</td>
            <td>{{$row['t_in']}}</td>
            <td>{{$row['t_out']}}</td>
            <td>{{$row['p_in']}}</td>
            <td>{{$row['p_out']}}</td>
            <td>{{$row['gpa_job']}}</td>
            <td>{{$row['gpa_res']}}</td>
            <td>{{$row['gpa_rem']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
