<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spku_d extends Model
{
    use HasFactory;
    protected $table = 'spku_d';
    protected $casts = [
        'tgl_input' => 'date', // otomatis jadi Carbon
        'tgl_perbaikan' => 'date',
        'tgl_pre' => 'date',
    ];
    protected $guarded = [];
}
