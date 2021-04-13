<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code.
 *
 * @author    Active Design <office@activedesign.ro>
 * @copyright 2016-2018 Active Design
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_8_0($module)
{
    $module->loadClasses('AffConf');
    $return = $module->addBackOfficeTabs();
    for ($i = 1; $i <= $module->getMLM_LEVELS(); $i++) {
        $return &= AffConf::updateConfig('mlm_commission_'.$i, 0);
    }
    return $return;
}
