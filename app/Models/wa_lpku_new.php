<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wa_lpku_new extends Model
{
    use HasFactory;
    protected $table = 'wa_lpku_new';

    protected $guarded = [];
    public $timestamps = false; // Menonaktifkan timestamps
}
