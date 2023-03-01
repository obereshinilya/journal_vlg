<?php

namespace App\Http\Controllers;

use App\Models\ConfigXML;
use App\Models\Day_params;
use App\Models\Events;
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


class XMLController_new extends Controller
{

    public function create_xml_transgaz($hours_xml) //раскоментить то, что с remove_astra
    {
        try {
            $time = date('Y-m-d H:i:s', strtotime('-1 hours')); //для часовиков начало
            $time1 = date('Y-m-d H:i:s', strtotime('-24 hours')); //для суточных начало
            $time2 = date('Y-m-d H:i:s', strtotime('-5 minutes')); //для минуток начало

            $time_zone = '+03:00';
            $hour = date("H");
            $hour = (ceil($hour / 2)-1) * 2;  //для подписи xml
            $minutes = date("i");
            $minutes = (ceil($minutes / 5)-1) * 5;
            if ($hours_xml == 1) {   //для часовика
                $obj_data = TableObj::where('guid_transgaz_hours', '!=', '')->get();     // выбираем те, что надо отправлять
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML GTY24H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.hour_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', $time)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok); //сколько надо отправить
                $count_params = count($params); //сколько есть на отправку
                if ($count_in_test_table != $count_params) {  //проверка на количество нужных и фактических
                    $check_count = 0; //если не равны
                } else {
                    $check_count = 1; //если равны
                    foreach ($params as $row) {
                        DB::table('app_info.hour_params')->where('id', $row->id)->update(['xml_create' => true]); //отмечаем, что по данных отправлена xml
                    }
                }
                $template_id = ' id="D_NDM.GTY2H.RT.V1';
                $type_xml = 'GTY2H';
                $comment = 'Сеансовые данные (2ч)';
            } elseif ($hours_xml == 24) {
                $obj_data = TableObj::where('guid_transgaz_day', '!=', '')->get();
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML GTY2H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.sut_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '=', $time1)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok);
                $count_params = count($params);
                if ($count_in_test_table != $count_params) {        //проверка на количество данных снизу и в базе
                    $check_count = 0;
                } else {
                    $check_count = 1;
                    foreach ($params as $row) {
                        DB::table('app_info.sut_params')->where('id', $row->id)->update(['xml_create' => true]);
                    }
                }
                $template_id = ' id="D_NDM.GTY24H.RT.V1';
                $type_xml = 'GTY24H';
                $comment = 'Сеансовые данные (24ч)';
            }

            $time_generate = date('Y-m-d') . 'T' . date('H:i:s').'+05:00';

            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ГП ТГ Югорск\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . $time_generate . "\"/>\n";
            $contents = $contents . "     <Comment>{$comment}</Comment>\n";
            if ($hours_xml ==1 ){
                if ($hour<10){
                    $hour ='0'.$hour;
                }
                $time_generate = date('Y-m-d', strtotime('-2 hours')) . 'T' . date('H:00:00', strtotime('-2 hours'));
            } elseif ($hours_xml == 24){
                $time_generate = date('Y-m-d') . 'T10:00:00';
            }
            $contents = $contents . "     <ReferenceTime time=\"" . $time_generate . $time_zone . "\"/>\n";
            $contents = $contents . "     <Scale>{$type_xml}</Scale>\n";

            $contents = $contents . "     <Template{$template_id}\"/>\n";
            if ($hours_xml == 24){
                $contents = $contents . "     <FullName>ГД Надым</FullName>\n";
            }
            $contents = $contents . "   </HeaderSection>\n";

