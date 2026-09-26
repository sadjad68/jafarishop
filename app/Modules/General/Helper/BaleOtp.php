<?php

namespace App\Modules\General\Helper;

use App\Library\NumberHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaleOtp
{
    public function send(string $mobile, string|int $otp): bool
    {
        $clientId = config('services.bale.client_id');
        $clientSecret = config('services.bale.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            Log::warning('Bale OTP credentials are not configured.');
            return false;
        }

        try {
            $baleMobile = NumberHelper::persian2LatinDigit($mobile);
            if (str_starts_with($baleMobile, '0')) {
                $baleMobile = '98' . substr($baleMobile, 1);
            }

            $tokenResponse = Http::asForm()
                ->connectTimeout(10)
                ->timeout(15)
                ->post('https://safir.bale.ai/api/v2/auth/token', [
                    'grant_type' => 'client_credentials',
                    'client_secret' => $clientSecret,
                    'scope' => 'read',
                    'client_id' => $clientId,
                ]);

            if (! $tokenResponse->successful()) {
                Log::error('Bale token request failed.', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->body(),
                ]);
                return false;
            }

            $accessToken = $tokenResponse->json('access_token');
            $otpResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])
                ->connectTimeout(10)
                ->timeout(15)
                ->post('https://safir.bale.ai/api/v2/send_otp', [
                    'phone' => $baleMobile,
                    'otp' => (string) $otp,
                ]);

            if ($otpResponse->successful()) {
                Log::info('Bale OTP sent successfully.', ['phone' => $baleMobile]);
                return true;
            }

            Log::error('Bale OTP send failed.', [
                'status' => $otpResponse->status(),
                'body' => $otpResponse->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Bale OTP exception: ' . $e->getMessage());
            return false;
        }
    }
}
