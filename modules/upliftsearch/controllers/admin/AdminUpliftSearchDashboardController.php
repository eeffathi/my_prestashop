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

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    '../../src/bigger_carts_rules_resolver.php'
));
require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    '../../src/bigger_carts_rules_handler.php'
));

require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
    '../../src/bigger_carts_indexer.php'
));

class AdminUpliftSearchDashboardController extends ModuleAdminController
{
    public function init()
    {
        parent::init();
        $this->bootstrap = true;
        $this->logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $this->logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
    }

    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign(array(
            'token' => Tools::getAdminTokenLite('AdminModules')
        ));
        $this->setTemplate('index.html');
    }

    public function ajaxProcessGetRules()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(BiggerCartsRulesHandler::getAllRulesFromDB()));
    }

    public function ajaxProcessGetSearchEndpoint()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(
            array(
                "search_endpoint" => Configuration::get('BC_SEARCH_ENDPOINT') . "/" .
                    Configuration::get('UPLIFT_DOMAIN_ID') . "_" .
                    Configuration::get('UPLIFT_CATALOG_ID')
            )
        ));
    }

    public function ajaxProcessUpdateRule()
    {
        $rule = json_decode(Tools::getValue('rule'));
        if ($rule->url != null && !empty($rule->url)) {
            $this->logger->logDebug("Updating Rule: " . print_r($rule, true));
            BiggerCartsRulesHandler::updateRule($rule);
        }
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(BiggerCartsRulesHandler::getAllRulesFromDB()));
    }

    public function ajaxProcessDeleteRule()
    {
        $rule_id = Tools::getValue('id_uplift_redirect_rule');
        BiggerCartsRulesHandler::deleteRule($rule_id);
        $this->logger->logDebug("Deleted Rule: " . $rule_id);
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(BiggerCartsRulesHandler::getAllRulesFromDB()));
    }

    public function ajaxProcessAddRule()
    {
        // Get information from form submission.
        $rule = json_decode(Tools::getValue('rule'));
        // Add rule.
        if ($rule->url != null && !empty($rule->url)) {
            BiggerCartsRulesHandler::addRule($rule);
        }
        $this->logger->logDebug("Got Rule: " . print_r($rule, true));
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(BiggerCartsRulesHandler::getAllRulesFromDB()));
    }

    public function ajaxProcessBulkUploadRules()
    {
        // Drop all existing rules.
        BiggerCartsRulesHandler::dropAllRules();
        // Get information from form submission.
        $rules = Tools::getValue('rules');
        // Add rule.
        BiggerCartsRulesHandler::bulkAddRules(json_decode($rules));
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(BiggerCartsRulesHandler::getAllRulesFromDB()));
    }

    public function ajaxProcessGetIndexBatchSize()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(array(
            'index_batch_size' => Configuration::get('BC_INDEX_BATCH_SIZE')
        )));
    }

    public function ajaxProcessUpdateIndexBatchSize()
    {
        Configuration::updateValue('BC_INDEX_BATCH_SIZE', Tools::getValue('index_batch_size'));
        $this->ajaxProcessGetIndexBatchSize();
    }

    public function ajaxProcessGetRuleSummary()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(
            array(
                "summary" => "Redirect rules can be used to make the user land on certain page when a particular keyphrase is entered in search bar. There are two type of keyphrases - partial and exact. Exact keyphrase checks for exact match of user query with the keyphrase. Partial keyphrase checks for presence of keyphrase in user query. Exact keyphrases take priority over partial keyphrases.",
                "title" => "Redirect Rules"
            )
        ));
    }

    public function ajaxProcessGetUpliftApiEndpoints()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(
            array(
                "domainApi" => Configuration::get('UPLIFT_DOMAIN_API_ENDPOINT'),
                "searchApi" => Configuration::get('UPLIFT_SEARCH_API_ENDPOINT'),
                "ingestApi" => Configuration::get('UPLIFT_INGEST_API_ENDPOINT'),
                "domainId" => Configuration::get('UPLIFT_DOMAIN_ID'),
                "catalogId" => Configuration::get('UPLIFT_CATALOG_ID')
            )
        ));
    }
    public function ajaxProcessGetDomainId()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(
            array(
                "domainId" => Configuration::get('UPLIFT_DOMAIN_ID')
            )
        ));
    }
    // Reset Feed index status so full reindex can happen..
    public function ajaxProcessResetFeedIndexStatus()
    {
        return $this->processResetFeedIndexStatus();
    }
    public function processResetFeedIndexStatus()
    {
        // Reset status of all products so they can be reindexed.
        BiggerCartsIndexer::resetProductIndexStatusAsNeeded(true, null);
        // Create new catalog version to index data to.
        BiggerCartsIndexer::createNewCatalogVersion();
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(array(
            "message" => "Index status is reset."
        )));
    }

    // Get the list of languages supported by this shop
    public function ajaxProcessGetLanguages()
    {
        return $this->processGetLanguages();
    }
    public function processGetLanguages()
    {
        die(Tools::jsonEncode(array_map(
            function ($input) {
                return $input['iso_code'];
            },
            Language::getLanguages(true)
        )));
    }

    // Reindex next batch of products till a max set of products to consider in this request.
    public function ajaxProcessReindexNextBatch()
    {
        return $this->processReindexNextBatch();
    }
    public function processReindexNextBatch()
    {
        header('Cache-Control: no-cache, must-revalidate');

        // Reindex next batch of products
        try {
            list($num_unique_products, $num_products_indexed) = BiggerCartsIndexer::indexDocuments(
                $full = true,
                $id_product = false,
                $max_products_to_reindex = Configuration::get('BC_INDEX_BATCH_SIZE')
            );
            die(Tools::jsonEncode(
                array(
                    "numProductsIndexed" => $num_products_indexed,
                    "numUniqueProducts" => $num_unique_products
                )
            ));
        } catch (Exception $e) {
            $this->logger->logDebug("Exception occurred " . print_r($e, true));

            die("Exception occurred " . print_r($e, true));
        }
    }

    // Promote interim catalog version to default
    public function ajaxProcessPublishInterimCatalogVersion()
    {
        return $this->processPublishInterimCatalogVersion();
    }
    public function processPublishInterimCatalogVersion()
    {
        header('Cache-Control: no-cache, must-revalidate');
        die(Tools::jsonEncode(array(
            "liveCatalogVersion" => "Non Existent"
        )));
    }

    /**
     * Check for security token
     *
     * @return bool
     */
    public function checkToken()
    {
        $token = Tools::getValue('token');
        if (!empty($token) && $token === $this->token) {
            return true;
        }

        if (count($_POST) || !Tools::getIsset('controller') || !Validate::isControllerName(Tools::getValue('controller')) || $token) {
            return false;
        }

        foreach ($_GET as $key => $value) {
            if (is_array($value) || !in_array($key, array(
                'action',
                'controller',
                'controllerUri'
            ))) {
                return false;
            }
        }

        $cookie = Context::getContext()->cookie;
        $whitelist = array(
            'date_add',
            'id_lang',
            'id_employee',
            'email',
            'profile',
            'passwd',
            'remote_addr',
            'shopContext',
            'collapse_menu',
            'checksum'
        );
        foreach ($cookie->getAll() as $key => $value) {
            if (!in_array($key, $whitelist)) {
                unset($cookie->$key);
            }
        }

        $cookie->write();

        return true;
    }
}
