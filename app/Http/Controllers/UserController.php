<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
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
            'loanOfficers'      => UserResource::collection(User::all())
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
        $loanOfficer = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => $request->password
        ]);

        if ($loanOfficer) {
            return response()->json([
                'responseStatus'    => 201,
                'responsemessage'   => 'Account created.',
                'loanOfficer'      => new UserResource($loanOfficer)
            ]);
        } else {
            return response()->json([
                'responseStatus'    => 400,
                'responsemessage'   => 'Unable to create account.',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if ($user) {
            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Account created.',
                'loanOfficer'       => new UserResource($user)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($user) {
            $user->update($request->only(['firstname', 'lastname', 'email']));

            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Account updated.',
                'loanOfficer'       => new UserResource($user)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();

            return response()->json([
                'responseStatus'    => 204,
                'responsemessage'   => 'Account removed.',
            ]);
        }
    }

    /**
     * Update the specified user password.
     *
     * @param \Illuminate\Http\Request $request
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        if ($user) {
            $user->update($request->only(['password']));

            return response()->json([
                'responseStatus'    => 200,
                'responsemessage'   => 'Password updated.',
                'loanOfficer'       => new UserResource($user)
            ]);
        }
    }
}
