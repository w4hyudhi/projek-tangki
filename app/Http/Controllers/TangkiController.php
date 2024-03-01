<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tangki;

class TangkiController extends Controller
{
    public function updateTangki(Request $request)
    {


        $tangki = Tangki::find($request->id);

        $tangki->request_status = $request->status;
        $tangki->save();

        return redirect()->back()->with('success', 'status updated.');
    }
}
