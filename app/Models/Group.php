<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'church_id'];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function groupLeaders()
    {
        return $this->hasMany(GroupLeader::class);
    }
}
