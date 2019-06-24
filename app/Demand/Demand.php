<?php

namespace App\Demand;

use App\User\User;
use App\Contract\Contract;
use App\Candidature\Candidature;
use Illuminate\Database\Eloquent\Model;
use App\Contract\Events\ContractCreated;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;

class Demand extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'contracted' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
            $demand->valid_until = now()->addMonths(1);
        });
    }

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

    public function isValid()
    {
        return $this->valid_until > now();
    }

    public function contractCandidature($candidature)
    {
        if ($this->isContracted()) {
            throw DemandAlreadyContracted::create($this->id);
        }

        if (!$this->isValid()) {
            throw DemandNoLongerAvailable::create($this->id);
        }
        $this->contracted();
        $contract = Contract::create([
            'demand_id' => $this->id,
            'candidature_id' => $candidature->id,
            'demander_owner_id' => $this->owner_id,
            'candidature_owner_id' => $candidature->owner_id,
        ]);
        event(new ContractCreated($this, $candidature, $contract));
    }
}
