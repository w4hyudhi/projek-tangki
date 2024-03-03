<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Tangki;
use Exception;
use Illuminate\Support\Facades\Auth;

class TankiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required'],
                'price' => ['required'],
                'scedule' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
            ]);

            if($request->hasFile('file')){
                $dokumen = $request->file('file');
                $nama_dokumen = 'FT'.date('Ymdhis').'.'.$request->file('file')->getClientOriginalExtension();
                $dokumen->move('images/',$nama_dokumen);

            }
            $tangki = Tangki::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'price' => $request->price,
                'scedule' => $request->scedule,
                'description' => $request->description,
                'photo_path' => 'images/'.$nama_dokumen,


            ]);
            return ResponseFormatter::success([
                'data' => $tangki
            ],'Tangki Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function updateTangki(Request $request)
    {
        $data = $request->all();
        if($request->hasFile('file')){
            $dokumen = $request->file('file');
            $nama_dokumen = 'FT'.date('Ymdhis').'.'.$request->file('file')->getClientOriginalExtension();
            $dokumen->move('images/',$nama_dokumen);
            $data['photo_path']='images/'.$nama_dokumen;

        }
        $user = Auth::user();
        $tangki = Tangki::where('user_id',$user->id)->first();
        $tangki->update($data);

        return ResponseFormatter::success($tangki,'Tangki Updated');
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $type = $request->input('type');
        $status = $request->input('status');

        if($id)
        {
            $product = Tangki::active()->with(['user','review'])->find($id);

            if($product)
                return ResponseFormatter::success(
                    $product,
                    'Data tangki berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data tangki tidak ada',
                    404
                );
        }

        $tangki = Tangki::active()->with(['user','review']);
        if($name)
        $tangki->where('name', 'like', '%' . $name . '%');
        if($type)
        $tangki->where('type', $type);
        if($status)
            $tangki->where('status', $status);
            return ResponseFormatter::success(
                $tangki->get(),
                'Data list tangki berhasil diambil'
            );
    }
}
