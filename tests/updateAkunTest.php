<?php
use PHPUnit\Framework\TestCase;

// Simpan direktori awal
$originalDir = getcwd();

// Pindah ke direktori classes tempat Master.php dan config.php berada
chdir(__DIR__ . '/../classes');

// Include file config.php dan Master.php
require_once '../config.php';
require_once 'Master.php';

// Kembalikan ke direktori awal
chdir($originalDir);

class updateAkunTest extends TestCase
{
    protected $master;
    protected $existingUserId; // jangan inisialisasi di sini

    protected function setUp(): void
    {
        $this->existingUserId = 4; // inisialisasi di sini, bukan di property langsung
        $this->master = new Master();

        // Mock settings dengan userdata dan set_userdata
        $settingsMock = new class {
            private $userdata;

            public function __construct()
            {
                $this->userdata = [
                    'id' => 4,
                    'password' => md5('123'), // password asli untuk verifikasi
                    'username' => 'Bedul',
                    'firstname' => 'Abdul',
                    'lastname' => 'Ghani'
                ];
            }

            public function userdata($key)
            {
                return $this->userdata[$key] ?? null;
            }

            public function set_userdata($key, $value)
            {
                $this->userdata[$key] = $value;
            }
        };

        $reflection = new ReflectionClass($this->master);
        $property = $reflection->getProperty('settings');
        $property->setAccessible(true);
        $property->setValue($this->master, $settingsMock);
    }

    public function testUpdateAccountSuccess()
    {
        $_POST = [
            'id' => $this->existingUserId,
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'username' => 'Jens',
            'password' => '123',
            'cpassword' => '123'
        ];

        $response = $this->master->update_account();
        $data = json_decode($response, true);

        $this->assertIsArray($data);
        $this->assertEquals('success', $data['status']);

        // Verifikasi data sudah berubah di DB
        $result = $this->master->conn->query("SELECT firstname, lastname, username FROM users WHERE id = {$this->existingUserId}");
        $row = $result->fetch_assoc();

        $this->assertEquals('Jane', $row['firstname']);
        $this->assertEquals('Smith', $row['lastname']);
        $this->assertEquals('Jens', $row['username']);
    }
}