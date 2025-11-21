<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logCap extends Model
{
    use HasFactory;

    protected $table = 'log_cap';

    protected $guarded = [];
    public $timestamps = false; // Menonaktifkan timestamps
}
