<?php

namespace App\Http\Controllers;

use App\Models\BalansReport;
use App\Models\DayBalans;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\MonthBalans;
use App\Models\PlanBalans;
use App\Models\Ppr_table;
use App\Models\Rezhim_gpa;
use App\Models\Rezhim_gpa_report;
use App\Models\SvodniyReport;
use App\Models\ValDobicha;
use App\Models\YearBalans;
use Faker\Core\Number;
use http\Exception\BadUrlException;
use Illuminate\Http\Request;
use DOMDocument;
use phpDocumentor\Reflection\Types\False_;
use phpDocumentor\Reflection\Types\True_;
use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\String\s;

class BalansController extends Controller
{
    public function reports(){
        $new_log  = (new MainTableController)->create_log_record('Открыл отчеты');
        return view('web.reports.REPORTS_MAIN');
    }

    public function gpa_rezhim()
    {
        $new_log  = (new MainTableController)->create_log_record('Посмотрел режим работы ГПА');
        return view('web.reports.open_gpa_rezhim');
    }

    public function get_gpa_rezhim()
    {
        $gpa_rezhim =[];
        for ($i = 11; $i<27; $i++){
            $data = Rezhim_gpa::orderbyDesc('id')->where('number_gpa', '=', $i)->first();
            if ($data){
                $gpa_rezhim[$i] = $data;
            }
        }
        return $gpa_rezhim;
    }

    public function post_gpa_rezhim(Request $request)
    {
        $data = $request->all();
        for ($i = 11; $i<17; $i++){
            try {
                $rezhim = Rezhim_gpa::orderbyDesc('id')->where('number_gpa', '=', $i)->first()->rezhim;
            } catch (\Throwable $exception){
                $rezhim = '';
            }
            if ($rezhim != $data['gpa'.$i]){
                $new_log  = (new MainTableController)->create_log_record('Изменил режим работы ГПА'.$i);
                Rezhim_gpa::create(['number_gpa'=>$i, 'rezhim'=>$data['gpa'.$i], 'timestamp'=>date('Y-m-d H:i:s')]);
            }
        }
        for ($i = 21; $i<27; $i++){
            try {
                $rezhim = Rezhim_gpa::orderbyDesc('id')->where('number_gpa', '=', $i)->first()->rezhim;
            } catch (\Throwable $exception){
                $rezhim = '';
            }
            if ($rezhim != $data['gpa'.$i]){
                $new_log  = (new MainTableController)->create_log_record('Изменил режим работы ГПА'.$i);
                Rezhim_gpa::create(['number_gpa'=>$i, 'rezhim'=>$data['gpa'.$i], 'timestamp'=>date('Y-m-d H:i:s')]);
            }
        }
    }

    public function get_gpa_rezhim_report($dks)
    {
        $new_log  = (new MainTableController)->create_log_record('Посмотрел отчет по режимам работы ГПА ДКС'.$dks);
        if ($dks == 1){
            $gkp = '2';
        }else{
            $gkp = '2В';
        }
        return view('web.reports.open_gpa_rezhim_report', compact('dks', 'gkp'));
    }
    public function get_rezhim_table()
    {
        return Rezhim_gpa::orderbydesc('id')->get()->toArray();
    }

