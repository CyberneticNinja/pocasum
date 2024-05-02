<?php

namespace App\Livewire;

use App\Models\Church;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Churches extends Component
{
    public $churches;
    public bool $confirmingDelete = false;
    public $churchToDelete = null;
    public bool $tableSummary = true;
    public bool $showForm = false;  // Controls whether the form is shown
    public bool $editMode = false;  // When true, the component is in "edit" mode
    public bool $deleteMode = false;  // When true, the component is in "delete" mode
    public bool $createForm = false;  // When true, the component is in "create" mode
    public $currentChurch = null;  // Stores the current church being edited or viewed

    public array $churchInfo = [];

    public function mount()
    {
        // Check if the user is not authenticated
        if (!Auth::check()) {
            // Redirect to the home page
            return redirect()->route('home-page');
        }
        $this->isAdmin = auth()->user()->hasRole('admin'); // Check if user is admin
        $this->churches = Church::all(); // Fetch all churches
    }

    public function render()
    {
        return view('livewire.churches');
    }

    public function editChurch(Church $church)
    {
        $this->tableSummary = false;
        $this->createForm = false;
        $this->editMode = true;
        $this->deleteMode = false;
        $this->currentChurch = $church;

        $this->churchInfo['name'] = $this->currentChurch->name;
        $this->churchInfo['description'] = $this->currentChurch->description;
    }

    public function createChurch()
    {
        $this->tableSummary = false;
        $this->createForm = true;
        $this->editMode = false;
        $this->deleteMode = false;
        $this->currentChurch = new Church();  // Creating a new instance for binding
    }

    public function saveChurch()
    {
        if ($this->editMode) {
            $this->validate($this->getValidationRules('edit'));
            $this->currentChurch->name = $this->churchInfo['name'];
            $this->currentChurch->description = $this->churchInfo['description'];
            $this->currentChurch->save();
            $this->resetForm();
            $this->churches = Church::all(); // Fetch all churches
            $this->churchInfo = [];
        } elseif ($this->createForm) {
            //        $this->currentChurch->name = 'Love-Joy church';
//        $churchInfo = [];
            $this->validate($this->getValidationRules('create'));
//        dd($this->churchInfo['description']);
            $this->currentChurch->name = $this->churchInfo['name'];
            $this->currentChurch->description = $this->churchInfo['description'];
            $this->currentChurch->save();
            $this->resetForm();
            $this->churches = Church::all(); // Fetch all churches
            $this->churchInfo = [];
        }
    }

    public function deleteChurch(Church $church)
    {
        $this->tableSummary = false;
        $this->createForm = false;
        $this->editMode = false;
        $this->deleteMode = true;
        $this->currentChurch = $church;
//        dd($this->currentChurch);
//        $church->delete();
//        $this->resetForm();
//        $this->churches = Church::all(); // Refresh the churches list
    }
    public function confirmDelete(Church $church)
    {
        $this->churchToDelete = $church;
        $this->confirmingDelete = true;
        $this->churchToDelete->delete();
        $this->resetForm();;
    }
    protected function getValidationRules(string $type)
    {
        if ($type === 'create') {
            return [
                'churchInfo.name' => 'required|string|max:255|unique:churches,name',
                'churchInfo.description' => 'required|string'
            ];
        } elseif ($type === 'edit') {
            $churchId = $this->currentChurch->id;

            return [
                'churchInfo.name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('churches', 'name')->ignore($churchId)
                ],
                'churchInfo.description' => 'required|string'
            ];
        }
    }

    public function resetForm()
    {
        $this->tableSummary = true;
        $this->showForm = false;
        $this->editMode = false;
        $this->deleteMode = false;
        $this->createForm = false;
        $this->currentChurch = null;
        $this->churches = Church::all();
    }
}
