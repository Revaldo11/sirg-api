<?php

namespace App\Models;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'research_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function researchs()
    {
        return $this->hasMany(Research::class);
    }
}