    public function get_gpa_rezhim_report_data($date, $dks)
    {
        $present_day = date('Y-m-d', strtotime($date.' +1 day'));
        if ($dks == 1){
            $data16 = Rezhim_gpa_report::where([['date', '=', $date], ['time', '=', '16:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
            $data20 = Rezhim_gpa_report::where([['date', '=', $date], ['time', '=', '20:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
            $data00 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '00:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
            $data04 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '04:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
            $data08 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '08:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
            $data12 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '12:00'], ['number_gpa', 'like', '1%']])->orderby('number_gpa')->get();
        } else{
            $data16 = Rezhim_gpa_report::where([['date', '=', $date], ['time', '=', '16:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
            $data20 = Rezhim_gpa_report::where([['date', '=', $date], ['time', '=', '20:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
            $data00 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '00:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
            $data04 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '04:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
            $data08 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '08:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
            $data12 = Rezhim_gpa_report::where([['date', '=', $present_day], ['time', '=', '12:00'], ['number_gpa', 'like', '2%']])->orderby('number_gpa')->get();
        }
        $arr = ['data16'=>$data16,
            'data20'=>$data20,
            'data00'=>$data00,
            'data04'=>$data04,
            'data08'=>$data08,
            'data12'=>$data12,
            ];
        return $arr;
    }

    public function print_gpa_rezhim_report($date, $dks){
        $new_log  = (new MainTableController)->create_log_record('Распечатал отчет по режимам работы ГПА для ДКС'. $dks);
        if ($dks == 1){
            $gkp = '2';
        }else{
            $gkp = '2В';
        }
        return view('web.pdf_form.pdf_rezhim_dks', compact( 'dks', 'date', 'gkp'));
    }

    public function open_svodniy(){
        $config = SvodniyReport::orderbyDesc('id')->where('config', '=', true)->first();
        if(!$config || !$config->in_gas || !$config->out_gas || !$config->skv_job || !$config->skv_rem || !$config->skv_res || !$config->gpa_job || !$config->gpa_rem || !$config->gpa_res  || !$config->t_in || !$config->t_out || !$config->p_in || !$config->p_out ){
            return redirect('/svodniy_setting');
        } else{
            $new_log  = (new MainTableController)->create_log_record('Посмотрел сводный отчет');
            return view('web.reports.open_svodniy');
        }
    }

    public function get_svodniy($date){
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
                $data_to_report[$j][$key]['val'] = '...';
                $data_to_report[$j][$key]['id'] = 'false';
                $data_to_report[$j][$key]['xml_create'] = false;
                $data_to_report[$j][$key]['timestamp'] = date('Y-m-d H:45:s', strtotime($start_day. '+ '.$j.'hours'));
            }
            $buff_data = $from_hour_param->wherebetween('timestamp', [date('Y-m-d H:i:s', strtotime($start_day. '+ '.$j.'hours')), date('Y-m-d H:i:s', strtotime($start_day. '+ '.($j+1).'hours'))])->toArray();
            foreach ($buff_data as $row){
                $param_name = array_search($row['hfrpok_id'], $config);
                $data_to_report[$j][$param_name]['val'] = $row['val'];
                $data_to_report[$j][$param_name]['id'] = $row['id'];
                $data_to_report[$j][$param_name]['xml_create'] = $row['xml_create'];
            }
        }
        return $data_to_report;
    }

    public function update_param_svodniy($param_name, $timestamp, $id, $val){
        if ($id=='false'){
            $hfrpok = SvodniyReport::where('config', '=', true)->first()->$param_name;
            Hour_params::create(['hfrpok_id'=>(int)$hfrpok, 'val'=>$val, 'timestamp'=>$timestamp, 'manual'=>true]);
        }else{
            Hour_params::where('id', '=', $id)->first()->update(['val'=>$val]);
        }
    }


//    public function create_record_svodniy(){
//        $hfrpok = SvodniyReport::orderbyDesc('id')->where('config', '=', true)->first()->toArray();
//        $keys = array_keys($hfrpok);
//        foreach ($keys as $key) {
//            if ($key != 'config' & $key != 'timestamp' & $key != 'id'){
//                try {
//                    $to_table[$key] = Hour_params::orderbyDesc('id')->where('hfrpok_id', '=', stristr($hfrpok[$key], '.', true))->first()->val;
//                }catch (\Throwable $e){
//                    $to_table[$key] = 0;
//                }
//            }
//        }
//        $to_table['timestamp'] = date('Y-m-d H:i:s');
//        SvodniyReport::create($to_table);
//    }

    public function print_svodniy($date){
        $new_log  = (new MainTableController)->create_log_record('Распечатал сводный отчет за ' . $date);
        return view('web.pdf_form.pdf_svodniy', compact( 'date'));
    }

    public function svodniy_setting(){
        $new_log  = (new MainTableController)->create_log_record('Открыл настройки сводного отчета');
        $config = SvodniyReport::orderbyDesc('id')->where('config', '=', true)->first();
        if(!$config || !$config->in_gas || !$config->out_gas || !$config->skv_job || !$config->skv_rem || !$config->skv_res || !$config->gpa_job || !$config->gpa_rem || !$config->gpa_res  || !$config->t_in || !$config->t_out || !$config->p_in || !$config->p_out){
            $data = 'false';
            return view('web.reports.setting_svodniy', compact('data'));
        } else{
            $data = 'true';
            return view('web.reports.setting_svodniy', compact('data'));
        }
    }

    public function save_param_svodniy($params, $hfrpok){
        $new_log  = (new MainTableController)->create_log_record('Изменил настройки сводного отчета');
        try {
            $config = SvodniyReport::orderbyDesc('id')->where('config', '=', true)->first()->update([$params=>$hfrpok]);
            return true;
        } catch (\Throwable $e){
            $config = SvodniyReport::create(['config'=>true, $params=>$hfrpok]);
            return true;
        }
    }

    public function get_setting_svodniy(){
        $config = SvodniyReport::orderbyDesc('id')->where('config', '=', true)->first();
        return $config;
    }

/////По валовому
    public function open_val(){    //открытие формы
        $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->get();
        if(count($config) < 4){
            return redirect('/valoviy_setting');
        } else{
            $new_log  = (new MainTableController)->create_log_record('Посмотрел годовой балансовый отчет');
            return view('web.reports.open_val_year');
        }
    }
    public function save_plan_month($date, $value, $mestorozhdeniye){   //сохранение годового
        if ($mestorozhdeniye == 'yams'){
            $text = 'Ямсовейского ГКМ';
        } else{
            $text = 'Юбилейного ГКМ';
        }
        $new_log  = (new MainTableController)->create_log_record('Изменил план за '.$date.' '.$text);
        $data = PlanBalans::where('year', '=', $date)->where('yams_yub', '=', $mestorozhdeniye)->get();
        if ($data->isEmpty()){
            PlanBalans::create([
                'year'=>$date, 'plan_year'=>$value, 'yams_yub'=>$mestorozhdeniye
            ]);
        } else{
            $data->first()->update(['plan_year'=>$value]);
        }
    }

    public function get_plan($date, $type, $mesto){  //получение планов на год по месторождениям
        if ($type == 'year'){
            for ($i=1; $i<13; $i++){
                if ($i<10){
                    $j = '0'.$i;
                } else{
                    $j = $i;
                }
                if (count(PlanBalans::where('year', '=', $date.'-'.$j)->where('yams_yub', '=', $mesto)->get())){
                    $data[$i] = PlanBalans::where('year', '=', $date.'-'.$j)->where('yams_yub', '=', $mesto)->first()->plan_year;
                } else{
                    $data[$i] = 0;
                }
            }
            return $data;
        } elseif($type == 'month'){
            try {
                for ($i =0; $i<2; $i++){
                    if ($i == 0){
                        $mesto = 'yams';
                    } else{
                        $mesto = 'yub';
                    }
                    if (count(PlanBalans::where('year', '=', $date)->where('yams_yub', '=', $mesto)->get())){
                        $data[$mesto] = PlanBalans::where('year', '=', $date)->where('yams_yub', '=', $mesto)->first()->plan_year;
                    } else{
                        $data[$mesto] = 0;
                    }
                }
                return $data;
            } catch (\Throwable $e){
                return $e;
            }
        }
    }

    public function create_record_valoviy(){    //создание записей в таблицах годового месячного и суточного валовых
        $hour = (int) date('H');
        $day = (int) date('d');
        $month =(int) date('m');
        $year =(int) date('Y');
        try {
            $hfrpoks = YearBalans::where('config', '=', true)->get();
            $hfrpok_yams = (int) $hfrpoks->where('yams_yub', '=', 'fact_yams')->first()->val;
            $hfrpok_yams_out = (int) $hfrpoks->where('yams_yub', '=', 'out_yams')->first()->val;
            $hfrpok_yams_self = (int) $hfrpoks->where('yams_yub', '=', 'self_yams')->first()->val;
            $hfrpok_yams_lost = (int) $hfrpoks->where('yams_yub', '=', 'lost_yams')->first()->val;

            //Получим последний часовой
            $hour_last = Hour_params::where('timestamp', '>', date('Y-m-d H:i:s', strtotime(' -1 month')))->orderbydesc('id')->get();
            $last_yams = $hour_last->where('hfrpok_id', '=', $hfrpok_yams)->first()->val;
            $last_yams_out = $hour_last->where('hfrpok_id', '=', $hfrpok_yams_out)->first()->val;
            $last_yams_self = $hour_last->where('hfrpok_id', '=', $hfrpok_yams_self)->first()->val;
            $last_yams_lost = $hour_last->where('hfrpok_id', '=', $hfrpok_yams_lost)->first()->val;
            //запишев в часовой
            $day_bal = DayBalans::where('hour', '=', $hour)->where('day', '=', $day)->where('month', '=', $month)->where('year', '=', $year)->get();
            $day_yams = $day_bal->where('yams_yub', '=', 'fact_yams');
            $day_yams_self = $day_bal->where('yams_yub', '=', 'self_yams');
            $day_yams_lost = $day_bal->where('yams_yub', '=', 'lost_yams');
            $day_yams_out = $day_bal->where('yams_yub', '=', 'out_yams');
            if (count($day_yams) < 1){
                $data_to_day['hour'] = $hour;
                $data_to_day['day'] = $day;
                $data_to_day['month'] = $month;
                $data_to_day['year'] = $year;
                $data_to_day['yams_yub'] = 'fact_yams';
                $data_to_day['val'] = $last_yams;
                DayBalans::create($data_to_day);
            } else{
                $day_yams->first()->update([$last_yams]);
            }
            if (count($day_yams_out)  < 1){
                $data_to_day['hour'] = $hour;
                $data_to_day['day'] = $day;
                $data_to_day['month'] = $month;
                $data_to_day['year'] = $year;
                $data_to_day['yams_yub'] = 'out_yams';
                $data_to_day['val'] = $last_yams_out;
                DayBalans::create($data_to_day);
            } else{
                $day_yams_out->first()->update([$last_yams_out]);
            }
            if (count($day_yams_self)  < 1){
                $data_to_day['hour'] = $hour;
                $data_to_day['day'] = $day;
                $data_to_day['month'] = $month;
                $data_to_day['year'] = $year;
                $data_to_day['yams_yub'] = 'self_yams';
                $data_to_day['val'] = $last_yams_self;
                DayBalans::create($data_to_day);
            } else{
                $day_yams_self->first()->update([$last_yams_self]);
            }
            if (count($day_yams_lost)  < 1){
                $data_to_day['hour'] = $hour;
                $data_to_day['day'] = $day;
                $data_to_day['month'] = $month;
                $data_to_day['year'] = $year;
                $data_to_day['yams_yub'] = 'lost_yams';
                $data_to_day['val'] = $last_yams_lost;
                DayBalans::create($data_to_day);
            } else{
                $day_yams_lost->first()->update([$last_yams_lost]);
            }
            if($hour<12){
                $day = (int) date('d', strtotime('-1 days'));
                $month =(int) date('m', strtotime('-1 days'));
                $year =(int) date('Y', strtotime('-1 days'));
            }
            //посчитаем суточный
            $month_data = MonthBalans::where('day', '=', $day)->where('month', '=', $month)->where('year', '=', $year)->get();
            $month_yams = $month_data->where('yams_yub', '=', 'fact_yams');
            $month_yams_out = $month_data->where('yams_yub', '=', 'out_yams');
            $month_yams_self = $month_data->where('yams_yub', '=', 'self_yams');
            $month_yams_lost = $month_data->where('yams_yub', '=', 'lost_yams');

            if (count($month_yams)  < 1){
                MonthBalans::create(['day'=>$day, 'month'=>$month, 'year'=>$year, 'yams_yub'=>'fact_yams', 'val'=>$last_yams]);
            } else{
                $month_yams->first()->update(['val'=>$month_yams->first()->val + $last_yams]);
            }
            if (count($month_yams_out)  < 1){
                MonthBalans::create(['day'=>$day, 'month'=>$month, 'year'=>$year, 'yams_yub'=>'out_yams', 'val'=>$last_yams_out]);
            } else{
                $month_yams_out->first()->update(['val'=>$month_yams_out->first()->val + $last_yams_out]);
            }
            if (count($month_yams_self)  < 1){
                MonthBalans::create(['day'=>$day, 'month'=>$month, 'year'=>$year, 'yams_yub'=>'self_yams', 'val'=>$last_yams_self]);
            } else{
                $month_yams_self->first()->update(['val'=>$month_yams_self->first()->val + $last_yams_self]);
            }
            if (count($month_yams_lost)  < 1){
                MonthBalans::create(['day'=>$day, 'month'=>$month, 'year'=>$year, 'yams_yub'=>'lost_yams', 'val'=>$last_yams_lost]);
            } else{
                $month_yams_lost->first()->update(['val'=>$month_yams_lost->first()->val + $last_yams_lost]);
            }
            //Посчитаем годовой
            $year_bal = YearBalans::where('month', '=', $month)->where('year', '=', $year)->get();
            $year_yams = $year_bal->where('yams_yub', '=', 'fact_yams');
            $year_yams_out = $year_bal->where('yams_yub', '=', 'out_yams');
            $year_yams_self = $year_bal->where('yams_yub', '=', 'self_yams');
            $year_yams_lost = $year_bal->where('yams_yub', '=', 'lost_yams');

            if (count($year_yams)  < 1){
                YearBalans::create(['month'=>$month, 'year'=>$year, 'yams_yub'=>'fact_yams', 'val'=>$last_yams]);
            } else{
                $year_yams->first()->update(['val'=>$year_yams->first()->val + $last_yams]);
            }
            if (count($year_yams_lost)  < 1){
                YearBalans::create(['month'=>$month, 'year'=>$year, 'yams_yub'=>'lost_yams', 'val'=>$last_yams_lost]);
            } else{
                $year_yams_lost->first()->update(['val'=>$year_yams_lost->first()->val + $last_yams_lost]);
            }
            if (count($year_yams_out)  < 1){
                YearBalans::create(['month'=>$month, 'year'=>$year, 'yams_yub'=>'out_yams', 'val'=>$last_yams_out]);
            } else{
                $year_yams_out->first()->update(['val'=>$year_yams_out->first()->val + $last_yams_out]);
            }
            if (count($year_yams_self)  < 1){
                YearBalans::create(['month'=>$month, 'year'=>$year, 'yams_yub'=>'self_yams', 'val'=>$last_yams_self]);
            } else{
                $year_yams_self->first()->update(['val'=>$year_yams_self->first()->val + $last_yams_self]);
            }
        }catch (\Throwable $e){
            dd($e);
        }
    }

    public function get_val($date, $type){   //получение данных для таблиц
        if ($type == 'year'){
            $year_data = YearBalans::where('year', '=', $date)->get();
            $year_yams = $year_data->where('yams_yub', '=', 'fact_yams');
            $year_yams_lost = $year_data->where('yams_yub', '=', 'lost_yams');
            $year_yams_self = $year_data->where('yams_yub', '=', 'self_yams');
            $year_yams_out = $year_data->where('yams_yub', '=', 'out_yams');
            $pprs_yams = Ppr_table::wherebetween('plan_begin', [date($date.'-01-01 00:00:01'), date($date.'-12-31 23:59:59')])->where('object', '=', 'Волгоградское ПХГ')->orderby('plan_begin')->get()->toArray();
            for ($i =1; $i<13; $i++){
                if ($i<10)
                    $j = '0'.$i;
                else
                    $j =$i;
                $number_day = cal_days_in_month(CAL_GREGORIAN, $i, $date);
                $start_month = date($date.'-'.$j.'-01 00:00:01');
                $end_month = date($date.'-'.$j.'-'.$number_day.' 23:59:59');
                //всплывашка для ямса
                $to_tooltip_yams[$i] = '';
                foreach ($pprs_yams as $ppr_yams){
                    if ($ppr_yams['fact_begin']){
                        $start_ppr = $ppr_yams['fact_begin'];
                        $end_ppr = $ppr_yams['fact_end'];
                    }else{
                        $start_ppr = $ppr_yams['plan_begin'];
                        $end_ppr = $ppr_yams['plan_end'];
                    }
                        if ((strtotime($start_month) < strtotime($start_ppr) && strtotime($end_month) > strtotime($start_ppr)) && (strtotime($start_month) < strtotime($end_ppr) && strtotime($end_month) > strtotime($end_ppr))){   // если ппр в середине месяца
                            $to_tooltip_yams[$i] = $to_tooltip_yams[$i]. 'C '.date('Y-m-d H:i', strtotime($start_ppr)).' по '.date('Y-m-d H:i', strtotime($end_ppr)).' &#013 &#10 ';
                        }elseif ((strtotime($start_month) < strtotime($start_ppr) && strtotime($end_month) > strtotime($start_ppr)) && (strtotime($end_month) < strtotime($end_ppr))){ //если ппр начался в тек месяце а конец в след
                            $to_tooltip_yams[$i] = $to_tooltip_yams[$i].'C '.date('Y-m-d H:i', strtotime($start_ppr)).' по '.date('Y-m-d H:i', strtotime($end_month)).' &#013 &#10 ';
                        }elseif ((strtotime($start_month) > strtotime($start_ppr)) && (strtotime($start_month) < strtotime($end_ppr) && strtotime($end_month) > strtotime($end_ppr))){ //если ппр начался в пред а конец в тек
                            $to_tooltip_yams[$i] = $to_tooltip_yams[$i].'C '.date('Y-m-d H:i', strtotime($start_month)).' по '.date('Y-m-d H:i', strtotime($end_ppr)).' &#013 &#10 ';
                        }elseif (strtotime($start_month) > strtotime($start_ppr) && strtotime($end_month)<strtotime($end_ppr)){  //если начало ппр до нач месяца и конец ппр в след месяце
                            $to_tooltip_yams[$i] = $to_tooltip_yams[$i].'C '.date('Y-m-d H:i', strtotime($start_month)).' по '.date('Y-m-d H:i', strtotime($end_month)).' &#013 &#10 ';
                        }
                }

                try {
                    $data_yams[$i] = $year_yams->where('month', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams[$i] = '...';
                }
                try {
                    $data_yams_lost[$i] = $year_yams_lost->where('month', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_lost[$i] = '...';
                }
                try {
                    $data_yams_out[$i] = $year_yams_out->where('month', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_out[$i] = '...';
                }
                try {
                    $data_yams_self[$i] = $year_yams_self->where('month', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_self[$i] = '...';
                }
            }
            $plan = PlanBalans::wherebetween('year', [date($date.'-01'), date($date.'-12'), ])->get()->toArray();
            for($j =1 ; $j<13; $j++){
                if ($j<10)
                    $month = '0'.$j;
                else
                    $month = $j;
                foreach ($plan as $row){
                    if ($row['year'] == date($date.'-'.$month)){
                        $data_to_table['plan'][$row['yams_yub']][$j] = $row['plan_year'];
                    }
                }
            }
            $data_to_table['month'] = ['Месяцы', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            $data_to_table['yams_tooltip'] = $to_tooltip_yams;
            $data_to_table['yams'] = $data_yams;
            $data_to_table['yams_self'] = $data_yams_self;
            $data_to_table['yams_lost'] = $data_yams_lost;
            $data_to_table['yams_out'] = $data_yams_out;
            return $data_to_table;
        } elseif ($type == 'month'){
            $year = (int) stristr($date, '-', true);
            $month = (int) substr(stristr($date, '-'), 1, 2);
            $month_data = MonthBalans::where('year', '=', $year)->where('month', '=', $month)->get();
            $month_yams = $month_data->where('yams_yub', '=', 'fact_yams');
            $month_yams_self = $month_data->where('yams_yub', '=', 'self_yams');
            $month_yams_lost = $month_data->where('yams_yub', '=', 'lost_yams');
            $month_yams_out = $month_data->where('yams_yub', '=', 'out_yams');
            $number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($i =1; $i<=$number_day; $i++){
                try {
                    $data_yams[$i] = $month_yams->where('day', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams[$i] = '...';
                }
                try {
                    $data_yams_lost[$i] = $month_yams_lost->where('day', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_lost[$i] = '...';
                }
                try {
                    $data_yams_self[$i] = $month_yams_self->where('day', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_self[$i] = '...';
                }
                try {
                    $data_yams_out[$i] = $month_yams_out->where('day', '=', $i)->first()->val;
                } catch (\Throwable $e){
                    $data_yams_out[$i] = '...';
                }
                $job_time_po_dnyam_yams[$i] = 24*60*60;   //без ППР рабочее время в сек за день
            }
            $data_to_table['yams'] = $data_yams;
            $data_to_table['yams_self'] = $data_yams_self;
            $data_to_table['yams_lost'] = $data_yams_lost;
            $data_to_table['yams_out'] = $data_yams_out;
////Обработка плана Ямс
            try {
                $plan_month_yams = PlanBalans::where('year', '=', $date)->where('yams_yub', '=', 'yams')->first()->plan_year;
            }catch (\Throwable $e){
                $plan_month_yams = 0;
            }
            $start_month =date('Y-m-01 12:00:01', strtotime($date));
            $end_month = date('Y-m-01 11:59:59', strtotime($date.' +1 month'));
            $ppr_yams = Ppr_table::wherebetween('plan_begin', [$start_month, $end_month])->
                orwherebetween('plan_end', [$start_month, $end_month])->
                orwherebetween('fact_begin', [$start_month, $end_month])->
                orwherebetween('fact_end', [$start_month, $end_month])
                ->get()->where('object', 'Волгоградское ПХГ')->toArray();
            $unable_time = 0;  //сколько за месяц неактивна
            if (count($ppr_yams)==0){
                $buff_day = 0;
                $day_from_plan = [];
                for ($i =1; $i<=$number_day; $i++) {
                    $buff_day++;
                }
            }else{
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

            try {
                $weight_minute_in_yams = $plan_month_yams/($buff_day*24*60*60 - $unable_time);
            }catch (\Throwable $e){
                $weight_minute_in_yams = 0;
            }

            for ($i =1; $i<=$number_day; $i++){
                if (!in_array($i, $day_from_plan)){
                    $data_to_table['plan']['yams']["$i"] =$weight_minute_in_yams*$job_time_po_dnyam_yams[$i];
                }
            }
//            dd($data_to_table);
            return $data_to_table;

        } else{              ///Данные для балансового за сутки
            $year = (int) mb_substr($date, 0, 4);
            $month = (int) mb_substr($date, 5, 2);
            $day = (int) mb_substr($date, 8, 2);
            $day_data = DayBalans::where('year', '=', $year)->where('month', '=', $month)->where('day', '=', $day)->get();
            $day_data_next = DayBalans::where('year', '=', $year)->where('month', '=', (int)date('m', strtotime($date. ' +1 days')))->where('day', '=', (int)date('d', strtotime($date. ' +1 days')))->get();
            $day_yams = $day_data->where('yams_yub', '=', 'fact_yams');
            $day_yams_next = $day_data_next->where('yams_yub', '=', 'fact_yams');
            $day_yams_lost = $day_data->where('yams_yub', '=', 'lost_yams');
            $day_yams_lost_next = $day_data_next->where('yams_yub', '=', 'lost_yams');
            $day_yams_self = $day_data->where('yams_yub', '=', 'self_yams');
            $day_yams_self_next = $day_data_next->where('yams_yub', '=', 'self_yams');
            $day_yams_out = $day_data->where('yams_yub', '=', 'out_yams');
            $day_yams_out_next = $day_data_next->where('yams_yub', '=', 'out_yams');

            for ($i =0; $i<24; $i++){
                if ($i < 12){
                    try {
                        $data_yams[$i] = $day_yams->where('hour', '=', $i+12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams[$i] = '...';
                    }
                    try {
                        $data_yams_lost[$i] = $day_yams_lost->where('hour', '=', $i+12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_lost[$i] = '...';
                    }
                    try {
                        $data_yams_self[$i] = $day_yams_self->where('hour', '=', $i+12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_self[$i] = '...';
                    }
                    try {
                        $data_yams_out[$i] = $day_yams_out->where('hour', '=', $i+12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_out[$i] = '...';
                    }
                }else{
                    try {
                        $data_yams[$i] = $day_yams_next->where('hour', '=', $i-12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams[$i] = '...';
                    }
                    try {
                        $data_yams_lost[$i] = $day_yams_lost_next->where('hour', '=', $i-12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_lost[$i] = '...';
                    }
                    try {
                        $data_yams_self[$i] = $day_yams_self_next->where('hour', '=', $i-12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_self[$i] = '...';
                    }
                    try {
                        $data_yams_out[$i] = $day_yams_out_next->where('hour', '=', $i-12)->first()->val;
                    } catch (\Throwable $e){
                        $data_yams_out[$i] = '...';
                    }
                }
            }
            $data_to_table['yams'] = $data_yams;
            $data_to_table['yams_self'] = $data_yams_self;
            $data_to_table['yams_out'] = $data_yams_out;
            $data_to_table['yams_lost'] = $data_yams_lost;


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
            get()->where('object', 'Волгоградское ПХГ')->toArray();   //получили все ППР, которые цепляют месяц
            $unable_time = 0;  //сколько за месяц неактивна
            if (count($ppr_yams)==0){
                $buff_day = 0;
                $day_from_plan = [];
                for ($i =1; $i<=$number_day; $i++) {
                        $buff_day++;
                }
            }else{
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
                        $buff_day +=1;
                        $start_day = date('Y-m-'.$i.' 12:00:01', strtotime($date));
                        $end_day = date('Y-m-d H:i:s', strtotime($start_day. ' +23 hours 59 minutes'));
                        if (strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){  //если день попал целиком
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - 24*60*60; //сутки простоя в секундах
                            $unable_time += 24*60*60;
                            array_push($array_ids_ppr[$i], $row['id']); //id записи ппр, которая влияет на эти сутки
                        }elseif (strtotime($start_day) < strtotime($start_ppr) && strtotime($end_day)>strtotime($start_ppr) && strtotime($end_day) < strtotime($end_ppr)){   //если день попал второй половиной
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_day) - strtotime($start_ppr));
                            $unable_time += strtotime($end_day) - strtotime($start_ppr);
                            array_push($array_ids_ppr[$i], $row['id']); //id записи ппр, которая влияет на эти сутки
                        } elseif(strtotime($start_day) > strtotime($start_ppr) && strtotime($end_day) > strtotime($end_ppr) && strtotime($start_day) < strtotime($end_ppr)){  //если день попал второй половиной
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_ppr) - strtotime($start_day));
                            $unable_time += strtotime($end_ppr) - strtotime($start_day);
                            array_push($array_ids_ppr[$i], $row['id']); //id записи ппр, которая влияет на эти сутки
                        }elseif (strtotime($start_ppr) > strtotime($start_day) && strtotime($end_ppr) < strtotime($end_day)){   //если ППР в середине дня
                            $job_time_po_dnyam_yams[$i] = $job_time_po_dnyam_yams[$i] - (strtotime($end_ppr) - strtotime($start_ppr));
                            $unable_time += strtotime($end_ppr) - strtotime($start_ppr);
                            array_push($array_ids_ppr[$i], $row['id']); //id записи ппр, которая влияет на эти сутки
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
            return $data_to_table;
        }
    }

    public function valoviy_setting(){
        $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->get();
        if(count($config) < 4){
            $data = 'false';
            $new_log  = (new MainTableController)->create_log_record('Открыл настройки балансового отчета');
            return view('web.reports.setting_valoviy', compact('data'));
        } else{
            $data = 'true';
            $new_log  = (new MainTableController)->create_log_record('Открыл настройки балансового отчета');
            return view('web.reports.setting_valoviy', compact('data'));
        }

    }

    public function get_setting_valoviy(){
        try {
            try {   //настройка факт ямс
                $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->where('yams_yub', '=', 'fact_yams')->first();
                $data_to_table['fact_yams'] = $config->val;
            } catch (\Throwable $exception){

            }
            try {   //настройка собств ямс
                $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->where('yams_yub', '=', 'self_yams')->first();
                $data_to_table['self_yams'] = $config->val;
            } catch (\Throwable $exception){

            }
            try {   //настройка потери ямс
                $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->where('yams_yub', '=', 'lost_yams')->first();
                $data_to_table['lost_yams'] = $config->val;
            } catch (\Throwable $exception){

            }
            try {   //выход
                $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->where('yams_yub', '=', 'out_yams')->first();
                $data_to_table['out_yams'] = $config->val;
            } catch (\Throwable $exception){

            }
            return $data_to_table;
        } catch (\Throwable $e){
            return $e;
        }
    }
    public function save_param_valoviy($params, $hfrpok){
        $new_log  = (new MainTableController)->create_log_record('Изменил настройки балансового отчета');
        try {
            $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->where('yams_yub', '=', $params)->first()->update(['val'=>$hfrpok]);

        }catch (\Throwable $e){
            $config = YearBalans::create(['yams_yub'=>$params, 'val'=>$hfrpok, 'config'=>true]);
        }
    }

    public function print_val($date, $type, $mesto){
        if ($mesto=='yams'){
            $title = 'Ямсовейского ГКМ';
            $mesto = 'yams';

        }elseif ($mesto=='yub'){
            $title = 'Юбилейного ГКМ';
            $mesto = 'yub';

        }else{
            $title = 'ННГДУ';
            $mesto = 'nngdu';
        }
        if ($type == 'year'){
            $new_log  = (new MainTableController)->create_log_record('Распечатал годовой балансовый отчет за '. $date);
            return view('web.pdf_form.pdf_val_year', compact( 'date', 'title', 'mesto'));
        } elseif($type == 'month'){
            $new_log  = (new MainTableController)->create_log_record('Распечатал месячный балансовый '.$title.' отчет за '. $date);
            return view('web.pdf_form.pdf_val_month', compact( 'date', 'title', 'mesto'));
        } else{
            $new_log  = (new MainTableController)->create_log_record('Распечатал суточный балансовый отчет за '. $date);
            return view('web.pdf_form.pdf_val_day', compact( 'date', 'title', 'mesto'));
        }
    }
    public function open_val_month(){    //открытие формы
        $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->get();
        if(count($config) < 4){
            return redirect('/valoviy_setting');
        } else{
            $new_log  = (new MainTableController)->create_log_record('Посмотрел месячный балансовый отчет');
            return view('web.reports.open_val_month');
        }
    }
    public function open_val_day(){    //открытие формы
        $config = YearBalans::orderbyDesc('id')->where('config', '=', true)->get();
        if(count($config) < 4){
            return redirect('/valoviy_setting');
        } else{
            $new_log  = (new MainTableController)->create_log_record('Посмотрел суточный балансовый отчет');
            return view('web.reports.open_val_day');
        }
    }












    public function open_balans(){
        $new_log  = (new MainTableController)->create_log_record('Посмотрел данные от смежных систем');

        return view('web.reports.open_balans');
    }

    public function get_balans($date){
        $sum = 0;
        $gz_u = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))])
            ->where('guid', '=', 'd91cb427-770e-4fe9-82bb-3c853a3532de')->orderbydesc('date')->get()->toArray();
        try {
            $gz_u_sum = 0;
            foreach ($gz_u as $row){
                $gz_u_sum = $gz_u_sum + $row['value'];
            }
            $to_balans['poteri'] = $gz_u_sum;
            $sum = $sum + $to_balans['poteri'];
        } catch (\Throwable $e){
            $to_balans['poteri'] = 0;
        }
        $gz_uh = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', 'dd812389-45fe-4dc4-ac99-3d6cfd64730b')->orderbydesc('date')->get()->toArray();
        try {
            $gz_uh_sum = 0;
            foreach ($gz_uh as $row){
                $gz_uh_sum = $gz_uh_sum + $row['value'];
            }
            $to_balans['rash_val'] = $gz_uh_sum;
            $sum = $sum + $to_balans['rash_val'];
        } catch (\Throwable $e){
            $to_balans['rash_val'] = 0;
        }
        $st_org = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', '142a3b0b-0b34-4cef-9a8b-728d6629e9e1')->orderbydesc('date')->get()->toArray();
        try {
            $st_org_sum = 0;
            foreach ($st_org as $row){
                $st_org_sum = $st_org_sum + $row['value'];
            }
            $to_balans['rash_gaz'] = $st_org_sum;
            $sum = $sum + $to_balans['rash_gaz'];
        } catch (\Throwable $e){
            $to_balans['rash_gaz'] = 0;
        }
        $tov_g = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', 'fbb860e6-225d-4dd3-ac4e-02b83fb0167e')->orderbydesc('date')->get()->toArray();
        try {
            $tov_g_sum = 0;
            foreach ($tov_g as $row){
                $tov_g_sum = $tov_g_sum + $row['value'];
            }
            $to_balans['rash_sobstv'] = $tov_g_sum;
            $sum = $sum + $to_balans['rash_sobstv'];
        } catch (\Throwable $e){
            $to_balans['rash_sobstv'] = 0;
        }
        $self = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', 'be1f7e18-c0b7-42d9-b06c-48dbedabf693')->orderbydesc('date')->get()->toArray();
        try {
            $self_sum = 0;
            foreach ($self as $row){
                $self_sum = $self_sum + $row['value'];
            }
            $to_balans['stor'] = $self_sum;
            $sum = $sum + $to_balans['stor'];
        } catch (\Throwable $e){
            $to_balans['stor'] = 0;
        }
        $val = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', '2e0ccf1f-5ccf-493e-b5f1-c99cd243a22f')->orderbydesc('date')->get()->toArray();
        try {
            $val_sum = 0;
            foreach ($val as $row){
                $val_sum = $val_sum + $row['value'];
            }
            $to_balans['rash_tov'] = $val_sum;
            $sum = $sum + $to_balans['rash_tov'];
        } catch (\Throwable $e){
            $to_balans['rash_tov'] = 0;
        }
        $sum_ter = 0;
        $metanol = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', '6f219298-9b2c-48e2-a22c-5f503844f3e2')->orderbydesc('date')->get()->toArray();
        try {
            $val_sum = 0;
            foreach ($metanol as $row){
                $val_sum = $val_sum + $row['value'];
            }
            $to_balans['rash_metanol'] = $val_sum;
            $sum_ter = $sum_ter + $to_balans['rash_metanol'];
        } catch (\Throwable $e){
            $to_balans['rash_metanol'] = 0;
        }
        $teg = BalansReport::wherebetween('date',  [date('Y-m-01', strtotime($date)), date('Y-m-01', strtotime($date. ' +1 month'))])
            ->where('guid', '=', '0aa8b401-1010-4736-9581-2853a5a7d5d0')->orderbydesc('date')->get()->toArray();
        try {
            $val_sum = 0;
            foreach ($teg as $row){
                $val_sum = $val_sum + $row['value'];
            }
            $to_balans['rash_teg'] = $val_sum;
            $sum_ter = $sum_ter + $to_balans['rash_teg'];
        } catch (\Throwable $e){
            $to_balans['rash_teg'] = 0;
        }
        $to_balans['sum'] = $sum;
        $to_balans['sum_ter'] = $sum_ter;
        return $to_balans;
    }

    public function print_balans($date){
        $new_log  = (new MainTableController)->create_log_record('Распечатал данные от смежных систем за '.$date);
        return view('web.pdf_form.pdf_bal', compact( 'date'));
    }





}

?>
