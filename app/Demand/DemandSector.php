<?php

namespace App\Demand;

use Illuminate\Database\Eloquent\Model;

class DemandSector extends Model
{
    protected $guarded = [];
    protected $table = 'demand_sectors';

    public static function boot()
    {
        parent::boot();
        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
        });
    }
}
