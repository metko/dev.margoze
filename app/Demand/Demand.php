<?php

namespace App\Demand;

use App\User\User;
use App\Contract\Contract;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Illuminate\Database\Eloquent\Model;
use App\Contract\Events\ContractCreated;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Demand\Exceptions\DemandAlreadyContracted;
use App\Demand\Exceptions\DemandNoLongerAvailable;
use App\Candidature\Exceptions\CandidatureBelongsToOwnerDemand;

class Demand extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [
        'candidatures', 'category', 'sector', 'owner',
    ];
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
            if (!$demand->status) {
                $demand->status = 'default';
            }
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

    public function sector()
    {
        return $this->belongsTo(DemandSector::class, 'sector_id');
    }

    public function hasStatus(string $status)
    {
        $statut = strtolower($status);
        if ($this->status == $status) {
            return true;
        }

        return false;
    }

    public function category()
    {
        return $this->belongsTo(DemandCategory::class);
    }

    public function hasCategory($category)
    {
        if ($category instanceof DemandCategory) {
            return $this->category->is($category);
        }
        if ($this->category->name == $category || $this->category->slug == $category) {
            return true;
        }

        return false;
    }

    public function contractCandidature($candidature)
    {
        if ($this->isContracted()) {
            throw DemandAlreadyContracted::create($this->id);
        }
        if ($candidature->owner->isOwnerDemand($this)) {
            throw CandidatureBelongsToOwnerDemand::create($this->id);
        }

        if (!$this->isValid()) {
            throw DemandNoLongerAvailable::create($this->id);
        }
        $this->contracted();
        $contract = Contract::create([
            'demand_id' => $this->id,
            'candidature_id' => $candidature->id,
            'demand_owner_id' => $this->owner_id,
            'candidature_owner_id' => $candidature->owner_id,
        ]);
        event(new ContractCreated($this, $candidature, $contract, $candidature->owner));

        return $contract;
    }

    public function getValidForAttribute()
    {
        $date = $this->valid_until;

        return Carbon::parse($this->valid_until)->locale('fr')->diffInDays();
    }
}
