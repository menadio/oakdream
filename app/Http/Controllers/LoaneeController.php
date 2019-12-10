<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Loanee;
use App\Notifications\Welcome;
use App\Http\Resources\Loanee as LoaneeResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
            'responseStatus'    => 200,
            'responseMessage'   => 'Successful operation.',
            'loanees'           => LoaneeResource::collection(Loanee::orderBy('created_at', 'desc')->get())
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
        $loanee = Loanee::create([
            'account'   => rand(1000000000, 9999999999),
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'address'   => $request->address,
            'phone'     => $request->phone
        ]);

        if ($loanee) {

            // notify loanee
            $loanee->notify(new Welcome($loanee));

            return response()->json([
                'responseStatus'    => 201,
                'responseMessage'   => 'Account created.',
                'loanee'            => new LoaneeResource($loanee)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 400,
                'responseMessage'   => 'Unable to create loanee account.',
            ]);
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
                'responseStatus'    => 200,
                'responseMessage'   => 'Successful operation.',
                'loanee'            => new LoaneeResource($loanee),
                'loanStats'         => $loanStats
            ]);
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
            $loanee->update($request->only(['firstname', 'lastname', 'email', 'address', 'phone']));

            return response()->json([
                'responseStatus'    => 200,
                'responseMessage'   => 'Account updated.',
                'loanee'            => new LoaneeResource($loanee)
            ]);
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
                'responseStatus'    => 204,
                'responseMessage'   => 'Account removed.',
            ]);
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
            'responseStatus'    => 200,
            'responseMessage'   => 'Successful operation.',
            'loaneeStats'       => $loaneeStats
        ]);
    }
}
