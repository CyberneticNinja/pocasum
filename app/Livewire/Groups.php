<?php

namespace App\Livewire;

use App\Models\Church;
use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\GroupLeader;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Groups extends Component
{
    public bool $tableSummaryDisplay;
    public bool $managedGroupDisplay;
    public bool $deleteDisplay;
    public bool $createDisplay;
    public $selectedColor = '#000000';

    public $color = '#283194';
    public $darkColors = [
        'black' => [
            'name' => 'Black',
            'hex' => '#000000',
        ],
        'navy' => [
            'name' => 'Navy',
            'hex' => '#000080',
        ],
        'dark-slate-blue' => [
            'name' => 'Dark Slate Blue',
            'hex' => '#483D8B',
        ],
        'midnight-blue' => [
            'name' => 'Midnight Blue',
            'hex' => '#191970',
        ],
        'maroon' => [
            'name' => 'Maroon',
            'hex' => '#800000',
        ],
        'purple' => [
            'name' => 'Purple',
            'hex' => '#800080',
        ],
        'indigo' => [
            'name' => 'Indigo',
            'hex' => '#4B0082',
        ],
        'dark-green' => [
            'name' => 'Dark Green',
            'hex' => '#006400',
        ],
        'forest-green' => [
            'name' => 'Forest Green',
            'hex' => '#228B22',
        ],
        'teal' => [
            'name' => 'Teal',
            'hex' => '#008080',
        ],
        'dark-red' => [
            'name' => 'Dark Red',
            'hex' => '#8B0000',
        ],
        'brown' => [
            'name' => 'Brown',
            'hex' => '#A52A2A',
        ],
        'gray-dark' => [
            'name' => 'Gray (Dark)',
            'hex' => '#4F4F4F',
        ],
        'charcoal' => [
            'name' => 'Charcoal',
            'hex' => '#36454C',
        ],
        'black-coffee' => [
            'name' => 'Black Coffee',
            'hex' => '#3B2F2F',
        ],
        'orange' => [ // Additional color
            'name' => 'Orange',
            'hex' => '#FF5733',
        ],
        'teal-light' => [ // Additional color
            'name' => 'Teal Light',
            'hex' => '#4CAF50',
        ],
        'amber' => [ // Additional color
            'name' => 'Amber',
            'hex' => '#FFC107',
        ],
        'light-blue' => [ // Additional color
            'name' => 'Light Blue',
            'hex' => '#00BCD4',
        ],
        'deep-purple' => [ // Additional color
            'name' => 'Deep Purple',
            'hex' => '#9C27B0',
        ],
        'red' => [ // Additional color
            'name' => 'Red',
            'hex' => '#F44336',
        ],
        'indigo-dark' => [ // Additional color
            'name' => 'Indigo Dark',
            'hex' => '#3F51B5',
        ],
        'yellow' => [ // Additional color
            'name' => 'Yellow',
            'hex' => '#FFEB3B',
        ],
    ];

    public $selectedGroup = array();
    public $selectedChurchId;
    public $selectedGroupModel;
    public $groups;
    public $churches;
    public $name;
    public $group_user;
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
        $this->group_user = GroupUser::all();
    }
    public function userJoinedGroup($groupId)
    {
        return$exists = GroupUser::where('group_id', $groupId)
            ->where('user_id', Auth::user()->id)
            ->exists();
    }

    public function leaveGroup($groupId)
    {
        $groupUser = GroupUser::where('group_id', $groupId)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($groupUser) {
            $groupUser->delete();
        }
    }

    public function joinGroup($groupId)
    {
        $alreadyJoined = GroupUser::where('group_id', $groupId)
            ->where('user_id', Auth::user()->id)
            ->exists();

        if (!$alreadyJoined) {
            GroupUser::create([
                'group_id' => $groupId,
                'user_id' => Auth::id(),
            ]);
        }
    }

    public function createGroup()
    {
        $this->tableSummaryDisplay = false;
        $this->managedGroupDisplay = false;
        $this->deleteDisplay = false;
        $this->createDisplay = true;
        $this->selectedChurchId = 1;
//        dd($this->selectedChurchId);
    }

    public function createNewGroup()
    {
        $this->validate();
        $newGroup = new Group();
        $newGroup->name = $this->selectedGroup['name'];
        $newGroup->color = $this->selectedColor;
        $newGroup->church_id = $this->selectedChurchId;
        $newGroup->save();

        $this->tableSummaryDisplay = true;
        $this->managedGroupDisplay = false;
        $this->deleteDisplay = false;
        $this->createDisplay = false;
    }

    public function manageGroup(Group $group)
    {
        $this->tableSummaryDisplay = false;
        $this->managedGroupDisplay = true;
        $this->selectedGroup['name'] = $group->name;
        $this->selectedColor = $group->color;
        $this->selectedChurchId = $group->church_id;
        $this->selectedGroupModel = $group;
    }

    public function saveGroup()
    {
        $this->validate();
        $this->selectedGroupModel->name = $this->selectedGroup['name'];
        $this->selectedGroupModel->church_id = $this->selectedChurchId;
        $this->selectedGroupModel->color = $this->selectedColor;

//        $eventWithGroupId = GroupEvent::with('group')
//            ->where('group_id', '=', $this->selectedGroupModel->id)
//            ->get();

//        foreach ($eventWithGroupId as $event) {
//            $event->color = $this->selectedColor;
//        }

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
            $this->selectedColor = $group->color;
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
