<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TripController extends Controller
{
    //

    public function store(Request $request){

        $validater = Validator::make($request->all(),[
            'destination_name' => 'required',
            'destination' => 'required',
            'origin' => 'required',
        ]);
        if($validater->fails()){
            return response()->json($validater->errors()->all(), 422);
        }
        return $request->user()->trips()->create($request->only([
            'destination_name',
            'destination',
            'origin'
        ]));

    }
    public function show(Request $request, Trip $trip){
            if($trip->user_id == $request->user()->id ){
             return $trip;
       }
           if($trip->driver && $request->user()->driver){
             if($trip->driver->id == $request->user()->driver->id){
                return $trip;
        }
       }
           return response()->json([
             'message' => 'could not find trip'
            ], 404);
       }

       public function accept(Request $request, Trip $trip){
        $validater = Validator::make($request->only([
            'driver_location' => 'required'
        ]));
        if($validater->fails()){
            return response()->json($validater->errors()->all(), 422);
        }
        $trip->update([
            'driver_location' => $request->driver_location,
            'driver_id' => $request->user()->id
        ]);
        $trip->load('driver.user');
        return $trip;       
        
       }
       public function start(Request $request, Trip $trip){
        $trip->update([
            'is_started' => true
        ]);
        $trip->load('driver.user');
        return $trip;
       }
       public function end(Request $request, Trip $trip){
        $trip->update([
            'is_complete' => true
        ]);
        $trip->load('driver.user');
        return $trip;
       }
       public function location(Request $request, Trip $trip){
        $validater = Validator::make($request->only([
            'driver_location' => 'required'
        ]));
        if($validater->fails()){
            return response()->json($validater->errors()->all(), 422);
        }
        $trip->update([
            'driver_location' => $request->driver_location
        ]);
        $trip->load('driver.user');
        return $trip;
}
}
