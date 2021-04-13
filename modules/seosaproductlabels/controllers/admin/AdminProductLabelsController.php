<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    SeoSA <885588@bk.ru>
 * @copyright 2012-2021 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class AdminProductLabelsController
 */
class AdminProductLabelsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'seosaproductlabels';
        $this->lang = true;
        $this->identifier = 'id_product_label';
        $this->className = 'ProductLabelSeo';
        $this->bootstrap = true;
        $this->display = 'list';
        $this->imageType = 'png';
        $this->fieldImageSettings = array(
            'name' => 'image',
            'dir' => 'seosaproductlabels'
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Would you like to delete the selected items?')
            )
        );

        parent::__construct();

        $this->_select .= ' a.`id_product_label`, a.`name`, b.`url`, shop.`active`, 
        CONCAT_WS("|", shop.`date_from`, shop.`date_to`) AS date ';

        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'seosaproductlabels_shop` shop 
        ON a.id_product_label = shop.id_product_label';

        $this->_where .= 'AND shop.id_shop = '.(int)$this->context->shop->id.' 
        AND b.id_shop = '.(int)$this->context->shop->id;

        $this->fields_list = array(
            'id_product_label' => array(
                'title' => $this->l('ID'),
                'width' => 20
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'search' => true,
                'filter_key' => 'a!name'
            ),
            'text' => array(
                'title' => $this->l('Text'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'callback' => 'getTextForList'
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'align' => 'center',
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'type' => 'image_custom',
                'callback' => 'getImageForList'
            ),
            'url' => array(
                'title' => $this->l('URL'),
                'search' => true,
                'filter_key' => 'b!url'
            ),
            'date' => array(
                'title' => $this->l('Date'),
                'callback' => 'getDateForList'
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'class' => 'fixed-width-sm',
                'active' => 'status',
                'align' => 'center',
                'type' => 'bool',
                'orderby' => false,
                'search' => true,
                'filter_key' => 'a!active'
            )
        );
    }

    public static function getTextForList($echo, $label)
    {
        if ($label['label_type'] == 'image') {
            return '--';
        }
        return $echo;
    }

    public static function getImageForList($echo, $label)
    {
        if ($label['label_type'] == 'text') {
            return '--';
        }
        return $echo;
    }

    public function getDateForList($echo, $label)
    {
        $date = strtotime(date('Y-m-d'));
        if (strtotime($label['date_from']) > $date || ($date > strtotime($label['date_to'])
                && $label['date_to'] != '0000-00-00')) {
            $class = 'warning-date';
        } elseif (strtotime($label['date_from']) <= $date && ($date <= strtotime($label['date_to'])
                && $label['date_to'] != '0000-00-00')) {
            $class = 'normal-date';
        } else {
            $class = 'no-date';
        }
        return '<span class="' . $class . '">' . $echo . '</span>';
    }

    public function renderForm()
    {
        $show_categories = ProductLabelSeo::getCategories($this->object->id);

        $selected_categories = array();
        foreach ($show_categories as $show_category) {
            $selected_categories[] = $show_category;
        }

        $all_categories = Category::getCategories(
            $this->context->language->id,
            false,
            false,
            '',
            'ORDER BY c.id_category ASC'
        );
        $all_active_categories = Category::getCategories(
            $this->context->language->id,
            true,
            false,
            '',
            'ORDER BY c.id_category ASC'
        );

        $disabled_categories = array();
        foreach ($all_categories as $row_all) {
            $break = false;
            foreach ($all_active_categories as $row_active) {
                if ($row_all['id_category'] == $row_active['id_category']) {
                    $break = true;
                    break;
                }
            }
            if ($break) {
                continue;
            }
            $disabled_categories[] = $row_all['id_category'];
        }

        $this->fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Product label'),
                'icon' => 'icon-tags'
            ),
            'input' => array(
                array(
                    'type' => ((float)_PS_VERSION_ < 1.6 ? 'radio' : 'switch_active'),
                    'label' => $this->l('Enabled:'),
                    'name' => 'active',
                    'col' => '3',
                    'class' => 't',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'hint' => $this->l('Will be hidden on all products if not enabled:'),
                ),
                array(
                    'type' => 'text_name',
                    'label' => $this->l('Name:'),
                    'name' => 'name',
                    'col' => '3',
                    'required' => true,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                ),
                array(
                    'type' => 'free2',
                    'name' => 'tabs_for',
                    'col' => '9',
                    'label' => $this->l('For:'),
                    'hint' => $this->l('Only one!')
                ),
                array(
                    'type' => ((float)_PS_VERSION_ < 1.6 ? 'radio' : 'switch_include_category_product'),
                    'label' => $this->l('Display in the child category:'),
                    'name' => 'include_category_product',
                    'col' => '3',
                    'class' => 't',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'include_category_product_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'include_category_product_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'hint' => $this->l(
                        'If yes: It will appear on all products where there is at least least one selected
                        category. If no: will be displayed only in the products for which the default category'
                    ),
                    'desc' => $this->l('Category only')
                ),
                array(
                    'type' => 'exlc_incl_select2',
                    'label' => $this->l('Excluded product:'),
                    'name' => 'excluded',
                    'col' => '3',
                    'hint' => $this->l('Select products for which the sticker will not be displayed'),
                ),
                array(
                    'type' => 'exlc_incl_select2',
                    'label' => $this->l('Included product:'),
                    'name' => 'included',
                    'col' => '3',
                    'hint' => $this->l('Select the products for which the sticker will be displayed'),
                ),
                array(
                    'type' => 'position',
                    'label' => $this->l('Position:'),
                    'name' => 'position',
                    'col' => '3',
                ),
                array(
                    'type' => 'group2',
                    'label' => $this->l('Group access:'),
                    'name' => 'groupBox',
                    'col' => '3',
                    'values' => Group::getGroups(Context::getContext()->language->id),
                    'hint' => $this->l('Mark the groups to which the logo will be displayed.')
                ),
                array(
                    'type' => 'date_from_to',
                    'label' => $this->l('Valid:'),
                    'name' => '',
                    'col' => '3',
                    'name_from' => 'date_from',
                    'name_to' => 'date_to'
                ),
                array(
                    'type' => 'text_mini_price',
                    'label' => $this->l('Minimum price:'),
                    'name' => 'mini_price',
                    'col' => '3',
                ),
                array(
                    'type' => 'text_max_price',
                    'label' => $this->l('Maximum price:'),
                    'name' => 'max_price',
                    'col' => '3',
                ),
                array(
                    'type' => 'button',
                    'name' => 'label_type',
                    'col' => '3',
                    'label' => $this->l('Label:'),
                    'values' => array(
                        array(
                            'value' => 'image',
                            'id' => 'select_type_image',
                            'label' => $this->l('Image')
                        ),
                        array(
                            'value' => 'text',
                            'id' => 'select_type_text',
                            'label' => $this->l('Text')
                        )
                    )
                ),
                array(
                    'type' => 'file_style',
                    'label' => $this->l('Image:'),
                    'required' => false,
                    'lang' => true,
                    'name' => 'image',
                    'col' => '3',
                    'hint' => $this->l('Upload a image from your computer.'),
                ),
                array(
                    'type' => 'text_index_image_css',
                    'label' => $this->l('CSS for image in home page:'),
                    'name' => 'index_image_css',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Example') . ': width: 80px; heigth: 80px;',
                ),
                array(
                    'type' => 'text_product_image_css',
                    'label' => $this->l('CSS for image in product page:'),
                    'name' => 'product_image_css',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Example') . ': width: 80px; heigth: 80px;',
                ),
                array(
                    'type' => 'text_category_image_css',
                    'label' => $this->l('CSS for image in category page:'),
                    'name' => 'category_image_css',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Example') . ': width: 80px; heigth: 80px;',
                ),
                array(
                    'type' => 'text_text',
                    'label' => $this->l('Text:'),
                    'name' => 'text',
                    'col' => '3',
                    'lang' => true,
                    'required' => false,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                ),
                array(
                    'type' => 'text_text_css',
                    'label' => $this->l('CSS for text:'),
                    'name' => 'text_css',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Example:') . ': color:red; font-size: 1.8em;',
                ),
                array(
                    'type' => 'text_url',
                    'label' => $this->l('URL:'),
                    'name' => 'url',
                    'col' => '3',
                    'lang' => true,
                    'required' => false,
                    'hint' => $this->l('Wrap label in link tag if not empty'),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Hint:'),
                    'name' => 'hint',
                    'col' => '3',
                    'lang' => true,
                    'required' => false,
                    'autoload_rte' => true,
                    'hint' => $this->l('Invalid characters:') . ' <>;=#{}',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hint text color:'),
                    'name' => 'hint_text_color',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Default: white(#ffffff)')
                ),
                array(
                    'type' => 'color_hint_background',
                    'label' => $this->l('Hint background:'),
                    'name' => 'hint_background',
                    'col' => '3',
                    'required' => false,
                    'hint' => $this->l('Default: black(#000000)')
                ),
                array(
                    'type' => 'text_hint_opacity',
                    'label' => $this->l('Hint opacity:'),
                    'name' => 'hint_opacity',
                    'col' => '3',
                    'required' => false,
                    'default_value' => 1,
                    'hint' => $this->l('Default: not transparent(1)')
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $this->context->smarty->assign(array(
            'categories' => Category::getSimpleCategories($this->context->language->id),
            'manufacturers' => Manufacturer::getManufacturers(true),
            'featurers' => Feature::getFeatures($this->context->language->id, true),
            'suppliers' => Supplier::getSuppliers(),
            'current_lang' => $this->context->language->id,
//            'excluded' => ProductLabel::getExcludedProducts($this->context->language->id),
        ));

        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/jquery.fileStyle.css');
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/select2.min.css');
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/admin.css');
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/ion.rangeSlider.css');
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/ion.rangeSlider.skinNice.css');
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/tabs_style.css');
        $this->context->controller->addJS($this->module->getPathURI() . '/views/js/jquery.fileStyle.js');
        $this->context->controller->addJS($this->module->getPathURI() . '/views/js/select2.full.min.js');
        $this->context->controller->addJS($this->module->getPathURI() . '/views/js/form.js');
        $this->context->controller->addJS($this->module->getPathURI() . '/views/js/ion.rangeSlider.min.js');

        // ps1.5
        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/admin15.css');
        }

        return ($this->module->multishop_warning ? $this->module->multishop_warning : parent::renderForm());
    }

    /**
     * @param ObjectModel $obj
     *
     * @return array
     */
    public function getFieldsValue($obj)
    {
        $default_image_css = 'width:80px; height:80px;';
        $image_css = ProductLabelSeo::getImageCSS($obj->id);
        $fields_value = parent::getFieldsValue($obj);
        $fields_value['image'] = array();
        $fields_value['date_from'] = $obj->date_from;
        $fields_value['date_to'] = $obj->date_to;

        foreach ($this->getLanguages() as $l) {
            $fields_value['image'][$l['id_lang']] = '';
        }

        $fields_value['index_image_css'] = $image_css['index'] ? $image_css['index'] : $default_image_css;
        $fields_value['product_image_css'] = $image_css['product'] ? $image_css['product'] : $default_image_css;
        $fields_value['category_image_css'] = $image_css['category'] ? $image_css['category'] : $default_image_css;
        $fields_value['tabs_for'] = $this->getTabsFor();

        // support 1.3.92
        foreach ($fields_value['text'] as $text) {
            if ($text) {
                $fields_value['label_type'] = 'text';
            };
        }

        foreach ($this->getLanguages() as $l) {
            $image_link = $this->getImageDir($obj->id, $l['id_lang']);
            $fields_value['image'][$l['id_lang']] = null;
            if (file_exists($image_link)) {
                $fields_value['image'][$l['id_lang']] = ImageManager::thumbnail(
                    $image_link,
                    $this->table . '_' . (int)$obj->id . '_' . $l['id_lang'] . '.' . $this->imageType,
                    350,
                    $this->imageType,
                    true,
                    true
                );
            }
        }

        $groups = Group::getGroups($this->context->language->id);

        $selected_groups = $obj->groups ? explode(',', $obj->groups) : array();

        foreach ($groups as $group) {
            $fields_value['groupBox_'.$group['id_group']] = in_array($group['id_group'], $selected_groups);
        }

        return $fields_value;
    }

    private function getCategoriesTab()
    {
        $show_categories = ProductLabelSeo::getCategories($this->object->id);

        $selected_categories = array();
        foreach ($show_categories as $show_category) {
            $selected_categories[] = $show_category;
        }

        $all_categories = Category::getCategories(
            $this->context->language->id,
            false,
            false,
            '',
            'ORDER BY c.id_category ASC'
        );
        $all_active_categories = Category::getCategories(
            $this->context->language->id,
            true,
            false,
            '',
            'ORDER BY c.id_category ASC'
        );

        $disabled_categories = array();
        foreach ($all_categories as $row_all) {
            $break = false;
            foreach ($all_active_categories as $row_active) {
                if ($row_all['id_category'] == $row_active['id_category']) {
                    $break = true;
                    break;
                }
            }
            if ($break) {
                continue;
            }
            $disabled_categories[] = $row_all['id_category'];
        }

        $params = array(
            'type' => 'categories',
            'label' => $this->l('Categories for views:'),
            'name' => 'categories',
            'tree' => array(
                'id' => 'tree_categories_1',
                'use_checkbox' => true,
                'selected_categories' => $selected_categories,
                'disabled_categories' => $disabled_categories,
                'use_search' => true
            )
        );
        $tree = new HelperTreeCategories(
            $params['tree']['id'],
            isset($params['tree']['title']) ? $params['tree']['title'] : null
        );

        if (isset($params['name'])) {
            $tree->setInputName($params['name']);
        }

        if (isset($params['tree']['selected_categories'])) {
            $tree->setSelectedCategories($params['tree']['selected_categories']);
        }

        if (isset($params['tree']['disabled_categories'])) {
            $tree->setDisabledCategories($params['tree']['disabled_categories']);
        }

        if (isset($params['tree']['root_category'])) {
            $tree->setRootCategory($params['tree']['root_category']);
        }

        if (isset($params['tree']['use_search'])) {
            $tree->setUseSearch($params['tree']['use_search']);
        }

        if (isset($params['tree']['use_checkbox'])) {
            $tree->setUseCheckBox($params['tree']['use_checkbox']);
        }

        if (isset($params['tree']['set_data'])) {
            $tree->setData($params['tree']['set_data']);
        }

        $html = $tree->render();

        $order = array('Collapse All', 'Expand All', 'Check All', 'Uncheck All');
        $replace = array(
            $this->l('Collapse All'),
            $this->l('Expand All'),
            $this->l('Check All'),
            $this->l('Uncheck All')
        );

        $html = str_replace($order, $replace, $html);

        return $html;
    }

    public function getManufacturersTab()
    {
        $this->context->smarty->assign(
            array(
                'input' => array(
                    'name' => 'manufacturer',
                    'multiple' => true,
                    'options' => array('query' => Manufacturer::getManufacturers(true),
                        'id' => 'id_manufacturer',
                        'name' => 'name')
                )
            )
        );

        $fields_value = array();
        $fields_value['manufacturer'] = ProductLabelSeo::getManufacturer((int)Tools::getValue('id_product_label'));

        $this->context->smarty->assign('fields_value', $fields_value);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/select2.tpl');
    }

    public function getFeatureValue()
    {

        $features = Feature::getFeatures((int)$this->context->language->id);
        $feature_all = array();

        foreach ($features as &$feature) {
            $feature['values'] = FeatureValue::getFeatureValuesWithLang(
                (int)$this->context->language->id,
                $feature['id_feature'],
                true
            );
            foreach ($feature['values'] as &$features_value) {
                array_push($feature_all, $features_value);
            }
        }

        $this->context->smarty->assign(
            array(
                'input' => array(
                    'name' => 'feature',
                    'multiple' => true,
                    'options' => array(
                        'query' => $feature_all,
                        'id' => 'id_feature_value',
                        'name' => 'value'
                    )
                )
            )
        );

        $fields_value = array();
        $fields_value['feature'] = ProductLabelSeo::getFeaturer((int)Tools::getValue('id_product_label'));
        $this->context->smarty->assign('fields_value', $fields_value);

        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/select2.tpl');
    }

    private function getSuppliersTab()
    {
        $this->context->smarty->assign(
            array(
                'input' => array(
                    'name' => 'supplier',
                    'multiple' => true,
                    'options' => array('query' => Supplier::getSuppliers(),
                        'id' => 'id_supplier',
                        'name' => 'name'
                    )
                )
            )
        );

        $fields_value = array();
        $fields_value['supplier'] = ProductLabelSeo::getSupplier((int)Tools::getValue('id_product_label'));

        $this->context->smarty->assign('fields_value', $fields_value);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/select2.tpl');
    }

    private function getQuantityTab()
    {
        $this->context->smarty->assign(
            array(
                'name' => 'quantity',
                'options' => array(
                    'id' => 'quantity',
                    'name' => 'name'),
            ),
            array(
                'name' => 'quantity_max',
                'options' => array(
                    'id' => 'quantity_max',
                    'name' => 'name'),
            )
        );

        $id_product_label = (int)Tools::getValue('id_product_label');
        $fields_value = ProductLabelSeo::getProductQuantity($id_product_label);
        $fields_value_do = ProductLabelSeo::getProductQuantitymax($id_product_label);
        $this->context->smarty->assign('fields_value_do', $fields_value_do);
        $this->context->smarty->assign('fields_value', $fields_value);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/input.tpl');
    }

    private function getStatusTab()
    {
        $id_product_label = (int)Tools::getValue('id_product_label');
        if ($id_product_label) {
            $default_value = ProductLabelSeo::getProductStatus($id_product_label);
        } else {
            $default_value = 1;
        }

        $buttons = array(
            1 => $this->l('New'),
            2 => $this->l('Sale')
        );

        $this->context->smarty->assign(
            array('buttons' => $buttons, 'name' => 'product_status', 'default_value' => $default_value)
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/tabs_for/button_group.tpl'
        );
    }

    private function getCartRulesTab()
    {
        $this->context->smarty->assign(
            array(
                'input' => array(
                    'name' => 'cart_rules',
                    'options' => array(
                        'id' => 'id_cart_rule',
                        'name' => 'name',
                        'query' => ProductLabelSeo::getCartRules()
                    ),
                    'multiple' => true,
                    'id' => 'select_cart_rules'
                )
            )
        );

        $fields_value = array();
        $fields_value['cart_rules'] =
            ProductLabelSeo::getCartRulesForProductLabel((int)Tools::getValue('id_product_label'));

        $this->context->smarty->assign('fields_value', $fields_value);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/swap.tpl');
    }

    private function getConditionTab()
    {
        $id_product_label = (int)Tools::getValue('id_product_label');
        if ($id_product_label) {
            $default_value = ProductLabelSeo::getProductCondition($id_product_label);
        } else {
            $default_value = 1;
        }

        $buttons = array(
            'new' => $this->l('New'),
            'used' => $this->l('Used'),
            'refurbished' => $this->l('Refurbished')
        );

        $this->context->smarty->assign(
            array('buttons' => $buttons, 'name' => 'product_condition', 'default_value' => $default_value)
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->module->name . '/views/templates/admin/tabs_for/button_group.tpl'
        );
    }

    private function getTabsFor()
    {
        $tabs = array(
            'categories' => array(
                'label' => $this->l('Categories'),
                'bind' => 1,
                'content' => $this->getCategoriesTab()
            ),
            'manufacturers' => array(
                'label' => $this->l('Manufacturers'),
                'bind' => 0,
                'content' => $this->getManufacturersTab()
            ),
            'feature' => array(
                'label' => $this->l('Feature'),
                'bind' => 8,
                'content' => $this->getFeatureValue()
            ),
            'suppliers' => array(
                'label' => $this->l('Suppliers'),
                'bind' => 2,
                'content' => $this->getSuppliersTab()
            ),
            'status' => array(
                'label' => $this->l('Status'),
                'bind' => 3,
                'content' => $this->getStatusTab()
            ),
            'cart_rules' => array(
                'label' => $this->l('Cart rules'),
                'bind' => 4,
                'content' => $this->getCartRulesTab()
            ),
            'condition' => array(
                'label' => $this->l('Condition'),
                'bind' => 5,
                'content' => $this->getConditionTab()
            ),
            'bestsellers' => array(
                'label' => $this->l('Bestsellers'),
                'bind' => 6,
                'content' => $this->l('The sticker will be shown in the products from the bestseller category')
            ),
            'quantity' => array(
                'label' => $this->l('Quantity'),
                'bind' => 7,
                'content' => $this->getQuantityTab()
            )
        );

        $this->context->smarty->assign(
            array(
                'tabs_for' => $tabs,
                'product_label' => new ProductLabelSeo(Tools::getValue('id_product_label'))
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name
            . '/views/templates/admin/tabs_for/tabs.tpl');
    }

    public function processSave()
    {
        if (Tools::getValue('label_type') == 'image') {
            foreach (${'_POST'} as $key => &$post) {
                if (strpos($key, 'text_') === 0) {
                    $post = '';
                }
            }
        }

        if (strtotime(Tools::getValue('date_from')) > strtotime(Tools::getValue('date_to'))) {
            $this->errors[] = $this->module->l('The sticker display can not end before it starts..');
        }

        $categories = Tools::getValue('categories', array());
        $manufacturer = Tools::getValue('manufacturer');
        $features = Tools::getValue('feature');
        $supplier = Tools::getValue('supplier');
        $cart_rules = Tools::getValue('cart_rules_selected');
        $select_for = Tools::getValue('select_for');
        $excluded = Tools::getValue('excluded');
        $included = Tools::getValue('included');
        $groups = Tools::getValue('groupBox');

        if ($this->object = parent::processSave()) {
            ProductLabelSeo::deleteManufacturer($this->object->id);
            ProductLabelSeo::deleteSupplier($this->object->id);
            ProductLabelSeo::deleteCartRules($this->object->id);
            ProductLabelSeo::deleteCategories($this->object->id);
            ProductLabelSeo::deleteFeaturer($this->object->id);
            $this->object->product_status = 0;
            $this->object->product_condition = '';
            $this->object->quantity = '';
            $this->object->quantity_max = '';
            $this->object->bestsellers = 0;

            if ($select_for == ProductLabelLocation::_FOR_CATEGORY_) {
                ProductLabelSeo::setCategories(
                    $this->object->id,
                    array_map('intval', $categories),
                    false
                );
            } elseif ($select_for == ProductLabelLocation::_FOR_MANUFACTURER_) {
                ProductLabelSeo::setManufacturer($this->object->id, $manufacturer, false);
            } elseif ($select_for == ProductLabelLocation::_FOR_SUPPLIER_) {
                ProductLabelSeo::setSupplier($this->object->id, $supplier, false);
            } elseif ($select_for == ProductLabelLocation::_FOR_STATUS_) {
                if (in_array($product_status = (int)Tools::getValue('product_status'), array(1, 2))) {
                    $this->object->product_status = $product_status;
                }
            } elseif ($select_for == ProductLabelLocation::_FOR_CART_RULES_) {
                ProductLabelSeo::setCartRules($this->object->id, $cart_rules, false);
            } elseif ($select_for == ProductLabelLocation::_FOR_CONDITION_) {
                if (in_array(
                    $product_condition = Tools::getValue('product_condition'),
                    array('new', 'used', 'refurbished')
                )) {
                    $this->object->product_condition = $product_condition;
                }
            } elseif ($select_for == ProductLabelLocation::_FOR_BESTSELLERS_) {
                $this->object->bestsellers = 1;
            } elseif ($select_for == ProductLabelLocation::_FOR_QUANTITY_) {
                $this->object->quantity = Tools::getValue('quantity', '');
                $this->object->quantity_max = Tools::getValue('quantity_max', '');
            } elseif ($select_for == ProductLabelLocation::_FOR_FEATURE_) {
                ProductLabelSeo::setFeatures($this->object->id, $features, false);
            }

            $this->object->groups = $groups ? implode(',', $groups) : null;

            try {
                $this->object->save();
            } catch (Exception $e) {
                $this->context->controller->errors[] = $e->getMessage();
            }

            ProductLabelSeo::saveExcluded($this->object->id, $excluded);
            ProductLabelSeo::saveIncluded($this->object->id, $included);

            $this->createImageDir($this->object->id);
            foreach ($this->getLanguages() as $l) {
                $input = 'image_' . (int)$l['id_lang'];
                if (_PS_VERSION_ < 1.6) {
                    $file = null;
                    if (isset($_FILES[$input]['name']) &&
                        !empty($_FILES[$input]['name']) &&
                        !empty($_FILES[$input]['tmp_name'])) {
                        $file['rename'] = uniqid() . Tools::strtolower(Tools::substr($_FILES[$input]['name'], -5));
                        $file['content'] = Tools::file_get_contents($_FILES[$input]['tmp_name']);
                        $file['tmp_name'] = $_FILES[$input]['tmp_name'];
                        $file['name'] = $_FILES[$input]['name'];
                        $file['mime'] = $_FILES[$input]['type'];
                        $file['error'] = $_FILES[$input]['error'];
                        $file['size'] = $_FILES[$input]['size'];
                    }
                } else {
                    $file = Tools::fileAttachment($input);
                }

                if ($file && $this->checkImage($file['tmp_name'])) {
                    ImageManager::resize($file['tmp_name'], $this->getImageDir($this->object->id, $l['id_lang']));
                } else {
                    $this->errors[] = $this->l('For language ' . $l['name'] . ' image not found!');
                }
            }
        }
        /** for validation */
        unset($post);
        return false;
    }

    public function getUploadDir()
    {
        return _PS_IMG_DIR_ . 'seosaproductlabels/';
    }

    public function createImageDir($id)
    {
        $path = $this->getUploadDir() . $id . '/';
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    public function getImageDir($id, $id_lang)
    {
        return $this->getUploadDir() . $id . '/' . $id_lang . '.' . $this->imageType;
    }

    public function checkImage($tmp_name, $type = array(IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG))
    {
        if (function_exists('exif_imagetype')) {
            return in_array(exif_imagetype($tmp_name), $type);
        }

        $size = getimagesize($tmp_name);
        return in_array($size[2], $type);
    }

    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
        foreach ($this->_list as &$item) {
            $item['image'] = ImageManager::thumbnail(
                $this->getImageDir($item[$this->identifier], $this->context->language->id),
                $this->table . '_' . (int)$item[$this->identifier] . '_' . $this->context->language->id . '.'
                . $this->imageType,
                50,
                $this->imageType,
                true,
                true
            );
        }
    }

    public function renderList()
    {
        $this->context->controller->addCSS($this->module->getPathURI() . '/views/css/admin.css');

        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addCSS($this->module->getPathURI() . 'views/css/admin-theme.css');
        }

        $this->tpl['link_on_tab_module'] = $this->module->getDocumentationLinks();
        return $this->module->getDocumentationLinks().
            ($this->module->multishop_warning ? $this->module->multishop_warning : parent::renderList());
    }

    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if ($class === null || $class == 'AdminTab') {
            $class = Tools::substr(get_class($this), 0, -10);
        } elseif (Tools::strtolower(Tools::substr($class, -10)) == 'controller') {
            /** classname has changed, from AdminXXX to AdminXXXController,
             * so we remove 10 characters and we keep same keys
             */
            $class = Tools::substr($class, 0, -10);
        }
        return Translate::getAdminTranslation($string, $class, $addslashes, $htmlentities);
    }
}
