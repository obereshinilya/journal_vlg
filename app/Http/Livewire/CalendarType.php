<?php

namespace App\Http\Livewire;

use App\Http\Controllers\AdminController;
use App\Models\Dynamic\Calendar_type;
use Livewire\WithPagination;
use Livewire\Component;

class CalendarType extends Component
{
    public $type_name;
    public $type_id;

    public function submit()
    {
        $validatedDate = $this->validate([
            'type_name' => 'required',
        ]);
        $data['name'] = $validatedDate['type_name'];
        Calendar_type::create($data);
        $this->resetInputFields();
        AdminController::log_record('Создал возможное событие в календаре');//пишем в журнал
        return redirect()->to('/docs/calendar_event');

    }

    public function render()
    {
        return view('livewire.calendar-type', [
            'types'=> Calendar_type::orderby('id')->get(),
        ]);
    }
    public function edit($id)
    {
        $this->updateMode = true;
        $event= Calendar_type::where('id',$id)->first();
        $this->type_id = $event->id;
        $this->type_name = $event->name;
        AdminController::log_record('Открыл для редактирования возможное событие в календаре');//пишем в журнал
    }
    public function update()
    {

            $event = Calendar_type::find($this->type_id);

            $event->update([
                'name' => $this->type_name,
                'id' => $this->type_id,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Users Updated Successfully.');
            $this->resetInputFields();
        AdminController::log_record('Сохранил после редактирования возможное событие в календаре');//пишем в журнал
        return redirect()->to('/docs/calendar_event');
    }
    private function resetInputFields(){
        $this->type_name = '';
    }

    public function delete($id)
    {
        if($id){
            Calendar_type::where('id',$id)->delete();
            AdminController::log_record('Удалил возможное событие в календаре');//пишем в журнал
            session()->flash('message', 'Events Deleted Successfully.');
        }
    }
}
