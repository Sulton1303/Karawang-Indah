<?php
use PHPUnit\Framework\TestCase;

class masukanTest extends TestCase
{
    public function testMasukanSuccess()
    {
        $_POST = [
            'name' => 'Orang Keren',
            'email' => 'keren@gmail.com',
            'subject' => 'Keluhan',
            'message' => 'tolong sistem lebih dipercantik'
        ];

        chdir(__DIR__ . '/../classes');
        require_once '../config.php';
        require_once 'Master.php';
        chdir(__DIR__);

        $master = new Master();
        $response = $master->save_inquiry();

        $result = json_decode($response, true);

        $this->assertEquals('success', $result['status']);
        $this->assertIsArray($result);
    }
}