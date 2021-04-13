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

class WidgetProductBox extends WidgetProductBase
{
    public function getName()
    {
        return 'product-box';
    }

    public function getTitle()
    {
        return __('Product Box', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-info-box';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_box',
            array(
                'label' => __('Product Box', 'elementor'),
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

        $prods = !empty($this->context->employee)
            ? \Product::getProducts($this->context->language->id, 0, 1, 'id_product', 'ASC', false, true)
            : array()
        ;
        $this->addControl(
            'product_id',
            array(
                'label' => __('Product ID', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => !empty($prods[0]['id_product']) ? $prods[0]['id_product'] : 1,
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

        $product_selector = '{{WRAPPER}}:not(.wrapfix) .elementor-product-box > *, {{WRAPPER}}.wrapfix .elementor-product-box > * > *';
        $product_selector_hover = '{{WRAPPER}}:not(.wrapfix) .elementor-product-box > :hover, {{WRAPPER}}.wrapfix .elementor-product-box > * > :hover';

        $this->addResponsiveControl(
            'align',
            array(
                'label' => __('Alignment', 'elementor'),
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
                    '' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-product-box' => 'text-align: {{VALUE}};',
                    $product_selector => 'display: inline-block;',
                ),
            )
        );

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
        $product = $this->getProduct($settings['product_id']);

        if (empty($product)) {
            return;
        }

        if ('custom' === $settings['skin']) {
            // Custom Skin
            echo '<div class="elementor-product-box">' . $this->fetchMiniature($product, $settings) . '</div>';
        } elseif (_CE_PS16_) {
            // Theme Skin PS 1.6
            $tpl = _PS_THEME_DIR_ . 'product-list.tpl';

            if (!file_exists($tpl)) {
                return;
            }
            $this->context->smarty->assign(array(
                'id' => 'elementor-product-box-' . $this->getId(),
                'class' => 'elementor-product-box',
                'products' => array($product),
            ));
            echo $this->context->smarty->fetch($tpl);
        } else {
            // Theme Skin PS 1.7+
            $tpl = "catalog/_partials/miniatures/{$settings['skin']}.tpl";

            if (!file_exists(_PS_THEME_DIR_ . "templates/$tpl") && !file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl")) {
                return;
            }
            $this->context->smarty->assign('product', $product);

            echo '<div class="elementor-product-box">' . $this->context->smarty->fetch($tpl) . '</div>';
        }
    }
}
