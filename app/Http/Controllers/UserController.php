<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'success'   => true,
            'message'   => 'Operation successful.',
            'data'      => UserResource::collection(User::paginate(5)),
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
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'success'   => false,
                'message'   => 'Validation error.',
                'data'      => $errors
            ], 422);
        }

        // create user resource if user input pass validation
        $loanOfficer = User::create([
            'firstname' => ucfirst($request->firstname),
            'lastname'  => ucfirst($request->lastname),
            'email'     => $request->email,
            'password'  => $request->password
        ]);

        if ($loanOfficer) {
            return response()->json([
                'success'       => true,
                'message'       => 'User account created successfully.',
                'data'          => new UserResource($loanOfficer)
            ], 201);
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
                'success'   => true,
                'message'   => 'Operation successful.',
                'data'      => new UserResource($user)
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
            $user->update($request->only([ucfirst('firstname'), ucfirst('lastname'), 'email']));

            return response()->json([
                'success'   => true,
                'message'   => 'Account updated.',
                'data'      => new UserResource($user)
            ], 200);
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
                'success'   => true,
                'message'   => 'Account deleted.',
            ], 200);
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
            // validate user input
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Failed validation.',
                    'data'      => $validator->errors()
                ]);
            }

            // update password if user input pass validation
            $user->update($request->only(['password']));

            return response()->json([
                'success'   => true,
                'message'   => 'Password updated successfully.',
                'data'      => new UserResource($user)
            ], 200);
        }
    }
}
