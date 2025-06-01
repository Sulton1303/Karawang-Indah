<?php
use PHPUnit\Framework\TestCase;

class hapusMasukanTest extends TestCase
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

        $reflection = new ReflectionClass($this->master);
        if ($reflection->hasProperty('settings')) {
            $prop = $reflection->getProperty('settings');
            $prop->setAccessible(true);
            $prop->setValue($this->master, $dummySettings);
        }
    }

    public function testDeleteInquiryExistingData()
    {
        $_POST['id'] = 10; 

        $response = $this->master->delete_inquiry();

        $result = json_decode($response, true);

        $this->assertEquals('success', $result['status']);
    }
}