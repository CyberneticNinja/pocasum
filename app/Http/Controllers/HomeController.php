<?php

namespace App\Http\Controllers;

use App\Models\GroupEvent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = GroupEvent::with(['group.church'])->get()->map(function ($event) {
            $groupNameWithChurch = $event->group->name . ' (' . $event->group->church->name . ')' . ' ' . $event->group->id;

            // Ensuring that finish_time is handled properly
            $finishTime = $event->finish_time ? $event->finish_time->toIso8601String() : null;
//            if($event->rrule === null)
//            {
//                dd($event->comments);
//            }
            return [
                'name' => $groupNameWithChurch, // Group name along with Church name
                'start_time' => $event->start_time->toIso8601String(),
                'finish_time' => $finishTime,
                'duration' => $event->duration, // Include duration
                'color' => $event->color,
                'rrule' => $event->rrule,
                'comments' => $event->comments
            ];
        });

        return view('home', ['events' => $events]);
    }
}