            foreach ($obj_data as $row) {

                $data_param = $params->where('hfrpok_id', '=', $row->hfrpok)->first();
                if ($data_param != '') {
                    $value = $data_param->val;
                    $contents = $contents . "   <DataSection>\n";
                    if ($hours_xml == 1) {
                        $contents = $contents . "     <Identifier type=\"GTY\">" . $row->guid_transgaz_hours . "</Identifier>\n";
                    } elseif ($hours_xml == 24) {
                        $contents = $contents . "     <Identifier type=\"GTY\">" . $row->guid_transgaz_day . "</Identifier>\n";
                    }
                    $contents = $contents . "     <Value>" . $value . "</Value>\n";
                    $contents = $contents . "     <Source>" . '0' . "</Source>\n";
                    $contents = $contents . "   </DataSection>\n";
                }
            }
            $contents = $contents . "</BusinessMessage>\n";
            $check_data = 1;
        } catch (\Throwable $e) {
            if ($hours_xml == 1 ){
                $name_xml = 'GTY2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } elseif($hours_xml == 24) {
                $name_xml = 'GTY24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            }
            $check_data = 0;
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Ошибка отправки XML!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $path = str_replace("\\", "/", $path );
        $path = (string) $path;

        if ($check_count != 0){
            if ($check_data != 0){
                    if ($hours_xml == 1 ){
                        $name_xml = 'GTY2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                    } elseif($hours_xml == 24) {
                        $name_xml = 'GTY24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                    }
                    try {
                        try {
                            $setting_sftp = SftpServer::where('type', '=', 'osnovnoi_transgaz')->first()->toArray();
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
                            $setting_sftp = SftpServer::where('type', '=', 'reserv_transgaz')->first()->toArray();
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
            } else{
                if ($hours_xml == 1 ){
                    $name_xml = 'GTY2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                } elseif($hours_xml == 24) {
                    $name_xml = 'GTY24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                }
                $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
                $data_in_journal['option'] = 'Ошибка записи XML!';
                $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                $record = Events::create($data_in_journal);
            }
        } else{
            if ($hours_xml == 1 ){
                $name_xml = 'GTY2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } elseif($hours_xml == 24) {
                $name_xml = 'GTY24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            }
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Файл сеансовых данных не соответствует полноте наполнения!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
    }

    public function create_xml_ius($hours_xml) //раскоментить то, что с remove_astra
    {
        try {
            $time = date('Y-m-d H:i:s', strtotime('-1 hours')); //для часовиков начало
            $time1 = date('Y-m-d H:i:s', strtotime('-24 hours')); //для суточных начало
            $time2 = date('Y-m-d H:i:s', strtotime('-5 minutes')); //для минуток начало

            $time_zone = '+03:00';
            $hour = date("H");
            $hour = (ceil($hour / 2)-1) * 2;  //для подписи xml
            $minutes = date("i");
            $minutes = (ceil($minutes / 5)-1) * 5;
            if ($hours_xml == 1) {   //для часовика
                $obj_data = TableObj::where('guid_ius_hours', '!=', '')->get();     // выбираем те, что надо отправлять
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML IUS24H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.hour_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '>', $time)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok); //сколько надо отправить
                $count_params = count($params); //сколько есть на отправку
                if ($count_in_test_table != $count_params) {  //проверка на количество нужных и фактических
                    $check_count = 0; //если не равны
                } else {
                    $check_count = 1; //если равны
                    foreach ($params as $row) {
                        DB::table('app_info.hour_params')->where('id', $row->id)->update(['xml_create' => true]); //отмечаем, что по данных отправлена xml
                    }
                }
                $template_id = ' id="D_NDM.IUS2H.RT.V1';
                $type_xml = 'IUS2H';
                $comment = 'Сеансовые данные (2ч)';
            } elseif ($hours_xml == 24) {
                $obj_data = TableObj::where('guid_ius_day', '!=', '')->get();
                if (count($obj_data) == 0){    //проверка на наличие записей с guid
                    $data_in_journal['event'] = 'Отправка XML IUS2H';
                    $data_in_journal['option'] = 'Нет данных для отправки!';
                    $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                    $record = Events::create($data_in_journal);
                }
                $hfrpok = [];
                foreach ($obj_data as $row){
                    array_push($hfrpok, $row->hfrpok);
                }
                $params = DB::table('app_info.sut_params')->whereIn('hfrpok_id', $hfrpok)->where('timestamp', '=', $time1)->orderByDesc('timestamp')->get();
                $count_in_test_table = count($hfrpok);
                $count_params = count($params);
                if ($count_in_test_table != $count_params) {        //проверка на количество данных снизу и в базе
                    $check_count = 0;
                } else {
                    $check_count = 1;
                    foreach ($params as $row) {
                        DB::table('app_info.sut_params')->where('id', $row->id)->update(['xml_create' => true]);
                    }
                }
                $template_id = ' id="D_NDM.IUS24H.RT.V1';
                $type_xml = 'IUS24H';
                $comment = 'Сеансовые данные (24ч)';
            }

            $time_generate = date('Y-m-d') . 'T' . date('H:i:s').'+05:00';

            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ИУС П Д\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . $time_generate . "\"/>\n";
            $contents = $contents . "     <Comment>{$comment}</Comment>\n";
            if ($hours_xml ==1 ){
                if ($hour<10){
                    $hour ='0'.$hour;
                }
                $time_generate = date('Y-m-d', strtotime('-2 hours')) . 'T' . date('H:00:00', strtotime('-2 hours'));
            } elseif ($hours_xml == 24){
                $time_generate = date('Y-m-d') . 'T10:00:00';
            }
            $contents = $contents . "     <ReferenceTime time=\"" . $time_generate . $time_zone . "\"/>\n";
            $contents = $contents . "     <Scale>{$type_xml}</Scale>\n";

            $contents = $contents . "     <Template{$template_id}\"/>\n";
            if ($hours_xml == 24){
                $contents = $contents . "     <FullName>ГД Надым</FullName>\n";
            }
            $contents = $contents . "   </HeaderSection>\n";

            foreach ($obj_data as $row) {

                $data_param = $params->where('hfrpok_id', '=', $row->hfrpok)->first();
                if ($data_param != '') {
                    $value = $data_param->val;
                    $contents = $contents . "   <DataSection>\n";
                    if ($hours_xml == 1) {
                        $contents = $contents . "     <Identifier type=\"IUS\">" . $row->guid_ius_hours . "</Identifier>\n";
                    } elseif ($hours_xml == 24) {
                        $contents = $contents . "     <Identifier type=\"IUS\">" . $row->guid_ius_day . "</Identifier>\n";
                    }
                    $contents = $contents . "     <Value>" . $value . "</Value>\n";
                    $contents = $contents . "     <Source>" . '0' . "</Source>\n";
                    $contents = $contents . "   </DataSection>\n";
                }
            }
            $contents = $contents . "</BusinessMessage>\n";
            $check_data =1;
        } catch (\Throwable $e) {
            if ($hours_xml == 1 ){
                $name_xml = 'IUS2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } elseif($hours_xml == 24) {
                $name_xml = 'IUS24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            }
            $check_data = 0;
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Ошибка отправки XML!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $path = str_replace("\\", "/", $path );
        $path = (string) $path;

        if ($check_count != 0){
            if ($check_data != 0){
                    if ($hours_xml == 1 ){
                        $name_xml = 'IUS2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                    } elseif($hours_xml == 24) {
                        $name_xml = 'IUS24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                    }
                    try {
                        try {
                            $setting_sftp = SftpServer::where('type', '=', 'osnovnoi_ius')->first()->toArray();
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
                            $setting_sftp = SftpServer::where('type', '=', 'reserv_ius')->first()->toArray();
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
            } else{
                if ($hours_xml == 1 ){
                    $name_xml = 'IUS2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                } elseif($hours_xml == 24) {
                    $name_xml = 'IUS24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
                }
                $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
                $data_in_journal['option'] = 'Ошибка записи XML!';
                $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
                $record = Events::create($data_in_journal);
            }
        } else{
            if ($hours_xml == 1 ){
                $name_xml = 'IUS2H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            } elseif($hours_xml == 24) {
                $name_xml = 'IUS24H_' . date('Y_m_d_H_i_s',strtotime('-2 hours'));
            }
            $data_in_journal['event'] = 'Отправка XML'.' '. $name_xml;
            $data_in_journal['option'] = 'Файл сеансовых данных не соответствует полноте наполнения!';
            $data_in_journal['timestamp'] = date('Y-m-d H:i:s');
            $record = Events::create($data_in_journal);
        }
    }

    public function create_xml_hand_transgaz($id_xml)
    {
        $from_journal = Events::where('id', '=', $id_xml);

        if (Events::where('id', '=', $id_xml)->where('event', 'like', '%GTY2H%')->first()){
            $hours_xml = 1; //часовик
        }elseif (Events::where('id', '=', $id_xml)->where('event', 'like', '%GTY24H%')->first()){
            $hours_xml = 24; //суточный
        }
        $name_xml = substr($from_journal->first()->event, 21, 25);    //название xml
        $timestamp = date('Y-m-d H:i', strtotime(
            str_replace('_', '-', substr($from_journal->first()->event, -19, -9)).' '.
            str_replace('_', ':', substr($from_journal->first()->event, -8, -3)).' +2 hours'));     //время отправки местное
        //Данные для XML
        if ($hours_xml == 1){

            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_transgaz_hours', '!=', '')->
            join('app_info.hour_params', 'test_table.hfrpok', '=', 'hour_params.hfrpok_id')
                ->where('hour_params.timestamp', '<', $timestamp)
                ->where('hour_params.timestamp', '>', date('Y-m-d H:i', strtotime($timestamp.' -1 hours')))
                ->select('test_table.hfrpok', 'test_table.guid_transgaz_hours', 'hour_params.val', 'hour_params.manual', 'hour_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ГП ТГ Югорск\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+05:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (2ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp.' -2 hours'))."T".date('H:00:00', strtotime($timestamp.' -2 hours'))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>GTY2H</Scale>\n";
            $contents = $contents . "     <Template id=\"D_NDM.GTY2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"GTY\">" . $row->guid_transgaz_hours . "</Identifier>\n";
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
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_transgaz_day', '!=', '')->
            join('app_info.sut_params', 'test_table.hfrpok', '=', 'sut_params.hfrpok_id')
                ->where('sut_params.timestamp', '=', date('Y-m-d', strtotime($timestamp.' -1 days')))
                ->select('test_table.hfrpok', 'test_table.guid_transgaz_day', 'sut_params.val', 'sut_params.manual', 'sut_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ГП ТГ Югорск\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+05:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (24ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp.' -2 hours'))."T".date('H:00:00', strtotime($timestamp.' -2 hours'))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>GTY24H</Scale>\n";
            $contents = $contents . "     <Template id=\"D_NDM.GTY2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_transgaz_day . "</Identifier>\n";
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
        }
        try {
            try {
                $setting_sftp = SftpServer::where('type', '=', 'osnovnoi_transgaz')->first()->toArray();
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
                $setting_sftp = SftpServer::where('type', '=', 'reserv_transgaz')->first()->toArray();
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
    }

    public function create_xml_hand_ius($id_xml)
    {
        $from_journal = Events::where('id', '=', $id_xml);

        if (Events::where('id', '=', $id_xml)->where('event', 'like', '%IUS2H%')->first()){
            $hours_xml = 1; //часовик
        }elseif (Events::where('id', '=', $id_xml)->where('event', 'like', '%IUS24H%')->first()){
            $hours_xml = 24; //суточный
        }
        $name_xml = substr($from_journal->first()->event, 21, 25);    //название xml
        $timestamp = date('Y-m-d H:i', strtotime(
            str_replace('_', '-', substr($from_journal->first()->event, -19, -9)).' '.
            str_replace('_', ':', substr($from_journal->first()->event, -8, -3)).' +2 hours'));     //время отправки местное
        //Данные для XML
        if ($hours_xml == 1){

            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_ius_hours', '!=', '')->
            join('app_info.hour_params', 'test_table.hfrpok', '=', 'hour_params.hfrpok_id')
                ->where('hour_params.timestamp', '<', $timestamp)
                ->where('hour_params.timestamp', '>', date('Y-m-d H:i', strtotime($timestamp.' -1 hours')))
                ->select('test_table.hfrpok', 'test_table.guid_ius_hours', 'hour_params.val', 'hour_params.manual', 'hour_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ГП ТГ Югорск\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+05:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (2ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp.' -2 hours'))."T".date('H:00:00', strtotime($timestamp.' -2 hours'))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>IUS2H</Scale>\n";
            $contents = $contents . "     <Template id=\"D_NDM.IUS2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"IUS\">" . $row->guid_ius_hours . "</Identifier>\n";
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
            $data_to_xml = DB::table('app_info.test_table')->where('test_table.guid_ius_day', '!=', '')->
            join('app_info.sut_params', 'test_table.hfrpok', '=', 'sut_params.hfrpok_id')
                ->where('sut_params.timestamp', '=', date('Y-m-d', strtotime($timestamp.' -1 days')))
                ->select('test_table.hfrpok', 'test_table.guid_ius_day', 'sut_params.val', 'sut_params.manual', 'sut_params.id')
                ->get();
            $contents = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $contents = $contents . "<BusinessMessage>\n";
            $contents = $contents . "   <HeaderSection>\n";
            $contents = $contents . "     <Sender id=\"ГП ДБ Надым\"/>\n";
            $contents = $contents . "     <Receiver id=\"ИУС П Д\"/>\n";    //ИУС ПТП
            $contents = $contents . "     <Generated at=\"" . date('Y-m-d')."T".date('H:i:s')."+05:00\"/>\n";
            $contents = $contents . "     <Comment>Сеансовые данные (24ч)</Comment>\n";
            $contents = $contents . "     <ReferenceTime time=\"" . date('Y-m-d', strtotime($timestamp.' -2 hours'))."T".date('H:00:00', strtotime($timestamp.' -2 hours'))."+03:00\"/>\n";
            $contents = $contents . "     <Scale>IUS24H</Scale>\n";
            $contents = $contents . "     <Template id=\"D_NDM.IUS2H.RT.V1\"/>\n";
            $contents = $contents . "   </HeaderSection>\n";
            foreach ($data_to_xml as $row){
                $contents = $contents . "   <DataSection>\n";
                $contents = $contents . "     <Identifier type=\"ASDU_ESG\">" . $row->guid_ius_day . "</Identifier>\n";
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
        }
        try {
            try {
                $setting_sftp = SftpServer::where('type', '=', 'osnovnoi_ius')->first()->toArray();
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
                $setting_sftp = SftpServer::where('type', '=', 'reserv_ius')->first()->toArray();
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
    }

}

?>
