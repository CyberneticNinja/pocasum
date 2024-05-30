<?php

namespace App\Livewire;

use App\Models\Church;
use App\Models\Group;
use App\Models\GroupEvent;
use DateTime;
use Livewire\Attributes\On;
use Livewire\Component;

class Events extends Component
{

    public array $newEventDetails = [
        'groupId' => 0,
        'churchId' => 0,
        'eventDuration' => '',
        'eventDate' => '',
        'eventDateEnd'=>'',
        'eventAddress' => '',
        'eventComments' => '',
        'recurrenceFrequency' => '',
        'recurrenceDays' => '',
        'specificDayOfMonth' => [],
        'recurrence_interval_monthly' => '',
    ];

    public array $specificDayOfMonth =[];
    public bool $oneTimeEvent = true;
    public int $selectedChurchId = 0;
    public int $selectedGroupId = 0;
    public $selectedChurch = '';
    public $selectedGroup = '';
    public array $events = [];
    public string $rrule = '';
    public bool $showEventModal = false;
    public bool $toggleEdit = false;
    public bool $isAdmin = false;
    public bool $isGroupLeader = false;
    public bool $isGroupLeaderOfGroup = false;
    public bool $displayCreateEvent = false;
    public bool $displayOneTimeEventCreation = false;
    public bool $displayRecurringEventCreation = false;
    public array $eventDetails = [];
    public array $newEvents = [];
    public \Illuminate\Database\Eloquent\Collection $churches;
    public \Illuminate\Database\Eloquent\Collection $groups;

    private $all;

    public function mount()
    {
        $this->isGroupLeader = auth()->user()->hasRole('group-leader');
        $this->isAdmin = auth()->user()->hasRole('admin');
        $this->churches = Church::all();
        $this->selectedChurch = $this->churches->first()->id ?? null;
        $this->loadEvents();
    }

    public function oneTimeEventRules()
    {
        return [
            'newEventDetails.groupId' => 'required|integer|exists:groups,id',
            'newEventDetails.churchId' => 'required|integer|exists:churches,id',
            'newEventDetails.eventDuration' => 'required|string',
            'newEventDetails.eventDate' => 'required|date',
            'newEventDetails.eventAddress' => 'required|string',
        ];
    }

    public function recurringEventRules()
    {
        return [
            'newEventDetails.groupId' => 'required|integer|exists:groups,id',
            'newEventDetails.churchId' => 'required|integer|exists:churches,id',
            'newEventDetails.eventDuration' => 'required|string',
            'newEventDetails.eventDate' => 'required|date',
            'newEventDetails.eventDateEnd' => 'required|date|after:newEventDetails.eventDate',
            'newEventDetails.eventAddress' => 'required|string',
            'newEventDetails.recurrenceFrequency' => 'required|string'
        ];
    }


    public function updatedNewEventDetailsSpecificDayOfMonth($value)
    {
        if($value !== "")
        {
            if (isset($this->specificDayOfMonth[$value])) {
                unset($this->specificDayOfMonth[$value]);
            } else {
                $this->specificDayOfMonth[$value] = 1;
            }
        }
    }

    public function updatedNewEventDetailsRecurrenceFrequency($value)
    {
        $this->newEvents['recurrenceFrequency'] = (string) $value;
    }

    public function updatedNewEventDetailsGroupId($value)
    {
        $this->newEvents['churchId'] = (int) $value;
//        $this->loadGroups();
    }
    public function updatedNewEventDetailsChurchId($value)
    {
        $this->newEventDetails['churchId'] = (int) $value;
        $this->loadGroups();
    }

    public function getFormattedHoursMinSecs($value)
    {
        $hours = floor($value / 60);
        $minutes = $value % 60;
        $seconds = 0;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    }
    public function updatedNewEventDetailsEventDuration($value)
    {
//        $hours = floor($value / 60);
//        $minutes = $value % 60;
//        $seconds = 0;
//
//        $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
////        $this->newEventDetails['eventDuration'] = $formattedDuration;
        $this->newEventDetails['eventDuration'] = $value;
    }

    /**
     * @throws \Exception
     */
    public function updatedNewEventDetailsEventDate($value)
    {
        $dateTime = new DateTime($value);
        $this->newEventDetails['eventDate'] = $dateTime->format('Y-m-d H:i:s');
        // Perform additional actions if needed
    }

