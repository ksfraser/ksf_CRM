<?php
/**
 * ComposerDependencyManager Test for FA_CRM
 */

namespace Ksfraser\Unit;

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\ComposerDependencyManager;

class ComposerDependencyManagerTest extends TestCase
{
    private string $testModuleDir;
    private string $vendorDir;

    protected function setUp(): void
    {
        $this->testModuleDir = sys_get_temp_dir() . '/fa_crm_test_' . uniqid();
        mkdir($this->testModuleDir, 0755, true);
        mkdir($this->testModuleDir . '/vendor', 0755, true);
        $this->vendorDir = $this->testModuleDir . '/vendor';
    }

    protected function tearDown(): void
    {
        $this->recursiveDelete($this->testModuleDir);
    }

    private function recursiveDelete(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->recursiveDelete($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testConstructorWithModuleDir(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $reflection = new \ReflectionProperty($mgr, 'moduleDir');
        $reflection->setAccessible(true);
        $this->assertEquals($this->testModuleDir, $reflection->getValue($mgr));
    }

    public function testHasComposerJsonReturnsFalse(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $this->assertFalse($mgr->hasComposerJson());
    }

    public function testIsInstalledReturnsFalseWhenNoVendor(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $this->assertFalse($mgr->isInstalled());
    }

    public function testIsInstalledReturnsTrueWhenBothFilesExist(): void
    {
        file_put_contents($this->vendorDir . '/autoload.php', '<?php');
        file_put_contents($this->testModuleDir . '/composer.lock', '{}');
        
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $this->assertTrue($mgr->isInstalled());
    }

    public function testGetAutoloadPathThrowsWhenNotExists(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $this->expectException(\Exception::class);
        $mgr->getAutoloadPath();
    }

    public function testEnsureDependenciesWithoutComposerJson(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $this->assertTrue($mgr->ensureDependencies());
    }

    public function testGetStatusReturnsCorrectArray(): void
    {
        $mgr = new ComposerDependencyManager($this->testModuleDir);
        $status = $mgr->getStatus();
        
        $this->assertIsArray($status);
        $this->assertArrayHasKey('module_dir', $status);
        $this->assertArrayHasKey('is_installed', $status);
        $this->assertFalse($status['is_installed']);
    }
}