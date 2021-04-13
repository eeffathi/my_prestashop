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

class AdminPsaffiliateVouchersController extends AdminController
{
    public $fields_list;

    public function __construct()
    {
        $this->moduleObj = Module::getInstanceByName('psaffiliate');
        $this->moduleObj->loadClasses(array('AffConf'));
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->action = 'view';
        parent::__construct();
    }

    public function renderList()
    {
        $display = '';
        if (Tools::isSubmit('submitVouchers')) {
            $voucher_templates = Tools::jsonEncode(Tools::getValue('voucher_templates_selected'));
            $success = AffConf::updateConfig('voucher_templates', $voucher_templates);
            $success &= AffConf::updateConfig('vouchers_tracking', (int)Tools::getValue('vouchers_tracking'));
            if ($success) {
                $display .= $this->moduleObj->displayConfirmation($this->l('Settings saved.'));
            } else {
                $display .= $this->moduleObj->displayError($this->l('Could not save settings.'));
            }
        }
        $this->context->controller->addJquery();
        $this->context->controller->addJs($this->moduleObj->getPath().'views/js/admin/jquery.multi-select.js');
        $this->context->controller->addJs($this->moduleObj->getPath().'views/js/admin/jquery.quicksearch.js');
        $this->context->controller->addJs($this->moduleObj->getPath().'views/js/admin/vouchers.js');
        $this->context->controller->addCss($this->moduleObj->getPath().'views/css/multi-select.dist.css');
        Media::addJsDef(array(
            'text_available_items' => $this->l('Available items'),
            'text_selected_items' => $this->l('Selected items'),
        ));
        $this->moduleObj->loadClasses('VoucherToShare');
        $cartRules = CartRule::getCustomerCartRules($this->context->language->id, 0, true);
        if ($cartRules) {
            foreach ($cartRules as $key => $cartRule) {
                if (VoucherToShare::isVoucherToShareByCartRule($cartRule['id_cart_rule'])) {
                    unset($cartRules[$key]);
                }
            }
        }
        $this->context->smarty->assign(array(
            'cartRules' => $cartRules,
            'selected_cart_rules' => VoucherToShare::getVoucherTemplatesIds(),
            'vouchers_tracking' => AffConf::getConfig('vouchers_tracking'),
        ));
        $display .= $this->context->smarty->fetch(_PS_MODULE_DIR_.'psaffiliate/views/templates/admin/vouchers.tpl');
        parent::renderList();

        return $display;
    }

    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return Translate::getModuleTranslation('psaffiliate', $string, get_class($this));
    }
}
