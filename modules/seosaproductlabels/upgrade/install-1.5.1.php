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
 * @author    SeoSA    <885588@bk.ru>
 * @copyright 2012-2021 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

function upgrade_module_1_5_1()
{
    $sql = array();
    $sql[] = 'CREATE TABLE `'._DB_PREFIX_.'seosaproductlabels_shop` ( 
                `id_product_label` INT NOT NULL , 
                `id_shop` INT NOT NULL , 
                `active` INT NOT NULL , 
                `label_type` VARCHAR(255) NOT NULL , 
                `include_category_product` INT NOT NULL , 
                `product_status` INT NOT NULL , 
                `product_condition` VARCHAR(32) NOT NULL , 
                `groups` VARCHAR(255) NOT NULL , 
                `position` TEXT NOT NULL , 
                `select_for` INT NOT NULL , 
                `text_css` VARCHAR(255) NOT NULL , 
                `index_image_css` VARCHAR(255) NOT NULL , 
                `product_image_css` VARCHAR(255) NOT NULL , 
                `category_image_css` VARCHAR(255) NOT NULL , 
                `hint_background` VARCHAR(255) NOT NULL , 
                `hint_opacity` VARCHAR(255) NOT NULL , 
                `hint_text_color` VARCHAR(255) NOT NULL , 
                `date_from` DATE NOT NULL , 
                `date_to` DATE NOT NULL , 
                `max_price` INT NOT NULL , 
                `mini_price` INT NOT NULL , 
                `quantity` VARCHAR(255) NOT NULL , 
                `quantity_max` VARCHAR(255) NOT NULL , 
                `bestsellers` INT(10) NOT NULL , 
                PRIMARY KEY (`id_product_label`, `id_shop`)) ENGINE = InnoDB;';

    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_lang` ADD `id_shop` INT NOT NULL AFTER `id_lang`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_lang` 
    DROP PRIMARY KEY, ADD PRIMARY KEY (`id_product_label`, `id_lang`, `id_shop`) USING BTREE;';

    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_cart_rules` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_category` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_excluded` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_included` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_manufacturer` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'seosaproductlabels_supplier` 
    ADD `id_shop` INT NOT NULL AFTER `id_product_label`;';
    foreach ($sql as $query) {
        if (!Db::getInstance()->execute($query)) {
            return false;
        }
    }

    $shops = Shop::getShops();
    $labels = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels`');

    $res = true;
    foreach ($labels as $label) {
        foreach ($shops as $shop) {
            $res &= Db::getInstance()->insert('seosaproductlabels_shop', array(
                'id_product_label' => $label['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'active' => $label['active'],
                'label_type' => $label['label_type'],
                'include_category_product' => $label['include_category_product'],
                'product_status' => $label['product_status'],
                'product_condition' => $label['product_condition'],
                'groups' => $label['groups'],
                'position' => $label['position'],
                'select_for' => $label['select_for'],
                'text_css' => $label['text_css'],
                'index_image_css' => $label['index_image_css'],
                'product_image_css' => $label['product_image_css'],
                'category_image_css' => $label['category_image_css'],
                'hint_background' => $label['hint_background'],
                'hint_opacity' => $label['hint_opacity'],
                'hint_text_color' => $label['hint_text_color'],
                'date_from' => $label['date_from'],
                'date_to' => $label['date_to'],
                'max_price' => $label['max_price'],
                'mini_price' => $label['mini_price'],
                'quantity' => $label['quantity'],
                'quantity_max' => $label['quantity_max'],
                'bestsellers' => $label['bestsellers']
            ));
        }
    }

    if (!$res) {
        return false;
    }

    $manufacturers = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_manufacturer`');

    foreach ($manufacturers as $manufacturer) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_manufacturer', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_manufacturer = '.(int)$manufacturer['id_manufacturer'].' 
                AND id_product_label = '.(int)$manufacturer['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_manufacturer', array(
                'id_product_label' => $manufacturer['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_manufacturer' => $manufacturer['id_manufacturer']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }

    $categories = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_category`');

    foreach ($categories as $category) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_category', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_category = '.(int)$category['id_category'].' 
                AND id_product_label = '.(int)$category['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_category', array(
                'id_product_label' => $category['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_category' => $category['id_category']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }

    $excludeds = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_excluded`');

    foreach ($excludeds as $excluded) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_excluded', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_excluded_product = '.(int)$excluded['id_excluded_product'].' 
                AND id_product_label = '.(int)$excluded['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_excluded', array(
                'id_product_label' => $excluded['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_excluded_product' => $excluded['id_excluded_product']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }

    $includeds = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_included`');

    foreach ($includeds as $included) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_included', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_included_product = '.(int)$included['id_included_product'].' 
                AND id_product_label = '.(int)$included['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_included', array(
                'id_product_label' => $included['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_included_product' => $included['id_included_product']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }

    $suppliers = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_supplier`');

    foreach ($suppliers as $supplier) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_supplier', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_supplier = '.(int)$supplier['id_supplier'].' 
                AND id_product_label = '.(int)$supplier['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_supplier', array(
                'id_product_label' => $supplier['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_supplier' => $supplier['id_supplier']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }

    $cart_rules = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'seosaproductlabels_cart_rules`');

    foreach ($cart_rules as $cart_rule) {
        $i = 0;
        foreach ($shops as $shop) {
            if ($i == 0) {
                $res &= Db::getInstance()->update('seosaproductlabels_cart_rules', array(
                    'id_shop' => $shop['id_shop']
                ), 'id_supplier = '.(int)$cart_rule['id_cart_rule'].' 
                AND id_product_label = '.(int)$cart_rule['id_product_label']);
            }
            $res &= Db::getInstance()->insert('seosaproductlabels_cart_rules', array(
                'id_product_label' => $cart_rule['id_product_label'],
                'id_shop' => $shop['id_shop'],
                'id_cart_rule' => $cart_rule['id_cart_rule']
            ));
            $i++;
        }
    }

    if (!$res) {
        return false;
    }
    return $res;
}
