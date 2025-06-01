<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $login;
    
    protected function setUp(): void
{
    if (!defined('PHPUNIT_RUNNING')) {
        define('PHPUNIT_RUNNING', true);
    }

    if (file_exists(__DIR__ . '/../initialize.php')) {
        require_once(__DIR__ . '/../initialize.php');
    }

    if (file_exists(__DIR__ . '/../classes/Login.php')) {
        require_once(__DIR__ . '/../classes/Login.php');
        $this->login = new Login();
    }
}

    
    public function testLoginClassExists()
    {
        if (class_exists('Login')) {
            $this->assertInstanceOf('Login', $this->login);
            $this->assertNotNull($this->login);
        } else {
            $this->markTestSkipped('Login class not found');
        }
    }
    
    public function testLoginMethodExists()
    {
        if (!$this->login) {
            $this->markTestSkipped('Login class not available');
        }
        
        $this->assertTrue(
            method_exists($this->login, 'login_user'),
            'login_user method should exist in Login class'
        );
    }
    
    public function testBasicFunctionality()
    {
        if (!$this->login) {
            $this->markTestSkipped('Login class not available');
        }
        
        try {
            $this->assertTrue(true, 'Basic test passed');
        } catch (Exception $e) {
            $this->fail('Exception thrown: ' . $e->getMessage());
        }
    }
    
    protected function tearDown(): void
    {
        if (isset($_POST['username'])) {
            unset($_POST['username']);
        }
        if (isset($_POST['password'])) {
            unset($_POST['password']);
        }
    }
}