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
        $request->validate([
            'phone' => ['required', 'regex:/^\+92[0-9]{10}$/']
        ], [
            'phone.regex' => 'phone number must start with +92 and should be 12 digits'
        ]);


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
    public function verify(Request $request)
    {

        $request->validate([
            'login_code' => 'required',
        ]);
        $user = User::where('phone', $request->phone)->where('login_code', $request->login_code)->first();
        if ($user) {
            $user->update(['login_code' => null]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        return response()->json([
            'message' => 'Invalid login code'
        ], 422);
    }
}
