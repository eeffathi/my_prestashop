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

class Psaffiliate extends Module
{
    protected static $config_prefix = 'PSAFF_';
    public $secondaryControllers;
    public static $MLM_LEVELS = 10;

    const ADDONS_API = 'https://api.addons.prestashop.com';

    public function __construct()
    {
        $this->name = 'psaffiliate';
        $this->tab = 'advertising_marketing';
        $this->version = '1.8.7';
        $this->author = 'Active Design';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->need_instance = 0;
        $this->module_key = '7b2f06c363c6b53d93d78b51cf5df405';
        $this->author_address = '0xc0D7cE57752e47305707d7174B9686C0Afb229c3';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('PS Affiliate');
        $this->description = $this->l('PS Affiliate is an affiliate tracking system which allows Prestashop store owners to easily deploy a full-fledged affiliate program. It is ridiculously easy to install, configure and use, has all the features and flexibility you\'d need, and runs by itself - just deploy it and let it make you money.');

        $this->secondaryControllers = array(
            'AdminPsaffiliateAdmin' => $this->l('Dashboard'),
            'AdminPsaffiliateConfiguration' => $this->l('Configuration'),
            'AdminPsaffiliateAffiliates' => $this->l('Affiliates'),
            'AdminPsaffiliateCustomFields' => $this->l('Affiliates Custom Fields'),
            'AdminPsaffiliatePayments' => $this->l('Payments'),
            'AdminPsaffiliatePaymentMethods' => $this->l('Payment Methods'),
            'AdminPsaffiliateBanners' => $this->l('Banners'),
            'AdminPsaffiliateTexts' => $this->l('Text Ads'),
            'AdminPsaffiliateRates' => $this->l('General Commission Rates'),
            'AdminPsaffiliateCategoryRates' => $this->l('Category Commission Rates'),
            'AdminPsaffiliateProductRates' => $this->l('Product Commission Rates'),
            'AdminPsaffiliateMLM' => $this->l('Multi level marketing'),
            'AdminPsaffiliateTraffic' => $this->l('Traffic'),
            'AdminPsaffiliateSales' => $this->l('Sales'),
            'AdminPsaffiliateCampaigns' => $this->l('Campaigns'),
            'AdminPsaffiliateVouchers' => $this->l('Vouchers to share'),
            'AdminPsaffiliateStatistics' => $this->l('Statistics'),
        );
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $hookForProductPage = 'displayProductButtons';
        } else {
            $hookForProductPage = 'displayRightColumnProduct';
        }

