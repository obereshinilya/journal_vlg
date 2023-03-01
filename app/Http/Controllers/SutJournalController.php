<?php

namespace App\Http\Controllers;


use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Livewire\str;

class SutJournalController extends Controller
{
    public function copy_record(){
        $date_new = date('Y-m-d');
        $date_old = date('Y-m-d', strtotime('-1 day'));
        foreach (JournalSmeny_table::where('timestamp', '=', $date_old)->where('color_back', '=', 'rgb(211, 211, 211)')->get()->toArray() as $old_row){
           $old_row['id'] = '';
           $old_row['timestamp'] = $date_new;
           JournalSmeny_table::create($old_row);
        }
    }
    public function get_tds($date){
        $rows = JournalSmeny::where('date', '=', $date)->get()->toArray();
        return $rows;
    }
    public function save_td(Request $request){
        $new_log  = (new MainTableController)->create_log_record('Скорректировал журнал смены за '. $request['date']);
        $row = JournalSmeny::where('date', '=', $request['date'])->where('id_record', $request['id_record'])->first();
        if ($row){
            $row->update(['val'=>$request['val']]);
        }else{
            JournalSmeny::create($request->all());
        }
    }
    public function change_color_td(Request $request){
        $row = JournalSmeny::where('date', '=', $request['date'])->where('id_record', $request['id_record'])->first();
        $row->update(['color_text'=>$request['color_text']]);
        $row->update(['text_weight'=>$request['text_weight']]);
    }
    public function open_journal_smeny(){
        $new_log  = (new MainTableController)->create_log_record('Открыл журнал смены');
        return view('web.reports.open_journal_smeny');
    }

    public function save_journal_smeny(Request $request, $date){   //сохранение только отдельных строк
        $new_log  = (new MainTableController)->create_log_record('Добавил запись в журнал смены за '.$date);
        $data = $request->all();
        try {
            if (!$data['date']){
                $data['date'] = '-';
            }
            if (!$data['oborudovanie']){
                $data['oborudovanie'] = '-';
            }
            if (!$data['status']){
                $data['status'] = '-';
            }
            $data['timestamp'] = $date;
            JournalSmeny_table::create($data);
        }catch (\Throwable $e){
            return $e;
        }
    }

    public function get_insert_tabels($timestamp, $name_table){
        if ($name_table != 'all'){
            $data_to_journal[0]['rows'] = json_decode(json_encode ( JournalSmeny_table::where('name_table', '=', $name_table)->where('timestamp', '=', $timestamp)->get() ) , true);
        } else{ //если надо получить все таблицы при откр страницы
            $i=0;
            foreach (JournalSmeny_table::groupBy('name_table')->select('name_table', DB::raw('count(*) as total'))->get() as $table){
                $data_to_journal[$i]['name_table'] = $table->name_table;
                $data_to_journal[$i]['rows'] = json_decode(json_encode ( JournalSmeny_table::where('timestamp', '=', $timestamp )->where('name_table', '=', $table->name_table )->get() ) , true);
                $i++;
            }
        }
        return $data_to_journal;
    }

    public function delete_record($id_row){
        $row = JournalSmeny_table::where('id', '=', $id_row)->first();
        $new_log  = (new MainTableController)->create_log_record('Удалил запись из журнала смены за '.$row->timestamp);
        $row->delete();
    }

    public function update_record(Request $request, $id_row){
        $row = JournalSmeny_table::where('id', '=', $id_row)->first();
        $new_log  = (new MainTableController)->create_log_record('Изменил запись из журнала смены за '.$row->timestamp);
        $row->update($request->all());
    }

    public function replace_record($id_row, $date){
            try {
                $row = JournalSmeny_table::where('id', '=', $id_row)->first()->toArray();
                if (count($row) != 0){
                    unset($row['id']);
                    $row['timestamp'] = $date;
                    JournalSmeny_table::create($row);
                }else{
                    $new_date = stristr($date, '_', true);
                    $old_date = ltrim(stristr($date, '_'), '_');
                    $row = JournalSmeny::where([['id_record', '=', $id_row], ['date', '=', $old_date]])->first()->toArray();
                    unset($row['id']);
                    $row['date'] = $new_date;
                    $search_row = JournalSmeny::where([['id_record', '=', $id_row], ['date', '=', $new_date]])->first();
                    if ($search_row){
                        $search_row->update($row);
                    }else{
                        JournalSmeny::create($row);
                    }                }
            } catch (\Throwable $e){
                $new_date = stristr($date, '_', true);
                $old_date = ltrim(stristr($date, '_'), '_');
                $row = JournalSmeny::where([['id_record', '=', $id_row], ['date', '=', $old_date]])->first()->toArray();
                unset($row['id']);
                $row['date'] = $new_date;
                $search_row = JournalSmeny::where([['id_record', '=', $id_row], ['date', '=', $new_date]])->first();
                if ($search_row){
                    $search_row->update($row);
                }else{
                    JournalSmeny::create($row);
                }
            }
        $new_log  = (new MainTableController)->create_log_record('Добавил запись в журнал смены за '.$date);
    }

    public function print_journal_smeny($date){
        return view('web.pdf_form.pdf_journal_smeny', compact('date'));
    }


}

?>
