<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $guarded = [];

    public function demand()
    {
        return $this->belongsTo(\App\Demand::class);
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }
}
