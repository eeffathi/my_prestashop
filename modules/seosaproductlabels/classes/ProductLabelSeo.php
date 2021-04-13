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
 * Class ProductLabel
 */
class ProductLabelSeo extends ObjectModel
{
    const _STATUS_NEW = 1;
    const _STATUS_SALE = 2;

    public $id;
    public $name;
    public $url;
    public $active = false;
    public $label_type = 'image';
    public $position = 'center-center';
    public $mini_price = 0;
    public $max_price = 0;
    public $quantity;
    public $quantity_max;
    public $select_for;
    public $include_category_product = false;
    public $index_image_css = null;
    public $product_image_css = null;
    public $category_image_css = null;
    public $text;
    public $text_css = null;
    public $hint = null;
    public $hint_background = null;
    public $hint_opacity = 0;
    public $hint_text_color = null;
    public $product_status;
    public $groups;
    public $product_condition;
    public $date_from;
    public $date_to;

    public static $definition = array(
        'table'   => 'seosaproductlabels',
        'primary' => 'id_product_label',
        'multilang' => true,
        'multilang_shop' => true,
        'fields'  => array(
            'name'   => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 128),
            /* Shop fields */
            'url'    => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl'),
            'active' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'label_type' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'position' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'mini_price' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'max_price' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'quantity' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'quantity_max' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'select_for' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isInt'),
            'index_image_css' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'product_image_css' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'category_image_css' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'text' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
            'text_css' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'include_category_product' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
            'hint' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'hint_background' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'hint_opacity' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isFloat'),
            'hint_text_color' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'product_status' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isInt'),
            'groups' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'product_condition' => array('type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isString'),
            'date_from' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat'),
            'date_to' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat')
        ),
    );

    public static function createTable()
    {
        $sql = array();
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels` (
                  `id_product_label` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` TEXT NOT NULL,
                  `active` TINYINT(1) NOT NULL DEFAULT "0",
                  `label_type` VARCHAR(255) NULL DEFAULT NULL,
                  `include_category_product` TINYINT(1) NOT NULL DEFAULT "0",
                  `product_status` TINYINT(1) NOT NULL DEFAULT "0",
                  `product_condition`  VARCHAR(32) NOT NULL,
                  `groups` VARCHAR(255) NULL DEFAULT NULL,
                  `position` TEXT NOT NULL,
                  `select_for` INT(11) NOT NULL,
                  `text_css` VARCHAR(255) NULL DEFAULT NULL,
                  `index_image_css` VARCHAR(255) NOT NULL,
                  `product_image_css` VARCHAR(255) NOT NULL,
                  `category_image_css` VARCHAR(255) NOT NULL,
                  `hint_background` VARCHAR(255) NOT NULL,
                  `hint_opacity` VARCHAR(255) NOT NULL,
                  `hint_text_color` VARCHAR(255) NOT NULL,
                  `date_from` date NOT NULL,
                  `date_to` date NOT NULL,
                  `max_price` decimal(10,2)NOT NULL DEFAULT "0.00",
                  `mini_price` decimal(10,2)NOT NULL DEFAULT "0.00",
                  `quantity` VARCHAR(255) NOT NULL,
                  `quantity_max` VARCHAR(255) NOT NULL,
                  `bestsellers` int(10) NOT NULL,
                  PRIMARY KEY (`id_product_label`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_shop` ( 
                `id_product_label` INT(11) NOT NULL , 
                `id_shop` INT(11) NOT NULL , 
                `active` INT(11) NOT NULL , 
                `label_type` VARCHAR(255) NOT NULL , 
                `include_category_product` INT(11) NOT NULL , 
                `product_status` INT(11) NOT NULL , 
                `product_condition` VARCHAR(32) NOT NULL , 
                `groups` VARCHAR(255) NOT NULL , 
                `position` TEXT NOT NULL , 
                `select_for` INT(11) NOT NULL , 
                `text_css` VARCHAR(255) NOT NULL , 
                `index_image_css` VARCHAR(255) NOT NULL , 
                `product_image_css` VARCHAR(255) NOT NULL , 
                `category_image_css` VARCHAR(255) NOT NULL , 
                `hint_background` VARCHAR(255) NOT NULL , 
                `hint_opacity` VARCHAR(255) NOT NULL , 
                `hint_text_color` VARCHAR(255) NOT NULL , 
                `date_from` DATE NOT NULL , 
                `date_to` DATE NOT NULL , 
                `max_price` decimal(10,2) NOT NULL , 
                `mini_price` decimal(10,2) NOT NULL , 
                `quantity` VARCHAR(255) NOT NULL , 
                `quantity_max` VARCHAR(255) NOT NULL , 
                `bestsellers` INT(10) NOT NULL , 
                PRIMARY KEY (`id_product_label`, `id_shop`)) ENGINE = InnoDB;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_category` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_category` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_lang` (
              `id_product_label` int(11) NOT NULL,
              `id_lang` int(10) NOT NULL,
              `id_shop` INT NOT NULL,
              `text` VARCHAR(255) NOT NULL,
              `url` TEXT,
              `hint` TEXT NOT NULL,
              PRIMARY KEY (`id_product_label` , `id_lang`, `id_shop`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_manufacturer` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_manufacturer` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_featurer` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_featurer` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_supplier` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_supplier` int(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_cart_rules` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_cart_rule` int(10) NOT NULL,
              PRIMARY KEY (`id_product_label`,`id_cart_rule`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_excluded` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_excluded_product` int(11) NOT NULL,
              PRIMARY KEY (`id_product_label`,`id_excluded_product`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seosaproductlabels_included` (
              `id_product_label` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `id_included_product` int(11) NOT NULL,
              PRIMARY KEY (`id_product_label`,`id_included_product`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('seosaproductlabels', array('type' => 'shop'));
        Shop::addTableAssociation('seosaproductlabels_lang', array('type' => 'fk_shop'));

        parent::__construct($id, $id_lang, $id_shop);
    }

    public static function getList($active = false)
    {
        return Db::getInstance()->executeS(
            'SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels` a 
            LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_shop` s ON a.`id_product_label` = s.`id_product_label` 
            WHERE '.($active ? ' s.`active` = 1' : '1').' AND s.`id_shop` = '.(int)Context::getContext()->shop->id
        );
    }


    public static function dropTable()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_lang');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_shop');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_category');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_manufacturer');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_featurer');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_supplier');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_excluded');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_cart_rules');
        Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'seosaproductlabels_included');

        return true;
    }

    public static function getCategories($id_product_label)
    {
        $result = Db::getInstance()->executeS('SELECT id_category FROM '._DB_PREFIX_.'seosaproductlabels_category
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
        $ids = array();
        if (is_array($result) && count($result)) {
            foreach ($result as $item) {
                $ids[] = (int)$item['id_category'];
            }
        }
        return $ids;
    }

    public static function getImageCSS($id_product_label)
    {
        return Db::getInstance()->getRow(
            'SELECT `index_image_css` `index`, `product_image_css` `product`, `category_image_css` `category` 
            FROM `'._DB_PREFIX_.'seosaproductlabels_shop` WHERE `id_product_label` = '.(int)$id_product_label.' 
            AND `id_shop` = '.(int)Shop::getContextShopID()
        );
    }

    public static function getExcludedProducts($id_product_label, $id_lang)
    {
        return Db::getInstance()->executeS('SELECT e.`id_excluded_product`, pl.`name` 
            FROM `'._DB_PREFIX_.'seosaproductlabels_excluded` e
            JOIN `'._DB_PREFIX_.'product` p ON e.`id_excluded_product` = p.`id_product`
            JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = p.`id_product`
            WHERE e.`id_product_label` = '.(int)$id_product_label.' AND pl.`id_lang` = '.(int)$id_lang.' 
            AND e.`id_shop` = '.(int)Shop::getContextShopID());
    }

    public static function getIncludedProducts($id_product_label, $id_lang)
    {
        $key = 'getIncludedProducts'.$id_product_label.'_'.$id_lang;
        if (Cache::isStored($key)) {
            return Cache::retrieve($key);
        }

        Cache::store($key, Db::getInstance()->executeS('SELECT e.`id_included_product`, pl.`name` 
            FROM `'._DB_PREFIX_.'seosaproductlabels_included` e
            JOIN `'._DB_PREFIX_.'product` p ON e.`id_included_product` = p.`id_product`
            JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = p.`id_product`
            WHERE e.`id_product_label` = '.(int)$id_product_label.' AND pl.`id_lang` = '.(int)$id_lang.' 
            AND e.`id_shop` = '.(int)Shop::getContextShopID()));
        return Cache::retrieve($key);
    }

    public static function checkExcludedProductForLabel($id_product, $id_label)
    {
        return (bool)Db::getInstance()->getValue('SELECT `id_excluded_product` 
            FROM `'._DB_PREFIX_.'seosaproductlabels_excluded`
            WHERE `id_product_label` = '.(int)$id_label.' AND `id_excluded_product` = '.(int)$id_product.' 
            AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    public static function checkIncludedProductForLabel($id_product, $id_label)
    {
        if ($incl = self::getIncludedProducts($id_label, Context::getContext()->language->id)) {
            foreach ($incl as $value) {
                if ($id_product == $value['id_included_product']) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param $id_product_label
     * @return void
     */
    public static function deleteCategories($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_category
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    /**
     * @param $id_product_label
     * @return string number
     */
    public static function getManufacturer($id_product_label)
    {
        $result = Db::getInstance()->executeS('SELECT id_manufacturer 
        FROM '._DB_PREFIX_.'seosaproductlabels_manufacturer
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());

        if (is_array($result)) {
            $return = array();
            foreach ($result as $row) {
                $return[] = $row['id_manufacturer'];
            }

            return $return;
        }

        return '0';
    }

    /**
     * @param $id_product_label
     * @return string number
     */
    public static function getSupplier($id_product_label)
    {
        $result = Db::getInstance()->getRow('SELECT id_supplier FROM '._DB_PREFIX_.'seosaproductlabels_supplier
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());

        if (is_array($result)) {
            return $result['id_supplier'];
        }

        return '0';
    }

    public static function getProductStatus($id_product_label)
    {
        return Db::getInstance()->getValue('SELECT `product_status` FROM `'._DB_PREFIX_.'seosaproductlabels_shop`
            WHERE `id_product_label` = '.(int)$id_product_label);
    }

    public static function getProductQuantity($id_product_label)
    {
        return Db::getInstance()->getValue('SELECT `quantity` FROM `'._DB_PREFIX_.'seosaproductlabels_shop`
            WHERE `id_product_label` = '.(int)$id_product_label);
    }

    public static function getProductQuantitymax($id_product_label)
    {
        return Db::getInstance()->getValue('SELECT `quantity_max` FROM `'._DB_PREFIX_.'seosaproductlabels_shop`
            WHERE `id_product_label` = '.(int)$id_product_label);
    }

    public static function getProductCondition($id_product_label)
    {
        return Db::getInstance()->getValue('SELECT `product_condition` FROM `'._DB_PREFIX_.'seosaproductlabels_shop`
            WHERE `id_product_label` = '.(int)$id_product_label);
    }

    public static function getCartRules($active = true, $id_lang = false)
    {
        $sql = 'SELECT crl.`id_cart_rule`, crl.`name` FROM `'._DB_PREFIX_.'cart_rule_lang` crl';
        $id_shop = Shop::getContextShopID();
        if ($active) {
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON crl.`id_cart_rule` = cr.`id_cart_rule`';
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'cart_rule_shop` crs ON crs.`id_shop` = ' . $id_shop;
        }

        $sql .= ' WHERE crl.`id_lang` = '.($id_lang ? (int)$id_lang : (int)Context::getContext()->language->id);

        if ($active) {
            $sql .= ' AND cr.`active` = 1';
            $sql .= ' AND cr.`id_cart_rule` = crs.`id_cart_rule`';
        }
// находим общие для всех магазинов правила корзины
        $sql2 = 'SELECT crl.`id_cart_rule`, crl.`name` FROM `'._DB_PREFIX_.'cart_rule_lang` crl';
        $sql2 .= ' LEFT JOIN `'._DB_PREFIX_.'cart_rule_shop` crs ON crs.`id_shop` = ' . $id_shop;
        $sql2 .= ' LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON crl.`id_cart_rule` = cr.`id_cart_rule`';
        $sql2 .= ' WHERE crl.`id_lang` = '.($id_lang ? (int)$id_lang : (int)Context::getContext()->language->id);
        $sql2 .= '  AND cr.`active` = 1 AND cr.`shop_restriction` = 0';
// объединяем найденное
        $merge = array_merge(Db::getInstance()->executeS($sql2), Db::getInstance()->executeS($sql));


        return $merge;
    }

    public static function getCartRulesForProductLabel($id_product_label)
    {
        $res = Db::getInstance()->executeS('SELECT `id_cart_rule` FROM '._DB_PREFIX_.'seosaproductlabels_cart_rules
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());

        $ids_cart_rule = array();

        if (is_array($res)) {
            foreach ($res as $row) {
                $ids_cart_rule[] = $row['id_cart_rule'];
            }
        }

        return $ids_cart_rule;
    }

    /**
     * @param $id_product_label
     * @return void
     */
    public static function deleteManufacturer($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_manufacturer
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    /**
     * @param $id_product_label
     * @return void
     */
    public static function deleteSupplier($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_supplier
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    public static function deleteQuantity($id_product_label)
    {
        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'seosaproductlabels_shop 
        SET quantity = ""  
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    public static function deleteCartRules($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_cart_rules
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    /**
     * @param $id_product_label
     * @return void
     */
    public static function deleteExcluded($id_product_label, $id_product = null)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_excluded
        WHERE id_product_label = '.(int)$id_product_label.' 
        AND `id_shop` = '.(int)Shop::getContextShopID().($id_product ? ' 
        AND `id_excluded_product` = '.(int)$id_product : ''));
    }

    /**
     * @param $id_product_label
     * @return void
     */
    public static function deleteIncluded($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_included
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    /**
 * @param int $id_product_label
 * @param array $ids
 * @param bool $delete_old_items
 * @return void
 */
    public static function setCategories($id_product_label, $ids, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteCategories($id_product_label);
        }

        $insert = array();
        if (is_array($ids) && count($ids)) {
            foreach ($ids as $id) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_category' => (int)$id,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_category', $insert);
            }
        }
    }

    /**
     * @param int $id_product_label
     * @param array $id_manufacturer
     * @param bool $delete_old_items
     * @return void
     */
    public static function setManufacturer($id_product_label, $ids_manufacturer, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteManufacturer($id_product_label);
        }

        $insert = array();
        if (is_array($ids_manufacturer) && count($ids_manufacturer)) {
            foreach ($ids_manufacturer as $id_manufacturer) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_manufacturer' => (int)$id_manufacturer,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_manufacturer', $insert);
            }
        }
    }

    public static function setFeatures($id_product_label, $ids_features, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteFeaturer($id_product_label);
        }

        $insert = array();

        if (is_array($ids_features) && count($ids_features)) {
            foreach ($ids_features as $ids_featurer) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_featurer' => (int)$ids_featurer,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_featurer', $insert);
            }
        }
    }
    public static function getFeaturer($id_product_label)
    {
        $result = Db::getInstance()->executeS('SELECT id_featurer FROM '._DB_PREFIX_.'seosaproductlabels_featurer
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());

        if (is_array($result)) {
            $return = array();
            foreach ($result as $row) {
                $return[] = $row['id_featurer'];
            }

            return $return;
        }
        return '0';
    }
    public static function deleteFeaturer($id_product_label)
    {
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'seosaproductlabels_featurer
        WHERE id_product_label = '.(int)$id_product_label.' AND `id_shop` = '.(int)Shop::getContextShopID());
    }

    /**
     * @param int $id_product_label
     * @param array $id_supplier
     * @param bool $delete_old_items
     * @return void
     */
    public static function setSupplier($id_product_label, $ids_supplier, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteSupplier($id_product_label);
        }

        $insert = array();
        if (is_array($ids_supplier) && count($ids_supplier)) {
            foreach ($ids_supplier as $id_supplier) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_supplier' => (int)$id_supplier,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_supplier', $insert);
            }
        }
    }

    public static function setCartRules($id_product_label, $ids_cart_rule, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteCartRules($id_product_label);
        }

        $insert = array();
        if (is_array($ids_cart_rule)) {
            foreach ($ids_cart_rule as $id_cart_rule) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_cart_rule' => (int)$id_cart_rule,
                    'id_shop' => Shop::getContextShopID()
                );
            }
            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_cart_rules', $insert);
            }
        }
    }

    public static function saveExcluded($id_product_label, $excluded, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteExcluded($id_product_label);
        }

        $insert = array();
        if (is_array($excluded) && count($excluded)) {
            foreach ($excluded as $id_excluded) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_excluded_product' => (int)$id_excluded,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_excluded', $insert);
            }
        }
    }

    public static function saveIncluded($id_product_label, $included, $delete_old_items = true)
    {
        if ($delete_old_items) {
            self::deleteIncluded($id_product_label);
        }

        $insert = array();
        if (is_array($included) && count($included)) {
            self::deleteExcluded($id_product_label);
            foreach ($included as $id_included) {
                $insert[] = array(
                    'id_product_label' => (int)$id_product_label,
                    'id_included_product' => (int)$id_included,
                    'id_shop' => Shop::getContextShopID()
                );
            }

            if (count($insert)) {
                Db::getInstance()->insert('seosaproductlabels_included', $insert);
            }
        }
    }
    
    public function checkDateRange()
    {
        if (strtotime($this->date_from) > strtotime($this->date_to)) {
            return false;
        }
        return true;
    }
    
    public function add($auto_date = true, $null_values = false)
    {
        if (!$this->checkDateRange()) {
            return false;
        }

        $context = Shop::getContext();
        $context_id_shop = Shop::getContextShopID();
        Shop::setContext(Shop::CONTEXT_ALL);
        $result = parent::add($auto_date, $null_values);
        Shop::setContext($context, $context_id_shop);

        return $result;
    }
    
    public function update($null_values = false)
    {
        if (!$this->checkDateRange()) {
            return false;
        }

        $asso_shop = array();
        foreach (Db::getInstance()->executeS('SELECT id_shop FROM '._DB_PREFIX_.'shop') as $shop) {
            $asso_shop[$shop['id_shop']] = $shop['id_shop'];
        }

        ${'_POST'}['checkBoxShopAsso_'.ProductLabelSeo::$definition['table']] = $asso_shop;

        return parent::update($null_values);
    }
}
