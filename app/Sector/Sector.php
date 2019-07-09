<?php

namespace App\Sector;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $guarded = [];
    protected $table = 'sectors';

    public static function boot()
    {
        parent::boot();
        static::updating(function ($demand) {
        });
        static::creating(function ($demand) {
        });
    }
}
