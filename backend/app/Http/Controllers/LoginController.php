<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LoginNeedVarifaction;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //

    public function login(Request $request)
    {
        $validater = Validator::make($request->all(), [
            'phone' => 'required|numeric|min:13'
        ]);
        if ($validater->fails()) {
            return response()->json($validater->errors()->all(), 422);
        }
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);
        if (!$user) {
            return response()->json([
                'message' => 'Could not process user with that phone number'
            ]);
        }
        $user->notify(new LoginNeedVarifaction());
        // response
        return response()->json([
            'message' => 'Login code sent to ' . $user->phone
        ]);
    }

    public function verify(Request $request)
    {
        // validate
        // $request->validate([
        //     'phone' => 'required|numeric|exists:users,phone',
        //     'login_code' => 'required|numeric|between:1111,9999',
        // ]);
        $validater = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users,phone',
            'login_code' => 'required|numeric|between:1111,9999',
        ]);
        $user = User::where('phone', $request->phone)->where('login_code', $request->login_code)->first();
        // is code provided is same as stored
        // if so return back an auth token
        if ($user) {
            $user->update(['login_code' => null]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        // if not return back error
        return response()->json([
            'message' => 'Invalid varifaction code'
        ], 401);
    }
}
