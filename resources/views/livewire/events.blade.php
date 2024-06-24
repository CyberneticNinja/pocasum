<div>
    <!-- createEvent -->
    @if($isAdmin || $isGroupLeaderOfGroup)
        <button wire:click="createEvent"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Calendar Event
        </button>
    @endif

    <br/>
    <!-- Calendar -->
    <div wire:ignore>
        <div id='calendar'></div>
    </div>
    @if($showEventModal)
        <div x-data="{ open: @entangle('showEventModal'), deleteSeries: false }" x-show="open"
             style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg shadow-lg text-black">
                <h2 class="text-xl font-bold">Event Details</h2>
                @if($toggleEdit)
                    <p>Group Name: <span x-text="$wire.eventDetails.title"></span></p>
                @else
                    <p>Group Name: <span x-text="$wire.eventDetails.title"></span></p>
                    <p>Church Name: <span x-text="$wire.eventDetails.church_name"></span></p>
                    @if($rrule === 'No recurring rule set')
                        ONE TIME EVENT
                    @else
                        SERIES OF EVENTS
                    @endif
                @endif
                <p>Start: <span x-text="$wire.eventDetails.start"></span></p>
                <p>End: <span x-text="$wire.eventDetails.end"></span></p>
                <p>Comments: <span x-text="$wire.eventDetails.comments"></span></p>
                @if($isAdmin || $isGroupLeaderOfGroup)
                    <button wire:click="toggleEditMode"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">EDIT
                    </button>
                    @if($rrule !== 'No recurring rule set')
                        <button @click="deleteSeries = false; $wire.deleteEvent(deleteSeries)"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">DELETE CURRENT
                        </button>
                        <button @click="deleteSeries = true; $wire.deleteEvent(deleteSeries)"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">DELETE SERIES
                        </button>
                    @else
                        <button @click="deleteSeries = false; $wire.deleteEvent(deleteSeries)"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">DELETE CURRENT
                        </button>
                    @endif
                @endif
                <button @click="open = false; $wire.closeEventModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close
                </button>
            </div>
        </div>
    @endif
    @if($displayCreateEvent)
        <div x-data="{ open: @entangle('displayCreateEvent') }" x-show="open"
             style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg shadow-lg text-black">
                <h2 class="text-xl font-bold">Create Event</h2>
                <div>
                    <button wire:click="setOneTimeEvent(true)"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">One-Time Event
                    </button>
                    <button wire:click="setOneTimeEvent(false)"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Recurring Event
                    </button>
                </div>
                <button @click="open = false; $wire.displayCreateEvent = false"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mt-4">Close
                </button>
            </div>
        </div>
    @endif
    <div>
        <!-- Other content -->

        @if($displayOneTimeEventCreation)
            <div x-data="{ open: @entangle('displayOneTimeEventCreation') }" x-show="open"
                 style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg text-black">
                    <h2 class="text-xl font-bold">Create One Time Event</h2>

                    <div class="mb-4">
                        <label for="church" class="block text-gray-700 font-bold mb-2">Church:</label>
                        <select wire:model.live="newEventDetails.churchId" id="church" name="church"
                                class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <option value="0">Select Church</option>
                            @foreach ($churches as $church)
                                <option value="{{ $church->id }}">{{ $church->name }}</option>
                            @endforeach
                        </select>
                        {{--                        {{ $newEventDetails.churchId }}--}}
                        @error('newEventDetails.churchId')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @if($newEventDetails['churchId'] !== 0)
                        <div class="mb-4">
                            <label for="group" class="block text-gray-700 font-bold mb-2">Group:</label>
                            <select wire:model.live="newEventDetails.groupId" id="group" name="group"
                                    class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" {{ !$newEventDetails['churchId'] ? 'disabled' : '' }}>
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            {{--                            {{ $newEventDetails.groupId }}--}}
                            @error('newEventDetails.groupId')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    @if($newEventDetails['groupId'] !== 0)
                        <div class="mb-4">
                            <label for="event-date" class="block text-gray-700 font-bold mb-2">Event Date and
                                Time:</label>
                            <input type="datetime-local" id="event-date" wire:model.live="newEventDetails.eventDate"
                                   class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>
                            @error('newEventDetails.eventDate')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="duration" class="block text-gray-700 font-bold mb-2">Duration:</label>
                            <select wire:model.live="newEventDetails.eventDuration" id="duration" name="duration"
                                    class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="">Select Duration</option>
                                @for($minutes = 15; $minutes <= 360; $minutes += 15)
                                    <option value="{{ $minutes }}">
                                        {{ $minutes < 60 ? $minutes . ' minutes' : floor($minutes / 60) . ' hour' . (floor($minutes / 60) > 1 ? 's' : '') . ' ' . ($minutes % 60 > 0 ? ($minutes % 60) . ' minutes' : '') }}
                                    </option>
                                @endfor
                            </select>
                            @error('newEventDetails.eventDuration')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="mb-4">
                            <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                            <input type="text" id="address" wire:model.live="newEventDetails.eventAddress"
                                   class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>
                            @error('newEventDetails.eventAddress')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comments" class="block text-gray-700 font-bold mb-2">Comments:</label>
                            <textarea id="comments" wire:model.live="newEventDetails.eventComments" rows="4"
                                      class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                            @error('newEventDetails.eventComments')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button wire:click="oneTimeEventSave"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-gray-600 mt-4">
                            Submit
                        </button>
                    @endif
                    <button @click="open = false; $wire.displayOneTimeEventCreation = false"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mt-4">Close
                    </button>
                </div>
            </div>
        @endif
        @if($displayRecurringEventCreation)
            <div x-data="{ open: @entangle('displayRecurringEventCreation') }" x-show="open"
                 style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg text-black w-full max-w-4xl">
                    <h2 class="text-xl font-bold mb-4">Create Recurring Event</h2>
                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full md:w-1/2 px-2">
                            <div class="mb-4">
                                <label for="church" class="block text-gray-700 font-bold mb-2">Church:</label>
                                <select wire:model.live="newEventDetails.churchId" id="church" name="church"
                                        class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option value="0">Select Church</option>
                                    @foreach ($churches as $church)
                                        <option value="{{ $church->id }}">{{ $church->name }}</option>
                                    @endforeach
                                </select>
                                @error('newEventDetails.churchId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if($newEventDetails['churchId'] !== 0)
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="group" class="block text-gray-700 font-bold mb-2">Group:</label>
                                    <select wire:model.live="newEventDetails.groupId" id="group" name="group"
                                            class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('newEventDetails.groupId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        @if($newEventDetails['groupId'] !== 0)
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="event-date" class="block text-gray-700 font-bold mb-2">Event Date
                                        Start:</label>
                                    <input type="datetime-local" id="event-date"
                                           wire:model.live="newEventDetails.eventDate"
                                           class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>
                                    @error('newEventDetails.eventDate')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="duration" class="block text-gray-700 font-bold mb-2">Duration:</label>
                                    <select wire:model.live="newEventDetails.eventDuration" id="duration"
                                            name="duration"
                                            class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Select Duration</option>
                                        @for($minutes = 15; $minutes <= 360; $minutes += 15)
                                            <option value="{{ $minutes }}">
                                                {{ $minutes < 60 ? $minutes . ' minutes' : floor($minutes / 60) . ' hour' . (floor($minutes / 60) > 1 ? 's' : '') . ' ' . ($minutes % 60 > 0 ? ($minutes % 60) . ' minutes' : '') }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('newEventDetails.eventDuration')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                                    <input type="text" id="address" wire:model.live="newEventDetails.eventAddress"
                                           class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>
                                    @error('newEventDetails.eventAddress')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="comments" class="block text-gray-700 font-bold mb-2">Comments:</label>
                                    <textarea id="comments" wire:model.live="newEventDetails.eventComments" rows="4"
                                              class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                                    @error('newEventDetails.eventComments')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="event-date" class="block text-gray-700 font-bold mb-2">Event Date
                                        End:</label>
                                    <input type="datetime-local" id="event-date"
                                           wire:model.live="newEventDetails.eventDateEnd"
                                           class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"/>
                                    @error('newEventDetails.eventDateEnd')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2">
                                <div class="mb-4">
                                    <label for="recurrence"
                                           class="block text-gray-700 font-bold mb-2">Recurrence:</label>
                                    <select wire:model.live="newEventDetails.recurrenceFrequency" id="recurrence"
                                            name="recurrence"
                                            class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Select Recurrence</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                    {{--                                    {{ $newEventDetails['recurrenceFrequency'] }}--}}
                                    @error('newEventDetails.recurrenceFrequency')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                    @if($newEventDetails['recurrenceFrequency'] === 'weekly')
                                        <div class="flex flex-wrap">
                                            @php
                                                $daysOfWeek = ['SU' => 'Sunday', 'MO' => 'Monday', 'TU' => 'Tuesday', 'WE' => 'Wednesday', 'TH' => 'Thursday', 'FR' => 'Friday', 'SA' => 'Saturday'];
                                            @endphp
                                            @foreach($daysOfWeek as $key => $day)
                                                <div class="flex items-center mr-4 mb-2">
                                                    <input type="checkbox" id="day-{{ $key }}" value="{{ $key }}"
                                                           wire:model.live="newEventDetails.recurrence_days.{{ $key }}"
                                                           class="form-checkbox h-5 w-5 text-indigo-600">
                                                    <label for="day-{{ $key }}" class="ml-2">{{ $day }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mb-4">
                                            <label for="recurrence_interval" class="block text-gray-700 font-bold mb-2">Recurrence
                                                Interval:</label>
                                            <select wire:model.live="newEventDetails.recurrence_interval"
                                                    id="recurrence_interval" name="recurrence_interval"
                                                    class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                <option value="">Select Interval</option>
                                                <option value="1">Every Week</option>
                                                <option value="2">Every Other Week</option>
                                                @for($i = 3; $i <= 26; $i++)
                                                    <option value="{{ $i }}">Every {{ $i }}rd Week</option>
                                                @endfor
                                            </select>
                                            @error('newEventDetails.recurrence_interval')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif


                                    @if($newEventDetails['recurrenceFrequency'] === 'monthly')
                                        @php
                                            function ordinal($number) {
                                                $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
                                                if ((($number % 100) >= 11) && (($number % 100) <= 13))
                                                    return $number . 'th';
                                                else
                                                    return $number . $ends[$number % 10];
                                            }

                                            function dayName($abbreviation) {
                                                $days = [
                                                    'SU' => 'Sunday',
                                                    'MO' => 'Monday',
                                                    'TU' => 'Tuesday',
                                                    'WE' => 'Wednesday',
                                                    'TH' => 'Thursday',
                                                    'FR' => 'Friday',
                                                    'SA' => 'Saturday',
                                                ];
                                                return $days[$abbreviation];
                                            }
                                        @endphp
                                        <div class="w-full md:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="recurrence_interval_monthly"
                                                       class="block text-gray-700 font-bold mb-2">Monthly Recurrence
                                                    Interval:</label>
                                                <select wire:model.live="newEventDetails.recurrence_interval_monthly"
                                                        id="recurrence_interval_monthly"
                                                        name="recurrence_interval_monthly"
                                                        class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    <option value="">Select Interval</option>
                                                    <option value="1">Every Month</option>
                                                    <option value="2">Every Other Month</option>
                                                    @for($i = 3; $i <= 12; $i++)
                                                        <option value="{{ $i }}">Every {{ $i }}rd Month</option>
                                                    @endfor
                                                </select>
                                                @error('newEventDetails.recurrence_interval_monthly')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="w-full md:w-1/2 px-2">
                                            <div class="mb-4">
                                                <label for="specific_day_of_month"
                                                       class="block text-gray-700 font-bold mb-2">Specific Day of the
                                                    Month:</label>

                                                <select wire:model.live="newEventDetails.specificDayOfMonth"
                                                        id="specific_day_of_month" name="specific_day_of_month"
                                                        class="border rounded-md w-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    <option value="">Select Specific Day</option>
                                                    @foreach(['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'] as $day)
                                                        @for($i = 1; $i <= 4; $i++)
                                                            @if(isset($specificDayOfMonth[$i .$day]) == 1)
                                                                <option value="{{ $i . $day }}">
                                                                    Every {{ ordinal($i) }} {{ $day }} (selected)
                                                                </option>
                                                            @else
                                                                <option value="{{ $i . $day }}">
                                                                    Every {{ ordinal($i) }} {{ $day }} </option>
                                                            @endif
                                                        @endfor
                                                        @if(isset($specificDayOfMonth['-1'.$day]) == 1)
                                                            <option value="-1{{ $day }}">Last {{ $day }} (selected) </option>
                                                        @else
                                                            <option value="-1{{ $day }}">Last {{ $day }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                                @error('newEventDetails.specific_day_of_month')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    @endif

                                </div>
                            </div>
                        @endif
                    </div>
                    @if($newEventDetails['groupId'] !== 0)
                        <div class="flex justify-end mt-4">
                            <button wire:click="reccuringEventSave"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Submit
                            </button>
                            <button @click="open = false; $wire.displayOneTimeEventCreation = false"
                                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">Close
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var events = @json($this->events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                <!-- TODO this needs to come from the .env -->
                timeZone: 'America/New_York',
                events: events,
                eventClick: function (info) {
                    console.log(info.event.extendedProps)
                    const eventDetails = {
                        id: info.event.id,
                        title: info.event.title,
                        start: info.event.start.toLocaleString(),
                        end: info.event.end ? info.event.end.toLocaleString() : null,
                        comments: info.event.extendedProps.comments,
                        church_id: info.event.extendedProps.church_id,
                        church_name: info.event.extendedProps.church_name,
                    };

                    Livewire.dispatch('calendar-event-clicked', {
                        eventDetails
                    })
                }
            });
            calendar.render();
        });
    </script>
@endpush
