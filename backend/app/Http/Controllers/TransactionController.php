<?php

namespace App\Http\Controllers;

use App\Models\VCard;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function index(Request $request){
        $sortField = $request->get('sortField', 'date');
        $sortOrder = $request->get('sortOrder', 'desc');
    
        if (Auth::user()->user_type === 'A') {
            $transactions = Transaction::orderBy($sortField, $sortOrder)->paginate(10);
        } else {
            $transactions = Transaction::where('vcard', Auth::user()->id)->orderBy($sortField, $sortOrder)->paginate(10);
        }
    
        return $transactions;
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

    if (!$vcard) {
        return response()->json(['error'=>'No Vcard','messasge'=>'Sem vcard associado'], 422);
    }

    if($request->input('type') == 'D'){
        if ($request->input('value') > $vcard->balance) {
            return response()->json(['error' => 'No cash','message'=>'Fundos Insuficientes'], 422);
        }
    }

    $new_balance = ($request->input('type') == 'D') 
        ? $vcard->balance - $request->input('value')
        : $vcard->balance + $request->input('value');

    $transactionData = [
        'vcard' => $request->input('vcard'),
        'date' => Carbon::now(),
        'datetime' => Carbon::now(),
        'type' => $request->input('type'),
        'value' => $request->input('value'),
        'pair_vcard' => $request->input('pair_vcard'),
        'old_balance' => $vcard->balance,
        'new_balance' => $new_balance,
        'payment_type' => $request->input('payment_type'),
        'payment_reference' => $request->input('payment_reference'),
        'description' => $request->input('description'),
        'category_id' => $request->input('categorie'),
    ];

    $newTransaction = Transaction::create($transactionData);
    
    $vcard->balance = $new_balance;
    
    
    $vcard->save();


    return new TransactionResource($newTransaction);
}

public function loadStatistics(){
    $statistics = Transaction::select(Transaction::raw('MONTH(date) as month'), Transaction::raw('COUNT(*) as count'))
                    ->groupBy('month')
                    ->get();
    return $statistics;
}


}
