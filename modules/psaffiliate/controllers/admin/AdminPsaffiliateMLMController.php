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

class AdminPsaffiliateMLMController extends AdminController
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
        $output = "";
        if (((bool)Tools::isSubmit('submitAdminPsaffiliateMLM')) == true) {
            $postProcess = $this->postProcess();
            if (is_bool($postProcess) && $postProcess) {
                $output .= $this->moduleObj->displayConfirmation($this->l('Settings updated'));
            } else {
                $output .= $this->moduleObj->displayError($postProcess);
            }
        }
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this->moduleObj;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitAdminPsaffiliateMLM';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminPsaffiliateMLM', false);
        $helper->token = Tools::getAdminTokenLite('AdminPsaffiliateMLM');

        $mlm_form = $this->generateForm();

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigForm(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $AffConf = new AffConf;

        return $output.$helper->generateForm(array($mlm_form));
    }

    public function generateForm()
    {
        $mlm_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable multi-level marketing'),
                        'desc' => $this->l('Enables you to give commissions to affiliates on multiple levels - only for orders'),
                        'name' => 'mlm_enable',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        for ($i = 1; $i <= $this->moduleObj->getMLM_LEVELS(); $i++) {
            $mlm_form['form']['input'][] = array(
                'col' => 1,
                'type' => 'text',
                'name' => 'mlm_commission_'.$i,
                'label' => sprintf($this->l('Commission for level %s'), $i),
                'suffix' => '%',
                'validate' => 'isFloat',
            );
        }

        return $mlm_form;
    }

    public function getConfigForm()
    {
        $data = array(
            'mlm_enable' => AffConf::getConfig('mlm_enable'),
        );

        for ($i = 1; $i <= $this->moduleObj->getMLM_LEVELS(); $i++) {
            $data['mlm_commission_'.$i] = (float)AffConf::getConfig('mlm_commission_'.$i);
        }

        return $data;
    }

    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        return Translate::getModuleTranslation('psaffiliate', $string, get_class($this));
    }

    public function postProcess()
    {
        if (((bool)Tools::isSubmit('submitAdminPsaffiliateMLM')) == true) {
            $validateFields = $this->validateFields();
            if (!$validateFields) {
                $AffConf = new AffConf;
                foreach (array_keys($this->getConfigForm()) as $key) {
                    AffConf::updateConfig($key, (float)Tools::getValue($key));
                }
                return true;
            }
            return $validateFields;
        }
    }

    public function validateFields()
    {
        $errors = array();
        $AffConf = new AffConf;
        foreach ($this->generateForm() as $configForm) {
            foreach ($configForm['input'] as $input) {
                $name = $input['name'];
                $label = $input['label'];
                if (isset($input['validate'])) {
                    $validate = $input['validate'];
                    if (!Validate::$validate(pSQL(Tools::getValue($name)))) {
                        $errors[$name] = $AffConf->generateError($label, $validate);
                    }
                }
            }
        }

        return implode("<br />", $errors);
    }
}
