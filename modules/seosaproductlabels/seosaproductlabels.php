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
 * @author    SeoSA    <885588@bk.ru>
 * @copyright 2012-2021 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once(_PS_MODULE_DIR_ . 'seosaproductlabels/classes/ProductLabelSeo.php');
require_once(_PS_MODULE_DIR_ . 'seosaproductlabels/classes/ProductLabelLocation.php');

if (!class_exists('TransModSPL')) {
    require_once(dirname(__FILE__) . '/classes/TransModSPL.php');
}

/**
 * Class SeosaProductLabels
 */
class SeosaProductLabels extends Module
{
    public $multishop_warning;
    private static $count = 0;

    public function __construct()
    {
        $this->name = 'seosaproductlabels';
        $this->tab = 'front_office_features';
        $this->version = '1.5.8';
        $this->bootstrap = 1;
        $this->author = 'SeoSA';
        $this->need_instance = '0';
        parent::__construct();
        $this->displayName = $this->l('Product labels');
        $this->description = $this->l('Add labels on your products in front office');
        $this->module_key = '869ac1ad46754ef23ddc0bca9cd6b991';

        $shop_context = Shop::getContext();
        if (Shop::isFeatureActive() && ($shop_context == Shop::CONTEXT_ALL || $shop_context == Shop::CONTEXT_GROUP)) {
            $this->multishop_warning = $this->displayWarning($this->l('Please select a store'));
        }
    }

    public function install()
    {
        mkdir(_PS_IMG_DIR_ . 'seosaproductlabels/');
        if ((float)_PS_VERSION_ < 1.6) {
            $this->insertHookInProductListTPL();
        }
        if (!parent::install()
            || !ProductLabelSeo::createTable()
            || !ProductLabelLocation::createTable()
            || !$this->installTab()
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('actionProductDelete')
            || !$this->registerHook('displayProductListReviews')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('displayHeader')
            || !$this->registerHooksPs17()) {
            return false;
        }

        try {
            $this->demoInstall();
        } catch (Exception $e) {
            $logger = new FileLogger();
            $logger->setFilename(_PS_ROOT_DIR_ . '/log/' . date('Ymd') . '_exception.log');
            $logger->logError($e->getMessage());
        }

        return true;
    }

    private function demoInstall()
    {
        if (!class_exists('DirectoryIterator')) {
            throw new Exception('Demo stickers are not created: class DirectoryIterator does not exist');
        }

        if (!class_exists('ProductLabelSeo')) {
            require_once _MODULE_DIR_.$this->name.'/'.$this->name.'.php';
        }

        $max_id_label = Db::getInstance()->getValue(
            'SELECT MAX(id_product_label) `max_id` FROM `' . _DB_PREFIX_ . 'seosaproductlabels`'
        );

        $id_label = $max_id_label ? $max_id_label + 1 : 1;

        $demo_path = _PS_MODULE_DIR_ . $this->name . '/views/img/demo';

        foreach (new DirectoryIterator($demo_path) as $file) {
            if ($file->isDot()) {
                continue;
            }

            $file_name = $file->getFilename();
            $name = stristr($file_name, '.png', true);

            if (!$name) {
                continue;
            }
            $path = _PS_ROOT_DIR_ . '/img/' . $this->name . '/' . $id_label;

            if (!file_exists($path)) {
                if (!mkdir($path, 0777, true)) {
                    throw new Exception('Failed to create directory: ' . $path);
                }
            }

            $source = $demo_path . '/' . $file_name;
            foreach (Language::getLanguages() as $lang) {
                $target = $path . '/' . $lang['id_lang'] . '.png';
                if (!copy($source, $target)) {
                    throw new Exception('Couldn\'t copy ' . $source . ' to ' . $target);
                }
            }

            $product_label = new ProductLabelSeo();
            $product_label->name = $name;
            if (!$product_label->save()) {
                throw new Exception('Couldn\'t create ' . $name);
            }
            $id_label++;
        }
    }

