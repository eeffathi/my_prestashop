<?php
/**
* 2007-2020 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class Supplier extends SupplierCore
{
    public static function getProducts(
        $id_supplier,
        $id_lang,
        $p,
        $n,
        $order_by = null,
        $order_way = null,
        $get_total = false,
        $active = true,
        $active_category = true
    ) {
        $context = Context::getContext();
        if (isset($context->filtered_result) && $context->filtered_result['controller'] == 'supplier') {
            if ($get_total) {
                return $context->filtered_result['total'];
            }
            return $context->filtered_result['products'];
        } else {
            return parent::getProducts(
                $id_supplier,
                $id_lang,
                $p,
                $n,
                $order_by,
                $order_way,
                $get_total,
                $active,
                $active_category
            );
        }
    }
}
