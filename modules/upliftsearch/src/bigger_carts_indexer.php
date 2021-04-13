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
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\RequestException;

if (!defined('_PS_VERSION_')) {
    exit();
}
require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'bigger_carts_category_tree_generator.php'
));

/*
 * Hooks to handle:
 * actionProductAdd
 * actionProductUpdate
 * categoryUpdate
 * actionCategoryUpdate
 */
class BiggerCartsIndexer extends SearchCore
{
    public function __construct()
    {
    }

    public static function indexDocuments($full = false, $id_product = false, $max_products_to_reindex = -1)
    {
        if ($id_product) {
            $full = false;
        }

        // Get timestamp
        $curr_timestamp = "latest";

        // Get file path prefix
        $file_path_prefix = 'incoming/prestashop/' . Configuration::get('BC_DOMAIN_HASH') . "/feed/";
        $domain_hash = Configuration::get('BC_DOMAIN_HASH');

        $db = Db::getInstance();

        $guzzleClient = new GuzzleHttp\Client();

        // Get categories data through an iterator
        $category_tree = BiggerCartsCategoryTreeGenerator::getCategoryTreeToIndex($db);

        // Get product data through an iterator
        $product_set_generator = BiggerCartsIndexer::getProductSetToIndex(
            $db,
            $category_tree,
            $full,
            $id_product,
            $max_products_to_reindex
        );

        // Write products data to s3
        return BiggerCartsIndexer::sendProductsForIngestion(
            $domain_hash,
            $curr_timestamp,
            $guzzleClient,
            $product_set_generator,
            $max_products_to_reindex
        );
    }

    public static function deleteDocument($id_product)
    {
        $domainId = Configuration::get('UPLIFT_DOMAIN_ID');
        $catalogId = Configuration::get('UPLIFT_CATALOG_ID');
        $response = [];
        $guzzleClient = new GuzzleHttp\Client();
        $endpoint_url = Configuration::get('UPLIFT_INGEST_API_ENDPOINT') . '/v2/domain/' . $domainId . '/catalog/' . $catalogId . '/documents/' . $id_product;
        $guzzleClient->delete($endpoint_url);
    }

    public static function createNewCatalogVersion()
    {
    }

    public static function publishInterimCatalogVersion()
    {
    }

