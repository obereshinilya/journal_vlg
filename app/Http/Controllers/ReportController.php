<?php

namespace App\Http\Controllers;


use App\Models\AstraGaz;
use App\Models\AstraGaz_setting;
use App\Models\Events;
use App\Models\Hour_params;
use App\Models\JournalSmeny;
use App\Models\JournalSmeny_table;
use App\Models\Min_params;
use App\Models\Ppr_table;
use App\Models\SftpServer;
use App\Models\Sut_params;
use App\Models\DzMasdu;
use App\Models\TableObj;
use App\Models\YearBalans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function Livewire\str;
class ReportController extends Controller
{
    public function save_fact_month($year, $month, $obj, $val){
        if (count(YearBalans::where('year', '=', $year)->where('month', '=', $month)->where('yams_yub', '=', $obj)->get())){
            YearBalans::where('year', '=', $year)->where('month', '=', $month)->where('yams_yub', '=', $obj)->first()->update(['val'=>$val]);
        }else{
            YearBalans::create(['val'=>$val, 'yams_yub'=>$obj, 'year'=>$year, 'month'=>$month]);
        }
    }
    public function check_new_dz(){
        return count(DzMasdu::where('check', '=', false)->get()->toArray());
//    if (count(DzMasdu::where('check', '=', false)->get())){
//        foreach (DzMasdu::where('check', '=', false)->get() as $row){
//            $row->update(['check'=>true]);
//        }
//        return ['Ghj'=>0, 'dfk'=>1];
    }
//}
    public function create_month_xml(){
        $obj_data = TableObj::where('guid_masdu_sut', '!=', '')->get();
        $hfrpok = [];
        foreach ($obj_data as $row){
            array_push($hfrpok, $row->hfrpok);
        }
        $params = DB::table('app_info.hour_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', date('Y-m-d H:i:s', strtotime('-3 days')))->orderByDesc('timestamp')->get();
        $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $contents = $contents . "<BusinessMessage>\n";
        $contents = $contents . "   <HeaderSection>\n";
        $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
        $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
        $contents = $contents . "     <Generated at=\"" . date('Y-m-d').'T'.date('H:i:s').'+05:00'."\"/>\n";
        $contents = $contents . "     <Comment>Сеансовые данные.Месяц.</Comment>\n";
        $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-01', strtotime('+1 month')).'T10:00:00+03:00'. "\"/>\n";
        $contents = $contents . "     <Scale>P1M</Scale>\n";
        $contents = $contents . "     <Template id=\"D_NDM.PT1M.PL.V1\"/>\n";
        $contents = $contents . "     <FullName>ГД Надым</FullName>\n";
        $contents = $contents . "   </HeaderSection>\n";
        foreach ($obj_data as $row) {
            $data_param = $params->where('hfrpok_id', '=', $row->hfrpok)->first();
            if ($data_param != '') {
                $value = $data_param->val;
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_sut . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $value . "</Value>\n";
                $contents = $contents . "     <Source>0</Source>\n";
                $contents = $contents . "   </DataSection>\n";
            }
        }
        $contents = $contents . "</BusinessMessage>\n";
        $name_xml = 'PT1M_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
        try {
            try {
                $setting_sftp = SftpServer::where('type', '=', 'osnovnoi')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'],
                ]);
                $disk->put($name_xml . '.xml', $contents, 'public');
            } catch (\Throwable $e){
                $setting_sftp = SftpServer::where('type', '=', 'reserv')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'],
                ]);
                $disk->put($name_xml . '.xml', $contents, 'public'); //можно дописать путь перед $name_xml
            }
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'XML успешно отправлена!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }catch (\Throwable $exep){
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
    }

    public function create_xml_hand_sut($id_xml)
    {
        $obj_data = TableObj::where('guid_masdu_sut', '!=', '')->get();
        $hfrpok = [];
        foreach ($obj_data as $row){
            array_push($hfrpok, $row->hfrpok);
        }
        $params = DB::table('app_info.hour_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', date('Y-m-d H:i:s', strtotime('-3 days')))->orderByDesc('timestamp')->get();
        $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $contents = $contents . "<BusinessMessage>\n";
        $contents = $contents . "   <HeaderSection>\n";
        $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
        $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
        $contents = $contents . "     <Generated at=\"" . date('Y-m-d').'T'.date('H:i:s').'+05:00'."\"/>\n";
        $contents = $contents . "     <Comment>Сеансовые данные.Месяц.</Comment>\n";
        $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-01', strtotime('+1 month')).'T10:00:00+03:00'. "\"/>\n";
        $contents = $contents . "     <Scale>P1M</Scale>\n";
        $contents = $contents . "     <Template id=\"D_NDM.PT1M.PL.V1\"/>\n";
        $contents = $contents . "     <FullName>ГД Надым</FullName>\n";
        $contents = $contents . "   </HeaderSection>\n";
        foreach ($obj_data as $row) {
            $data_param = $params->where('hfrpok_id', '=', $row->hfrpok)->first();
            if ($data_param != '') {
                $value = $data_param->val;
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_sut . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $value . "</Value>\n";
                $contents = $contents . "     <Source>0</Source>\n";
                $contents = $contents . "   </DataSection>\n";
            }
        }
        $contents = $contents . "</BusinessMessage>\n";
        $name_xml = 'PT1M_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
        try {
            try {
                $setting_sftp = SftpServer::where('type', '=', 'osnovnoi')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'],
                ]);
                $disk->put($name_xml . '.xml', $contents, 'public');
            } catch (\Throwable $e){
                $setting_sftp = SftpServer::where('type', '=', 'reserv')->first()->toArray();
                $disk = Storage::build([
                    'driver' => 'sftp',
                    'host' => $setting_sftp['adres_sftp'],
                    'username' => $setting_sftp['user'],
                    'password' => $setting_sftp['password'],
                    'visibility' => 'public',
                    'permPublic' => 0777, /// <- this one did the trick
                    'root' => $setting_sftp['path_sftp'],
                ]);
                $disk->put($name_xml . '.xml', $contents, 'public'); //можно дописать путь перед $name_xml
            }
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'XML успешно отправлена!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }catch (\Throwable $exep){
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
        Events::where('id', '=', $id_xml)->first()->update(['option'=>'Отсутствие связи с sftp-сервером! Повторная отправка осуществлена '.date('Y-m-d H:i:s')]);
    }

    public function print_param_dks($date){
        return view('web.pdf_form.pdf_param_dks', compact('date'));
    }
    public function report_param_dks(){
        return view('web.reports.open_param_dks');
    }
    public function get_param_dks($date){
        $array = [375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 393, 394];
        $hfrpok_params = Min_params::wherein('hfrpok_id', $array)->wherebetween('timestamp', [date('Y-m-d 12:00:01', strtotime($date)), date('Y-m-d 11:59:01', strtotime($date. ' + 1 days'))])
            ->orderbydesc('id')->get();
        $data_to_table['name'][1] = 'ДКС-1 Ямсовейского ГКМ';
        $data_to_table['pressure'][1] = $hfrpok_params->where('hfrpok_id', '=', 381)->first();
        $data_to_table['stepen'][1] = $hfrpok_params->where('hfrpok_id', '=', 385)->first();
        $data_to_table['all'][1] = '6';
        $data_to_table['job'][1] = $hfrpok_params->where('hfrpok_id', '=', 375)->first();
        $data_to_table['reserv'][1] = $hfrpok_params->where('hfrpok_id', '=', 379)->first();
        $data_to_table['repair'][1] = $hfrpok_params->where('hfrpok_id', '=', 377)->first();
        $data_to_table['name'][2] = 'ДКС-2 Ямсовейского ГКМ';
        $data_to_table['pressure'][2] = $hfrpok_params->where('hfrpok_id', '=', 382)->first();
        $data_to_table['stepen'][2] = $hfrpok_params->where('hfrpok_id', '=', 386)->first();
        $data_to_table['all'][2] = '6';
        $data_to_table['job'][2] = $hfrpok_params->where('hfrpok_id', '=', 376)->first();
        $data_to_table['reserv'][2] = $hfrpok_params->where('hfrpok_id', '=', 380)->first();
        $data_to_table['repair'][2] = $hfrpok_params->where('hfrpok_id', '=', 378)->first();
        $data_to_table['name'][3] = 'ДКС-1 Юбилейного ГКМ';
        $data_to_table['pressure'][3] = $hfrpok_params->where('hfrpok_id', '=', 393)->first();
        $data_to_table['stepen'][3] = $hfrpok_params->where('hfrpok_id', '=', 383)->first();
        $data_to_table['all'][3] = '6';
        $data_to_table['job'][3] = $hfrpok_params->where('hfrpok_id', '=', 387)->first();
        $data_to_table['reserv'][3] = $hfrpok_params->where('hfrpok_id', '=', 389)->first();
        $data_to_table['repair'][3] = $hfrpok_params->where('hfrpok_id', '=', 391)->first();
        $data_to_table['name'][4] = 'ДКС-2 Юбилейного ГКМ';
        $data_to_table['pressure'][4] = $hfrpok_params->where('hfrpok_id', '=', 394)->first();
        $data_to_table['stepen'][4] = $hfrpok_params->where('hfrpok_id', '=', 384)->first();
        $data_to_table['all'][4] = '6';
        $data_to_table['job'][4] = $hfrpok_params->where('hfrpok_id', '=', 388)->first();
        $data_to_table['repair'][4] = $hfrpok_params->where('hfrpok_id', '=', 390)->first();
        $data_to_table['reserv'][4] = $hfrpok_params->where('hfrpok_id', '=', 392)->first();
        return $data_to_table;    }
    public function report_skv(){
        return view('web.reports.open_skv');
    }
    public function get_skv($date){
$array = [372, 366, 363, 365, 364, 367, 371, 368, 370, 369];
$hfrpok_params = Min_params::wherein('hfrpok_id', $array)->wherebetween('timestamp', [date('Y-m-d 12:00:01', strtotime($date)), date('Y-m-d 11:59:01', strtotime($date. ' + 1 days'))])
            ->orderbydesc('id')->get();        $data_to_table['name'][1] = 'Ямсовейское ГКМ';
        $data_to_table['all'][1] = $hfrpok_params->where('hfrpok_id', '=', 372)->first();
        $data_to_table['job'][1] = $hfrpok_params->where('hfrpok_id', '=', 366)->first();
        $data_to_table['prostoy'][1] = $hfrpok_params->where('hfrpok_id', '=', 363)->first();
        $data_to_table['reserv'][1] = $hfrpok_params->where('hfrpok_id', '=', 365)->first();
        $data_to_table['repair'][1] = $hfrpok_params->where('hfrpok_id', '=', 364)->first();
        $data_to_table['name'][2] = 'Юбилейное ГКМ';
        $data_to_table['all'][2] = $hfrpok_params->where('hfrpok_id', '=', 367)->first();
        $data_to_table['job'][2] = $hfrpok_params->where('hfrpok_id', '=', 371)->first();
        $data_to_table['prostoy'][2] = $hfrpok_params->where('hfrpok_id', '=', 368)->first();
        $data_to_table['reserv'][2] = $hfrpok_params->where('hfrpok_id', '=', 370)->first();
        $data_to_table['repair'][2] = $hfrpok_params->where('hfrpok_id', '=', 369)->first();
        return $data_to_table;
    }
    public function print_skv($date){
        return view('web.pdf_form.pdf_skv', compact('date'));
    }


}

?>
