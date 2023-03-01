<?php

namespace App\Http\Controllers;


use App\Models\AcceptTimeParam;
use App\Models\Min_params;
use App\Models\TableObj;
use Illuminate\Support\Facades\DB;

class MinutesController extends Controller
{

    public function get_minute_param($date, $hour)     ///new
    {
        if ($hour<10){
            $hour = '0'.$hour;
        }
        $date_start = $date.' '.date("$hour:00");
        $date_stop = date('Y-m-d H:59', strtotime($date_start));
        $data = TableObj::where('min_param', '=', true)->get();
        $i = 0;
        $array_hfrpok = [];
        $zero_array = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($data as $row){
            $result[$i]['hfrpok'] = $row->hfrpok;
            array_push($array_hfrpok, $row->hfrpok);
            $result[$i]['namepar1'] = $row->namepar1;
            $result[$i]['shortname'] = $row->shortname;
            $result[$i]['min_params'] = $zero_array;
            $i++;
        }
        $buff_data = Min_params::wherein('hfrpok_id', $array_hfrpok)
            ->whereBetween('timestamp', [date('Y-m-d H:i', strtotime($date_start)),
                date('Y-m-d H:i', strtotime($date_stop))])->get();
        foreach ($buff_data as $row){
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = floor(((int) date('i', strtotime($row->timestamp)))/5);
            $result[$k]['min_params'][$j] = $row->val;
        }
        return $result;
    }

    public function accept_time_param($type, $hour_day, $date_month)
    {
        if (count(AcceptTimeParam::where('type', '=', $type)->where('hour_day', '=', $hour_day)->where('date_month', '=', $date_month)->get())>0){

        }else{
            AcceptTimeParam::create(['type'=>$type, 'hour_day'=>$hour_day, 'date_month'=>$date_month]);
            if ($type == 'day'){
                $hour_day = $hour_day+9;
                $new_log  = (new MainTableController)->create_log_record('Подтвердил достоверность часовых сигналов за '.$date_month.' за '.date('H:00', strtotime(date('00:00'). '+'.$hour_day.' hours')));
            }else{
                if ($hour_day<10){
                    $hour_day = '0'.$hour_day;
                }
                $new_log  = (new MainTableController)->create_log_record('Подтвердил достоверность суточных сигналов за '.$date_month.'-'.$hour_day);
            }
        }
    }

    public function get_accept($type, $date_month)
    {
        return AcceptTimeParam::orderbyDesc('id')->where(['type'=>$type, 'date_month'=>$date_month])->select('hour_day')->get();
    }



}

?>
