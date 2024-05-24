<?php

namespace App\Livewire;

use App\Models\Church;
use App\Models\GroupEvent;
use Livewire\Attributes\On;
use Livewire\Component;

class Events extends Component
{
    public array $events = [];
    public string $rrule = ''; // Add this line
    public bool $showEventModal = false;
    public bool $toggleEdit = false;
    public bool $isAdmin = false;
    public bool $isGroupLeader = false;
    public bool $isGroupLeaderOfGroup = false;
    public array $eventDetails = [];
    public  \Illuminate\Database\Eloquent\Collection $churches ;
    public function mount()
    {
        $this->isGroupLeader = auth()->user()->hasRole('group-leader');
        $this->isAdmin = auth()->user()->hasRole('admin');
        $this->churches = Church::all();
        $this->loadEvents();
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
            // Check if the current group leader is linked to the event's group
            $userGroupIds = auth()->user()->groups->pluck('id')->toArray(); // Get all group IDs associated with the user
            if (in_array($event->group_id, $userGroupIds)) {
                $this->isGroupLeaderOfGroup = true;
            }
            else
            {
                $this->isGroupLeaderOfGroup = false;
            }
        }
        $this->dispatch('refreshCalendar');
        $this->showEventModal = true;
        // Additional handling code here
    }
    public function saveEventDetails()
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
//        $this->emit('refreshCalendar');  // Optional: refresh the calendar to show the updated details
    }

    public function toggleEditMode()
    {
        $this->toggleEdit = !$this->toggleEdit;
    }
    public function closeEventModal()
    {
        $this->showEventModal = false;
    }
    /**
     * @return void
     */
    public function loadEvents()
    {
        $this->events = GroupEvent::with('group.church')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->group->name,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->finish_time ? $event->finish_time->toIso8601String() : null,
                'color' => $event->color,
                'church_name' =>$event->group->church->name,
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
//    public function deleteEvent($deleteSeries)
//    {
//        $eventId = $this->eventDetails['id'];
//        $event = GroupEvent::findOrFail($eventId);
//
//        if ($deleteSeries) {
//            // Delete the entire series
//            $event->delete();
//        } else {
//            // Delete a single occurrence
//            if($event->rrule == null)
//            {
//                $event->delete();
//            }
//            else
//            {
//                //if is a recurring
//                $rrule = $event->rrule;
//                $exdateLine = '';
//
//                // Check if EXDATE already exists
//                if (str_contains($rrule, 'EXDATE')) {
//                    // Add new date to the existing EXDATE line
//                    $rrule = preg_replace_callback('/(EXDATE:)([^\n]*)/', function ($matches) use (&$exdateLine) {
//                        $exdateLine = $matches[1] . $matches[2] . ',' . (new \DateTime($this->eventDetails['start']))->format('Ymd\THis\Z');
//                        return $exdateLine;
//                    }, $rrule);
//
//                    dd($event->id.' . This exception is not new '.$rrule);
//
//                }else {
//                    //there is no exdate
//
//                    // Get the existing rrule
//                    $rrule = $event->rrule;
//
//                    // Get the DTSTART from rrule
//                    preg_match('/DTSTART:(\d{8}T\d{6}Z)/', $rrule, $matches);
//                    $dtstart = $matches[1] ?? null;
//
//                    if ($dtstart) {
//                        $dtstartDateTime = \DateTime::createFromFormat('Ymd\THis\Z', $dtstart);
//                        $dtstartTime = $dtstartDateTime->format('H:i:s\Z');
//
//                        // Get the date part from the occurrence start time
//                        $occurrenceDate = new \DateTime($this->eventDetails['start']);
//                        $exdate = $occurrenceDate->format('Ymd\T') . $dtstartTime;
//
//                        $exdate = str_replace(':', '', $exdate);
//
//                        // Check if EXDATE already exists in rrule
//                        if (strpos($rrule, 'EXDATE') !== false) {
//                            // Append new EXDATE to the existing EXDATE line
//                            $rrule = preg_replace_callback('/(EXDATE:)([^\n]*)/', function ($matches) use ($exdate) {
//                                return $matches[1] . $matches[2] . ',' . $exdate;
//                            }, $rrule);
//                        } else {
//                            // Add new EXDATE line at the beginning
//                            $exdateLine = 'EXDATE:' . $exdate . "\n";
//                            $rrule = $exdateLine . $rrule;
//                        }
//
//                        // Ensure there are no extra newline characters
//                        $rrule = str_replace('\\n', "\n", $rrule);
//                        $rrule = str_replace('\n', "\n", $rrule);
//
//
//                        // Save the updated rrule to the event
//                        $event->rrule = $rrule;
//                        $event->save();
//                    }
//                }
//            }
//
//                //If there is no exdate, male it,
//
//                //If there is an exdate add to it
//
//            //not a recurring event
//
//                //delete based on id
//
//
//            // Parse existing EXDATE field if it exists
////            $rrule = json_decode($event->rrule, true);
////
////            $exdates = [];
////            if (isset($rrule['EXDATE'])) {
////                $exdates = explode(',', $rrule['EXDATE']);
////            }
////
////            // Add the new exdate
////            $exdates[] = $exdateString;
////            $rrule['EXDATE'] = implode(',', $exdates);
////
////            // Update the event's rrule field
////            $event->rrule = json_encode($rrule);
////            dd($event->rrule);
////            $event->save();
//        }
//
//        $this->showEventModal = false;
////        $this->loadEvents(); // Reload events to reflect changes
////        $this->dispatch('calendar-refresh');
//    }

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
                        // Add new EXDATE line at the beginning
                        $exdateLine = 'EXDATE:' . $exdate . "\n";
                        $rrule = $exdateLine . $rrule;
                    }

                    // Ensure there are no extra newline characters
                    $rrule = str_replace(['\\n', '\n'], "\n", $rrule);

                    // Save the updated rrule to the event
                    $event->rrule = $rrule;
                    $event->save();
                }
            }
        }

        $this->showEventModal = false;
        // $this->loadEvents(); // Reload events to reflect changes
        // $this->dispatch('calendar-refresh');
    }

    public function render()
    {
        return view('livewire.events');
    }
}
