<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecturer extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'name',
        'phone',
        'year_lecturer',
        'community_service',
        'achievement_lecturer',
        'password',
        'path_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
