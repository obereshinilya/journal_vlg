<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\DzMasdu;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
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


}

?>
