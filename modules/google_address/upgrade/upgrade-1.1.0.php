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

function upgrade_module_1_1_0($module)
{
    $result = true;
    $result &= $module->addAdminTab();
    $result &= $module->registerHook('displayBackOfficeHeader');
    $result &= Configuration::updateValue(
        'GOOGLE_MAP_THEME',
        'default',
        false,
        Context::getContext()->shop->id_shop_group,
        Context::getContext()->shop->id_shop
    );
    $result &= Configuration::updateValue(
        'GOOGLE_MAP_ZOOM_LEVEL',
        10,
        false,
        Context::getContext()->shop->id_shop_group,
        Context::getContext()->shop->id_shop
    );

    $newConfigs = $module->getConfigSettingValues();
    if (isset($newConfigs) && $newConfigs) {
        foreach (array_keys($newConfigs) as $key) {
            $result &= Configuration::updateValue(
                $key,
                true,
                false,
                Context::getContext()->shop->id_shop_group,
                Context::getContext()->shop->id_shop
            );
        }
    }
    return $result;
}
