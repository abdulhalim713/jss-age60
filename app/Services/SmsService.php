<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS to a phone number.
     *
     * @param string $number
     * @param string $message
     * @return array
     */
    public static function send(string $number, string $message): array
    {
        $apiUrl = config('services.bulksmsbd.api_url', 'http://bulksmsbd.net/api/smsapi');
        $apiKey = config('services.bulksmsbd.api_key', 'W6uYRXsPj2nLHfPJ3YCC');
        $senderId = config('services.bulksmsbd.sender_id', '8809617625226');

        // Normalize phone number: remove any non-digit characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);

        // If it's a standard BD number of 11 digits starting with 0, prefix with 88
        if (strlen($cleanNumber) === 11 && str_starts_with($cleanNumber, '0')) {
            $cleanNumber = '88' . $cleanNumber;
        }

        try {
            $response = Http::get($apiUrl, [
                'api_key' => $apiKey,
                'type' => 'text',
                'number' => $cleanNumber,
                'senderid' => $senderId,
                'message' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("SMS successfully sent to {$cleanNumber}.", ['response' => $data]);
                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error("Failed to send SMS to {$cleanNumber}. Status: {$response->status()}", ['body' => $response->body()]);
            return [
                'success' => false,
                'error' => "HTTP Status: {$response->status()}",
            ];
        } catch (\Exception $e) {
            Log::error("SMS sending exception for {$cleanNumber}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
