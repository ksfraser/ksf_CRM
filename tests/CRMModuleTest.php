<?php
/**
 * CRM Module Tests
 *
 * Unit tests for the WebERP-style CRM module
 */

use PHPUnit\Framework\TestCase;
use FA\Modules\CRMModule;

// Include the module file directly for testing
require_once __DIR__ . '/../CRMModule.php';

class CRMModuleTest extends TestCase
{
    private $crmModule;

    protected function setUp(): void
    {
        $this->crmModule = new CRMModule();
    }

    public function testGetName()
    {
        $this->assertEquals('CRM', $this->crmModule->getName());
    }

    public function testGetVersion()
    {
        $this->assertEquals('1.0.0', $this->crmModule->getVersion());
    }

    public function testGetDescription()
    {
        $this->assertStringContainsString('Customer Relationship Management', $this->crmModule->getDescription());
    }

    public function testGetDependencies()
    {
        $dependencies = $this->crmModule->getDependencies();
        $this->assertContains('Core', $dependencies);
    }

    public function testActivate()
    {
        // Mock the necessary FA functions that would be called during activate
        // This is a basic test - in a real scenario, we'd mock the entire environment

        // For now, just test that activate doesn't throw an exception
        $result = $this->crmModule->activate();
        $this->assertTrue($result);
    }

    public function testDatabaseTableCreation()
    {
        // This test would verify that database tables are created correctly
        // In a real test environment, we'd set up a test database

        $this->markTestIncomplete('Database tests require test database setup');
    }

    public function testCustomerTypeOperations()
    {
        // Test customer type CRUD operations
        // This would test the CRM database functions

        $this->markTestIncomplete('CRM database function tests require database setup');
    }
}