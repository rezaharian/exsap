<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class prob_msn extends Model
{
    protected $table = 'prob_msns';

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(prob_msd::class, 'prob_cod', 'prob_cod');
    }
}
