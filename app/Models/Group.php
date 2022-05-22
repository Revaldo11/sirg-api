<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    // protected $fillable = [
    //     'title',
    //     'description',
    //     'path',
    //     'user_id',
    //     'research_id',
    // ];

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'research_id',
    ];


    public function research()
    {
        return $this->hasMany(Research::class);
    }
}