    public function updatedNewEventDetailsEventDateEnd($value)
    {
        $dateTime = new DateTime($value);
        $this->newEventDetails['eventDateEnd'] = $dateTime->format('Y-m-d H:i:s');
        // Perform additional actions if needed
    }

    public function updatedNewEventDetailsEventAddress($value)
    {
        $this->newEventDetails['eventAddress'] = $value;
        // Perform additional actions if needed
    }

    public function updatedNewEventDetailsEventComments($value)
    {
        $this->newEventDetails['eventComments'] = $value;
        // Perform additional actions if needed
    }
    public function loadGroups()
    {
        if ($this->newEventDetails['churchId']) {
            $this->groups = Group::where('church_id', $this->newEventDetails['churchId'])->get();
        } else {
            $this->groups = Group::all();
        }
    }

    public function setOneTimeEvent(bool $value): void
    {
        if($this->oneTimeEvent !== $value)
        {
            $this->resetNewEventDetails();
        }
        $this->oneTimeEvent = $value;

        if($value)
        {
            $this->displayOneTimeEventCreation = true;
            $this->displayRecurringEventCreation = false;
        }
        else
        {
            $this->displayOneTimeEventCreation = false;
            $this->displayRecurringEventCreation = true;
            $this->newEvents['recurrenceFrequency'] = "";

//            dd($this->newEvents);
        }
        $this->showEventModal = false;
        $this->displayCreateEvent = false;
        $this->all = Group::all();
        $this->groups = $this->all;
    }

    public function oneTimeEventSave()
    {
        if ($this->oneTimeEvent) {
            $this->rules = $this->oneTimeEventRules();
        } else {
            $this->rules = $this->recurringEventRules();
        }

        $this->validate(); // Triggers validation based on selected rules

        $event = new GroupEvent();
        $event->location_address = $this->newEventDetails['eventAddress'];
        $event->user_id = auth()->user()->id;
        $event->group_id = $this->newEventDetails['groupId'];
        $group = Group::findOrFail($this->newEventDetails['groupId']);
        $event->start_time = $this->newEventDetails['eventDate'];
        $event->color = $group->color;
        $event->duration = $this->getFormattedHoursMinSecs($this->newEventDetails['eventDuration']);
        // Convert duration from HH:MM:SS to seconds
        $durationInSeconds = strtotime('1970-01-01 ' . $this->newEventDetails['eventDuration']) - strtotime('1970-01-01 00:00:00');

        // Calculate finish_time by adding duration to start_time
        $finishTime = strtotime($this->newEventDetails['eventDate']) + $durationInSeconds;
        $event->finish_time = date('Y-m-d H:i:s', $finishTime);

        $event->save();
    }

    public function reccuringEventSave()
    {
        $this->rules = $this->recurringEventRules();
        $this->validate();

        $event = new GroupEvent();
        $event->location_address = $this->newEventDetails['eventAddress'];
        $event->user_id = auth()->user()->id;
        $event->group_id = $this->newEventDetails['groupId'];
        $event->start_time = $this->newEventDetails['eventDate'];
        $group = Group::findOrFail($this->newEventDetails['groupId']);
        $event->color = $group->color;
        $event->duration = $this->getFormattedHoursMinSecs($this->newEventDetails['eventDuration']);        // Convert duration from HH:MM:SS to seconds
        $durationInSeconds = strtotime('1970-01-01 ' . $this->newEventDetails['eventDuration']) - strtotime('1970-01-01 00:00:00');

        // Calculate finish_time by adding duration to start_time
        $finishTime = strtotime($this->newEventDetails['eventDate']) + $durationInSeconds;
        $event->finish_time = date('Y-m-d H:i:s', $finishTime);

        $rrule = '';
        if($this->newEventDetails['recurrenceFrequency'] === 'daily')
        {
            $eventDate = new \DateTime($this->newEventDetails['eventDate']);
            $eventDateEnd = new \DateTime($this->newEventDetails['eventDateEnd']);
            $event->rrule = 'DTSTART:'.$eventDate->format('Ymd\THis\Z')."\nFREQ=DAILY;INTERVAL=1;UNTIL=".$eventDateEnd->format('Ymd\THis\Z');
        }
        $event->save();
    }

