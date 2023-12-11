<?php

namespace App\Http\Controllers;

use App\Models\VCard;
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
    public function show(Transaction $transaction){
        return new TransactionResource($transaction);
        //$transaction = Transaction::find($transaction);
    }
    public function store(Request $request)
    {
        $rules = [
            
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $vcard = VCard::find($request->input('vcard')); 

        if(!$vcard){
            return response()->json(['No Vcard' => $validator->errors()], 423);
        }

        if($request->input('value') > $vcard->balance){
            return response()->json(['No cash'=> $validator->error()], 425);
        }
        $new_balance = $vcard->balance;
        if($request->input('type') == 'D'){
            $new_balance = $vcard->balance - $request->input('value');
        }else{
            $new_balance = $vcard->balance + $request->input('value');
        }
       
        $newTransaction = Transaction::create([
            'vcard' => $request->input('vcard'),
            'date' =>  Carbon::now(),
            'datetime' =>  Carbon::now(),
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'pair_vcard' => $request->input('pair_vcard'),
            'old_balance' => $vcard->balance,
            'new_balance' => $new_balance,
            'payment_type' => $request->input('payment_type'),
            'payment_reference' => $request->input('payment_reference'),
            'description' => $request->input('description'),
        ]);
        if($request->input('type') == 'D'){
            $vcard->balance = $vcard->balance - $request->input('value');
        }else{
            $vcard->balance = $vcard->balance + $request->input('value');
        }
        $vcard->save();
        return new TransactionResource($newTransaction);
    }
}
