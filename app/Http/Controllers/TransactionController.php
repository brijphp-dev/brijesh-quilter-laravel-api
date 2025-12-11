<?php

namespace App\Http\Controllers;

use App\Models\{Account, Transaction, User};
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransactionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index($accountNumber)
    {
        $user = User::find(Auth::id());
        $retriveAccount = Account::where([
            'user_id' => $user->id,
            'account_number' => $accountNumber
        ])->first();
        $transactions = $retriveAccount->transactions()->paginate(10);
        return $this->sendResponse( $transactions , 'Successfull.');
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($accountNumber, Request $request)
    {
        $input = $request->all();
       
        $validator = Validator::make($input, [
            't_type' => [
                'required',
                Rule::in(['w', 'd']),
            ],
            't_amount' => 'required|numeric'
        ],[
            't_type.required' => 'Transaction type is required',
            't_type.in' => 'Transaction type must either "w" or "d"',
            't_amount.required' => 'Transaction amount is required',
            't_amount.numeric' => 'Valid transaction amount is required',
        ]);
       
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()->all());       
        }
        $user = User::find(Auth::id());
        $retriveAccount = Account::where([
            'user_id' => $user->id,
            'account_number' => $accountNumber
        ])->first();

        if ($retriveAccount) {
            if( strtolower( $input['t_type'] ) == 'w'){
                $transaction_type = 'withdraw';
                if( $retriveAccount->balance < $input['t_amount'] ){
                    return $this->sendError('Insufficient balance.', [] );
                }
                $retriveAccount->balance -= $input['t_amount'];
            }else {
                $transaction_type = 'deposit';
                $retriveAccount->balance += $input['t_amount'];
            }
            
            $retriveAccount->save();
            // Create a new transaction record
            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->account_id = $retriveAccount->id;
            $transaction->transaction_type = $transaction_type;
            $transaction->transaction_amount = $input['t_amount'];
            $transaction->save();
            
            return $this->sendResponse( $retriveAccount , 'Successfull.');
        }else{
            return $this->sendError('Account not found.', [] );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
