<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\StandardSignup;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(String $uid)
    {
        $registered = Registration::where('creator_id', '=', $uid)->where('registered', '=', 1)->with('events')->orderBy('created_at', 'desc')->get();
        return response()->json($registered, 200);
    }

    public function checkIfUserIsRegistered(String $uid, String $eid)
    {
        $registered = Registration::where('creator_id', '=', $uid)->where('event_id', '=', $eid)->get();
        return response()->json($registered, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $registered = Registration::create([
            'creator_id' => $request->creator_id,
            'event_id' => $request->event_id,
            'registered' => $request->registered,
        ]);

        return response()->json($registered, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function show(Registration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registration $registration)
    {
        $updated = Registration::find($registration->id);
        $updated->registered = $request->registered;
        $updated->save();
        return response()->json($updated, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Registration  $registration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Registration::where('event_id', '=', $request->event_id)->where('creator_id', '=', $request->creator_id)->delete();
    }

    public function checkUserIn(Request $request)
    {
        // Find user based on [email] and [uid]
        $user = StandardSignup::where('uuid', '=', $request->uuid)->where("email", '=', $request->email)->get();
        if (count($user)) {
            $registered = Registration::where('creator_id', '=', $user->first()->id)->where('event_id', '=', $request->eid)->get()->first();
            // Check if registered has a record
            if ($registered) {
                if ($registered->checked_in !== 1) {
                    $registered->checked_in = 1;
                    $registered->save();
                    return response()->json(["success" => "User was checked in."], 200);
                } else {
                    return response()->json(["error" => "User already checked in"], 200);
                }
            }
            return response()->json(["error" => "Could not find record"], 200);
        }
        return response()->json(["error" => "Could not verify user"], 200);
    }
    public function returnCheckinAmount(String $eid)
    {
        $records = Registration::where('event_id', '=', $eid)->where('registered', '=', 1)->get();
        return response()->json(count($records));
    }
}