<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\DzMasdu;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\LogSmena;
use App\Models\Ppr_table;
use App\Models\SftpServer;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Livewire\str;

class DZController extends Controller
{
    public function get_journal_dz(){
        $data = DzMasdu::orderByDesc('create')->get();
        return $data;
    }
    public function journal_dz(){
        $new_log  = (new MainTableController)->create_log_record('Открыл журнал диспетчерских заданий');
        return view('web.journal_dz');
    }
    public function save_comment_dz($id, $text){
        DzMasdu::where('id', '=', $id)->update(['comment'=>$text]);
    }
    public function confirm_dz($id){
        try {
            $user = UserAuth::where('ip', '=', \request()->ip())->orderbydesc('id')->first()->username;
        }catch (\Throwable $e){
            $user = false;
        }
        if ($user){
            $text = 'Принял '.$user.' в '.date('Y-m-d H:i:s');
        }else{
            $text = 'Принято в '.date('Y-m-d H:i:s');
        }
            DzMasdu::where('id', '=', $id)->update(['check'=>true, 'info'=>$text]);
    }

    public function check_smena(){
        $log_smena = LogSmena::orderbydesc('id')->first();
        $name_active_user = UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->username;
        if ($log_smena){
            if ($log_smena->name_user == $name_active_user && $log_smena->stop_smena){   //если текущий пользователь сдал смену
                $to_view['text'] = 'Смена вами сдана!';
                $to_view['commit_smena'] = false;    //принять смену нельзя
            }elseif ($log_smena->name_user == $name_active_user && !$log_smena->stop_smena){  //если текущий пользователь не сдал смену
                $to_view['commit_smena'] = false;    //принять смену нельзя
            }elseif ($log_smena->name_user != $name_active_user && $log_smena->stop_smena){    //если зашел другой пользователь, когда смена была сдана предыдущим
                $to_view['text'] = 'Принять смену?';
                $to_view['commit_smena'] = true;    //принять смену можно
            }elseif ($log_smena->name_user != $name_active_user && !$log_smena->stop_smena){   //если зашел другой пользователь, когда смена не была сдана предыдущим
                $to_view['text'] = 'Смена еще не сдана!';
                $to_view['commit_smena'] = false;    //принять смену нельзя
            }
        }else{
            LogSmena::create(
                ['name_user'=>$name_active_user,
                'start_smena'=>date('Y-m-d H:i:s')]);
            $to_view['commit_smena'] = false;    //принять смену нельзя
        }
        return $to_view;
    }

    public function confirm_smena(){
        $name_active_user = UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->username;
        LogSmena::create(['name_user'=>$name_active_user,
            'start_smena'=>date('Y-m-d H:i:s')]);
        $new_log  = (new MainTableController)->create_log_record('Принял смену');
    }
    public function pass_smena(){
        LogSmena::orderbydesc('id')->first()->update(['stop_smena'=>date('Y-m-d H:i:s')]);
        $new_log  = (new MainTableController)->create_log_record('Сдал смену');
    }


}

?>
