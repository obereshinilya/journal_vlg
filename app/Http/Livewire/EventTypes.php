<?php

namespace App\Http\Livewire;

use App\Http\Controllers\AdminController;
use App\Models\Matrix\Event_types;
use Livewire\WithPagination;
use App\User;
use Livewire\Component;

class EventTypes extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $name;
    public $from_type_obj;
    public $event_id;

    private function resetInputFields(){
        $this->name = '';
        $this->from_type_obj = '';
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'from_type_obj' => 'required',
        ]);
        Event_types::create($validatedDate);
        $this->resetInputFields();
        AdminController::log_record('Создал запись о возможном опасном событии');//пишем в журнал
        return redirect()->to('/docs/events');

    }

    public function render()
    {
        if ($this->search <>'') {
           return view('livewire.event-types', [
                'events'=> Event_types::orwhere('from_type_obj', '=', $this->search)->orderBy('id')->get(),
            ]);
        }
        else
        {
           return view('livewire.event-types', [
                'events'=>Event_types::orderby('id')->get(),
            ]);
        }

    }
    public function delete($id)
    {
        if($id){
            Event_types::where('id',$id)->delete();
            session()->flash('message', 'Events Deleted Successfully.');
            AdminController::log_record('Удалил запись о возможном опасном событии');//пишем в журнал
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $event= Event_types::where('id',$id)->first();
        $this->event_id = $id;
        $this->name = $event->name;
        $this->from_type_obj = $event->from_type_obj;
        AdminController::log_record('Открыл для редактирования запись о возможном опасном событии');//пишем в журнал

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'from_type_obj' => 'required',
        ]);

        if ($this->event_id) {
            $event = Event_types::find($this->event_id);
            $event->update([
                'name' => $this->name,
                'from_type_obj' => $this->from_type_obj,
            ]);
            AdminController::log_record('Сохранил после редактирования запись о возможном опасном событии');//пишем в журнал
            $this->updateMode = false;
            session()->flash('message', 'Events Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
    }
}