    private static function sendProductsForIngestion(
        $domain_hash,
        $curr_timestamp,
        $guzzleClient,
        $product_set_generator,
        $max_products_to_reindex
    ) {
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        //$logger->logDebug("Start sending products for ingestion");
        $num_products_indexed = 0;
        $num_unique_products = 0;
        $domainId = Configuration::get('UPLIFT_DOMAIN_ID');
        $catalogId = Configuration::get('UPLIFT_CATALOG_ID');
        $dataIngestionApiEndpoint = Configuration::get('UPLIFT_INGEST_API_ENDPOINT') . '/v2/domain/' . $domainId . '/catalog/' .
            $catalogId . '/documents';
        $analyticsIngestionApiEndpoint = Configuration::get('UPLIFT_INGEST_API_ENDPOINT') . '/v2/domain/' . $domainId . '/catalog/' .
            $catalogId . '/stats/document';

        $unique_products = null;
        $product_set = null;
        foreach ($product_set_generator as list($unique_products, $product_set)) {
            //$logger->logDebug("Writing " . print_r(json_encode($product_set), true));
            $transformed_documents = BiggerCartsIndexer::transformDocuments($product_set);
            $analytics_datapoints = BiggerCartsIndexer::getAnalyticsData($product_set);
            $promises = array();
            try {
                foreach ($transformed_documents as $transformed_document) {
                    $id_lang = $transformed_document['id_lang'];
                    $old_product_hash = $transformed_document['product_hash'];
                    unset($transformed_document['product_hash']);
                    unset($transformed_document['id_lang']);
                    $new_product_hash = md5(serialize($transformed_document));
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'uplift_product_hash` (`id_product`, `product_hash`, `id_lang`)
                            values (' . (int) $transformed_document['document_id'] . ', "' . $new_product_hash . '", ' . (int)$id_lang . ')
                            ON DUPLICATE KEY UPDATE product_hash="'. $new_product_hash . '"';
                    Db::getInstance()->execute($sql);
                    if ($old_product_hash != $new_product_hash) {
                        $promises[] = $guzzleClient->createRequest(
                            'PUT',
                            $dataIngestionApiEndpoint,
                            [
                                'body' => json_encode($transformed_document),
                                'headers' => [
                                    'Content-Type' => 'application/json'
                                ]
                            ]
                        );
                    }
                }
                foreach ($analytics_datapoints as $analytics_datapoint) {
                    $id_lang = $analytics_datapoint['id_lang'];
                    $old_analytics_hash = $analytics_datapoint['analytics_hash'];
                    unset($analytics_datapoint['analytics_hash']);
                    unset($analytics_datapoint['id_lang']);
                    $new_analytics_hash = md5(serialize($analytics_datapoint));
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'uplift_product_hash` (`id_product`, `analytics_hash`, `id_lang`)
                            values (' . (int) $analytics_datapoint['documentId'] . ', "' . $new_analytics_hash . '", ' . (int)$id_lang . ')
                            ON DUPLICATE KEY UPDATE analytics_hash="'. $new_analytics_hash . '"';
                    Db::getInstance()->execute($sql);
                    if ($old_analytics_hash != $new_analytics_hash) {
                        $promises[] = $guzzleClient->createRequest(
                            'POST',
                            $analyticsIngestionApiEndpoint,
                            [
                                'body' => json_encode($analytics_datapoint),
                                'headers' => [
                                    'Content-Type' => 'application/json'
                                ]
                            ]
                        );
                    }
                }
                $pool = new Pool($guzzleClient, $promises);
                $pool->wait();
            } catch (RequestException $e) {
                $logger->logDebug("Exception occurred " . $e->getMessage());
            } catch (Exception $e) {
                $logger->logDebug("Exception occurred " . print_r($e, true));
            }
            $num_products_indexed += count($product_set);
            $num_unique_products += count($unique_products);
            if (($num_products_indexed >= $max_products_to_reindex) && ($max_products_to_reindex > 0)) {
                break;
            }
        }
        //$logger->logDebug("Finish sending $num_products_indexed product(s) for ingestion");
        return array(
            $num_unique_products,
            $num_products_indexed
        );
    }

    private static function getAnalyticsData($product_set)
    {
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        $output = array();
        foreach ($product_set as $product) {
            if ((isset($product['total_sale_orders']) && ((int)$product['total_sale_orders']) > 0) ||
                (isset($product['total_quantity_sold']) && ((int)$product['total_quantity_sold']) > 0)) {
                    $output[] = array("documentId" => $product['id_product'],
                                      "data" => array(
                                            array(
                                                "dataSource" => "prestashop",
                                                "timeRange" => "all",
                                                "stats" => array(
                                                    "numCheckouts" => isset($product['total_sale_orders']) ? ((int)$product['total_sale_orders']) : 0,
                                                    "numSold" => isset($product['total_quantity_sold']) ? ((int)$product['total_quantity_sold']) : 0
                                                )
                                            )
                                        ),
                                       "analytics_hash" => $product['analytics_hash'],
                                       "id_lang" => $product['id_lang']
                                );
            }
        }
        return $output;
    }

