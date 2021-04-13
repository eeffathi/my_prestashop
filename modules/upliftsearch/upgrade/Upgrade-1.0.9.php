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

function upgrade_module_1_0_9($object)
{
    $api_endpoint = 'https://5dh3ofvrkc.execute-api.us-east-1.amazonaws.com/dev/domain';
    $guzzleClient = new GuzzleHttp\Client();
    $response = $guzzleClient->post(
        $api_endpoint,
        [
                                        'body' => json_encode(
                                            array(

                                                                        "name" => Configuration::get('PS_SHOP_NAME'),
                                                                        "url" => Configuration::get('PS_SHOP_DOMAIN')
                                                                )
                                        ),
                                        'headers' => [
                                                        'Content-Type' => 'application/json'
                                        ]
                                    ]
    );
    $responseBody = $response->json();
    $domainId = $responseBody["id"];
    // Create a catalog
    $catalog_response = $guzzleClient->post(
        $api_endpoint . '/' . $domainId . '/catalog',
        [
                                                'body' => json_encode(
                                                    array(
                                                                                "name" => "prestashop_default_catalog",
                                                                                "integration_type" => "prestashop"
                                                                        )
                                                ),
                                                'headers' => [
                                                                'Content-Type' => 'application/json'
                                                ]
                                            ]
    )->json();
    $catalogId = $catalog_response["id"];
    $defaultCatalogVersionId = $catalog_response["version"]["id"];

    Configuration::updateValue('UPLIFT_DOMAIN_ID', $domainId);
    Configuration::updateValue('UPLIFT_CATALOG_ID', $catalogId);
    Configuration::updateValue('UPLIFT_DEFAULT_CATALOG_VERSION_ID', $defaultCatalogVersionId);
    Configuration::updateValue('UPLIFT_INTERIM_CATALOG_VERSION_ID', null);

    Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
                                'uplift_products_indexed` (
                                      `id_product` int(11) unsigned not null,
                                      `is_indexed` int(11) unsigned not null DEFAULT 0,
                                      PRIMARY KEY (`id_product`)
                                     ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
    );
    return true;
}
