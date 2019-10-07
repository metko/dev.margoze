<?php

namespace App\Credit;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $type = 'default';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($credit) {
            $credit->credit();
        });
    }

    public function credit($save = false)
    {
        $this->demands_valid_for = config('credits.'.$this->type.'.demands_valid_for');
        $this->urgence_status_count = config('credits.'.$this->type.'.urgence_status_count');
        $this->photos_demand_count = config('credits.'.$this->type.'.photos_demand_count');
        $this->offers_per_month = config('credits.'.$this->type.'.offers_per_month');
        $this->offers_valid_for = config('credits.'.$this->type.'.offers_valid_for');
        $this->candidatures_count = config('credits.'.$this->type.'.candidatures_count');
        $this->contracts_count = config('credits.'.$this->type.'.contracts_count');
        if ($save) {
            return $this->save();
        }

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function incrementField($field, $amount)
    {
        $this->$field += $amount;
        $this->save();

        return $this;
    }

    public function useCredit($field, $amount = 1)
    {
        $this->$field -= $amount;
        $this->save();

        return $this;
    }
}
