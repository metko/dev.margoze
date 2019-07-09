<?php

namespace App\Candidature;

use App\User\User;
use App\Demand\Demand;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidature extends Model
{
    /**
     * The guarded fields.
     */
    protected $guarded = [];

    /**
     * The relation we want to load everytime.
     */
    protected $with = ['owner'];

    /**
     * The demand related to the candidature.
     */
    public function demand(): BelongsTo
    {
        return $this->belongsTo(Demand::class);
    }

    /**
     * The owner of the candidature.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return readable created_at.
     */
    public function getCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->locale('fr')->diffForHumans();
    }
}