    private static function transformDocuments($product_set)
    {
        $output = array();
        
        foreach ($product_set as $product) {
            $locale_specific_data = array(
                'short_description' => $product['description_short'],
                'keywords' => $product['features'],
                'description' => $product['description'],
                'manufacturer' => $product['manufacturer_name'],
                'all_category_names' => $product['all_category_names'],
                'category_breadcrumb' => $product['category_breadcrumb'],
                'primary_category_name' => $product['category_name'],
                'name' => $product['product_name'],
                'attributes' => $product['attributes'],
                'id_lang' => $product['id_lang'],
                'iso_code' => $product['iso_code'],
                'all_category_breadcrumbs' => $product['all_category_breadcrumbs'],
                'tags' => $product['tags']
            );
            $transformed_document = array(
                'document_id' => $product['id_product'],
                'customer_product_id' => $product['id_product'],
                'product_id' => $product['reference'],
                'date_of_activation' => $product['date_add'],
                'primary_category_id' => $product['category_id'],
                'base_price' => $product['price'],
                'wholesale_price' => $product['wholesale_price'],
                'locales' => array(
                    $product['iso_code'] => $locale_specific_data
                ),
                'combination_reference_numbers' => array_values(array_filter(
                    array_map(
                        function ($input) {
                             return $input['pa_reference'];
                        },
                        isset($product['attributes_fields']) ? $product['attributes_fields'] : array()
                    )
                )),
                'wholesale_price_till_two_decimals' => (int)(((float)(isset($product['wholesale_price']) ? $product['wholesale_price'] : 0)) * 100),
                'is_active' => isset($product['active']) ? (int)($product['active']) : 0,
                'combination_price' => BiggerCartsIndexer::getCombinationPrice($product['price'], $product['attributes_fields']),
                'default_combination_id' => BiggerCartsIndexer::getDefaultCombinationId($product['attributes_fields']),
                'product_hash' => $product['product_hash'],
                'id_lang' => $product['id_lang']
            );
            $transformed_document['price'] = BiggerCartsIndexer::getFinalPrice(
                $transformed_document['default_combination_id'],
                $transformed_document['combination_price'],
                $product['specific_prices']
            );
            $transformed_document['price_till_two_decimals'] = (int)((float)($transformed_document['price']) * 100);
            $transformed_document['additional_fields'] = BiggerCartsIndexer::getAdditionalFields($product);
            $output[] = $transformed_document;
        }
        return $output;
    }

    private static function getAdditionalFields($product)
    {
        $filterOutKeys = array(
            'id_product',
            'reference',
            'date_add',
            'product_name',
            'category_name',
            'all_category_names',
            'features',
            'category_id',
            'description',
            'description_short',
            'manufacturer_name',
            'price',
            'date_upd',
            'active',
            'total_quantity_sold',
            'total_sale_orders',
            'price',
            'attributes',
            'wholesale_price',
            'category_breadcrumb',
            'all_category_breadcrumbs',
            'id_lang',
            'iso_code',
            'tags',
            'analytics_hash',
            'product_hash'
        );
        return array_diff_key($product, array_flip($filterOutKeys));
    }

    private static function getCombinationPrice($base_price, $attributes_fields)
    {
        $price_impact = 0;
        if (!$base_price) {
            $base_price = 0;
        }
        if ($attributes_fields) {
            foreach ($attributes_fields as $combination) {
                if (isset($combination['pa_default']) && $combination['pa_default'] == '1') {
                    $price_impact = isset($combination['pa_price_impact']) ? $combination['pa_price_impact'] : 0;
                    break;
                }
            }
        }
        $combination_price = (float)($base_price + $price_impact);
        return $combination_price;
    }

    private static function getDefaultCombinationId($attributes_fields)
    {
        if ($attributes_fields) {
            foreach ($attributes_fields as $combination) {
                if (isset($combination['pa_default']) && $combination['pa_default'] == '1') {
                    return $combination['id_product_attribute'];
                }
            }
        }
        return '0';
    }

    private static function getFinalPrice($default_combination_id, $combination_price, $specific_prices)
    {
        $specific_price_to_pick = null;
        if (!$specific_prices) {
            return $combination_price;
        }
        foreach ($specific_prices as $specific_price) {
            if ($specific_price['from_quantity'] != '1') {
                continue;
            }
            if ($specific_price['id_product_attribute'] == '0') {
                $specific_price_to_pick = $specific_price;
            }
            if ($specific_price['id_product_attribute'] == $default_combination_id) {
                $specific_price_to_pick = $specific_price;
                break;
            }
        }
        $specific_price_modification = 0.0;
        if ($specific_price_to_pick) {
            if ($specific_price_to_pick['reduction_type'] == 'amount') {
                $specific_price_modification = -1.0 * (float)($specific_price_to_pick['reduction']);
            } elseif ($specific_price_to_pick['reduction_type'] == 'percentage') {
                $specific_price_modification = -1.0 * (float)($specific_price_to_pick['reduction']) * $combination_price;
            }
        }
        return $combination_price + $specific_price_modification;
    }

