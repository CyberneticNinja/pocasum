<div>
    <div wire:ignore>
        <div id='calendar'></div>
    </div>
    <div x-data="{ open: @entangle('showEventModal'), deleteSeries: false }" x-show="open" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg text-black">
            <h2 class="text-xl font-bold">Event Details</h2>
            @if($toggleEdit)
                <p>Group Name: <span x-text="$wire.eventDetails.title"></span></p>
            @else
                <p>Group Name: <span x-text="$wire.eventDetails.title"></span></p>
                <p>Church Name: <span x-text="$wire.eventDetails.church_name"></span></p>
                @if($rrule === 'No recurring rule set')
                    One time event
                @else
                    Change Recurrence
                @endif
            @endif
            <p>Start: <span x-text="$wire.eventDetails.start"></span></p>
            <p>End: <span x-text="$wire.eventDetails.end"></span></p>
            <p>Comments: <span x-text="$wire.eventDetails.comments"></span></p>
            @if($isAdmin || $isGroupLeaderOfGroup)
                <button wire:click="toggleEditMode" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">EDIT</button>
                <div x-show="{{ $rrule !== 'No recurring rule set' }}">
                    <button @click="deleteSeries = false; $wire.deleteEvent(deleteSeries)" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">DELETE CURRENT</button>
                    <button @click="deleteSeries = true; $wire.deleteEvent(deleteSeries)" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">DELETE SERIES</button>
                </div>
            @endif
            <button @click="open = false; $wire.closeEventModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var events = @json($this->events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                timeZone: 'America/New_York',
                events: events,
                eventClick: function(info) {
                    console.log(info.event.extendedProps)
                    const eventDetails = {
                        id:info.event.id,
                        title:info.event.title,
                        start: info.event.start.toLocaleString(),
                        end: info.event.end ? info.event.end.toLocaleString() : null,
                        comments:info.event.extendedProps.comments,
                        church_id: info.event.extendedProps.church_id,
                        church_name: info.event.extendedProps.church_name,
                    };

                    Livewire.dispatch('calendar-event-clicked',{
                        eventDetails
                    })
                }
            });
            calendar.render();
        });
    </script>
@endpush
