<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function candidatures()
    {
        return $this->hasMany(\App\Candidature::class);
    }
}
