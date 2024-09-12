<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas'; // nama tabel di database

    protected $fillable = [
        'tanggal', 
        'waktu', 
        'kegiatan', 
        'tipe_acara',
        'tempat',
        'delegasi', 
        'drescode'
    ];
}
