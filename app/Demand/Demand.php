<?php

namespace App\Demand;

use App\User\User;
use App\Sector\Sector;
use App\Category\Category;
use App\Contract\Contract;
use Illuminate\Support\Carbon;
use App\Candidature\Candidature;
use Metko\Galera\Facades\Galera;
use Illuminate\Database\Eloquent\Model;
use App\Contract\Events\ContractCreated;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demand extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [];
    protected $casts = [
        'contracted' => 'boolean',
    ];

    /**
     * boot.
     */
    public static function boot()
    {
        parent::boot();

        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
        });
    }

    /**
     * Owner of the demand.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All the candidature owned by the demand.
     *
     * @return HasMany
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Sector of the demand.
     *
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * Category of the demand.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Contract the demand.
     *
     * @return Demand
     */
    public function contract(): bool
    {
        return $this->update(['contracted' => true]);
    }

    /**
     * Check if the demand is contracteed.
     *
     * @return bool
     */
    public function isContracted(): Bool
    {
        return $this->contracted;
    }

    /**
     * Check if th dmand is still valid.
     *
     * @return bool
     */
    public function isValid(): Bool
    {
        return $this->valid_until > now();
    }

    /**
     * Check if the demand has the given status.
     *
     * @param mixed $status
     */
    public function hasStatus(string $status): bool
    {
        $statut = strtolower($status);
        if ($this->status == $status) {
            return true;
        }

        return false;
    }

    /**
     * Check if the demand has the givven category.
     *
     * @param mixed $category
     */
    public function hasCategory($category): bool
    {
        if ($category instanceof Category) {
            return $this->category->is($category);
        }
        if ($this->category->name == $category || $this->category->slug == $category) {
            return true;
        }

        return false;
    }

    /**
     * Create a contract between a candidature and a demand.
     *
     * @param mixed $candidature
     *
     * @return Contract
     */
    public function contractCandidature($candidature): Contract
    {
        if ($this->isContracted()) {
            throw Exceptions\DemandAlreadyContracted::create($this->id);
        }
        if ($candidature->owner->isOwnerDemand($this)) {
            throw Exceptions\CandidatureBelongsToOwnerDemand::create($this->id);
        }

        if (!$this->isValid()) {
            throw Exceptions\DemandNoLongerAvailable::create($this->id);
        }
        $this->contract();

        if (!$conversation = Galera::converationExist([$this->owner, $candidature->owner])) {
            $conversation = Galera::participants($this->owner_id, $candidature->owner_id)->make();
        }
        $contract = Contract::create([
            'demand_id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'candidature_id' => $candidature->id,
            'demand_owner_id' => $this->owner_id,
            'candidature_owner_id' => $candidature->owner_id,
            'conversation_id' => $conversation->id,
            'category_id' => $this->category_id,
            'sector_id' => $this->sector_id,
            'be_done_at' => $this->be_done_at,
        ]);
        event(new ContractCreated($this, $candidature, $contract, $candidature->owner));

        return $contract;
    }

    /**
     * Return readable valid_for attribute.
     *
     * @return string
     */
    public function getValidForAttribute(): string
    {
        if (Carbon::parse($this->valid_until)->lt(now())) {
            return 'Demande terminé';
        }

        return Carbon::parse($this->valid_until)->locale('fr')->diffInDays().' jours restants';
    }

    /**
     *Return readable created_at attribute.
     *
     * @return string
     */
    public function getCreatedAttribute(): string
    {
        return Carbon::parse($this->created_at)->locale('fr')->diffForHumans();
    }

    /**
     * return path of the demand.
     */
    public function path(): string
    {
        return route('demands.show', $this->id);
    }
}
