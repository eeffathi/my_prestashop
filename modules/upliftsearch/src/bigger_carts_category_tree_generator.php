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

use function ICanBoogie\array_insert;

if (!defined('_PS_VERSION_')) {
    exit();
}

class BiggerCartsCategoryTreeGenerator
{
    public function __construct()
    {
    }

    public static function getCategoryTreeToIndex($db)
    {
        // Get a mapping from category id to array of parent ids till top category id
        $category_parent_id_hierarchy = BiggerCartsCategoryTreeGenerator::getCategoryIdTree($db);

        // Get a map from category id to array of category names
        $category_id_to_parent_name_hierarchy = BiggerCartsCategoryTreeGenerator::getCategoryNameTree(
            $db,
            $category_parent_id_hierarchy
        );
        return array(
            'id_hierarchy' => $category_parent_id_hierarchy,
            'name_hierarchy' => $category_id_to_parent_name_hierarchy
        );
    }

    private static function getCategoryNameTree($db, $category_parent_id_hierarchy)
    {
        $category_to_parent_name_map = BiggerCartsCategoryTreeGenerator::getCategoryIdToNameMap($db);
        $category_id_to_parent_name_hierarchy = array();
        foreach ($category_parent_id_hierarchy as $category_id => $parent_id_hierarchy) {
            $category_id_to_parent_name_hierarchy[$category_id] = array();
            foreach ($parent_id_hierarchy as $index => $parent_id) {
                if (isset($category_to_parent_name_map[$parent_id])) {
                    array_push($category_id_to_parent_name_hierarchy[$category_id], $category_to_parent_name_map[$parent_id]);
                }
            }
        }
        return $category_id_to_parent_name_hierarchy;
    }

    private static function getCategoryIdToNameMap($db)
    {
        $sql = 'SELECT id_category, id_lang, name FROM ' . _DB_PREFIX_ . 'category_lang';
        $db_output = Db::getInstance()->executeS($sql, true, false);
        $category_to_parent_name_map = array();
        foreach ($db_output as $category_data) {
            $category_id = $category_data['id_category'];
            $language_id = $category_data['id_lang'];
            if (!array_key_exists($category_id, $category_to_parent_name_map)) {
                $category_to_parent_name_map[$category_id] = array();
            }
            $category_to_parent_name_map[$category_id][$language_id] = $category_data['name'];
        }
        return $category_to_parent_name_map;
    }

    private static function getCategoryIdTree($db)
    {
        $category_to_parent_id_map = BiggerCartsCategoryTreeGenerator::getCategoryToParentIdMap($db);
        $category_id_to_parent_id_hierarchy = array();
        // Now each non-indexed product is processed one by one, langage by langage
        foreach ($category_to_parent_id_map as $category_id => $parent_id) {
            $category_id_to_parent_id_hierarchy[$category_id] = BiggerCartsCategoryTreeGenerator::getParentHierarchy(
                $category_id,
                $category_to_parent_id_map,
                $category_id_to_parent_id_hierarchy
            );
        }
        return $category_id_to_parent_id_hierarchy;
    }

    private static function getCategoryToParentIdMap($db)
    {
        $sql = 'SELECT id_category, id_parent FROM ' . _DB_PREFIX_ . 'category';
        $db_output = Db::getInstance()->executeS($sql, true, false);
        $category_to_parent_id_map = array();
        foreach ($db_output as $category_data) {
            $category_to_parent_id_map[$category_data['id_category']] = $category_data['id_parent'];
        }
        return $category_to_parent_id_map;
    }

    private static function getParentHierarchy(
        $category_id,
        $category_to_parent_id_map,
        &$category_id_to_parent_id_hierarchy
    ) {
        // Base case
        if ($category_id == 0) {
            return array();
        }
        // If category hierarchy is not cached, find it recursively and cache it.
        if (!array_key_exists($category_id, $category_id_to_parent_id_hierarchy)) {
            $parent_id = $category_to_parent_id_map[$category_id];
            $category_hierarchy = BiggerCartsCategoryTreeGenerator::getParentHierarchy(
                $parent_id,
                $category_to_parent_id_map,
                $category_id_to_parent_id_hierarchy
            );
            array_unshift($category_hierarchy, $category_id);
            $category_id_to_parent_id_hierarchy[$category_id] = $category_hierarchy;
        }
        // Return category hierarchy from cache.
        return $category_id_to_parent_id_hierarchy[$category_id];
    }
}