    private static function getProductSetToIndex(
        $db,
        $category_tree,
        $full = false,
        $id_product = false,
        $max_products_to_reindex = -1
    ) {
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        // Every fields are weighted according to the configuration in the backend
        $weight_array = BiggerCartsIndexer::getFieldWeights();

        // Retrieve the number of languages
        $total_languages = count(Language::getLanguages());

        $sql_attribute = BiggerCartsIndexer::getProductAttributeFieldsSQL();
        // Products are processed in batches in order to avoid overloading MySQL DB
        $num_products_per_batch = Configuration::get('BC_INDEX_BATCH_SIZE');
        if ($max_products_to_reindex > 0) {
            $num_products_per_batch = min($max_products_to_reindex, $num_products_per_batch);
        }
        for ($ids = BiggerCartsIndexer::getProducIdsToIndex($id_product, $num_products_per_batch); count($ids) > 0; $ids = BiggerCartsIndexer::getProducIdsToIndex(
            $id_product,
            $num_products_per_batch
        )
        ) {
            $products = BiggerCartsIndexer::getFinalProductsToIndex($total_languages, $ids, $id_product, $weight_array);
            // Now each non-indexed product is processed one by one, langage by langage
            foreach ($products as &$product) {
                if ((int) $weight_array['tags']) {
                    $product['tags'] = Search::getTags($db, (int) $product['id_product'], (int) $product['id_lang']);
                }
                if ((int) $weight_array['attributes']) {
                    $product['attributes'] = BiggerCartsIndexer::getAttributes(
                        $db,
                        (int) $product['id_product'],
                        (int) $product['id_lang']
                    );
                }
                if ((int) $weight_array['features']) {
                    $product['features'] = Search::getFeatures(
                        $db,
                        (int) $product['id_product'],
                        (int) $product['id_lang']
                    );
                }
                $attribute_fields = BiggerCartsIndexer::getDefaultAttributesFields(
                    $db,
                    (int) $product['id_product'],
                    $sql_attribute
                );
                if ($attribute_fields) {
                    $product['attributes_fields'] = $attribute_fields;
                }
                // Get specific prices
                $specific_prices = $db->executeS(
                    'SELECT from_quantity, reduction, reduction_type, id_product_attribute ' .
                        'from ' . _DB_PREFIX_ . 'specific_price where id_product = ' .
                        (int) $product['id_product'],
                    true,
                    false
                );
                if ($specific_prices) {
                    $product['specific_prices'] = $specific_prices;
                } else {
                    $product['specific_prices'] = array();
                }
                // Add categories
                $product['all_category_ids'] = BiggerCartsIndexer::getProductCategories($db, $product['id_product']);

                // Add category breadcrumbs
                $product['category_id_hierarchy'] = $category_tree['id_hierarchy'][$product['category_id']];
                $product['category_breadcrumb'] = array();
                if ($product['category_id']) {
                    foreach ($category_tree['name_hierarchy'][$product['category_id']] as $index => $category_name) {
                        $product['category_breadcrumb'][] = $category_name[$product['id_lang']];
                    }
                }
                $product['all_category_id_hierarchies'] = array();
                $product['all_category_breadcrumbs'] = array();
                $product['all_category_names'] = array();
                foreach ($product['all_category_ids'] as $index => $category_id) {
                    $product['all_category_id_hierarchies'][] = $category_tree['id_hierarchy'][$category_id];
                    $category_breadcrumb = array();
                    foreach ($category_tree['name_hierarchy'][$category_id] as $index => $category_name) {
                        $category_breadcrumb[] = $category_name[$product['id_lang']];
                    }
                    $product['all_category_breadcrumbs'][] = $category_breadcrumb;
                    $product['all_category_names'][] = $category_breadcrumb[0];
                }
                // Truncate description
                if (Tools::strlen($product['description']) > 500) {
                    $product['description'] = mb_substr($product['description'], 0, 500);
                }
                if (Tools::strlen($product['description_short']) > 500) {
                    $product['description_short'] = mb_substr($product['description_short'], 0, 500);
                }
            }
            // Use $products to write to s3
            if ($ids) {
                BiggerCartsIndexer::setUpliftProductsAsIndexed($ids);
            }
            //$logger->logDebug(count($ids) . " unique products for " . count($products) . " total products");

            yield array(
                $ids,
                $products
            );
        }
    }

