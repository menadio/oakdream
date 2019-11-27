<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Http\Resources\Plan as PlanResource;
use Illuminate\Http\Request;

class PlanController extends Controller
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
            'plans'             => PlanResource::collection(Plan::orderBy('created_at', 'desc')->get())
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
        $plan = Plan::create([
            'name'  => $request->name
        ]);

        if ($plan) {
            return response()->json([
                'responseStatus'    => 201,
                'responseMessage'   => 'Plan created.',
                'plan'              => new PlanResource($plan)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 400,
                'responseMessage'   => 'Unable to create plan.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        if ($plan) {
            return response()->json([
                'responseStatus'    => 200,
                'responseMessage'   => 'Successful operation.',
                'plan'              => new PlanResource($plan)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        if ($plan) {
            $plan->update($request->only(['name']));

            return response()->json([
                'responseStatus'    => 200,
                'responseMessage'   => 'Plan updated.',
                'plan'              => new PlanResource($plan)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        if ($plan) {
            $plan->delete();

            return response()->json([
                'responseStatus'    => 204,
                'responseMessage'   => 'Plan removed.',
            ]);
        }
    }
}
