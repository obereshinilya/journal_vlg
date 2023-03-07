<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\TableObj;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class HoursController extends Controller
{

    public function get_hour_param($date)
    {
        $all_param_hour = TableObj::where('hour_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->get();
        $array_hfrpok = [];
        for ($j = 1; $j < 25; $j++) {
            $zero_array[$j] = ['id' => false];
        }
        $i = 0;
        foreach ($all_param_hour as $row) {
            array_push($array_hfrpok, $row->hfrpok);
            $result[$i]['hfrpok'] = $row->hfrpok;
            $result[$i]['namepar1'] = $row->namepar1;
            $result[$i]['shortname'] = $row->shortname;
            $result[$i] += $zero_array;
            $i++;
        }
        $disp_date_time = date('Y-m-d 11:00', strtotime($date));
        $data = Hour_params::wherein('hfrpok_id', $array_hfrpok)->wherebetween('timestamp', [$disp_date_time,
            date('Y-m-d H:i', strtotime($disp_date_time . '+1439 minutes'))])->
        orderbydesc('hfrpok_id')->get();
        foreach ($data as $row) {
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = (int)date('H', strtotime($row->timestamp . '- 8 hours'));
            if ($j == 0)
                $j = 24;
            $result[$k][$j] = $row->toArray();
            $result[$k]['charts'] = true;
        }
        return $result;
    }

    public function get_min_param($date, $hour)
    {
        if ($hour < 10) {
            $hour = '0' . $hour;
        }
        $date_start = $date . ' ' . date("$hour:05");
        $date_stop = date('Y-m-d H:59', strtotime($date_start));
        $data = TableObj::where('hour_param', '=', true)->select('hfrpok')->get();
        $i = 0;
        $array_hfrpok = [];
        $zero_array = [null, null, null, null, null, null, null, null, null, null, null];
        foreach ($data as $row) {
            array_push($array_hfrpok, $row->hfrpok);
            $result[$i] = $zero_array;
            $i++;
        }
        $buff_data = Min_params::wherein('hfrpok_id', $array_hfrpok)
            ->whereBetween('timestamp', [date('Y-m-d H:i', strtotime($date_start)),
                date('Y-m-d H:i', strtotime($date_stop))])->
            orderbydesc('hfrpok_id')->get();
        foreach ($buff_data as $row) {
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = floor(((int)date('i', strtotime($row->timestamp))) / 5);
            $result[$k][$j] = $row->val;
        }
        return $result;
    }

    public function print_hour($date, $parent, $search)
    {
        return view('web.pdf_form.pdf_hour_param', compact('date', 'parent', 'search'));
    }

    public function print_hour_area($date, \Illuminate\Http\Request $request)
    {
        try {
            $data = $request->all();
            return view('web.pdf_form.pdf_hour_param_area', compact('date',  'data'));
        }catch (\Throwable $e){
            return $e;
        }
    }

}

?>
