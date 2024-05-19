{{--<div wire:init="loadEvents">--}}
<div>
    <div wire:ignore>
    <div id='calendar'></div>
    </div>
    <div x-data="{ open: @entangle('showEventModal') }" x-show="open" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg text-black">
            <h2 class="text-xl font-bold">Event Details</h2>
            <p>Title: <span x-text="$wire.eventDetails.title"></span></p>
            <p>Start: <span x-text="$wire.eventDetails.start"></span></p>
            <p>End: <span x-text="$wire.eventDetails.end"></span></p>
            <p>Comments: <span x-text="$wire.eventDetails.comments"></span></p>
            <button @click="open = false; $wire.closeEventModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Close</button>
        </div>
    </div>
</div>
{{--</div>--}}
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
                    const eventDetails = {
                        id:info.event.id,
                        title:info.event.title,
                        start: info.event.start.toLocaleString(),
                        end: info.event.end ? info.event.end.toLocaleString() : null,
                        comments:info.event.extendedProps.comments
                    };
                    console.log('Livewire:', typeof Livewire); // Should not be 'undefined'
                    // window.dispatchEvent(new CustomEvent('calendarEventClicked', {
                    //     detail: eventDetails
                    // }));
                    // Livewire.emit('calendarEventClicked', eventDetails);
                    Livewire.dispatch('calendar-event-clicked',{
                        eventDetails
                    })

                }
            });
            calendar.render();

            // Listen for Livewire event to refresh the calendar
            window.addEventListener('refreshCalendar', function () {
                console.log('Received refreshCalendar event');
                console.log('Current events before refetch:', calendar.getEvents());
                // console.log('Events before refresh:', calendar.getEvents());
                calendar.refetchEvents();
                // Give it a moment to re-fetch and render
                setTimeout(() => {
                    console.log('Current events after refetch:', calendar.getEvents());
                    if (calendar.getEvents().length === 0) {
                        console.error('No events found after refetch.');
                    }
                    else
                    {
                        console.log('event found')
                    }
                }, 1000);  // Adjust timeout as necessary based on expected fetch duration
                // console.log('Events after refresh:', calendar.getEvents());
            });
        });
        // document.addEventListener('DOMContentLoaded', function () {
            {{--var calendarEl = document.getElementById('calendar');--}}
            {{--var events = @json($this->events);--}}
            {{--var calendar = new FullCalendar.Calendar(calendarEl, {--}}
            {{--    initialView: 'timeGridWeek',--}}
            {{--    timeZone: 'America/New_York',--}}
            {{--    events: events,--}}
            {{--    eventClick: function(info) {--}}
            {{--        const eventDetails = {--}}
            {{--            start: info.event.start.toISOString(),--}}
            {{--            end: info.event.end ? info.event.end.toISOString() : null--}}
            {{--        };--}}
            {{--        console.log('Livewire:', typeof Livewire); // Should not be 'undefined'--}}
            {{--        // window.dispatchEvent(new CustomEvent('calendarEventClicked', {--}}
            {{--        //     detail: eventDetails--}}
            {{--        // }));--}}
            {{--        // Livewire.emit('calendarEventClicked', eventDetails);--}}
            {{--        Livewire.dispatch('calendar-event-clicked',{--}}
            {{--            id: info.event.id,--}}
            {{--            start:info.event.start.toISOString(),--}}
            {{--            end:info.event.end.toISOString(),--}}
            {{--            comments: info.event.extendedProps.comments--}}
            {{--        })--}}

            {{--    }--}}
            {{--});--}}
            // calendar.render();
            // window.addEventListener('refreshCalendar', function () {
            //     console.log('refresh')
            //     calendar.refetchEvents(); // This makes FullCalendar refetch the event sources
            // });
        // });
    </script>
@endpush
{{--@endpush('scripts')--}}
