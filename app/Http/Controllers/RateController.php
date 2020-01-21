<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Http\Resources\Rate as RateResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
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
            'message'   => 'Successful operation.',
            'data'      => RateResource::collection(Rate::orderBy('created_at', 'desc')->paginate(5))
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
            'interest'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Failed validation.',
                'data'      => $validator->errors()
            ]);
        }

        // create new rate
        $rate = Rate::create(['interest' => $request->interest]);

        if ($rate) {
            return response()->json([
                'success'   => true,
                'message'   => 'New rate created.',
                'data'      => new RateResource($rate)
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        if ($rate) {
            return response()->json([
                'success'   => true,
                'message'   => 'Retrieved rate details successfully.',
                'data'      => new RateResource($rate)
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        if ($rate) {
            $rate->update($request->only(['interest']));

            return response()->json([
                'success'   => true,
                'message'   => 'Rate updated.',
                'data'      => new RateResource($rate)
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        if ($rate) {
            $rate->delete();

            return response()->json([
                'success'    => true,
                'message'   => 'Rate removed.',
            ], 200);
        }
    }
}
