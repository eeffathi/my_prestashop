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

class BiggerCartsRulesHandler
{
    private static $partialRules = [];

    private static $exactRules = [];

    public static function bulkAddRules($rules)
    {
        // Go over list of all the rules.
        foreach ($rules as $rule) {
            // Add rules to DB
            BiggerCartsRulesHandler::addRule($rule, false);
        }
        BiggerCartsRulesHandler::refreshRuleCache();
        // Return whether the operation succeeded.
        return true;
    }

    public static function dropAllRules()
    {
        Db::getInstance()->execute('Delete from `' . _DB_PREFIX_ . 'uplift_redirect_rules`');
        BiggerCartsRulesHandler::refreshRuleCache();
        // Return whether the operation succeeded.
        return true;
    }

    public static function deleteRule($rule_id)
    {
        // Delete rule from DB.
        Db::getInstance()->execute(
            'Delete from `' . _DB_PREFIX_ .
                                    'uplift_redirect_rules`  where `id_uplift_redirect_rule`=' . $rule_id
        );
        BiggerCartsRulesHandler::refreshRuleCache();
        // Return whether the operation succeeded.
        return true;
    }

    public static function addRule($rule, $shouldRefreshRuleCache = true)
    {
        // Add rule to DB.
        try {
            Db::getInstance()->execute(
                'Insert into `' . _DB_PREFIX_ .
                                        'uplift_redirect_rules`  (
                                       `url`,
                                       `exact_keyphrases`,
                                       `partial_keyphrases`) values (
                                       "' . $rule->url . '","' . $rule->exact_keyphrases . '","' .
                                        $rule->partial_keyphrases . '")'
            );
        } catch (Exception $e) {
            return false;
        }
        if ($shouldRefreshRuleCache) {
            BiggerCartsRulesHandler::refreshRuleCache();
        }
        // Return whether the operation succeeded.
        return true;
    }

    public static function updateRule($rule)
    {
        try {
            // Update rule.
            Db::getInstance()->execute(
                'Update `' . _DB_PREFIX_ . 'uplift_redirect_rules` set `url`= "' . $rule->url .
                                        '",' . '`exact_keyphrases`="' . $rule->exact_keyphrases .
                                        '",`partial_keyphrases`="' . $rule->partial_keyphrases .
                                        '" where `id_uplift_redirect_rule`=' . $rule->id_uplift_redirect_rule
            );
        } catch (Exception $e) {
            return false;
        }
        BiggerCartsRulesHandler::refreshRuleCache();
        // Return whether the operation succeeded.
        return true;
    }

    public static function getAllRulesFromDB()
    {
        return Db::getInstance()->executeS(
            'Select * from `' . _DB_PREFIX_ .
                                            'uplift_redirect_rules` order by `id_uplift_redirect_rule` desc',
            true,
            false
        );
    }

    public static function getAllPartialRules()
    {
        BiggerCartsRulesHandler::refreshRuleCache();
        return self::$partialRules;
    }

    public static function getAllExactRules()
    {
        BiggerCartsRulesHandler::refreshRuleCache();
        return self::$exactRules;
    }

    private static function refreshRuleCache()
    {
        $logger = new FileLogger(0); // 0 == debug level, logDebug() won’t work without this.
        $logger->setFilename(_PS_ROOT_DIR_ . "/debug.log");
        
        // Get all rules from DB.
        $allRules = BiggerCartsRulesHandler::getAllRulesFromDB();

        // Build partial and exact rule cache.
        $currExactRules = [];
        $currPartialRules = [];
        foreach ($allRules as $rule) {
            foreach (array_filter(explode(",", $rule['exact_keyphrases'])) as $keyphrase) {
                $currExactRules[] = array(
                                            "keyphrase" => $keyphrase,
                                            "url" => $rule["url"]
                );
            }
            foreach (array_filter(explode(",", $rule['partial_keyphrases'])) as $keyphrase) {
                $currPartialRules[] = array(
                                            "keyphrase" => $keyphrase,
                                            "url" => $rule["url"]
                );
            }
        }

        // Swap static variables with partial and exact rule cache.
        self::$exactRules = $currExactRules;
        self::$partialRules = $currPartialRules;
    }

