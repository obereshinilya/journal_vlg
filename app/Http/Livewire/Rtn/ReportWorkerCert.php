<?php

namespace App\Http\Livewire\Rtn;

use App\Models\Rtn\Report_worker_certification;
use Livewire\Component;
use Livewire\WithPagination;

class ReportWorkerCert extends Component
{
    use WithPagination;
    public $search = '';
    public $users;
    public $type_super;
    public $manager;
    public $special;
    public $worker;
    public $all_work;
    public $w_cert_id;

    public function render()
    {
        return view('livewire.rtn.report-worker-cert', ['rows'=>Report_worker_certification::orderby('id')->get()]);
    }
    private function resetInputFields(){
        $this->type_super = '';
        $this->manager = '';
        $this->special = '';
        $this->worker = '';
        $this->all_work = '';

    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'type_super' => 'required',
            'manager' => 'required',
            'special' => 'required',
            'worker' => 'required',
            'all_work' => 'required',
        ]);
        Report_worker_certification::create($validatedDate);
        $this->resetInputFields();
        return redirect()->to('/docs/events');

    }
    public function delete($id)
    {
        if($id){
            Report_worker_certification::where('id',$id)->delete();
            session()->flash('message', 'Data Deleted Successfully.');
        }
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $w_cert = Report_worker_certification::where('id',$id)->first();
        $this-> w_cert_id = $id;
        $this->type_super = $w_cert->type_super;
        $this->manager = $w_cert->manager;
        $this->special = $w_cert->special;
        $this->worker = $w_cert->worker;
        $this->all_work = $w_cert->all_work;

    }
    public function update()
    {
        $validatedDate = $this->validate([
            'type_super' => 'required',
            'manager' => 'required',
            'special' => 'required',
            'worker' => 'required',
            'all_work' => 'required',

        ]);

        if ($this-> w_cert_id) {
            $w_cert = Report_worker_certification::find($this-> w_cert_id);
            $w_cert ->update([
                'type_super' => $this->type_super,
                'manager' => $this->manager,
                'special' => $this->special,
                'worker' => $this->worker,
                'all_work' => $this->all_work,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Data Updated Successfully.');
            $this->resetInputFields();
            return redirect()->to('/docs/events');
        }
    }
}
