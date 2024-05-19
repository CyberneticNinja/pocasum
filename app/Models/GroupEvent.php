<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time', 'finish_time', 'location_address', 'comments',
        'user_id', 'group_id', 'color', 'rrule', 'duration','comments'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'finish_time' => 'datetime',
        'rrule' => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

//    public function getDurationInMinutesAttribute()
//    {
//        list($hours, $minutes, $seconds) = explode(':', $this->duration);
//        return $hours * 60 + $minutes + round($seconds / 60, 2);
//    }
}
