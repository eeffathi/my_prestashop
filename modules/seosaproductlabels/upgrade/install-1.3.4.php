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

function upgrade_module_1_3_4()
{
    $res = true;
    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'seosaproductlabels_lang`');
    if (is_array($list_fields)) {
        foreach ($list_fields as $k => $field) {
            $list_fields[$k] = $field['Field'];
        }
        if (!in_array('hint', $list_fields)) {
            $res = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'seosaproductlabels_lang`
            ADD `hint` TEXT NOT NULL');
        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'seosaproductlabels`');
    if (is_array($list_fields)) {
        foreach ($list_fields as $k => $field) {
            $list_fields[$k] = $field['Field'];
        }
        if (!in_array('hint_background', $list_fields) && $res) {
            $res = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'seosaproductlabels`
            ADD `hint_background` VARCHAR(255) NOT NULL');
        }
        if (!in_array('hint_opacity', $list_fields) && $res) {
            $res = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'seosaproductlabels`
            ADD `hint_opacity` VARCHAR(255) NOT NULL');
        }
        if (!in_array('hint_text_color', $list_fields) && $res) {
            $res = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'seosaproductlabels`
            ADD `hint_text_color` VARCHAR(255) NOT NULL');
        }
    }

    return $res;
}
