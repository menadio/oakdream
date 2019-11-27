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
            'responseStatus'    => 200,
            'responsemessage'   => 'Successful operation.',
            'schedules'         => ScheduleResource::collection(Schedule::all())
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
                'responseStatus'    => 200,
                'responsemessage'   => 'Successful operation.',
                'schedule'          => new ScheduleResource($schedule)
            ]);
        }
    }
}
