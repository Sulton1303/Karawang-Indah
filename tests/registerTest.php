<?php
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    public function testRegister()
    {
        $postData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'johndoe',
            'password' => 'password123',
        ];

        $url = 'http://localhost/tourism/classes/Master.php?f=register';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);  // decode response JSON ke array

        var_dump($json); // debugging

        $this->assertNotFalse($response, 'Response should not be false');
        $this->assertIsArray($json, 'Response should be valid JSON array');
        $this->assertArrayHasKey('status', $json, 'Response should contain status');
        $this->assertEquals('success', $json['status'], 'Register should succeed');
    }
}