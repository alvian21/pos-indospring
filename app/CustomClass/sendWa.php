<?php

namespace App\CustomClass;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Bitly;


class sendWa
{

    function __construct($phone, $otp)
    {
        $this->phone = $phone;
        $this->otp = $otp;
    }

    function two_factor_code()
    {

        $phone = $this->phone;
        $otp = $this->otp;

        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json', 'API-Key' => config('app.API_KEY')]
            ]);
            $body = 'Koperasi Karyawan PT. Indospring, Tbk. Your two factor code is : ' . $otp . '. Test Otp';

            $data = json_encode([
                'phone' => $phone,
                'messageType' => 'otp',
                'body' => $body
            ]);
            $response = $client->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
                'body' => $data
            ]);

            $getstatus = $response->getStatusCode();
            $getbody = json_decode($response->getBody()->getContents(), true);

            $resbody = $getbody['data'];
            $success = $resbody['success'];
            $message = $resbody['message'];
            $res = [
                'success' => $success,
                'message' => $message,
            ];

            return $res;
        } catch (BadResponseException $th) {
            return [
                'status' => 400,
                'message' => $th->getMessage()
            ];
        }
    }


    function text()
    {

        $phone = $this->phone;
        $otp = $this->otp;

        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json', 'API-Key' => config('app.API_KEY')]
            ]);
            $body = 'Koperasi Karyawan PT. Indospring, Tbk. Your text is : ' . $otp;

            $data = json_encode([
                'phone' => $phone,
                'messageType' => 'otp',
                'body' => $body
            ]);
            $response = $client->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
                'body' => $data
            ]);

            $getstatus = $response->getStatusCode();
            $getbody = json_decode($response->getBody()->getContents(), true);

            $resbody = $getbody['data'];
            $success = $resbody['success'];
            $message = $resbody['message'];
            $res = [
                'success' => $success,
                'message' => $message,
            ];

            return $res;
        } catch (BadResponseException $th) {
            return [
                'status' => 400,
                'message' => $th->getMessage()
            ];
        }
    }



    function verification()
    {
        $phone = $this->phone;
        $otp = $this->otp;

        try {
            $url = Bitly::getUrl($otp);
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json', 'API-Key' => config('app.API_KEY')]
            ]);
            $body = ' Silahkan klik ini untuk melakukan registrasi :'."\n\n" . $url ;

            $data = json_encode([
                'phone' => $phone,
                'messageType' => 'otp',
                'body' => $body
            ]);
            $response = $client->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
                'body' => $data
            ]);

            $getstatus = $response->getStatusCode();
            $getbody = json_decode($response->getBody()->getContents(), true);

            $resbody = $getbody['data'];
            $success = $resbody['success'];
            $message = $resbody['message'];
            $res = [
                'status' => $getstatus,
                'success' => $success,
                'message' => $message,
            ];

            return $res;
        } catch (BadResponseException $th) {
            return [
                'status' => 400,
                'message' => $th->getMessage()
            ];
        }
    }


    function verification_success()
    {
        $phone = $this->phone;

        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json', 'API-Key' => config('app.API_KEY')]
            ]);
            $body = 'Koperasi Karyawan PT. Indospring, Tbk. Hello, ID Anda telah di verifikasi dan sudah bisa digunakan';

            $data = json_encode([
                'phone' => $phone,
                'messageType' => 'otp',
                'body' => $body
            ]);
            $response = $client->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
                'body' => $data
            ]);

            $getstatus = $response->getStatusCode();
            $getbody = json_decode($response->getBody()->getContents(), true);

            $resbody = $getbody['data'];
            $success = $resbody['success'];
            $message = $resbody['message'];
            $res = [
                'status' => $getstatus,
                'success' => $success,
                'message' => $message,
            ];

            return $res;
        } catch (BadResponseException $th) {
            return [
                'status' => 400,
                'message' => $th->getMessage()
            ];
        }
    }
}
