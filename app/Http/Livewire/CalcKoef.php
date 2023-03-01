<?php

namespace App\Http\Livewire;

use App\Http\Controllers\AdminController;
use App\Models\Dynamic\Calc_koef;
use Livewire\WithPagination;
use Livewire\Component;

class CalcKoef extends Component
{
    public $name;
    public $from_oto;
    public $koef_id;
    public $koef;

    public function render()
    {
        return view('livewire.calc-koef', [
            'koefs'=> Calc_koef::orderby('id')->get(),
        ]);
    }
    public function edit($id)
    {
        $this->updateMode = true;
        $event= Calc_koef::where('id',$id)->first();
        $this->koef_id = $id;
        $this->name = $event->description;
        $this->from_oto = $event->from_oto;
        $this->koef = $event->koef;
        AdminController::log_record('Открыл для редактирования коэффициент для расчета');//пишем в журнал

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'koef' => 'required|numeric|min:0|max:1',
        ]);

//       if ($this->koef_id) {
            $event = Calc_koef::find($this->koef_id);
            $event->update([
                'koef' => $this->koef,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Users Updated Successfully.');
            $this->resetInputFields();
        AdminController::log_record('Сохранил после редактирования коэффициент для расчета');//пишем в журнал
        return redirect()->to('/docs/koef');
//        }
    }
    private function resetInputFields(){
        $this->name = '';
        $this->from_oto = '';
        $this->koef = '';

    }
}
