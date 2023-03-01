<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableObj extends Model{
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

    public function getParentKeyName()
    {
        return 'parentId';
    }

    public function getLocalKeyName()
    {
        return 'id';
    }

    protected $table='app_info.test_table';
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $fillable = [
        'hfrpok', 'namepar1', 'inout', 'shortname', 'level', 'parentId', 'guid_masdu_5min', 'guid_masdu_hours', 'guid_masdu_day', 'guid_masdu_sut',
        'tag_name', 'min_param', 'hour_param', 'sut_param', 'name_str',
        'guid_transgaz_hours', 'guid_transgaz_day',
        'guid_ius_hours', 'guid_ius_day',
    ];


    static function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = TableObj::createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public static function getTree(){
        $data=TableObj::select('id', 'hfrpok',
        'namepar1', 'parentId', 'level')->where('inout', '=', '!')->orderBy('parentId')->orderBy('id')->get();

        foreach ($data as $row){
            $arr[]=array('id'=>$row->id,
                'hfrpok'=>$row->hfrpok,
                'namepar1'=>$row->namepar1,
                'parentId'=>$row->parentId,
                'level'=>$row->level);
        }

        $new = array();
        foreach ($arr as $a){
            $new[$a['parentId']][] = $a;
        }
        $tree = TableObj::createTree($new, array($data[0]));

        return $tree;
    }

    public static function getFieldsName(){
        $fields=TableObj::select('namepar1', 'id')->where('level', '=', '2')->get();
        return $fields;
    }

    public static function getObjectsName(){
        $objects=TableObj::select('namepar1', 'id')->where([['level', '=', '3'], ['inout', '=', '!']])->get();
        return $objects;
    }



}

?>
