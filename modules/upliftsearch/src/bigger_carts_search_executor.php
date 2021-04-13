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
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;

if (! defined("_PS_VERSION_")) {
    exit();
}

class BiggerCartsSearchExecutor
{
    public function __construct()
    {
        $this->es_url = Configuration::get('BC_SEARCH_ENDPOINT');
        $this->domain_hash = Configuration::get('BC_DOMAIN_HASH');
        $this->domainId = Configuration::get('UPLIFT_DOMAIN_ID');
        $this->catalogId = Configuration::get('UPLIFT_CATALOG_ID');
        $this->logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $this->logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        $this->guzzleClient = new GuzzleHttp\Client();
        $this->db = Db::getInstance();
    }

    public function search($query_term, $page_number, $page_size, $order_by, $order_way, $applicable_rules, $id_lang)
    {
        if ($page_size > 50) {
            $page_size = 50;
        }
        // If a redirect rule is present for this context, apply the redirect
        $this->redirectIfRrequired($applicable_rules);

        // Build search query
        $search_query = $this->getSearchQuery($query_term, $page_number, $page_size, $order_by, $order_way, $id_lang);
        // Get response from search API
        $search_response = $this->getSearchResponse($search_query);
        return array(
            'results' => join(
                ", ",
                array_map(
                    function ($input) {
                        return $input['attributes']['customer_product_id'];
                    },
                    $search_response["results"]
                )
            ),
            'total' => $search_response['numResults']
        );
    }

    private function redirectIfRrequired($applicable_rules)
    {
        if (array_key_exists("redirect", $applicable_rules)) {
            Tools::redirect($applicable_rules["redirect"]);
        }
    }

    private function getSearchQuery($query_term, $page_number, $page_size, $order_by, $order_way, $id_lang)
    {
        $sql = 'SELECT iso_code from ' . _DB_PREFIX_ . 'lang WHERE id_lang=' . $id_lang;
        $iso_code = $this->db->getRow($sql)['iso_code'];
        $ranking_params = '';
        switch ($order_by) {
            case 'price':
                $ranking_params = '{ "relevanceType" : "attribute", "attributeName": "price_till_two_decimals", "rankModifier": "' . $order_way . '"}';
                break;
            case 'name':
                $ranking_params = '{ "relevanceType" : "attribute", "attributeName": "name.keyword", "rankModifier": "' . $order_way . '"}';
                break;
            default:
                $ranking_params = '{}';
        }

        return '{
            "requestParams":{},
            "searchParams":{
                "language":"' . $iso_code . '",
                "attributesToRetrieve":["customer_product_id"],
                "paginationParams": {"hitsPerPage": ' . $page_size . ', "page": ' . ($page_number > 0 ? ($page_number - 1) : 0). '},
                "rankingParams": ' . $ranking_params . ',
                "personalizationParams": {},
                "keyphrase": "' . $query_term . '"
            }
        }';
    }

    private function curlPost($url, stdClass $post = null, array $options = array())
    {
        $post_fields = json_encode($post);
        $defaults = array(
                            // Tell cURL that we want to send a POST request.
                            CURLOPT_POST => 1,
                            CURLOPT_HEADER => 0,
                            CURLOPT_URL => $url,
                            // Return result
                            CURLOPT_RETURNTRANSFER => 1,
                            // Attach our encoded JSON string to the POST fields.
                            CURLOPT_POSTFIELDS => $post_fields
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (! $result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    private function getSearchResponse($search_query)
    {
        $url = Configuration::get('UPLIFT_SEARCH_API_ENDPOINT') . '/v1/domain/' . $this->domainId . '/catalog/' .
            $this->catalogId . '/search';
        $headers = array(
            'content-type: application/json'
        );
        $body = [
            'body' => $search_query,
            'headers' => $headers
        ];
        $response = $this->guzzleClient->post(
            $url,
            $body
        );
        $result = json_decode($response->getBody(), true);
        return $result;
    }
}
