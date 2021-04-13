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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

if (! defined('_PS_VERSION_')) {
    exit();
}

function upgrade_module_1_3_7($object)
{
    Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
            'uplift_product_hash` (
                                      `id_product` int(11) unsigned not null,
                                      `product_hash` varchar(32) not null,
                                      `analytics_hash` varchar(32) not null,
                                      PRIMARY KEY (`id_product`)
                                     ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
    );
    return true;
}
