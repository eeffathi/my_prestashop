<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
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

function upgrade_module_1_6_9($module)
{
    $res = Db::getInstance()->execute("CREATE TABLE `"._DB_PREFIX_."aff_payment_methods_lang` (
        `id_payment_method` INT NOT NULL,
        `id_lang` INT NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `description` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id_payment_method`, `id_lang`)
    ) ENGINE = "._MYSQL_ENGINE_.";");

    return (bool)$res;
}
