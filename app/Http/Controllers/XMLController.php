<?php

namespace App\Http\Controllers;

use App\Models\ConfigXML;
use App\Models\Day_params;
use App\Models\Events;
use App\Models\UserAuth;
use App\Models\Hour_params;
use App\Models\Min_params;
use App\Models\SftpServer;
use App\Models\Sut_params;
use App\Models\TableObj;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use App\Models\GD_obj;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\NormConsumption;
use App\Models\PlanConsumption;
use App\Models\EconomyNorm;
use App\Models\EconomyPlan;
use App\Models\FactConsumption;
use PDF;
use DOMDocument;
use LibXMLError;


class XMLController extends Controller
{

    public function test_sftp($type){
        try {
            $setting_sftp = SftpServer::where('type', '=', $type)->first()->toArray();
            $disk = Storage::build([
                'driver' => 'sftp',
                'host' => $setting_sftp['adres_sftp'],
                'username' => $setting_sftp['user'],
                'password' => $setting_sftp['password'],
                'visibility' => 'public',
                'timeout' => '1',
                'permPublic' => 0777, /// <- this one did the trick
                'root' => $setting_sftp['path_sftp'],
            ]);
            $name = 'test_sftp_'.date('Y-m-d H:i').'.xml';
            $disk->put($name, 'Test sftp-server '.date('Y-m-d H:i'), 'public');
            if ($disk->get($name)){
                return 'Успешно!';
            }
        } catch (\Throwable $e){
            return 'Ошибка!';
        }
    }
    public function get_sftp_setting($type){
        $data = SftpServer::where('type', '=', $type)->first()->toArray();
        return $data;
    }
    public function save_sftp_setting(Request $request, $type){
        $new_log  = (new MainTableController)->create_log_record('Изменил настройки sftp-сервера');
        $data = SftpServer::where('type', '=', $type)->first();
        if ($data){
            try {
                $data->update($request->all());
            }catch (\Throwable $e){
                return $e;
            }
        }else{
            SftpServer::create($request->all());
        }
    }
    public function journal_xml(){
        $new_log  = (new MainTableController)->create_log_record('Открыл журнал отправки XML');
        if (UserAuth::orderbyDesc('id')->where('ip', '=', \request()->ip())->first()->level == 'cdp'){
            return view('web.journal_xml_cdp');
        }else{
            return view('web.journal_xml_rdp');
        }
    }
    public function get_journal_xml_data(){
        $data = Events::orderByDesc('id')->get();
        return $data;
    }

