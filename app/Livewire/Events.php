<?php

namespace App\Livewire;

use App\Models\Church;
use App\Models\Group;
use App\Models\GroupEvent;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\On;
use Livewire\Component;
use RRule\RRule;
use PHPUnit\Event\Event;

class Events extends Component
{

    public array $newEventDetails = [
        'groupId' => 0,
        'churchId' => 0,
        'eventDuration' => '',
        'eventDate' => '',
        'eventDateEnd' => '',
        'eventAddress' => '',
        'eventComments' => '',
        'recurrenceFrequency' => '',
        'recurrenceDays' => '',
        'specificDayOfMonth' => [],
        'recurrence_interval_monthly' => '',
    ];

    public array $specificDayOfMonth = [];
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
    public bool $editDisplay = false;
    public $isEventClickedReccuring = '';
    public bool $displayOneTimeEventCreation = false;
    public bool $displayRecurringEventCreation = false;
    public array $eventDetails = [];
    public array $editEventDetails = [

    ];
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

    public function oneTimeEventEditRules()
    {
        return [
            'editEventDetails.groupId' => 'required|integer|exists:groups,id',
            'editEventDetails.church_id' => 'required|integer|exists:churches,id',
            'editEventDetails.duration' => 'required|string',
            'editEventDetails.start' => 'required|date',
            'editEventDetails.location_address' => 'required|string',
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

    public function recurringEditEventRules()
    {
        return [
            'editEventDetails.groupId' => 'required|integer|exists:groups,id',
            'editEventDetails.church_id' => 'required|integer|exists:churches,id',
            'editEventDetails.duration' => 'required|integer',
            'editEventDetails.start' => 'required|date',
            'editEventDetails.location_address' => 'required|string',
        ];
    }

    public function updatedEditEventDetailsSpecificDayOfMonth($value)
    {
        if ($value !== "")//remove
        {
            if (isset($this->specificDayOfMonth[$value]) == 1) {
                unset($this->specificDayOfMonth[$value]);
            } else {
                $this->specificDayOfMonth[$value] = 1;
//                dd($value);
            }
//            if($value == '-1SU')
//            {
//                dd($this->specificDayOfMonth);
//            }
        }
    }
    public function updatedNewEventDetailsSpecificDayOfMonth($value)
    {
        if ($value !== "")//remove
        {
            if (isset($this->specificDayOfMonth[$value]) == 1) {
                unset($this->specificDayOfMonth[$value]);
            } else {
                $this->specificDayOfMonth[$value] = 1;
//                dd($value);
            }
//            if($value == '-1SU')
//            {
//                dd($this->specificDayOfMonth);
//            }
        }
    }

    public function updatedNewEventDetailsRecurrenceFrequency($value)
    {
        $this->newEvents['recurrenceFrequency'] = (string)$value;
    }

    public function updatedNewEventDetailsGroupId($value)
    {
        $this->newEvents['churchId'] = (int)$value;
//        $this->loadGroups();
    }

    public function updatedNewEventDetailsChurchId($value)
    {
        $this->newEventDetails['churchId'] = (int)$value;
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


    /* edited event duration */

    public function updatedEditEventDetailsEventDuration($value)
    {
        $this->editEventDetails['eventDuration'] = $value;
    }

    /**
     * @throws \Exception
     */
    public function updatedEditEventDetailsEventDate($value)
    {
        $dateTime = new DateTime($value);
        $this->editEventDetails['eventDate'] = $dateTime->format('Y-m-d H:i:s');
        // Perform additional actions if needed
    }

    public function updatedEditEventDetailsEventDateEnd($value)
    {
        $dateTime = new DateTime($value);
        $this->editEventDetails['eventDateEnd'] = $dateTime->format('Y-m-d H:i:s');
        // Perform additional actions if needed
    }

    public function updatedEditEventDetailsEventAddress($value)
    {
        $this->editEventDetails['eventAddress'] = $value;
        // Perform additional actions if needed
    }

    public function updatedEditEventDetailsEventComments($value)
    {
        $this->editEventDetails['eventComments'] = $value;
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
        if ($this->oneTimeEvent !== $value) {
            $this->resetNewEventDetails();
        }
        $this->oneTimeEvent = $value;

        if ($value) {
            $this->displayOneTimeEventCreation = true;
            $this->displayRecurringEventCreation = false;
        } else {
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

    public function updatedEditEventDetailsGroupId($value)
    {
        $this->editEventDetails['churchId'] = (int)$value;
    }

    public function updatedEditEventDetailsChurchId($value)
    {
        $this->editEventDetails['churchId'] = (int)$value;
        if ($this->editEventDetails['churchId']) {
            $this->groups = Group::where('church_id', $this->editEventDetails['churchId'])->get();
        } else {
            $this->groups = Group::all();
        }
    }

    /* edit recurring event*/
    public function recurringEventEdit()
    {
        $event = GroupEvent::where('id','=',$this->editEventDetails['id'])->first();
        if($event->rrule)
        {
            $groupOfEvent = Group::where('id','=',$event->group_id)->first();

            $this->rules = $this->recurringEditEventRules();
            $this->validate();
            $event->color = $groupOfEvent->color;
            $event->comments = $this->editEventDetails['comments'];
            $event->location_address = $this->editEventDetails['location_address'];
            $event->start_time = $this->editEventDetails['start'];
            $event->user_id = auth()->user()->id;
            $event->duration = $this->getFormattedHoursMinSecs($this->editEventDetails['duration']);


            // Convert duration from HH:MM:SS to seconds
            $durationInSeconds = strtotime('1970-01-01 ' . $this->editEventDetails['duration']) - strtotime('1970-01-01 00:00:00');

            // Assuming $this->editEventDetails['start_time_date'] is a string in ISO 8601 format
            $start_time = Carbon::parse($this->editEventDetails['start_time_date']);

            // Assuming $this->editEventDetails['duration'] is an integer representing minutes
            $duration = intval($this->editEventDetails['duration']);

            // Calculate finish time by adding the duration to the start time
            $finish_time = $start_time->addMinutes($duration);

            // Set the finish time to the event
            $event->finish_time = $finish_time->toDateTimeString();
            //$event->finish_time = date('Y-m-d H:i:s', $finish_time);


            if ($this->editEventDetails['recurrenceFrequency'] === 'daily') {
                $eventDate = new \DateTime($this->editEventDetails['start_time']);
                $eventDateEnd = new \DateTime($this->editEventDetails['end_time_date']);
                $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=DAILY;INTERVAL=1;UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
            } elseif ($this->editEventDetails['recurrenceFrequency'] === 'weekly') {
                $eventDate = new \DateTime($this->editEventDetails['start_time_date']);
                $eventDateEnd = new \DateTime($this->editEventDetails['end_time_date']);


                $byDay = 'BYDAY=';
                foreach ($this->editEventDetails['recurrence_days'] as $day => $value) {
                    if ($value == 1) {
                        $byDay = $byDay . '' . $day . ',';
                    }
                }
                $byDay = substr($byDay, 0, -1);
                $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=WEEKLY;INTERVAL=" . $this->editEventDetails['recurrence_interval'] . ";" . $byDay . ";UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
            } elseif ($this->editEventDetails['recurrenceFrequency'] === 'monthly') {
                $eventDate = new \DateTime($this->editEventDetails['start_time_date']);
                $eventDateEnd = new \DateTime($this->editEventDetails['end_time_date']);

                $byDay = 'BYDAY=';
                foreach ($this->specificDayOfMonth as $day => $value) {
                    if ($value == 1) {
                        $byDay = $byDay . '' . $day . ',';
                    }
                }
                $byDay = substr($byDay, 0, -1);
                $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=MONTHLY;INTERVAL=" . $this->editEventDetails['recurrence_interval_monthly'] . ";" . $byDay . ";UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
            }

            $event->save();
            return to_route('calendar');
        }
    }

    public function oneTimeEventEdit()
    {
        $editedGroup = null;
        foreach ($this->groups as $group) {
            $editedGroup = $group;
        }
        if (!isset($editedGroup->rrule)) {
            $this->rules = $this->oneTimeEventEditRules();
            $this->validate();
            $event = GroupEvent::find($this->editEventDetails['id']);
            $event->location_address = $this->editEventDetails['location_address'];
            $event->user_id = auth()->user()->id;
            $event->group_id = $this->editEventDetails['groupId'];
            $group = Group::findOrFail($this->editEventDetails['groupId']);
            $event->start_time = $this->editEventDetails['start'];
            $event->color = $group->color;
//            dd($this->editEventDetails['duration']);
            $event->duration = $this->getFormattedHoursMinSecs($this->editEventDetails['duration']);
            // Convert duration from HH:MM:SS to seconds
            $durationInSeconds = strtotime('1970-01-01 ' . $this->editEventDetails['duration']) - strtotime('1970-01-01 00:00:00');

            // Calculate finish_time by adding duration to start_time
            $finishTime = strtotime($this->editEventDetails['start']) + $durationInSeconds;
            $event->finish_time = date('Y-m-d H:i:s', $finishTime);

            $event->save();
            return to_route('calendar');
        }
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
        return to_route('calendar');
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
        if ($this->newEventDetails['recurrenceFrequency'] === 'daily') {
            $eventDate = new \DateTime($this->newEventDetails['eventDate']);
            $eventDateEnd = new \DateTime($this->newEventDetails['eventDateEnd']);
            $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=DAILY;INTERVAL=1;UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
        } elseif ($this->newEventDetails['recurrenceFrequency'] === 'weekly') {
            $eventDate = new \DateTime($this->newEventDetails['eventDate']);
            $eventDateEnd = new \DateTime($this->newEventDetails['eventDateEnd']);
            $byDay = 'BYDAY=';
            foreach ($this->newEventDetails['recurrence_days'] as $day => $value) {
                if ($value == 1) {
                    $byDay = $byDay . '' . $day . ',';
                }
            }
            $byDay = substr($byDay, 0, -1);
            $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=WEEKLY;INTERVAL=" . $this->newEventDetails['recurrence_interval'] . ";" . $byDay . ";UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
        } elseif ($this->newEventDetails['recurrenceFrequency'] === 'monthly') {
            $eventDate = new \DateTime($this->newEventDetails['eventDate']);
            $eventDateEnd = new \DateTime($this->newEventDetails['eventDateEnd']);

            $byDay = 'BYDAY=';
            foreach ($this->specificDayOfMonth as $day => $value) {
                if ($value == 1) {
                    $byDay = $byDay . '' . $day . ',';
                }
            }
            $byDay = substr($byDay, 0, -1);
            $event->rrule = 'DTSTART:' . $eventDate->format('Ymd\THis\Z') . "\nFREQ=MONTHLY;INTERVAL=" . $this->newEventDetails['recurrence_interval_monthly'] . ";" . $byDay . ";UNTIL=" . $eventDateEnd->format('Ymd\THis\Z');
        }
        $event->save();

        return to_route('calendar');
    }

    protected function resetNewEventDetails()
    {
        $this->newEventDetails = [
            'groupId' => 0,
            'churchId' => 0,
            'eventDuration' => '',
            'eventDate' => '',
            'eventAddress' => '',
            'eventComments' => '',
            'recurrenceFrequency' => '',
            'recurrenceDays' => ''
        ];
    }

    #[On('calendar-event-clicked')]
    public function calendarEventClicked($eventDetails)
    {
        $this->isEventClickedReccuring = $this->getEventRRule($eventDetails['id']);
//        dd($this->isEventClickedReccuring);
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

    public function editClicked($value)
    {
        if ($value) {
            $this->editEventDetails = $this->eventDetails;
            $groupEvent = GroupEvent::find($this->eventDetails['id']);
            if ($groupEvent) {
                $this->editEventDetails['comments'] = $groupEvent->comments;
                $this->editEventDetails['location_address'] = $groupEvent->location_address;
                $this->editEventDetails['start_time_date'] = $this->convertToDateTimeLocal($groupEvent->start_time);
                $this->editEventDetails['duration'] = $groupEvent->duration;
                $this->editEventDetails['churchId'] = 0;
                $this->editEventDetails['rrule'] = $groupEvent->rrule;
                $this->editEventDetails['end_time_date'] = '';
            }

            $pattern = '/UNTIL=([0-9T]+Z?)/';
            if (preg_match($pattern, $this->editEventDetails['rrule'], $matches)) {
                $untilDate = $matches[1];
                $dateTime = DateTime::createFromFormat('Ymd\THis\Z', $untilDate);
                $this->editEventDetails['readable_rrule'] = $this->rruleToReadableString($this->editEventDetails['rrule']);

                if ($dateTime !== false) {

                    $this->editEventDetails['end_time_date'] = $dateTime->format('Y-m-d\TH:i');
                }
            }

            $this->editEventDetails['recurrenceFrequency'] = '';
            $this->editEventDetails['recurrenceDays'] = '';
            $this->editEventDetails['specificDayOfMonth'] = '';

            $this->editEventDetails['duration'] = $this->convertToMinutes($this->editEventDetails['duration']);
            $this->editEventDetails['groupId'] = 0;
            $this->showEventModal = false;
            $this->editDisplay = true;
        }
    }

    protected function convertToDateTimeLocal($datetime): string
    {
        return Carbon::parse($datetime)->format('Y-m-d\TH:i');
    }

    public function openEditModal()
    {
        $this->editDisplay = true;
    }

    public function closeEditModal()
    {
        $this->editDisplay = false;
    }

    function convertToMinutes($timeString)
    {
        // Split the time string by comma to handle multiple time strings
        $timeStrings = explode(', ', $timeString);
        $totalMinutes = 0;

        foreach ($timeStrings as $time) {
            // Check if the time string matches the HH:MM:SS pattern
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                // Split the individual time string into hours, minutes, and seconds
                list($hours, $minutes, $seconds) = explode(':', $time);

                // Convert hours and seconds to minutes
                $hoursInMinutes = $hours * 60;
                $secondsInMinutes = $seconds / 60;

                // Sum all minutes
                $totalMinutes += $hoursInMinutes + $minutes + $secondsInMinutes;
            } else {
                // Handle invalid time format, here we simply skip it
                echo "Invalid time format: $time\n";
            }
        }

        return $totalMinutes;
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

    /**
     * @param $deleteSeries
     * @return RedirectResponse
     * @throws \Exception
     */
    public function deleteEvent($deleteSeries)
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
        return to_route('calendar');
    }

    public function rruleToReadableString($rruleString) {

        $cleanedRruleString = $this->removeExdate($rruleString);

        try {
            $rrule = new RRule($cleanedRruleString);
            return $rrule->humanReadable();
        } catch (Exception $e) {
          return ('Invalid rrule string: ' . $e->getMessage());
        }
    }
    protected function removeExdate($rruleString) {
        // Split the string into lines
        $lines = explode("\n", $rruleString);
        $filteredLines = [];
        $dtstart = '';

        // Process each line
        foreach ($lines as $line) {
            if (strpos(trim($line), 'EXDATE') === 0) {
                continue; // Skip EXDATE lines
            } elseif (strpos(trim($line), 'DTSTART') === 0) {
                $dtstart = trim($line); // Capture DTSTART line
            } else {
                $filteredLines[] = trim($line); // Capture other lines
            }
        }

        // Join the filtered lines back into a single string, removing any empty lines
        $rrulePart = implode("\n", array_filter($filteredLines));

        // Ensure RRULE: precedes FREQ
        if (strpos($rrulePart, 'FREQ=') === 0) {
            $rrulePart = 'RRULE:' . $rrulePart;
        }

        // Combine DTSTART and RRULE parts
        return $dtstart . "\n" . $rrulePart;
    }

    public function render()
    {
        return view('livewire.events');
    }
}
