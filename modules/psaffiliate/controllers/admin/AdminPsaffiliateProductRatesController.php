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

class AdminPsaffiliateProductRatesController extends AdminController
{
    public $fields_list;
    protected $_defaultOrderBy = 'a.id_product';
    protected $_defaultOrderWay = 'ASC';

    public function __construct()
    {
        $this->moduleObj = Module::getInstanceByName('psaffiliate');
        $this->moduleObj->loadClasses(array('Affiliate', 'AffConf'));
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->action = 'view';

        $this->required_database = false;
        $this->table = 'aff_product_rates';
        $this->identifier = 'id_product';
        $this->lang = false;
        $this->explicitSelect = true;

        $this->allow_export = true;

        $this->context = Context::getContext();

        $this->default_form_language = $this->context->language->id;
        $this->list_no_link = true;

        $this->_use_found_rows = false;
        $this->fields_list = array(
            'id_product' => array(
                'title' => $this->l('ID'),
                'search' => true,
                'filter_type' => 'int',
                'filter_key' => 'p!id_product',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'search' => true,
            ),
            'rate_percent' => array(
                'title' => $this->l('Commission percent'),
                'align' => 'text-center',
                'search' => true,
                'callback' => 'callback_percent',
            ),
            'rate_value' => array(
                'title' => $this->l('Commission value'),
                'search' => true,
                'callback' => 'callback_value',
            ),
            'multiplier' => array(
                'title' => $this->l('Multiplier'),
                'search' => true,
                'callback' => 'callback_multi',
            ),
            'extra_commission_value' => array(
                'title' => $this->l('Extra max commission'),
                'search' => true,
                'callback' => 'callback_extra',
            ),
        );

        $this->_select = 'p.`id_product`, p.`reference`, pl.`name`, IFNULL(a.`rate_percent`, -1) `rate_percent`, IFNULL(a.`rate_value`, -1) `rate_value`, IFNULL(a.`multiplier`, 1) `multiplier`, IFNULL(a.`extra_commission_value` ,0) `extra_commission_value`';
        $this->_join = 'RIGHT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product` = a.`id_product`) LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = "'.(int)$this->context->language->id.'" AND pl.`id_shop` = "'.(int)$this->context->shop->id.'")';

        parent::__construct();

    }

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $id_product = (int)Tools::getValue('id_product');
        $exists = (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'aff_product_rates` WHERE `id_product` = "'.(int)$id_product.'"');
        if (Tools::getValue('action') == 'percent') {
            if ($id_product) {
                $value = (float)Tools::getValue('value');
                if ($exists) {
                    Db::getInstance()->update('aff_product_rates', array('rate_percent' => $value), '`id_product` = "' . (int)$id_product . '"');
                } else {
                    Db::getInstance()->insert('aff_product_rates', array('id_product' => (int)$id_product, 'rate_percent' => $value));
                }
                die(Tools::jsonEncode(array(
                    'success' => 1,
                )));
            }
        }
        if (Tools::getValue('action') == 'value') {
            if ($id_product) {
                $value = (float)Tools::getValue('value');
                if ($exists) {
                    Db::getInstance()->update('aff_product_rates', array('rate_value' => $value), '`id_product` = "' . (int)$id_product . '"');
                } else {
                    Db::getInstance()->insert('aff_product_rates', array('id_product' => (int)$id_product, 'rate_value' => $value));
                }
                die(Tools::jsonEncode(array(
                    'success' => 1,
                )));
            }
        }
        if (Tools::getValue('action') == 'multiplier') {
            if ($id_product) {
                $value = (float)Tools::getValue('value');
                if ($exists) {
                    Db::getInstance()->update('aff_product_rates', array('multiplier' => $value), '`id_product` = "' . (int)$id_product . '"');
                } else {
                    Db::getInstance()->insert('aff_product_rates', array('id_product' => (int)$id_product, 'multiplier' => $value));
                }
                die(Tools::jsonEncode(array(
                    'success' => 1,
                )));
            }
        }
        if (Tools::getValue('action') == 'extra_commissions_value') {
            if ($id_product) {
                $value = (float)Tools::getValue('value');
                if ($exists) {
                    Db::getInstance()->update('aff_product_rates', array('extra_commission_value' => $value), '`id_product` = "' . (int)$id_product . '"');
                } else {
                    Db::getInstance()->insert('aff_product_rates', array('id_product' => (int)$id_product, 'extra_commission_value' => $value));
                }
                die(Tools::jsonEncode(array(
                    'success' => 1,
                )));
            }
        }
        $this->meta_title = $this->l('Product Commission Rates');
        return parent::initContent();
    }

    public function renderList()
    {
        return parent::renderList();
    }

    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return Translate::getModuleTranslation('psaffiliate', $string, get_class($this));
    }


    public function renderView()
    {
        return parent::renderView();
    }

    public static function callback_percent($echo, $tr)
    {

        return '<div class="input-group"><input type="text" class="callback" data-action="percent" name="rates_percent['.(int)$tr['id_product'].']" data-id_product="'.(int)$tr['id_product'].'" value="'.number_format($echo, 2, '.', '').'" /><span class="input-group-addon">%</span></div>';
    }
    public static function callback_value($echo, $tr)
    {
        $currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
        $iso_code = (AffConf::getConfig('point_commissions') ? (empty(AffConf::getConfig('commissions_name', true))) ? 'Points' : AffConf::getConfig('commissions_name', true) : $currency->iso_code);
        return '<div class="input-group"><input type="text" class="callback" data-action="value" name="rates_value['.(int)$tr['id_product'].']" data-id_product="'.(int)$tr['id_product'].'" value="'.number_format($echo, 2, '.', '').'" /><span class="input-group-addon">'.$iso_code.'</span></div>';
    }
    public static function callback_multi($echo, $tr)
    {
        return '<div class="input-group"><input type="text" class="callback" data-action="multiplier" name="multiplier['.(int)$tr['id_product'].']" data-id_product="'.(int)$tr['id_product'].'" value="'.number_format($echo, 2, '.', '').'" /><span class="input-group-addon">*</span></div>';
    }
    public static function callback_extra($echo, $tr)
    {
        $currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
        $iso_code = (AffConf::getConfig('point_commissions') ? (empty(AffConf::getConfig('commissions_name', true))) ? 'Points' : AffConf::getConfig('commissions_name', true) : $currency->iso_code);
        return '<div class="input-group"><input type="text" class="callback" data-action="extra_commissions_value" name="extra_commissions_value['.(int)$tr['id_product'].']" data-id_product="'.(int)$tr['id_product'].'" value="'.number_format($echo, 2, '.', '').'" /><span class="input-group-addon">'.$iso_code.'</span></div>';
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $this->addJs(__PS_BASE_URI__.'/modules/psaffiliate/views/js/admin/productRatesController.js');
    }

}

