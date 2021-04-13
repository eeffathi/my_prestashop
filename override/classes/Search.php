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
class Search extends SearchCore
{
    /*
    * module: upliftsearch
    * date: 2021-04-06 12:36:24
    * version: 1.4.3
    */
    public static function find(
        $id_lang,
        $expr,
        $page_number = 1,
        $page_size = 1,
        $order_by = 'position',
        $order_way = 'desc',
        $ajax = false,
        $use_cookie = true,
        Context $context = null
    ) {
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        $query_term = Tools::strtolower(trim(preg_replace('/\t+/', '', $expr)));
        $module = ModuleCore::getInstanceByName('upliftsearch');
        $applicable_rules = $module->getRulesResolver()->getApplicableRules(
            [
                                                                                "query_term" => $query_term,
                                                                                "page_number" => $page_number,
                                                                                "order_by" => $order_by,
                                                                                "order_way" => $order_way,
                                                                                "ajax" => $ajax,
                                                                                "context" => $context,
                                                                                "use_cookie" => $use_cookie
                                                                            ]
        );
        $search_response = $module->getSearchExecutor()->search(
            $query_term,
            $page_number,
            $page_size,
            $order_by,
            $order_way,
            $applicable_rules,
            $id_lang
        );
        $query_result = $search_response['results'];
        $num_results = $search_response['total'];
        $result_properties = $module->getSearchPostProcessor()->addAdditionalDetails(
            (int) $id_lang,
            $ajax,
            $context,
            $query_result
        );
        if ($ajax) {
            return $result_properties;
        }
        return array(
                        'total' => $num_results,
                        'result' => $result_properties
        );
    }
}
