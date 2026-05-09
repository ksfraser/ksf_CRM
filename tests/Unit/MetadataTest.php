<?php
/**
 * FA_CRM Module Metadata Test
 */

namespace Ksfraser\Unit;

use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
    public function testPermissionConstantsAreDefined(): void
    {
        $this->assertTrue(defined('CRM_VIEW_CUSTOMER'));
        $this->assertTrue(defined('CRM_MANAGE_CUSTOMER'));
        $this->assertTrue(defined('CRM_VIEW_OPPORTUNITY'));
        $this->assertTrue(defined('CRM_MANAGE_OPPORTUNITY'));
        $this->assertTrue(defined('CRM_ADMIN'));
    }

    public function testPermissionConstantsHaveCorrectValues(): void
    {
        $this->assertEquals('CRM_VIEW_CUSTOMER', CRM_VIEW_CUSTOMER);
        $this->assertEquals('CRM_MANAGE_CUSTOMER', CRM_MANAGE_CUSTOMER);
        $this->assertEquals('CRM_VIEW_OPPORTUNITY', CRM_VIEW_OPPORTUNITY);
        $this->assertEquals('CRM_MANAGE_OPPORTUNITY', CRM_MANAGE_OPPORTUNITY);
        $this->assertEquals('CRM_ADMIN', CRM_ADMIN);
    }

    public function testPermissionConstantsAreDistinct(): void
    {
        $constants = [
            CRM_VIEW_CUSTOMER,
            CRM_MANAGE_CUSTOMER,
            CRM_VIEW_OPPORTUNITY,
            CRM_MANAGE_OPPORTUNITY,
            CRM_ADMIN,
        ];
        $this->assertCount(5, array_unique($constants));
    }
}