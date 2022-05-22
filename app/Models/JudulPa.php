<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//-class JudulPa extends Model
//{
//    use HasFactory;
//}

class JudulPa extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'supervisor',
        'description',
        'qualification',
    ];
}