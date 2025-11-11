<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
use App\Models\WithdrawRequest;
use App\Services\FirebasePushNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type'                  => 'nullable|in:deposit,withdraw',
            'status'                => 'nullable|in:pending,completed,failed',
        ]);
        $status = $request->query('status') ?? 'completed';
        $user = Auth::guard('sanctum')->user();
        $user->load('wallet');

        if (!$user->wallet) {
            return response()->json([
                'message'                   => __('No Wallet Found'),
            ], 401);
        }

        $transactions = Transaction::where('wallet_id', $user->wallet->id)
            ->latest()
            ->where('status', $status)
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->query('type'));
            })->paginate(10);

        return response()->json([
            'wallet'        => new WalletResource($user->wallet),
            'transactions'  => TransactionResource::collection($transactions),
            'meta'          => $transactions->toArray()['meta'] ?? [],
            'links'         => $transactions->toArray()['links'] ?? [],
        ]);
    }

    public function chargeWallet(Request $request , FirebasePushNotifications $notification)
    {
        $request->validate([
            'amount'                => 'required|numeric|min:0',
        ]);

        $user = Auth::guard('sanctum')->user();

        $user->load('wallet');

        if (!$user->wallet) {
            return response()->json([
                'message'                   => __('No Wallet Found'),
            ], 401);
        }

        //TODO: redirect to payment gateway and update the transaction from there
        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'wallet_id'             => $user->wallet->id,
                'user_name'             => $user->name,
                'type'                  => 'deposit',
                'amount'                => $request->amount,
                'status'                => 'completed'
            ]);

            $user->wallet()->update([
                'balance' => DB::raw("balance + {$request->amount}")
            ]);
            // to do:: for testing apis

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        // TODO: uncomment
        // if($user->fcm_token){
        //     $notification->sendPushNotification(
        //         $user->fcm_token,
        //         "New Charge Added To Your Wallet",
        //         "{$request->amount} Added To Your Wallet",
        //         [
        //             'id'        =>$transaction->id,
        //         ],
        //     );
        // }

        return new TransactionResource($transaction);
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'amount'                =>'required|numeric|integer|min:1',
        ]);

        $user = Auth::guard('sanctum')->user();
        $user->load('wallet');
        $wallet = $user->wallet;

        if(! $wallet){
            return response()->json([
                'message'               =>__('No Wallet Found'),
            ],401);
        }

        if($wallet->balance < $request->amount){
            return response()->json([
                'message'               =>__('Insufficient Wallet Amount'),
            ],401);
        }

        WithdrawRequest::create([
            'user_id'                   =>$user->id,
            'amount'                    =>$request->amount,
        ]);

        return response()->json([
            'message'               =>__('Request Has Been Added'),
        ],201);
    }

    public function myRequests(Request $request)
    {
        $request->validate([
            'status'                  =>'nullable|in:pending,completed',
        ]);

        $user = Auth::guard('sanctum')->user();

        $requests = WithdrawRequest::where('user_id',$user->id)
            ->latest()
            ->when($request->filled('type'),function ($q) use ($request){
                $q->where('status',$request->query('status'));
            })->paginate(10);

        return response()->json([
            'requests'                  =>$requests ,
        ]);
    }
}
