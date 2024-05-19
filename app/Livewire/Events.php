<?php

namespace App\Livewire;

use App\Models\GroupEvent;
use Livewire\Attributes\On;
use Livewire\Component;

class Events extends Component
{
    public array $events = [];
    public bool $showEventModal = false;
    public string $stringDate;
    public string $endDater;
    public array $eventDetails = [];
    public function mount()
    {
        $this->loadEvents();
    }

    #[On('calendar-event-clicked')]

    public function calendarEventClicked($eventDetails)
    {
        $this->eventDetails = $eventDetails;
//        $this->loadEvents();
//        dd($start.' '.$end.' '.$id.' '.$comments);
        $this->dispatch('refreshCalendar');
        $this->showEventModal = true;
        // Additional handling code here
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
                'title' => $event->group->name . ' (' . $event->group->church->name . ')',
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->finish_time ? $event->finish_time->toIso8601String() : null,
                'color' => $event->color,
                'rrule' => $event->rrule,
                'duration' => $event->duration,
                'comments' => $event->comments
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.events');
    }
}
