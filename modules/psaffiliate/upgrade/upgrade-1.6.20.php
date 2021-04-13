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

function upgrade_module_1_6_20($module)
{
    $module->loadClasses('AffConf');
    AffConf::updateConfig('override_vts_affiliate', 1);
    return Db::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."aff_vts` (
          `id_vts` INT NOT NULL AUTO_INCREMENT,
          `id_cart_rule` INT NOT NULL,
          `id_cart_rule_template` INT NOT NULL,
          `id_affiliate` INT NOT NULL,
          `code_prefix` VARCHAR(32) NOT NULL,
          `code_noprefix` VARCHAR(64) NOT NULL,
          `date_add` DATETIME NOT NULL,
          PRIMARY KEY (`id_vts`)
          ) ENGINE="._MYSQL_ENGINE_.";");
}
