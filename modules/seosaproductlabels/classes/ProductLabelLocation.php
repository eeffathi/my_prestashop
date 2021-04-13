<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    SeoSA <885588@bk.ru>
 * @copyright 2012-2021 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class ProductLabelLocation
 */
class ProductLabelLocation extends ObjectModel
{
    const _FOR_MANUFACTURER_ = 0;
    const _FOR_CATEGORY_ = 1;
    const _FOR_SUPPLIER_ = 2;
    const _FOR_STATUS_ = 3;
    const _FOR_CART_RULES_ = 4;
    const _FOR_CONDITION_ = 5;
    const _FOR_BESTSELLERS_ = 6;
    const _FOR_QUANTITY_ = 7;
    const _FOR_FEATURE_ = 8;

    public $id;
    public $id_product;
    public $id_product_label;
    public $id_shop;
    public $position;

    public static $cache = array();

    public static $definition = array(
        'table'   => 'seosaproductlabels_location',
        'primary' => 'id_product_label_location',
        'fields'  => array(
            'id_product'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_product_label' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'position'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'id_shop'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId')
        ),
    );

    public static function createTable()
    {
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_location` (
                                      `id_product_label_location` INT(11) NOT NULL AUTO_INCREMENT,
                                      `id_product` INT(11) NOT NULL,
                                      `id_shop` INT(11) NOT NULL,
                                      `id_product_label` INT(11) NOT NULL,
                                      `position` TEXT NOT NULL,
                                      PRIMARY KEY (`id_product_label_location`)
                                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;'
        );

        return true;
    }

    public static function dropTable()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_location');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_lang');

        return true;
    }

    public static function prew1($id_category_default, $categories)
    {

        $key = serialize(func_get_args());
        if (Cache::isStored($key)) {
            return Cache::retrieve($key);
        }
        Cache::store($key, Db::getInstance()->executeS('SELECT l.*
             FROM ' . _DB_PREFIX_ . 'seosaproductlabels_category sc
             LEFT JOIN `' . _DB_PREFIX_ . 'seosaproductlabels_shop` AS l 
             ON l.id_product_label = sc.id_product_label
             WHERE IF(l.`include_category_product`,
             sc.`id_category` IN(' . implode(',', array_map('intval', $categories)) . '),
             sc.`id_category` = ' . (int)$id_category_default . ') 
             AND l.active = 1 AND l.`id_shop` = ' . (int)Context::getContext()->shop->id));
        return Cache::retrieve($key);
    }

    public static function getForProduct($id_product, $incl_category_setting = true)
    {
        $context = Context::getContext();
        $product = new Product($id_product);

        $items = array();

        $result = Db::getInstance()->executeS('SELECT l.`id_product_label`,
            l.`mini_price`,
            l.`max_price`,
            l.`quantity`,
            l.`quantity_max`,
            pl.`url`,
            l.`active`,
            ll.id_product_label_location, ll.position,
            ll.`id_product`,
            l.`label_type`,
            l.`groups`,
            l.`product_condition`,
            l.`date_from`,
            l.`date_to`
            FROM `'._DB_PREFIX_.'seosaproductlabels_location` ll
            LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_shop` AS l ON l.id_product_label = ll.id_product_label
            LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_lang` pl ON pl.id_product_label = l.id_product_label
            WHERE l.active = 1 AND (ll.id_product = '.(int)$id_product
            .')
            AND l.`id_shop` = '.(int)$context->shop->id.'
            AND pl.`id_lang` = '.(int)$context->language->id);

        if (is_array($result) && count($result)) {
            foreach ($result as $item) {
                $items[$item['id_product_label']] = $item;
            }
        }

        if ($incl_category_setting) {
            $categories = $product->getCategories();
            if (count($categories)) {
                $result = self::prew1($product->id_category_default, $categories);
                if (is_array($result) && count($result)) {
                    foreach ($result as $item) {
                        if (!array_key_exists($item['id_product_label'], $items)) {
                            $items[$item['id_product_label']] = $item;
                            $items[$item['id_product_label']]['id_product'] = 0;
                        }
                    }
                }
            }
        }

        if (!isset(self::$cache['spl_manufacturer'])) {
            self::$cache['spl_manufacturer'] = Db::getInstance()->executeS('SELECT l.*, sm.id_manufacturer, sl.text
                        FROM '._DB_PREFIX_.'seosaproductlabels_manufacturer sm
                        LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_shop` AS l 
                        ON l.id_product_label = sm.id_product_label
                        LEFT JOIN '._DB_PREFIX_.'seosaproductlabels_lang AS sl 
                        ON l.id_product_label = sl.id_product_label
                        WHERE l.active = 1 AND sl.id_lang = '.(int)$context->language->id.' 
                        AND l.`id_shop` = '.(int)$context->shop->id.' GROUP BY sm.id_manufacturer');
        }


        if (is_array(self::$cache['spl_manufacturer']) && count(self::$cache['spl_manufacturer'])) {
            foreach (self::$cache['spl_manufacturer'] as $key => $item) {
                if (self::$cache['spl_manufacturer'][$key]['id_manufacturer'] == $product->id_manufacturer) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }
            }
        }

        //----------------------------------------------------

        if (!isset(self::$cache['spl_featurer'])) {
            self::$cache['spl_featurer'] = Db::getInstance()->executeS('SELECT l.*, sf.id_featurer, sl.text
                        FROM '._DB_PREFIX_.'seosaproductlabels_featurer sf
                        LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_shop` AS l 
                        ON l.id_product_label = sf.id_product_label
                        LEFT JOIN '._DB_PREFIX_.'seosaproductlabels_lang AS sl 
                        ON l.id_product_label = sl.id_product_label
                        WHERE l.active = 1 AND sl.id_lang = '.(int)$context->language->id.' 
                        AND l.`id_shop` = '.(int)$context->shop->id);
        }


        if (is_array(self::$cache['spl_featurer']) && count(self::$cache['spl_featurer'])) {
            $features_product = Product::getFeaturesStatic($product->id);

            foreach (self::$cache['spl_featurer'] as $key => $item) {
                foreach ($features_product as $id_feature_value) {
                    if (self::$cache['spl_featurer'][$key]['id_featurer'] == $id_feature_value['id_feature_value']) {
                        if (!array_key_exists($item['id_product_label'], $items)) {
                            $items[$item['id_product_label']] = $item;
                            $items[$item['id_product_label']]['id_product'] = 0;
                        }
                    }
                }
            }
        }
        //----------------------------------------------------

        if (!isset(self::$cache['spl_supplier'])) {
            self::$cache['spl_supplier'] = Db::getInstance()->executeS('SELECT l.*, ss.id_supplier, sl.text
                        FROM ' . _DB_PREFIX_ . 'seosaproductlabels_supplier ss
                        LEFT JOIN `' . _DB_PREFIX_ . 'seosaproductlabels_shop` AS l 
                        ON l.id_product_label = ss.id_product_label
                        LEFT JOIN ' . _DB_PREFIX_ . 'seosaproductlabels_lang AS sl 
                        ON l.id_product_label = sl.id_product_label
                        WHERE l.active = 1 AND sl.id_lang = ' . (int)$context->language->id . ' 
                        AND l.`id_shop` = ' . (int)$context->shop->id);
        }

        if (is_array(self::$cache['spl_supplier']) && count(self::$cache['spl_supplier'])) {
            foreach (self::$cache['spl_supplier'] as $key => $item) {
                $associated_suppliers = ProductSupplier::getSupplierCollection($product->id);
                foreach ($associated_suppliers as $associated_supplier) {
                    if (self::$cache['spl_supplier'][$key]['id_supplier'] == $associated_supplier->id_supplier) {
                        if (!array_key_exists($item['id_product_label'], $items)) {
                            $items[$item['id_product_label']] = $item;
                            $items[$item['id_product_label']]['id_product'] = 0;
                        }
                    }
                }
            }
        }



            self::$cache['spl_cart_rule'] = Db::getInstance()->executeS(
                'SELECT l.*, cr.id_cart_rule, sl.text, sl.hint, sl.url
                        FROM ' . _DB_PREFIX_ . 'seosaproductlabels_cart_rules cr
                        LEFT JOIN `' . _DB_PREFIX_ . 'seosaproductlabels_shop` AS l 
                        ON l.id_product_label = cr.id_product_label
                        LEFT JOIN ' . _DB_PREFIX_ . 'seosaproductlabels_lang AS sl 
                        ON l.id_product_label = sl.id_product_label
                        LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule` crp ON crp.`id_cart_rule` = cr.`id_cart_rule` 
                        WHERE l.active = 1 AND sl.id_lang = ' . (int)$context->language->id . ' 
                        AND crp.`active` = 1 AND l.`id_shop` = ' . (int)$context->shop->id
            );

        if (count(self::$cache['spl_cart_rule'])) {
            $type = Db::getInstance()->executes(
                'SELECT pr.`type` FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule`  pr
                LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` rg 
                ON rg.`id_product_rule_group` = pr.`id_product_rule_group`
                WHERE rg.`id_cart_rule` =' . (int)self::$cache['spl_cart_rule'][0]['id_cart_rule']
            );
        } else {
            $type = array();
        }

        $manufactures = array();
        $categori = array();
        $products_r = array();
        foreach ($type as $item) {
            if ($item['type'] == 'categories') {
                $categories = Product::getProductCategories($id_product);
                $categories_str = "";
                foreach ($categories as $key => $element) {
                    if (count($categories) > $key && $key != 0) {
                        $categories_str = $categories_str . ',' . $element;
                    } else {
                        $categories_str .= $element;
                    }
                }
                $categori = Db::getInstance()->executeS(
                    'SELECT crprv.id_item AS id_category, crprg.id_cart_rule 
            FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` crprv
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule` crpr 
            ON crprv.id_product_rule = crpr.id_product_rule
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` crprg 
            ON crprg.id_product_rule_group = crpr.id_product_rule_group
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule` cr 
            ON cr.id_cart_rule = crprg.id_cart_rule
            WHERE cr.active = 1 AND crprv.id_item  IN(' . $categories_str.')'
                );
            }

            if ($item['type'] == 'manufacturers') {
                $manufactures = Db::getInstance()->executeS(
                    'SELECT crprv.id_item AS id_manufacturer, crprg.id_cart_rule 
            FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` crprv
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule` crpr 
            ON crprv.id_product_rule = crpr.id_product_rule
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` crprg 
            ON crprg.id_product_rule_group = crpr.id_product_rule_group
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule` cr 
            ON cr.id_cart_rule = crprg.id_cart_rule
            WHERE cr.active = 1 AND crprv.id_item = ' . (int)$product->id_manufacturer
                );
            }
            if ($item['type'] == 'products') {
                $products_r = Db::getInstance()->executeS(
                    'SELECT crprv.id_item AS id_product, crprg.id_cart_rule 
            FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` crprv
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule` crpr 
            ON crprv.id_product_rule = crpr.id_product_rule
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` crprg 
            ON crprg.id_product_rule_group = crpr.id_product_rule_group
            LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule` cr ON cr.id_cart_rule = crprg.id_cart_rule
            WHERE cr.active = 1 AND crprv.id_item = ' . (int)$id_product
                );
            }
        }

        if (is_array($categori) || is_array($manufactures) || is_array($products_r)) {
            if (is_array($categori) && is_array($manufactures) && is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = array_merge($categori, $manufactures, $products_r);
            }
            if (is_array($categori) && is_array($manufactures) && !is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = array_merge($categori, $manufactures);
            }
            if (!is_array($categori) && is_array($manufactures) && is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = array_merge($manufactures, $products_r);
            }
            if (!is_array($categori) && !is_array($manufactures) && is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = $products_r;
            }
            if (is_array($categori) && !is_array($manufactures) && !is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = $categori;
            }
            if (!is_array($categori) && is_array($manufactures) && !is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = $manufactures;
            }
            if (is_array($categori) && !is_array($manufactures) && is_array($products_r)) {
                self::$cache['cart_rule_product_rule_value'] = array_merge($categori, $products_r);
            }
        }

        if (is_array(self::$cache['spl_cart_rule']) && is_array(self::$cache['cart_rule_product_rule_value'])) {
            foreach (self::$cache['spl_cart_rule'] as $res_cr) {
                foreach (self::$cache['cart_rule_product_rule_value'] as $prod_cr) {
                    if (!array_key_exists($res_cr['id_product_label'], $items)) {
                        if ($res_cr['id_cart_rule'] == $prod_cr['id_cart_rule']) {
                            $items[$res_cr['id_product_label']] = $res_cr;
                            $items[$res_cr['id_product_label']]['id_product'] = 0;
                        }
                    }
                }
            }
        }

        if (!isset(self::$cache['spl_seosaproductlabels_lang'])) {
            self::$cache['spl_seosaproductlabels_lang'] = Db::getInstance()->executeS(
                'SELECT sl.`text`, s.`id_product_label`,spl.`name`, s.`text_css`, sl.`hint`,
            sl.`url`, s.`label_type`, s.`product_status`, s.`position`, s.`groups`, s.`product_condition`,
            s.`date_from`, s.`date_to`, s.`select_for`, s.`mini_price`,
            s.`max_price`, s.`quantity`, s.`quantity_max`, s.`id_shop`
            FROM ' . _DB_PREFIX_ . 'seosaproductlabels_lang sl
            LEFT JOIN `' . _DB_PREFIX_ . 'seosaproductlabels_shop` AS s ON sl.id_product_label = s.id_product_label
            LEFT JOIN `' . _DB_PREFIX_ . 'seosaproductlabels`  AS spl ON spl.id_product_label = s.id_product_label
            WHERE sl.id_lang="' . (int)$context->language->id . '" AND s.`active` = 1 
            AND s.`id_shop` = ' . (int)$context->shop->id
            );
        }

        if (is_array(self::$cache['spl_seosaproductlabels_lang'])
            && count(self::$cache['spl_seosaproductlabels_lang'])) {
            foreach (self::$cache['spl_seosaproductlabels_lang'] as $key => $item) {
                if ($product->on_sale && $item['product_status'] == ProductLabelSeo::_STATUS_SALE) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }

                if (self::checkNewProduct($product) && $item['product_status'] == ProductLabelSeo::_STATUS_NEW) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }

                if ($item['product_condition'] == $product->condition) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }

                if ($item['select_for'] == self::_FOR_BESTSELLERS_ && !isset(self::$cache['bestsellers'])) {
                    if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                        $bestsellers = ProductSale::getBestSales(
                            (int)$context->language->id,
                            0,
                            (int)Configuration::get('PS_BLOCK_BESTSELLERS_TO_DISPLAY'),
                            null
                        );
                    } else {
                        $bestsellers = ProductSale::getBestSalesLight(
                            (int)$context->language->id,
                            0,
                            (int)Configuration::get('PS_BLOCK_BESTSELLERS_TO_DISPLAY'),
                            null
                        );
                    }
                    if (!empty($bestsellers)) {
                        foreach ($bestsellers as $bestseller) {
                            self::$cache['bestsellers'][] = $bestseller['id_product'];
                        }
                    }
                }

                if ($item['select_for'] == self::_FOR_BESTSELLERS_
                    && in_array($id_product, self::$cache['bestsellers'])) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }
                if ($item['select_for'] == self::_FOR_QUANTITY_) {
                    if (!array_key_exists($item['id_product_label'], $items)) {
                        $qty = Product::getQuantity($product->id);
                        if ($qty >= $item['quantity']
                            && (!(int)$item['quantity_max'] || $qty <= $item['quantity_max'])) {
                            $items[$item['id_product_label']] = $item;
                            $items[$item['id_product_label']]['id_product'] = 0;
                        }
                    }
                }
            }
        }

        if (count($items) && is_array(self::$cache['spl_seosaproductlabels_lang'])
            && count(self::$cache['spl_seosaproductlabels_lang'])) {
            $date = strtotime(date('Y-m-d'));
            foreach ($items as $key => &$item) {
                if (strtotime($item['date_from']) > $date || ($date > strtotime($item['date_to'])
                        && $item['date_to'] != '0000-00-00')) {
                    if ($context->controller->controller_type == 'front') {
                        unset($items[$key]);
                        continue;
                    }
                }
                $groups = $item['groups'] ? explode(',', $item['groups']) : array();

                if ($context->controller->controller_type != 'admin'
                    && !in_array($context->customer->id_default_group, $groups)) {
                    unset($items[$key]);
                    continue;
                }

                $group_lab = explode(',', $item['groups']);
                foreach ($group_lab as $value) {
                    if ($value == (string)$context->customer->id_default_group) {
                        $items[$item['id_product_label']] = $item;
                        $items[$item['id_product_label']]['id_product'] = 0;
                    }
                }


                if (array_key_exists($item['id_product_label'], $items)) {
                    foreach (self::$cache['spl_seosaproductlabels_lang'] as $value) {
                        if ($value['id_product_label'] == $item['id_product_label']) {
                            $item['hint'] = $value['hint'];
                            $item['text'] = $value['text'];
                            $item['url'] = $value['url'];
                            $item['text_css'] = $value['text_css'];
                            $item['id_lang'] = $context->language->id;
                            $item['mini_price'] = $value['mini_price'];
                            $item['max_price'] = $value['max_price'];
                            $item['name'] = $value['name'];
                            $item['id_shop'] = $value['id_shop'];

                            if (!$value['label_type']) {
                                $item['label_type'] = $item['text'] ? 'text' : 'image';
                            } else {
                                $item['label_type'] = $value['label_type'];
                            }
                        }
                    }
                }
            }
        }

        if (is_array($items) && count($items)) {
            foreach ($items as &$item) {
                $item['fix_hint_position'] = (strpos($item['position'], 'left') === false
                    ? 'left' : 'right');
            }
        }
        return $items;
    }

    private static function checkNewProduct($product)
    {
        $key = 'checkNewProduct_'.$product->id;

        if (Cache::isStored($key)) {
            return Cache::retrieve($key);
        }
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }

        $sql = 'SELECT DATEDIFF(`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(int)$nb_days_new_product.' DAY)) > 0 AS new
					FROM `'._DB_PREFIX_.'product_shop` WHERE id_product = '.(int)$product->id;

        Cache::store($key, Db::getInstance()->getValue($sql));

        return Cache::retrieve($key);
    }

    public static function clearForProduct($id_product)
    {
        $context = Context::getContext();
        return Db::getInstance()->executeS(
            'DELETE FROM `'._DB_PREFIX_.'seosaproductlabels_location` ll
            WHERE ll.id_product = '.(int)$id_product.' AND ll.`id_shop` = '.(int)$context->shop->id
        );
    }

    public static function getLocations($id_product, $id_product_label)
    {
        $result = Db::getInstance()->executeS('SELECT sl.`id_product_label_location` 
            FROM `'._DB_PREFIX_.'seosaproductlabels_location` sl
            WHERE sl.`id_product` = '.(int)$id_product
            .' AND sl.`id_product_label` = '.(int)$id_product_label
            .' AND sl.`id_shop` IN('.implode(',', array_map('intval', Shop::getContextListShopID())).')');
        return (is_array($result) && count($result) ? $result : array());
    }
}
