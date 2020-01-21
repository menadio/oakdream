<?php

namespace App\Http\Resources;

use App\Http\Resources\Loan as LoanResource;
use App\Http\Resources\Schedule as ScheduleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Loanee extends JsonResource
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
            'id'        => $this->id,
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'account'   => $this->account,
            'email'     => $this->email,
            'address'   => ucwords($this->address),
            'phone'     => $this->phone,
            'loans'     => LoanResource::collection($this->loans)
        ];
    }
}
