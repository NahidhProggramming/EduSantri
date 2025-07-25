<?php

namespace App\Services;

use CURLFile;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        if (empty($this->token)) {
            \Log::error('Fonnte token is empty! Check .env and config.');
        } else {
            \Log::debug('FONNTE_TOKEN:', [$this->token]);
        }
    }

    public function sendText($phone, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone, // format: 628xxxx
            'message' => $message,
            'countryCode' => '62',
        ]);

        \Log::debug('WA Response', ['response' => $response->json()]);
        return $response->json();
    }

  
    public function checkToken()
    {
        try {
            $response = Http::withToken($this->token)
                ->get('https://api.fonnte.com/me');

            $result = $response->json();

            if (isset($result['status']) && $result['status']) {
                return ['valid' => true, 'message' => 'Token valid', 'data' => $result];
            }

            return [
                'valid' => false,
                'message' => $result['reason'] ?? 'Token tidak valid'
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
