<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Loan;
use App\Schedule;
use App\Http\Resources\Loan as LoanResource;
use App\Notifications\LoanApproved;
use App\Notifications\LoanDenied;
use App\Notifications\LoanRequest;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'responseStatus'    => 200,
            'responsemessage'   => 'Successful operation.',
            'loans'             => LoanResource::collection(Loan::orderBy('created_at', 'desc')->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loan = Loan::create([
            'user_id'   => $request->user_id,
            'loanee_id' => $request->loanee_id,
            'reference' => rand(1000000000, 9999999999),
            'principal' => $request->principal,
            'rate_id'   => $request->rate,
            'plan_id'   => $request->plan,
            'duration'  => $request->duration
        ]);

        if ($loan) {

            // notify loanee
            $loan->loanee->notify(new LoanRequest($loan));

            return response()->json([
                'responseStatus'    => 201,
                'responsemessage'   => 'Loan created.',
                'loan'              => new LoanResource($loan)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 400,
                'responsemessage'   => 'Unable to place loan order.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        if ($loan) {
            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Successful operation.',
                'loan'              => new LoanResource($loan)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        if ($loan && ($loan->status == 'pending')) {
            $loan->update($request->only([
                'principal', 'rate', 'plan', 'duration'
            ]));

            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Update successful.',
                'loan'              => new LoanResource($loan)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 403,
                'responsemessage'   => 'Loan has already been reviewed. Please place make new request.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        if ($loan) {
            $loan->delete();

            return response()->json([
                'responseStatus'    => 204,
                'responsemessage'   => 'Loan removed.',
            ]);
        }
    }

    /**
     * Approve the specified loan resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function approveLoan(Request $request, Loan $loan)
    {
        if ($loan) {
            $loan->update([
                'status'    => 'approved',
                'comment'   => $request->comment
            ]);

            // notify loanee
            $loan->loanee->notify(new LoanApproved($loan));

            $this->createPaymentSchedule($loan);

            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Loan approved.',
                'loan'              => new LoanResource($loan)
            ]);
        }
    }

    /**
     * Deny the specified loan resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function denyLoan(Request $request, Loan $loan)
    {
        if ($loan) {
            $loan->update([
                'status'    => 'denied',
                'comment'   => $request->comment
            ]);

            // notify loanee
            $loan->loanee->notify(new LoanDenied($loan));

            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Loan denied.',
                'loan'              => new LoanResource($loan)
            ]);
        }
    }

    /**
     * Make schedule for loan payment
     *
     * @param $loan
     */
    public function createPaymentSchedule($loan)
    {
        $loanAmount     = $loan->principal;
        $loanRate       = $loan->rate->interest;
        // $loanPlan       = $loan->plan->id;
        $loanDuration   = $loan->duration;

        // calculate amount to be paid monthly
        $monthlyPayback = $loanAmount / $loanDuration;

        // initialize projection array
        $projections = [];

        if ($loan->plan->name == "Reducing Balance") {

            $projections = $this->reducingBalanceLoanPlan($loanAmount, $loanDuration, $loanRate, $monthlyPayback);
        } else {

            $projections = $this->equalRepaymentPlan($loanAmount, $loanDuration, $loanRate);
        }

        $schedule = Carbon::parse($loan->updated_at)->addMonth()->toDateString();

        for ($i = 0; $i < count($projections); $i++) {

            Schedule::create([
                'loan_id'   => $loan->id,
                'amount'    => $projections[$i]['amount'],
                'interest'  => $projections[$i]['interest'],
                'total'     => $projections[$i]['total'],
                'schedule'  => $schedule
            ]);

            $schedule = Carbon::parse($schedule)->addMonth()->toDateString();
        }
    }

    /**
     * Get loan statistics
     *
     * @return \Illuminate\Http\Response
     */
    public function getStats()
    {
        $loanStats = [
            'loansTotal'        => Loan::count(),
            'loansApproved'     => Loan::where('status', 'approved')->count(),
            'loansDenied'       => Loan::where('status', 'denied')->count()
        ];

        return response()->json([
            'responseStatus'    => 200,
            'responseMessage'   => 'Successful operation.',
            'loanStats'         => $loanStats
        ]);
    }

    /**
     * Reducing balance calulation
     */
    function reducingBalanceLoanPlan($loanAmount, $loanDuration, $loanRate, $monthlyPayback)
    {
        for ($i = 1; $i <= $loanDuration; $i++) {

            // calculate interest value
            $interest = ($loanAmount * $loanRate) / 100;

            // calculate amount to payback
            $paybackAmount = $monthlyPayback;

            // set projection
            $projection = [
                'amount'    => $paybackAmount,
                'interest'  => $interest,
                'total'     => $paybackAmount + $interest
            ];

            // store result in projections array
            $projections[] = $projection;

            // set reducing balance
            $loanAmount = $loanAmount - $monthlyPayback;

        }

        return $projections;
    }

    /**
     * Monthly dureation calculation
     */
    function equalRepaymentPlan($loanAmount, $loanDuration, $loanRate)
    {
        for ($i = 1; $i <= $loanDuration; $i++) {
            // calculate monthly payback amount
            $paybackAmount = ($loanAmount * $loanRate) / 100;

            // is i the last month of the loan
            if ($i == $loanDuration) {
                $projection = [
                    'amount'    => 0,
                    'interest'  => $paybackAmount,
                    'total'     => $paybackAmount + $loanAmount
                ];
            } else {
                $projection = [
                    'amount'    => 0,
                    'interest'  => $paybackAmount,
                    'total'     => $paybackAmount
                ];
            }

            $projections[] = $projection;
        }

        return $projections;
    }
}
