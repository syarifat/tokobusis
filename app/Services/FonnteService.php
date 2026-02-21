<?php

namespace App\Services;

class FonnteService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function sendMessage($target, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Kode negara Indonesia
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $this->token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        return $response;
    }
}