<?php

namespace App\Candidature;

use App\User;
use App\Demand\Demand;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $guarded = [];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
