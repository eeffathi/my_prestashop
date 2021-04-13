<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetCategoryTree extends WidgetBase
{
    public function getName()
    {
        return 'category-tree';
    }

    public function getTitle()
    {
        return __('Category Tree', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-toggle';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_category_tree',
            array(
                'label' => __('Category Tree', 'elementor'),
            )
        );

        $this->addControl(
            'root_category',
            array(
                'label' => __('Category Root', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => array(
                    '0' => __('Home Category', 'elementor'),
                    '1' => __('Current Category', 'elementor'),
                    '2' => __('Parent Category', 'elementor'),
                    '3' => __('Current Category', 'elementor') . ' / ' . __('Parent Category', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'max_depth',
            array(
                'label' => __('Maximum Depth', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 4,
            )
        );

        $this->addControl(
            'sort',
            array(
                'label' => __('Sort', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => array(
                    '0' => __('By Position', 'elementor'),
                    '1' => __('By Name', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'sort_way',
            array(
                'label' => __('Sort Order', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => array(
                    '0' => __('Ascending', 'elementor'),
                    '1' => __('Descending', 'elementor'),
                ),
            )
        );

        $this->endControlsSection();
    }

    private function _getCategories($category, &$settings)
    {
        $range = '';
        $maxdepth = $settings['max_depth'];
        if (\Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= ' . (int) $category->nleft . ' AND nright <= ' . (int) $category->nright;
        }

        $resultIds = array();
        $resultParents = array();
        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
            FROM `' . _DB_PREFIX_ . 'category` c
            INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . \Shop::addSqlRestrictionOnLang('cl') . ')
            INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
            WHERE (c.`active` = 1 OR c.`id_category` = ' . (int) \Configuration::get('PS_HOME_CATEGORY') . ')
            AND c.`id_category` != ' . (int) \Configuration::get('PS_ROOT_CATEGORY') . '
            ' . ((int) $maxdepth != 0 ? ' AND `level_depth` <= ' . (int) $maxdepth : '') . '
            ' . $range . '
            AND c.id_category IN (
                SELECT id_category
                FROM `' . _DB_PREFIX_ . 'category_group`
                WHERE `id_group` IN (' . pSQL(implode(', ', \Customer::getGroupsStatic((int) $this->context->customer->id))) . ')
            )
            ORDER BY `level_depth` ASC, ' . ($settings['sort'] ? 'cl.`name`' : 'cs.`position`') . ' ' . ($settings['sort_way'] ? 'DESC' : 'ASC')
        );
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        return $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        $children = array();

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }

        return array(
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'desc' => $desc,
            'children' => $children,
        );
    }

    public function setLastVisitedCategory()
    {
        if (method_exists($this->context->controller, 'getCategory') && ($category = $this->context->controller->getCategory())) {
            $this->context->cookie->last_visited_category = $category->id;
        } elseif (method_exists($this->context->controller, 'getProduct') && ($product = $this->context->controller->getProduct())) {
            if (!isset($this->context->cookie->last_visited_category) ||
                !\Product::idIsOnCategoryId($product->id, array(array('id_category' => $this->context->cookie->last_visited_category))) ||
                !\Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop)
            ) {
                $this->context->cookie->last_visited_category = (int) $product->id_category_default;
            }
        }
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }

        $settings = $this->getSettings();

        $this->setLastVisitedCategory();

        if ($settings['root_category'] && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
            $category = new \Category($this->context->cookie->last_visited_category, $this->context->language->id);

            if ($settings['root_category'] == 2 && !$category->is_root_category && $category->id_parent ||
                $settings['root_category'] == 3 && !$category->is_root_category && !$category->getSubCategories($category->id, true)
            ) {
                $category = new \Category($category->id_parent, $this->context->language->id);
            }
        } else {
            $category = new \Category((int) \Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
        }

        if (_CE_PS16_) {
            $tpl = 'blockcategories/blockcategories.tpl';
            $theme_tpl = _PS_THEME_DIR_ . 'modules/' . $tpl;
            $override = file_exists($theme_tpl);

            $this->context->smarty->assign(array(
                'isDhtml' => \Configuration::get('BLOCK_CATEG_DHTML'),
                'branche_tpl_path' => dirname($override ? $theme_tpl : _PS_MODULE_DIR_ . $tpl) . '/category-tree-branch.tpl',
                'currentCategory' => $category,
                'currentCategoryId' => $category->id,
                'blockCategTree' => $this->_getCategories($category, $settings),
            ));
        } else {
            $tpl = 'ps_categorytree/views/templates/hook/ps_categorytree.tpl';
            $theme_tpl = _PS_THEME_DIR_ . 'modules/' . $tpl;
            $override = file_exists($theme_tpl);

            $this->context->smarty->assign(array(
                'currentCategory' => $category->id,
                'categories' => $this->_getCategories($category, $settings),
            ));
        }

        echo $this->context->smarty->fetch($override ? $theme_tpl : _PS_MODULE_DIR_ . $tpl);
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = array(), $args = array())
    {
        $this->context = \Context::getContext();
        parent::__construct($data, $args);
    }
}
