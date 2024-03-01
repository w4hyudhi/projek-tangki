<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddresResponse;
use App\Models\Address;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AddressController extends Controller
{
    public function add(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'address' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],

            ]);
            $address = Address::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return ResponseFormatter::success([
                AddresResponse::make($address),
            ],'Tangki Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }


    public function update(Request $request)
    {


        $request->validate([
            'id' => ['required'],
        ]);
        $data = $request->all();
        $address = Address::find($request->id);
        $address->update($data);
        return ResponseFormatter::success(AddresResponse::make($address),'Tangki Updated');
    }

    public function all()
    {
        $address = Address::where('user_id', Auth::user()->id)->get();
        return ResponseFormatter::success(  AddresResponse::collection($address),'data alamat');
    }
}
