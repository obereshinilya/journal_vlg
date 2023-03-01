<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\DataToAlpha;
use App\Models\DayBalans;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\MonthBalans;
use App\Models\PlanBalans;
use App\Models\Ppr_table;
use App\Models\SftpServer;
use App\Models\YearBalans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Livewire\str;

class ToAlphaController extends Controller
{
    public function update_data_to_alpha(){
        if((int)date('h')<13){
$date = date('Y-m-d', strtotime('- 1 days'));
}else{
	$date = date('Y-m-d');
}
        $year = (int) mb_substr($date, 0, 4);
        $month = (int) mb_substr($date, 5, 2);
        $day = (int) mb_substr($date, 8, 2);

        $number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
//////Обработка плана Ямс
        try {
            $plan_month_yams = PlanBalans::where('year', '=', $year.'-'.mb_substr($date, 5, 2))->where('yams_yub', '=', 'yams')->first()->plan_year;
        }catch (\Throwable $e){
            $plan_month_yams = 0;
        }
        for ($i =1; $i<=$number_day; $i++){
            $job_time_po_dnyam_yams[$i] = 24*60*60; //рабочее время без ппр за сутки
            $array_ids_ppr[$i] = [];  //для массива с id ппр, проводимыми в этот день
        }
        $start_month =date('Y-m-01 12:00:01', strtotime($date));
        $end_month = date('Y-m-01 11:59:59', strtotime($date. ' +1 month'));
        $ppr_yams = Ppr_table::wherebetween('plan_begin', [$start_month, $end_month])->
        orwherebetween('plan_end', [$start_month, $end_month])->
        orwherebetween('fact_begin', [$start_month, $end_month])->
        orwherebetween('fact_end', [$start_month, $end_month])->
        get()->where('object', 'Ямсовейское ГКМ')->toArray();   //получили все ППР, которые цепляют месяц
        $unable_time = 0;  //сколько за месяц неактивна
        if (count($ppr_yams)==0){
            $disp_zadanie = JournalSmeny::wherebetween('date', [date('Y-m-d', strtotime($start_month)), date('Y-m-t', strtotime($start_month))])->where('id_record', '=','yangkm_1')->get();
            $buff_day = 0;
            $day_from_plan = [];
            for ($i =1; $i<=$number_day; $i++) {
                if ($i< 10){
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                if (count($disp_zadanie->where('date', '=', date('Y-m-'.$k,strtotime($start_month))))){
                    array_push($day_from_plan, $i);
                    $value_day = (float) $disp_zadanie->where('date', '=', date('Y-m-'.$k, strtotime($start_month)))->first()->val;
                    $value_day = $value_day*1000;
                    $plan_month_yams -= $value_day;
                    $data_to_table['plan']['yams'][$i] = $value_day;
                }else{
                    $buff_day++;
                }
            }
        }else{
            $disp_zadanie = JournalSmeny::wherebetween('date', [date('Y-m-d', strtotime($start_month)), date('Y-m-t', strtotime($start_month))])->where('id_record', '=','yangkm_1')->get();
            foreach ($ppr_yams as $row){
                if ($row['fact_begin'] != ''){
                    $start_ppr = $row['fact_begin'];
                    $end_ppr = $row['fact_end'];
                }else{
                    $start_ppr = $row['plan_begin'];
                    $end_ppr = $row['plan_end'];
                }
                $buff_day = 0;
                $day_from_plan = [];
                for ($i =1; $i<=$number_day; $i++){
                    if ($i< 10){
                        $k = '0'.$i;
                    }else{
                        $k = $i;
                    }
                    if (count($disp_zadanie->where('date', '=', date('Y-m-'.$k,strtotime($start_month))))){
                        array_push($day_from_plan, $i);
                        $value_day = (float) $disp_zadanie->where('date', '=', date('Y-m-'.$k, strtotime($start_month)))->first()->val;
                        $value_day = $value_day*1000;
                        $plan_month_yams -= $value_day;
                        $data_to_table['plan']['yams'][$i] = $value_day;
                    }else{
                        $buff_day +=1;
                        $start_day = date('Y-m-'.$i.' 12:00:01', strtotime($date));
                        $end_day = date('Y-m-d H:i:s', strtotime($start_day. ' +23 hours 59 minutes'));
                        if (strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){  //если день попал целиком
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - 24*60*60; //сутки простоя в секундах
                            $unable_time += 24*60*60;
                        }elseif (strtotime($start_day) < strtotime($start_ppr) && strtotime($end_day)>strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){   //если день попал второй половиной
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_day) - strtotime($start_ppr));
                            $unable_time += strtotime($end_day) - strtotime($start_ppr);
                        } elseif(strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) > strtotime($end_ppr) && strtotime($start_day) < strtotime($end_ppr)){  //если день попал второй половиной
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_ppr) - strtotime($start_day));
                            $unable_time += strtotime($end_ppr) - strtotime($start_day);
                        }elseif (strtotime($start_ppr) > strtotime($start_day) && strtotime($end_ppr) < strtotime($end_day)){   //если ППР в середине дня
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_ppr) - strtotime($start_ppr));
                            $unable_time += strtotime($end_ppr) - strtotime($start_ppr);
                        }
                    }

                }
            }
        }

        try {
            $weight_minute_in_yams = $plan_month_yams/($buff_day*24*60*60 - $unable_time);   //сколько должны добывать за секунду активности
        }catch (\Throwable $e){
            $weight_minute_in_yams = 0;
        }
        for ($i =1; $i<=$number_day; $i++){
            if (!in_array($i, $day_from_plan)){
                $data_to_table['plan']['yams']["$i"] =$weight_minute_in_yams*$job_time_po_dnyam_yams[$i];
            }
        }

        for ($j =1; $j<25; $j++) {
            $prostoy[$j] = 60*60;   //задаем количество секунд в часе
        }
        if ($array_ids_ppr[$day]){    //если что-то влияет на время активности
            $rows = Ppr_table::wherein('id', $array_ids_ppr[$day])->get()->toArray();  //взяли влияющие записи ппр
            foreach ($rows as $row){
                if ($row['fact_begin'] != ''){   //также проверка на заполненность фактического времени
                    $start_ppr = $row['fact_begin'];
                    $end_ppr = $row['fact_end'];
                }else{
                    $start_ppr = $row['plan_begin'];
                    $end_ppr = $row['plan_end'];
                }
                $start_hour = date('Y-m-d 11:00:01', strtotime($date));
                $end_hour = date('Y-m-d 11:59:59', strtotime($date));
                for ($j =1; $j<25; $j++){
                    $start_hour = date('Y-m-d H:i:s', strtotime($start_hour. ' + 1 hours'));
                    $end_hour = date('Y-m-d H:i:s', strtotime($end_hour. ' + 1 hours'));
                    if (strtotime($start_hour) > strtotime($start_ppr) && strtotime($end_hour) < strtotime($end_ppr)){  //если час попал целиком
                        $prostoy[$j] = $prostoy[$j] - 60*60; //час простоя в секундах
                    }elseif (strtotime($start_hour) < strtotime($start_ppr) && strtotime($end_hour)>strtotime($start_ppr) && strtotime($end_hour) < strtotime($end_ppr)){   //если час попал второй половиной
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_hour) - strtotime($start_ppr));
                    } elseif(strtotime($start_hour) > strtotime($start_ppr) && strtotime($end_hour) > strtotime($end_ppr) && strtotime($start_hour) < strtotime($end_ppr)){  //если час попал первой половиной
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_ppr) - strtotime($start_hour));
                    }elseif (strtotime($start_ppr) > strtotime($start_hour) && strtotime($end_ppr) < strtotime($end_hour)){   //если ППР в середине часа
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_ppr) - strtotime($start_ppr));
                    }
                }
            }
            $buff_weight = 0;
            for ($j =1; $j<25; $j++) {
                $buff_weight += $prostoy[$j];
            }
            if ($buff_weight ==0){
                $buff_weight = $data_to_table['plan']['yams'][$day]/24;
                for ($j =1; $j<25; $j++) {
                    $data_to_table['plan']['yams']['hour'][$j] = $buff_weight;
                }
            }else{
                $buff_weight = $data_to_table['plan']['yams'][$day]/$buff_weight;
                for ($j =1; $j<25; $j++) {
                    $data_to_table['plan']['yams']['hour'][$j] = $buff_weight*$prostoy[$j];
                }
            }
        }else{
            for ($j =1; $j<25; $j++) {
                $data_to_table['plan']['yams']['hour'][$j] = $data_to_table['plan']['yams'][$day]/24;
            }
        }
        //////Обработка плана Юбилейке
        try {
            $plan_month_yub = PlanBalans::where('year', '=', $year.'-'.mb_substr($date, 5, 2))->where('yams_yub', '=', 'yub')->first()->plan_year;
        }catch (\Throwable $e){
            $plan_month_yub = 0;
        }
        for ($i =1; $i<=$number_day; $i++){
            $job_time_po_dnyam_yub[$i] = 24*60*60; //рабочее время без ппр
            $array_ids_ppr[$i] = [];  //для массива с id ппр, проводимыми в этот день
        }
        $start_month =date('Y-m-01 12:00:01', strtotime($date));
        $end_month = date('Y-m-01 11:59:59', strtotime($date. ' +1 month'));
        $ppr_yub = Ppr_table::wherebetween('plan_begin', [$start_month, $end_month])->
        orwherebetween('plan_end', [$start_month, $end_month])->
        orwherebetween('fact_begin', [$start_month, $end_month])->
        orwherebetween('fact_end', [$start_month, $end_month])->
        get()->where('object', 'Юбилейное ГКМ')->toArray();
        $unable_time = 0;  //сколько за месяц неактивна
        if (count($ppr_yub)==0){
            $disp_zadanie = JournalSmeny::wherebetween('date', [date('Y-m-d', strtotime($start_month)), date('Y-m-t', strtotime($start_month))])->where('id_record', '=','yub_1')->get();
            $buff_day = 0;
            $day_from_plan = [];
            for ($i =1; $i<=$number_day; $i++) {
                if ($i< 10){
                    $k = '0'.$i;
                }else{
                    $k = $i;
                }
                if (count($disp_zadanie->where('date', '=', date('Y-m-'.$k,strtotime($start_month))))){
                    array_push($day_from_plan, $i);
                    $value_day = (float) $disp_zadanie->where('date', '=', date('Y-m-'.$k, strtotime($start_month)))->first()->val;
                    $value_day = $value_day*1000;

                    $plan_month_yams -= $value_day;
                    $data_to_table['plan']['yub'][$i] = $value_day;
                }else{
                    $buff_day++;
                }
            }
        }else{
            $disp_zadanie = JournalSmeny::wherebetween('date', [date('Y-m-d', strtotime($start_month)), date('Y-m-t', strtotime($start_month))])->where('id_record', '=','yub_1')->get();
            $unable_time = 0;  //сколько за месяц неактивна
            $day_from_plan = [];
            foreach ($ppr_yub as $row){
                if ($row['fact_begin'] != ''){
                    $start_ppr = $row['fact_begin'];
                    $end_ppr = $row['fact_end'];
                }else{
                    $start_ppr = $row['plan_begin'];
                    $end_ppr = $row['plan_end'];
                }
                $buff_day = 0;
                $day_from_plan = [];
                for ($i =1; $i<=$number_day; $i++){
                    if ($i< 10){
                        $k = '0'.$i;
                    }else{
                        $k = $i;
                    }
                    if (count($disp_zadanie->where('date', '=', date('Y-m-'.$k,strtotime($start_month))))){
                        array_push($day_from_plan, $i);
                        $value_day = (float) $disp_zadanie->where('date', '=', date('Y-m-'.$k, strtotime($start_month)))->first()->val;
                        $value_day = $value_day*1000;
                        $plan_month_yub -= $value_day;
                        $data_to_table['plan']['yub'][$i] = $value_day;
                    }else{
                        $buff_day +=1;
                        $start_day = date('Y-m-'.$i.' 12:00:01', strtotime($date));
                        $end_day = date('Y-m-d H:i:s', strtotime($start_day. ' +23 hours 59 minutes'));
                        if (strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){  //если день попал целиком
                            $job_time_po_dnyam_yub[$i] = $job_time_po_dnyam_yub[$i] - 24*60*60; //сутки простоя в секундах
                            $unable_time += 24*60*60;
                        }elseif (strtotime($start_day) < strtotime($start_ppr) && strtotime($end_day)>strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){   //если день попал второй половиной
                            $job_time_po_dnyam_yub[$i] = $job_time_po_dnyam_yub[$i] - (strtotime($end_day) - strtotime($start_ppr));
                            $unable_time += strtotime($end_day) - strtotime($start_ppr);
                        } elseif(strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) > strtotime($end_ppr) && strtotime($start_day) < strtotime($end_ppr)){  //если день попал второй половиной
                            $job_time_po_dnyam_yub[$i] = $job_time_po_dnyam_yub[$i] - (strtotime($end_ppr) - strtotime($start_day));
                            $unable_time += strtotime($end_ppr) - strtotime($start_day);
                        }elseif (strtotime($start_ppr) > strtotime($start_day) && strtotime($end_ppr) < strtotime($end_day)){   //если ППР в середине дня
                            $job_time_po_dnyam_yub[$i] = $job_time_po_dnyam_yub[$i] - (strtotime($end_ppr) - strtotime($start_ppr));
                            $unable_time += strtotime($end_ppr) - strtotime($start_ppr);
                        }
                    }
                }
            }
        }
        try {
            $weight_minute_in_yub = $plan_month_yub/($buff_day*24*60*60 - $unable_time);
        }catch (\Throwable $e){
            $weight_minute_in_yub = 0;
        }
        for ($i =1; $i<=$number_day; $i++){
            if (!in_array($i, $day_from_plan)){
                $data_to_table['plan']['yub']["$i"] =$weight_minute_in_yub*$job_time_po_dnyam_yub[$i];
            }
        }
        for ($j =1; $j<25; $j++) {
            $prostoy[$j] = 60*60;
        }
        if ($array_ids_ppr[$day]){
            $rows = Ppr_table::wherein('id', $array_ids_ppr[$day])->get()->toArray();
            foreach ($rows as $row){
                if ($row['fact_begin'] != ''){
                    $start_ppr = $row['fact_begin'];
                    $end_ppr = $row['fact_end'];
                }else{
                    $start_ppr = $row['plan_begin'];
                    $end_ppr = $row['plan_end'];
                }
                $start_hour = date('Y-m-d 11:00:01', strtotime($date));
                $end_hour = date('Y-m-d 11:59:59', strtotime($date));
                for ($j =1; $j<25; $j++){
                    $start_hour = date('Y-m-d H:i:s', strtotime($start_hour. ' + 1 hours'));
                    $end_hour = date('Y-m-d H:i:s', strtotime($end_hour. ' + 1 hours'));
                    if (strtotime($start_hour) > strtotime($start_ppr) && strtotime($end_hour) < strtotime($end_ppr)){  //если час попал целиком
                        $prostoy[$j] = $prostoy[$j] - 60*60; //час простоя в секундах
                    }elseif (strtotime($start_hour) < strtotime($start_ppr) && strtotime($end_hour)>strtotime($start_ppr) && strtotime($end_hour) < strtotime($end_ppr)){   //если час попал второй половиной
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_hour) - strtotime($start_ppr));
                    } elseif(strtotime($start_hour) > strtotime($start_ppr) && strtotime($end_hour) > strtotime($end_ppr) && strtotime($start_hour) < strtotime($end_ppr)){  //если час попал первой половиной
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_ppr) - strtotime($start_hour));
                    }elseif (strtotime($start_ppr) > strtotime($start_hour) && strtotime($end_ppr) < strtotime($end_hour)){   //если ППР в середине часа
                        $prostoy[$j] = $prostoy[$j] - (strtotime($end_ppr) - strtotime($start_ppr));
                    }
                }
            }
            $buff_weight = 0;
            for ($j =1; $j<25; $j++) {
                $buff_weight += $prostoy[$j];
            }
            if ($buff_weight ==0){
                $buff_weight = $data_to_table['plan']['yub'][$day]/24;
                for ($j =1; $j<25; $j++) {
                    $data_to_table['plan']['yub']['hour'][$j] = $buff_weight;
                }
            }else{
                $buff_weight = $data_to_table['plan']['yub'][$day]/$buff_weight;
                for ($j =1; $j<25; $j++) {
                    $data_to_table['plan']['yub']['hour'][$j] = $buff_weight*$prostoy[$j];
                }
            }
        }else{
            for ($j =1; $j<25; $j++) {
                $data_to_table['plan']['yub']['hour'][$j] = $data_to_table['plan']['yub'][$day]/24;
            }
        }
        for ($j=0; $j<=23; $j++){
            if ($data_to_table['plan']['yams'][$j+1]=='...'){
                $yams_plan = 0;
            }else{
                $yams_plan = $data_to_table['plan']['yams']['hour'][$j+1];
            }
            if ($data_to_table['plan']['yub']['hour'][$j+1]=='...'){
                $yub_plan = 0;
            }else{
                $yub_plan = $data_to_table['plan']['yub']['hour'][$j+1];
            }
            $data_to_table['plan']['nngdu']['hour'][$j+1] =$yams_plan+$yub_plan;
        }
        $to_record['plan_yams'] = round($data_to_table['plan']['yams'][(int)date('d')], 3);
        $to_record['plan_yub'] = round($data_to_table['plan']['yub'][(int)date('d')], 3);
        $to_record['plan_nngdu'] = round($to_record['plan_yams'] + $to_record['plan_yub'], 3);
        $to_record['plan_yams_hour'] = round($data_to_table['plan']['yams']['hour'][(int)date('h')], 3);
        $to_record['plan_yub_hour'] = round($data_to_table['plan']['yub']['hour'][(int)date('h')], 3);
        $to_record['plan_nngdu_hour'] = round($data_to_table['plan']['nngdu']['hour'][(int)date('h')], 3);
        $data_from_astra = AstraGaz::orderbydesc('id')->first();
        if ($data_from_astra){
            $to_record['q_ks'] = $data_from_astra->q_ks;
            $to_record['p_ks'] = $data_from_astra->p_ks;
            $to_record['t_ks'] = $data_from_astra->t_ks;
            $to_record['q_lu'] = $data_from_astra->q_lu;
            $to_record['p_lu'] = $data_from_astra->p_lu;
            $to_record['t_lu'] = $data_from_astra->t_lu;
        }else{
            $to_record['q_ks'] = 0;
            $to_record['p_ks'] = 0;
            $to_record['t_ks'] = 0;
            $to_record['q_lu'] = 0;
            $to_record['p_lu'] = 0;
            $to_record['t_lu'] = 0;
        }
        if (DataToAlpha::first()){
            DataToAlpha::where('tagname', '=', 'plan_yams')->first()->update(['value'=>$to_record['plan_yams']]);
            DataToAlpha::where('tagname', '=', 'plan_yub')->first()->update(['value'=>$to_record['plan_yub']]);
            DataToAlpha::where('tagname', '=', 'plan_nngdu')->first()->update(['value'=>$to_record['plan_nngdu']]);
            DataToAlpha::where('tagname', '=', 'plan_yams_hour')->first()->update(['value'=>$to_record['plan_yams_hour']]);
            DataToAlpha::where('tagname', '=', 'plan_yub_hour')->first()->update(['value'=>$to_record['plan_yub_hour']]);
            DataToAlpha::where('tagname', '=', 'plan_nngdu_hour')->first()->update(['value'=>$to_record['plan_nngdu_hour']]);
            DataToAlpha::where('tagname', '=', 'q_ks')->first()->update(['value'=>$to_record['q_ks']]);
            DataToAlpha::where('tagname', '=', 'p_ks')->first()->update(['value'=>$to_record['p_ks']]);
            DataToAlpha::where('tagname', '=', 't_ks')->first()->update(['value'=>$to_record['t_ks']]);
            DataToAlpha::where('tagname', '=', 'q_lu')->first()->update(['value'=>$to_record['q_lu']]);
            DataToAlpha::where('tagname', '=', 'p_lu')->first()->update(['value'=>$to_record['p_lu']]);
            DataToAlpha::where('tagname', '=', 't_lu')->first()->update(['value'=>$to_record['t_lu']]);
        }else{
            DataToAlpha::create(['value'=>$to_record['plan_yams'], 'tagname'=>'plan_yams']);
            DataToAlpha::create(['value'=>$to_record['plan_yub'], 'tagname'=>'plan_yub']);
            DataToAlpha::create(['value'=>$to_record['plan_nngdu'], 'tagname'=>'plan_nngdu']);
            DataToAlpha::create(['value'=>$to_record['plan_yams_hour'], 'tagname'=>'plan_yams_hour']);
            DataToAlpha::create(['value'=>$to_record['plan_yub_hour'], 'tagname'=>'plan_yub_hour']);
            DataToAlpha::create(['value'=>$to_record['plan_nngdu_hour'], 'tagname'=>'plan_nngdu_hour']);
            DataToAlpha::create(['value'=>$to_record['q_ks'], 'tagname'=>'q_ks']);
            DataToAlpha::create(['value'=>$to_record['p_ks'], 'tagname'=>'p_ks']);
            DataToAlpha::create(['value'=>$to_record['t_ks'], 'tagname'=>'t_ks']);
            DataToAlpha::create(['value'=>$to_record['q_lu'], 'tagname'=>'q_lu']);
            DataToAlpha::create(['value'=>$to_record['p_lu'], 'tagname'=>'p_lu']);
            DataToAlpha::create(['value'=>$to_record['t_lu'], 'tagname'=>'t_lu']);
        }
    }
}
?>
