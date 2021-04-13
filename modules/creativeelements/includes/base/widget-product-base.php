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

abstract class WidgetProductBase extends WidgetBase
{
    protected $context;

    protected $catalog;

    protected $show_prices;

    protected $parentTheme;

    protected $imageSize;

    protected $currency;

    protected $usetax;

    protected $noImage;

    public function __construct($data = array(), $args = array())
    {
        $this->context = \Context::getContext();
        $this->catalog = \Configuration::get('PS_CATALOG_MODE');
        $this->show_prices = _CE_PS16_ ? empty($this->context->smarty->tpl_vars['PS_CATALOG_MODE']->value) : !\Configuration::isCatalogMode();
        $this->parentTheme = !empty($this->context->shop->theme) ? $this->context->shop->theme->get('parent') : '';
        $this->imageSize = \ImageType::{_CE_PS16_ ? 'getFormatedName' : 'getFormattedName'}('home');
        $this->loading = stripos($this->getName(), 'carousel') === false ? 'lazy' : 'auto';

        if ($this->context->controller instanceof \AdminController) {
            isset($this->context->customer->id) or $this->context->customer = new \Customer();
        } else {
            if (_CE_PS16_) {
                $this->currency = new \Currency($this->context->cookie->id_currency);
                $this->usetax = \Product::getTaxCalculationMethod((int) $this->context->customer->id) != PS_TAX_EXC;
            } elseif (!$this->catalog) {
                $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
                $this->noImage = method_exists($imageRetriever, 'getNoPictureImage') ? $imageRetriever->getNoPictureImage($this->context->language) : null;
            }
        }
        parent::__construct($data, $args);
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function _skinOptions()
    {
        $opts = array();

        if (_CE_PS16_) {
            $opts['product'] = __('Default', 'elementor');
        } else {
            $pattern = 'templates/catalog/_partials/miniatures/*product*.tpl';
            $tpls = $this->parentTheme ? glob(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/$pattern") : array();
            $tpls += glob(_PS_THEME_DIR_ . $pattern);

            foreach ($tpls as $tpl) {
                $opt = basename($tpl, '.tpl');
                $opts[$opt] = 'product' === $opt ? __('Default', 'elementor') : \Tools::ucFirst($opt);
            }
            unset($opts['pack-product']);
        }
        $opts['custom'] = __('Custom', 'elementor');

        return $opts;
    }

    protected function _listingOptions()
    {
        $opts = array(
            'category' => __('Featured Products', 'elementor'),
            'prices-drop' => __('Prices Drop', 'elementor'),
            'new-products' => __('New Products', 'elementor'),
        );
        if (!$this->catalog) {
            $opts['best-sales'] = __('Best Sales', 'elementor');
        }
        $opts['products'] = __('Custom Products', 'elementor');

        return $opts;
    }

    protected function addListingControls($limit = 'limit')
    {
        $this->addControl(
            'listing',
            array(
                'label' => __('Listing', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'category',
                'options' => $this->_listingOptions(),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'products_label',
            array(
                'raw' => __('Products', 'elementor'),
                'type' => ControlsManager::RAW_HTML,
                'condition' => array(
                    'listing' => 'products',
                ),
            )
        );

        $this->addControl(
            'products',
            array(
                'type' => ControlsManager::REPEATER,
                'fields' => array(
                    array(
                        'name' => 'id',
                        'label' => __('Product ID', 'elementor'),
                        'type' => ControlsManager::NUMBER,
                        'min' => 1,
                    ),
                ),
                'title_field' => __('Product ID', 'elementor') . ': {{ id }}',
                'condition' => array(
                    'listing' => 'products',
                ),
            )
        );

        $this->addControl(
            'category_id',
            array(
                'label' => __('Category ID', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 2,
                'min' => 2,
                'condition' => array(
                    'listing' => 'category',
                ),
            )
        );

        $this->addControl(
            'order_by',
            array(
                'label' => __('Order By', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'position',
                'options' => array(
                    'position' => __('Popularity', 'elementor'),
                    'quantity' => __('Sales Volume', 'elementor'),
                    'date_add' => __('Arrival', 'elementor'),
                    'date_upd' => __('Update', 'elementor'),
                ),
                'condition' => array(
                    'listing' => array('category', 'prices-drop'),
                ),
            )
        );

        $this->addControl(
            'order_dir',
            array(
                'label' => __('Order Direction', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'asc',
                'options' => array(
                    'asc' => __('Ascending', 'elementor'),
                    'desc' => __('Descending', 'elementor'),
                ),
                'condition' => array(
                    'listing' => array('category', 'prices-drop'),
                ),
            )
        );

        $this->addControl(
            'randomize',
            array(
                'label' => __('Randomize', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'condition' => array(
                    'listing' => array('category', 'products'),
                ),
            )
        );

        $this->addControl(
            $limit,
            array(
                'label' => __('Product Limit', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 8,
                'condition' => array(
                    'listing!' => 'products',
                )
            )
        );
    }

    protected function getImageSizeOptions()
    {
        $opts = array();
        $sizes = \ImageType::getImagesTypes('products');

        foreach ($sizes as &$size) {
            $opts[$size['name']] = "{$size['name']} - {$size['width']} x {$size['height']}";
        }
        if (empty($opts[$this->imageSize])) {
            // set first image size as default when home doesn't exists
            $this->imageSize = key($opts);
        }
        return $opts;
    }

    protected function addMiniatureControls()
    {
        $this->startControlsSection(
            'section_content',
            array(
                'label' => __('Content', 'elementor'),
                'condition' => array(
                    'skin' => 'custom',
                ),
            )
        );

        $this->addResponsiveControl(
            'image_size',
            array(
                'label' => __('Image Size', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getImageSizeOptions(),
                'default' => $this->imageSize,
            )
        );

        $this->addControl(
            'show_second_image',
            array(
                'label' => __('Second Image', 'elementor'),
                'description' => __('Show second image on hover if exists'),
                'type' => _CE_PS16_ ? ControlsManager::HIDDEN : ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
            )
        );

        $this->addControl(
            'show_category',
            array(
                'label' => __('Category', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
            )
        );

        $this->addControl(
            'show_description',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
            )
        );

        $this->addControl(
            'description_length',
            array(
                'label' => __('Max. Length', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'condition' => array(
                    'show_description!' => '',
                )
            )
        );

        $this->addControl(
            'show_regular_price',
            array(
                'label' => __('Regular Price', 'elementor'),
                'type' => $this->catalog ? ControlsManager::HIDDEN : ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_atc',
            array(
                'label' => __('Add to Cart', 'elementor'),
                'type' => $this->catalog ? ControlsManager::HIDDEN : ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_qv',
            array(
                'label' => __('Quick View', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_badges',
            array(
                'label' => __('Badges', 'elementor'),
                'type' => ControlsManager::SELECT2,
                'options' => array(
                    'sale' => __('Sale', 'elementor'),
                    'new' => __('New', 'elementor'),
                    'pack' => __('Pack', 'elementor'),
                ),
                'default' => array('sale', 'new', 'pack'),
                'label_block' => true,
                'multiple' => true,
            )
        );

        $this->addControl(
            'badge_sale_text',
            array(
                'label' => __('Sale Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'sale',
                        ),
                    ),
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_atc',
            array(
                'label' => __('Add to Cart', 'elementor'),
                'condition' => array(
                    'skin' => 'custom',
                    'show_atc' => $this->catalog ? 'hide' : 'yes',
                ),
            )
        );

        $this->addControl(
            'atc_text',
            array(
                'label' => __('Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => __('Add to Cart', 'elementor'),
            )
        );

        $this->addControl(
            'atc_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
            )
        );

        $this->addControl(
            'atc_icon_align',
            array(
                'label' => __('Icon Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => __('Before', 'elementor'),
                    'right' => __('After', 'elementor'),
                ),
                'condition' => array(
                    'atc_icon!' => '',
                ),
            )
        );

        $this->addControl(
            'atc_icon_indent',
            array(
                'label' => __('Icon Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'atc_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-atc .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_qv',
            array(
                'label' => __('Quick View', 'elementor'),
                'condition' => array(
                    'skin' => 'custom',
                    'show_qv!' => '',
                ),
            )
        );

        $this->addControl(
            'qv_text',
            array(
                'label' => __('Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => __('Quick View', 'elementor'),
            )
        );

        $this->addControl(
            'qv_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
            )
        );

        $this->addControl(
            'qv_icon_align',
            array(
                'label' => __('Icon Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => __('Before', 'elementor'),
                    'right' => __('After', 'elementor'),
                ),
                'condition' => array(
                    'qv_icon!' => '',
                ),
            )
        );

        $this->addControl(
            'qv_icon_indent',
            array(
                'label' => __('Icon Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'qv_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-quick-view .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function addMiniatureStyleControls()
    {
        $this->startControlsSection(
            'section_style_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'skin' => 'custom',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Hover Animation', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('None', 'elementor'),
                    'grow' => __('Grow', 'elementor'),
                    'shrink' => __('Shrink', 'elementor'),
                    'rotate' => __('Rotate', 'elementor'),
                    'grow-rotate' => __('Grow Rotate', 'elementor'),
                    'float' => __('Float', 'elementor'),
                    'sink' => __('Sink', 'elementor'),
                    'bob' => __('Bob', 'elementor'),
                    'hang' => __('Hang', 'elementor'),
                    'buzz-out' => __('Buzz Out', 'elementor'),
                ),
                'prefix_class' => 'elementor-img-hover-',
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-image',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'skin' => 'custom',
                ),
            )
        );

        $this->addControl(
            'content_align',
            array(
                'label' => __('Alignment', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'elementor'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-content' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'content_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'content_min_height',
            array(
                'label' => __('Min. Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-content' => 'min-height: {{SIZE}}{{UNIT}};',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 300,
                    ),
                ),
            )
        );

        $this->startControlsTabs('content_style_tabs');

        $this->startControlsTab(
            'content_style_title',
            array(
                'label' => __('Title', 'elementor'),
            )
        );

        $this->addControl(
            'title_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_multiline',
            array(
                'label' => __('Allow Multiline', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-title' => 'overflow: visible; white-space: normal;',
                )
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-title',

            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_category',
            array(
                'label' => __('Category', 'elementor'),
                'condition' => array(
                    'show_category!' => '',
                )
            )
        );

        $this->addControl(
            'category_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-category' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'category_multiline',
            array(
                'label' => __('Allow Multiline', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-category' => 'overflow: visible; white-space: normal;',
                ),
            )
        );

        $this->addControl(
            'category_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-category' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'category_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-category',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_description',
            array(
                'label' => __('Description', 'elementor'),
                'condition' => array(
                    'show_description!' => '',
                )
            )
        );

        $this->addControl(
            'description_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-description' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-description',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_price',
            array(
                'label' => __('Price', 'elementor'),
            )
        );

        $this->addControl(
            'price_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-price-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'price_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-price' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'price_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-price-wrapper',
            )
        );

        $this->addControl(
            'heading_style_regular_price',
            array(
                'label' => __('Regular Price', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ),
            )
        );

        $this->addControl(
            'regular_price_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-price-regular' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ),
            )
        );

        $this->addResponsiveControl(
            'regular_price_size',
            array(
                'label' => _x('Size', 'Typography Control', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em', 'rem'),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-price-regular' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_atc',
            array(
                'label' => __('Add to Cart', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'skin' => 'custom',
                    'show_atc' => $this->catalog ? 'hide' : 'yes',
                ),
            )
        );

        $this->addControl(
            'atc_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'atc_align',
            array(
                'label' => __('Alignment', 'elementor'),
                'label_block' => false,
                'type' => ControlsManager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'elementor'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'fa fa-align-right',
                    ),
                    'justify' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'prefix_class' => 'elementor-atc--align-',
                'default' => 'justify',
            )
        );

        $this->addControl(
            'atc_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'sm',
                'options' => array(
                    'xs' => __('Extra Small', 'elementor'),
                    'sm' => __('Small', 'elementor'),
                    'md' => __('Medium', 'elementor'),
                    'lg' => __('Large', 'elementor'),
                    'xl' => __('Extra Large', 'elementor'),
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'atc_typography',
                'label' => __('Typography', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-atc .elementor-button',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            )
        );

        $this->startControlsTabs('atc_style_tabs');

        $this->startControlsTab(
            'atc_style_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'atc_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'atc_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'background-color: {{VALUE}};',
                ),
                'default' => '#000',
            )
        );

        $this->addControl(
            'atc_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'atc_style_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'atc_color_hover',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'atc_bg_color_hover',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'atc_border_color_hover',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'atc_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 0,
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'atc_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'separator' => '',
                'default' => array(
                    'unit' => 'px',
                    'size' => 0,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_qv',
            array(
                'label' => __('Quick View', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'show_qv' => 'yes',
                    'skin' => 'custom',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'qv_typography',
                'label' => __('Typography', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-quick-view',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            )
        );

        $this->startControlsTabs('qv_style_tabs');

        $this->startControlsTab(
            'qv_style_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'qv_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'qv_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'qv_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'qv_style_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'qv_color_hover',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'qv_bg_color_hover',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'qv_border_color_hover',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'qv_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'qv_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-quick-view' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_badges',
            array(
                'label' => __('Badges', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'skin',
                            'value' => 'custom',
                        ),
                        array(
                            'name' => 'show_badges[0]',
                            'operator' => 'in',
                            'value' => array('sale', 'new', 'pack'),
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badges_top',
            array(
                'label' => __('Top Distance', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => -20,
                        'max' => 20,
                    ),
                    'em' => array(
                        'min' => -2,
                        'max' => 2,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badges-left' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-badges-right' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'badges_left',
            array(
                'label' => __('Left Distance', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'separator' => '',
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => -20,
                        'max' => 20,
                    ),
                    'em' => array(
                        'min' => -2,
                        'max' => 2,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badges-left' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'badge_sale_position',
                            'value' => 'left',
                        ),
                        array(
                            'name' => 'badge_new_position',
                            'value' => 'left',
                        ),
                        array(
                            'name' => 'badge_pack_position',
                            'value' => 'left',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badges_right',
            array(
                'label' => __('Right Distance', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'separator' => '',
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => -20,
                        'max' => 20,
                    ),
                    'em' => array(
                        'min' => -2,
                        'max' => 2,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badges-right' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'badge_sale_position',
                            'value' => 'right',
                        ),
                        array(
                            'name' => 'badge_new_position',
                            'value' => 'right',
                        ),
                        array(
                            'name' => 'badge_pack_position',
                            'value' => 'right',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'bagdes_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'show_badges[1]',
                            'operator' => 'in',
                            'value' => array('new','pack'),
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badges_min_width',
            array(
                'label' => __('Min. Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 50,
                )
            )
        );

        $this->addControl(
            'badges_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'badges_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'badges_typography',
                'selector' => '{{WRAPPER}} .elementor-badge',
            )
        );

        $this->startControlsTabs('badge_style_tabs');

        $this->startControlsTab(
            'badge_style_sale',
            array(
                'label' => __('Sale', 'elementor'),
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'sale',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badge_sale_position',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left', 'elementor'),
                    ),
                    'right' => array(
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right', 'elementor'),
                    ),
                ),
                'default' => 'right',
            )
        );


        $this->addControl(
            'badge_sale_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-sale' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'badge_sale_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'separator' => '',
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-sale' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'badge_style_new',
            array(
                'label' => __('New', 'elementor'),
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'new',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badge_new_position',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left', 'elementor'),
                    ),
                    'right' => array(
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right', 'elementor'),
                    ),
                ),
                'default' => 'right',
            )
        );

        $this->addControl(
            'badge_new_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-new' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'badge_new_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'separator' => '',
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-new' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'badge_style_pack',
            array(
                'label' => __('Pack', 'elementor'),
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'pack',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'badge_pack_position',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left', 'elementor'),
                    ),
                    'right' => array(
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right', 'elementor'),
                    ),
                ),
                'default' => 'right',
            )
        );

        $this->addControl(
            'badge_pack_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-pack' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'badge_pack_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-badge-pack' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    private function getProduct16($id)
    {
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) $this->context->shop->id;
        $ps160 = version_compare(_PS_VERSION_, '1.6.1', '<');
        $combination = !$ps160 && \Combination::isFeatureActive();
        $nb_days_new_product = (int) \Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $sql = '
            SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity, ' .
                ($combination ? 'IFNULL(pas.id_product_attribute, 0) AS id_product_attribute, pas.minimal_quantity AS product_attribute_minimal_quantity,' : '') .
                'pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`,
                image_shop.`id_image` id_image, il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
                DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
                INTERVAL ' . $nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
            FROM `' . _DB_PREFIX_ . 'category_product` cp
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                ON p.`id_product` = cp.`id_product` ' . \Shop::addSqlAssociation('product', 'p') .
            ($combination ? ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pas
                ON (p.`id_product` = pas.`id_product` AND pas.`default_on` = 1 AND pas.id_shop=' . $id_shop . ')' : '') . \Product::sqlStock('p', 0) . '
            LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
                ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . $id_lang . \Shop::addSqlRestrictionOnLang('cl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . $id_lang . \Shop::addSqlRestrictionOnLang('pl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . ($ps160
                ? 'image` i ON (i.`id_product` = p.`id_product`)' . \Shop::addSqlAssociation('image', 'i', true, 'image_shop.cover = 1')
                : 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover = 1 AND image_shop.id_shop = ' . $id_shop . ')'
            ) . '
            LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
                ON ' . ($ps160 ? 'i' : 'image_shop') . '.`id_image` = il.`id_image` AND il.`id_lang` = '. $id_lang . '
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                ON m.`id_manufacturer` = p.`id_manufacturer`
            WHERE product_shop.`active` = 1
                AND product_shop.`id_shop` = ' . $id_shop . '
                AND p.`id_product` = ' . (int) $id . '
                AND product_shop.`active` = 1
                AND product_shop.`visibility` IN ("both", "catalog")';

        $row = \Db::getInstance()->getRow($sql);
        $result = $row ? \Product::getProductProperties($id_lang, $row) : array();

        $result['price'] = $this->show_prices ? \Tools::displayPrice(\Product::getPriceStatic($id, $this->usetax), $this->currency) : '';

        return $result;
    }

    private function getProducts16($listing, $order_by, $order_dir, $limit, $id_category = 2)
    {
        $id_lang = (int) $this->context->language->id;

        switch ($listing) {
            case 'category':
                $category = new \Category($id_category, $id_lang);
                $result = 'rand' == $order_by
                    ? $category->getProducts($id_lang, 0, $limit, null, null, false, true, true, $limit)
                    : $category->getProducts($id_lang, 0, $limit, $order_by, $order_dir)
                ;
                break;
            case 'prices-drop':
                $result = \Product::getPricesDrop($id_lang, 0, $limit, false, $order_by, $order_dir);
                break;
            case 'new-products':
                $result = \Product::getNewProducts($id_lang, 0, $limit);
                break;
            case 'best-sales':
                $result = \ProductSale::getBestSales($id_lang, 0, $limit, 'sales');
                break;
        }
        if (empty($result)) {
            return false;
        }
        foreach ($result as &$row) {
            $row['price'] = $this->show_prices ? \Tools::displayPrice(\Product::getPriceStatic((int) $row['id_product'], $this->usetax), $this->currency) : '';
        }
        return $result;
    }

    protected function getProduct($id)
    {
        if (_CE_PS16_) {
            // PrestaShop 1.6 compatibility
            return $this->getProduct16($id);
        }

        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $assembler = new \ProductAssembler($this->context);
        $result = array('id_product' => $id);

        try {
            if (!$assembledProduct = $assembler->assembleProduct($result)) {
                return false;
            }
            return $presenter->present(
                $presenterFactory->getPresentationSettings(),
                $assembledProduct,
                $this->context->language
            );
        } catch (\Exception $ex) {
            return false;
        }
    }

    protected function getProducts($listing, $order_by, $order_dir, $limit, $id_category = 2, $products = array())
    {
        $tpls = array();

        if ('products' === $listing) {
            // Custom Products
            if ('rand' === $order_by) {
                shuffle($products);
            }
            foreach ($products as &$product) {
                if ($product['id']) {
                    $tpls[] = $this->getProduct($product['id']);
                }
            }
            return $tpls;
        }

        if (_CE_PS16_) {
            // PrestaShop 1.6 compatibility
            return $this->getProducts16($listing, $order_by, $order_dir, $limit, $id_category);
        }

        $query = new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery();
        $query->setResultsPerPage($limit <= 0 ? 8 : (int) $limit);
        $query->setQueryType($listing);

        switch ($listing) {
            case 'category':
                $category = new \Category((int) $id_category);
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider($this->context->getTranslator(), $category);
                $query->setQueryType($listing);
                $query->setSortOrder(
                    'rand' == $order_by
                    ? \PrestaShop\PrestaShop\Core\Product\Search\SortOrder::random()
                    : new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir)
                );
                break;
            case 'prices-drop':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider($this->context->getTranslator());
                $query->setQueryType($listing);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'new-products':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider($this->context->getTranslator());
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', 'date_add', 'desc'));
                break;
            case 'best-sales':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider($this->context->getTranslator());
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', 'name', 'asc'));
                break;
        }
        $result = $searchProvider->runQuery(new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext($this->context), $query);

        $assembler = new \ProductAssembler($this->context);
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        foreach ($result->getProducts() as $rawProduct) {
            $tpls[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $tpls;
    }

    protected function fetchMiniature($product, &$settings)
    {
        $article = 'article-' . $product['id_product'];
        $image_size = !empty($settings['image_size']) ? $settings['image_size'] : $this->imageSize;
        $show_atc = $this->show_prices && !empty($settings['show_atc']);
        $min_qty = empty($product['product_attribute_minimal_quantity']) ? $product['minimal_quantity'] : $product['product_attribute_minimal_quantity'];
        $badges = array();

        if (_CE_PS16_) {
            $link = $product['link'];
            $cover_url = array(
                'desktop' => $this->context->link->getImageLink($product['link_rewrite'], $product['id_image'], $image_size),
            );
            if (!empty($settings['image_size_tablet']) && $settings['image_size_tablet'] != $image_size) {
                $cover_url['tablet'] = $this->context->link->getImageLink($product['link_rewrite'], $product['id_image'], $settings['image_size_tablet']);
            }
            if (!empty($settings['image_size_mobile']) && $settings['image_size_mobile'] != $settings['image_size_tablet']) {
                $cover_url['mobile'] = $this->context->link->getImageLink($product['link_rewrite'], $product['id_image'], $settings['image_size_mobile']);
            }
            $cover_alt = !empty($product['legend']) ? $product['legend'] : $product['name'];
            $cover_size = \Image::getSize($image_size);
            $on_sale = !empty($product['specific_prices']['reduction']);
            $regular_price = \Tools::displayPrice($product['price_without_reduction'], $this->currency);

            if ($on_sale && in_array('sale', $settings['show_badges'])) {
                $sp = &$product['specific_prices'];
                $badges['sale'] = !empty($settings['badge_sale_text'])
                    ? $settings['badge_sale_text']
                    : ('percentage' === $sp['reduction_type'] ? -$sp['reduction'] * 100 . '%' : \Tools::displayPrice(-$sp['reduction'], $this->currency));
                ;
            }
            if (!empty($product['new']) && in_array('new', $settings['show_badges'])) {
                $badges['new'] = __('New', 'elementor');
            }
            if (!empty($product['cache_is_pack']) && in_array('pack', $settings['show_badges'])) {
                $badges['pack'] = __('Pack', 'elementor');
            }
        } else {
            $link = $product['url'];
            $cover = !empty($product['cover']) || !$this->noImage ? $product['cover'] : $this->noImage;
            $cover_url = array(
                'desktop' => $cover['bySize'][$image_size]['url'],
            );
            if (!empty($settings['image_size_tablet']) && $settings['image_size_tablet'] != $image_size) {
                $cover_url['tablet'] = $cover['bySize'][$settings['image_size_tablet']]['url'];
            }
            if (!empty($settings['image_size_mobile']) && $settings['image_size_mobile'] != $settings['image_size_tablet']) {
                $cover_url['mobile'] = $cover['bySize'][$settings['image_size_mobile']]['url'];
            }
            $cover_alt = !empty($product['cover']['legend']) ? $product['cover']['legend'] : $product['name'];
            $cover_size = $cover['bySize'][$image_size];
            $atc_url = $product['add_to_cart_url'];
            $on_sale = !empty($product['has_discount']);
            $regular_price = $product['regular_price'];

            if ($on_sale && in_array('sale', $settings['show_badges'])) {
                $badges['sale'] = !empty($settings['badge_sale_text'])
                    ? $settings['badge_sale_text']
                    : $product['percentage' === $product['discount_type'] ? 'discount_percentage' : 'discount_amount_to_display']
                ;
            }
            if (!empty($product['flags']['new']['label']) && in_array('new', $settings['show_badges'])) {
                $badges['new'] = $product['flags']['new']['label'];
            }
            if (!empty($product['flags']['pack']['label']) && in_array('pack', $settings['show_badges'])) {
                $badges['pack'] = $product['flags']['pack']['label'];
            }
        }

        if ($show_atc && empty($atc_url)) {
            $args = array(
                'add' => 1,
                'id_product' => (int) $product['id_product'],
                'ipa' => (int) $product['id_product_attribute'],
                'token' => \Tools::getToken(false),
            );
            if (_CE_PS16_) {
                $args['qty'] = (int) $min_qty;
            }
            $atc_url = $this->context->link->getPageLink('cart', true, null, $args);
        }

        if (!empty($settings['show_description'])) {
            $description = strip_tags($product['description_short']);

            if (!empty($settings['description_length']) && \Tools::strlen($description) > $settings['description_length']) {
                $description = rtrim(\Tools::substr($description, 0, \Tools::strpos($description, ' ', $settings['description_length'])), '-,.') . '...';
            }
        }
        $this->addRenderAttribute($article, array(
            'data-id-product' => $product['id_product'],
            'data-id-product-attribute' => $product['id_product_attribute'],
        ));

        ob_start();
        ?>
        <article class="elementor-product-miniature js-product-miniature" <?php echo _CE_PS16_ ? '' : $this->getRenderAttributeString($article); ?>>
            <a class="elementor-product-link" href="<?php echo esc_attr($link); ?>">
                <div class="elementor-image">
                    <picture class="elementor-cover-image">
                        <?php if (isset($cover_url['mobile'])) : ?>
                            <source media="(max-width: 767px)" srcset="<?php echo esc_attr($cover_url['mobile']); ?>">
                        <?php endif; ?>
                        <?php if (isset($cover_url['tablet'])) : ?>
                            <source media="(max-width: 991px)" srcset="<?php echo esc_attr($cover_url['tablet']); ?>">
                        <?php endif; ?>
                        <img src="<?php echo esc_attr($cover_url['desktop']); ?>" loading="<?php echo $this->loading; ?>" alt="<?php echo esc_attr($cover_alt); ?>"
                            width="<?php echo (int) $cover_size['width']; ?>" height="<?php echo (int) $cover_size['height']; ?>">
                    </picture>
                    <?php
                    if (!empty($settings['show_second_image']) && !empty($product['images'])) :
                        foreach ($product['images'] as $image) :
                            if ($image['id_image'] != $cover['id_image']) :
                                ?>
                                <picture class="elementor-second-image">
                                    <?php if (isset($cover_url['mobile'])) : ?>
                                        <source media="(max-width: 767px)" srcset="<?php echo esc_attr($image['bySize'][$settings['image_size_mobile']]['url']); ?>">
                                    <?php endif; ?>
                                    <?php if (isset($cover_url['tablet'])) : ?>
                                        <source media="(max-width: 991px)" srcset="<?php echo esc_attr($image['bySize'][$settings['image_size_tablet']]['url']); ?>">
                                    <?php endif; ?>
                                    <img src="<?php echo esc_attr($image['bySize'][$image_size]['url']); ?>" loading="lazy" alt="<?php echo esc_attr($image['legend']); ?>"
                                        width="<?php echo (int) $image['bySize'][$image_size]['width']; ?>" height="<?php echo (int) $image['bySize'][$image_size]['height']; ?>">
                                </picture>
                                <?php
                                break;
                            endif;
                        endforeach;
                    endif;
                    ?>
                    <?php if (!empty($settings['show_qv'])) : ?>
                        <div class="elementor-button elementor-quick-view" data-link-action="quickview">
                            <div class="elementor-button-inner">
                                <span class="elementor-button-icon elementor-align-icon-<?php echo $settings['qv_icon_align']; ?>">
                                    <i class="<?php echo esc_attr($settings['qv_icon']); ?>"></i>
                                </span>
                                <span class="elementor-button-text"><?php echo $settings['qv_text']; ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php foreach (array('left', 'right') as $position) : ?>
                    <div class="elementor-badges-<?php echo $position; ?>">
                    <?php foreach ($badges as $badge => $label) : ?>
                        <?php if ($position == $settings["badge_{$badge}_position"]) : ?>
                            <div class="elementor-badge elementor-badge-<?php echo $badge ?>"><?php echo $label ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <div class="elementor-content">
                    <?php if (!empty($settings['show_category'])) : ?>
                        <h4 class="elementor-category"><?php echo $product['category_' . (_CE_PS16_ ? 'default' : 'name')]; ?></h4>
                    <?php endif; ?>
                    <h3 class="elementor-title"><?php echo $product['name']; ?></h3>
                    <?php if (!empty($description)) : ?>
                        <div class="elementor-description"><?php echo $description; ?></div>
                    <?php endif; ?>
                    <?php if ($this->show_prices && $product['show_price']) : ?>
                        <div class="elementor-price-wrapper">
                            <?php if ($on_sale && !empty($settings['show_regular_price'])) : ?>
                                <span class="elementor-price-regular"><?php echo $regular_price; ?></span>
                            <?php endif; ?>
                            <span class="elementor-price"><?php echo $product['price']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php if (_CE_PS16_ && $show_atc) : ?>
                <div class="elementor-atc">
                    <a class="elementor-button ajax_add_to_cart_button elementor-size-<?php echo $settings['atc_size']; ?>" href="<?php echo esc_attr($atc_url) ?>" <?php echo $this->getRenderAttributeString($article); ?>>
                        <?php if (!empty($settings['atc_icon'])) : ?>
                            <span class="elementor-atc-icon elementor-align-icon-<?php echo $settings['atc_icon_align']; ?>">
                                <i class="<?php echo $settings['atc_icon']; ?>"></i>
                            </span>
                        <?php endif; ?>
                        <span class="elementor-button-text"><?php echo $settings['atc_text']; ?></span>
                    </a>
                </div>
            <?php elseif ($show_atc) : ?>
                <form class="elementor-atc" action="<?php echo esc_attr($atc_url); ?>">
                    <input type="hidden" name="qty" value="<?php echo (int) $min_qty; ?>">
                    <button type="submit" class="elementor-button elementor-size-<?php echo $settings['atc_size']; ?>" data-button-action="add-to-cart">
                        <?php if (!empty($settings['atc_icon'])) : ?>
                            <span class="elementor-atc-icon elementor-align-icon-<?php echo $settings['atc_icon_align']; ?>">
                                <i class="<?php echo $settings['atc_icon']; ?>"></i>
                            </span>
                        <?php endif; ?>
                        <span class="elementor-button-text"><?php echo $settings['atc_text']; ?></span>
                    </button>
                </form>
            <?php endif; ?>
        </article>
        <?php
        return ob_get_clean();
    }

    public function onImport($widget)
    {
        static $id_product;

        if (null === $id_product) {
            $products = \Product::getProducts(\Context::getContext()->language->id, 0, 1, 'id_product', 'ASC', false, true);
            $id_product = !empty($products[0]['id_product']) ? $products[0]['id_product'] : '';
        }

        // Check Category ID
        if (!empty($widget['settings']['category_id'])) {
            $category = new \Category($widget['settings']['category_id']);

            if (!$category->id) {
                $widget['settings']['category_id'] = \Context::getContext()->shop->id_category;
            }
        }

        // Check Product ID
        if (!empty($widget['settings']['product_id'])) {
            $product = new \Product($widget['settings']['product_id']);

            if (!$product->id) {
                $widget['settings']['product_id'] = $id_product;
            }
        }

        // Check Product IDs
        if (!empty($widget['settings']['products'])) {
            $table = _DB_PREFIX_ . 'product';
            $prods = array();
            $ids = array();

            foreach ($widget['settings']['products'] as &$prod) {
                $ids[] = (int) $prod['id'];
            }
            $ids = implode(', ', $ids);
            $rows = \Db::getInstance()->executeS("SELECT id_product FROM $table WHERE id_product IN ($ids)");

            foreach ($rows as &$row) {
                $prods[$row['id_product']] = true;
            }

            foreach ($widget['settings']['products'] as &$prod) {
                if ($prod['id'] && empty($prods[$prod['id']])) {
                    $prod['id'] = $id_product;
                }
            }
        }
        return $widget;
    }

    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        if ($this->getSettings('skin') != 'custom') {
            if ($wrapfix = Helper::getWrapfix()) {
                $this->addRenderAttribute('_wrapper', 'class', $wrapfix);
            } elseif (_CE_PS16_) {
                $this->addRenderAttribute('_wrapper', 'class', 'wrapfix');
            }
        }
    }

    public function renderPlainContent()
    {
    }
}
