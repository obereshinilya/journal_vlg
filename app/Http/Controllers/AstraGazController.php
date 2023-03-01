<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\Ppr_table;
use App\Models\SftpServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Livewire\str;

class AstraGazController extends Controller
{
    public function open_astragaz(){
        return view('web.reports.open_astragaz');
    }
    public function get_astragaz(){
        $new_log  = (new MainTableController)->create_log_record('Открыл отчет записи с АстраГаз');
        return AstraGaz::orderbyDesc('id')->get();
    }
    public function remove_astragaz($id){
        $new_log  = (new MainTableController)->create_log_record('Удалил '.$id .' запись с АстраГаз');
        return AstraGaz::where('id', '=', $id)->first()->delete();
    }

    public function get_result_astragaz(){
        $setting_sftp = SftpServer::where('type', '=', 'astragaz')->first()->toArray();
        $disk = Storage::build([
            'driver' => 'sftp',
            'host' => $setting_sftp['adres_sftp'],
            'username' => $setting_sftp['user'],
            'password' => $setting_sftp['password'],
            'visibility' => 'public',
            'timeout' => '1',
            'permPublic' => 0777, /// <- this one did the trick
            'root' => $setting_sftp['path_sftp'].'from_astragaz/',
        ]);
        $text = $disk->get('result.txt');
        $text = explode(PHP_EOL, $text, 50);
        for ($i = 0; $i<count($text); $i++){
            $words[$i] = explode(" ", $text[$i]);
            $words[$i] = preg_replace("/[^0-9.]/", '', $words[$i]); //массив выражений
            $value = 0;
            for ($j=0; $j<count($words[$i]); $j++){
                if(is_numeric($words[$i][$j])){
                    $data[$i][$value] = $words[$i][$j];
                    $value++;
                }
            }
        }
        $table['date'] = date($data[0][2].'-m-'.$data[0][1].' '. $data[0][0].':00:00');
        $table['q_ks'] = $data[1][0];
        $table['p_ks'] = $data[1][1];
        $table['t_ks'] = $data[1][2];
        $table['q_lu'] = $data[2][1];
        $table['p_lu'] = $data[2][2];
        $table['t_lu'] = $data[2][3];
        if (AstraGaz::where('date', '=', $table['date'])->get()->toArray()){

        }else{
            AstraGaz::create($table);
        }
    }

    public function create_astragaz_files(){
        $hour = (int) date('H');

        $setting_sftp = SftpServer::where('type', '=', 'astragaz')->first()->toArray();
        $disk = Storage::build([
            'driver' => 'sftp',
            'host' => $setting_sftp['adres_sftp'],
            'username' => $setting_sftp['user'],
            'password' => $setting_sftp['password'],
            'visibility' => 'public',
            'timeout' => '1',
            'permPublic' => 0777, /// <- this one did the trick
            'root' => $setting_sftp['path_sftp'].'to_astragaz/',
        ]);
        ///формирование для кранов
        $contents = date('d.m.Y')." 2\n";
        $disk->put('KR.'.$hour, $contents, 'public');
        //формирование для датчиков и кс
        $data_from_hour = Hour_params::wherebetween('timestamp', [date('Y-m-d H:i', strtotime('-3 hours')), date('Y-m-d H:i')])->orderbydesc('id')->get();
        $setting_astragaz = AstraGaz_setting::get()->first()->toArray();
        $key = array_keys($setting_astragaz);
        foreach ($key as $param){
            if ($param !== 'id'){
                $data_to_file[$param] = $data_from_hour->where('hfrpok_id', '=', $setting_astragaz[$param])->first()->val;
            }
        }
        $contents = date('d.m.Y')."\n";
        $contents = $contents."   1, ".$data_to_file['q_lu1'].", ".$data_to_file['p_lu1'].", ".$data_to_file['t_lu1'].", 0.67, 7900.00\n";
        $contents = $contents."   2, ".$data_to_file['q_lu2'].", ".$data_to_file['p_lu2'].", ".$data_to_file['t_lu2']."\n";
        $contents = $contents."  11, 4X1, ".$data_to_file['q_sn'].", ".$data_to_file['p_in'].", ".$data_to_file['p_vsas'].", ".$data_to_file['p_nagn'].", ".$data_to_file['p_out'].", ".$data_to_file['t_vsas'].", ".$data_to_file['t_nagn'].", ".$data_to_file['t_avo'].", ".$data_to_file['t_vozd'].", 0, 0\n";
        $disk->put('PZG.'.$hour, $contents, 'public');
    }

    public function astragaz_setting(){
        return view('web.reports.setting_astragaz');
    }

    public function get_setting_astragaz(){
        return AstraGaz_setting::orderbyDesc('id')->get()->first();
    }
    public function save_param_astragaz($name_param, $hfrpok){
        $data_to_table[$name_param] = $hfrpok;
        return AstraGaz_setting::orderbyDesc('id')->get()->first()->update($data_to_table);
    }


}

?>
