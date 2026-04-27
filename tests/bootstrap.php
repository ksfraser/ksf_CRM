<?php
/**
 * Bootstrap for FA_CRM Module Tests
 */

declare(strict_types=1);

set_include_path(implode(PATH_SEPARATOR, [
    __DIR__ . '/../vendor-src',
    get_include_path(),
]));

require_once __DIR__ . '/../vendor-src/Ksfraser/Common/ComposerDependencyManager.php';

define('TB_PREF', 'fa_');
define('CRM_TABLE_PREFIX', 'fa_crm_');
define('CRM_VIEW_CUSTOMER', 'CRM_VIEW_CUSTOMER');
define('CRM_MANAGE_CUSTOMER', 'CRM_MANAGE_CUSTOMER');
define('CRM_VIEW_OPPORTUNITY', 'CRM_VIEW_OPPORTUNITY');
define('CRM_MANAGE_OPPORTUNITY', 'CRM_MANAGE_OPPORTUNITY');
define('CRM_ADMIN', 'CRM_ADMIN');