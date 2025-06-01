<?php
use PHPUnit\Framework\TestCase;

class batalkanBookingTest extends TestCase
{
    protected $master;

    protected function setUp(): void
    {
        chdir(__DIR__ . '/../classes');
        require_once '../config.php';
        require_once 'Master.php';
        chdir(__DIR__);

        $this->master = new Master();

        $dummySettings = new class {
            public function set_flashdata($key, $message)
            {

            }
        };

        $refClass = new ReflectionClass($this->master);
        if ($refClass->hasProperty('settings')) {
            $settingsProp = $refClass->getProperty('settings');
            $settingsProp->setAccessible(true);
            $settingsProp->setValue($this->master, $dummySettings);
        }
    }

    public function testBatalkanBooking()
    {
        $_POST['id'] = 9;

        $response = $this->master->delete_booking();
        $result = json_decode($response, true);

        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
    }
}