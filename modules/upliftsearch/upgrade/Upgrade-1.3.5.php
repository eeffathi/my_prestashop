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

function upgrade_module_1_3_5($object)
{
    Configuration::updateValue('UPLIFT_DOMAIN_API_ENDPOINT', 'https://api.global.discoverlift.com/config');
    Configuration::updateValue('UPLIFT_SEARCH_API_ENDPOINT', 'https://api.global.discoverlift.com/catalog');
    Configuration::updateValue('UPLIFT_INGEST_API_ENDPOINT', 'https://api.global.discoverlift.com/ingest');
    return true;
}
