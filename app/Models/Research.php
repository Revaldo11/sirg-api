<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Research extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'author',
        'file',
        'user_id',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
