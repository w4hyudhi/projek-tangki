<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResponse;
use App\Models\Status;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\helpers;
use App\Http\Resources\TransactionUserResponse;
use App\Models\Review;

class TransactionController extends Controller
{

    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');

        if($id)
        {
            $transaction = Transaction::with(['status_booking','tangki.user','address','review.user'])->find($id);

            if($transaction)
            return new TransactionUserResponse($transaction);
            else
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
        }



        $transaction = Transaction::with(['status_booking','tangki.user','address','review.user'])->where('user_id', Auth::user()->id);

        if($status)
            $transaction->where('status', $status);

        // return ResponseFormatter::success(
        //     $transaction->paginate($limit),
        //     'Data list transaksi berhasil diambil'
        // );
        return TransactionUserResponse::collection($transaction->get());
    }

    public function checkout(Request $request)
    {
        $request->validate([

            'tangki_id' => 'exists:tangkis,id',
            'address_id' => 'exists:addresses,id',
            'price' => 'required',

            // 'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED',
        ]);



        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'tangki_id' => $request->tangki_id,
            'address_id' => $request->address_id,
            'price' => $request->price,
            'description' => $request->description,
            'status'=>'Menunggu Konfirmasi',
            'payment'=>'unpaid'
        ]);

        $status = Status::create([
            'transaction_id' => $transaction->id,
            'status' =>  $transaction->status,

        ]);

        $fcm_token =$transaction->tangki->user->fcm_token;
        try{
            if($fcm_token){
                $data = [
                    'title' => 'Pesanan Baru',
                    'description' => 'Anda memiliki pesanan baru dari '.$transaction->user->name,
                    'order_id' => $transaction->id,
                    'image' => '',
                    'type'=> 'order_status'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch(\Exception $e){

        }




        return ResponseFormatter::success($transaction->load('status_booking','tangki.user','address'), 'Transaksi berhasil');
    }

    public function update(Request $request)
    {

            $request->validate([
            'id' => ['required'],
            'status' => ['required'],
            ]);
            $data = $request->all();
            $transaction = Transaction::find($request->id);
            $transaction->update($data);

            $status = Status::create([
                'transaction_id' => $request->id,
                'status' => $request->status,

            ]);
            $transaction = Transaction::with(['status_booking','tangki.user','address'])->find($request->id);


        $fcm_token =$transaction->user->fcm_token;
        try{
            if($fcm_token){
                $data = [
                    'title' => 'Update Pesanan',
                    'description' => 'Pesana Air Bersaih anda pada '.$transaction->tangki->name.' telah diupdate menjadi '.$transaction->status,
                    'order_id' => $transaction->id,
                    'image' => '',
                    'type'=> 'order_status'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch(\Exception $e){

        }

            if($transaction)
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
        }

        public function driver(Request $request)
        {
            $id = $request->input('id');
            $status = $request->input('status');
            $active = $request->input('active');

            if($id)
            {
                $transaction = Transaction::with(['status_booking','tangki.user','address'])->find($id);

                if($transaction)
                    return new TransactionResponse($transaction);
                else
                    return ResponseFormatter::error(
                        null,
                        'Data transaksi tidak ada',
                        404
                    );
            }


            // return json_encode($request->user()->tangki);
            $transaction = Transaction::with(['status_booking','user','address'])->where('tangki_id', Auth::user()->tangki->id);

            if($status)
                $transaction->where('status', $status);

            if($active)
                $transaction->whereNotIn('status', ['Pesanan Dibatalkan', 'Pesanan Gagal', 'Pesanan Selesai']);

           return TransactionResponse::collection($transaction->get());
        }

        public function update_paid_status(Request $request)
        {
            $request->validate([
                'id' => ['required'],
                'payment' => ['required'],
            ]);
            $data = $request->all();
            $transaction = Transaction::find($request->id);
            $transaction->update($data);

            $transaction = Transaction::with(['status_booking','tangki.user','address'])->find($request->id);

            if($transaction)
                return ResponseFormatter::success(
                    $transaction,
                    'Data pembayaran berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
        }

        public function create_review(Request $request)
        {

            $request->validate([
                'id' => ['required'],
                'comments' => ['required'],
                'star' => ['required'],
            ]);
            $transaction = Transaction::find($request->id);


    if (Review::where('transaction_id', $request->id)->exists()) {
        return ResponseFormatter::error(null, 'Review for this transaction already exists', 400);
    }

            $review = Review::create([
                'transaction_id' => $request->id,
                'user_id' => $transaction->user_id,
                'tangki_id' => $transaction->tangki_id,
                'comments' => $request->comments,
                'star' => $request->star,
            ]);

            return ResponseFormatter::success($review, 'Review berhasil ditambahkan');
        }





}
