<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DriverController extends Controller
{
    //
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load('driver');
        return $user;
    }

    public function update(Request $request)
    {

        $validater = Validator::make($request->all(),[
            'name' => 'required',
            'year' => 'required|numeric|between:2000,2024',
            'license_plate' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
        ]);
        if($validater->fails()){
            return response()->json($validater->errors()->all(), 422);
        }
        $user = $request->user();
        $user->update($request->only('name'));
        // create driver or update
        $user->driver()->updateOrCreate($request->only([
            'year',
            'license_plate',
            'make',
            'model',
            'color'

        ]));
        $user->load('driver');
        return $user;
    }
}
