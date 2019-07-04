<?php

namespace App\Contract;

use App\User\User;
use App\Demand\Demand;
use App\Candidature\Candidature;
use Metko\Galera\GlrConversation;
use Illuminate\Database\Eloquent\Model;
use App\Contract\Events\ContractValidated;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contract\Exceptions\DateAlreadySubmit;
use App\Contract\Exceptions\ContractAlreadyValidated;
use App\Contract\Exceptions\UserDoesntBelongsToContract;

class Contract extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $with = ['demand', 'userDemand', 'userCandidature'];

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
        return $this->belongsTo(User::class, 'demand_owner_id', 'id');
    }

    public function userCandidature()
    {
        return $this->belongsTo(User::class, 'candidature_owner_id', 'id');
    }

    public function demand()
    {
        return $this->belongsTo(Demand::class, 'demand_id', 'id');
    }

    public function conversation()
    {
        return $this->belongsTo(GlrConversation::class);
    }

    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'candidature_id', 'id');
    }

    public function setDate($date, $user)
    {
        if ($this->userCanEditDetails($user, true)) {
            $this->be_done_at = $date;
            $this->wait_for_validate = true;
            $this->last_propose_by = $user->id;
            $this->save();
        }
    }

    protected function userCanEditDetails($user, $withErrors = false)
    {
        //Verfifier si l'user fais partie du contrat
        if ($this->validated_at) {
            throw ContractAlreadyValidated::create();
        }
        if (!$user->isInContract($this)) {
            if ($withErrors) {
                throw UserDoesntBelongsToContract::create();
            }

            return false;
        }
        if ($this->last_propose_by == $user->id) {
            if ($withErrors) {
                throw DateAlreadySubmit::create();
            }

            return false;
        }

        return true;
    }

    public function refuseDate($user)
    {
        if ($this->userCanEditDetails($user)) {
            $this->be_done_at = null;
            $this->wait_for_validate = false;
            $this->last_propose_by = null;
            $this->save();

            return $this;
        }

        return false;
    }

    public function acceptDate($user)
    {
        if ($this->userCanEditDetails($user)) {
            if (!empty($this->be_done_at && empty($this->validated_at))) {
                $this->validated_at = now();
                $this->save();
                event(new ContractValidated($this));

                return $this;
            }
        }

        return false;
    }

    public function cancelContract($user)
    {
        if (!$user->isInContract($this)) {
            if ($withErrors) {
                throw UserDoesntBelongsToContract::create();
            }

            return false;
        }
        $this->cancelled_by = $user->id;
        $this->save();

        return $this;
    }
}
