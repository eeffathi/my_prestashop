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

function upgrade_module_1_0_3($object)
{
    return (Configuration::updateValue('BC_DOMAIN_HASH', sha1(Configuration::get('PS_SHOP_DOMAIN'))) &&
        Configuration::updateValue(
            'BC_SEARCH_ENDPOINT',
            'https://search-biggercarts-dev-odyffory34zdswrzbprp5v26ry.us-east-1.es.amazonaws.com'
        ) &&
        $object->registerHook('productSearchProvider'));
}