    protected function resetNewEventDetails()
    {
        $this->newEventDetails = [
            'groupId' => 0,
            'churchId' => 0,
            'eventDuration' => '',
            'eventDate'=>'',
            'eventAddress' =>'',
            'eventComments'=>'',
            'recurrenceFrequency'=>'',
            'recurrenceDays'=>''
        ];
    }
    #[On('calendar-event-clicked')]
    public function calendarEventClicked($eventDetails)
    {
        $this->rrule = $this->getEventRRule($eventDetails['id']);
        $this->toggleEdit = false;
        $this->eventDetails = $eventDetails;
        $eventId = $this->eventDetails['id'];
        $event = GroupEvent::with('group')->findOrFail($eventId);

        if ($this->isGroupLeader) {
            $userGroupIds = auth()->user()->groups->pluck('id')->toArray();
            if (in_array($event->group_id, $userGroupIds)) {
                $this->isGroupLeaderOfGroup = true;
            } else {
                $this->isGroupLeaderOfGroup = false;
            }
        }
        $this->dispatch('refreshCalendar');
        $this->showEventModal = true;
    }

    public function createEvent()
    {
        $this->displayCreateEvent = true;
    }

    public function saveEventDetails(): void
    {
        $this->validate([
            'eventDetails.title' => 'required|string|max:255',
            'eventDetails.comments' => 'nullable|string|max:500',
        ]);

        $event = GroupEvent::findOrFail($this->eventDetails['id']);
        $event->title = $this->eventDetails['title'];
        $event->comments = $this->eventDetails['comments'];
        $event->save();

        $this->showEventModal = false;
    }

    public function toggleEditMode(): void
    {
        $this->toggleEdit = !$this->toggleEdit;
    }

    public function closeEventModal(): void
    {
        $this->showEventModal = false;
    }

    public function loadEvents()
    {
        $this->events = GroupEvent::with('group.church')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->group->name,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->finish_time ? $event->finish_time->toIso8601String() : null,
                'color' => $event->color,
                'church_name' => $event->group->church->name,
                'church_id' => $event->group->church->id,
                'rrule' => $event->rrule,
                'duration' => $event->duration,
                'comments' => $event->comments
            ];
        })->toArray();
    }

    private function getEventRRule($eventId)
    {
        foreach ($this->events as $event) {
            if ($event['id'] == $eventId) {
                return $event['rrule'] ?? 'No recurring rule set';  // Provide a default message if rrule is not set
            }
        }
        return 'Event not found';
    }

    public function deleteEvent($deleteSeries): void
    {
        $eventId = $this->eventDetails['id'];
        $event = GroupEvent::findOrFail($eventId);

        if ($deleteSeries) {
            // Delete the entire series
            $event->delete();
        } else {
            // Delete a single occurrence
            if ($event->rrule == null) {
                $event->delete();
            } else {
                // If the event is recurring
                $rrule = $event->rrule;

                // Get the DTSTART from rrule
                preg_match('/DTSTART:(\d{8}T\d{6}Z)/', $rrule, $matches);
                $dtstart = $matches[1] ?? null;

                if ($dtstart) {
                    $dtstartDateTime = \DateTime::createFromFormat('Ymd\THis\Z', $dtstart);
                    $dtstartTime = $dtstartDateTime->format('H:i:s\Z');

                    // Get the date part from the occurrence start time
                    $occurrenceDate = new \DateTime($this->eventDetails['start']);
                    $exdate = $occurrenceDate->format('Ymd\T') . $dtstartTime;

                    // Remove colons from exdate
                    $exdate = str_replace(':', '', $exdate);

                    if (str_contains($rrule, 'EXDATE')) {
                        // If EXDATE already exists, append the new date
                        $rrule = preg_replace_callback('/(EXDATE:)([^\n]*)/', function ($matches) use ($exdate) {
                            return $matches[1] . $matches[2] . ',' . $exdate;
                        }, $rrule);
                    } else {
                        // If EXDATE does not exist, add it
                        $rrule .= "\nEXDATE:$exdate";
                    }
                }

                $event->rrule = $rrule;
                $event->save();
            }
        }

        $this->showEventModal = false;
        $this->dispatch('refreshCalendar');
    }

    public function render()
    {
        return view('livewire.events');
    }
}
