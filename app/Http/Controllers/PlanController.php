<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Http\Resources\Plan as PlanResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'success'   => true,
            'message'   => 'Successful operation.',
            'data'      => PlanResource::collection(Plan::orderBy('created_at', 'desc')->paginate(5))
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
        // validate user input
        $validator = Validator::make($request->all(), [
            'name'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => 'Falied validation.',
                'data'      => $validator->errors()
            ]);
        }

        // create new plan
        $plan = Plan::create([
            'name'  => $request->name
        ]);

        if ($plan) {
            return response()->json([
                'success'   => true,
                'message'   => 'Plan created.',
                'data'      => new PlanResource($plan)
            ], 201);
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
                'success'   => true,
                'message'   => 'Retrieved plan details successfully.',
                'data'      => new PlanResource($plan)
            ], 200);
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
                'success'   => true,
                'message'   => 'Plan updated.',
                'data'      => new PlanResource($plan)
            ], 200);
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
                'success'   => true,
                'message'   => 'Plan removed.',
            ], 200);
        }
    }
}
