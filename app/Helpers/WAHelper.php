<?php

namespace App\Helpers;
use Auth;
use LogicException;

class WAHelper
{
	static function sendMessage($phone, $message)
	{
		$phone = self::parsePhone($phone);
		$data = [
            'ApiKey'    => getEnv('WA_KEY'),
            'Phone'     => $phone,
            'Message'   => $message
            // 'Message'   => str_replace('$DATE$', date('d-m-Y H:i:s', strtotime('+2 minutes')), str_replace('\n', PHP_EOL, str_replace('$OTP$', $otp, getEnv('WA_MESSAGE'))))
        ];

        try {
            $response = self::request('POST', getEnv('WA_URL').'v5/send', $data);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() == 200 && json_decode($response->getBody())->status) {
            return json_decode($response->getBody());
        }else{
            return ['status' => false];
        }
	}

	private static function request($method='GET', $url=null, $data=[], $headers=[])
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $url, [
                        'json' => $data,
                        'headers' => array_merge([
                            'User-Agent' => 'SIRAJA/'.date('Y'),
                        ], $headers)
                    ]);

        return $response;
    }

    private static function parsePhone($number=null)
    {
        if($number == null) {
            return $number;
        }

        $number = str_replace(" ","",$number);
        $number = str_replace("'","",$number);
        $number = str_replace("\"","",$number);
        $number = str_replace("-","",$number);
        $number = str_replace("(","",$number);
        $number = str_replace("*","",$number);
        $number = str_replace("^","",$number);
        $number = str_replace(")","",$number);
        $number = str_replace(".","",$number);
        $number = str_replace(",","",$number);
        $number = str_replace("/","",$number);
        $number = str_replace("?","",$number);

        $number = preg_replace('/[a-z]/i', '', $number);
        // dd($number);
        preg_match_all('!\d+!', $number, $no);
        $no = $no[0][0];


        $phone = null;


        // 0 TO 62
     //     if(substr(trim($no), 0, 2)=='62'){
     //     $phone = trim($no);
     //     }elseif(substr(trim($no), 0, 1)=='0'){
     //     $phone = '62'.substr(trim($no), 1);
        // }elseif(substr(trim($no), 0, 1)=='+'){
     //     $phone = substr(trim($no), 1);
        // }

        // 62 TO 0
        if(substr(trim($no), 0, 1)=='0'){
            $phone = trim($no);
        }elseif(substr(trim($no), 0, 2)=='62'){
            $phone = '0'.substr(trim($no), 2);
        }elseif(substr(trim($no), 0, 3)=='+62'){
            $phone = '0'.substr(trim($no), 3);
        }

        return $phone;
    }
}
