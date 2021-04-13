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

if (! defined('_PS_VERSION_')) {
    exit();
}
require_once implode(DIRECTORY_SEPARATOR, array(
    dirname(__FILE__),
                                                'bigger_carts_rules_handler.php'
));

class BiggerCartsRulesResolver
{
    public function __construct()
    {
        $this->logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $this->logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
    }

    public function getApplicableRules($request_context)
    {
        $applicable_rules = [];
        // Redirect rules are not applicable for ajax calls.
        if (array_key_exists("ajax", $request_context)) {
            if ($request_context["ajax"]) {
                return $applicable_rules;
            }
        }
        // Read from config all the redirect rules.
        $exactRules = BiggerCartsRulesHandler::getAllExactRules();
        $partialRules = BiggerCartsRulesHandler::getAllPartialRules();
        // Check if a redirect rule exists for this keyphrase.
        if (array_key_exists("query_term", $request_context)) {
            $query_term = trim(Tools::strtolower($request_context["query_term"]));
            foreach ($exactRules as $rule) {
                if ($query_term == trim(Tools::strtolower($rule["keyphrase"]))) {
                    $applicable_rules["redirect"] = $rule["url"];
                    break;
                }
            }
            if (! array_key_exists("redirect", $applicable_rules)) {
                foreach ($partialRules as $rule) {
                    if (strpos($query_term, trim(Tools::strtolower($rule['keyphrase']))) !== false) {
                        $applicable_rules["redirect"] = $rule["url"];
                        break;
                    }
                }
            }
        }

        // Return null or the chosen redirect rule.
        return $applicable_rules;
    }
}
