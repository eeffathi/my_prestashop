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

function upgrade_module_1_8_5($module)
{
    $langs = Language::getLanguages();
    $res = true;
    foreach ($langs as $lang) {
        $res &= Db::getInstance()->insert('aff_configuration_lang', array(
            'name' => 'commissions_name',
            'id_lang' => (int)$lang['id_lang'],
            'value' => 'Points',
        ));
    }
    $res &= Db::getInstance()->insert('aff_configuration', array(
        array(
            'name' => 'point_commissions',
            'value' => '0',
        ),
    ), false, true, Db::REPLACE);
    return $res;
}
