<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Report_worker;
use Livewire\Component;
use Livewire\WithPagination;

class ReportWorker extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $surname;
    public $name;
    public $sub_name;
    public $post;
    public $education;
    public $work_exp;
    public $last_attestation;
    public $responsibility;
    public $report_w_id;


    public function render()
    {
        return view('livewire.rtn.report-worker', ['rows'=>Report_worker::orderby('id')->get()]);
    }
    private function resetInputFields(){
        $this->surname= '';
        $this->name= '';
        $this->sub_name= '';
        $this->post= '';
        $this->education= '';
        $this->work_exp= '';
        $this->last_attestation= '';
        $this->responsibility= '';
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'surname' => 'required',
            'name' => 'required',
            'sub_name' => 'required',
            'post' => 'required',
            'education' => 'required',
            'work_exp' => 'required',
            'last_attestation' => 'required',
            'responsibility' => 'required',
        ]);
        Report_worker::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }
    public function delete($id)
    {
        if($id){
            Report_worker::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $report = Report_worker::where('id',$id)->first();
        $this->report_w_id = $id;
        $this->surname = $report->surname;
        $this->name = $report->name;
        $this->sub_name = $report->sub_name;
        $this->post = $report->post;
        $this->education = $report->education;
        $this->work_exp = $report->work_exp;
        $this->last_attestation = $report->last_attestation;
        $this->responsibility = $report->responsibility;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'surname' => 'required',
            'name' => 'required',
            'sub_name' => 'required',
            'post' => 'required',
            'education' => 'required',
            'work_exp' => 'required',
            'last_attestation' => 'required',
            'responsibility' => 'required',

        ]);

        if ($this-> report_w_id) {
            $report = Report_worker::find($this-> report_w_id);
            $report ->update([
                'surname' => $this->surname,
                'name' => $this->name,
                'sub_name' => $this->sub_name,
                'post' => $this->post,
                'education' => $this->education,
                'work_exp' => $this->work_exp,
                'last_attestation' => $this->last_attestation,
                'responsibility' => $this->responsibility,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
    }

}
