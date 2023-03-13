<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\DzMasdu;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\LogSmena;
use App\Models\Min_params;
use App\Models\Ppr_table;
use App\Models\SftpServer;
use App\Models\TableObj;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Livewire\str;

class DZController extends Controller
{
    public function create_xml_dz($name, $dz, $fact, $razn){
        try {
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content = $content.'<BusinessMessage>';
            $content = $content.'   <NameBranch>'.$name.'</NameBranch>';
            $content = $content.'   <NewExercise>'.$dz.'</NewExercise>';
            $content = $content.'   <FactMeaning>'.$fact.'</FactMeaning>';
            $content = $content.'   <Deviation>'.$razn.'</Deviation>';
            $content = $content.'</BusinessMessage>';
            try {
                $setting_sftp = SftpServer::where('type', '=', 'osnovnoi')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'].'import/',
                ]);
                $disk->put('new_dz_'.date('Y_m_d_H_i_s_').'.xml', $content, 'public');
            } catch (\Throwable $e) {
                $setting_sftp = SftpServer::where('type', '=', 'reserv')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'].'import/',
                ]);
                $disk->put('new_dz_'.date('Y_m_d_H_i_s_').'.xml', $content, 'public');
            }

//            $disk = Storage::build([
//                'driver' => 'sftp',
//                'host' => '172.16.205.139',
//                'username' => 'horizont',
//                'password' => 'demodemo',
//                'visibility' => 'public',
//                'permPublic' => 0777, /// <- this one did the trick
//                'root' => '/usr/PROZESS/horizont/var/cc/dj/import/',
//            ]);
//            $disk->put('new_dz_'.date('Y_m_d_H_i_s_').'.xml', $content, 'public');
            return 'ok';
        }catch (\Throwable $e){
            return $e;
        }
    }
    public function generator(){
        return view('web.reports.generator');
    }
    public function dashboard(){
        return view('web.reports.dashboard');
    }
    public function get_journal_dz(){
        $data = DzMasdu::orderByDesc('create')->get();
        return $data;
    }
    public function journal_dz(){
        $new_log  = (new MainTableController)->create_log_record('Открыл журнал диспетчерских заданий');
        if (UserAuth::orderbyDesc('id')->where('ip', '=', \request()->ip())->first()->level == 'cdp'){
            return view('web.journal_dz_cdp');
        }else{
            return view('web.journal_dz_rdp');
        }
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
        try {
            $log_smena = LogSmena::orderbydesc('id')->where('level', '=', UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->level)->first();
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
                $to_view['text'] = 'Принять смену?';
                $to_view['commit_smena'] = true;    //принять смену можно
            }
            return $to_view;
        }catch (\Throwable $e){
            $to_view['text'] = 'Вы не авторизованы!';
            $to_view['commit_smena'] = false;    //принять смену можно
            return $to_view;
        }
    }

    public function confirm_smena(){
        $name_active_user = UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->username;
        LogSmena::create(['name_user'=>$name_active_user,
            'start_smena'=>date('Y-m-d H:i:s'),
            'level'=>UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->level]);
        $new_log  = (new MainTableController)->create_log_record('Принял смену');
    }
    public function pass_smena(){
        LogSmena::where('name_user', '=', UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->username)
            ->where('level', '=', UserAuth::orderbydesc('id')->where('ip', '=', \request()->ip())->first()->level)
            ->orderbydesc('start_smena')->first()->update(['stop_smena'=>date('Y-m-d H:i:s')]);
        $new_log  = (new MainTableController)->create_log_record('Сдал смену');
    }

    public function get_graph_history($hfrpok, $date_start, $date_stop, $type){
        try {
            $one_hfrpok = explode(" ", $hfrpok);
        }catch (\Throwable $e){
            $one_hfrpok[0] = $hfrpok;
        }
        for ($i=0; $i<count($one_hfrpok); $i++){
            if ($type == 'sut'){
                $date_start = date('Y-m-01 10:00', strtotime($date_start));
                $date_stop = date('Y-m-t 10:00', strtotime($date_stop));
            }else{
                $date_start = date('Y-m-d 10:00', strtotime($date_start));
                $date_stop = date('Y-m-d 10:00', strtotime($date_stop. ' +1 day'));
            }
            $hour_data = Hour_params::where('hfrpok_id', '=', $one_hfrpok[$i])->select('timestamp', 'val')->wherebetween('timestamp', [$date_start, $date_stop]);
            $minute_data = Min_params::where('hfrpok_id', '=', $one_hfrpok[$i])->select('timestamp', 'val')->wherebetween('timestamp', [$date_start, $date_stop])->union($hour_data)
                ->orderby('timestamp')->get();
            $data_to_graph['xaxis'][$i] = [];
            $data_to_graph['data'][$i] = [];
            $this_param = TableObj::where('hfrpok', '=', $one_hfrpok[$i])->first()->toArray();
            $data_to_graph['statick_tr'][$i] = $this_param['namepar1'].'. '.$this_param['shortname'];
            foreach ($minute_data as $row){
                array_push($data_to_graph['xaxis'][$i], strtotime($row->timestamp.' +4 hours')*1000);
                array_push($data_to_graph['data'][$i], $row->val);
            }
        }
        return $data_to_graph;
    }

    public function log_smena(){
        return view('web.journal_log_smena');
    }
    public function get_journal_log_smena(){
        $level = UserAuth::orderbyDesc('id')->where('ip', '=', \request()->ip())->first()->level;
        if ($level == 'cdp'){
            return LogSmena::orderby('start_smena')->join('app_info.level_info', 'log_smena.level', '=', 'level_info.short_name')->get();
        }else{
            return LogSmena::orderby('start_smena')->join('app_info.level_info', 'log_smena.level', '=', 'level_info.short_name')->where('level', '=', $level)->get();
        }
    }
}

?>
