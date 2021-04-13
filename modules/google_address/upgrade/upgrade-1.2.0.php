<?php
/**
* NOTICE OF LICENSE.
*
* This source file is subject to a commercial license from BSofts.
* Use, copy, modification or distribution of this source file without written
* license agreement from the BSofts is strictly forbidden.
*
*  @author    BSoft Inc
*  @copyright 2020 BSoft Inc.
*  @license   Commerical License
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_0($module)
{
    return $module->updateChanges();
}
