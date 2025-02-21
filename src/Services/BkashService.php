<?php

namespace Tipusultan\Bkash\Services;

class BkashService
{
    private $base_url;
    private $username;
    private $password;
    private $app_key;
    private $app_secret;

    public function __construct()
    {
        $this->base_url = config('bkash.sandbox') 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta' 
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta';
        $this->username = config('bkash.username');
        $this->password = config('bkash.password');
        $this->app_key = config('bkash.app_key');
        $this->app_secret = config('bkash.app_secret');
    }

    public function createPayment($data)
    {
        return $this->curlCall('/tokenized/checkout/create', 'POST', $data);
    }

    public function executePayment($paymentID)
    {
        return $this->curlCall('/tokenized/checkout/execute', 'POST', ['paymentID' => $paymentID]);
    }

    public function refundPayment($data)
    {
        return $this->curlCall('/tokenized/checkout/payment/refund', 'POST', $data);
    }

    private function curlCall($url, $method, $data = null)
    {
        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: '.$this->getToken(),
            'X-APP-Key: '.$this->app_key
        ]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    private function getToken()
    {
        return 'YOUR_GENERATED_BKASH_TOKEN';
    }
}