    public function hand_for_masdu($hours_xml){
        $timestamp = date('Y-m-d H:i:s');     //время отправки местное
        //Данные для XML
        if ($hours_xml == 1){
//            $name_xml = 'PT1H_'.date('Y_m_d_H_i_s', strtotime(' -2 hours'));
            $name_xml = 'PT1H_'.date('Y_m_d_H_i_s');
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_masdu_hours', '!=', '')->
            join('app_info.hour_params', 'test_table.hfrpok', '=', 'hour_params.hfrpok_id')
                ->where('hour_params.timestamp', '<', $timestamp)
                ->where('hour_params.timestamp', '>', date('Y-m-d H:i:s', strtotime($timestamp.' -1 hours')))
                ->select('test_table.hfrpok', 'test_table.guid_masdu_hours', 'hour_params.val', 'hour_params.manual', 'hour_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
            $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+03:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (1ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d')."T".date('H:00:00')."+03:00\"/>\n";
            $contents = $contents . "     <Scale>PT1H</Scale>\n";
            $contents = $contents . "     <Template id=\"G_PHG.PT1H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_hours . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $row->val . "</Value>\n";
                if ($row->manual){
                    $contents = $contents . "     <Source>1</Source>\n";
                }else{
                    $contents = $contents . "     <Source>0</Source>\n";
                }
                $contents = $contents . "   </DataSection>\n";
                Hour_params::where('id', '=', $row->id)->update(['xml_create'=>true]);
            }
            $contents = $contents . "</BusinessMessage>\n";
            Storage::disk('local')->put('buffer_xml_hand/PT1H.xml', $contents, 'public');
            try {
                $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                $path = str_replace("\\", "/", $path );
                $path = (string) $path;
                $xml = new DOMDocument();
                $xml->load($path.'buffer_xml_hand/PT1H.xml');
                if (!$xml->schemaValidate($path.'schema_xml/PT1H.xsd')) {
                    $data_in_journal['option'] = 'Файл сеансовых данных не соответствует формату передачи сеансовых данных!';
                    $data_in_journal['event'] = 'Отправка XML '. $name_xml;
//                    $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return '0';
                }
            }catch (\Throwable $e){
                $data_in_journal['option'] = 'Файл сеансовых данных не соответствует формату передачи сеансовых данных!';
                $data_in_journal['event'] = 'Отправка XML '. $name_xml;
//                $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
                $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                $record = Events::create($data_in_journal);
                return $e;
            }
        }elseif ($hours_xml == 24){
            $name_xml = 'PT24H_'.date('Y_m_d_H_i_s');
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_masdu_day', '!=', '')->
            join('app_info.sut_params', 'test_table.hfrpok', '=', 'sut_params.hfrpok_id')
                ->where('sut_params.timestamp', '=', date('Y-m-d', strtotime($timestamp.' -1 days')))
                ->select('test_table.hfrpok', 'test_table.guid_masdu_day', 'sut_params.val', 'sut_params.manual', 'sut_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
            $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+03:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (24ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime('-24 hours'))."T".date('10:00:00', strtotime('-24 hours'))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>PT24H</Scale>\n";
            $contents = $contents . "     <Template id=\"G_PHG.PT24H.RT.V1\"/>\n";
            $ip = \request()->ip();
            try {
                $data_user = UserAuth::orderbyDesc('id')->where('ip', '=', $ip)->first();
                $name = $data_user->username;
            } catch (\Throwable $e){
                $name = 'Неизвестно';
            }
            $contents = $contents . "     <FullName>".$name."</FullName>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_day . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $row->val . "</Value>\n";
                if ($row->manual){
                    $contents = $contents . "     <Source>1</Source>\n";
                }else{
                    $contents = $contents . "     <Source>0</Source>\n";
                }
                $contents = $contents . "   </DataSection>\n";
                Sut_params::where('id', '=', $row->id)->update(['xml_create'=>true]);
            }
            $contents = $contents . "</BusinessMessage>\n";
            Storage::disk('local')->put('buffer_xml_hand/PT24H.xml', $contents, 'public');
            try {
                $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                $path = str_replace("\\", "/", $path );
                $path = (string) $path;
                $xml = new DOMDocument();
                $xml->load($path.'buffer_xml_hand/PT24H.xml');
                if (!$xml->schemaValidate($path.'schema_xml/PT24H.xsd')) {
                    $data_in_journal['option'] = 'Файл сеансовых данных не соответствует формату передачи сеансовых данных!';
                    $data_in_journal['event'] = 'Отправка XML '. $name_xml;
//                    $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return '0';
                }
            }catch (\Throwable $e){
                $data_in_journal['option'] = 'Файл сеансовых данных не соответствует формату передачи сеансовых данных!';
                $data_in_journal['event'] = 'Отправка XML '. $name_xml;
//                $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
                $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                $record = Events::create($data_in_journal);
                return $e;
            }
        }
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
            $data_in_journal['event'] = 'Отправка XML '.$name_xml;
            $data_in_journal['option'] = 'XML успешно отправлена!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }catch (\Throwable $exep){
            $data_in_journal['event'] = 'Отправка XML '. $name_xml;
            $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
            Storage::disk('local')->put('buffer_xml_hand/'.$name_xml.'.xml', $contents, 'public');
        }
    }

