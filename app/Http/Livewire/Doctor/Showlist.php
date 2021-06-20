<?php

namespace App\Http\Livewire\Doctor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Doctor;
use App\Models\Specialty;

class Showlist extends Component
{
	use WithPagination;

	protected $queryString = [
		'search' => ['except' => ''], 
		'perPage' => ['except' => '5']
	];

	//Propiedades de la lista
	public $search;
	public $perPage;
	public $isOpen;

	//Propiedades del formulario
	public $id_doctor;
	public $name;
	public $document;
	public $id_specialty;
	public $specialties;

	public function mount(){
		$this->fill([
			'search' => '',
			'perPage' => '5',
			'isOpen' => false,
			'specialties' => Specialty::get()
		]);
	}

    public function render()
    {
    	$doctors = Doctor::where('name', 'like', "%$this->search%")
		->orWhere('document', 'like', "%$this->search%")
		->orWhere(function($query){
			$query->whereHas('specialty', function($query){
				$query->where('name', 'like', "%$this->search%");
			});
		})->paginate($this->perPage);
        return view('livewire.doctor.list', ['doctors' => $doctors]);
    }

    public function clearFilter(){
    	$this->reset(['search','page','perPage']);
    }

    public function openModal(){
		$this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->reset(['id_doctor', 'name','id_specialty','document']);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255',
            'id_specialty' => 'required|numeric|exists:App\Models\Specialty,id',
            'document' => 'required|numeric',
        ]);
        $data = array(
            'name' => $this->name,
            'id_specialty' => $this->id_specialty,
            'document' => $this->document
        );
        $doctor = Doctor::updateOrCreate(['id' => $this->id_doctor], $data);
        session()->flash('message', $this->id_doctor ? 'Doctor actualizado correctamente.' : 'Doctor creado correctamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $this->id_doctor = $id;
        $this->name = $doctor->name;
        $this->id_specialty = $doctor->id_specialty;
        $this->document = $doctor->document;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->id_doctor = $id;

        $doctor = Doctor::find($id);
        $doctor->appointments()->delete();
        $doctor->delete();
        session()->flash('message', 'Doctor eliminado correctamente.');
    }

}
