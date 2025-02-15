<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'skill_id',
        'project_url',
        'image',
    ];
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
