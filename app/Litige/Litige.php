<?php

namespace App\Litige;

use App\User\User;
use App\Contract\Contract;
use Metko\Galera\GlrConversation;
use Illuminate\Database\Eloquent\Model;

class Litige extends Model
{
    protected $guarded = [];

    protected $casts = [
        'uuid' => 'string',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function causer()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(GlrConversation::class);
    }

    public function close()
    {
        $this->closed = true;
        $this->save();
    }

    public function isClosed()
    {
        if ($this->closed) {
            return true;
        }

        return false;
    }
}
