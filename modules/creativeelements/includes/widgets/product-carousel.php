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

class WidgetProductCarousel extends WidgetProductBase
{
    public function getName()
    {
        return 'product-carousel';
    }

    public function getTitle()
    {
        return __('Product Carousel', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            array(
                'label' => __('Product Carousel', 'elementor'),
            )
        );

        $this->addControl(
            'skin',
            array(
                'label' => __('Skin', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => $this->_skinOptions(),
                'default' => 'product',
            )
        );

        $this->addListingControls();

        $this->endControlsSection();

        $this->addMiniatureControls();

        $this->startControlsSection(
            'section_additional_options',
            array(
                'label' => __('Carousel Settings', 'elementor'),
                'type' => ControlsManager::SECTION,
            )
        );

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->addResponsiveControl(
            'slides_to_show',
            array(
                'label' => __('Slides to Show', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Default', 'elementor'),
                ) + $slides_to_show,
            )
        );

        $this->addControl(
            'slides_to_scroll',
            array(
                'label' => __('Slides to Scroll', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '2',
                'options' => $slides_to_show,
                'condition' => array(
                    'slides_to_show!' => '1',
                ),
            )
        );

        $this->addControl(
            'navigation',
            array(
                'label' => __('Navigation', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'both',
                'options' => array(
                    'both' => __('Arrows and Dots', 'elementor'),
                    'arrows' => __('Arrows', 'elementor'),
                    'dots' => __('Dots', 'elementor'),
                    'none' => __('None', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'additional_options',
            array(
                'label' => __('Additional Options', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'pause_on_hover',
            array(
                'label' => __('Pause on Hover', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'elementor'),
                    'no' => __('No', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'autoplay',
            array(
                'label' => __('Autoplay', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'elementor'),
                    'no' => __('No', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'autoplay_speed',
            array(
                'label' => __('Autoplay Speed', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 5000,
            )
        );

        $this->addControl(
            'infinite',
            array(
                'label' => __('Infinite Loop', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'elementor'),
                    'no' => __('No', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'effect',
            array(
                'label' => __('Effect', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide' => __('Slide', 'elementor'),
                    'fade' => __('Fade', 'elementor'),
                ),
                'condition' => array(
                    'slides_to_show' => '1',
                ),
            )
        );

        $this->addControl(
            'speed',
            array(
                'label' => __('Animation Speed', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => 500,
            )
        );

        $this->addControl(
            'direction',
            array(
                'label' => __('Direction', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'ltr',
                'options' => array(
                    'ltr' => __('Left', 'elementor'),
                    'rtl' => __('Right', 'elementor'),
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_product',
            array(
                'label' => __('Product Box', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'product_spacing_custom',
            array(
                'label' => __('Columns Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-list' => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);',
                ),
            )
        );

        $this->addResponsiveControl(
            'product_spacing_row',
            array(
                'label' => __('Rows Gap', 'elementor'),
                'separator' => '',
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'em'),
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                ),
            )
        );

        $product_selector = '{{WRAPPER}}:not(.wrapfix) .slick-slide-inner > *, {{WRAPPER}}.wrapfix .slick-slide-inner > * > *';
        $product_selector_hover = '{{WRAPPER}}:not(.wrapfix) .slick-slide-inner > :hover, {{WRAPPER}}.wrapfix .slick-slide-inner > * > :hover';

        $this->addResponsiveControl(
            'product_padding',
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
                    $product_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ),
            )
        );

        $this->addControl(
            'product_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    $product_selector => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $this->addControl(
            'product_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'separator' => '',
                'selectors' => array(
                    $product_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->startControlsTabs('product_style_tabs');

        $this->startControlsTab(
            'product_style_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'product_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    $product_selector => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'product_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-product-miniature' => 'background: {{VALUE}};',
                ),
                'condition' => array(
                    'skin' => 'custom',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'product_box_shadow',
                'separator' => '',
                'selector' => $product_selector,
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'product_style_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'product_border_color_hover',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    $product_selector_hover => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'product_bg_color_hover',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-product-miniature:hover' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'skin' => 'custom',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'product_box_shadow_hover',
                'separator' => '',
                'selector' => $product_selector_hover,
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->addMiniatureStyleControls();

        $this->startControlsSection(
            'section_style_navigation',
            array(
                'label' => __('Navigation', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'navigation' => array('arrows', 'dots', 'both'),
                ),
            )
        );

        $this->addControl(
            'heading_style_arrows',
            array(
                'label' => __('Arrows', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );

        $this->addControl(
            'arrows_position',
            array(
                'label' => __('Arrows Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'inside',
                'options' => array(
                    'inside' => __('Inside', 'elementor'),
                    'outside' => __('Outside', 'elementor'),
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );

        $this->addControl(
            'arrows_size',
            array(
                'label' => __('Arrows Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 60,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-prev:before, {{WRAPPER}} .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );

        $this->addControl(
            'arrows_color',
            array(
                'label' => __('Arrows Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-prev:before, {{WRAPPER}} .slick-slider .slick-next:before' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );
        $this->addControl(
            'arrows_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-prev, {{WRAPPER}} .slick-slider .slick-next' => 'background: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );

        $this->addControl(
            'heading_style_dots',
            array(
                'label' => __('Dots', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );

        $this->addControl(
            'dots_position',
            array(
                'label' => __('Dots Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'outside',
                'options' => array(
                    'outside' => __('Outside', 'elementor'),
                    'inside' => __('Inside', 'elementor'),
                ),
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );

        $this->addControl(
            'dots_size',
            array(
                'label' => __('Dots Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 5,
                        'max' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-carousel .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );

        $this->addControl(
            'dots_color',
            array(
                'label' => __('Dots Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-carousel .slick-dots li button:before' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        if (is_admin() && $this->getSettings('skin') !== 'custom') {
            return print '<div class="ce-remote-render"></div>';
        }
        if (empty($this->context->currency->id)) {
            return;
        }

        $settings = $this->getSettings();

        if ($settings['randomize'] && ($settings['listing'] == 'category' || $settings['listing'] == 'products')) {
            $settings['order_by'] = 'rand';
        }

        $products = $this->getProducts(
            $settings['listing'],
            $settings['order_by'],
            $settings['order_dir'],
            $settings['limit'],
            $settings['category_id'],
            $settings['products']
        );
        $boxes = array();

        if (empty($products)) {
            return;
        }

        if ('custom' === $settings['skin']) {
            // Custom Skin
            foreach ($products as &$product) {
                $boxes[] = '<div><div class="slick-slide-inner">' . $this->fetchMiniature($product, $settings) . '</div></div>';
            }
        } elseif (_CE_PS16_) {
            // Theme Skin PS 1.6
            $tpl = _PS_THEME_DIR_ . 'product-list.tpl';

            if (!file_exists($tpl)) {
                return;
            }

            foreach ($products as &$product) {
                $this->context->smarty->assign(array(
                    'id' => "slick-slide-inner-{$this->getId()}-{$product['id_product']}",
                    'class' => 'slick-slide-inner',
                    'products' => array($product),
                ));
                $boxes[] = '<div>' . $this->context->smarty->fetch($tpl) . '</div>';
            }
        } else {
            // Theme Skin PS 1.7+
            $tpl = "catalog/_partials/miniatures/{$settings['skin']}.tpl";

            if (!file_exists(_PS_THEME_DIR_ . "templates/$tpl") && !file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl")) {
                return;
            }

            foreach ($products as &$product) {
                $this->context->smarty->assign('product', $product);
                $boxes[] = '<div><div class="slick-slide-inner">' . $this->context->smarty->fetch($tpl) . '</div></div>';
            }
        }

        $is_slideshow = '1' === $settings['slides_to_show'];
        $is_rtl = 'rtl' === $settings['direction'];
        $direction = $is_rtl ? 'rtl' : 'ltr';
        $show_dots = in_array($settings['navigation'], array('dots', 'both'));
        $show_arrows = in_array($settings['navigation'], array('arrows', 'both'));

        $slick_options = array(
            'slidesToShow' => empty($settings['slides_to_show']) ? 4 : absint($settings['slides_to_show']),
            'slidesToShowTablet' => empty($settings['slides_to_show_tablet']) ? 3 : absint($settings['slides_to_show_tablet']),
            'slidesToShowMobile' => empty($settings['slides_to_show_mobile']) ? 1 : absint($settings['slides_to_show_mobile']),
            'autoplaySpeed' => absint($settings['autoplay_speed']),
            'autoplay' => ('yes' === $settings['autoplay']),
            'infinite' => ('yes' === $settings['infinite']),
            'pauseOnHover' => ('yes' === $settings['pause_on_hover']),
            'speed' => absint($settings['speed']),
            'arrows' => $show_arrows,
            'dots' => $show_dots,
            'rtl' => $is_rtl,
        );
        if (!$is_slideshow) {
            $slick_options['slidesToScroll'] = absint($settings['slides_to_scroll']);
        } else {
            $slick_options['fade'] = ('fade' === $settings['effect']);
        }

        $carousel_classes = array('elementor-image-carousel');

        if ($show_arrows) {
            $carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
        }
        if ($show_dots) {
            $carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
        }
        ?>
        <div class="elementor-image-carousel-wrapper elementor-slick-slider" dir="<?php echo $direction; ?>">
            <div class="<?php echo implode(' ', $carousel_classes); ?>" data-slider_options='<?php echo json_encode($slick_options); ?>'>
                <?php echo implode('', $boxes); ?>
            </div>
        </div>
        <?php
    }
}
