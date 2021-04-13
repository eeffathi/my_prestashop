<?php
/**
* NOTICE OF LICENSE.
*
* This source file is subject to a commercial license from BSofts.
* Use, copy, modification or distribution of this source file without written
* license agreement from the BSofts is strictly forbidden.
*
*  @author    BSoft Inc
*  @copyright 2020 BSoft Inc.
*  @license   Commerical License
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/GoogleAddress.php');

class Google_address extends Module
{
    protected $googleUrl = 'https://developers.google.com/maps/documentation/javascript/get-api-key';

    private $id_shop = null;

    private $id_shop_group = null;

    /* constants for menu */
    const BS_GOOGLEADDRESS_AJAX = 'AdminGoogleAddress';

    public function __construct()
    {
        $this->name = 'google_address';
        $this->tab = 'front_office_features';
        $this->version = '1.2.0';
        $this->author = 'BSofts Inc';
        $this->need_instance = 0;
        $this->controllers = array('gaddress');

        $this->module_key = 'e80fbe3ae5e8368a4f041e8c077af447';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Autocomplete Google Address');
        $this->description = $this->l('This module autocompletes google address and fills city and zipcode for your customers.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        if ($this->id_shop === null || !Shop::isFeatureActive()) {
            $this->id_shop = Shop::getContextShopID(true);
        } else {
            $this->id_shop = $this->context->shop->id;
        }

        if ($this->id_shop_group === null || !Shop::isFeatureActive()) {
            $this->id_shop_group = Shop::getContextShopGroupID(true);
        } else {
            $this->id_shop_group = $this->context->shop->id_shop_group;
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $return = true;
        if (parent::install() && $this->registerHook(array('displayBackOfficeHeader', 'displayHeader','ModuleRoutes'))) {
            $formFieldKeys = array_keys($this->getConfigFormValues());
            foreach ($formFieldKeys as $key) {
                $value = true;
                switch ($key) {
                    case 'GOOGLE_ADDRESS_API_KEY':
                        $value = null;
                        break;
                    case 'GOOGLE_MAP_THEME':
                        $value = 'default';
                        break;
                    case 'GOOGLE_MAP_ZOOM_LEVEL':
                        $value = 10;
                        break;
                    default:
                        $value = $value;
                }
                $return &= Configuration::updateValue($key, $value, $this->id_shop_group, $this->id_shop);
            }
        }
        return $return;
    }

    public function uninstall()
    {
        $return = true;
        if (parent::uninstall()) {
            $formFieldKeys = array_keys($this->getConfigFormValues());
            foreach ($formFieldKeys as $key) {
                $return &= Configuration::deleteByName($key);
            }
        }
        return $return;
    }

    public function addAdminTab()
    {
        $ajaxTab = new Tab();
        $ajaxTab->active = 1;
        $ajaxTab->module = $this->name;
        $ajaxTab->class_name = Google_address::BS_GOOGLEADDRESS_AJAX;
        $ajaxTab->id_parent = -1;
        $ajaxTab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $ajaxTab->name[$lang['id_lang']] = $this->displayName;
        }

        if (!$ajaxTab->add()) {
            return false;
        }
        return true;
    }
    
    public function removeAdminTab()
    {
        // remove ajax tab
        if (!Validate::isLoadedObject($ajaxTab = Tab::getInstanceFromClassName(Google_address::BS_GOOGLEADDRESS_AJAX))) {
            return false;
        } else {
            if (!$ajaxTab->delete()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (true === ((bool)Tools::isSubmit('submitGoogle_addressModule'))) {
            $this->postProcess();
        }

        $this->context->smarty->assign('doc_path', $this->_path.'documentation/readme_en.pdf');

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = 'configuration';
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGoogle_addressModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'tabs' => array(
                    'map' => $this->l('Map Settings'),
                    'front' => $this->l('Frontoffice Settings'),
                    'back' => $this->l('Backoffice Settings'),
                ),
                'input' => array_merge(
                    $this->getMapSettings(),
                    $this->getFrontofficeSettings(),
                    $this->getBackofficeSettings()
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getMapSettings()
    {
        return array(
            array(
                'col' => 6,
                'type' => 'text',
                'name' => 'GOOGLE_ADDRESS_API_KEY',
                'label' => $this->l('Google API Key'),
                'prefix' => '<i class="icon icon-key"></i>',
                'desc' => $this->l('Follow instructions here to get an API key.').' <strong><a href="'.$this->googleUrl.'" target="_blank">' . $this->l('Get an API Key') . '</a></strong>',
                'tab' => 'map'
            ),
            array(
                'col' => 3,
                'type' => 'text',
                'name' => 'GOOGLE_MAP_ZOOM_LEVEL',
                'label' => $this->l('Map Zoom Level'),
                'prefix' => '<i class="icon icon-search-plus"></i>',
                'desc' => $this->l('Set default zoom level for Google map. Set a value from 0-18.'),
                'tab' => 'map'
            ),
            array(
                'col' => 4,
                'type' => 'select',
                'name' => 'GOOGLE_MAP_THEME',
                'label' => $this->l('Google Map Theme'),
                'desc' => $this->l('Select a theme for Google map'),
                'options' => array(
                    'query' => array(
                        array('theme' => 'browns', 'name' => $this->l('Cobalt')),
                        array('theme' => 'cobalt', 'name' => $this->l('Browns')),
                        array('theme' => 'darkgold', 'name' => $this->l('Dark and Gold')),
                        array('theme' => 'greenpoison', 'name' => $this->l('Green Poison')),
                        array('theme' => 'greyscale', 'name' => $this->l('Midnight')),
                        array('theme' => 'midnight', 'name' => $this->l('Greyscale')),
                        array('theme' => 'nightmode', 'name' => $this->l('Nightmode')),
                        array('theme' => 'orangenight', 'name' => $this->l('Orange Night')),
                        array('theme' => 'sketch', 'name' => $this->l('Sketch')),
                        array('theme' => 'uberblue', 'name' => $this->l('Uber Blue')),
                        array('theme' => 'yellow', 'name' => $this->l('Yellow')),
                    ),
                    'name' => 'name',
                    'id' => 'theme',
                    'default' => array('value' => 'default', 'label' => $this->l('Default'))
                ),
                'tab' => 'map'
            ),
        );
    }

    protected function getFrontofficeSettings()
    {
        return array(
            array(
                'type' => 'switch',
                'label' => $this->l('Enable Google Map'),
                'name' => 'GOOGLE_ADDRESS_GOOGLE_MAP',
                'is_bool' => true,
                'desc' => $this->l('Enable google map to locate address on map.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_GOOGLE_MAP_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_GOOGLE_MAP_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill City'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_CITY',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill city from address.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_CITY_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_CITY_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill State'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_STATE',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill state from address.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_STATE_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_STATE_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Zipcode'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill zipcode from address.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Country'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill country from address.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Enable International Phone Validator'),
                'name' => 'GOOGLE_ADDRESS_INTL_PHONE',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to validate international dialing numbers.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_INTL_PHONE_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_INTL_PHONE_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Address 2'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill complement address.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'front'
            ),
        );
    }

    protected function getBackofficeSettings()
    {
        return array(
            array(
                'type' => 'switch',
                'label' => $this->l('Enable Google Map'),
                'name' => 'GOOGLE_ADDRESS_GOOGLE_MAP_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable google map to locate address on map in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_GOOGLE_MAP_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_GOOGLE_MAP_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill City'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_CITY_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill city from address in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_CITY_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_CITY_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill State'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_STATE_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill state from address in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_STATE_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_STATE_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Zipcode'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill zipcode from address in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Country'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill country from address in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_COUNTRY_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Enable International Phone Validator'),
                'name' => 'GOOGLE_ADDRESS_INTL_PHONE_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to validate international dialing numbers in backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_INTL_PHONE_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_INTL_PHONE_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Autofill Address 2'),
                'name' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_BO',
                'is_bool' => true,
                'desc' => $this->l('Enable this option to auto-fill complement address on backoffice.'),
                'values' => array(
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_BO_on',
                        'value' => true,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_BO_off',
                        'value' => false,
                        'label' => $this->l('Disabled')
                    )
                ),
                'tab' => 'back'
            ),
        );
    }
    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $configForm = array(
            'GOOGLE_ADDRESS_API_KEY' => Tools::getValue(
                'GOOGLE_ADDRESS_API_KEY',
                Configuration::get(
                    'GOOGLE_ADDRESS_API_KEY',
                    null,
                    $this->id_shop_group,
                    $this->id_shop
                )
            ),
            'GOOGLE_MAP_THEME' => Tools::getValue(
                'GOOGLE_MAP_THEME',
                Configuration::get(
                    'GOOGLE_MAP_THEME',
                    null,
                    $this->id_shop_group,
                    $this->id_shop
                )
            ),
            'GOOGLE_MAP_ZOOM_LEVEL' => Tools::getValue(
                'GOOGLE_MAP_ZOOM_LEVEL',
                Configuration::get(
                    'GOOGLE_MAP_ZOOM_LEVEL',
                    null,
                    $this->id_shop_group,
                    $this->id_shop
                )
            )
        );

        $configForm += $this->getConfigSettingValues() + $this->getConfigSettingValues('frontoffice');

        return $configForm;
    }

    public function getConfigSettingValues($settings = 'backoffice')
    {
        $keys = array(
            'GOOGLE_ADDRESS_GOOGLE_MAP',
            'GOOGLE_ADDRESS_INTL_PHONE',
            'GOOGLE_ADDRESS_AUTOFILL_CITY',
            'GOOGLE_ADDRESS_AUTOFILL_STATE',
            'GOOGLE_ADDRESS_AUTOFILL_ZIPCODE',
            'GOOGLE_ADDRESS_AUTOFILL_COUNTRY',
            'GOOGLE_ADDRESS_AUTOFILL_ADDRESS2',
        );

        $settingValues = array();
        foreach ($keys as $key) {
            $key = ('frontoffice' == $settings)? $key : $key . '_BO';
            $settingValues[$key] = Tools::getValue(
                $key,
                Configuration::get(
                    $key,
                    null,
                    $this->id_shop_group,
                    $this->id_shop
                )
            );
        }
        return $settingValues;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            $value = Tools::getValue($key);
            if ('GOOGLE_MAP_ZOOM_LEVEL' == $key && isset($value) && !Validate::isUnsignedInt($value)) {
                $this->context->controller->errors[] = $this->l('Invalid zoom level value.');
                break;
            }
            Configuration::updateValue(
                $key,
                $value,
                false,
                $this->id_shop_group,
                $this->id_shop
            );
        }

        if (!count($this->context->controller->errors)) {
            $this->context->controller->confirmations[] = $this->l('Settings successfully updated.');
        }
    }

    public function getModuleUrl($token = true)
    {
        return Context::getContext()->link->getAdminLink('AdminModules', $token).'&'.http_build_query(array(
            'configure'   => $this->name,
            'tab_module'  => $this->tab,
            'module_name' => $this->name,
        ));
    }

    public function hookDisplayBackOfficeHeader()
    {
        $controller = Dispatcher::getInstance()->getController();
        if (in_array($controller, array('AdminAddresses'))) {
            $this->getAssets();
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $controller = Dispatcher::getInstance()->getController();
        if (in_array($controller, array('address', 'cart', 'checkout', 'order', 'orderopc'))) {
            $this->getAssets('frontoffice');
        }
    }

    public function hookModuleRoutes()
    {
        return array(
            'module-'.$this->name . '-' . $this->controllers[0] => array(
                'controller' => $this->controllers[0],
                'rule' => 'google-address',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => $this->name,
                ),
            )
        );
    }

    protected function getAssets($request = 'backoffice')
    {
        $configs = array();
        $active = ('backoffice' == $request)? false : true;
        $countries = GoogleAddress::getCountriesIso($active);
        $utils = $this->_path.'views/js/utils.js';
        $formFieldKeys = array_keys($this->getConfigSettingValues($request));
        foreach ($formFieldKeys as $key) {
            $configs[(('backoffice' == $request)? rtrim($key, '_BO') : $key)] = (int)Configuration::get(
                $key,
                null,
                $this->context->shop->id_shop_group,
                $this->context->shop->id
            );
        }
        $gAddressUrl = $this->context->link->getAdminLink(Google_address::BS_GOOGLEADDRESS_AJAX);
        $this->context->controller->addJquery();
        if ('frontoffice' == $request) {
            $gAddressUrl = $this->context->link->getModuleLink($this->name, $this->controllers[0]);
        }

        Media::addJsDef(array(
            'utils' => $utils,
            'gAddressUrl' => $gAddressUrl,
            'gAcountries' => Tools::jsonEncode($countries),
            'type' => (('frontoffice' == $request)? 'fo' : 'bo'),
            'countryIso' => Tools::jsonEncode(array_values($countries)),
            'defaultCountry' => (int) Configuration::get('PS_COUNTRY_DEFAULT'),
            'front_controller' => ('frontoffice' == $request)? true : false,
            'isOneSeven' => (Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')? true : false),
            'defaultLat' => Configuration::get('PS_STORES_CENTER_LAT'),
            'defaultLong' => Configuration::get('PS_STORES_CENTER_LONG'),
            'undetermined_add_label' => $this->l('Undetermined location'),
            'msg_labels' => array(
                'error' => $this->l('Invalid'),
                'success' => $this->l('Valid'),
            ),
            'intl_errors' => array(
                $this->l('Invalid number'),
                $this->l('Invalid country code'),
                $this->l('Too short'),
                $this->l('Too long'),
                $this->l('Invalid number'),
            ),
            'ISO_LANG' => $this->context->language->iso_code,
            'GOOGLE_API_KEY' => Configuration::get(
                'GOOGLE_ADDRESS_API_KEY',
                null,
                $this->context->shop->id_shop_group,
                $this->context->shop->id
            ),
            'GOOGLE_MAP_ZOOM_LEVEL' => (int) Configuration::get(
                'GOOGLE_MAP_ZOOM_LEVEL',
                null,
                $this->context->shop->id_shop_group,
                $this->context->shop->id
            ),
            'GOOGLE_MAP_THEME' => GoogleAddress::googleMapThemes(Configuration::get(
                'GOOGLE_MAP_THEME',
                null,
                $this->context->shop->id_shop_group,
                $this->context->shop->id
            )),
        ) + $configs);

        $this->context->controller->addJS(array(
            $this->_path.'/views/js/intlTelInput.js',
            $this->_path.'/views/js/' . $this->name . '.min.js'
        ));
        
        $this->context->controller->addCSS(array(
            $this->_path.'/views/css/intlTelInput.css',
            $this->_path.'/views/css/gaddress.css'
        ));
    }

    public function updateChanges()
    {
        return ($this->registerHook('displayBackOfficeHeader') &&
            Configuration::updateValue('GOOGLE_ADDRESS_AUTOFILL_ADDRESS2', 0, false, $this->id_shop_group, $this->id_shop) && 
            Configuration::updateValue('GOOGLE_ADDRESS_AUTOFILL_ADDRESS2_BO', 0, false, $this->id_shop_group, $this->id_shop));
    }
}
