<?php

namespace App\Media;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = [];
    public $table = 'medias';

    /**
     * Get the owning commentable model.
     */
    public function mediable()
    {
        return $this->morphTo();
    }
}
