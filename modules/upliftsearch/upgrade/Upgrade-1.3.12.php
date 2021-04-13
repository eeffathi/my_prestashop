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

function upgrade_module_1_3_12($object)
{
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'uplift_product_hash`
        ADD id_lang int(11) unsigned after id_product';
    $result = Db::getInstance()->execute($sql);
    return true;
}
