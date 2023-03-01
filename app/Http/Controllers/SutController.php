<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\Sut_params;
use App\Models\TableObj;
use Illuminate\Support\Facades\DB;

class SutController extends Controller
{

    public function get_sut_param($month)
    {
        $all_param_hour = TableObj::where('sut_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->get();
        $array_hfrpok = [];
        for($j=1; $j<=cal_days_in_month(CAL_GREGORIAN, (int)date('m', strtotime($month)), (int)date('Y', strtotime($month)));$j++){
            $zero_array[$j] = ['id'=>false];
        }
        $i=0;
        foreach ($all_param_hour as $row){
            array_push($array_hfrpok, $row->hfrpok);
            $result[$i]['hfrpok'] = $row->hfrpok;
            $result[$i]['namepar1'] = $row->namepar1;
            $result[$i]['shortname'] = $row->shortname;
            $result[$i] += $zero_array;
            $i++;
        }
        $data = Sut_params::wherein('hfrpok_id', $array_hfrpok)->
        wherebetween('timestamp', [date('Y-m-01', strtotime($month)),
            date('Y-m-t', strtotime($month))])->get();
        foreach ($data as $row){
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = (int) date('d', strtotime($row->timestamp));
            $result[$k][$j] = $row->toArray();
            $result[$k]['charts'] = true;
        }
        return $result;
    }

    public function print_sut($date, $parent, $search){
        return view('web.pdf_form.pdf_sut_param', compact('date', 'parent', 'search'));
    }



}

?>
