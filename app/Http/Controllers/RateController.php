<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Http\Resources\Rate as RateResource;
use Illuminate\Http\Request;

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
            'responseStatus'    => 200,
            'responseMessage'   => 'Successful operation.',
            'rates'             => RateResource::collection(Rate::orderBy('created_at', 'desc')->get())
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
        $rate = Rate::create(['interest' => $request->interest]);

        if ($rate) {
            return response()->json([
                'responseStatus'    => 201,
                'responseMessage'   => 'New rate created.',
                'rate'              => new RateResource($rate)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 400,
                'responsemessage'   => 'Unable to create rate.',
            ]);
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
                'responseStatus'    => 200,
                'responseMessage'   => 'Successful operation.',
                'rate'              => new RateResource($rate)
            ]);
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
                'responseStatus'    => 200,
                'responseMessage'   => 'Rate updated.',
                'rate'              => new RateResource($rate)
            ]);
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
                'responseStatus'    => 204,
                'responseMessage'   => 'Rate removed.',
            ]);
        }
    }
}
