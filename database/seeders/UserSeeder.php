<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{Account, Transaction, User};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionType= [1=>'d', 2=>'w'];
        //Create 3 New Users
        for ($i=0; $i <= 3; $i++) {
            $userPrefer = Str::random(5);
            $user = User::create([
                'name' => 'TestUser'. $userPrefer . $i,
                'email' => 'testuser' . strtolower( $userPrefer ) .$i.'@email.com',
                'password' => '123456'
            ]);
            $user_id = $user->id;

            // Create random number of accounts for each User
            for ($j=0; $j <= rand(2, 5); $j++) {
                $account = new Account();
                $account->user_id = $user_id;
                $account->account_number = mt_rand(10000000, 99999999);
                $account->balance = 100;
                $account->save();

                $account_id = $account->id;

                // Now let's create some transactions for each account
                for ($k=0; $k < rand(25, 40); $k++) { 
                    $t_type = $transactionType[rand(1,2)];
                    $t_amount = rand(1, 80);
                    if( strtolower( $t_type ) == 'w'){
                        $transaction_type = 'withdraw';
                        if( $account->balance < $t_amount ){
                            // Do nothing
                            $t_amount = 0;
                        }
                        $account->balance -= $t_amount;
                    }else {
                        $transaction_type = 'deposit';
                        $account->balance += $t_amount;
                    }
                    
                    $account->save();
                    // Create a new transaction record
                    $transaction = new Transaction;
                    $transaction->user_id = $user_id;
                    $transaction->account_id = $account_id;
                    $transaction->transaction_type = $transaction_type;
                    $transaction->transaction_amount = $t_amount;
                    $transaction->save();
                }
            }
        }
    }
}
