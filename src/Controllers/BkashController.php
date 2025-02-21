<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Tipusultan\Bkash\Services\BkashService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

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
        $website_url = url('/');
        
        $body_data = [
            'mode' => '0011',
            'payerReference' => auth()->id(),
            'callbackURL' => $website_url.'/bkash/callback',
            'amount' => $request->amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => "Inv_".Str::random(6)
        ];

        $response = $this->bkash->createPayment($body_data);
        
        return isset($response['bkashURL']) 
            ? redirect($response['bkashURL']) 
            : redirect()->route('url-pay')->with('error', 'Payment creation failed');
    }

    public function callback(Request $request)
    {
        Log::info('bKash Callback Request', ['payload' => $request->all()]);
        
        if ($request->status == 'failure' || $request->status == 'cancel') {
            return view('bkash.fail', ['response' => 'Payment Failed!']);
        }

        $response = $this->bkash->executePayment($request->paymentID);
        
        return isset($response['trxID']) 
            ? view('bkash.success', ['response' => $response['trxID']]) 
            : view('bkash.fail', ['response' => 'Payment Failed!']);
    }

    public function getRefund()
    {
        return view('bkash.refund');
    }

    public function refundPayment(Request $request)
    {
        $response = $this->bkash->refundPayment([
            'paymentID' => $request->paymentID,
            'trxID' => $request->trxID,
            'amount' => $request->amount
        ]);

        return view('bkash.refund', [
            'response' => isset($response['refundTrxID']) 
                ? "Refund successful! TrxID: ".$response['refundTrxID'] 
                : "Refund failed!"
        ]);
    }
}
