<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Uplift
 * @copyright Uplift
 * @license   GPLv3
 */

if (!defined('_PS_VERSION_')) {
    exit();
}

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'src',
    'bigger_carts_indexer.php'
));

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'src',
    'bigger_carts_search_post_processor.php'
));

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'src',
    'bigger_carts_search_executor.php'
));

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'src',
    'bigger_carts_rules_resolver.php'
));

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    'vendor',
    'autoload.php'
));

class UpliftSearch extends Module
{
    private $module_name = '1+ Search';

    public function __construct()
    {
        $this->name = 'upliftsearch';
        $this->tab = 'search_filter';
        $this->version = '1.4.3';
        $this->author = 'Uplift';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;
        $this->logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $this->logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");

        parent::__construct();

        $this->displayName = $this->l('1+ Search');
        $this->description = $this->l("Fast and Reliable site search to increase your conversion rates");

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->indexer = new BiggerCartsIndexer();
        $this->module_key = '9d86ae62392f8faaba5459f49e52249a';
        /* Backward compatibility */
        if (_PS_VERSION_ < '1.5') {
            require(_PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (parent::install() && $this->addConfiguration() && $this->registerDomain() &&
            $this->registerHook('actionProductAdd') &&
            $this->registerHook('actionProductDelete') &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('actionAdminControllerSetMedia') && $this->createTables() && $this->installModuleTab()
        ) {
            return true;
        }

        return false;
    }

    public function uninstall()
    {
        if ($this->uninstallModuleTab() && $this->removeTables() &&
            $this->unregisterDomain() && $this->deleteConfiguration() && parent::uninstall()
        ) {
            return true;
        }

        return false;
    }

    public function getContent()
    {
        $output = null;
        $return_text = "Please go to the '" . $this->module_name . "' tab on left to configure this module.";
        if (_PS_VERSION_ > '1.7') {
            $return_text = $return_text . " It is present inside the 'Shop Parameters' tab ";
        }

        return $return_text;
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/uplift_search_admin_dashboard.css');
    }

    public function hookActionProductAdd($params)
    {
        return $this->hookActionProductUpdate($params);
    }
    public function hookActionProductUpdate($params)
    {
        if (!empty($params['id_product'])) {
            $product_id = $params['id_product'];
            $this->indexer->resetProductIndexStatusAsNeeded(false, $product_id);
            $this->indexer->indexDocuments(
                $full = false,
                $id_product = $product_id,
                $max_products_to_reindex = Configuration::get('BC_INDEX_BATCH_SIZE')
            );
        }
    }
    public function hookActionProductDelete($params)
    {
        if (!empty($params['id_product'])) {
            $product_id = $params['id_product'];
            $this->indexer->deleteDocument($product_id);
        }
    }

    private function createTables()
    {
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
                'uplift_redirect_rules` (
                                       `id_uplift_redirect_rule` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                       `url` varchar(255) NOT NULL,
                                       `exact_keyphrases` varchar(255) NOT NULL,
                                       `partial_keyphrases` varchar(255) NOT NULL,
                                       PRIMARY KEY (`id_uplift_redirect_rule`),
                                       UNIQUE(`url`)
                                       ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
        );
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
                'uplift_products_indexed` (
                                      `id_product` int(11) unsigned not null,
                                      `is_indexed` int(11) unsigned not null DEFAULT 0,
                                      PRIMARY KEY (`id_product`)
                                     ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
        );
        Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ .
                'uplift_product_hash` (
                                          `id_product` int(11) unsigned not null,
                                          `id_lang` int(11) unsigned,
                                          `product_hash` varchar(32) not null,
                                          `analytics_hash` varchar(32) not null,
                                          PRIMARY KEY (`id_product`, `id_lang`)
                                         ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
        );
        return true;
    }

    private function removeTables()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'uplift_products_indexed`');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'uplift_product_hash`');
        return true;
    }

    private function deleteConfiguration()
    {
        if (Configuration::deleteByName('BC_S3_BUCKET_NAME') && Configuration::deleteByName('BC_S3_REGION') &&
            Configuration::deleteByName('BC_SEARCH_ENDPOINT') && Configuration::deleteByName('BC_DOMAIN_HASH') &&
            Configuration::deleteByName('BC_RULES') && Configuration::deleteByName('BC_INDEX_BATCH_SIZE') &&
            Configuration::deleteByName('UPLIFT_DOMAIN_API_ENDPOINT') && Configuration::deleteByName('UPLIFT_INGEST_API_ENDPOINT') &&
            Configuration::deleteByName('UPLIFT_SEARCH_API_ENDPOINT')
        ) {
            return true;
        }
        return false;
    }

    private function addConfiguration()
    {
        if (Configuration::updateValue('BC_S3_BUCKET_NAME', 'biggercarts-public-access') &&
            Configuration::updateValue('BC_S3_REGION', 'us-east-1') &&
            Configuration::updateValue(
                'BC_SEARCH_ENDPOINT',
                'https://search-biggercarts-dev-odyffory34zdswrzbprp5v26ry.us-east-1.es.amazonaws.com'
            ) &&
            Configuration::updateValue('BC_DOMAIN_HASH', sha1(Configuration::get('PS_SHOP_DOMAIN'))) &&
            Configuration::updateValue('BC_RULES', []) && Configuration::updateValue('BC_INDEX_BATCH_SIZE', 20) &&
            Configuration::updateValue('UPLIFT_DOMAIN_API_ENDPOINT', 'https://api.global.discoverlift.com/domain-api') &&
            Configuration::updateValue('UPLIFT_SEARCH_API_ENDPOINT', 'https://api.global.discoverlift.com/search-api') &&
            Configuration::updateValue('UPLIFT_INGEST_API_ENDPOINT', 'https://api.global.discoverlift.com/ingest-api')
        ) {
            return true;
        }
        return false;
    }

    private function installModuleTab()
    {
        $tabId = null;
        $tab = null;
        if (_PS_VERSION_ > '1.7') {
            $tabId = (int) Tab::getIdFromClassName('AdminUpliftSearchDashboard');
            if (!$tabId) {
                $tabId = null;
            }
            $tab = new Tab($tabId);
            $tab->active = 1;
        } else {
            $tab = new Tab();
        }
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->module_name;
        }
        $tab->module = $this->name;
        $shop_parameters_id = (int) Tab::getIdFromClassName('ShopParameters');
        if (!$shop_parameters_id) {
            $shop_parameters_id = 0;
        }
        $tab->id_parent = $shop_parameters_id;
        $tab->class_name = 'AdminUpliftSearchDashboard';
        return $tab->save();
    }

    private function uninstallModuleTab()
    {
        $id_tab = Tab::getIdFromClassName('AdminUpliftSearchDashboard');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    private function registerDomain()
    {
        $api_endpoint = Configuration::get('UPLIFT_DOMAIN_API_ENDPOINT') . '/v1/domain/';
        $guzzleClient = new GuzzleHttp\Client();
        $response = $guzzleClient->post(
            $api_endpoint,
            [
                'body' => json_encode(
                    array(

                        "name" => Configuration::get('PS_SHOP_NAME'),
                        "url" => Configuration::get(
                            'PS_SHOP_DOMAIN'
                        )
                    )
                ),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );
        $responseBody = json_decode($response->getBody(), true);
        $domainId = $responseBody["id"];
        // Create a catalog
        $catalog_response = json_decode($guzzleClient->post(
            $api_endpoint . $domainId . '/catalog',
            [
                'body' => json_encode(
                    array(
                        "integration_type" => "prestashop",
                        "name" => "prestashop_default_catalog"
                    )
                ),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]
        )->getBody(), true);
        $catalogId = $catalog_response["id"];
        $defaultCatalogVersionId = $catalog_response["version"]["id"];

        Configuration::updateValue('UPLIFT_DOMAIN_ID', $domainId);
        Configuration::updateValue('UPLIFT_CATALOG_ID', $catalogId);
        Configuration::updateValue('UPLIFT_DEFAULT_CATALOG_VERSION_ID', $defaultCatalogVersionId);
        return true;
    }

    private function unregisterDomain()
    {
        try {
            $api_endpoint = Configuration::get('UPLIFT_DOMAIN_API_ENDPOINT') . '/v1/domain/' .
                Configuration::get('UPLIFT_DOMAIN_ID');
            $guzzleClient = new GuzzleHttp\Client();
            $response = $guzzleClient->delete($api_endpoint);
        } catch (Exception $e) {
            // Ignore any exceptions here.
        }
        return true;
    }

    public function getSearchPostProcessor()
    {
        return new BiggerCartsSearchPostProcessor();
    }

    public function getSearchExecutor()
    {
        return new BiggerCartsSearchExecutor();
    }

    public function getRulesResolver()
    {
        return new BiggerCartsRulesResolver();
    }
}