    private static function getProductCategories($db, $id)
    {
        $product_categories = $db->executeS(
            'SELECT cp.id_product, cp.id_category FROM ' . _DB_PREFIX_ .
                'category c INNER JOIN ' . _DB_PREFIX_ .
                'category_product cp ON (c.id_category = cp.id_category) ' .
                'WHERE c.active = 1 ' .
                'AND cp.id_product = ' . (int) $id,
            true,
            false
        );
        if (array_key_exists('id_product', $product_categories)) {
            $product_categories = [$product_categories];
        }
        $list_of_categories = array();
        foreach ($product_categories as $index => $data) {
            $list_of_categories[] = $data['id_category'];
        }
        return $list_of_categories;
    }

    private static function getDefaultAttributesFields($db, $id_product, $sql_attribute)
    {
        return $db->executeS(
            'SELECT id_product ' . $sql_attribute . ' FROM ' . _DB_PREFIX_ .
                'product_attribute pa WHERE pa.id_product = ' . (int) $id_product,
            true,
            false
        );
    }

    private static function getProductAttributeFieldsSQL()
    {
        return ', pa.id_product_attribute, pa.price AS pa_price_impact, pa.default_on as pa_default, pa.reference AS pa_reference' .
            ', pa.supplier_reference AS pa_supplier_reference, pa.ean13 AS pa_ean13, pa.upc AS pa_upc';
    }

    public static function getAttributes($db, $id_product, $id_lang)
    {
        if (!Combination::isFeatureActive()) {
            return '';
        }

        $attributes = array();
        $attributesArray = $db->executeS(
            'SELECT al.name FROM ' . _DB_PREFIX_ . 'product_attribute pa INNER JOIN ' .
                _DB_PREFIX_ .
                'product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute INNER JOIN ' .
                _DB_PREFIX_ .
                'attribute_lang al ON (pac.id_attribute = al.id_attribute AND al.id_lang = ' .
                (int) $id_lang . ')' . Shop::addSqlAssociation('product_attribute', 'pa') .
                ' WHERE pa.id_product = ' . (int) $id_product,
            true,
            false
        );
        foreach ($attributesArray as $attribute) {
            $attributes[] = $attribute['name'];
        }

        return array_values(array_unique($attributes));
    }

    private static function getProducIdsToIndex($id_product = false, $limit = 50)
    {
        $ids = array();
        if (!$id_product) {
            // Limit products for each step but be sure that each attribute is taken into account
            $sql = 'SELECT p.id_product FROM ' . _DB_PREFIX_ . 'product p
                LEFT JOIN ' . _DB_PREFIX_ .
                'uplift_products_indexed upi ON  upi.id_product=p.id_product
                WHERE upi.`is_indexed` is null or upi.`is_indexed` != 1
                LIMIT ' . (int) $limit;
        } else {
            $sql = 'SELECT p.id_product FROM ' . _DB_PREFIX_ . 'product p
                LEFT JOIN ' . _DB_PREFIX_ .
                'uplift_products_indexed upi ON  upi.id_product=p.id_product
                WHERE (upi.`is_indexed` is null or upi.`is_indexed` != 1) and p.id_product = "' . $id_product . '"  LIMIT ' . (int) $limit;
        }
        $res = Db::getInstance()->executeS($sql, false);
        while ($row = Db::getInstance()->nextRow($res)) {
            $ids[] = $row['id_product'];
        }
        return $ids;
    }