    public function registerHooksPs17()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return $this->registerHook('displayAfterProductThumbs');
        }
        return true;
    }

    public function uninstall()
    {
        $this->delTree(_PS_IMG_DIR_ . 'seosaproductlabels/');
        if (!ProductLabelSeo::dropTable()
            || !ProductLabelLocation::dropTable()
            || !parent::uninstall()
            || !$this->uninstallTab()) {
            return false;
        }

        return true;
    }

    public function insertHookInProductListTPL()
    {
        call_user_func_array(
            'copy',
            array(_PS_THEME_DIR_ . 'product-list.tpl',
                _PS_THEME_DIR_ . 'product-list.tpl.old')
        );
        $file = @Tools::file_get_contents(_PS_THEME_DIR_ . 'product-list.tpl');
        $file = preg_replace(
            '/(<div.*right_block.*">)/i',
            '$1' . PHP_EOL . '{hook h=\'displayProductListReviews\' product=$product}',
            $file
        );
        @file_put_contents(_PS_THEME_DIR_ . 'product-list.tpl', $file);
    }

    public function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }


    public function installTab()
    {
        $id_parent = Tab::getIdFromClassName('AdminCatalog');
        $languages = Language::getLanguages();

        $name = array();
        foreach ($languages as $language) {
            $name[$language['id_lang']] = $this->l('AdminProductLabels');
        }

        $tab = new Tab();
        $tab->id_parent = $id_parent;
        $tab->class_name = 'AdminProductLabels';
        $tab->name = $name;
        $tab->module = $this->name;
        $tab->active = true;

        return $tab->save();
    }

    public function uninstallTab()
    {
        $tab = Tab::getInstanceFromClassName('AdminProductLabels');

        return $tab->delete();
    }

    private static $cache;

    public function prepareHook($id_product)
    {
        if (!$id_product) {
            return;
        }

        $cache_key = md5($id_product);

        if (!isset(self::$cache[$cache_key])) {
            $upload_dir_url = __PS_BASE_URI__ . 'img/seosaproductlabels/';
            $full_upload_dir_url = _PS_ROOT_DIR_ . '/img/seosaproductlabels/';
            $base_image_url = $this->context->link->protocol_content . Tools::getMediaServer(
                $upload_dir_url
            ) . $upload_dir_url;

            $product_labels = ProductLabelLocation::getForProduct($id_product);

            foreach ($product_labels as $key => &$product_label) {
                $id_product_label = $product_label['id_product_label'];
                if (!file_exists($full_upload_dir_url
                        . $id_product_label . '/' . $this->context->language->id . '.png')
                    && !$product_label['text']) {
                    unset($product_labels[$key]);
                }

                if (ProductLabelSeo::checkIncludedProductForLabel($id_product, $id_product_label) ||
                    ProductLabelSeo::checkExcludedProductForLabel($id_product, $id_product_label)
                ) {
                    $product_label['text'] = false;
                    $product_label['hint'] = false;
                    unset($product_labels[$key]);
                    continue;
                }
                $product_label['id_currency'] = $this->context->cart->id_currency;
                $product_label['max_price'] =
                    Tools::convertPrice($product_label['max_price'], $product_label['id_currency']);
                $product_label['mini_price'] =
                    Tools::convertPrice($product_label['mini_price'], $product_label['id_currency']);
                $product_label['price'] = Product::getPriceStatic($id_product, true, null, 2);
                $product_label['quantity_prod'] = StockAvailable::getQuantityAvailableByProduct($id_product, 0);
                $product_label['image_url'] =
                    $upload_dir_url . $id_product_label . '/' . $this->context->language->id . '.png';

                $image_css = ProductLabelSeo::getImageCSS($id_product_label);

                $page = $this->context->controller->php_self;
                if (is_null($page)) {
                    $page = 'category';
                }
                if ($page != 'product' && !isset($image_css[$page])) {
                    if ($page != 'product') {
                        $page = 'category';
                    }
                }
                if (isset($image_css[$page])) {
                    $product_label['image_css'] = $image_css[$page];
                } else {
                    $product_label['image_css'] = '';
                }
            }

            self::$cache[$cache_key] = array(
                'base_image_url' => $base_image_url,
                'seosa_product_labels' => $product_labels,
            );
        }

        $this->context->smarty->assign(self::$cache[$cache_key]);

        return true;
    }

    public function hookDisplayProductListReviews($params)
    {
        if ($this->prepareHook($params['product']['id_product'])) {
            return $this->display(__FILE__, 'product_list_reviews.tpl');
        }
    }

    public function hookDisplayRightColumnProduct()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            return '';
        }

        if ($this->prepareHook(Tools::getValue('id_product'))) {
            return $this->display(__FILE__, 'right_column_product.tpl');
        }
    }

    public function hookDisplayAfterProductThumbs()
    {
        if ($this->prepareHook(Tools::getValue('id_product'))) {
            return $this->display(__FILE__, 'after_product_thumbs.tpl');
        }
    }

    public function hookDisplayFooterProduct($params)
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return '';
        }

        if ($this->prepareHook($params['product']['id_product'])) {
            return $this->display(__FILE__, 'right_column_product.tpl');
        }
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return '';
        }
        if ($params['type'] != 'weight' || $this->context->controller instanceof ProductController) {
            return '';
        }

        return $this->hookDisplayProductListReviews($params);
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if ($this->multishop_warning) {
            return $this->multishop_warning;
        }
        if (Tools::getValue('key_tab') == 'ModuleSeosaproductlabels') {
            $this->hookActionProductUpdate();
        }

        $id_product = Tools::getValue('id_product');
        if (!$id_product) {
            $id_product = (int)$params['id_product'];
        }
        $current_product_labels = ProductLabelLocation::getForProduct($id_product);

        $upload_dir = _PS_IMG_DIR_ . 'seosaproductlabels/';

        foreach ($current_product_labels as &$current_product_label) {
            $id_product_label = $current_product_label['id_product_label'];
            $image = $upload_dir . $id_product_label . '/' . $this->context->language->id . '.png';
            if (file_exists($image) && $current_product_label['label_type'] == 'image') {
                $current_product_label['image_url'] = ImageManager::thumbnail(
                    $image,
                    'seosaproductlabels_'.(int)$id_product_label . '_' .(int)$current_product_label['id_lang'] . '.png',
                    60,
                    'png',
                    true,
                    true
                );
                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
                    $current_product_label['image_url'] = str_replace(
                        '..',
                        Tools::getShopDomain(true),
                        $current_product_label['image_url']
                    );
                }
            } else {
                $current_product_label['image_url'] = null;
            }
        }

        $product_labels = ProductLabelSeo::getList(true);
        $this->context->smarty->assign(
            array(
                'product_labels' => $product_labels,
                'current_product_labels' => $current_product_labels,
                'ps_version' => (float)_PS_VERSION_
            )
        );

        return $this->display(__FILE__, 'admin_products_extra.tpl');
    }

    public function hookActionProductUpdate()
    {
        self::$count++;
        if (self::$count > 1) {
            // I have no idea why it's called twice!
            return;
        }
        $product = new Product((int)Tools::getValue('id_product'));
        if (Validate::isLoadedObject($product)) {
            $id_product_label = Tools::getValue('seosa_id_product_label');
            $position = Tools::getValue('seosa_product_label_position');

            if ($id_product_label && $position) {
                foreach (Shop::getContextListShopID() as $id_shop) {
                    $product_label_location = new ProductLabelLocation();
                    $product_label_location->id_product = (int)$product->id;
                    $product_label_location->id_product_label = $id_product_label;
                    $product_label_location->position = $position;
                    $product_label_location->id_shop = (int)$id_shop;
                    $product_label_location->save();
                }
            }
        }

        $for_remove = Tools::getValue('seosa_remove_product_label_location');

        if (is_array($for_remove) && $for_remove) {
            foreach ($for_remove as $id_product_label_location) {
                $product_label_location = new ProductLabelLocation($id_product_label_location);
                $locations = ProductLabelLocation::getLocations(
                    $product_label_location->id_product,
                    $product_label_location->id_product_label
                );

                foreach ($locations as $location) {
                    $location_object = new ProductLabelLocation((int)$location['id_product_label_location']);

                    if (Validate::isLoadedObject($location_object)) {
                        $location_object->delete();
                    }
                }
            }
        }
    }

    public function hookActionProductDelete($product)
    {
        if ($product && isset($product->id)) {
            ProductLabelLocation::clearForProduct($product->id);
        }
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/seosaproductlabels.css');

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->context->controller->addJS($this->_path . 'views/js/seosaproductlabels17.js');
        } else {
            $this->context->controller->addJS($this->_path . 'views/js/seosaproductlabels.js');
        }
        $page = Tools::getValue('controller');
        if (($page != 'index') && ($page != 'product') && ($page != 'category')) {
            return "";
        }

        $labels = ProductLabelSeo::getList();

        if (is_array($labels) && count($labels)) {
            foreach ($labels as &$label) {
                if (strpos($label['position'], 'left') !== false) {
                    $label['fix_hint_position'] = 'left';
                } elseif (strpos($label['position'], 'right') !== false) {
                    $label['fix_hint_position'] = 'right';
                } elseif (strpos($label['position'], 'center') !== false) {
                    $page = $this->context->controller->php_self;
                    preg_match('/.*(height\s?:\s?\d+px)/u', $label[$page.'_image_css'], $match);
                    if (isset($match[1])) {
                        $arr = explode(':', $match[1]);
                        $image_height = $arr[1];
                    } else {
                        $image_height = '80px';
                    }
                    $label['image_height'] = $image_height;
                }
            }
        }

        $this->context->smarty->assign(array(
            'seosa_labels' => $labels
        ));

        return $this->display(__FILE__, 'hint_script.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::isSubmit('ajax')) {
            if (Tools::getValue('action') == 'save_product_label') {
                $this->hookActionProductUpdate();
                die(Tools::jsonEncode(array(
                    'hasError' => false
                )));
            }

            if (Tools::getValue('action') == 'get_product_for_excl_incl') {
                $result = array();
                if (Tools::getValue('activ') == 'manufacturers') {
                    $sql = 'SELECT p.`id_product`, pl.`name` 
                        FROM `' . _DB_PREFIX_ . 'product` p
                        ' . Shop::addSqlAssociation('product', 'p') .
                        (Combination::isFeatureActive() ?
                            'LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` product_attribute 
                            ON (p.`id_product` = product_attribute.`id_product`)
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
                        ON (product_attribute.`id_product_attribute` = product_attribute_shop.`id_product_attribute` 
                        AND product_attribute_shop.`default_on` = 1 
                        AND product_attribute_shop.id_shop=' . (int)$this->context->shop->id . ')' : '') . '
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                        ON (p.`id_product` = pl.`id_product` 
                        AND pl.`id_lang` = ' . (int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').')
                        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                        ON (m.`id_manufacturer` = p.`id_manufacturer`)
                        ' . Product::sqlStock('p', 0);

                    $ids_manufacturer = Tools::getValue('manufacturers');
                    if (!$ids_manufacturer) {
                        $result = array();
                    } else {
                        if (count($ids_manufacturer) == 1) {
                            $sql .= ' WHERE p.`id_manufacturer` = ' . (int)$ids_manufacturer[0];
                        } else {
                            $sql .= ' WHERE p.`id_manufacturer` IN('. pSQL((implode(', ', $ids_manufacturer))).')';
                        }

                        $sql .= ' AND product_shop.`active` = 1';

                        $sql .= ' GROUP BY p.id_product ORDER BY p.`id_product` ASC';

                        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    }
                } elseif (Tools::getValue('activ') == 'categories') {
                    $ids_categories = Tools::getValue('categories');

                    if (is_array($ids_categories) && count($ids_categories)) {
                        $child_categories = array();
                        if (Tools::getValue('include_category_product')) {
                            foreach ($ids_categories as $id_category) {
                                $category = new Category((int)$id_category);
                                foreach ($category->getAllChildren($id_category) as $obj_caterogy) {
                                    $child_categories[] = $obj_caterogy->id;
                                }
                            }
                        }

                        $ids_categories = array_merge($ids_categories, $child_categories);
                        if (version_compare(_PS_VERSION_, '1.6.0.14', '<=')) {
                            $sql = 'SELECT p.`id_product`, pl.`name`
                                FROM `' . _DB_PREFIX_ . 'category_product` cp
                                LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                                ON p.`id_product` = cp.`id_product`
                                ' . Shop::addSqlAssociation('product', 'p') .
                                (Combination::isFeatureActive() ? ' 
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
                                ON (p.`id_product` = product_attribute_shop.`id_product_attribute` 
                                AND product_attribute_shop.`default_on` = 1 
                                AND product_attribute_shop.id_shop=' . (int)$this->context->shop->id . ')' : '') . '
                                ' . Product::sqlStock('p', 0) . '
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                                ON (p.`id_product` = pl.`id_product`
                                AND pl.`id_lang` = ' .
                                (int)$this->context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                                WHERE product_shop.`id_shop` = ' . (int)$this->context->shop->id . '
                                AND cp.`id_category` IN(' . pSQL(implode(', ', $ids_categories)) . ')
                                AND product_shop.`active` = 1';
                            $sql .= ' GROUP BY p.`id_product`';
                            $sql .= ' ORDER BY p.`id_product` ASC';

                            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
                        } else {
                            $sql = 'SELECT p.`id_product`, pl.`name`
                                FROM `' . _DB_PREFIX_ . 'category_product` cp
                                LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                                ON p.`id_product` = cp.`id_product`
                                ' . Shop::addSqlAssociation('product', 'p') .
                            (Combination::isFeatureActive() ? ' 
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
                                ON (p.`id_product` = product_attribute_shop.`id_product` 
                                AND product_attribute_shop.`default_on` = 1 
                                AND product_attribute_shop.id_shop=' . (int)$this->context->shop->id . ')' : '') . '
                                ' . Product::sqlStock('p', 0) . '
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                                ON (p.`id_product` = pl.`id_product`
                                AND pl.`id_lang` = ' . (int)$this->context->language->id
                                . Shop::addSqlRestrictionOnLang('pl') . ')
                                WHERE product_shop.`id_shop` = ' . (int)$this->context->shop->id . '
                                AND cp.`id_category` IN(' . pSQL(implode(', ', $ids_categories)) . ')
                                AND product_shop.`active` = 1';
                            $sql .= ' GROUP BY p.`id_product`';
                            $sql .= ' ORDER BY p.`id_product` ASC';
                            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
                        }
                    }
                } elseif (Tools::getValue('activ') == 'suppliers') {
                    $sql = 'SELECT p.`id_product`, pl.`name` 
                        FROM `' . _DB_PREFIX_ . 'product` p
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON pl.`id_product`=p.`id_product`
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON ps.`id_product`=p.`id_product`
                        WHERE ps.id_supplier IN(' . pSQL(implode(',', Tools::getValue('suppliers', array(0)))) .')
                        GROUP BY p.`id_product`';
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                } elseif (Tools::getValue('activ') == 'status' && Tools::getValue('product_status')) {
                    if (Tools::getValue('product_status') == 1) {
                        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
                        if (!Validate::isUnsignedInt($nb_days_new_product)) {
                            $nb_days_new_product = 20;
                        }
                        $seconds_days = $nb_days_new_product * 3600 * 24;
                        $date_add = time() - $seconds_days;
                        $date = date('Y-m-d H:i:s', $date_add);

                        $sql = 'SELECT p.`id_product`, pl.`name`
                        FROM `' . _DB_PREFIX_ . 'product` p
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON pl.`id_product`=p.`id_product`
                        WHERE p.`date_add` > "' . pSQL($date) . '" 
                        AND pl.`id_lang` = ' . (int)$this->context->language->id;
                    } elseif (Tools::getValue('product_status') == 2) {
                        $sql = 'SELECT p.`id_product`, pl.`name`
                        FROM `' . _DB_PREFIX_ . 'product` p
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON pl.`id_product`=p.`id_product`
                        WHERE p.`on_sale` > 0 AND pl.`id_lang` = ' . (int)$this->context->language->id;
                    }
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                } elseif (Tools::getValue('activ') == 'cart_rules') {
                    $cart_rules = Tools::getValue('cart_rules_selected');
                    if (is_array($cart_rules) && count($cart_rules) > 1) {
                        $where = 'IN (' . pSQL(implode(',', $cart_rules)) . ')';
                    } elseif (is_array($cart_rules) && count($cart_rules) == 1) {
                        $where = '= ' . (int)$cart_rules[0];
                    } else {
                        $where = '= 0';
                    }

                    $sql = 'SELECT rv.`id_item`, pr.`type`,rg.`id_cart_rule` 
                     FROM `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` rg
                     LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule` pr 
                     ON rg.id_product_rule_group = pr.id_product_rule_group
                     LEFT JOIN `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` rv 
                     ON rv.id_product_rule = pr.id_product_rule
                     WHERE rg.id_cart_rule ' . $where;
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    //    $arr = array_map('unserialize', array_unique(array_map('serialize', $result)));


                    $categ_str = "";
                    $manuf_str = "";
                    $product_str = "";
                    $products_rule = array();

                    if ($cart_rules) {
                        foreach ($cart_rules as $it) {
                            foreach ($result as $el) {
                                if ($el['id_cart_rule'] == $it) {
                                    if ($el['type'] == 'categories') {
                                        if ($categ_str == "") {
                                            $categ_str = $el['id_item'];
                                        } else {
                                            $categ_str = $categ_str . ',' . $el['id_item'];
                                        }
                                    }
                                }

                                if ($el['id_cart_rule'] == $it) {
                                    if ($el['type'] == 'manufacturers') {
                                        if ($manuf_str == "") {
                                            $manuf_str = $el['id_item'];
                                        } else {
                                            $manuf_str = $manuf_str . ',' . $el['id_item'];
                                        }
                                    }
                                }
                                if ($el['id_cart_rule'] == $it) {
                                    if ($el['type'] == 'products') {
                                        if ($product_str == "") {
                                            $product_str = $el['id_item'];
                                        } else {
                                            $product_str = $product_str . ',' . $el['id_item'];
                                        }
                                    }
                                }
                            }

                            $sql_rule = '
                          SELECT p.`id_product`, pl.`name` from `' . _DB_PREFIX_ . 'product` p
		                  LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON p.`id_product` = pl.`id_product` 
		                  AND pl.`id_lang` = ' . (int)$this->context->language->id;

                            if ($categ_str) {
                                $sql_rule .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_product`c 
                                ON c.id_product = p.id_product';
                            }
                            if ($manuf_str && $categ_str) {
                                $sql_rule .= ' where c.`id_category` in(' . $categ_str . ') 
                                AND p.id_manufacturer in (' . $manuf_str . ') GROUP BY p.`id_product`';
                            } elseif ($categ_str && !$manuf_str) {
                                $sql_rule .= ' where c.`id_category` in(' . $categ_str . ') GROUP BY p.`id_product`';
                            } elseif (!$categ_str && $manuf_str) {
                                $sql_rule .= ' where p.id_manufacturer in (' . $manuf_str . ') GROUP BY p.`id_product`';
                            }
                            $products_rule[] = db::getinstance()->executeS($sql_rule);
                            $manuf_str = "";
                            $categ_str = "";
                        }
                    }
                    if ($products_rule) {
                        foreach ($products_rule as $prod) {
                            $result = array_map(
                                "unserialize",
                                array_unique(array_map("serialize", $prod))
                            );
                        }
                    }
                    if ($product_str) {
                        $products_rules = DB::getInstance()->executeS('
                                   SELECT p.`id_product`, pl.`name`  FROM `' . _DB_PREFIX_ . 'product` p
                                   LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON p.`id_product` = pl.`id_product` 
                                   AND pl.`id_lang` = ' . (int)$this->context->language->id . ' 
                                   WHERE p.`id_product` IN (' . $product_str . ')');
                        $result = array_merge($products_rules, $result);
                        $result = array_map(
                            "unserialize",
                            array_unique(array_map("serialize", $result))
                        );
                    }
                } elseif (Tools::getValue('activ') == 'condition') {
                    $condition = Tools::getValue('product_condition', 'no_condition');
                    $sql = 'SELECT p.`id_product`, pl.`name`
                        FROM `' . _DB_PREFIX_ . 'product` p
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON pl.`id_product`=p.`id_product`
                        WHERE p.`condition` = "' . pSQL($condition) . '" 
                        AND pl.`id_lang` = ' . (int)$this->context->language->id;
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                } elseif (Tools::getValue('activ') == 'bestsellers') {
                    $result = ProductSale::getBestSalesLight(
                        (int)$this->context->language->id,
                        0,
                        (int)Configuration::get('PS_BLOCK_BESTSELLERS_TO_DISPLAY')
                    );
                } elseif (Tools::getValue('activ') == 'quantity') {
                    $count_range = explode('-', Tools::getValue('count_range'));
                    $min = $count_range[0];
                    $max = $count_range[1];
                    if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                        $sql = 'SELECT sa.`id_product`, pl.`name` FROM `' . _DB_PREFIX_ . 'product_lang` pl
                        LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON pl.`id_product` = sa.`id_product`
                        WHERE sa.`id_product_attribute` = 0 
                        AND sa.`quantity` >= ' . (int)$min . ($max ? ' AND sa.`quantity` <= ' . (int)$max : '').' 
                        GROUP BY sa.`id_product`';
                    } else {
                        $sql = 'SELECT p.`id_product`, pl.`name` FROM `' . _DB_PREFIX_ . 'product` p
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON pl.`id_product`=p.`id_product`
                        WHERE p.`quantity` >= ' . (int)$min . ($max ? ' AND p.`quantity` <= ' . (int)$max : '');
                    }
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                }

                $excluded = ProductLabelSeo::getExcludedProducts(
                    Tools::getValue('id_product_label'),
                    $this->context->language->id
                );
                $included = ProductLabelSeo::getIncludedProducts(
                    Tools::getValue('id_product_label'),
                    $this->context->language->id
                );

                if (is_array($result) && count($result) && ($excluded || $included)) {
                    foreach ($result as &$product) {
                        $product['excl_selected'] = '';
                        $product['incl_selected'] = '';
                        foreach ($excluded as $excl) {
                            if ($product['id_product'] == $excl['id_excluded_product']) {
                                $product['excl_selected'] = 'selected';
                            }
                        }
                        foreach ($included as $incl) {
                            if ($product['id_product'] == $incl['id_included_product']) {
                                $product['incl_selected'] = 'selected';
                            }
                        }
                    }
                }

                die(Tools::jsonEncode(array(
                    'hasError' => false,
                    'data' => $result
                )));
            }
        }

        if ($this->context->controller->controller_name == 'AdminProducts') {
            $this->context->controller->addCSS($this->_path . 'views/css/products_extra.css');
        }
    }

    public function getUploadDir()
    {
        return $this->local_path . 'uploads/';
    }

    public function registerSmartyFunctions()
    {
        if ((float)_PS_VERSION_ < 1.5) {
            $smarty = &$GLOBALS['smarty'];
        } else {
            $smarty = $this->context->smarty;
        }
        if (!array_key_exists('get_image_lang', $smarty->registered_plugins['function'])) {
            smartyRegisterFunction($smarty, 'function', 'get_image_lang', array($this, 'getImageLang'));
        }
    }

    public function getImageLang($smarty)
    {
        if (_PS_VERSION_ < 1.5) {
            $cookie = &$GLOBALS['cookie'];
        } else {
            $cookie = $this->context->cookie;
            $cookie->id_lang = $this->context->language->id;
        }

        $path = $smarty['path'];
        $module_path = 'seosaproductlabels/views/img/';
        $current_language = new Language($cookie->id_lang);
        $module_lang_path = $module_path . $current_language->iso_code . '/';
        $module_lang_default_path = $module_path . 'en/';
        $path_image = false;
        if (file_exists(_PS_MODULE_DIR_ . $module_lang_path . $path)) {
            $path_image = _MODULE_DIR_ . $module_lang_path . $path;
        } elseif (file_exists(_PS_MODULE_DIR_ . $module_lang_default_path . $path)) {
            $path_image = _MODULE_DIR_ . $module_lang_default_path . $path;
        }

        if ($path_image) {
            return '<img class="thumbnail" src="' . $path_image . '">';
        } else {
            return '[can not load image "' . $path . '"]';
        }
    }

    public function getContent()
    {
        $this->registerSmartyFunctions();
        return $this->getDocumentation();
    }

    public function assignDocumentation()
    {
        $smarty = Context::getContext()->smarty;
        if (!array_key_exists('no_escape', $smarty->registered_plugins['modifier'])) {
            smartyRegisterFunction($smarty, 'modifier', 'no_escape', array(__CLASS__, 'noEscape'));
        }
        if (class_exists('TransModSPL')) {
            if (!array_key_exists('ld', $smarty->registered_plugins['modifier'])) {
                smartyRegisterFunction($smarty, 'modifier', 'ld', array(TransModSPL::getInstance(), 'ld'));
            }
        }

        $this->context->controller->addCSS($this->getLocalPath() . 'views/css/documentation.css');

        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addCSS(($this->_path) . 'views/css/documentation.css', 'all');
            $this->context->controller->addCSS(($this->_path) . 'views/css/admin-theme.css', 'all');
        }
        $documentation_folder = $this->getLocalPath() . 'views/templates/admin/documentation';
        $documentation_pages = self::globRecursive($documentation_folder . '/**.tpl');
        natsort($documentation_pages);

        $tree = array();
        if (is_array($documentation_pages) && count($documentation_pages)) {
            foreach ($documentation_pages as &$documentation_page) {
                $name = str_replace(array($documentation_folder . '/', '.tpl'), '', $documentation_page);
                $path = explode('/', $name);

                $tmp_tree = &$tree;
                foreach ($path as $key => $item) {
                    $part = $item;
                    if ($key == (count($path) - 1)) {
                        $tmp_tree[$part] = $name;
                    } else {
                        if (!isset($tmp_tree[$part])) {
                            $tmp_tree[$part] = array();
                        }
                    }
                    $tmp_tree = &$tmp_tree[$part];
                }
            }
        }

        $this->context->smarty->assign('tree', $this->buildTree($tree));
        $this->context->smarty->assign('documentation_pages', $documentation_pages);
        $this->context->smarty->assign('documentation_folder', $documentation_folder);
    }

    public function getDocumentation()
    {
        $this->assignDocumentation();
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name .'/views/templates/admin/documentation.tpl');
    }

    public function buildTree($tree)
    {
        $tree_html = '';
        if (is_array($tree) && count($tree)) {
            foreach ($tree as $name => $tree_item) {
                preg_match('/^(\d+)\._(.*)$/', $name, $matches);
                $format_name = $matches[1] . '. ' . TransModSPL::getInstance()->ld($matches[2]);

                $tree_html .= '<li>';
                $tree_html .= '<a ' . (!is_array($tree_item) ? 'data-tab="'
                        . $tree_item . '" href="#"' : '') . '>' . $format_name . '</a>';
                if (is_array($tree_item) && count($tree_item)) {
                    $tree_html .= '<ul>';
                    $tree_html .= $this->buildTree($tree_item);
                    $tree_html .= '</ul>';
                }
                $tree_html .= '</li>';
            }
        }
        return $tree_html;
    }

    /**
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    public static function globRecursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        if (!$files) {
            $files = array();
        }

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $files = array_merge($files, self::globRecursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }

    public static function noEscape($value)
    {
        return $value;
    }

    public function getDocumentationLinks()
    {
        $this->context->smarty->assign('link_on_tab_module', $this->getAdminLink());
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name
            . '/views/templates/admin/documentation_links.tpl');
    }

    public function getAdminLink()
    {
        return $this->context->link->getAdminLink('AdminModules', true)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
    }

    public static function getAllCategoriesName(
        $root_category = null,
        $id_lang = false,
        $active = true,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getAllCategoriesName_'
            .md5((int)$root_category.(int)$id_lang.(int)$active.(int)$use_shop_restriction
                .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
				SELECT c.id_category, cl.name
				FROM `'._DB_PREFIX_.'category` c
				'.($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '').'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl 
				ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
				'.(isset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg 
				ON c.`id_category` = cg.`id_category`' : '').'
				'.(isset($root_category) ? 'RIGHT JOIN `'._DB_PREFIX_.'category` c2 
				ON c2.`id_category` = '.(int)$root_category.' AND c.`nleft` >= c2.`nleft` 
				AND c.`nright` <= c2.`nright`' : '').'
				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND c.`active` = 1' : '').'
				'.(isset($groups) && Group::isFeatureActive() ? ' 
				AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
				'.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' 
				GROUP BY c.`id_category`' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
				'.($sql_limit != '' ? $sql_limit : ''));
            Cache::store($cache_id, $result);
        } else {
            $result = Cache::retrieve($cache_id);
        }
        return $result;
    }

    public function displayWarning($warning)
    {
        $output = '
		<div class="bootstrap">
		<div class="module_warning alert alert-warning" >
			<button type="button" class="close" data-dismiss="alert">&times;</button>';

        if (is_array($warning)) {
            $output .= '<ul>';
            foreach ($warning as $msg) {
                $output .= '<li>'.$msg.'</li>';
            }
            $output .= '</ul>';
        } else {
            $output .= $warning;
        }

        // Close div openned previously
        $output .= '</div></div>';

        return $output;
    }
}
