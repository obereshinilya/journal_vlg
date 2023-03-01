<?php

namespace App\Http\Controllers;


use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\Ppr_table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Livewire\str;

class PPRController extends Controller
{
    public function open_ppr(){
        return view('web.reports.open_ppr');
    }
    public function get_ppr($year){
        $new_log  = (new MainTableController)->create_log_record('Открыл график проведения ППР за '.$year.'г.');
        return Ppr_table::orderbyDesc('plan_begin')->where('plan_begin', '>=', date($year.'-01-01 00:00:01'))->
        where('plan_begin', '<=', date($year.'-12-31 23:59:59'))->get()->toArray();
    }
    public function delete_record_ppr($id){
        $row = Ppr_table::where('id', '=', $id)->first();
        $new_log  = (new MainTableController)->create_log_record('Удалил запись ППР за '.$row->plan_begin);
        $row->delete();
    }
    public function update_record_ppr(Request $request, $id){
        $row = Ppr_table::where('id', '=', $id)->first();
        try {
            if ($request->fact_begin != ''){
                $year = date('Y', strtotime($request->fact_begin));
                $rows_from_table = Ppr_table::orderbyDesc('plan_begin')->where('plan_begin', '>=', date($year.'-01-01 00:00:01'))->
                where('plan_begin', '<=', date($year.'-12-31 23:59:59'))->where('object', '=', $request->object)->get()->toArray();
                foreach ($rows_from_table as $row_from_table){
                    if ($row_from_table['id'] != $id){
                        if ($row_from_table['fact_begin']){ //если заполнен факт

                            if (strtotime($request->fact_begin) > strtotime($row_from_table['fact_begin']) && strtotime($request->fact_begin) < strtotime($row_from_table['fact_end'])){  //если начало между началом и концом др ППР
                                return ['Про'=>0, 'вал'=>0];
                            } elseif (strtotime($request->fact_end) > strtotime($row_from_table['fact_begin']) && strtotime($request->fact_end) < strtotime($row_from_table['fact_end'])){  //если конец между началом и концом др ППР
                                return ['Про'=>0, 'вал'=>0];
                            }elseif (strtotime($request->fact_end) > strtotime($row_from_table['fact_end']) && strtotime($request->fact_begin) < strtotime($row_from_table['fact_begin'])){
                                return ['Про'=>0, 'вал'=>0];
                            }
                        }else{
                            if (strtotime($request->fact_begin) > strtotime($row_from_table['plan_begin']) && strtotime($request->fact_begin) < strtotime($row_from_table['plan_end'])){  //если начало между началом и концом др ППР
                                return ['Про'=>0, 'вал'=>0];
                            } elseif (strtotime($request->fact_end) > strtotime($row_from_table['plan_begin']) && strtotime($request->fact_end) < strtotime($row_from_table['plan_end'])){  //если конец между началом и концом др ППР
                                return ['Про'=>0, 'вал'=>0];
                            }elseif (strtotime($request->fact_end) > strtotime($row_from_table['plan_end']) && strtotime($request->fact_begin) < strtotime($row_from_table['plan_begin'])){
                                return ['Про'=>0, 'вал'=>0];
                            }
                        }
                    }
                }
            }
            $new_log  = (new MainTableController)->create_log_record('Изменил запись ППР за '.date('Y-m-d H:i', strtotime($request->plan_begin)));
            $row->update($request->all());
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function create_record_ppr(Request $request){
        try {
            $year = date('Y', strtotime($request->plan_begin));
            $rows_from_table = Ppr_table::orderbyDesc('plan_begin')->where('plan_begin', '>=', date($year.'-01-01 00:00:01'))->
            where('plan_begin', '<=', date($year.'-12-31 23:59:59'))->where('object', '=', $request->object)->get()->toArray();
            foreach ($rows_from_table as $row){
                if ($row['fact_begin']){ //если заполнен факт
                    if (strtotime($request->plan_begin) > strtotime($row['fact_begin']) && strtotime($request->plan_begin) < strtotime($row['fact_end'])){  //если начало между началом и концом др ППР
                        return ['Про'=>0, 'вал'=>0];
                    } elseif (strtotime($request->plan_end) > strtotime($row['fact_begin']) && strtotime($request->plan_end) < strtotime($row['fact_end'])){  //если конец между началом и концом др ППР
                        return ['Про'=>0, 'вал'=>0];
                    }elseif (strtotime($request->plan_end) > strtotime($row['fact_end']) && strtotime($request->plan_begin) < strtotime($row['fact_begin'])){
                        return ['Про'=>0, 'вал'=>0];
                    }
                }else{
                    if (strtotime($request->plan_begin) > strtotime($row['plan_begin']) && strtotime($request->plan_begin) < strtotime($row['plan_end'])){  //если начало между началом и концом др ППР
                        return ['Про'=>0, 'вал'=>0];
                    } elseif (strtotime($request->plan_end) > strtotime($row['plan_begin']) && strtotime($request->plan_end) < strtotime($row['plan_end'])){  //если конец между началом и концом др ППР
                        return ['Про'=>0, 'вал'=>0];
                    }elseif (strtotime($request->plan_end) > strtotime($row['plan_end']) && strtotime($request->plan_begin) < strtotime($row['plan_begin'])){
                        return ['Про'=>0, 'вал'=>0];
                    }
                }
            }
        }catch (\Throwable $e){
            return $e;
        }
        $new_log  = (new MainTableController)->create_log_record('Добавил запись ППР');
        Ppr_table::create($request->all());
        return true;
    }

    public function print_ppr($year){
        return view('web.pdf_form.pdf_ppr', compact('year'));
    }





}

?>
