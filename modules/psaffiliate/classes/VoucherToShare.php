<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code.
 *
 * @author    Active Design <office@activedesign.ro>
 * @copyright 2016-2018 Active Design
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class VoucherToShare extends ObjectModel
{
    public $id;
    public $id_vts;
    public $id_cart_rule;
    public $id_cart_rule_template;
    public $id_affiliate;
    public $code_noprefix;
    public $code_prefix;
    public $name;
    public $date_add;
    public $date_lastused;
    public $sales;
    public $sales_approved;
    public $total_earnings;
    public $total_earnings_approved;
    public $voucher_name;
    public $voucher_code;
    public $cart_rule;
    public $quantity;
    public $description;

    public static $definition = array(
        'table' => 'aff_vts',
        'primary' => 'id_vts',
        'fields' => array(
            'id_vts' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => false),
            'id_cart_rule' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => true),
            'id_cart_rule_template' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => true),
            'id_affiliate' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'copy_post' => true),
            'code_prefix' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'copy_post' => true),
            'code_noprefix' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'copy_post' => true),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
        ),
    );

    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);

        $this->cart_rule = new CartRule($this->id_cart_rule, $id_lang);
        $this->code = $this->cart_rule->code;
        $this->name = $this->cart_rule->name;
        $this->description = $this->cart_rule->description;
        $this->quantity = $this->cart_rule->quantity;
        $this->date_lastused = Db::getInstance()->getValue('SELECT o.`date_add` FROM `'._DB_PREFIX_.'orders` o LEFT JOIN `'._DB_PREFIX_.'order_cart_rule` ocr ON (ocr.`id_order` = o.`id_order`) WHERE ocr.`id_cart_rule` = "'.(int)$this->id_cart_rule.'" ORDER BY o.`date_add` DESC');
        $this->sales = (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'order_cart_rule` ocr LEFT JOIN `'._DB_PREFIX_.'aff_sales` s ON (ocr.`id_order` = s.`id_order`) WHERE ocr.`id_cart_rule` = "'.(int)$this->id_cart_rule.'" AND s.`id_affiliate` = "'.(int)$this->id_affiliate.'"');
        $this->sales_approved = (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'order_cart_rule` ocr LEFT JOIN `'._DB_PREFIX_.'aff_sales` s ON (ocr.`id_order` = s.`id_order`) WHERE ocr.`id_cart_rule` = "'.(int)$this->id_cart_rule.'" AND s.`id_affiliate` = "'.(int)$this->id_affiliate.'" AND s.`approved` = "1"');
        $this->total_earnings = (float)Db::getInstance()->getValue('SELECT SUM(s.`commission`) FROM `'._DB_PREFIX_.'order_cart_rule` ocr LEFT JOIN `'._DB_PREFIX_.'aff_sales` s ON (ocr.`id_order` = s.`id_order`) WHERE ocr.`id_cart_rule` = "'.(int)$this->id_cart_rule.'" AND s.`id_affiliate` = "'.(int)$this->id_affiliate.'"');
        $this->total_earnings_approved = (float)Db::getInstance()->getValue('SELECT SUM(s.`commission`) FROM `'._DB_PREFIX_.'order_cart_rule` ocr LEFT JOIN `'._DB_PREFIX_.'aff_sales` s ON (ocr.`id_order` = s.`id_order`) WHERE ocr.`id_cart_rule` = "'.(int)$this->id_cart_rule.'" AND s.`id_affiliate` = "'.(int)$this->id_affiliate.'" AND s.`approved` = "1"');
    }

    public static function getVoucherTemplates($active = false, $id_lang = null)
    {
        $voucher_templates = AffConf::getConfig('voucher_templates[]');
        $return = array();
        if ($voucher_templates) {
            foreach ($voucher_templates as $voucher_template) {
               $cart_rule = new CartRule((int)$voucher_template, $id_lang);
               if (Validate::isLoadedObject($cart_rule) && (!$active || ($cart_rule->active && strtotime($cart_rule->date_from) < time() && strtotime($cart_rule->date_to) > time()))) {
                   $return[] = (array)$cart_rule;
               }
            }
        }

        return $return;
    }

    public static function getVoucherTemplatesIds()
    {
        $templates = AffConf::getConfig('voucher_templates[]');
        if ($templates) {
            return $templates;
        }
        return array();
    }

    public static function hasVoucherTemplates($active = false)
    {
        $vouchers = self::getVoucherTemplates($active);

        return (bool)sizeof($vouchers);
    }

    public static function isActiveVoucherTemplate($id_voucher_template)
    {
        $voucher_templates = AffConf::getConfig('voucher_templates[]');
        if ($voucher_templates) {
            foreach ($voucher_templates as $voucher_template) {
                if ((int)$voucher_template == $id_voucher_template) {
                    $cart_rule = new CartRule((int)$voucher_template);
                    if (Validate::isLoadedObject($cart_rule) && ($cart_rule->active && strtotime($cart_rule->date_from) < time() && strtotime($cart_rule->date_to) > time())) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function create()
    {
        $cart_rule = new CartRule($this->id_cart_rule_template);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $cart_rule->name[$lang['id_lang']] = $this->voucher_name;
        }
        $cart_rule->code = $this->voucher_code;
        $cart_rule->id_cart_rule = 0;
        $cart_rule->id = 0;
        if ($cart_rule->add()) {
            $this->id_cart_rule = $cart_rule->id;
            $this->copyCartRuleInfo($this->id_cart_rule_template, $this->id_cart_rule);
            return $this->save();
        }

        return false;
    }

    public function update($null_values = false)
    {
        $parent = parent::update($null_values);
        $cart_rule = new CartRule($this->id_cart_rule);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $cart_rule->name[$lang['id_lang']] = $this->voucher_name;
        }
        $cart_rule->code = $this->voucher_code;
        return $parent && $cart_rule->save();
    }

    public static function getAffiliateVouchersToShare($id_affiliate, $id_lang = null)
    {
        $return = array();
        $vouchers_to_share = Db::getInstance()->executeS('SELECT `id_vts` FROM `'._DB_PREFIX_.'aff_vts` WHERE `id_affiliate` = "'.(int)$id_affiliate.'" ORDER BY `id_vts` DESC');
        foreach ($vouchers_to_share as $vts) {
            $return[] = new VoucherToShare($vts['id_vts'], $id_lang);
        }

        return $return;
    }

    public static function getIdAffiliateByIdCartRule($id_cart_rule)
    {
        return (int)Db::getInstance()->getValue('SELECT `id_affiliate` FROM `'._DB_PREFIX_.'aff_vts` WHERE `id_cart_rule` = "'.(int)$id_cart_rule.'"');
    }

    public static function getCartRulesByIdOrder($id_order)
    {
        $return = array();
        $cart_rules = Db::getInstance()->executeS('SELECT `id_cart_rule` FROM `'._DB_PREFIX_.'order_cart_rule` WHERE `id_order` = "'.(int)$id_order.'" ORDER BY `value` DESC');
        if ($cart_rules) {
            foreach ($cart_rules as $cart_rule) {
                $return[] = (int)$cart_rule['id_cart_rule'];
            }
        }

        return $return;
    }

    public static function cartRuleExists($code, $id_cart_rule_exclude = null)
    {
        $return = (bool)Db::getInstance()->getValue('
		SELECT `id_cart_rule`
		FROM `'._DB_PREFIX_.'cart_rule`
		WHERE `code` = "' . pSQL($code) . '" AND `id_cart_rule` != "'.(int)$id_cart_rule_exclude.'"');

        return $return;
    }

    public static function isVoucherToShareByCartRule($id_cart_rule)
    {
        return (bool)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'aff_vts` WHERE `id_cart_rule` = "'.(int)$id_cart_rule.'"');
    }

    public static function getCartRulesUsingTemplate($id_cart_rule_template)
    {
        $cart_rules = Db::getInstance()->executeS('SELECT `id_vts`, `id_cart_rule` FROM `'._DB_PREFIX_.'aff_vts` WHERE `id_cart_rule_template` = "'.(int)$id_cart_rule_template.'"');
        return $cart_rules;
    }

    public function copyCartRuleInfo($id_cart_rule_source, $id_cart_rule_destination)
    {
        Db::getInstance()->execute('
		INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_shop` (`id_cart_rule`, `id_shop`)
		(SELECT ' . (int) $id_cart_rule_destination . ', id_shop FROM `' . _DB_PREFIX_ . 'cart_rule_shop` WHERE `id_cart_rule` = ' . (int) $id_cart_rule_source . ')');
        Db::getInstance()->execute('
		INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_carrier` (`id_cart_rule`, `id_carrier`)
		(SELECT ' . (int) $id_cart_rule_destination . ', id_carrier FROM `' . _DB_PREFIX_ . 'cart_rule_carrier` WHERE `id_cart_rule` = ' . (int) $id_cart_rule_source . ')');
        Db::getInstance()->execute('
		INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_group` (`id_cart_rule`, `id_group`)
		(SELECT ' . (int) $id_cart_rule_destination . ', id_group FROM `' . _DB_PREFIX_ . 'cart_rule_group` WHERE `id_cart_rule` = ' . (int) $id_cart_rule_source . ')');
        Db::getInstance()->execute('
		INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_country` (`id_cart_rule`, `id_country`)
		(SELECT ' . (int) $id_cart_rule_destination . ', id_country FROM `' . _DB_PREFIX_ . 'cart_rule_country` WHERE `id_cart_rule` = ' . (int) $id_cart_rule_source . ')');
        Db::getInstance()->execute('
		INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_combination` (`id_cart_rule_1`, `id_cart_rule_2`)
		(SELECT ' . (int) $id_cart_rule_destination . ', IF(id_cart_rule_1 != ' . (int) $id_cart_rule_source . ', id_cart_rule_1, id_cart_rule_2) FROM `' . _DB_PREFIX_ . 'cart_rule_combination`
		WHERE `id_cart_rule_1` = ' . (int) $id_cart_rule_source . ' OR `id_cart_rule_2` = ' . (int) $id_cart_rule_source . ')');

        // Todo : should be changed soon, be must be copied too
        // Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'cart_rule_product_rule` WHERE `id_cart_rule` = '.(int)$this->id);
        // Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'cart_rule_product_rule_value` WHERE `id_product_rule` NOT IN (SELECT `id_product_rule` FROM `'._DB_PREFIX_.'cart_rule_product_rule`)');
        // Copy products/category filters
        $products_rules_group_source = Db::getInstance()->ExecuteS('
		SELECT id_product_rule_group,quantity FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_group`
		WHERE `id_cart_rule` = ' . (int) $id_cart_rule_source . ' ');

        foreach ($products_rules_group_source as $product_rule_group_source) {
            Db::getInstance()->execute('
			INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` (`id_cart_rule`, `quantity`)
			VALUES (' . (int) $id_cart_rule_destination . ',' . (int) $product_rule_group_source['quantity'] . ')');
            $id_product_rule_group_destination = Db::getInstance()->Insert_ID();

            $products_rules_source = Db::getInstance()->ExecuteS('
			SELECT id_product_rule,type FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule`
			WHERE `id_product_rule_group` = ' . (int) $product_rule_group_source['id_product_rule_group'] . ' ');

            foreach ($products_rules_source as $product_rule_source) {
                Db::getInstance()->execute('
				INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule` (`id_product_rule_group`, `type`)
				VALUES (' . (int) $id_product_rule_group_destination . ',"' . pSQL($product_rule_source['type']) . '")');
                $id_product_rule_destination = Db::getInstance()->Insert_ID();

                $products_rules_values_source = Db::getInstance()->ExecuteS('
				SELECT id_item FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_value`
				WHERE `id_product_rule` = ' . (int) $product_rule_source['id_product_rule'] . ' ');

                foreach ($products_rules_values_source as $product_rule_value_source) {
                    Db::getInstance()->execute('
					INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` (`id_product_rule`, `id_item`)
					VALUES (' . (int) $id_product_rule_destination . ',' . (int) $product_rule_value_source['id_item'] . ')');
                }
            }
        }
    }
}
