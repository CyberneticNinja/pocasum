<?php

namespace App\Livewire;

use App\Models\Church;
use App\Models\Group;
use App\Models\GroupLeader;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Groups extends Component
{
    public bool $tableSummaryDisplay;
    public bool $managedGroupDisplay;
    public bool $deleteDisplay;
    public $selectedGroup = array();
    public $selectedChurchId;
    public $selectedGroupModel;
    public $groups;
    public $churches;
    public $name;
    public $description;
    public $groupIdBeingManaged = null;

    public function mount()
    {
        if (!Auth::check()) {
            // Redirect to the home page
            return redirect()->route('home-page');
        }
        $this->tableSummaryDisplay = true;
        $this->managedGroupDisplay = false;
        $this->churches = Church::all();
//        $this->fetchGroups();
    }

    public function manageGroup(Group $group)
    {
        $this->tableSummaryDisplay = false;
        $this->managedGroupDisplay = true;
        $this->selectedGroup['name'] = $group->name;
        $this->selectedChurchId = $group->church_id;
        $this->selectedGroupModel = $group;
    }

    public function saveGroup()
    {
        $this->validate();
        $this->selectedGroupModel->name = $this->selectedGroup['name'];
        $this->selectedGroupModel->church_id = $this->selectedChurchId;
        $this->selectedGroupModel->save();
        $this->tableSummaryDisplay = true;
        $this->managedGroupDisplay = false;
    }

    protected $rules = [
        'selectedChurchId' => 'required|exists:churches,id',
        'selectedGroup.name' => 'required|string|max:255',
    ];

    public function deleteGroup()
    {
        $this->tableSummaryDisplay = false;
        $this->managedGroupDisplay = false;
        $this->deleteDisplay = true;
    }

    public function render()
    {
        return view('livewire.groups');
    }

    public function editGroup($groupId)
    {
        if ($this->canManageGroup($groupId)) {
            $group = Group::findOrFail($groupId);
            $this->groupIdBeingManaged = $group->id;
            $this->name = $group->name;
            $this->description = $group->description;
        } else {
            session()->flash('error', 'Unauthorized to manage this group.');
        }
    }

    public function confirmDelete()
    {
        $this->selectedGroupModel->delete();
        $this->resetViews();
    }

    public function cancelDelete()
    {
        // Reset views
        $this->resetViews();
    }

    public function resetViews()
    {
        $this->tableSummaryDisplay = true;
        $this->managedGroupDisplay = false;
        $this->deleteDisplay = false;
    }
//    public function deleteGroup($groupId)
//    {
//        if ($this->canManageGroup($groupId)) {
//            Group::destroy($groupId);
//            $this->fetchGroups();
//            session()->flash('message', 'Group deleted successfully.');
//        } else {
//            session()->flash('error', 'Unauthorized to delete this group.');
//        }
//    }

    public function canManageGroup($groupId)
    {
        if (Auth::user()->hasRole('admin')) {
            return true;
        } elseif (Auth::user()->hasRole('group-leader')) {
            return GroupLeader::where('user_id', Auth::id())->where('group_id', $groupId)->exists();
        }
        return false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->description = null;
        $this->groupIdBeingManaged = null;
    }
}
