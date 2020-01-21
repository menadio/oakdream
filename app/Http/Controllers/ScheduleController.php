<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Http\Resources\Schedule as ScheduleResource;
use Illuminate\Http\Request;

class ScheduleController extends Controller
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
            'message'   => 'Retrieved schedules successfully.',
            'data'      => ScheduleResource::collection(schedule::paginate(5))
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        if ($schedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Retrived schedule details successfully.',
                'data'      => new ScheduleResource($schuedle)
            ]);
        }
    }
}
