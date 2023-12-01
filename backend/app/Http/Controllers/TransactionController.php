<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

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
}
