<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Uplift
 * @copyright Uplift
 * @license   GPLv3
 */

if (! defined('_PS_VERSION_')) {
    exit();
}

function upgrade_module_1_0_7($object)
{
    Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
                                'uplift_redirect_rules` (
                                       `id_uplift_redirect_rule` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                       `url` varchar(255) NOT NULL,
                                       `exact_keyphrases` varchar(255) NOT NULL,
                                       `partial_keyphrases` varchar(255) NOT NULL,
                                       PRIMARY KEY (`id_uplift_redirect_rule`),
                                       UNIQUE(`url`)
                                       ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
    );
    return true;
}
