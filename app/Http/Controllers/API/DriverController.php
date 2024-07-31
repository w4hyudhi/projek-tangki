<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverAuthRequest;
use App\Http\Resources\DriverResponse;
use App\Models\Tangki;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DriverController extends Controller
{
    public function register(DriverAuthRequest $request)
    {

        //  return json_encode($request->all());

        if($request->hasFile('image')){
            $dokumen = $request->file('image');
            $profile = 'FT'.date('Ymdhis').'.'.$request->file('image')->getClientOriginalExtension();
            $dokumen->move('images/',$profile);
        }

        if($request->hasFile('store_image')){
            $dokumen = $request->file('store_image');
            $nama_dokumen = 'FT'.date('Ymdhis').'.'.$request->file('store_image')->getClientOriginalExtension();
            $dokumen->move('store/',$nama_dokumen);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => 'DRIVER',
            'password' => Hash::make($request->password),
            'photo_path' => 'images/'.$profile,
        ]);

        $tangki = Tangki::create([
            'user_id' => $user->id,
            'name' => $request->store_name,
            'type' => $request->type,
            'price' => $request->price,
            'status' => 'Non-Active',
            'request_status' => 0,
            'scedule' => $request->scedule,
            'description' => $request->description,
            'photo_path' => 'store/'.$nama_dokumen,
        ]);

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return ResponseFormatter::success([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'data' => DriverResponse::make($user),

        ],'Authenticated');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required',
                'password' => 'required'
            ]);
            $credentials = request(['phone', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],'Authentication Failed', 500);
            }


            $user = User::where('phone', $request->phone)->where('ROLE', 'DRIVER')->first();
            if ( ! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    function profile(Request $request){
        $user = Auth::user();
        return DriverResponse::make($user);
    }

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => 'error get data'], 403);
        }

        $user = Auth::user();

        $user->update([
            'fcm_token' => $request->fcm_token
        ]);

        return response()->json(['message'=>'successfully updated!'], 200);
    }

    function update_active_status(){
        $user = Auth::user();
        $tangki = Tangki::where('user_id', $user->id)->first();
        $tangki->active = $tangki->active == 0 ? 1 : 0;
        $tangki->save();
        return ResponseFormatter::success([
            'tangki' => $tangki
        ],'Tangki Updated');
    }

    function tes_conection(){
        return response()->json(['message'=>'success'], 200);
    }

    public function record_location_data(Request $request)
    {
        $user = Auth::user();
        DB::table('delivery_histories')->insert([
            'user_id' => $user->id,
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'time' => now(),
            'location' => $request['location'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'location recorded'], 200);
    }

    public function dashboardDriver()
    {
$tangkiId = Auth::user()->tangki->id;
$totalEarnings = DB::table('transactions')
    ->where('payment', 'paid')
    ->where('tangki_id', $tangkiId)
    ->sum('price');

$earningsToday = DB::table('transactions')
    ->where('payment', 'paid')
    ->where('tangki_id', $tangkiId)
    ->whereDate('created_at', Carbon::today())
    ->sum('price');

$earningsThisWeek = DB::table('transactions')
    ->where('payment', 'paid')
    ->where('tangki_id', $tangkiId)
    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
    ->sum('price');

$earningsThisMonth = DB::table('transactions')
    ->where('payment', 'paid')
    ->where('tangki_id', $tangkiId)
    ->whereMonth('created_at', Carbon::now()->month)
    ->sum('price');

    $totalTransactions = DB::table('transactions')
        ->where('status', 'pesanan selesai')
        ->where('tangki_id', $tangkiId)
        ->count();

    $transactionsToday = DB::table('transactions')
        ->where('status', 'pesanan selesai')
        ->where('tangki_id', $tangkiId)
        ->whereDate('created_at', Carbon::today())
        ->count();

    $transactionsThisWeek = DB::table('transactions')
        ->where('status', 'pesanan selesai')
        ->where('tangki_id', $tangkiId)
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->count();

    $transactionsThisMonth = DB::table('transactions')
        ->where('status', 'pesanan selesai')
        ->where('tangki_id', $tangkiId)
        ->whereMonth('created_at', Carbon::now()->month)
        ->count();

    return response()->json([
        'total_earnings' => (int) $totalEarnings,
        'earnings_today' => (int) $earningsToday,
        'earnings_this_week' => (int) $earningsThisWeek,
        'earnings_this_month' => (int) $earningsThisMonth,
        'total_transactions' => (int) $totalTransactions,
        'transactions_today' => (int) $transactionsToday,
        'transactions_this_week' => (int) $transactionsThisWeek,
        'transactions_this_month' => (int) $transactionsThisMonth,
    ]);
    }
}