    public static function getAllRules()
    {
        return array(
                        array(
                                'url' => 'https://citimarinestore.com/en/19-marine-watermakers-for-sale',
                                'type' => 'partial',
                                'keyphrase' => 'watermaker'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/19-marine-watermakers-for-sale',
                                'type' => 'partial',
                                'keyphrase' => 'water maker'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/115-marine-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/115-marine-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'pumps'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/122-jabsco-marine-blowers',
                                'type' => 'partial',
                                'keyphrase' => 'blower'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/27-marine-generators',
                                'type' => 'partial',
                                'keyphrase' => 'generator (anywhere in the word)'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-18-autopilots',
                                'type' => 'partial',
                                'keyphrase' => 'autopilot'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-18-autopilots',
                                'type' => 'partial',
                                'keyphrase' => 'auto pilot'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-18-autopilots',
                                'type' => 'partial',
                                'keyphrase' => 'pilot'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/84-raymarine-autopilots',
                                'type' => 'partial',
                                'keyphrase' => 'raymarine autopilot'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/84-raymarine-autopilots',
                                'type' => 'partial',
                                'keyphrase' => 'raymarine auto pilot'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'air conditioner'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'exact',
                                'keyphrase' => 'ac'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'exact',
                                'keyphrase' => 'a/c'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'marine ac'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'marine a/c'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'marine air'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'self contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'self-contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/184-marine-air-conditioning-boat-ac',
                                'type' => 'partial',
                                'keyphrase' => 'self contained air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/319-marine-internet-connectivity-equipment',
                                'type' => 'partial',
                                'keyphrase' => 'internet'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/319-marine-internet-connectivity-equipment',
                                'type' => 'partial',
                                'keyphrase' => 'wifi'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-24-radars-radomes-pedestals',
                                'type' => 'partial',
                                'keyphrase' => 'radar'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/22-propulsion',
                                'type' => 'partial',
                                'keyphrase' => 'shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'partial',
                                'keyphrase' => 'cutless bearing'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'partial',
                                'keyphrase' => 'cutlass bearing'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'partial',
                                'keyphrase' => 'bearing'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'partial',
                                'keyphrase' => 'cutless'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'partial',
                                'keyphrase' => 'cutlass'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/393-webasto-marine-chiller-air-conditioning-units',
                                'type' => 'partial',
                                'keyphrase' => 'chiller'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/393-webasto-marine-chiller-air-conditioning-units',
                                'type' => 'partial',
                                'keyphrase' => 'marine chiller'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/233-brownies-hookah-diving-systems',
                                'type' => 'partial',
                                'keyphrase' => 'hookah'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/233-brownies-hookah-diving-systems',
                                'type' => 'partial',
                                'keyphrase' => 'diving hookahs'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/115-marine-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'ac pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/115-marine-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'a/c pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/130-little-wonder',
                                'type' => 'partial',
                                'keyphrase' => 'little wonder'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/312-windlasses',
                                'type' => 'partial',
                                'keyphrase' => 'windlass'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/12-marine-gps-electronics',
                                'type' => 'partial',
                                'keyphrase' => 'gps'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/64-jl-audio-marine-subwoofers',
                                'type' => 'partial',
                                'keyphrase' => 'jl audio sub'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/64-jl-audio-marine-subwoofers',
                                'type' => 'partial',
                                'keyphrase' => 'jl audio subwoofer'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/9-marine-refrigerators-freezers',
                                'type' => 'partial',
                                'keyphrase' => 'refrigerator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/9-marine-refrigerators-freezers',
                                'type' => 'partial',
                                'keyphrase' => 'fridge'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/383-marine-solar-panel-kits-inverters-solar-power-for-boats',
                                'type' => 'partial',
                                'keyphrase' => 'solar'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/383-marine-solar-panel-kits-inverters-solar-power-for-boats',
                                'type' => 'partial',
                                'keyphrase' => 'solar panel'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/281-westerbeke-marine-generators',
                                'type' => 'partial',
                                'keyphrase' => 'gas generator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/281-westerbeke-marine-generators',
                                'type' => 'partial',
                                'keyphrase' => 'gasoline generator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/6-lighting-',
                                'type' => 'partial',
                                'keyphrase' => 'underwater lights'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/34-zimar-zincs-anodes',
                                'type' => 'partial',
                                'keyphrase' => 'zinc'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/34-zimar-zincs-anodes',
                                'type' => 'partial',
                                'keyphrase' => 'anode'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/34-zimar-zincs-anodes',
                                'type' => 'partial',
                                'keyphrase' => 'zinc anode'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/175-anchors',
                                'type' => 'partial',
                                'keyphrase' => 'anchor'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/96-antennas-',
                                'type' => 'partial',
                                'keyphrase' => 'antenna'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/96-antennas-',
                                'type' => 'partial',
                                'keyphrase' => 'shakespeare'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/172-marine-battery-chargers',
                                'type' => 'partial',
                                'keyphrase' => 'battery'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/172-marine-battery-chargers',
                                'type' => 'partial',
                                'keyphrase' => 'battery charger'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/172-marine-battery-chargers',
                                'type' => 'partial',
                                'keyphrase' => 'chargers'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/204-rule-bilge-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'bilge pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/289-frigibar-refrigerators-freezers-salty-dogs-deck-boxes',
                                'type' => 'partial',
                                'keyphrase' => 'deck box'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/144-watermaker-parts-accessories-consumables',
                                'type' => 'partial',
                                'keyphrase' => 'membrane'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-27-fishfinders',
                                'type' => 'partial',
                                'keyphrase' => 'fishfinder'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-27-fishfinders',
                                'type' => 'partial',
                                'keyphrase' => 'fish finder'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/99-icom-marine-radios',
                                'type' => 'partial',
                                'keyphrase' => 'vhf'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/99-icom-marine-radios',
                                'type' => 'partial',
                                'keyphrase' => 'vhf radio'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/231-survival-rafts-life-rafts',
                                'type' => 'partial',
                                'keyphrase' => 'life raft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/231-survival-rafts-life-rafts',
                                'type' => 'partial',
                                'keyphrase' => 'liferaft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'partial',
                                'keyphrase' => 'dinghies'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'partial',
                                'keyphrase' => 'dinghy'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/9-marine-refrigerators-freezers',
                                'type' => 'partial',
                                'keyphrase' => 'freezer'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/217-michigan-propellers',
                                'type' => 'partial',
                                'keyphrase' => 'propeller'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/354-marine-plumbing-toilets',
                                'type' => 'partial',
                                'keyphrase' => 'toilet'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/-26-sonars-sounders-transducers',
                                'type' => 'partial',
                                'keyphrase' => 'transducer'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/267-dometic-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'dometic pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/115-marine-pumps',
                                'type' => 'partial',
                                'keyphrase' => 'electric pumps'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/250-dometic-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'dometic self contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/250-dometic-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'dometic self-contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/250-dometic-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'dometic self-contained air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/250-dometic-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'dometic self contained air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/186-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'cruisair self contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/186-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'cruisair self-contained'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/186-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'cruisair self-contained air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/186-self-contained',
                                'type' => 'partial',
                                'keyphrase' => 'cruisair self contained air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'exact',
                                'keyphrase' => 'caribe'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'exact',
                                'keyphrase' => 'caribe dinghy'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'exact',
                                'keyphrase' => 'caribe dinghies'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/268-caribe-dinghies-inflatables',
                                'type' => 'exact',
                                'keyphrase' => 'caribe inflatable'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/20-mastervolt-battery-chargers',
                                'type' => 'exact',
                                'keyphrase' => 'mastervolt'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/20-mastervolt-battery-chargers',
                                'type' => 'exact',
                                'keyphrase' => 'mastervolt charger'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/20-mastervolt-battery-chargers',
                                'type' => 'exact',
                                'keyphrase' => 'mastervolt battery charger'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/7-shadow-caster-underwater-lights',
                                'type' => 'exact',
                                'keyphrase' => 'shadow caster'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/7-shadow-caster-underwater-lights',
                                'type' => 'exact',
                                'keyphrase' => 'shadow caster underwater lights'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/7-shadow-caster-underwater-lights',
                                'type' => 'exact',
                                'keyphrase' => 'shadowcaster underwater lights'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/7-shadow-caster-underwater-lights',
                                'type' => 'exact',
                                'keyphrase' => 'shadow caster light'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/287-vitrifrigo-marine-refrigerators-freezers',
                                'type' => 'exact',
                                'keyphrase' => 'vitrifrigo'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/287-vitrifrigo-marine-refrigerators-freezers',
                                'type' => 'exact',
                                'keyphrase' => 'vitrifrigo refrigerator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/272-viking-liferafts',
                                'type' => 'exact',
                                'keyphrase' => 'viking'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/272-viking-liferafts',
                                'type' => 'exact',
                                'keyphrase' => 'viking life raft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/272-viking-liferafts',
                                'type' => 'exact',
                                'keyphrase' => 'viking liferaft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/16-flir-marine-cameras',
                                'type' => 'exact',
                                'keyphrase' => 'flir'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/16-flir-marine-cameras',
                                'type' => 'exact',
                                'keyphrase' => 'flir camera'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/279-kohler-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'kohler'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/279-kohler-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'kohler generator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/281-westerbeke-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'westerbeke'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/281-westerbeke-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'westerbeke generator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/249-dometic-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'dometic'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/249-dometic-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'dometic air conditioner'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/249-dometic-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'dometic air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/249-dometic-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'dometic ac'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/249-dometic-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'dometic a/c'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruisair'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruiseair'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruisair air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruisair ac'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruisair a/c'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/185-cruisair-by-dometic',
                                'type' => 'exact',
                                'keyphrase' => 'cruisair air conditioner'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/34-zimar-zincs-anodes',
                                'type' => 'exact',
                                'keyphrase' => 'zimar'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/34-zimar-zincs-anodes',
                                'type' => 'exact',
                                'keyphrase' => 'zimar zinc'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/68-fusion-marine-sound-systems',
                                'type' => 'exact',
                                'keyphrase' => 'fusion'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/68-fusion-marine-sound-systems',
                                'type' => 'exact',
                                'keyphrase' => 'fusion radio'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/280-cummins-onan-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'onan'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/280-cummins-onan-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'onan generator'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/280-cummins-onan-marine-generators',
                                'type' => 'exact',
                                'keyphrase' => 'onan marine'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/176-lewmar-anchors',
                                'type' => 'exact',
                                'keyphrase' => 'lewmar'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/176-lewmar-anchors',
                                'type' => 'exact',
                                'keyphrase' => 'lewmar anchor'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/313-lewmar-windlasses',
                                'type' => 'exact',
                                'keyphrase' => 'lewmar windlass'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/270-seabobs-for-sale-prices',
                                'type' => 'exact',
                                'keyphrase' => 'seabob'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/270-seabobs-for-sale-prices',
                                'type' => 'exact',
                                'keyphrase' => 'sea bob'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/117-shurflo-marine-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'shurflo'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/117-shurflo-marine-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'shurflo pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/220-simrad-marine-electronics',
                                'type' => 'exact',
                                'keyphrase' => 'simrad'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/13-garmin-marine-electronics',
                                'type' => 'exact',
                                'keyphrase' => 'garmin'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/15-furuno',
                                'type' => 'exact',
                                'keyphrase' => 'furuno'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/400-lowrance-marine-electronics',
                                'type' => 'exact',
                                'keyphrase' => 'lowrance'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/24-pyi-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'pyi'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/24-pyi-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'pyi shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/62-jl-audio-marine-sound-systems',
                                'type' => 'exact',
                                'keyphrase' => 'jl audio'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/391-webasto-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'webasto'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/391-webasto-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'webasto air conditioner'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/391-webasto-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'webasto air conditioning'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/391-webasto-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'webasto ac'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/391-webasto-marine-air-conditioners',
                                'type' => 'exact',
                                'keyphrase' => 'webasto a/c'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/247-durabrite-lights',
                                'type' => 'exact',
                                'keyphrase' => 'durabrite'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/247-durabrite-lights',
                                'type' => 'exact',
                                'keyphrase' => 'durabrite light'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/247-durabrite-lights',
                                'type' => 'exact',
                                'keyphrase' => 'durabrite flood light'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/247-durabrite-lights',
                                'type' => 'exact',
                                'keyphrase' => 'durabrite floodlights'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'exact',
                                'keyphrase' => 'duramax'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/136-johnson-duramax-cutless-bearings',
                                'type' => 'exact',
                                'keyphrase' => 'duramax cutless bearing'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/23-tides-marine-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'tides marine'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/23-tides-marine-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'tidesmarine'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/23-tides-marine-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'tides shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/23-tides-marine-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'tides marine shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/23-tides-marine-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'tidesmarine shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/364-raritan-marine-toilets',
                                'type' => 'exact',
                                'keyphrase' => 'raritan'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/364-raritan-marine-toilets',
                                'type' => 'exact',
                                'keyphrase' => 'raritan toilet'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/289-frigibar-refrigerators-freezers-salty-dogs-deck-boxes',
                                'type' => 'exact',
                                'keyphrase' => 'frigibar'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/116-jabsco-marine-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'jabsco'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/116-jabsco-marine-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'jabsco pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/121-march-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'march'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/121-march-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'march pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/233-brownies-hookah-diving-systems',
                                'type' => 'exact',
                                'keyphrase' => 'brownie'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/233-brownies-hookah-diving-systems',
                                'type' => 'exact',
                                'keyphrase' => 'brownie hookah'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/233-brownies-hookah-diving-systems',
                                'type' => 'exact',
                                'keyphrase' => 'brownie diving hookah'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/269-headhunter-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'headhunter'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/269-headhunter-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'headhunter pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/269-headhunter-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'head hunter'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/269-headhunter-pumps',
                                'type' => 'exact',
                                'keyphrase' => 'head hunter pump'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/265-horizon-reverse-osmosis-hro-watermakers',
                                'type' => 'exact',
                                'keyphrase' => 'hro'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/265-horizon-reverse-osmosis-hro-watermakers',
                                'type' => 'exact',
                                'keyphrase' => 'hro watermaker'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/24-pyi-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'pss'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/24-pyi-shaft-seals',
                                'type' => 'exact',
                                'keyphrase' => 'pss shaft seal'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/14-raymarine',
                                'type' => 'exact',
                                'keyphrase' => 'raymarine'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/319-marine-internet-connectivity-equipment',
                                'type' => 'exact',
                                'keyphrase' => 'wave wifi'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/178-fortress-anchors',
                                'type' => 'exact',
                                'keyphrase' => 'fortress'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/178-fortress-anchors',
                                'type' => 'exact',
                                'keyphrase' => 'fortress anchor'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/8-lumitec',
                                'type' => 'exact',
                                'keyphrase' => 'lumitec'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/8-lumitec',
                                'type' => 'exact',
                                'keyphrase' => 'lumitec light'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/271-revere-life-rafts-for-sale',
                                'type' => 'exact',
                                'keyphrase' => 'revere'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/271-revere-life-rafts-for-sale',
                                'type' => 'exact',
                                'keyphrase' => 'revere life raft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/271-revere-life-rafts-for-sale',
                                'type' => 'exact',
                                'keyphrase' => 'revere liferaft'
                        ),
                        array(
                                'url' => 'https://citimarinestore.com/en/39-lumitec-underwater-lights',
                                'type' => 'exact',
                                'keyphrase' => 'lumitec underwater light'
                        )
        );
    }
}
