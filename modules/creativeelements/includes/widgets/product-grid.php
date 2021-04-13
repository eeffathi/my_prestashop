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

class WidgetProductGrid extends WidgetProductBase
{
    public function getName()
    {
        return 'product-grid';
    }

    public function getTitle()
    {
        return __('Product Grid', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-posts-grid';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_grid_settings',
            array(
                'label' => __('Product Grid', 'elementor'),
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

        $this->addListingControls('num_of_prods');

        $this->addResponsiveControl(
            'columns',
            array(
                'label' => __('Columns', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-product-grid' => '-ms-grid-columns: repeat({{VALUE}}, minmax(0, 1fr)); grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ),
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 1,
                'separator' => 'before',
            )
        );

        $this->endControlsSection();

        $this->addMiniatureControls();

        $this->startControlsSection(
            'section_style_product',
            array(
                'label' => __('Product Box', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'product_column_gap',
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
                    '{{WRAPPER}} .elementor-product-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'product_row_gap',
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
                    '{{WRAPPER}} .elementor-product-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $product_selector = '{{WRAPPER}}:not(.wrapfix) .elementor-product-grid > *, {{WRAPPER}}.wrapfix .elementor-product-grid > * > *';
        $product_selector_hover = '{{WRAPPER}}:not(.wrapfix) .elementor-product-grid > :hover, {{WRAPPER}}.wrapfix .elementor-product-grid > * > :hover';

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

        if ($settings['randomize'] && $settings['listing'] == 'category') {
            $settings['order_by'] = 'rand';
        }
        $products = $this->getProducts(
            $settings['listing'],
            $settings['order_by'],
            $settings['order_dir'],
            $settings['num_of_prods'],
            $settings['category_id'],
            $settings['products']
        );

        if (empty($products)) {
            return;
        }

        if ('custom' === $settings['skin']) {
            // Custom Skin
            echo '<div class="elementor-product-grid">';
            foreach ($products as &$product) {
                echo $this->fetchMiniature($product, $settings);
            }
            echo '</div>';
        } elseif (_CE_PS16_) {
            // Theme Skin PS 1.6
            $tpl = _PS_THEME_DIR_ . 'product-list.tpl';

            if (empty($products) || !file_exists($tpl)) {
                return;
            }

            $this->context->smarty->assign(array(
                'id' => 'elementor-product-grid-' . $this->getId(),
                'class' => 'elementor-product-grid',
                'products' => $products,
            ));
            echo $this->context->smarty->fetch($tpl);
        } else {
            // Theme Skin PS 1.7+
            $tpl = "catalog/_partials/miniatures/{$settings['skin']}.tpl";

            if (empty($products) || !(file_exists(_PS_THEME_DIR_ . "templates/$tpl") || file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl"))) {
                return;
            }

            echo '<div class="elementor-product-grid">';
            foreach ($products as &$product) {
                $this->context->smarty->assign('product', $product);
                echo $this->context->smarty->fetch($tpl);
            }
            echo '</div>';
        }
    }
}
