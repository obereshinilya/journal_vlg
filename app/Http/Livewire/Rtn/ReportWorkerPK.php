<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Report_worker_pk;
use Livewire\Component;
use Livewire\WithPagination;

class ReportWorkerPK extends Component
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
    public $report_pk_id;

    public function render()
    {
        return view('livewire.rtn.report-worker-p-k', ['rows'=>Report_worker_pk::orderby('id')->get()]);
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
        Report_worker_pk::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }
    public function delete($id)
    {
        if($id){
            Report_worker_pk::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $report_pk = Report_worker_pk::where('id',$id)->first();
        $this->report_pk_id = $id;
        $this->surname = $report_pk->surname;
        $this->name = $report_pk->name;
        $this->sub_name = $report_pk->sub_name;
        $this->post = $report_pk->post;
        $this->education = $report_pk->education;
        $this->work_exp = $report_pk->work_exp;
        $this->last_attestation = $report_pk->last_attestation;
        $this->responsibility = $report_pk->responsibility;

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

        if ($this-> report_pk_id) {
            $report_pk = Report_worker_pk::find($this-> report_pk_id);
            $report_pk ->update([
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
