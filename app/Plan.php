<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /**
     * Attributes not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get loans with plan
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }
}
