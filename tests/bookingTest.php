<?php
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    private $baseUrl = 'http://localhost/tourism/classes/Master.php?f=book_tour';

    public function testBookingSuccess()
    {
        $postData = [
            'user_id' => 3,
            'package_id' => 8,
            'schedule' => '2025-06-05',
        ];

        $response = $this->sendPostRequest($postData);

        echo "\nRaw Response:\n" . $response . "\n";

        // Decode respon JSON
        $json = json_decode($response, true);

        // Assertion
        $this->assertNotFalse($response, 'Response should not be false or empty');
        $this->assertIsArray($json, 'Response should be valid JSON array');
        $this->assertArrayHasKey('status', $json, 'Response should contain status');
        $this->assertEquals('success', $json['status'], 'Booking should succeed');
    }

    private function sendPostRequest($data)
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);

        // Debugging error jika gagal
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
        return $response;
    }
}