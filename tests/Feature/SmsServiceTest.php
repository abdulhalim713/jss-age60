<?php

namespace Tests\Feature;

use App\Services\SmsService;
use App\Models\Alumni;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if SmsService correctly normalizes BD phone numbers and sends HTTP query requests.
     */
    public function test_sms_service_sends_request_correctly(): void
    {
        Http::fake([
            'bulksmsbd.net/api/smsapi*' => Http::response([
                'response_code' => 202,
                'message_id' => 12345,
                'success_message' => 'SMS Submitted Successfully',
                'error_message' => ''
            ], 202)
        ]);

        // Standard 11 digit BD numbers starting with 0 should be prefixed with 88
        $result = SmsService::send('01712345678', 'Test Message');

        $this->assertTrue($result['success']);
        $this->assertEquals(202, $result['data']['response_code']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'bulksmsbd.net/api/smsapi') &&
                $request['number'] === '8801712345678' &&
                $request['message'] === 'Test Message' &&
                $request['api_key'] === 'W6uYRXsPj2nLHfPJ3YCC' &&
                $request['senderid'] === '8809617625226';
        });
    }

    /**
     * Test if the public registration controller triggers the SMS on register.
     */
    public function test_public_registration_triggers_sms(): void
    {
        Http::fake([
            'bulksmsbd.net/api/smsapi*' => Http::response([
                'response_code' => 202,
                'success_message' => 'SMS Submitted Successfully',
            ], 202)
        ]);

        $response = $this->post('/alumni/register', [
            'name' => 'John Doe',
            'batch' => '2010',
            'phone' => '01812345678',
            'email' => 'john@example.com',
            'address' => 'Dhaka',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('alumni', [
            'name' => 'John Doe',
            'phone' => '01812345678',
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'bulksmsbd.net/api/smsapi') &&
                $request['number'] === '8801812345678' &&
                str_contains($request['message'], 'জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের');
        });
    }
}
