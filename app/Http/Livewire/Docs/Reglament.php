<?php

namespace App\Http\Livewire\Docs;

use App\Http\Controllers\AdminController;
use App\Models\Tech_reg\Tech_reglament;
use Livewire\Component;
use Livewire\WithPagination;

class Reglament extends Component
{
    use WithPagination;
    public $search = '';
    public $min;
    public $max;
    public $koef;
    public $tech_id;
    public $name;

    public function render()
    {
        if ($this->search <>'')
            return view('livewire.docs.reglament', [
                'reglaments'=> Tech_reglament::orwhere('idObj', '=', $this->search)->orderBy('id')->get(),
            ]);

        else
       return view('livewire.docs.reglament', [
            'reglaments'=> Tech_reglament::orderby('id')->get(),
        ]);
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'min' => 'required',
            'max' => 'required',
            'koef' => 'required',
        ]);
        Tech_reglament::create($validatedDate);
        AdminController::log_record('Создал запись в справочнике технологических регламентов');//пишем в журнал
        //   $this->resetInputFields();
        return redirect()->to('/docs/reglament');

    }

    public function delete($id)
    {
        if($id){
            Tech_reglament::where('id',$id)->delete();
            AdminController::log_record('Удалил запись из справочника технологических регламентов');//пишем в журнал
            session()->flash('message', 'Events Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $tech_r= Tech_reglament::where('id',$id)->first();
        $this->tech_id = $id;
        $this->name = $tech_r->reglament_to_param->full_name;
        $this->min = $tech_r->min;
        $this->max = $tech_r->max;
        $this->koef = $tech_r->koef;
        AdminController::log_record('Открыл для редактирования запись из справочника технологических регламентов');//пишем в журнал
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'min' => 'required',
            'max' => 'required',
            'koef' => 'required',
        ]);

        if ($this->tech_id) {
            $event = Tech_reglament::find($this->tech_id);
            $event->update([
                'min' => $this->min,
                'max' => $this->max,
                'koef' => $this->koef,
            ]);
            AdminController::log_record('Сохранил после редактирования запись из справочника технологических регламентов');//пишем в журнал
            $this->updateMode = false;
            session()->flash('message', 'Events Updated Successfully.');
            //     $this->resetInputFields();
            return redirect()->to('/docs/reglament');
        }
    }
}
