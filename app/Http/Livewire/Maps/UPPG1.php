<?php

namespace App\Http\Livewire\Maps;

use App\Models\Ref_obj;
use App\Ref_opo;
use Livewire\Component;

class UPPG1 extends Component
{
    public $obj;
    public $color_10;
    public $color_11;
    public $color_12;
    public $color_13;
    public $colors;

    public function colors($op)
    {
        if ($op>=0.8) {
            $color[0] = 'good';
            $color[1] = 'Работа штатно';

        }
        elseif ($op<0.8 && $op>=0.5) {
            $color[0]  = 'normal';
            $color[1] = 'Низкий риск аварии';
        }
        elseif ($op<0.5 && $op>=0.2) {
            $color[0] = 'critical';
            $color[1] = 'Средний риск аварии';
        }
        elseif ($op<0.2 && $op>=0) {
            $color[0] = 'bad';
            $color[1] = 'Высокий риск аварии';
        }


        return $color;

    }

    public function render()
    {
        $j=0;
        for ($i = 9; $i <= 17; $i++) {
            $this_calc_tb = Ref_obj::find(199)->elem_to_calc_tb->where('from_oto', '=', $i)->first();
            $this->colors[$j] = $this->colors($this_calc_tb->op_tb);
            $j++;
        }


        return view('livewire.maps.u-p-p-g1',['opo' =>Ref_opo::find(3)]);
    }
}
