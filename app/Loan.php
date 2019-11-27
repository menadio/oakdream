<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /**
     * Attributes not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get user that placed loan order
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get loanee that requested loan
     */
    public function loanee()
    {
        return $this->belongsTo('App\Loanee');
    }

    /**
     * Get loan plan
     */
    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    /**
     * Get loan rate
     */
    public function rate()
    {
        return $this->belongsTo('App\Rate');
    }

    /**
     * Get loan schedules
     */
    public function schedules()
    {
        return $this->hasMany('App\Schedule');
    }
}