    private static function setUpliftProductsAsIndexed($ids)
    {
        foreach ($ids as $id_product) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'uplift_products_indexed` (`id_product`, `is_indexed`)
                            values (' . (int) $id_product . ', 1)
                            ON DUPLICATE KEY UPDATE is_indexed=1';
            Db::getInstance()->execute($sql);
        }
    }

    private static function getFinalProductsToIndex($total_languages, $ids, $id_product, $weight_array = array())
    {
        // Now get every attribute in every language
        $sql = 'SELECT p.id_product, pl.id_lang, pl.id_shop, p.date_add,
                       p.date_upd, p.active, l.iso_code, p.price, p.wholesale_price, p.quantity,
                       uph.product_hash, uph.analytics_hash ';

        if (is_array($weight_array)) {
            foreach ($weight_array as $key => $weight) {
                if ((int) $weight) {
                    switch ($key) {
                        case 'pname':
                            $sql .= ', pl.name product_name';
                            break;
                        case 'reference':
                            $sql .= ', p.reference';
                            break;
                        case 'supplier_reference':
                            $sql .= ', p.supplier_reference';
                            break;
                        case 'ean13':
                            $sql .= ', p.ean13';
                            break;
                        case 'upc':
                            $sql .= ', p.upc';
                            break;
                        case 'description_short':
                            $sql .= ', pl.description_short';
                            break;
                        case 'description':
                            $sql .= ', pl.description';
                            break;
                        case 'cname':
                            $sql .= ', cl.name category_name, cl.id_category category_id';
                            break;
                        case 'mname':
                            $sql .= ', m.name manufacturer_name';
                            break;
                    }
                }
            }
        }

        // Get order details
        $sql .= ', ps.quantity total_quantity_sold, ps.sale_nbr total_sale_orders';

        // Image details
        $sql .= ', pi.id_image ';

        $sql .= ' FROM ' . _DB_PREFIX_ . 'product p
            LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl
                ON p.id_product = pl.id_product
            ' . Shop::addSqlAssociation('product', 'p', true, null, true) . '
            LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl
                ON (cl.id_category = product_shop.id_category_default AND pl.id_lang = cl.id_lang AND cl.id_shop = product_shop.id_shop)
            LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer m
                ON m.id_manufacturer = p.id_manufacturer
            LEFT JOIN ' . _DB_PREFIX_ . 'lang l
                ON l.id_lang = pl.id_lang
            LEFT JOIN ' . _DB_PREFIX_ .
            'product_sale ps
                ON p.id_product = ps.id_product
            LEFT JOIN ' . _DB_PREFIX_ .
            'image pi on p.id_product = pi.id_product
            LEFT JOIN ' . _DB_PREFIX_ .
            'uplift_product_hash uph on p.id_product = uph.id_product and pl.id_lang = uph.id_lang
            WHERE l.iso_code != ""
            AND (pi.`cover` = 1 or pi.`id_product` is null)
            AND product_shop.visibility IN ("both", "search")
            ' . ($id_product ? 'AND p.id_product = ' . (int) $id_product : '') . '
            ' . ($ids ? 'AND p.id_product IN (' . implode(',', array_map('intval', $ids)) . ')' : '') .
            '
            AND product_shop.`active` = 1
            AND pl.`id_shop` = product_shop.`id_shop`';
    
        return Db::getInstance()->executeS($sql, true, false);
    }

    public static function resetProductIndexStatusAsNeeded($full, $id_product)
    {
        $db = Db::getInstance();
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");

        if ($full) {
            //$logger->logDebug("Truncating search_index table");

            $db->execute('TRUNCATE ' . _DB_PREFIX_ . 'uplift_products_indexed');
        } else {
            $db->execute(
                'INSERT INTO `' . _DB_PREFIX_ .
                    'uplift_products_indexed` (`id_product`, `is_indexed`)
                            values (' . (int) $id_product . ', 0)
                            ON DUPLICATE KEY UPDATE is_indexed=0'
            );
        }
    }

    private static function getFieldWeights()
    {
        return array(
            'pname' => 1,
            'reference' => 1,
            'pa_reference' => 1,
            'supplier_reference' => 1,
            'pa_supplier_reference' => 1,
            'ean13' => 1,
            'pa_ean13' => 1,
            'upc' => 1,
            'pa_upc' => 1,
            'description_short' => 1,
            'description' => 1,
            'cname' => 1,
            'mname' => 1,
            'tags' => 1,
            'attributes' => 1,
            'features' => 1
        );
    }
}
