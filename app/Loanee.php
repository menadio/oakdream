<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Loanee extends Model
{
    use Notifiable;

    /**
     * Attributes not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get loanee loans
     */
    public function loans()
    {
        return $this->hasMany('App\Loan');
    }

    /**
     * Get loanee schedules
     */
    public function schedules()
    {
        return $this->hasMany('App\Schedule');
    }
}
