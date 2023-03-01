<?php

namespace App\Http\Controllers;

use App\Models\ConfigXML;
use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\NewGUID;
use App\Models\Rezhim_gpa;
use App\Models\Rezhim_gpa_report;
use App\Models\Sut_params;
use Illuminate\Http\Request;
use App\Models\WellsCondition;
use App\Models\TableObj;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function check_hour_param()
    {
        $check=DB::select('SELECT * FROM public.average_param(20)');
    }
    public function check_sut_param()
    {
        $check=DB::select('SELECT * FROM public.average_param_sut(10)');
    }


    public function check_status_gpa()
    {

        $data_time = Min_params::orderbydesc('id')->where('timestamp', '>=', date('Y-m-d H:i:s', strtotime('-6 minutes')))->get();

        //прописать hfrpok статуса ГПА
        $gpa['21']['cold_res'] = 324;
        $gpa['21']['hot_res'] = 325;
        $gpa['21']['ring'] = 326;
        $gpa['21']['magistral'] = 327;
        $gpa['22']['cold_res'] = 329;
        $gpa['22']['hot_res'] = 328;
        $gpa['22']['ring'] = 331;
        $gpa['22']['magistral'] = 330;
        $gpa['23']['cold_res'] = 333;
        $gpa['23']['hot_res'] = 332;
        $gpa['23']['ring'] = 339;
        $gpa['23']['magistral'] = 334;
        $gpa['24']['cold_res'] = 336;
        $gpa['24']['hot_res'] = 335;
        $gpa['24']['ring'] = 338;
        $gpa['24']['magistral'] = 337;
        $gpa['25']['cold_res'] = 341;
        $gpa['25']['hot_res'] = 340;
        $gpa['25']['ring'] = 342;
        $gpa['25']['magistral'] = 347;
        $gpa['26']['cold_res'] = 345;
        $gpa['26']['hot_res'] = 344;
        $gpa['26']['ring'] = 346;
        $gpa['26']['magistral'] = 343;
        for ($i=21; $i<27; $i++){
            $last_status = Rezhim_gpa::where('number_gpa', '=', $i)->orderbyDesc('id')->first()->rezhim;
            if ($data_time->where('hfrpok_id', '=', $gpa[$i]['cold_res'])->first()->val > 0){
                if ($last_status != 'Холодный резерв'){
                    Rezhim_gpa::create(['rezhim'=>'Холодный резерв','number_gpa'=>$i,'timestamp'=>date('Y-m-d H:i:s')]);
                }
            }elseif($data_time->where('hfrpok_id', '=', $gpa[$i]['hot_res'])->first()->val > 0){
                if ($last_status != 'Горячий резерв') {
                    Rezhim_gpa::create(['rezhim'=>'Горячий резерв','number_gpa'=>$i,'timestamp'=>date('Y-m-d H:i:s')]);
                }
            }elseif($data_time->where('hfrpok_id', '=', $gpa[$i]['ring'])->first()->val > 0){
                if ($last_status != 'Кольцо'){
                    Rezhim_gpa::create(['rezhim'=>'Кольцо','number_gpa'=>$i,'timestamp'=>date('Y-m-d H:i:s')]);
                }
            }elseif($data_time->where('hfrpok_id', '=', $gpa[$i]['magistral'])->first()->val > 0){
                if ($last_status != 'Магистраль') {
                    Rezhim_gpa::create(['rezhim'=>'Магистраль','number_gpa'=>$i,'timestamp'=>date('Y-m-d H:i:s')]);
                }
            }else{
                if ($last_status != 'Нет режима') {
                    Rezhim_gpa::create(['rezhim'=>'Нет режима','number_gpa'=>$i,'timestamp'=>date('Y-m-d H:i:s')]);
                }
            }
        }

    }
    public function create_record_rezhim_dks()
    {
        $data = Hour_params::orderbyDesc('id')->wherebetween('timestamp', [date('Y-m-d 00:00', strtotime('-1 day')), date('Y-m-d 00:00', strtotime('+1 day'))])->get();
        for ($dks=1; $dks<3; $dks++){
            if ($dks == 1){
                for ($i = 11; $i<17; $i++){
                    $gpa['number_gpa']=$i;
                    $gpa['date']=date('Y-m-d');
                    $gpa['time']=date('H:00');
                    $rezhim = Rezhim_gpa::orderbyDesc('id')->where('number_gpa', '=', $i)->first();
                    $gpa['time_rezhim']=date('Y-m-d H:i', strtotime($rezhim->timestamp));
                    $gpa['rezhim']=$rezhim->rezhim;
                    $gpa['tvd']=$data->where('hfrpok_id', '=', 27+($i-11)*18)->first()->val;
                    $gpa['priv_tvd']=$data->where('hfrpok_id', '=', 28+($i-11)*18)->first()->val;
                    $gpa['tnd']=$data->where('hfrpok_id', '=', 29+($i-11)*18)->first()->val;
                    $gpa['Pin']=$data->where('hfrpok_id', '=', 30+($i-11)*18)->first()->val;
                    $gpa['Pout']=$data->where('hfrpok_id', '=', 31+($i-11)*18)->first()->val;
                    $gpa['Tin']=$data->where('hfrpok_id', '=', 32+($i-11)*18)->first()->val;
                    $gpa['Tout']=$data->where('hfrpok_id', '=', 33+($i-11)*18)->first()->val;
                    $gpa['Tvdv']=$data->where('hfrpok_id', '=', 34+($i-11)*18)->first()->val;
                    $gpa['Pvdv']=$data->where('hfrpok_id', '=', 35+($i-11)*18)->first()->val;
                    $gpa['Qtg']=$data->where('hfrpok_id', '=', 36+($i-11)*18)->first()->val;
                    $gpa['St_sj']=$data->where('hfrpok_id', '=', 37+($i-11)*18)->first()->val;
                    $gpa['Qcbn']=$data->where('hfrpok_id', '=', 38+($i-11)*18)->first()->val;
                    $gpa['Tvozd']=$data->where('hfrpok_id', '=', 39+($i-11)*18)->first()->val;
                    $gpa['q']='0';
                    $gpa['Pkol']=$data->where('hfrpok_id', '=', 40+($i-11)*18)->first()->val;
                    $gpa['Tpodsh']=$data->where('hfrpok_id', '=', 41+($i-11)*18)->first()->val;
                    $gpa['Tgg']=$data->where('hfrpok_id', '=', 42+($i-11)*18)->first()->val;
                    $gpa['Pbuf']=$data->where('hfrpok_id', '=', 43+($i-11)*18)->first()->val;
                    $gpa['Zapas']=$data->where('hfrpok_id', '=', 44+($i-11)*18)->first()->val;
                    $gpa['Tavo']=$data->where('hfrpok_id', '=', 237)->first()->val;
                    $gpa['mokveld_status']=$data->where('hfrpok_id', '=', 240+($i-11)*2)->first()->val;
                    $gpa['mokveld_zadanie']=$data->where('hfrpok_id', '=', 241+($i-11)*2)->first()->val;

                    Rezhim_gpa_report::create($gpa);
                }
            } else{
                for ($i = 21; $i<27; $i++){
                    $gpa['number_gpa']=$i;
                    $gpa['date']=date('Y-m-d');
                    $gpa['time']=date('H:00');
                    $rezhim = Rezhim_gpa::orderbyDesc('id')->where('number_gpa', '=', $i)->first();
                    $gpa['time_rezhim']=date('Y-m-d H:i', strtotime($rezhim->timestamp));
                    $gpa['rezhim']=$rezhim->rezhim;
                    $gpa['tvd']=$data->where('hfrpok_id', '=', 135+($i-21)*17)->first()->val;
                    $gpa['priv_tvd']=$data->where('hfrpok_id', '=', 136+($i-21)*17)->first()->val;
                    $gpa['tnd']=$data->where('hfrpok_id', '=', 137+($i-21)*17)->first()->val;
                    $gpa['Pin']=$data->where('hfrpok_id', '=', 138+($i-21)*17)->first()->val;
                    $gpa['Pout']=$data->where('hfrpok_id', '=', 139+($i-21)*17)->first()->val;
                    $gpa['Tin']=$data->where('hfrpok_id', '=', 140+($i-21)*17)->first()->val;
                    $gpa['Tout']=$data->where('hfrpok_id', '=', 141+($i-21)*17)->first()->val;
                    $gpa['Tvdv']=$data->where('hfrpok_id', '=', 142+($i-21)*17)->first()->val;
                    $gpa['Pvdv']=$data->where('hfrpok_id', '=', 143+($i-21)*17)->first()->val;
                    $gpa['Qtg']=$data->where('hfrpok_id', '=', 144+($i-21)*17)->first()->val;
                    $gpa['St_sj']=$data->where('hfrpok_id', '=', 145+($i-21)*17)->first()->val;
                    $gpa['Qcbn']=$data->where('hfrpok_id', '=', 146+($i-21)*17)->first()->val;
                    $gpa['Tvozd']=$data->where('hfrpok_id', '=', 147+($i-21)*17)->first()->val;
                    $gpa['q']='0';
                    $gpa['Pkol']=$data->where('hfrpok_id', '=', 148+($i-21)*17)->first()->val;
                    $gpa['Tpodsh']=$data->where('hfrpok_id', '=', 149+($i-21)*17)->first()->val;
                    $gpa['Tgg']=$data->where('hfrpok_id', '=', 150+($i-21)*17)->first()->val;
                    $gpa['Pbuf']='0';
                    $gpa['Zapas']=$data->where('hfrpok_id', '=', 151+($i-21)*17)->first()->val;
                    $gpa['Tavo']=$data->where('hfrpok_id', '=', 238)->first()->val;
                    $gpa['mokveld_status']=$data->where('hfrpok_id', '=', 252+($i-21)*2)->first()->val;
                    $gpa['mokveld_zadanie']=$data->where('hfrpok_id', '=', 253+($i-21)*2)->first()->val;
                    Rezhim_gpa_report::create($gpa);
                }
            }
        }
    }

    public function test($params_type)
    {

        $test_table_data = TableObj::select('hfrpok', 'id')->get();
        if ($params_type == 1){
            foreach ($test_table_data as $row){
                $data_in_table['val'] = rand(1, 100);
                $data_in_table['hfrpok_id'] = $row['id'];
                $data_in_table['timestamp'] = date('Y-m-d H:00:10');
                Hour_params::create($data_in_table);
            }
        } elseif ($params_type == 24){
            foreach ($test_table_data as $row) {
                $data_in_table['val'] = rand(1, 100);
                $data_in_table['hfrpok_id'] = $row['id'];
                $data_in_table['timestamp'] = date('Y-m-d', strtotime(" - 24 hours"));
                Sut_params::create($data_in_table);
            }
        } else{
            foreach ($test_table_data as $row) {
                $data_in_table['val'] = rand(1, 100);
                $data_in_table['hfrpok_id'] = $row['id'];
                $data_in_table['timestamp'] = date('Y-m-d H:i:s');
                Min_params::create($data_in_table);
            }
        }
    }



    public function update_guid()
    {
        $new_guid = NewGUID::where('check', 'false')->get();
        if ($new_guid){
            foreach ($new_guid as $row){
                $old_guid = $row->old_guid;
                $row_with_old_guid = TableObj::where('guid_masdu_5min', '=', $old_guid)->first();
                if ($row_with_old_guid){
                    $row_with_old_guid->update(['guid_masdu_5min'=>$row->new_guid]);
                    $row->update(['check'=>true]);
                }else{
                    $row_with_old_guid = TableObj::where('guid_masdu_hours', '=', $old_guid)->first();
                    if ($row_with_old_guid){
                    $row_with_old_guid->update(['guid_masdu_hours'=>$row->new_guid]);
                    $row->update(['check'=>true]);
                    }else{
                        $row_with_old_guid = TableObj::where('guid_masdu_day', '=', $old_guid)->first();
                        if ($row_with_old_guid){
                        $row_with_old_guid->update(['guid_masdu_day'=>$row->new_guid]);
                        $row->update(['check'=>true]);
                        }
                    }
                }
            }
        }
    }



    public function get_parent($parentId)
    {
        $data = TableObj::select('id', 'parentId', 'inout')->get();
        $data_parent = TableObj::select('id', 'parentId', 'inout')->orderBy('id')->where('parentId', '=', $parentId)->get();
        $children = [];
        foreach ($data_parent as $row){
            if ($row->inout == '!'){
                $j = $data->where('parentId', '=', $row->id);
                foreach ($j as $row1){
                    if ($row1->inout == '!') {
                        $i = $data->where('parentId', '=', $row1->id);
                        foreach ($i as $row2){
                            if ($row2->inout == '!') {
                                $i = $data->where('parentId', '=', $row2->id);
                                foreach ($i as $row3){
                                    if ($row3->inout == '!'){
                                        $k = $data->where('parentId', '=', $row3->id);
                                    }else{
                                        array_push($children, $row3->id);
                                    }
                                }
                            }else{
                                array_push($children, $row2->id);
                            }
                        }
                    } else{
                        array_push($children, $row1->id);
                    }
                }
            } else{
                array_push($children, $row->id);
            }
        }
        arsort($children);
        $all_id_child = TableObj::where('inout', '!=', '!')->select('id')->orderByDesc('id')->get();
        $all_child = [];
        foreach ($all_id_child as $row){
            array_push($all_child, $row->id);
        }
        foreach ($children as $row){
            $key = array_search($row, $all_child);
            unset($all_child[$key]);
        }
    return $all_child;
    }


    public function get_parent_name($parentId){
        $text = '';
        $test_table = TableObj::orderbydesc('id')->select('id', 'hfrpok', 'namepar1', 'inout', 'parentId', 'level')->get();
        $child = $test_table->where('hfrpok', '=', $parentId)->first();
        $level = $child->level;
        for ($i = 1; $i<$level; $i++){
            $parent = $test_table->where('id', '=', $child->parentId)->first();
            $parentName = $parent->namepar1;
            if ($i == 1){
                $text = $parentName;
            }else{
                $text = $parentName.'-'.$text;
            }
            $child = $parent;
        }
        return $text;
    }
}

?>
