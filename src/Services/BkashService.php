<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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

        // Log::info('bKash API Config:', [
        //     'sandbox' => config('bkash.sandbox'),
        //     'username' => $this->username,
        //     'password' => $this->password ? '*******' : 'MISSING',
        //     'app_key' => $this->app_key
        // ]);
    }

    public function getToken()
    {
        if (Cache::has('bkash_token')) {
            return Cache::get('bkash_token');
        }

        $token = $this->generateToken();
        if ($token) {
            Cache::put('bkash_token', $token, now()->addMinutes(55));
        }

        return $token;
    }

    public function generateToken()
{
    $header = [
        'Content-Type: application/json',
        'username: ' . $this->username,
        'password: ' . $this->password
    ];

    $body_data = [
        'app_key' => $this->app_key,
        'app_secret' => $this->app_secret
    ];

    //Log::info('bKash Token Request:', ['headers' => $header, 'body' => $body_data]);

    $response = $this->curlWithBody('/tokenized/checkout/token/grant', 'POST', $header, $body_data);

    //Log::info('bKash Token API Response:', ['raw_response' => $response]);

    // Ensure response is a valid string before decoding
    if (!is_string($response)) {
      //  Log::error('bKash Token API returned invalid response format', ['response' => $response]);
        return null;
    }

    $decodedResponse = json_decode($response, true);

    if (!isset($decodedResponse['id_token'])) {
        Log::error('bKash Token Generation Failed:', ['response' => $decodedResponse]);
        return null;
    }

    return $decodedResponse['id_token'];
}


private function authHeaders()
{
    $token = $this->getToken();

    // Log::info('bKash API Authorization Headers:', [
    //     'Authorization' => $token ? 'Bearer ' . $token : 'No Token',
    //     'X-APP-Key' => $this->app_key
    // ]);

    return [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
        'X-APP-Key: ' . $this->app_key
    ];
}

    private function curlWithBody($url, $method, $header, $data = null)
    {
        $full_url = $this->base_url . $url;
    
        // Log::info('bKash API Request', [
        //     'url' => $full_url,
        //     'method' => $method,
        //     'headers' => $header,
        //     'body' => json_encode($data)
        // ]);
    
        $curl = curl_init($full_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            Log::error('cURL Error:', ['error' => curl_error($curl)]);
        }
    
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        Log::info('bKash API Response', [
            'url' => $full_url,
            'http_code' => $httpCode,
            'response' => $response
        ]);
    
        return is_string($response) ? $response : json_encode($response);
    }
    

    public function createPayment($data)
    {
        return $this->curlWithBody('/tokenized/checkout/create', 'POST', $this->authHeaders(), $data);
    }

    public function executePayment($paymentID)
    {
        return $this->curlWithBody('/tokenized/checkout/execute', 'POST', $this->authHeaders(), ['paymentID' => $paymentID]);
    }

    public function queryPayment($paymentID)
    {
        return $this->curlWithBody('/tokenized/checkout/payment/status', 'POST', $this->authHeaders(), ['paymentID' => $paymentID]);
    }

    public function refundPayment($data)
    {
        return $this->curlWithBody('/tokenized/checkout/payment/refund', 'POST', $this->authHeaders(), $data);
    }
}
