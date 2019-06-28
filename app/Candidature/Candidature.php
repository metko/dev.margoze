<?php

namespace App\Candidature;

use App\User\User;
use App\Demand\Demand;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    protected $guarded = [];

    protected $with = ['owner'];

    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->locale('fr')->diffForHumans();
    }
}
