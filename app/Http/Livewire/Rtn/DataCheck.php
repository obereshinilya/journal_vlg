<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Data_check;
use Livewire\Component;
use Livewire\WithPagination;

class DataCheck extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $number_work;
    public $tick_stand;
    public $rating_work;
    public $rating_reasons;
    public $reg_num_reasons;
    public $quant_lesson_work;
    public $quant_lesson_alarms;
    public $plan_lesson_work;
    public $plan_lesson_crash;
    public $plan_lesson_alarms;
    public $plan_next_alarms;
    public $size_works;
    public $year_report;
    public $reg_num_opo;
    public $check_id;


    public function render()
    {
        return view('livewire.rtn.data-check', ['rows'=>\App\Models\Rtn\Data_check::orderby('id')->get()]);
    }

    private function resetInputFields(){
        $this->number_work = '';
        $this->tick_stand = '';
        $this->rating_work = '';
        $this->rating_reasons = '';
        $this->reg_num_reasons = '';
        $this->quant_lesson_work = '';
        $this->quant_lesson_alarms = '';
        $this->plan_lesson_work = '';
        $this->plan_lesson_crash = '';
        $this->plan_lesson_alarms = '';
        $this->plan_next_alarms = '';
        $this->size_works = '';
        $this->year_report = '';
        $this->reg_num_opo = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'number_work' => 'required',
            'tick_stand' => 'required',
            'rating_work' => 'required',
            'rating_reasons' => 'required',
            'reg_num_reasons' => 'required',
            'quant_lesson_work' => 'required',
            'quant_lesson_alarms' => 'required',
            'plan_lesson_work' => 'required',
            'plan_lesson_crash' => 'required',
            'plan_lesson_alarms' => 'required',
            'plan_next_alarms' => 'required',
            'size_works' => 'required',
            'year_report' => 'required',
            'reg_num_opo' => 'required',

        ]);
        Data_check::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');
    }
    public function delete($id)
    {
        if($id){
            Data_check::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
//        $this->updateMode = true;
        $check= Data_check::where('id',$id)->first();
        $this->check_id = $id;
        $this->number_work = $check->number_work;
        $this->tick_stand = $check->tick_stand;
        $this->rating_work = $check->rating_work;
        $this->rating_reasons = $check->rating_reasons;
        $this->reg_num_reasons = $check->reg_num_reasons;
        $this->quant_lesson_work = $check->quant_lesson_work;
        $this->quant_lesson_alarms = $check->quant_lesson_alarms;
        $this->plan_lesson_work = $check->plan_lesson_work;
        $this->plan_lesson_crash = $check->plan_lesson_crash;
        $this->plan_lesson_alarms = $check->plan_lesson_alarms;
        $this->plan_next_alarms = $check->plan_next_alarms;
        $this->size_works = $check->size_works;
        $this->year_report = $check->year_report;
        $this->reg_num_opo = $check->reg_num_opo;

    }
    public function update()
    {
//        $validatedDate = $this->validate([
//            'number_work'=> 'required',
//            'tick_stand'=> 'required',
//            'rating_work'=> 'required',
//            'rating_reasons'=> 'required',
//            'reg_num_reasons'=> 'required',
//            'quant_lesson_work'=> 'required',
//            'quant_lesson_alarms'=> 'required',
//            'plan_lesson_work'=> 'required',
//            'plan_lesson_crash'=> 'required',
//            'plan_lesson_alarms'=> 'required',
//            'plan_next_alarms'=> 'required',
//            'size_works'=> 'required',
//            'year_report'=> 'required',
//            'reg_num_opo'=> 'required',
//
//        ]);

//        if ($this->check_id) {
            $check = Data_check::find($this->check_id);
            $check->update([
                'number_work' => $this->number_work,
                'tick_stand' => $this->tick_stand,
                'rating_work' => $this->rating_work,
                'rating_reasons' => $this->rating_reasons,
                'reg_num_reasons' => $this->reg_num_reasons,
                'quant_lesson_work' => $this->quant_lesson_work,
                'quant_lesson_alarms' => $this->quant_lesson_alarms,
                'plan_lesson_work' => $this->plan_lesson_work,
                'plan_lesson_crash' => $this->plan_lesson_crash,
                'plan_lesson_alarms' => $this->plan_lesson_alarms,
                'plan_next_alarms' => $this->plan_next_alarms,
                'size_works' => $this->size_works,
                'year_report' => $this->year_report,
                'reg_num_opo' => $this->reg_num_opo,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/rtn');
//        }
    }
}
