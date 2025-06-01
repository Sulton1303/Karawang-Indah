<?php
use PHPUnit\Framework\TestCase;

$originalDir = getcwd();

chdir(__DIR__ . '/../classes');
require_once '../config.php';
require_once 'Master.php';

chdir($originalDir);

class tambahPackageTest extends TestCase
{
    protected $master;
    protected $dummyImagePath;

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


        
        $this->dummyImagePath = __DIR__ . '/../uploads/package_1/fslkm.jpg';

        
        $uploadDir = dirname($this->dummyImagePath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        
        if (!file_exists($this->dummyImagePath)) {
            file_put_contents($this->dummyImagePath, str_repeat('0', 1024));
        }
    }

    public function testSavePackage()
    {
        $_POST = [
            'title' => 'Paket Uji Coba',
            'tour_location' => 'Karawang',
            'cost' => 150000,
            'description' => 'Deskripsi paket wisata uji coba.',
            'status' => 1
        ];

        $_FILES['img'] = [
            'name' => ['fslkm.jpg'],           
            'type' => ['jpg'],
            'tmp_name' => [$this->dummyImagePath],
            'error' => [0],
            'size' => [filesize($this->dummyImagePath)]
        ];


        $response = $this->master->save_package();

        $this->assertIsString($response);
        $data = json_decode($response, true);

        $this->assertIsArray($data);
        $this->assertEquals('success', $data['status']);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->dummyImagePath)) {
            unlink($this->dummyImagePath);
        }
    }
}
