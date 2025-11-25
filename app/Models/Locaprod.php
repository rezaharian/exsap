<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locaprod extends Model
{
    use HasFactory;
    protected $table = 'locaprod';

    protected $guarded = [];
    public $timestamps = false; // Menonaktifkan timestamps

}
