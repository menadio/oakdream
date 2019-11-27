<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * Attributes not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get schedule loan
     */
    public function loan()
    {
        return $this->belongsTo('App\Loan');
    }

    /**
     * Get schedule loanee
     */
    public function loanee()
    {
        return $this->belongsTo('App\Loanee');
    }
}
