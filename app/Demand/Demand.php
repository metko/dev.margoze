<?php

namespace App\Demand;

use App\User\User;
use App\Candidature\Candidature;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}