    public function create_xml($hours_xml) //раскоментить то, что с remove_astra
    {
        try {
            $time = date('Y-m-d H:i:s', strtotime('-1 hours')); //для часовиков начало
            $time1 = date('Y-m-d H:i:s', strtotime('-24 hours')); //для суточных начало
            $time2 = date('Y-m-d H:i:s', strtotime('-5 minutes')); //для минуток начало

            $time_zone = '+03:00';
            $hour = (ceil(date("H") / 2)-1) * 2;  //для подписи xml
            $minutes = (ceil(date("i") / 5)-1) * 5;
            if ($hours_xml == 1) {   //для часовика
                $obj_data = TableObj::where('guid_masdu_hours', '!=', '')->get();     // выбираем те, что надо отправлять
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML PT2H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return false;
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.hour_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', $time)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok); //сколько надо отправить
                $count_params = count($params); //сколько есть на отправку
                if ($count_in_test_table != $count_params) {  //проверка на количество нужных и фактических
                    $data_in_journal['event'] = 'Отправка XML PT2H';
                    $data_in_journal['option'] = 'Файл сеансовых данных не соответствует полноте наполнения!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return false;
                } else {
                    foreach ($params as $row) {
                        DB::table('app_info.hour_params')->where('id', $row->id)->update(['xml_create' => true]); //отмечаем, что по данных отправлена xml
                    }
                }
                $template_id = ' id="G_PHG.PT2H.RT.V1';
                $type_xml = 'PT2H';
                $comment = 'Сеансовые данные (2ч)';
            } elseif ($hours_xml == 24) {
                $obj_data = TableObj::where('guid_masdu_day', '!=', '')->get();
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML PT2H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return false;
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.sut_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '=', $time1)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok);
                $count_params = count($params);
                if ($count_in_test_table != $count_params) {        //проверка на количество данных снизу и в базе
                    $data_in_journal['event'] = 'Отправка XML PT24H';
                    $data_in_journal['option'] = 'Файл сеансовых данных не соответствует полноте наполнения!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return false;
                } else {
                    foreach ($params as $row) {
                        DB::table('app_info.sut_params')->where('id', $row->id)->update(['xml_create' => true]);
                    }
                }
                $template_id = ' id="G_PHG.PT24H.RT.V1';
                $type_xml = 'PT24H';
                $comment = 'Сеансовые данные (24ч)';
            } else{
                $obj_data = TableObj::where('guid_masdu_5min', '!=', '')->get();
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML PT5M';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.5min_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', $time2)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok);
                $count_params = count($params);
                if ($count_in_test_table != $count_params) {        //проверка на количество данных снизу и в базе
                    $data_in_journal['event'] = 'Отправка XML PT5M';
                    $data_in_journal['option'] = 'Файл сеансовых данных не соответствует полноте наполнения!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                    return false;
                } else {
                    foreach ($params as $row) {
                        DB::table('app_info.5min_params')->where('id', $row->id)->update(['xml_create' => true]);
                    }
                }
                $template_id = ' id="G_PHG.PT5M.RT.V1';
                $type_xml = 'PT5M';
                $comment = 'Сеансовые данные (Реальное время)';
            }

                $time_generate = date('Y-m-d') . 'T' . date('H:i:s').'+03:00';

                $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
                $contents = $contents . "<BusinessMessage>\n";
                $contents = $contents . "   <HeaderSection>\n";
                $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
                $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
                $contents = $contents . "     <Generated at=\"" . $time_generate . "\"/>\n";
                $contents = $contents . "     <Comment>{$comment}</Comment>\n";
                if ($hours_xml ==1 ){
                    $time_generate = date('Y-m-d') . 'T' . date('H:00:00');
                } elseif ($hours_xml == 24){
                    $time_generate = date('Y-m-d') . 'T10:00:00';
                } else{
                    if ($minutes<10){
                        $minutes ='0'.$minutes;
                    }
                    $time_generate = date('Y-m-d') . 'T'.date('H:'. $minutes.':00');
                }
                $contents = $contents . "     <ReferenceTime time=\"" . $time_generate."+03:00\"/>\n";
                $contents = $contents . "     <Scale>{$type_xml}</Scale>\n";

                $contents = $contents . "     <Template{$template_id}\"/>\n";
                if ($hours_xml == 24){
                    $contents = $contents . "     <FullName>ГП ПХГ</FullName>\n";
                }
                $contents = $contents . "   </HeaderSection>\n";

                foreach ($obj_data as $row) {

                    $data_param = $params->where('hfrpok_id', '=', $row->hfrpok)->first();
                    if ($data_param != '') {
                        $value = $data_param->val;


                        $contents = $contents . "   <DataSection>\n";
                        if ($hours_xml == 1) {
                            $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_hours . "</Identifier>\n";
                        } elseif ($hours_xml == 24) {
                            $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_day . "</Identifier>\n";
			            } else {
                            $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_5min . "</Identifier>\n";
                        }
                        $contents = $contents . "     <Value>" . $value . "</Value>\n";
                        $contents = $contents . "     <Source>" . '0' . "</Source>\n";
                        $contents = $contents . "   </DataSection>\n";
                    }
                }
		$contents = $contents . "</BusinessMessage>\n";
                if ($hours_xml == 1) {
                    $name_xml = 'PT2H_' . date('Y_m_d_H_i_s');
                    Storage::disk('local')->put('buffer_xml/PT2H.xml', $contents, 'public');
                } elseif ($hours_xml == 24) {
                    $name_xml = 'PT24H_' . date('Y_m_d_H_i_s');
                    Storage::disk('local')->put('buffer_xml/PT24H.xml', $contents, 'public');
                } else{
                    $name_xml = 'PT5M' . date('Y_m_d_H_i_s');
                    Storage::disk('local')->put('buffer_xml/PT5M.xml', $contents, 'public');
                }
        } catch (\Throwable $e) {
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Ошибка отправки XML!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
            return false;
        }
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $path = str_replace("\\", "/", $path );
        $path = (string) $path;
        try {
            if ($hours_xml == 1) {
                $xml = new DOMDocument();
                $xml->load($path.'buffer_xml/PT2H.xml');
                if (!$xml->schemaValidate($path.'schema_xml/PT2H.xsd')) {
                } else {
                    $check_valid = 1;
                }
            } elseif ($hours_xml == 24) {
                $xml = new DOMDocument();
                $xml->load($path.'buffer_xml/PT24H.xml');
                if (!$xml->schemaValidate($path.'schema_xml/PT24H.xsd')) {
                } else {
                    $check_valid = 1;
                }
            } else{
                $xml = new DOMDocument();
                $xml->load($path.'buffer_xml/PT5M.xml');
                if (!$xml->schemaValidate($path.'schema_xml/PT5M.xsd')) {
                } else {
                    $check_valid = 1;
                }
            }

        } catch (\Throwable $e) {
            $check_valid = 0;
        }

        if ($check_valid != 0) {

            if ($hours_xml == 1 ){
                $name_xml = 'PT2H_' . date('Y_m_d_H_i_s');
            } elseif($hours_xml == 24) {
                $name_xml = 'PT24H_' . date('Y_m_d_H_i_s');
            } else{
                $name_xml = 'PT5M_' . date('Y_m_d_H_i_s');
            }
//                    Storage::disk('local')->put($name_xml . '.xml', $contents, 'public'); //можно дописать путь перед $name_xml
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
        } else {
            if ($hours_xml == 1 ){
                $name_xml = 'PT2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } elseif($hours_xml == 24) {
                $name_xml = 'PT24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } else{
                $name_xml = 'PT5M_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            }
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Файл сеансовых данных не соответствует формату передачи сеансовых данных!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
    }

    public function create_xml_hand($id_xml)
    {
        $from_journal = Events::where('id', '=', $id_xml);

        if (Events::where('id', '=', $id_xml)->where('event', 'like', '%PT2H%')->first()){
            $hours_xml = 1; //часовик
        }elseif (Events::where('id', '=', $id_xml)->where('event', 'like', '%PT24H%')->first()){
            $hours_xml = 24; //суточный
        }elseif(Events::where('id', '=', $id_xml)->where('event', 'like', '%PT5M%')->first()){
            $hours_xml = 5; //минутный
        }
        $name_xml = substr($from_journal->first()->event, 21, 25);    //название xml
        $timestamp = date('Y-m-d H:i', strtotime(
            str_replace('_', '-', substr($from_journal->first()->event, -19, -9)).' '.
            str_replace('_', ':', substr($from_journal->first()->event, -8, -3))));     //время отправки местное
        //Данные для XML
        if ($hours_xml == 1){
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_masdu_hours', '!=', '')->
                join('app_info.hour_params', 'test_table.hfrpok', '=', 'hour_params.hfrpok_id')
                ->where('hour_params.timestamp', '<', $timestamp)
                ->where('hour_params.timestamp', '>', date('Y-m-d H:i', strtotime($timestamp.' -1 hours')))
                ->select('test_table.hfrpok', 'test_table.guid_masdu_hours', 'hour_params.val', 'hour_params.manual', 'hour_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
            $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+03:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (2ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp))."T".date('H:00:00', strtotime($timestamp))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>PT2H</Scale>\n";
            $contents = $contents . "     <Template id=\"G_PHG.PT2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_hours . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $row->val . "</Value>\n";
                if ($row->manual){
                    $contents = $contents . "     <Source>1</Source>\n";
                }else{
                    $contents = $contents . "     <Source>0</Source>\n";
                }
                $contents = $contents . "   </DataSection>\n";
                Hour_params::where('id', '=', $row->id)->update(['xml_create'=>true]);
            }
            $contents = $contents . "</BusinessMessage>\n";
        }elseif ($hours_xml == 24){
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_masdu_day', '!=', '')->
            join('app_info.sut_params', 'test_table.hfrpok', '=', 'sut_params.hfrpok_id')
                ->where('sut_params.timestamp', '=', date('Y-m-d', strtotime($timestamp.' -1 days')))
                ->select('test_table.hfrpok', 'test_table.guid_masdu_day', 'sut_params.val', 'sut_params.manual', 'sut_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
            $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+05:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (24ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d')."T".date('H:00:00', strtotime($timestamp))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>PT24H</Scale>\n";
            $contents = $contents . "     <Template id=\"G_PHG.PT2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_day . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $row->val . "</Value>\n";
                if ($row->manual){
                    $contents = $contents . "     <Source>1</Source>\n";
                }else{
                    $contents = $contents . "     <Source>0</Source>\n";
                }
                $contents = $contents . "   </DataSection>\n";
                Sut_params::where('id', '=', $row->id)->update(['xml_create'=>true]);
            }
            $contents = $contents . "</BusinessMessage>\n";
        }else{
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_masdu_5min', '!=', '')->
            join('app_info.5min_params', 'test_table.hfrpok', '=', '5min_params.hfrpok_id')
                ->where('5min_params.timestamp', '<', date('Y-m-d H:i:s', strtotime($timestamp)))
                ->where('5min_params.timestamp', '>', date('Y-m-d H:i:s', strtotime($timestamp.' -3 minutes')))
                ->select('test_table.hfrpok', 'test_table.guid_masdu_5min', '5min_params.val', '5min_params.manual', '5min_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ПХГ\"/>\n";
            $contents = $contents . "     <Receiver id=\"М АСДУ ЕСГ\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+03:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (Реальное время)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp))."T".date('H:i:00', strtotime($timestamp))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>PT5M</Scale>\n";
            $contents = $contents . "     <Template id=\"G_PHG.PT2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_masdu_5min . "</Identifier>\n";
                $contents = $contents . "     <Value>" . $row->val . "</Value>\n";
                if ($row->manual){
                    $contents = $contents . "     <Source>1</Source>\n";
                }else{
                    $contents = $contents . "     <Source>0</Source>\n";
                }
                $contents = $contents . "   </DataSection>\n";
                Min_params::where('id', '=', $row->id)->update(['xml_create'=>true]);
            }
            $contents = $contents . "</BusinessMessage>\n";
        }
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
            $data_in_journal['event'] = 'Отправка XML '.$name_xml;
            $data_in_journal['option'] = 'XML успешно отправлена!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }catch (\Throwable $exep){
            $data_in_journal['event'] = 'Отправка XML '. $name_xml;
            $data_in_journal['option'] = 'Отсутствие связи с sftp-сервером!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
            Storage::disk('local')->put('buffer_xml_hand/'.$name_xml.'.xml', $contents, 'public');
        }
        Events::where('id', '=', $id_xml)->first()->update(['option'=>'Отсутствие связи с sftp-сервером! Повторная отправка осуществлена '.date('Y-m-d H:i:s')]);
    }    //когда повторная отправка

    public function check_error_xml(){
        $event_last = Events::where('timestamp', '>', date('Y-m-d H:i:s', strtotime(' - 3 days')))->get();
        $sftp_error = $event_last->where('option', '=', 'Отсутствие связи с sftp-сервером!');
        return count($sftp_error);
    }
}

?>