        return parent::install() &&
            $this->addBackOfficeTabs() &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayCustomerAccount') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook($hookForProductPage) &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('displayAdminCustomers') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('actionDeleteGDPRCustomer') &&
            $this->registerHook('actionExportGDPRData') &&
            $this->registerHook('registerGDPRConsent') &&
            $this->registerHook('actionObjectCartRuleDeleteAfter') &&
            $this->registerHook('actionObjectCartRuleUpdateAfter') &&
            $this->installAjaxAdminController();
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall() && $this->deleteBackOfficeTabs();
    }


    public $valInput = array(
    );

    /*
* This function is use to reset just the disctinct inputs and buttons
*/
    public function softReset()
    {
        $empty_aff_configuration = Db::getInstance()->delete('aff_configuration');
        $insert_aff_configuration = Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."aff_configuration` (`name`, `value`) VALUES
('affiliates_require_approval', '1'),
('ask_for_website', '1'),
('days_current_summary', '30'),
('days_remember_affiliate', '3'),
('include_cart_rules', '1'),
('include_shipping_tax', '0'),
('include_tax_rules', '1'),
('cat_prod_commission_bonus', '0'),
('general_rate_value_per_product', '0'),
('minimum_payment_amount', '100'),
('new_customers_affiliates_directly', '0'),
('order_states_approve', '[\"5\",\"4\"]'),
('order_states_cancel', '[\"6\"]'),
('textarea_at_registration', '1'),
('textarea_at_registration_required', '1'),
('enable_affiliates_details', '0'),
('enable_terms_at_signup', '1'),
('enable_voucher_payments', '0'),
('vouchers_for_affiliates_only', '0'),
('vouchers_partial_use', '1'),
('vouchers_exchange_rate', '1'),
('vouchers_always_approved', '0'),
('override_previous_affiliate', '0'),
('affiliate_id_parameter', 'aff'),
('affiliate_link_type', '0'),
('affiliate_year_prefix_parameter', 'y'),
('enable_invoices', '0'),
('first_order_multiplier', '1'),
('commissions_for_life', '0'),
('override_commissions_for_life', '0'),
('commission_for_life_multiplier', '1'),
('groups_allowed', ''),
('commissions_for_life_at_registration', '0'),
('multiply_with_category', '0');");
        $empty_aff_commission = Db::getInstance()->delete('aff_commission');
        $types = array(
            'click' => 0.5,
            'unique_click' => 1,
            'sale' => 0,
            'sale_percent' => '15',
            'max_commission' => '50',
        );
        $insert_aff_commission = true;
        foreach ($types as $type => $value) {
            $insert_aff_commission &= Db::getInstance()->insert('aff_commission', array(
                'id_affiliate' => 0,
                'date' => date('Y-m-d H:i:s'),
                'type' => $type,
                'value' => $value,
            ));
        }

        $reset_aff_category_rates = Db::getInstance()->update('aff_category_rates', array('rate_value' => -1, 'rate_percent' => -1, 'multiplier' => 1));
        $reset_aff_product_rates = Db::getInstance()->update('aff_product_rates', array('rate_value' => -1, 'rate_percent' => -1, 'multiplier' => 1));
    }



    public function addBackOfficeTabs()
    {
        if (!Tab::getIdFromClassName('PsaffiliateAdmin')) {
            $tab = new Tab;

            $tab->class_name = "PsaffiliateAdmin";
            $tab->id_parent = 0;
            $tab->module = $this->name;
            $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->displayName;
            if (!$tab->add()) {
                return false;
            }
        }

        $primaryTabId = Tab::getIdFromClassName('PsaffiliateAdmin');
        if ($primaryTabId) {
            foreach ($this->secondaryControllers as $class_name => $name) {
                if (Tab::getIdFromClassName($class_name)) {
                    continue;
                }
                $tab = new Tab;

                $tab->class_name = $class_name;
                $tab->id_parent = $primaryTabId;
                $tab->module = $this->name;
                $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $name;
                if (!$tab->add()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function deleteBackOfficeTabs()
    {
        $tab = new Tab(Tab::getIdFromClassName('PsaffiliateAdmin'));
        if (!$tab->delete()) {
            return false;
        }

        foreach (array_keys($this->secondaryControllers) as $class_name) {
            $tab = new Tab(Tab::getIdFromClassName($class_name));
            $tab->delete();
        }

        return true;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPsaffiliateModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfiguration(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $validateFields = $this->validateFields();
        if (!$validateFields) {
            $form_values = $this->getConfiguration();
            $db = Db::getInstance();
            $data = array();
            $i = 0;
            foreach (array_keys($form_values) as $key) {
                $data[$i]['name'] = pSQL($key);
                $data[$i]['value'] = pSQL(Tools::getValue($key, null));
                $i++;
            }

            return (bool)$db->insert('aff_configuration', $data, true, false, Db::REPLACE);
        } else {
            return $validateFields;
        }
    }

    public function validateFields()
    {
        $validate = true;
        $errors = array();
        foreach ($this->getConfigForm() as $configForm) {
            foreach ($configForm['input'] as $input) {
                $name = $input['name'];
                $label = $input['label'];
                if (isset($input['validate'])) {
                    $validate = $input['validate'];
                    if (!Validate::$validate(pSQL(Tools::getValue($name)))) {
                        $errors[$name] = $this->generateError($label, $validate);
                    }
                }
            }
        }

        return implode("<br />", $errors);
    }

    public function generateError($label = false, $validate = false)
    {
        if ($label && $validate) {
            switch ($validate) {
                case 'isFloat':
                    return sprintf($this->l('The field "%s" has to be a float value, separated by dot (".")'), $label);
                default:
                    return sprintf($this->l('The field "%1$s" is not validating the rule "%2$s"'), $label, $validate);
            }
        } else {
            return $this->l('Unknown error');
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addJS($this->getPathUri().'views/js/productRatesController.js');

        if ($this->showDiscover()) {
            $this->context->controller->addCSS($this->getPathUri().'views/css/discover.css');
            $this->context->controller->addJS($this->getPathUri().'views/js/discover.js');
        }
    }

    public function hookDisplayHeader()
    {
        $this->context->smarty->assign('id_module', $this->id);
        $this->startTracking();

        if (isset($this->context->controller->module->name) && $this->context->controller->module->name == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/front.js');
            $this->context->controller->addJS($this->_path.'views/js/clipboard.min.js');
            $this->context->controller->addCSS($this->_path.'views/css/front.css');
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                $this->context->controller->addCSS($this->_path.'views/css/front_ps17.css');
            }

            if (Tools::version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->context->controller->addCSS($this->_path.'views/css/bootstrap.modified.min.css');
                $this->context->controller->addJS($this->_path.'views/js/bootstrap.min.js');
                $this->context->controller->addJS($this->_path.'views/js/jquery.ui.tooltip.min.js');
            }
        }
        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'product') {
            $this->context->controller->addJS($this->_path.'views/js/clipboard.min.js');
            $this->context->controller->addCSS($this->_path.'views/css/product.css');
            $this->context->controller->addJS($this->_path.'views/js/product.js');
        }
    }

    public function hookDisplayCustomerAccount()
    {
        $is_affiliate = $this->isAffiliate();
        $this->context->smarty->assign('isAffiliate', $is_affiliate);

        if (!$is_affiliate) {
            $is_group_allowed = self::isGroupAllowed($this->context->customer->id_default_group);
            if (!$is_group_allowed) {
                return false;
            }
        }

        if (Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
            return $this->display(__FILE__, 'views/templates/front/my-account.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/front/ps17/my-account.tpl');
        }
    }

    public function hookActionValidateOrder($params)
    {
        // Exception for Amazon Order import module
        if (get_class($params['cart']) == 'AmazonCart') {
            return;
        }
        $this->loadClasses('AffConf');
        $checkSessionAffiliate = true;
        $has_affiliate = false;
        $is_lifetime_affiliate = false;
        if (AffConf::getConfig('commissions_for_life') && $id_affiliate_lifetime = $this->customerHasLifetimeAffiliate((int)$params['cart']->id_customer)) {
            $id_affiliate = $id_affiliate_lifetime;
            $has_affiliate = true;
            $is_lifetime_affiliate = true;
            $id_campaign = 0;
            if (!AffConf::getConfig('override_commissions_for_life')) {
                $checkSessionAffiliate = false;
            }
        }
        if ($checkSessionAffiliate && $this->hasSessionAffiliate()) {
            $id_affiliate = (int)$this->context->cookie->id_session_affiliate;
            $has_affiliate = true;
            $id_campaign = (int)$this->context->cookie->id_session_campaign;
        }
        if(!$has_affiliate) {
            $cart_data = self::getCartAffiliate($params['cart']->id);
            if($cart_data) {
                $id_affiliate = (int)$cart_data['id_affiliate'];
                $id_campaign = (int)$cart_data['id_campaign'];
                $has_affiliate = true;
            }
        }
        if ((!$has_affiliate || AffConf::getConfig('override_vts_affiliate')) && AffConf::getConfig('vouchers_tracking')) {
            $this->loadClasses('VoucherToShare');
            $cart_rules = VoucherToShare::getCartRulesByIdOrder($params['order']->id);
            if ($cart_rules) {
                foreach ($cart_rules as $id_cart_rule) {
                    $id_affiliate_by_cart_rule = VoucherToShare::getIdAffiliateByIdCartRule($id_cart_rule);
                    if ($id_affiliate_by_cart_rule) {
                        $has_affiliate = true;
                        $id_affiliate = $id_affiliate_by_cart_rule;
                        $id_campaign = 0;
                        break;
                    }
                }
            }
        }
        if ($has_affiliate && AffConf::getConfig('commissions_only_new_customers')) {
            $id_cart = $params['order']->id_cart;
            /* We check by cart id, not order id, because orders can be splitted */
            $has_other_orders = self::customerHasOtherOrdersByCart($params['order']->id_customer, $id_cart);
            if ($has_other_orders) {
                if (AffConf::getConfig('commissions_for_life') && $id_affiliate_lifetime = $this->customerHasLifetimeAffiliate((int)$params['order']->id_customer)) {
                    if ($id_affiliate_lifetime && $id_affiliate_lifetime != $id_affiliate) {
                        return;
                    }
                }
            }
        }
        if ($has_affiliate) {
            $this->loadClasses(array('Sale', 'Campaign'));
            $sale = new Sale;
            $sale->id_affiliate = $id_affiliate;
            $sale->id_campaign = $id_campaign;
            $sale->id_order = (int)$params['order']->id;
            $sale->approved = 0;
            $sale->commission = $this->calculateCommission($params['order'], $id_affiliate, $is_lifetime_affiliate);
            $sale->date = date('Y-m-d H:i:s');
            if ($sale->add() && $id_campaign) {
                Campaign::setLastActive($id_campaign);
            }
            self::addMLMSales($sale, $id_affiliate, 1);
        }
        /* Associate the affiliate as a lifetime one for this customer */
        if ($has_affiliate && AffConf::getConfig('commissions_for_life') && !$this->customerHasLifetimeAffiliate((int)$params['order']->id_customer)) {
            $this->associateCustomerToAffiliate((int)$params['order']->id_customer, $id_affiliate);
        }
    }

    public static function addMLMSales($sale, $id_affiliate_original, $level = 1)
    {
        if ($level <= self::getMLM_LEVELS()) {
            $id_affiliate = $sale->id_affiliate;
            $commission_percent_to_give = (float)AffConf::getConfig('mlm_commission_'.$level);
            if ($commission_percent_to_give) {
                $id_parent_affiliate = Db::getInstance()->getValue('SELECT `id_parent_affiliate` FROM `' . _DB_PREFIX_ . 'aff_affiliates` WHERE `id_affiliate` = "' . (int)$id_affiliate . '"');
                if ($id_parent_affiliate) {
                    $sale_new = $sale;
                    $sale_new->id = 0;
                    $sale_new->id_sale = 0;
                    $sale_new->id_affiliate = $id_parent_affiliate;
                    $sale_new->id_affiliate_origin = $id_affiliate_original;
                    $sale_new->commission = $sale->commission * ($commission_percent_to_give / 100);
                    $sale_new->add();
                    self::addMLMSales($sale_new, $id_affiliate_original, $level + 1);
                }
            }
        }
    }

    public function hookActionOrderStatusUpdate($params)
    {
        if ($params && isset($params['newOrderStatus']) && isset($params['id_order'])) {
            $id_status = $params['newOrderStatus']->id;
            $id_order = $params['id_order'];
            $this->loadClasses('AffConf');
            $affConf = new AffConf;
            $status_approve_commission = $affConf->getConfig('order_states_approve[]');
            $status_cancel_commission = $affConf->getConfig('order_states_cancel[]');
            if (in_array($id_status, $status_approve_commission)) {
                if (Db::getInstance()->getValue("SELECT COUNT(*) FROM `"._DB_PREFIX_."aff_sales` WHERE `id_order`='".(int)$id_order."'")) {
                    Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."aff_sales` SET `approved` = '1' WHERE `id_order`='".(int)$id_order."';");
                }
            } elseif (in_array($id_status, $status_cancel_commission)) {
                if (Db::getInstance()->getValue("SELECT COUNT(*) FROM `"._DB_PREFIX_."aff_sales` WHERE `id_order`='".(int)$id_order."'")) {
                    Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."aff_sales` SET `approved` = '0' WHERE `id_order`='".(int)$id_order."';");
                }
            }
        }
    }

    public function hookActionCustomerAccountAdd($params)
    {
        $this->loadClasses(array('Affiliate', 'AffConf'));
        if (AffConf::getConfig('new_customers_affiliates_directly') && isset($params['newCustomer']) && Validate::isLoadedObject($params['newCustomer'])) {
            $newCustomer = $params['newCustomer'];
            $affiliate = new Affiliate;
            $affiliate->id_customer = $newCustomer->id;
            $affiliate->active = (int)!AffConf::getConfig('affiliates_require_approval');
            $affiliate->has_been_reviewed = (int)!AffConf::getConfig('affiliates_require_approval');
            $affiliate->add();
        }

        if (AffConf::getConfig('commissions_for_life') && AffConf::getConfig('commissions_for_life_at_registration') && self::hasSessionAffiliate()) {
            $id_customer = $params['newCustomer']->id;
            $id_affiliate = (int)$this->context->cookie->id_session_affiliate;

            self::associateCustomerToAffiliate($id_customer, $id_affiliate);
        }
    }

    public function hookDisplayProductButtons($params)
    {
        if (!empty($params['product']) && $this->isAffiliate()) {
            if (is_object($params['product'])) {
                $id_product = (int)$params['product']->id;
            } else {
                $id_product = (int)$params['product']['id_product'];
            }
            $id_affiliate = $this->getAffiliateId();
            $this->context->smarty->assign(array(
                'product_affiliate_link' => $this->getAffiliateLink($id_affiliate, $id_product),
                'product_commision' => $this->formatProductRates($this->getRatesForProduct($id_product, $id_affiliate)),
            ));
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                return $this->display(__FILE__, 'views/templates/front/ps17/product_buttons.tpl');
            } else {
                return $this->display(__FILE__, 'views/templates/front/product_buttons.tpl');
            }
        }
    }

    public function hookDisplayRightColumnProduct($params)
    {
        if (Tools::getValue('id_product') && $this->isAffiliate()) {
            $id_product = (int)Tools::getValue('id_product');
            $id_affiliate = $this->getAffiliateId();
            $this->context->smarty->assign(array(
                'product_affiliate_link' => $this->getAffiliateLink($id_affiliate, $id_product),
                'product_commision' => $this->formatProductRates($this->getRatesForProduct($id_product, $id_affiliate)),
            ));
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                return $this->display(__FILE__, 'views/templates/front/ps17/product_buttons.tpl');
            } else {
                return $this->display(__FILE__, 'views/templates/front/product_buttons.tpl');
            }
        }
    }

    public function hookDisplayAdminCustomers($params)
    {
        $id_customer = (int)$params['id_customer'];
        $lifetime_affiliate_id = self::customerHasLifetimeAffiliate($id_customer);
        $lifetime_affiliate_name = "";
        if ($lifetime_affiliate_id) {
            $lifetime_affiliate_name = self::getAffiliateName($lifetime_affiliate_id);
        }
        $commissions_generated = self::getCustomerCommissionsGenerated($id_customer, 100);
        $this->context->smarty->assign(array(
            'id_affiliate' => self::getAffiliateId($id_customer),
            'lifetime_affiliate_id' => $lifetime_affiliate_id,
            'lifetime_affiliate_name' => $lifetime_affiliate_name,
            'commissions_generated' => $commissions_generated,
        ));

        return $this->display(__FILE__, 'views/templates/admin/customer_view.tpl');
    }

    public function hookActionObjectCartRuleUpdateAfter($params)
    {
        $object = $params['object'];
        /* If a cart rule gets enabled or disabled, we should do the same with vouchers created with that template */
        $this->loadClasses('VoucherToShare');
        $cart_rules_to_update = VoucherToShare::getCartRulesUsingTemplate($object->id);
        if ($cart_rules_to_update) {
            foreach ($cart_rules_to_update as $row) {
                $cart_rule = new CartRule($row['id_cart_rule']);
                $cart_rule->active = $object->active;
                $cart_rule->save();
            }
        }
    }

    public function hookActionObjectCartRuleDeleteAfter($params)
    {
        $object = $params['object'];
        /* If a cart rule gets enabled or disabled, we should do the same with vouchers created with that template */
        $this->loadClasses('VoucherToShare');
        $cart_rules_to_update = VoucherToShare::getCartRulesUsingTemplate($object->id);
        if ($cart_rules_to_update) {
            foreach ($cart_rules_to_update as $row) {
                $vts = new VoucherToShare($row['id_vts']);
                $vts->delete();
                $cart_rule = new CartRule($row['id_cart_rule']);
                $cart_rule->delete();
            }
        }
    }

    public function calculateCommission($order, $id_affiliate = false, $is_lifetime_affiliate = false)
    {
        if ($order && $id_affiliate) {
            if (is_numeric($order)) {
                $order = new Order($order);
            }
            $this->loadClasses(array('Affiliate', 'AffConf'));
            $aff = new Affiliate($id_affiliate);
            if (Validate::isLoadedObject($aff)) {
                $commission_for_products = $this->getCommissionForProducts($order->getProducts(), $aff->id, $order->id_currency);
                $with_taxes = AffConf::getConfig('include_tax_rules');
                //$max_commission_aff = AffConf::getConfig('max_commission_aff');
                $max_commission_aff = $aff->per_max_commission;
                $total = 0;
                if (AffConf::getConfig('include_cart_rules')) {
                    if ($with_taxes) {
                        $total -= Tools::convertPrice($order->total_discounts_tax_incl, $order->id_currency, false);
                    } else {
                        $total -= Tools::convertPrice($order->total_discounts_tax_excl, $order->id_currency, false);
                    }
                }
                if (AffConf::getConfig('include_shipping_tax')) {
                    if ($with_taxes) {
                        $total += Tools::convertPrice($order->total_shipping_tax_incl, $order->id_currency, false);
                    } else {
                        $total += Tools::convertPrice($order->total_shipping_tax_excl, $order->id_currency, false);
                    }
                }
                $per_sale_value = $aff->per_sale;
                if (AffConf::getConfig('general_rate_value_per_product')) {
                    $per_sale_value = 0;
                }
                $calculatedCommission = $per_sale_value + ($total * ($aff->per_sale_percent / 100)) + $commission_for_products;

                $id_customer = (int)$order->id_customer;
                $first_order_multiplier = (float)AffConf::getConfig('first_order_multiplier');
                /* If customer makes his first order, multiply the commission of the affiliate */
                if ($id_customer && $first_order_multiplier != 1 && !self::customerHasOtherOrdersExcept(
                        $id_customer,
                        $order->id
                    )
                ) {
                    $calculatedCommission *= $first_order_multiplier;
                }
                if ($is_lifetime_affiliate) {
                    $commission_for_life_multiplier = (float)AffConf::getConfig('commission_for_life_multiplier');
                    if ($commission_for_life_multiplier) {
                        $calculatedCommission *= $commission_for_life_multiplier;
                    }
                }

                $decimals = (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION');
                $calculatedCommission = Tools::ps_round($calculatedCommission, $decimals);

                if ($max_commission_aff > 0 && $calculatedCommission > $max_commission_aff) {
                    $calculatedCommission = $max_commission_aff;
                }

                foreach ($order->getProducts() as $prods) {
                    $extra_commission = Db::getInstance()->getValue('SELECT `extra_commission_value` FROM `'._DB_PREFIX_.'aff_product_rates` WHERE `id_product` = "'.$prods['id_product'].'"');
                    if ($extra_commission > 0) {
                        $calculatedCommission += $extra_commission;
                    }
                }

                return $calculatedCommission;
            }
        }

        return 0;
    }

    public function getCommissionForProducts($products, $id_affiliate = 0, $id_currency = null)
    {
        $return = 0;
        foreach ($products as $product) {
            $return += $this->getCommissionForProduct($product, $id_affiliate, $id_currency);
        }

        return $return;
    }

    public function getCommissionDataForProduct($id_product, $id_affiliate = 0)
    {
        $rates = $this->getRatesForProduct($id_product, $id_affiliate);

        return $rates;
    }

    public function getCommissionForProduct($product, $id_affiliate = 0, $id_currency = null)
    {
        if (is_null($id_currency)) {
            $id_currency = $this->context->currency->id;
        }
        $return = 0;
        $id_product = (int)$product['product_id'];
        $rates = $this->getCommissionDataForProduct($id_product, $id_affiliate);
        $price = (float)$product['total_price_tax_excl'];
        $with_taxes = AffConf::getConfig('include_tax_rules');
        if ($with_taxes) {
            $price = (float)$product['total_price_tax_incl'];
        }
        if (AffConf::getConfig('Commission_mode') == '2') {
            $wholesale = self::getWholesalePrice($id_product);
            $price -= $wholesale;
        }
        if ($rates) {
            if (AffConf::getConfig('Commission_mode') == '3') {
                $wholesale = self::getWholesalePrice($id_product);
                $return = $price - $wholesale;
            } elseif ((float)$rates['rate_percent'] > 0) {
                if ($with_taxes) {
                    $return += Tools::convertPrice((float)$price, $id_currency, false) * ((float)$rates['rate_percent'] / 100);
                } else {
                    $return += Tools::convertPrice((float)$price, $id_currency, false) * ((float)$rates['rate_percent'] / 100);
                }
            }
            if ((float)$rates['rate_value'] > 0) {
                $return += (float)$rates['rate_value'] * (float)$product['product_quantity'];
            }
        }
        if (isset($rates['multiplier'])) {
            $return *= $rates['multiplier'];
        }

        return $return;
    }

    public function getRatesForProduct($id_product, $id_affiliate = 0)
    {
        $this->loadClasses('AffConf');
        $rates_product = array();
        $product_rates_array = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'aff_product_rates` WHERE `id_product` = "'.(int)$id_product.'"');
        if (!$product_rates_array) {
            $product_rates_array = array(
                'id_product' => $id_product,
                'rate_percent' => -1,
                'rate_value' => -1,
                'multiplier' => 1,
            );
        }
        $rates_product = $product_rates_array;
        if ($product_rates_array['rate_percent'] == -1 || $product_rates_array['rate_value'] == -1 || $product_rates_array['multiplier'] == 1) {
            $category_rates_array = $this->getRatesForCategory($this->getCategoryOfProduct($id_product));
        }
        if ($rates_product['rate_percent'] == -1) {
            $rates_product['rate_percent'] = $category_rates_array['rate_percent'];
        }
        if ($rates_product['rate_value'] == -1) {
            $rates_product['rate_value'] = $category_rates_array['rate_value'];
        }
        if (AffConf::getConfig('multiply_with_category')) {
            $rates_product['multiplier'] *= $category_rates_array['multiplier'];
        } elseif ($rates_product['multiplier'] == 1) {
            $rates_product['multiplier'] = $category_rates_array['multiplier'];
        }
        if ($rates_product['rate_percent'] == -1 || $rates_product['rate_value'] == -1) {
            $this->loadClasses('Affiliate');
            $aff = new Affiliate($id_affiliate);
            if ($rates_product['rate_percent'] == -1) {
                $rates_product['rate_percent'] = $aff->per_sale_percent;
            }
            if ($rates_product['rate_value'] == -1) {
                $general_rate_value_per_product = AffConf::getConfig('general_rate_value_per_product');
                if ($general_rate_value_per_product) {
                    $rates_product['rate_value'] = $aff->per_sale;
                } else {
                    $rates_product['rate_value'] = 0;
                }
            }
        }

        return $rates_product;
    }

    public function formatProductRates($rates)
    {
        $rates['rate_value'] = (float)$rates['rate_value'];
        $rates['rate_percent'] = (float)$rates['rate_percent'];
        if (!isset($rates['multiplier'])) {
            $rates['multiplier'] = 1;
        }

        // If only rate value...
        if ($rates['rate_value'] && !$rates['rate_percent']) {
            return self::displayPriceOverride($rates['rate_value'] * $rates['multiplier']);
        }

        if (!isset($rates['id_product'])) {
            return self::displayPriceOverride(0.00);
        }

        $this->loadClasses('AffConf');

        $taxable = (bool)AffConf::getConfig('include_tax_rules');
        $spo = null;

        $product_price = Product::getPriceStatic(
            (int)$rates['id_product'],
            $taxable, /* $usetax */
            null,     /* $id_product_attribute */
            6,        /* $decimals */
            null,     /* $divisor */
            false,    /* $only_reduc */
            true,     /* $usereduc */
            1,        /* $quantity */
            false,    /* $force_associated_tax */
            null,     /* $id_customer */
            null,     /* $id_cart */
            null,     /* $id_address */
            $spo,     /* &$specific_price_output */
            $taxable, /* $with_ecotax */
            false     /* $use_group_reduction */
        );

        /**
         * 1. In functie de pretul produsului (exact cum e acum)
         * 2. Comisionul sa fie calculat in functie de diferenta dintre Wholesale price si pretul produsului (adica in functie de profitul vanzatorului)
         * 3. Comisionul sa fie egal cu diferenta dintre Wholesale price si pretul produsului
         */

        $wholesale = self::getWholesalePrice((int)$rates['id_product']);

        if (AffConf::getConfig('Commission_mode') == '2') {
            $product_price -= $wholesale;
        }

        if (AffConf::getConfig('Commission_mode') == '3') {
            return self::displayPriceOverride(($rates['rate_value'] + (($rates['rate_percent'] / 100) * $wholesale)) * $rates['multiplier']);
        }

        // if only rate percent...
        if ($rates['rate_percent'] && !$rates['rate_value']) {
            return self::displayPriceOverride((($rates['rate_percent'] / 100) * $product_price) * $rates['multiplier']);
        }

        // if both percent and value are set...
        return self::displayPriceOverride(($rates['rate_value'] + (($rates['rate_percent'] / 100) * $product_price)) * $rates['multiplier']);

    }

    public static function getWholesalePrice($pid){
        return (float)Db::getInstance()->getValue('SELECT `wholesale_price` FROM `'._DB_PREFIX_.'product_shop` WHERE `id_product` = "'.(int)$pid.'" AND `id_shop` = "'.(int)Context::getContext()->shop->id.'"');
    }

    public function getRatesForCategory($id_category)
    {
        $rates_array = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'aff_category_rates` WHERE `id_category` = "'.(int)$id_category.'"');
        if (!$rates_array) {
            $rates_array = array(
                'rate_percent' => -1,
                'rate_value' => -1,
                'multiplier' => 1,
            );
        }

        return $rates_array;
    }

    public function getCategoryOfProduct($id_product)
    {
        return (int)Db::getInstance()->getValue('SELECT `id_category_default` FROM `'._DB_PREFIX_.'product` WHERE `id_product` = "'.(int)$id_product.'"');
    }

    public function toggleStatus()
    {
        $id_affiliate = (int)Tools::getValue('id_affiliate');
        if ($id_affiliate) {
            $sql = Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."aff_affiliates` SET `active` = (CASE WHEN `active`='1' THEN '0' WHEN `active`='0' THEN '1' END) WHERE `id_affiliate`='".(int)$id_affiliate."' LIMIT 1;");

            return $sql;
        }

        return false;
    }

    public function setFieldsToUpdate()
    {
        if (Tools::getValue('controller')) {
            $controller = Tools::getValue('controller')."Controller";
            $controller = new $controller();

            return $controller->setFieldsToUpdate();
        }

        return false;
    }

    public function update()
    {
        if (Tools::getValue('controller')) {
            $controller = Tools::getValue('controller')."Controller";
            $controller = new $controller();

            return $controller->update();
        }
    }

    public function delete()
    {
        if (Tools::getValue('controller')) {
            $controller = Tools::getValue('controller')."Controller";
            $controller = new $controller();

            return $controller->delete();
        }

        return false;
    }

    public static function loadClasses($classes = array("Affiliate"))
    {
        if (!is_array($classes)) {
            $classes = array($classes);
        }
        foreach ($classes as $class) {
            if (file_exists(_PS_MODULE_DIR_."psaffiliate/classes/".$class.".php")) {
                require_once(_PS_MODULE_DIR_."psaffiliate/classes/".$class.".php");
            }
        }
    }

    public function isAffiliate($id_customer = false)
    {
        if (!$id_customer) {
            if (!$this->context->customer->isLogged()) {
                return false;
            } else {
                $id_customer = $this->context->customer->id;
            }
        }
        $sql = "SELECT `id_affiliate` FROM `"._DB_PREFIX_."aff_affiliates` WHERE `id_customer`='".(int)$id_customer."'";
        $sql = Db::getInstance()->getValue($sql);

        return (bool)$sql;
    }

    public static function getAffiliateId($id_customer = false)
    {
        if (!$id_customer) {
            $context = Context::getContext();
            if (!$context->customer->isLogged()) {
                return false;
            } else {
                $id_customer = $context->customer->id;
            }
        }
        $sql = "SELECT `id_affiliate` FROM `"._DB_PREFIX_."aff_affiliates` WHERE `id_customer`='".(int)$id_customer."'";
        $sql = Db::getInstance()->getValue($sql);

        return (int)$sql;
    }

    public static function getCustomerId($id_affiliate = false)
    {
        if (!$id_affiliate) {
            $context = Context::getContext();
            if (!$context->customer->id) {
                return false;
            } else {
                return $context->customer->id;
            }
        } else {
            Psaffiliate::loadClasses('Affiliate');
            $affiliate = new Affiliate($id_affiliate);
            if (Validate::isLoadedObject($affiliate)) {
                return $affiliate->id_customer;
            }
        }

        return false;
    }

    public static function hasSessionAffiliate()
    {
        $context = Context::getContext();
        if (isset($context->cookie->id_session_affiliate) && (int)$context->cookie->id_session_affiliate) {
            $id_session_affiliate = $context->cookie->id_session_affiliate;
            $id_customer_affiliate = Psaffiliate::getCustomerId($id_session_affiliate);
            $id_customer = PsAffiliate::getCustomerId();

            if ((int)$id_customer != (int)$id_customer_affiliate && (int)$id_customer_affiliate) {
                return true;
            }
        }

        return false;
    }

    public static function getAffiliateLink($id_affiliate = false, $id_product = false, $id_campaign = false)
    {
        self::loadClasses(array('AffConf'));
        if (!$id_affiliate) {
            $id_affiliate = self::getAffiliateId();
        }
        if ($id_affiliate) {
            $link_type = AffConf::getConfig('affiliate_link_type');
            if ($link_type == 1) {
                $affiliate_register_year = self::getAffiliateRegisterYear($id_affiliate);
                $affiliate_register_year = Tools::substr($affiliate_register_year, -2);
                $year_prefix = AffConf::getConfig('affiliate_year_prefix_parameter');
                if (!$year_prefix) {
                    $year_prefix = 'y';
                }
                $id_affiliate = $year_prefix.$affiliate_register_year.$id_affiliate;
            }
            $link_param = AffConf::getConfig('affiliate_id_parameter');
            $request = array($link_param => $id_affiliate);
            if ($id_campaign) {
                $request['id_campaign'] = $id_campaign;
            }
            $link = new Link;
            $context = Context::getContext();
            if (!$id_product) {
                $url = $link->getPageLink('index', null, $context->language->id, $request);
            } else {
                if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                    $url = $link->getProductLink(
                        $id_product,
                        null,
                        null,
                        null,
                        null,
                        null,
                        0,
                        false,
                        false,
                        false,
                        $request
                    );
                } else {
                    $url = $link->getProductLink($id_product);
                    if (strpos($url, '?') === false) {
                        $url .= '?'.$link_param.'='.$id_affiliate;
                    } else {
                        $url .= '&'.$link_param.'='.$id_affiliate;
                    }
                }
            }

            return $url;
        }

        return false;
    }

    public static function getAffiliateName($id_affiliate)
    {
        return Db::getInstance()->getValue('SELECT CONCAT(`firstname`, " ", `lastname`) FROM `'._DB_PREFIX_.'aff_affiliates` WHERE `id_affiliate` = "'.(int)$id_affiliate.'"');
    }

    public function startTracking()
    {
        $this->loadClasses('Tracking');
        $tracking = new Tracking;
        $tracking->startTracking();
    }

    public function hasTexts($active = false)
    {
        $this->loadClasses('Text');

        return Text::hasTexts($active);
    }

    public function hasBanners($active = false)
    {
        $this->loadClasses('Banner');

        return Banner::hasBanners($active);
    }

    public function hasVouchersToShare($active = false)
    {
        $this->loadClasses('VoucherToShare');

        return VoucherToShare::hasVoucherTemplates($active);
    }

    public function getAffiliatesList()
    {
        $sql = 'SELECT af.`id_affiliate` as `id`, CONCAT("#", af.`id_affiliate`, " - ", IF(af.`id_customer` <> 0, CONCAT(c.`firstname`, " ", c.`lastname`), CONCAT(af.`firstname`, " ", af.`lastname`))) as `value` FROM `'._DB_PREFIX_.'aff_affiliates` af LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = af.`id_customer`)';
        $result = Db::getInstance()->executeS($sql);
        $array = array();
        foreach ($result as $val) {
            $array[$val['id']] = $val['value'];
        }

        return $array;
    }

    public function getCampaignsList($id_affiliate = false, $for_edit_select = false)
    {
        $sql = 'SELECT c.`id_campaign` as `id`, CONCAT("#", c.`id_campaign`, " - ", c.`name`) as `value` FROM `'._DB_PREFIX_.'aff_campaigns` c';
        if ($id_affiliate) {
            $sql .= " WHERE c.`id_affiliate`='".(int)$id_affiliate."'";
        }
        $result = Db::getInstance()->executeS($sql);
        $array = array();
        if (!$for_edit_select) {
            foreach ($result as $val) {
                $array[$val['id']] = $val['value'];
            }

            return $array;
        }
        $result = array_merge(array(array('id' => '0', 'value' => '--')), $result);

        return $result;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getPathDir()
    {
        return dirname(__FILE__);
    }

    private function makeRequestToAddons($data = array())
    {
        $data = array_merge(
            array(
                'version' => _PS_VERSION_,
                'iso_lang' => Tools::strtolower(Language::getIsoById((int)$this->context->cookie->id_lang)),
                'iso_code' => Tools::strtolower(Country::getIsoById((int)Configuration::get('PS_COUNTRY_DEFAULT'))),
                'module_key' => $this->module_key,
                'method' => 'contributor',
                'action' => 'all_products',
            ),
            $data
        );

        $postData = http_build_query($data);
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $postData,
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'timeout' => 15,
            ),
        ));

        $jsonResponse = Tools::file_get_contents(static::ADDONS_API, false, $context);
        $response = Tools::jsonDecode($jsonResponse, true);

        if (empty($jsonResponse) || empty($response)) {
            return false;
        }

        return $response;
    }

    private function getAddonsModules()
    {
        $modules = json_decode(Configuration::get(static::$config_prefix.'ADDONS_MODULES'), true);
        $modulesLastUpdate = Configuration::get(static::$config_prefix.'ADDONS_MODULES_LAST_UPDATE');

        if ($modules && $modulesLastUpdate && strtotime('+2 day', $modulesLastUpdate) > time()) {
            return $modules;
        }

        $response = $this->makeRequestToAddons();
        $freshModules = $response['products'];
        if (!$response || empty($freshModules)) {
            return array();
        }

        $newModules = array();
        foreach ($freshModules as $module) {
            $newModules[] = array(
                'id' => $module['id'],
                'name' => $module['name'],
                'url' => $module['url'],
                'img' => $module['img'],
                'price' => $module['price'],
                'displayName' => $module['displayName'],
                'description' => $module['description'],
                'compatibility' => $module['compatibility'],
                'version' => $module['version'],
            );
        }

        Configuration::updateValue(static::$config_prefix.'ADDONS_MODULES', Tools::jsonEncode($newModules));
        Configuration::updateValue(static::$config_prefix.'ADDONS_MODULES_LAST_UPDATE', time());

        return $newModules;
    }

    private function showDiscover()
    {
        return $this->context->controller instanceof AdminPsaffiliateAdminController;
    }

    public function getDiscoverTpl()
    {
        $modules = $this->getAddonsModules();

        if (empty($modules)) {
            $this->context->smarty->assign('addons_modules', $modules);

            return $this->getLocalPath().'views/templates/admin/discover';
        }

        $defaultCurrencyIso = Tools::strtolower((new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')))->iso_code);

        $currencyIsos = array('eur', 'usd', 'gbp');
        $currencyIso = in_array($defaultCurrencyIso, $currencyIsos) ? $defaultCurrencyIso : 'eur';

        array_walk($modules, function (&$module) use ($currencyIso) {
            $price = array_change_key_case($module['price'], CASE_LOWER);
            $priceAmount = $price[$currencyIso];

            $formatted = '';
            if ($currencyIso == 'eur') {
                $formatted = number_format($priceAmount, 2, ',', '.').' €';
            } elseif ($currencyIso == 'usd') {
                $formatted = '$'.number_format($priceAmount, 2, '.', ',');
            } elseif ($currencyIso == 'gbp') {
                $formatted = '£ '.number_format($priceAmount, 2, '.', ',');
            }

            $module['price_formatted'] = $formatted;
        });

        shuffle($modules);

        $this->context->smarty->assign('addons_modules', $modules);

        return $this->getLocalPath().'views/templates/admin/discover';
    }

    public static function getAffiliateRegisterYear($id_affiliate)
    {
        return Db::getInstance()->getValue('SELECT EXTRACT(YEAR FROM date_created) FROM `'._DB_PREFIX_.'aff_affiliates` WHERE id_affiliate="'.(int)$id_affiliate.'"');
    }

    public static function customerHasOtherOrdersExcept($id_customer, $id_order)
    {
        return (bool)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'orders` WHERE `id_customer` = "'.(int)$id_customer.'" AND `id_order` != "'.(int)$id_order.'"');
    }

    public static function customerHasLifetimeAffiliate($id_customer)
    {
        return (int)Db::getInstance()->getValue('SELECT `id_affiliate` FROM `'._DB_PREFIX_.'aff_customers` WHERE `id_customer` = "'.(int)$id_customer.'"');
    }

    /* This function is used to associate the lifetime affiliate to a customer */
    public static function associateCustomerToAffiliate($id_customer, $id_affiliate)
    {
        return Db::getInstance()->insert('aff_customers', array(
            'id_affiliate' => (int)$id_affiliate,
            'id_customer' => (int)$id_customer,
            'date_add' => pSQL(date('Y-m-d H:i:s')),
        ), false, true, Db::REPLACE);
    }

    public static function getCustomerCommissionsGenerated($id_customer, $limit = 0)
    {
        $sql = 'SELECT `id_tracking` as `id`, `date`, `commission`, `id_affiliate`, (SELECT CONCAT(`firstname`, " ", `lastname`) FROM `'._DB_PREFIX_.'aff_affiliates` af WHERE af.`id_affiliate` = tr.`id_affiliate`) as `affiliate_name`, "tracking" as `type`, "1" as `approved`, "0" as `id_order`  FROM `'._DB_PREFIX_.'aff_tracking` tr WHERE `id_customer` = "'.(int)$id_customer.'"';
        $sql .= ' UNION SELECT `id_sale` as `id`, `date`, `commission`, `id_affiliate`, (SELECT CONCAT(`firstname`, " ", `lastname`) FROM `'._DB_PREFIX_.'aff_affiliates` af WHERE af.`id_affiliate` = sa.`id_affiliate`) as `affiliate_name`, "sale" as `type`, `approved`, `id_order` FROM `'._DB_PREFIX_.'aff_sales` sa WHERE `id_order` IN (SELECT `id_order` FROM `'._DB_PREFIX_.'orders` WHERE `id_customer` = "'.(int)$id_customer.'")';
        $sql .= ' ORDER BY `date` DESC';
        if ($limit) {
            $sql .= ' LIMIT '.(int)$limit;
        }

        return Db::getInstance()->executeS($sql);
    }

    public static function getLifetimeAffiliations($id_affiliate)
    {
        return Db::getInstance()->executeS('SELECT `id_affiliate`, `id_customer`, `date_add` as `date`, (SELECT CONCAT(`firstname`, " ", `lastname`) FROM `'._DB_PREFIX_.'customer` c WHERE c.`id_customer` = a.`id_customer`) as `customer_name`, ((SELECT IFNULL(SUM(`commission`), 0) FROM `'._DB_PREFIX_.'aff_tracking` WHERE `id_affiliate` = a.`id_affiliate` AND `id_customer` = a.`id_customer`) + (SELECT IFNULL(SUM(`commission`), 0) FROM `'._DB_PREFIX_.'aff_sales` WHERE `id_affiliate` = a.`id_affiliate` AND `id_order` IN (SELECT `id_order` FROM `'._DB_PREFIX_.'orders` WHERE `id_customer` = a.`id_customer`))) as `commission` FROM `'._DB_PREFIX_.'aff_customers` a WHERE a.`id_affiliate` = "'.(int)$id_affiliate.'" ORDER BY `date` DESC');
    }

    public function installAjaxAdminController()
    {
        $tab = new Tab;

        $tab->class_name = 'AdminPsaffiliateAjax';
        $tab->id_parent = '-1';
        $tab->module = $this->name;
        $tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = $this->displayName;
        if (!$tab->add()) {
            return false;
        }

        return true;
    }

    public static function getAllowedGroups()
    {
        self::loadClasses('AffConf');

        $allowed_groups = AffConf::getConfig('groups_allowed[]');

        return $allowed_groups;
    }

    public static function isGroupAllowed($id_group = 0)
    {
        $allowed_groups = self::getAllowedGroups();

        if (!$allowed_groups) {
            return true;
        } else {
            return in_array($id_group, $allowed_groups);
        }
    }

    public static function associateCartToAffiliate($id_cart, $id_affiliate, $id_campaign = 0)
    {
        return Db::getInstance()->insert('aff_cart', array(
            'id_cart' => (int)$id_cart,
            'id_affiliate' => (int)$id_affiliate,
            'id_campaign' => (int)$id_campaign,
            'date' => pSQL(date('Y-m-d H:i:s')),
        ), false, true, Db::REPLACE);
    }

    public static function getCartAffiliate($id_cart)
    {
        self::loadClasses('AffConf');
        $data = Db::getInstance()->getRow('SELECT `id_affiliate`, `id_campaign` FROM `'._DB_PREFIX_.'aff_cart` WHERE `id_cart` = "'.(int)$id_cart.'" AND `date` >= "'.pSQL(date('Y-m-d H:i:s', strtotime('-'.AffConf::getConfig('days_remember_affiliate').' days'))).'"');

        return $data;
    }

    public function hookActionCartSave($params)
    {
        if (!isset($this->context->cart)) {
            return;
        }
        $id_cart = (int)$this->context->cart->id;
        if ($this->hasSessionAffiliate()) {
            $id_affiliate = (int)$this->context->cookie->id_session_affiliate;
            $id_campaign = (int)$this->context->cookie->id_session_campaign;
            $cart_affiliate_data = self::getCartAffiliate($id_cart);
            if (!$cart_affiliate_data) {
                self::associateCartToAffiliate($id_cart, $id_affiliate, $id_campaign);
            } else {
                if ($cart_affiliate_data['id_affiliate'] != $id_affiliate || $cart_affiliate_data['id_campaign'] != $id_campaign) {
                    self::associateCartToAffiliate($id_cart, $id_affiliate, $id_campaign);
                }
            }
        }
    }

    public static function getAdminEmails()
    {
        self::loadClasses('AffConf');
        $admin_emails = AffConf::getConfig('emails');
        $admin_emails = explode(PHP_EOL, $admin_emails);
        $return = array();
        if ($admin_emails) {
            foreach ($admin_emails as $email) {
                $email = trim($email);
                if (Validate::isEmail($email)) {
                    $return[] = $email;
                }
            }
        }

        return $return;
    }

    public static function getGeneralRates()
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM `"._DB_PREFIX_."aff_commission` WHERE `id_affiliate`='0' ORDER BY `date` DESC";
        $array = $db->executeS($select);
        $return = array();
        foreach ($array as $a) {
            if (!isset($return[$a['type']])) {
                $return[$a['type']] = (float)$a['value'];
            }
        }

        return $return;
    }

    /**
     * Gdpr compliancy
     */
    /**
     * Delete customer personal data
     * @param $customer
     * @return string
     */
    public function hookActionDeleteGDPRCustomer ($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $customerId = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_customer` FROM `'._DB_PREFIX_.'customer` WHERE `email`="'.$customer['email'].'"');
            $affId = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_affiliate` FROM `'._DB_PREFIX_.'aff_affiliates` WHERE `id_customer`="'.$customerId.'"');
            $sql = array();
            $sql[] = Db::getInstance()->update('aff_affiliates_meta', array(
                'value' => 'GDPR ERASED',
            ), '`id_affiliate` ='.(int)$affId);
            $sql[] = Db::getInstance()->update('aff_affiliates', array(
                'email' => 'GDPR ERASED',
                'firstname' => 'GDPR ERASED',
                'lastname' => 'GDPR ERASED',
                'website' => 'GDPR ERASED',
                'textarea_registration' => 'GDPR ERASED',
            ), '`id_customer` ='.(int)$customerId);
            foreach ($sql as $query) {
                if (Db::getInstance()->execute($query) == false) {
                    return json_encode(true);
                }
            }
            return json_encode(sprintf($this->l('%s: Unable to delete customer using email.'), $this->displayName));
        }
    }

    /**
     * Export customer personal data
     * @param $customer
     * @return string
     */
    public function hookActionExportGDPRData ($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $customerId = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_customer` FROM `'._DB_PREFIX_.'customer` WHERE `email`="'.$customer['email'].'"');
            $affId = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `id_affiliate` FROM `'._DB_PREFIX_.'aff_affiliates` WHERE `id_customer`="'.$customerId.'"');
            $sql = array();
            $sql[] = "SELECT * FROM " . _DB_PREFIX_ . "aff_affiliates WHERE id_customer = '" . pSQL($customerId) . "'";
            $sql[] = "SELECT * FROM " . _DB_PREFIX_ . "aff_affiliates_meta WHERE id_affiliate = '" . pSQL($affId) . "'";

            foreach ($sql as $query) {
                if (Db::getInstance()->execute($query) == false) {
                    return json_encode(true);
                }
            }
            return json_encode(sprintf($this->l('%s: Unable to delete customer using email.'), $this->displayName));
        }
    }

    public static function customerHasOtherOrdersByCart($id_customer, $id_cart)
    {
        return (bool)Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'orders` WHERE `id_customer` = "'.(int)$id_customer.'" AND `id_cart` != "'.(int)$id_cart.'"');
    }

    public static function getMLM_LEVELS()
    {
        return self::$MLM_LEVELS;
    }

    public static function getMlmAffiliates($id_affiliate)
    {
        $return = array();
        $last_level_affiliates = array($id_affiliate);
        for ($i = 1; $i <= self::getMLM_LEVELS(); $i++) {
            if ($last_level_affiliates) {
                $return[$i] = self::getDirectAffiliatesFromIdAffiliates($last_level_affiliates, $id_affiliate);
            } else {
                $return[$i] = array();
            }
            $last_level_affiliates = array();
            foreach ($return[$i] as $row) {
                $last_level_affiliates[] = (int)$row['id_affiliate'];
            }
        }

        return $return;
    }

    public static function getDirectAffiliatesFromIdAffiliates($affiliates, $id_affiliate_for_sales = 0)
    {
        $return = Db::getInstance()->executeS('
            SELECT
                a.`id_affiliate`,
                CONCAT(a.`firstname`, " ", a.`lastname`) as `affiliate_name`,
                a.`id_customer`,
                b.`id_affiliate` as `id_parent_affiliate`,
                CONCAT(b.`firstname`, " ", b.`lastname`) as `parent_affiliate_name`
            FROM `'._DB_PREFIX_.'aff_affiliates` a
            LEFT JOIN `'._DB_PREFIX_.'aff_affiliates` b ON (a.`id_parent_affiliate` = b.`id_affiliate`)
            WHERE a.`id_parent_affiliate` IN ('.pSQL(implode(', ', $affiliates)).')'
        );
        if ($id_affiliate_for_sales) {
            foreach ($return as $key => &$row) {
                $row['sales_generated'] = (float)Db::getInstance()->getValue('SELECT SUM(`commission`) FROM `'._DB_PREFIX_.'aff_sales` WHERE `id_affiliate` = "'.(int)$id_affiliate_for_sales.'" AND `id_affiliate_origin` = "'.(int)$row['id_affiliate'].'" AND `approved` = "1"');
                $return[$key]['cust'][] = self::whoIsCustomer($row['id_customer']);
            }
        }
        return $return;
    }

    public static function getMlmRates()
    {
        $return = array();
        for ($i = 1; $i <= self::getMLM_LEVELS(); $i++) {
            $return[$i] = AffConf::getConfig('mlm_commission_'.$i);
        }

        return $return;
    }

    public static function getMlmParentInfo($id_affiliate)
    {
        $id_parent_affiliate = Db::getInstance()->getValue('SELECT `id_parent_affiliate` FROM `'._DB_PREFIX_.'aff_affiliates` WHERE `id_affiliate` = "'.(int)$id_affiliate.'"');
        $affiliate_name = '';
        if ($id_parent_affiliate) {
            $affiliate_name = Db::getInstance()->getValue('SELECT CONCAT(`firstname`, " ", `lastname`) as `name` FROM `'._DB_PREFIX_.'aff_affiliates` WHERE `id_affiliate` = "'.pSQL($id_parent_affiliate).'"');
        }

        return array(
            'id_parent_affiliate' => $id_parent_affiliate,
            'affiliate_name' => $affiliate_name,
            'parent_affiliate_details' => self::whoIsCustomer($id_affiliate),
        );
    }

    static function whoIsCustomer($id)
    {
        $infos =  Db::getInstance()->getRow("SELECT `address1`,`address2`, `city`, `postcode`, `id_state`, `id_country`, `phone`, `company` FROM `"._DB_PREFIX_."address` WHERE `id_customer` = ".$id);
        $newCountry = new Country($infos['id_country']);
        $newState = new State($infos['id_state']);

        $objCustomer = new Customer($id);
        $cust['lastname'] = $objCustomer->lastname;
        $cust['firstname'] = $objCustomer->firstname;
        $cust['company'] = $infos['company'];
        $cust['address1'] = $infos['address1'];
        $cust['address2'] = $infos['address2'];
        $cust['city'] = $infos['city'];
        $cust['postcode'] = $infos['postcode'];
        $cust['state'] = $newState->name;
        $cust['country'] = $newCountry->name[Context::getContext()->language->id];
        $cust['phone'] = $infos['phone'];
        $cust['email'] = $objCustomer->email;

        return $cust;
    }

    public static function displayPriceOverride($price, $currency = null)
    {
        self::loadClasses('AffConf');
        $points_label = (empty(AffConf::getConfig('commissions_name', true))) ? 'Points' : AffConf::getConfig('commissions_name', true);

        if (AffConf::getConfig('point_commissions')) {
            return $price . ' ' . $points_label;
        } elseif ($currency == null) {
            return Tools::displayPrice($price);
        } else {
            return Tools::displayPrice($price, $currency);
        }
    }

    public static function displayPriceOverrideHelperList($val, $tr)
    {
        return self::displayPriceOverride($val);
    }
}

