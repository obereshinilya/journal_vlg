<?php

namespace App\Http\Livewire;

use App\Models\Ref_obj;
use App\Ref_opo;
use Livewire\Component;

class FsMap extends Component
{
    public $name;
    public $status;
    public $color_status;
    public $elem_id;

    public function render()
    {

        return view('livewire.fs-map',[
        'objs'=>Ref_obj::orwhere([['InUse', '!=', '0'],['idOPO','=','1']])
            ->orderby('idObj')
            ->get(),
        'opo' =>Ref_opo::find(1),
            ]);
    }
    public function Show($id)
    {
        $this->updateMode = true;
        $elem= Ref_obj::where('idObj',$id)->first();
        $this->elem_id = $id;
        $this->name = $elem->nameObj;
        $this->status = $elem->obj_to_status->desc_work;
     //   $this->status = $elem->elem_to_calc->first()->status_ip_el;
        if ($elem->status == '50')
        {
            $this->color_status = 'good';
        }
        else
        {
            $this->color_status = 'repair';
        }
    }
}
