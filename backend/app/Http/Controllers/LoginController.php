<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LoginNeedVarifaction;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //

    public function login(Request $request){
        $validater = Validator::make($request->all(),[
            'phone' => 'required|min:13|numeric'
        ]);
        if ($validater->fails()) {
            return response()->json($validater->errors()->all(), 422);
        }
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);
        if (!$user) {
            return response()->json([
                'message' => 'Could not process user with that  phone number '
            ]);
        }
        $user->notify(new LoginNeedVarifaction());
        // response
        return response()->json([
            'message' => 'Login code sent to ' . $user->phone
        ]);
    }
    public function verify(Request $request){
        $validater = Validator::make($request->all(),[
            'login_code' => 'required|numeric|between:1000,9999',
            'phone' => 'required|min:13|numeric'
        ]);
        if ($validater->fails()) {
            return response()->json($validater->errors()->all(), 422);
        }
        $user = User::where('phone', $request->phone)->where('login_code', $request->login_code)->first();
        if($user){
            $user->update(['login_code' => null]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        return response()->json([
            'message' => 'Invalid login code'
        ], 422);
    }
}