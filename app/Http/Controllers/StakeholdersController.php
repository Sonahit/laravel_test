<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Models\User;
use App\Models\UserToNotify;
use Illuminate\Http\Request;

class StakeholdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EmailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailRequest $request)
    {
        $params = $request->all();
        $prevEmail = isset($params['hidden']) ? $params['hidden']: false;
        $user = User::firstOrNew([
            'email' => $prevEmail ? $prevEmail : $params['email'],
        ]);
        if ($prevEmail) {
            $user->email = $params['email'];
        }
        $user->save();
        $stakeHolder = UserToNotify::firstOrNew([
            'userId' => $user->id
        ]);
        $stakeHolder->save();
        return response('Successfully updated stakeholder', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $test = now();
        return response('', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailRequest $request)
    {
        $params = $request->all();
        $user = User::where([
            'email' => $params['email'],
        ])->first();
        if (!is_null($user)) {
            $stakeHolder = UserToNotify::where([
                'userId' => $user->id
            ])->first();
            if (!is_null($stakeHolder)) {
                $stakeHolder->delete();
            }
        }
        return response('Successfully deleted stakeHolder', 200);
    }
}
