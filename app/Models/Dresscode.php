<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dresscode extends Model
{
    use HasFactory;

    protected $table = 'dresscodes';
    protected $fillable = [
        'hari',
        'dresscode',
    ];
}
