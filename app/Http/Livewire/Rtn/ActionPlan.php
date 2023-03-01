<?php

namespace App\Http\Livewire\Rtn;

use Livewire\Component;
use App\Models\Rtn\Action_plan_pb;
use Livewire\WithPagination;

class ActionPlan extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $reg_num_opo;
    public $name_event;
    public $date_perfom;
    public $name_f;
    public $name_l;
    public $name_p;
    public $date_compl;
    public $date_transfer;
    public $reasons_post;
    public $reasons_transfer;
    public $check_exe;
    public $note;
    public $year_report;
    public $action_id;

    public function render()
    {
        return view('livewire.rtn.action-plan', ['rows'=>Action_plan_pb::orderby('id')->get()]);
    }


    private function resetInputFields(){
        $this->reg_num_opo = '';
        $this->name_event = '';
        $this->date_perfom = '';
        $this->name_f = '';
        $this->name_l = '';
        $this->name_p = '';
        $this->date_compl = '';
        $this->date_transfer = '';
        $this->reasons_post = '';
        $this->reasons_transfer = '';
        $this->check_exe = '';
        $this->note = '';
        $this->year_report = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
           'reg_num_opo' => 'required',
           'name_event' => 'required',
           'date_perfom' => 'required',
           'name_f' => 'required',
           'name_l' => 'required',
           'name_p' => 'required',
           'date_compl' => 'required',
           'date_transfer' => 'required',
           'reasons_post' => 'required',
           'reasons_transfer' => 'required',
           'check_exe' => 'required',
           'note' => 'required',
           'year_report' => 'required',
        ]);
        Action_plan_pb::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');
    }
    public function delete($id)
    {
        if($id){
            Action_plan_pb::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $action = Action_plan_pb::where('id',$id)->first();
        $this->action_id = $id;
        $this->reg_num_opo = $action->reg_num_opo;
        $this->name_event = $action->name_event;
        $this->date_perfom = $action->date_perfom;
        $this->name_f = $action->name_f;
        $this->name_l = $action->name_l;
        $this->name_p = $action->name_p;
        $this->date_compl = $action->date_compl;
        $this->date_transfer = $action->date_transfer;
        $this->reasons_post = $action->reasons_post;
        $this->reasons_transfer = $action->reasons_transfer;
        $this->check_exe =$action->check_exe;
        $this->note = $action->note;
        $this->year_report = $action->year_report;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'reg_num_opo' => 'required',
            'name_event' => 'required',
            'date_perfom' => 'required',
            'name_f' => 'required',
            'name_l' => 'required',
            'name_p' => 'required',
            'date_compl' => 'required',
            'date_transfer' => 'required',
            'reasons_post' => 'required',
            'reasons_transfer' => 'required',
            'check_exe' => 'required',
            'note' => 'required',
            'year_report' => 'required',

        ]);

        if ($this->action_id) {
            $action = Action_plan_pb::find($this->action_id);
            $action->update([
                'reg_num_opo' => $this->reg_num_opo,
                'name_event' => $this->name_event,
                'date_perfom' => $this->date_perfom,
                'name_f' => $this->name_f,
                'name_l' => $this->name_l,
                'name_p' => $this->name_p,
                'date_compl' => $this->date_compl,
                'date_transfer' => $this->date_transfer,
                'reasons_post' => $this->reasons_post,
                'reasons_transfer' => $this->reasons_transfer,
                'check_exe' => $this->check_exe,
                'note' => $this->note,
                'year_report' => $this->year_report,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
        return redirect()->to('/');
    }


}
