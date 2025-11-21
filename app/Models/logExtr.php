<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logExtr extends Model
{
    protected $table = 'log_extr';

    protected $guarded = [];
    public $timestamps = false; // Menonaktifkan timestamps
}
