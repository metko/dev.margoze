<?php

namespace App\Contract;

use App\User\User;
use App\Demand\Demand;
use App\Candidature\Candidature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($contract) {
        });
        static::updating(function ($contract) {
        });
        static::created(function ($contract) {
            //dd($contract);
        });
    }

    public function userDemand()
    {
        return $this->belongsTo(User::class, 'demander_owner_id', 'id');
    }

    public function userCandidature()
    {
        return $this->belongsTo(User::class, 'candidature_owner_id', 'id');
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id', 'id');
    }

    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'candidature_id', 'id');
    }
}
