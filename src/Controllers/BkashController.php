<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BkashService;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BkashController extends Controller
{
    private $bkash;

    public function __construct(BkashService $bkash)
    {
        $this->bkash = $bkash;
    }

    public function payment()
    {
        return view('bkash.pay');
    }

    public function createPayment(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
    
        $website_url = url('/');
    
        $body_data = [
            'mode' => '0011',
            'payerReference' => auth()->id(),
            'callbackURL' => $website_url . '/bkash/callback',
            'amount' => $request->amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => "Inv_" . uniqid()
        ];
    
        $response = $this->bkash->createPayment($body_data);
    
//Log::info('bKash Payment Response:', ['response' => $response]);
    
        // Ensure response is decoded correctly
        if (is_string($response)) {
            $response = json_decode($response, true);
        }
    
        // Check if `bkashURL` exists
        if (isset($response['bkashURL'])) {
           // Log::info('Redirecting to bKash:', ['url' => $response['bkashURL']]);
            return redirect()->away($response['bkashURL']); // Redirect properly
        }
    
        //::error('bKash Create Payment Failed', ['response' => $response]);
        return back()->withErrors(['error' => 'Payment creation failed.']);
    }
    

    public function callback(Request $request)
    {
        //Log::info('bKash Callback Request:', ['payload' => $request->all()]);
    
        if ($request->status === 'failure' || $request->status === 'cancel') {
            return view('bkash.fail', ['response' => 'Payment Failed!']);
        }
    
        // Execute payment to get transaction details
        $response = $this->bkash->executePayment($request->paymentID);
    
        Log::info('bKash Execute Payment Response:', ['response' => $response]);
    
        // Ensure response is decoded correctly
        if (is_string($response)) {
            $response = json_decode($response, true);
        }
    
        // Check if payment was successful
        if (isset($response['transactionStatus']) && $response['transactionStatus'] === 'Completed') {
    
            $user = Auth::user();
            if (!$user) {
                Log::error('User not authenticated during payment callback');
                return view('bkash.fail', ['response' => 'User authentication failed!']);
            }
    
            // // Store Payment in Database
            // Payment::create([
            //     'user_id' => $user->id,
            //     'amount' => $response['amount'],
            //     'payment_status' => 'completed',
            //     'Payment_type' => 'Bkash',
            //     'transaction_id' => $response['trxID']
            // ]);
    
            // Update User Balance
            $user->balance += $response['amount'];
            $user->save();
    
            // Log::info('Payment Successful & User Balance Updated', [
            //     'user_id' => $user->id,
            //     'trxID' => $response['trxID'],
            //     'new_balance' => $user->wallet_balance
            // ]);
           

            return $response;


         // ✅ Redirect to your custom URL after payment success
         return redirect('http://localhost/tipu/rexgamingbd/')
         ->with('success', 'Payment Success! Your balance has been updated.');
 }
    
    Log::error('bKash Payment Failed', ['response' => $response]);
    return view('bkash.fail', ['response' => 'Payment Failed!']);
    }
}
