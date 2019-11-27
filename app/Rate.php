<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    /**
     * Attributes not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get loans with rate
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }
}
