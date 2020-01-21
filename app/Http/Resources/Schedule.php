<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Schedule extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'loan_id'       => $this->loan_id,
            'amount'        => number_format($this->amount, 2),
            'interest'      => number_format($this->interest, 2),
            'total'         => number_format($this->total, 2),
            'schedule'      => $this->schedule,
        ];
    }
}
