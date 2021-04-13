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

class PsaffiliateVoucherstoshareModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->module->loadClasses(array('Affiliate', 'VoucherToShare'));
    }

    public function initContent()
    {
        parent::initContent();
        if (Tools::isSubmit('submitSaveVoucher') && $this->context->customer->isLogged() && Psaffiliate::getAffiliateId()) {
            $id_voucher_template = (int)Tools::getValue('id_voucher_template');
            $voucher_code = trim(Tools::getValue('voucher_code'));
            $voucher_name = trim(Tools::getValue('voucher_name'));

            if (Tools::getValue('id_vts')) {
                $id_vts = (int)Tools::getValue('id_vts');
                $voucher_to_share = new VoucherToShare($id_vts);
                if (Validate::isLoadedObject($voucher_to_share) && $voucher_to_share->id_affiliate == Psaffiliate::getAffiliateId()) {
                    $voucher_template = new CartRule($voucher_to_share->id_cart_rule_template);
                    $code_prefix = $voucher_template->code;
                    $code_noprefix = $voucher_code;
                    if ($code_prefix) {
                        $voucher_code = $code_prefix.'_'.$voucher_code;
                    }
                    if (VoucherToShare::cartRuleExists($voucher_code, $voucher_to_share->id_cart_rule)) {
                        $this->context->smarty->assign(array(
                            'voucherCodeExistsError' => true,
                        ));
                    } else {
                        $voucher_to_share->voucher_name = $voucher_name;
                        $voucher_to_share->voucher_code = $voucher_code;
                        $voucher_to_share->code_prefix = $code_prefix;
                        $voucher_to_share->code_noprefix = $code_noprefix;
                        if ($voucher_to_share->update()) {
                            $this->context->smarty->assign(array(
                                'id_voucher_to_share' => $voucher_to_share->id_vts,
                                'savedSuccess' => true,
                            ));
                        } else {
                            $this->context->smarty->assign(array(
                                'savedSuccess' => false,
                            ));
                        }
                    }
                }
            } elseif ($id_voucher_template && $voucher_code && $voucher_name && Validate::isCleanHtml($voucher_name) && Validate::isCleanHtml($voucher_code)) {
                if (VoucherToShare::isActiveVoucherTemplate($id_voucher_template)) {
                    $voucher_template = new CartRule($id_voucher_template);
                    $code_prefix = $voucher_template->code;
                    $code_noprefix = $voucher_code;
                    if ($code_prefix) {
                        $voucher_code = $code_prefix.'_'.$voucher_code;
                    }
                    if (VoucherToShare::cartRuleExists($voucher_code)) {
                        $this->context->smarty->assign(array(
                            'voucherCodeExistsError' => true,
                        ));
                    } else {
                        $voucher_to_share = new VoucherToShare();
                        $voucher_to_share->id_cart_rule_template = $id_voucher_template;
                        $voucher_to_share->id_affiliate = Psaffiliate::getAffiliateId();
                        $voucher_to_share->voucher_name = $voucher_name;
                        $voucher_to_share->voucher_code = $voucher_code;
                        $voucher_to_share->code_prefix = $code_prefix;
                        $voucher_to_share->code_noprefix = $code_noprefix;
                        if ($voucher_to_share->create()) {
                            $_POST['id_vts'] = $voucher_to_share->id;
                            $this->context->smarty->assign(array(
                                'id_voucher_to_share' => $voucher_to_share->id_vts,
                                'savedSuccess' => true,
                            ));
                        } else {
                            $this->context->smarty->assign(array(
                                'savedSuccess' => false,
                            ));
                        }
                    }
                } else {
                    $this->context->smarty->assign(array(
                        'voucherTemplateNotExistsError' => true,
                    ));
                }
            } else {
                $this->context->smarty->assign(array(
                    'voucherTemplateWrongDataError' => true,
                ));
            }
        }

        return $this->displayTemplate();
    }

    public function displayTemplate()
    {
        if ($this->context->customer->isLogged() && Psaffiliate::getAffiliateId()) {
            $id_voucher_to_share = (int)Tools::getValue('id_vts');
            $voucher = new VoucherToShare($id_voucher_to_share, $this->context->language->id);
            if ($voucher->id && Psaffiliate::getAffiliateId() != $voucher->id_affiliate) {
                $this->context->smarty->assign('hasErrorNotYourVoucher', true);
            } else {
                $this->context->smarty->assign(array(
                    'id_voucher_to_share' => $id_voucher_to_share,
                    'voucher' => (array)$voucher,
                    'voucher_templates' => VoucherToShare::getVoucherTemplates(true, $this->context->language->id),
                    'currency' => $this->context->currency,
                ));
            }
            if (Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
                $this->setTemplate("voucherstoshare.tpl");
            } else {
                $this->setTemplate("module:psaffiliate/views/templates/front/ps17/voucherstoshare.tpl");
            }
        } else {
            Tools::redirect('index.php?controller=authentication&back='.urlencode($this->context->link->getModuleLink(
                    'psaffiliate',
                    'myaccount'
                )));
        }
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = $this->addMyAccountToBreadcrumb();
        $breadcrumb['links'][] = array(
            'title' => $this->l('Affiliate Account'),
            'url' => $this->context->link->getModuleLink('psaffiliate', 'myaccount'),
        );

        return $breadcrumb;
    }

    public function l($string, $specific = false, $class = null, $addslashes = false, $htmlentities = true)
    {
        return Translate::getModuleTranslation('psaffiliate', $string, 'campaign');
    }
}
