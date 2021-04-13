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

function upgrade_module_1_4_0($object)
{
    $api_endpoint = Configuration::get('UPLIFT_DOMAIN_API_ENDPOINT') . '/v1/domain/';
    $guzzleClient = new GuzzleHttp\Client();
    $response = $guzzleClient->post(
        $api_endpoint,
        [
            'body' => json_encode(
                array(

                    "name" => Configuration::get('PS_SHOP_NAME'),
                    "url" => Configuration::get(
                        'PS_SHOP_DOMAIN'
                    )
                )
            ),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]
    );
    $responseBody = json_decode($response->getBody(), true);
    $domainId = $responseBody["id"];
    // Create a catalog
    $catalog_response = json_decode($guzzleClient->post(
        $api_endpoint . $domainId . '/catalog',
        [
            'body' => json_encode(
                array(
                    "integration_type" => "prestashop",
                    "name" => "prestashop_default_catalog"
                )
            ),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]
    )->getBody(), true);
    $catalogId = $catalog_response["id"];
    $defaultCatalogVersionId = $catalog_response["version"]["id"];

    Configuration::updateValue('UPLIFT_DOMAIN_ID', $domainId);
    Configuration::updateValue('UPLIFT_CATALOG_ID', $catalogId);
    Configuration::updateValue('UPLIFT_DEFAULT_CATALOG_VERSION_ID', $defaultCatalogVersionId);
    return true;
}
