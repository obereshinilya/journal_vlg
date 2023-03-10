<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\Hour_params;
use App\Models\OperSkv;
use App\Models\SftpServer;
use App\Models\UserAuth;
use Illuminate\Support\Facades\Storage;

class VlgController extends Controller
{
    public function print_oper_skv($timestamp){
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
        return view('web.pdf_form.pdf_oper_skv', compact('uphg', 'timestamp'));
    }

    public function report_oper_skv_main(){
        $date = OperSkv::groupby('timestamp')->select('timestamp')->get();
        $to_table = array();
        if (count($date)){
            foreach ($date as $row){
                $rec = OperSkv::where('timestamp', '=', $row->timestamp)->orderbydesc('edit_at')->first();
                array_push($to_table, ['timestamp'=>date('Y-m-d H:i', strtotime($row->timestamp)), 'edit_at'=>date('Y-m-d H:i', strtotime($rec->edit_at)), 'content_editable'=>$rec->content_editable]);
            }
        }else{
            array_push($to_table, ['timestamp'=>'Данные отсутствуют', 'edit_at'=>'', 'content_editable'=>'']);
        }
        if (UserAuth::orderbyDesc('id')->where('ip', '=', \request()->ip())->first()->level == 'cdp'){
            return view('web.reports.open_oper_skv_main_cdp', compact('to_table'));
        }else{
            return view('web.reports.open_oper_skv_main_rdp', compact('to_table'));
        }
    }

    public function open_oper_skv($timestamp){
        if (UserAuth::orderbyDesc('id')->where('ip', '=', \request()->ip())->first()->level == 'cdp'){
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
            return view('web.reports.open_oper_skv_cdp', compact('uphg', 'timestamp'));
        }else{
            $uphg = array(
                array('name'=>'Волгоградское УПХГ', 'th'=>true, 'short'=>'phg_id_23', 'main'=>false),
            );
            return view('web.reports.open_oper_skv_rdp', compact('uphg', 'timestamp'));
        }
    }

    public function get_data_oper_skv($timestamp){
        return OperSkv::where('timestamp', 'like', $timestamp.'%')->get();
    }

    public function save_td_oper($id, $text, $timestamp){
        $old_text = OperSkv::where('id_td', '=', $id)->where('timestamp', '=', $timestamp)->first();
        if ($old_text){
            $old_text->update(['text'=>$text, 'edit_at'=>date('Y-m-d H:i:s')]);
        }else{
            OperSkv::create(['id_td'=>$id, 'text'=>$text, 'timestamp'=>$timestamp, 'edit_at'=>date('Y-m-d H:i:s')]);
        }
    }

    public function create_record_oper_skv(){
        OperSkv::create(['id_td'=>'head', 'timestamp'=>date('Y-m-d H:i:s'), 'edit_at'=>date('Y-m-d H:i:s'), 'text'=>date('Y-m-d H:i:s')]);
    }

    public function remove_record_ope_skv($timestamp){
        OperSkv::where('timestamp', 'like', $timestamp.'%')->delete();
    }

    public function editable_record_ope_skv($bool, $timestamp){
        OperSkv::where('timestamp', 'like', $timestamp.'%')->update(['content_editable'=>$bool]);
    }



}

?>
