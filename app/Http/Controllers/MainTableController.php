<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\TableObj;
use App\Models\UserAuth;
use Illuminate\Http\Request;
use PHPUnit\Exception;

class MainTableController extends Controller
{
    public function index()
    {
        return view('main_table');
    }

    public function getMainTableInfo(Request $request){
        $table=TableObj::select('id',
            'hfrpok',
            'namepar1',
            'inout',
            'name',
            'name_str',
            'shortname',
            'guid_masdu_5min',
            'guid_masdu_hours',
            'guid_masdu_day')
            ->where('id', '=', $request->id)
            ->orWhere('parentId', $request->id)
            ->orderBy('id')
            ->get();
        return $table;
    }

    public function changeMainTable(Request $request){
        try{
            TableObj::where('id', $request->id)->
            update([$request->column=>$request->value]);
            return [true];
        }
        catch (\Exception $err){
            return [false, $err];
        }
    }


    public function getFieldsName(){
        return TableObj::getFieldsName();
    }


    public function add_index(Request $request){
        try{
            $type=$request->type;
            $parentId=$request->obj_id;
            $level=intval($request->level)+1;
            if ($type=='index'){
                foreach ($request->rows as $row){
                    $new_row=array();
                    foreach ($row as $col) {
                        $new_row[$col['column']]=$col['value'];
                    }
                    $new_row['level']=$level;
                    $new_row['parentId']=$parentId;
                    TableObj::insert($new_row);
                }
            }
            else if ($type=='object'){
                $new_obj=[];
                foreach ($request->object['obj_attribs'] as $obj){
                    $new_obj[$obj['column']]=$obj['value'];
                }
                $new_obj['inout']='!';
                $new_obj['level']=$level;
                $new_obj['parentId']=$parentId;
                $parentId=TableObj::insertGetId($new_obj);
                foreach ($request->object['indexes'] as $index){
                    $new_row=array();
                    foreach ($index as $col) {
                        $new_row[$col['column']]=$col['value'];
                    }
                    $new_row['level']=$level+1;
                    $new_row['parentId']=$parentId;
                    TableObj::insert($new_row);
                }
            }
            return true;
        }
        catch (\Exception $err){
            return [false, $err];
        }
    }

    public function delete_row(Request $request){
        try{
            TableObj::where('id', '=', $request->row_id)->delete();
            return true;
        }
        catch (Exception $err){
            return false;
        }
    }

    public function get_all_params(){
        $data = TableObj::where('inout', '!=', '!')->select('hfrpok', 'namepar1')->get();
        return $data;
    }


    public function create_log_record($message){
        $record['ip'] = \request()->ip();
        $record['event'] = $message;
        try {
            $data_user = UserAuth::orderbyDesc('id')->where('ip', '=', $record['ip'])->first();
            $record['username'] = $data_user->username;
            $record['domain_name'] = $data_user->domain_name;
            $record['level'] = $data_user->level;
        } catch (\Throwable $e){
            $record['username'] = 'Неизвестно';
            $record['domain_name'] = 'Неизвестно';
            $record['level'] = 'Неизвестно';
        }
        $record['date'] = date('Y-m-d H:i:s');
        try {
            if (Log::orderbydesc('id')->first()->event != $record['event']){
                Log::create($record);
            }
        }catch (\Throwable $e){
            Log::create($record);
        }
    }

    public function register_new_user($name, $level, $domain_name){
        UserAuth::create([
            'username'=>$name,
            'ip'=>\request()->ip(),
            'level'=>$level,
            'domain_name'=>$domain_name
        ]);
        return redirect('/');
    }
}

?>
