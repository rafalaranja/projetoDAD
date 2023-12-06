<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::all();
        return response ()->json(['data'=>$transactions],200);
    }
    public function show($id){
        $transaction = Transaction::find($id);
        if(!$transaction){
            return response()->json(['message'=> 'Transaction not found'],404);
        }
        return response()->json(['data'=>$transaction],200);

    }
    public function store(Request $request)
    {
        $newTransaction = Transaction::create([
            'vcard' => $request->input('vcard'),
            'date' =>  Carbon::now(),
            'datetime' =>  Carbon::now(),
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'pair_vcard' => $request->input('pair_vcard'),
            'old_balance' => $request->input('old_balance'),
            'new_balance' => $request->input('new_balance'),
            'payment_type' => $request->input('payment_type'),
            'payment_reference' => $request->input('payment_reference'),
            'description' => $request->input('description'),
        ]);
        return new TransactionResource($newTransaction);
    }
}
