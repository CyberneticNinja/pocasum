<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- rrule lib -->
    <script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>

    <!-- fullcalendar bundle -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <!-- the rrule-to-fullcalendar connector. must go AFTER the rrule lib -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@6.1.11/index.global.min.js'></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <div id='calendar'></div>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($events);
            var formattedEvents = events.map(event => {
            return {
                duration: event.duration,
                title: event.name,
                start: event.start_time,
                end: event.finish_time,
                color: event.color,
                rrule: event.rrule,
                extendedProps: {
                    comments: event.comments  // This ensures comments are available in extendedProps
                }
            };
        });
            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
                timeZone: 'America/New_York',
                slotLabelInterval: '01:00:00',
            eventDisplay: 'auto',
            events: formattedEvents,
                eventClick: function(info) {
                    const eventObj = info.event;
                    const startDate = eventObj.start ? eventObj.start.toLocaleDateString() : 'No start date';
                    const endDate = eventObj.end ? eventObj.end.toLocaleDateString() : 'No end date';
                    const comments = eventObj.extendedProps.comments ? eventObj.extendedProps.comments : 'No comments'; // Accessing comments

                    // Display event details including comments
                    alert('Event: ' + eventObj.title + '\nStart: ' + startDate + '\nEnd: ' + endDate + '\nComments: ' + comments);
                }
        });
            calendar.render();
        });
    </script>

</div>
</body>
</html>
