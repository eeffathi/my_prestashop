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

if (!defined('_PS_VERSION_')) {
    exit();
}

class BiggerCartsSearchPostProcessor
{
    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $this->logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
    }

    public function addAdditionalDetails($id_lang, $ajax, $context, $product_ids)
    {
        if (!$context) {
            $context = ContextCore::getContext();
        }

        if (empty($product_ids)) {
            return array();
        }
        $sql = '';
        if (_PS_VERSION_ > '1.6.0.4') {
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
                    pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
                image_shop.`id_image` id_image, il.`legend`, m.`name` manufacturer_name ,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            "' . date('Y-m-d') . ' 00:00:00",
                            INTERVAL ' .
                (ValidateCore::isUnsignedInt(ConfigurationCore::get('PS_NB_DAYS_NEW_PRODUCT')) ? ConfigurationCore::get(
                    'PS_NB_DAYS_NEW_PRODUCT'
                ) : 20) . ' DAY
                        )
                    ) > 0 new' .
                (CombinationCore::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.`id_product_attribute`,0) id_product_attribute' : '') . '
                    FROM ' . _DB_PREFIX_ . 'product p
                    ' . ShopCore::addSqlAssociation('product', 'p') . '
                    INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
                        p.`id_product` = pl.`id_product`
                        AND pl.`id_lang` = ' . (int) $id_lang . ShopCore::addSqlRestrictionOnLang('pl') . '
                    )
                    ' . (CombinationCore::isFeatureActive() ? 'LEFT JOIN `' .
                    _DB_PREFIX_ .
                    'product_attribute_shop` product_attribute_shop
                    ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' .
                    (int) $context->shop->id . ')' : '') . '
                    ' . ProductCore::sqlStock('p', 0) . '
                    LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                        ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' .
                (int) $context->shop->id . ')
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' .
                (int) $id_lang . ')
                    WHERE p.`id_product`  IN (' . pSQL($product_ids) . ')
                    GROUP BY product_shop.id_product order by field( p.`id_product`, ' . pSQL($product_ids) .
                ')';
        } else {
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
                    pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` manufacturer_name ,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            "' . date('Y-m-d') . ' 00:00:00",
                            INTERVAL ' .
                (ValidateCore::isUnsignedInt(ConfigurationCore::get('PS_NB_DAYS_NEW_PRODUCT')) ? ConfigurationCore::get(
                    'PS_NB_DAYS_NEW_PRODUCT'
                ) : 20) . ' DAY
                        )
                    ) > 0 new' .
                (Combination::isFeatureActive() ?
                    ', MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity' :
                    '') . '
                    FROM ' . _DB_PREFIX_ . 'product p
                    ' . ShopCore::addSqlAssociation('product', 'p') . '
                    INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
                        p.`id_product` = pl.`id_product`
                        AND pl.`id_lang` = ' . (int) $id_lang . ShopCore::addSqlRestrictionOnLang('pl') . '
                    )
                    ' . (Combination::isFeatureActive() ?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
                        ' . Shop::addSqlAssociation(
                        'product_attribute',
                        'pa',
                        false,
                        'product_attribute_shop.`default_on` = 1'
                    ) . '
                        ' . Product::sqlStock('p', 'product_attribute_shop', false, $context->shop) :
                    Product::sqlStock('p', 'product', false, Context::getContext()->shop)) . '
                    LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                    LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)' .
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (
                        i.`id_image` = il.`id_image`
                        AND il.`id_lang` = ' . (int)$id_lang . '
                    )
                    WHERE p.`id_product`  IN (' . pSQL($product_ids) . ')
                    GROUP BY product_shop.id_product order by field( p.`id_product`, ' . pSQL($product_ids) .
                ')';
        }
        $sql_result = $this->db->executeS($sql, true, false);
        $product_properties = ProductCore::getProductsProperties((int) $id_lang, $sql_result);
        foreach ($product_properties as &$product) {
            $product['pname'] = $product['name'];
            $product['cname'] = $product['category'];
            $product['crewrite'] = $product['category'];
            $product['prewrite'] = $product['link-rewrite'];
        }
        return $product_properties;
    }
}
