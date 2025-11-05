<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
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

    public function chargeWallet(Request $request)
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

        //to do: redirect to payment gateway and update the transaction from there
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
            return new TransactionResource($transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
