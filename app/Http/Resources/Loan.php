<?php

namespace App\Http\Resources;

use App\Http\Resources\Schedule as ScheduleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Loan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id'        => $this->id,
            'customer'  => $this->loanee->firstname . ' ' . $this->loanee->lastname,
            'reference' => $this->reference,
            'amount'    => number_format($this->principal, 2),
            // 'interest'  => $this->rate->interest,
            'plan'      => $this->plan->name,
            'duration'  => $this->duration,
            'status'    => $this->status,
            'schedules' => ScheduleResource::collection($this->schedules)
        ];
    }
}
