<?php
use PHPUnit\Framework\TestCase;

$originalDir = getcwd();

chdir(__DIR__ . '/../classes');
require_once '../config.php';
require_once 'Master.php';

chdir($originalDir);

class hapusPackageTest extends TestCase
{
    protected $master;

    protected function setUp(): void
    {
        $this->master = new Master();

        $reflection = new ReflectionClass($this->master);
        $property = $reflection->getProperty('settings');
        $property->setAccessible(true);
        $property->setValue($this->master, new class {
            public function set_flashdata($key, $message)
            {
            }
        });
    }

    public function testDeletePackage()
    {
        $idToDelete = 18; 

        $_POST = ['id' => $idToDelete];

        $response = $this->master->delete_package();

        $this->assertIsString($response);

        $data = json_decode($response, true);

        $this->assertIsArray($data);
        $this->assertEquals('success', $data['status']);

        $res = $this->master->conn->query("SELECT * FROM `packages` WHERE id = '{$idToDelete}'");
        $this->assertEquals(0, $res->num_rows);

        $uploadDir = base_app . "uploads/package_{$idToDelete}";
        $this->assertDirectoryDoesNotExist($uploadDir);
    }
}