<?php

namespace App\Demand;

use Illuminate\Database\Eloquent\Model;

class DemandStatus extends Model
{
    protected $guarded = [];
    protected $table = 'demand_status';

    public static function boot()
    {
        parent::boot();
        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
        });
    }
}
