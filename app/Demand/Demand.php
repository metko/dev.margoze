<?php

namespace App\Demand;

use App\User\User;
use App\Contract\Contract;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
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
    protected $with = ['owner', 'category', 'candidatures', 'sector'];
    protected $casts = [
        'contracted' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
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
        if (!$conversation = Galera::converationExist([$this->owner, $candidature->owner])) {
            $conversation = Galera::participants($this->owner_id, $candidature->owner_id)->make();
        }
        $contract = Contract::create([
            'demand_id' => $this->id,
            'candidature_id' => $candidature->id,
            'demand_owner_id' => $this->owner_id,
            'candidature_owner_id' => $candidature->owner_id,
            'conversation_id' => $conversation->id,
        ]);

        event(new ContractCreated($this, $candidature, $contract, $candidature->owner));

        return $contract;
    }

    public function getValidForAttribute()
    {
        return Carbon::parse($this->valid_until)->locale('fr')->diffInDays();
    }

    public function getCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->locale('fr')->diffForHumans();
    }

    public function path()
    {
        return route('demands.show', $this->id);
    }
}
