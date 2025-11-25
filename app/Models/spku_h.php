<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spku_h extends Model
{
    use HasFactory;
    protected $table = 'spku_h';

    protected $guarded = [];

    protected $casts = [
        'tgl_input' => 'date', // otomatis jadi Carbon
        'tgl_perbaikan' => 'date',
        'tgl_pre' => 'date',
    ];


    public function details()
    {
        return $this->hasMany(spku_d::class, 'spku_cod', 'spku_cod');
    }
}
