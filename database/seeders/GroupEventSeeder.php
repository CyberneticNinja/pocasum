<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupEventSeeder extends Seeder
{
    private function getRandomStartTime()
    {
        $morningStart = Carbon::parse('today 7:30 AM', 'America/New_York');
        $morningEnd = Carbon::parse('today 9:00 AM', 'America/New_York');
        $eveningStart = Carbon::parse('today 5:00 PM', 'America/New_York');
        $eveningEnd = Carbon::parse('today 7:30 PM', 'America/New_York');

        // Choose a random time in either the morning or evening
        if (rand(0, 1)) {
            return Carbon::createFromTimestamp(rand($morningStart->timestamp, $morningEnd->timestamp), 'America/New_York');
        } else {
            return Carbon::createFromTimestamp(rand($eveningStart->timestamp, $eveningEnd->timestamp), 'America/New_York');
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = Group::all(); // Fetch all groups
        $gCount = 0;
        $groupLocations = [
            "123 Main St, Anytown, USA",
            "456 Elm St, Springville, USA",
            "789 Oak St, Lakeview, USA",
            "101 Pine St, Ridgewood, USA",
            "202 Maple St, Eastwood, USA",
            "303 Birch St, Westgate, USA",
            "404 Cedar St, Southbank, USA",
            "505 Walnut St, Northshore, USA",
            "606 Ash St, Hillcrest, USA",
            "707 Cherry St, Sunnyside, USA",
            "808 Poplar St, Brightwood, USA",
            "909 Alder St, Moonville, USA",
            "1010 Spruce St, Starcity, USA",
            "1111 Fir St, Rivertown, USA",
            "1212 Linden St, Silverlake, USA",
            "1313 Willow St, Stonebridge, USA"
        ];
        $rruleArray = [
            "DTSTART:20240515T083000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=1FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T090000Z\nFREQ=WEEKLY;INTERVAL=2;BYDAY=SU;UNTIL=20241231T000000Z",
            "DTSTART:20240515T093000Z\nFREQ=WEEKLY;INTERVAL=2;BYDAY=TH;UNTIL=20241231T000000Z",
            "EXDATE:20240522T100000Z,20240529T100000Z\nDTSTART:20240515T100000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=WE;UNTIL=20241231T000000Z",
            "DTSTART:20240515T130000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T090000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=1FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T130000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=-1SA;UNTIL=20241231T000000Z",
            "DTSTART:20240515T150000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=1SU;UNTIL=20241231T000000Z",
            "DTSTART:20240515T083000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=1WE;UNTIL=20241231T000000Z",
            "DTSTART:20240515T153000Z\nFREQ=MONTHLY;INTERVAL=1;BYDAY=1FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T153000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T140000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=TH,FR;UNTIL=20241231T000000Z",
            "DTSTART:20240515T130000Z\nFREQ=WEEKLY;INTERVAL=2;BYDAY=SU;UNTIL=20241231T000000Z",
            "DTSTART:20240515T150000Z\nFREQ=WEEKLY;INTERVAL=2;BYDAY=TH;UNTIL=20241231T000000Z",
            "EXDATE:20240626T123000Z,20240807T123000Z\nDTSTART:20240515T123000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=WE;UNTIL=20241231T000000Z",
            "DTSTART:20240515T160000Z\nFREQ=WEEKLY;INTERVAL=1;BYDAY=FR;UNTIL=20241231T000000Z"
        ];

        $duration = [
            '00:30:00',
            '01:00:00',
            '00:30:00',
            '01:00:00',
            '00:30:00',
            '01:30:00',
            '01:00:00',
            '01:00:00',
            '01:00:00',
            '00:30:00',
            '00:30:00',
            '02:00:00',
            '02:00:00',
            '00:30:00',
            '01:00:00',
            '01:00:00',
        ];



        $index = 0;

        foreach ($groups as $group) {
            // Assume each group has at least one leader and we take the first one
            $leader = $group->groupLeaders()->first()->user;

            $startTime = new Carbon('first saturday of January 2024 7:30 AM', 'America/New_York');
            $finishTime = new Carbon('first saturday of January 2024 9:30 AM', 'America/New_York');

           // Create the event
            GroupEvent::create([
                'start_time' => $startTime,
                'finish_time' => $finishTime,
                'location_address' => $groupLocations[$gCount],
                'user_id' => $leader->id, // ID of the group leader
                'group_id' => $group->id,
                'color' => $group->color, // Assuming color is set at the group level
                'rrule' => $rruleArray[$index],
                'duration' => $duration[$index]
            ]);
            $index++;
            $gCount++;
        }
        //Put it here user id 22, group 15 at 8:30 - 9:30 am, the comments should have cancelled event
        // Create a non-recurring event on June 26, 2024

        $nonRecurringStartTime = Carbon::create(2024, 6, 26, 8, 30, 0, 'America/New_York');
        $nonRecurringFinishTime = Carbon::create(2024, 6, 26, 9, 30, 0, 'America/New_York');

//        $nonRecurringStartTime->setTimezone('America/New_York');
//        $nonRecurringFinishTime->setTimezone('America/New_York');

        GroupEvent::create([
            'start_time' => $nonRecurringStartTime,
            'finish_time' => $nonRecurringFinishTime,
            'location_address' => 'Specific location for this event',
            'user_id' => 22, // Specified user id
            'group_id' => 15, // Specified group id
            'color' => '#FF0000', // Example color
            'comments' => 'Cancelled event', // Adding comments as per requirement
            'rrule' => null, // No recurrence rule for a non-recurring event
            'duration' => '01:00:00' // Duration for the event
        ]);
    }
}
