<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Loanee;
use App\Notifications\Welcome;
use App\Http\Resources\Loanee as LoaneeResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoaneeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success'   => true,
            'message'   => 'Retrieved customers successfully.',
            'data'      => LoaneeResource::collection(Loanee::orderBy('created_at', 'desc')->paginate(5))
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate user input
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'email|unique:loanees',
            'address'   => 'required',
            'phone'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed validation.',
                'data'      => $validator->errors()
            ], 422);
        }

        // create account if user input pass validation
        $loanee = Loanee::create([
            'account'   => rand(1000000000, 9999999999),
            'firstname' => ucfirst($request->firstname),
            'lastname'  => ucfirst($request->lastname),
            'email'     => $request->email,
            'address'   => $request->address,
            'phone'     => $request->phone
        ]);

        if ($loanee) {
            // notify loanee
            $loanee->notify(new Welcome($loanee));

            return response()->json([
                'success'   => true,
                'message'   => 'Account created.',
                'data'      => new LoaneeResource($loanee)
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Loanee  $loanee
     * @return \Illuminate\Http\Response
     */
    public function show(Loanee $loanee)
    {
        if ($loanee) {
            return response()->json([
                'success'   => true,
                'message'   => 'Retrieved customer details successfully.',
                'data'      => new LoaneeResource($loanee),
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loanee  $loanee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loanee $loanee)
    {
        if ($loanee) {
            // update customer details
            $loanee->update($request->only(['firstname', 'lastname', 'email', 'address', 'phone']));

            return response()->json([
                'success'   => true,
                'message'   => 'Account updated.',
                'data'      => new LoaneeResource($loanee)
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loanee  $loanee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loanee $loanee)
    {
        if ($loanee) {
            $loanee->delete();

            return response()->json([
                'success'    => true,
                'message'   => 'Account removed.',
            ], 200);
        }
    }

    /**
     * Get all loanee stats
     *
     * @return \Illuminate\Http\Response
     */
    public function stats()
    {
        $loaneeStats = [
            'totalLoanees'      => Loanee::count(),
            'loaneesWithLoans'  => Loanee::whereHas('loans', function (Builder $query) {
                $query->where('status', 'approved');
            })->count(),
            'loaneesWaiting'    => Loanee::wherehas('loans', function (Builder $query) {
                $query->where('status', 'pending');
            })->count()
        ];

        return response()->json([
            'success'   => true,
            'message'   => 'Retrieved statistics successfully.',
            'data'      => $loaneeStats
        ]);
    }

    /**
     * Get customer loan statistics
     *
     * @param Loanee $loanee
     * @return Response
     */
    public function loaneeStats(Loanee $loanee)
    {
        if ($loanee) {
            $loanStats = [
                'approved'  => Loan::where([
                    ['status', 'approved'], ['loanee_id', $loanee->id]
                ])->count(),
                'denied'    => Loan::where([
                    ['status', 'denied'], ['loanee_id', $loanee->id]
                ])->count(),
                'pending'   => Loan::where([
                    ['status', 'pending'], ['loanee_id', $loanee->id]
                ])->count(),
            ];

            return response()->json([
                'success'   => true,
                'message'   => 'Retrieved customer loans statistics.',
                'data'      => $loanStats
            ], 200);
        }
    }
}
