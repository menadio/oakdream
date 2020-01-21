<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalculateController extends Controller
{
    /**
     * Calculate loan projection
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function calculate(Request $request)
    {
        // validate user input
        $validator = Validator::make($request->all(), [
            'amount'    => 'required',
            'rate'      => 'required',
            'plan'      => 'required',
            'duration'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed validation.',
                'data'      => $validator->errors()
            ], 422);
        }

        $amount     = $request->amount;
        $rate       = $request->rate;
        $plan       = $request->plan;
        $duration   = $request->duration;

        $projections = $this->monthlyPaybackAmount($amount, $rate, $plan, $duration);

        $paymentSchedule = $this->schedulePayments($projections, $request->date);

        // return repaymentSchedule array
        return response()->json([
            'success'   => true,
            'message'   => 'Successful operation',
            'data'      => $paymentSchedule
        ]);
    }

    /**
     * Calculate total amount to be paid back in monthly installments
     *
     * @param $amount
     * @param $rate
     * @param $plan
     * @param $duration
     */
    public function monthlyPaybackAmount($amount, $rate, $plan, $duration)
    {
        // initialize projection array
        $projections = [];

        if ($plan == "Reducing Balance") {

            // calculate amount to be paid monthly
            $monthlyPayback = $amount / $duration;

            for($i = 1; $i <= $duration; $i++) {

                // calculate interest value
                $interest = (($amount * $rate) / 100);

                // amount to payback monthly
                $paybackAmount = $monthlyPayback;

                $projection = [
                    'amount'    => number_format($paybackAmount),
                    'interest'  => number_format($interest),
                    'total'     => number_format($paybackAmount + $interest)
                ];

                // store result in projections array
                $projections[] = $projection;

                // set reducing balance
                $amount = $amount - $monthlyPayback;

            }
        } else {

            // calculate amount to be paid monthly
            $monthlyPayback = 0;

            for ($i = 1; $i <= $duration; $i++) {

                // calculate interest value
                $interest = ($amount * $rate) / 100;

                // amount to payback monthly
                $paybackAmount = $monthlyPayback;

                $projection = [
                    'amount'    => number_format($paybackAmount),
                    'interest'  => number_format($interest),
                    'total'     => number_format($paybackAmount + $interest)
                ];

                $projections[] = $projection;
            }
        }

        return $projections;
    }

    /**
     * Prepare loan repayment schedule
     *
     * @param $projections
     * @param $date
     */
    public function schedulePayments($projections, $date)
    {
        $startRepayment = $date;
        $repaymentSchedule = [];

        for ($i = 0; $i < count($projections); $i++) {

            // create a schedule
            $schedule = [
                'schedule'      => $startRepayment,
                'projection'    => $projections[$i]
            ];

            // append schedule to repaymentSchedule array
            $repaymentSchedule[] = $schedule;

            // set the next repayment data
            $startRepayment = Carbon::parse($startRepayment)
                ->addMonth()
                ->toDateString();
        }

        // return repayment schedule
        return $repaymentSchedule;
    }
}
