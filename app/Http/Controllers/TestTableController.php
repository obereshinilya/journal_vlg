<?php

namespace App\Http\Controllers;


use App\Models\Hour_params;
use App\Models\PlanBalans;
use App\Models\TableObj;
use App\Models\ToAlpha;
use Faker\Core\Number;
use Illuminate\Http\Request;use Illuminate\Support\Facades\DB;

class TestTableController extends Controller
{
    public function settings(){
        $new_log  = (new MainTableController)->create_log_record('Посмотрел настройки сигналов');
        $data = TableObj::where('inout', '!=', '!')->orderby('id')->get();
        return view('web.signal_settings', compact('data'));
    }

    public function signal_settings_store(Request $request){
//        try {
            $data = $request->all();
//            $table_obj = TableObj::where('inout', '!=', '!')->where('hfrpok', '!=', $data['hfrpok'])->get();
//            $key = array_keys($data);
//            foreach ($table_obj as $row){
//                foreach ($key as $param){
//                    if ($param != 'hfrpok' && $param != 'shortname' && $param != 'min_param' && $param != 'hour_param' && $param != 'sut_param'){
//                        if ($row[$param] == $data[$param] && $data[$param] != ''){
//                            return ['name_param'=>$row['namepar1'], 'pole'=>$param];
//                        }
//                    }
//
//                }
//            }
//        }catch (\Throwable $e){
//            return $e;
//        }

        TableObj::where('hfrpok', '=', $data['hfrpok'])->first()
            ->update($data);
        $name_signal = TableObj::where('hfrpok', '=', $data['hfrpok'])->first()->namepar1;
        $new_log  = (new MainTableController)->create_log_record('Обновил сигнал '. $name_signal);

    }

    public function create(){
        return view('web.signal_create');
    }

    public function store_object(Request $request){
        $data = $request->all();
        $level = TableObj::where('id', '=', $data['parentId'])->select('level')->first();
        $level = $level['level'] +1;
        TableObj::create(['parentId'=>$data['parentId'],'namepar1'=>$data['namepar1'],'inout'=>'!', 'level'=>$level ]);
        $new_log  = (new MainTableController)->create_log_record('Создал объект '. $data['namepar1']);
        return 'ok';
    }

    public function store_signal(Request $request){
        try {
            $data = $request->all();
            $level = TableObj::where('id', '=', $data['parentId'])->select('level')->first();
            $level = $level['level'] +1;
            $to_table = ['parentId'=>$data['parentId'], 'namepar1'=>$data['name_new_obj'], 'level'=>$level,
                'inout'=>'ВХОД', 'name_str'=>$data['shortname'], 'shortname'=>$data['shortname'], 'min_param'=>$data['ojd_rv'],
                'hour_param'=>$data['ojd_hour'], 'sut_param'=>$data['ojd_day'], 'tag_name'=>$data['tagname'],
                'guid_masdu_day'=>$data['masdu_day'], 'guid_masdu_hours'=>$data['masdu_hour'], 'guid_masdu_5min'=>$data['masdu_rv'],
            ];
            TableObj::create($to_table);
            $id = TableObj::orderbydesc('id')->select('id')->first();
            TableObj::where('id', '=', $id['id'])->first()->update(['hfrpok'=>$id['id']]);
            $new_log  = (new MainTableController)->create_log_record('Создал сигнал '. $data['name_new_obj']);

            return 'ok';
        } catch (\Throwable $e){
            return $e;
        }
    }

    public function record_plan(){
        $value = PlanBalans::orderbydesc('month')->first();
        $value = $value->toArray();
        $value = $value['plan_month'];
        ToAlpha::where('name_param', '=', 'month_plan')->first()->update(['value'=>$value]);
        ToAlpha::where('name_param', '=', 'day_plan')->first()->update(['value'=>round($value/30)]);
    }


}

?>
