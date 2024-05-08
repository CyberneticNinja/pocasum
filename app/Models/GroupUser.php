<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'group_user';

    protected $fillable = [
        'group_id',
        'user_id',
    ];

    // Define the relationship with the Group model
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
