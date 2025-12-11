<?php

namespace App\Http\Controllers;

use App\Models\{Account, User};
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class AccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index($accountNumber = null)
    {
        $user = User::find(Auth::id());
        if ( !is_null( $accountNumber ) ) {
            $retriveAccount = Account::where([
                'user_id' => $user->id,
                'account_number' => $accountNumber
            ])->get(); 
        }
        else{
            $retriveAccount = $user->accounts()->get();
        }
        
        if ($retriveAccount->isNotEmpty()) {
            return $this->sendResponse( $retriveAccount , 'Successfull.');
        }else{
            return $this->sendError('Account not found.', [] );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function generateAccountNumber() {
        $accountNumber = mt_rand(10000000, 99999999);
        if($this->accountNumberExists($accountNumber)){
            return $this->generateAccountNumber();
        }
        return $accountNumber;
    }

    public function accountNumberExists($accountNumber) {
        return Account::whereAccountNumber($accountNumber)->exists();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$input = $request->all();
        $user = User::find(Auth::id());
        $userAccount = [
            'user_id' => $user->id,
            'account_number' => $this->generateAccountNumber(),
            'balance' => 0
        ];
       
        Account::create($userAccount);
        $success = [
            'UserName' => $user->name,
            'UserAccountNumber' => $userAccount['account_number'],
            'UserAccountBallance' => $userAccount['balance']
            
        ];
       
        return $this->sendResponse( $success , 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
