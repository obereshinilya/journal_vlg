<?php

namespace App\Http\Controllers;


use App\Exports\Hour_paramsExport;
use App\Exports\HourExport;
use App\Exports\HourSvodkaExport;
use App\Exports\OperSKV_Export;
use App\Exports\SutExport;
use App\Models\Hour_params;
use App\Models\OperSkv;
use App\Models\Sut_params;
use App\Models\SvodniyReport;
use App\Models\TableObj;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//use Maatwebsite\Excel\Excel;
use function Livewire\str;
use Excel;

class ExcelController extends Controller
{
    public function excel_svodniy($date){
        $start_day = date('Y-m-d 09:00:00', strtotime($date));
        $param_hfrpoks = [];
        $config = SvodniyReport::where('config', '=', true)->select('in_gas', 'out_gas', 'skv_job', 'skv_res', 'skv_rem', 'gpa_job', 'gpa_res', 'gpa_rem', 't_in', 't_out', 'p_in', 'p_out')->first()->toArray();
        $keys = array_keys($config);
        for($i=0; $i<count($keys); $i++){
            array_push($param_hfrpoks, (int) $config[$keys[$i]]);
        }
        $from_hour_param = Hour_params::wherebetween('timestamp', [$start_day, date('Y-m-d H:i:s', strtotime($start_day. '+ 23 hours 59 minutes'))])->wherein('hfrpok_id', $param_hfrpoks)
            ->select('id', 'val', 'hfrpok_id', 'timestamp', 'xml_create')->get();
        $data_to_report = [];
        for ($j=0; $j<24; $j++){
            foreach ($keys as $key){
                $data_to_report[$j][$key] = '...';
            }
            $buff_data = $from_hour_param->wherebetween('timestamp', [date('Y-m-d H:i:s', strtotime($start_day. '+ '.$j.'hours')), date('Y-m-d H:i:s', strtotime($start_day. '+ '.($j+1).'hours'))])->toArray();
            foreach ($buff_data as $row){
                $param_name = array_search($row['hfrpok_id'], $config);
                $data_to_report[$j][$param_name] = $row['val'];
            }
        }
        $title = 'Часовая сводка за '.$date;
        $patch = 'Hour_svodka_'.date('Y_m_d').'.xlsx';
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new HourSvodkaExport($title, $data_to_report), $patch);
    }

    public function excel_hour($date, $parent, $search){
        ///родители
        if ($parent != 'undefined'){
            $data = TableObj::select('id', 'parentId', 'inout')->get();
            $data_parent = TableObj::select('id', 'parentId', 'inout')->orderBy('id')->where('parentId', '=', $parent)->get();
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
            if ($search != 'false'){
                $all_param_hour = TableObj::where('hour_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->wherenotin('hfrpok', $all_child)
                    ->whereRaw('LOWER(namepar1) LIKE ? ',['%'.$search.'%'])->get();
            }else{
                $all_param_hour = TableObj::where('hour_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->wherenotin('hfrpok', $all_child)->get();
            }
        }else{
            if ($search != 'false'){
                $all_param_hour = TableObj::where('hour_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->whereRaw('LOWER(namepar1) LIKE ? ',['%'.$search.'%'])->get();
            }else{
                $all_param_hour = TableObj::where('hour_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->get();
            }
        }
        $array_hfrpok = [];
        for($j=1; $j<25;$j++){
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
        $disp_date_time = date('Y-m-d 11:00', strtotime($date));
        $data = Hour_params::wherein('hfrpok_id', $array_hfrpok)->wherebetween('timestamp', [$disp_date_time,
            date('Y-m-d H:i', strtotime($disp_date_time. '+1439 minutes'))])->
        orderbydesc('hfrpok_id')->get();
        foreach ($data as $row){
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = (int) date('H', strtotime($row->timestamp.'- 8 hours'));
            if ($j == 0)
                $j =24;
            $result[$k][$j] = $row->toArray();
            $result[$k]['charts'] = true;
        }
        $data = $result;
        $title = 'Часовые показатели за '.$date;
        $patch = 'Hour_'.date('Y_m_d').'.xlsx';
//        return  view('web.excel.excel_hour_params', compact('title', 'data', 'patch'));
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new HourExport($title, $data), $patch);

    }


    public function excel_sut($date, $parent, $search){
        ///родители
        if ($parent != 'undefined'){
            $data = TableObj::select('id', 'parentId', 'inout')->get();
            $data_parent = TableObj::select('id', 'parentId', 'inout')->orderBy('id')->where('parentId', '=', $parent)->get();
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
            if ($search != 'false'){
                $all_param_hour = TableObj::where('sut_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->wherenotin('hfrpok', $all_child)
                    ->whereRaw('LOWER(namepar1) LIKE ? ',['%'.$search.'%'])->get();
            }else{
                $all_param_hour = TableObj::where('sut_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->wherenotin('hfrpok', $all_child)->get();
            }
        }else{
            if ($search != 'false'){
                $all_param_hour = TableObj::where('sut_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->whereRaw('LOWER(namepar1) LIKE ? ',['%'.$search.'%'])->get();
            }else{
                $all_param_hour = TableObj::where('sut_param', '=', true)->select('hfrpok', 'namepar1', 'shortname')->get();
            }
        }

        $array_hfrpok = [];
        for($j=1; $j<=cal_days_in_month(CAL_GREGORIAN, (int)date('m', strtotime($date)), (int)date('Y', strtotime($date)));$j++){
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
        wherebetween('timestamp', [date('Y-m-01', strtotime($date)),
            date('Y-m-t', strtotime($date))])->get();
        foreach ($data as $row){
            $k = array_search((int)$row->hfrpok_id, $array_hfrpok);
            $j = (int) date('d', strtotime($row->timestamp));
            $result[$k][$j] = $row->toArray();
        }
        $data = $result;
        $title = 'Суточные показатели за '.$date;
        $patch = 'Sut_'.date('Y_m_d').'.xlsx';
        $num_days = cal_days_in_month(CAL_GREGORIAN, (int)date('m', strtotime($date)), (int)date('Y', strtotime($date)));
        $month = date('m', strtotime($date));
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new SutExport($title, $data, $num_days, $month), $patch);

    }

    public function excel_oper_skv($timestamp){
        $uphg = array(
            array('name'=>'П.Уметское ПХГ', 'th'=>true, 'short'=>'phg_id_1', 'main'=>false),
            array('name'=>'Елшанское ПХГ', 'th'=>true, 'short'=>'phg_id_2', 'main'=>true),
            array('name'=>'Тульский г-т, в т.ч. зап.л.', 'th'=>false, 'short'=>'phg_id_3', 'main'=>true),
            array('name'=>'Бобриковский г-т', 'th'=>false, 'short'=>'phg_id_4', 'main'=>true),
            array('name'=>'Степновское ПХГ', 'th'=>true, 'short'=>'phg_id_5', 'main'=>true),
            array('name'=>'Степновское 4а-б', 'th'=>false, 'short'=>'phg_id_6', 'main'=>true),
            array('name'=>'Степновское 6-6', 'th'=>false, 'short'=>'phg_id_7', 'main'=>true),
            array('name'=>'Похвостневское УПХГ', 'th'=>true, 'short'=>'phg_id_8', 'main'=>false),
            array('name'=>'Похвостневская пром.пл.', 'th'=>true, 'short'=>'phg_id_9', 'main'=>true),
            array('name'=>'Кирюшкинское ПХГ', 'th'=>false, 'short'=>'phg_id_10', 'main'=>true),
            array('name'=>'Аманское ПХГ', 'th'=>false, 'short'=>'phg_id_11', 'main'=>true),
            array('name'=>'Отрадневская пром.пл.', 'th'=>true, 'short'=>'phg_id_12', 'main'=>true),
            array('name'=>'Дмитриевское ПХГ', 'th'=>false, 'short'=>'phg_id_13', 'main'=>true),
            array('name'=>'Михайловское ПХГ', 'th'=>false, 'short'=>'phg_id_14', 'main'=>true),
            array('name'=>'Щелковское ПХГ', 'th'=>true, 'short'=>'phg_id_15', 'main'=>false),
            array('name'=>'Калужское ПХГ', 'th'=>true, 'short'=>'phg_id_16', 'main'=>false),
            array('name'=>'Касимовское УПХГ', 'th'=>true, 'short'=>'phg_id_17', 'main'=>true),
            array('name'=>'Касимовское ПХГ', 'th'=>false, 'short'=>'phg_id_18', 'main'=>true),
            array('name'=>'Увязовское ПХГ', 'th'=>false, 'short'=>'phg_id_19', 'main'=>true),
            array('name'=>'Невское ПХГ', 'th'=>true, 'short'=>'phg_id_20', 'main'=>false),
            array('name'=>'Гатчинское ПХГ', 'th'=>true, 'short'=>'phg_id_21', 'main'=>false),
            array('name'=>'Калининградское ПХГ', 'th'=>true, 'short'=>'phg_id_22', 'main'=>false),
            array('name'=>'Волгоградское УПХГ', 'th'=>true, 'short'=>'phg_id_23', 'main'=>false),
            array('name'=>'с.Ставропольское ПХГ', 'th'=>true, 'short'=>'phg_id_24', 'main'=>true),
            array('name'=>'Гор. Зеленая Свита', 'th'=>false, 'short'=>'phg_id_25', 'main'=>true),
            array('name'=>'Гор. Хадум', 'th'=>false, 'short'=>'phg_id_26', 'main'=>true),
            array('name'=>'Краснодарское ПХГ', 'th'=>true, 'short'=>'phg_id_27', 'main'=>false),
            array('name'=>'Кущевское ПХГ', 'th'=>true, 'short'=>'phg_id_28', 'main'=>false),
            array('name'=>'Канчуринское ПХГ', 'th'=>true, 'short'=>'phg_id_29', 'main'=>true),
            array('name'=>'Канчуринское ПХГ', 'th'=>false, 'short'=>'phg_id_30', 'main'=>true),
            array('name'=>'Мусинское ПХГ', 'th'=>false, 'short'=>'phg_id_31', 'main'=>true),
            array('name'=>'Пунгинское ПХГ', 'th'=>true, 'short'=>'phg_id_32', 'main'=>false),
            array('name'=>'Карашурское ПХГ', 'th'=>true, 'short'=>'phg_id_33', 'main'=>true),
            array('name'=>'Тульский г-т', 'th'=>false, 'short'=>'phg_id_34', 'main'=>true),
            array('name'=>'Бобриковский г-т', 'th'=>false, 'short'=>'phg_id_35', 'main'=>true),
            array('name'=>'Совхозное ПХГ', 'th'=>true, 'short'=>'phg_id_36', 'main'=>false),
            array('name'=>'ООО "Газпром ПХГ"', 'th'=>true, 'short'=>'phg_id_37', 'main'=>false),
        );
        $from_db = OperSkv::where('timestamp', 'like', $timestamp.'%')->get();
        $data = [];
        try {
            foreach ($from_db as $row){
                $data[$row->id_td] = $row->text;
            }
        }catch (\Throwable $e){
        }

        $title = 'Оперативное состоние скважин на '.$timestamp;
        $patch = 'Oper_skv_'.date('Y_m_d').'.xlsx';
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new OperSKV_Export($title, $uphg, $timestamp, $data), $patch);

    }



}

?>
