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
        <th colspan="26"><h2 class="text-muted" style="text-align: center">{{$title}}</h2></th>
    </tr>
    <tr>
        <th>Наименование параметра</th>
        <th>Ед.изм.</th>
        <th>10:00</th>
        <th>11:00</th>
        <th>12:00</th>
        <th>13:00</th>
        <th>14:00</th>
        <th>15:00</th>
        <th>16:00</th>
        <th>17:00</th>
        <th>18:00</th>
        <th>19:00</th>
        <th>20:00</th>
        <th>21:00</th>
        <th>22:00</th>
        <th>23:00</th>
        <th>00:00</th>
        <th>01:00</th>
        <th>02:00</th>
        <th>03:00</th>
        <th>04:00</th>
        <th>05:00</th>
        <th>06:00</th>
        <th>07:00</th>
        <th>08:00</th>
        <th>09:00</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$row['namepar1']}}</td>
            <td>{{$row['shortname']}}</td>
            @for($i=1; $i<=24; $i++)
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
