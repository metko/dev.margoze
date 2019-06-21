<?php

namespace App\Demand;

use App\User\User;
use App\Candidature\Candidature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demand extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'contracted' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function contracted()
    {
        return $this->update(['contracted' => true]);
    }

    public function isContracted()
    {
        return $this->contracted;
    }
}
