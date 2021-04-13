<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

class WidgetCallToAction extends WidgetBase
{
    public function getName()
    {
        return 'call-to-action';
    }

    public function getTitle()
    {
        return __('Call to Action', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-image-rollover';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_cta',
            array(
                'label' => __('Call to Action', 'elementor'),
            )
        );

        $this->addControl(
            'skin',
            array(
                'label' => __('Skin', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'classic' => __('Classic', 'elementor'),
                    'cover' => __('Cover', 'elementor'),
                ),
                'render_type' => 'template',
                'prefix_class' => 'elementor-cta--skin-',
                'default' => 'classic',
            )
        );

        $this->addControl(
            'bg_image',
            array(
                'label' => __('Choose Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
                'seo' => true,
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'layout',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'above' => array(
                        'title' => __('Above', 'elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'prefix_class' => 'elementor-cta-%s-layout-image-',
                'condition' => array(
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                ),
            )
        );

        $this->addResponsiveControl(
            'image_min_width',
            array(
                'label' => __('Min. Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-bg-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                    'layout' => array('left', 'right'),
                ),
            )
        );

        $this->addResponsiveControl(
            'image_min_height',
            array(
                'label' => __('Min. Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units' => array('px', 'vh'),

                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-bg-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                    'layout' => array('', 'above'),
                ),
            )
        );

        $this->addControl(
            'ribbon_title',
            array(
                'label' => __('Ribbon', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'ribbon_horizontal_position',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'ribbon_title!' => '',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_content',
            array(
                'label' => __('Content', 'elementor'),
            )
        );

        $this->addControl(
            'graphic_element',
            array(
                'label' => __('Graphic Element', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'none' => array(
                        'title' => __('None', 'elementor'),
                        'icon' => 'fa fa-ban',
                    ),
                    'image' => array(
                        'title' => __('Image', 'elementor'),
                        'icon' => 'fa fa-picture-o',
                    ),
                    'icon' => array(
                        'title' => __('Icon', 'elementor'),
                        'icon' => 'fa fa-star',
                    ),
                ),
                'separator' => 'before',
                'default' => 'none',
            )
        );

        $this->addControl(
            'graphic_image',
            array(
                'label' => __('Choose Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
                'condition' => array(
                    'graphic_element' => 'image',
                ),
            )
        );

        $this->addControl(
            'icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'default' => 'fa fa-star',
                'condition' => array(
                    'graphic_element' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'default' => __('Default', 'elementor'),
                    'stacked' => __('Stacked', 'elementor'),
                    'framed' => __('Framed', 'elementor'),
                ),
                'default' => 'default',
                'separator' => '',
                'condition' => array(
                    'graphic_element' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_shape',
            array(
                'label' => __('Shape', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'circle' => __('Circle', 'elementor'),
                    'square' => __('Square', 'elementor'),
                ),
                'default' => 'circle',
                'separator' => '',
                'condition' => array(
                    'icon_view!' => 'default',
                    'graphic_element' => 'icon',
                ),
            )
        );

        $this->addControl(
            'title',
            array(
                'label' => __('Title & Description', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => __('This is the heading', 'elementor'),
                'placeholder' => __('Enter your title', 'elementor'),
                'label_block' => true,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_text',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementor'),
                'placeholder' => __('Enter your description', 'elementor'),
                'separator' => 'none',
                'rows' => 5,
                'show_label' => false,
            )
        );

        $this->addControl(
            'title_tag',
            array(
                'label' => __('Title HTML Tag', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                ),
                'default' => 'h2',
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addControl(
            'button',
            array(
                'label' => __('Button Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'default' => __('Click Here', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link', 'elementor'),
                'type' => ControlsManager::URL,
                'placeholder' => __('https://your-link.com', 'elementor'),
            )
        );

        $this->addControl(
            'link_click',
            array(
                'label' => __('Apply Link On', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'box' => __('Whole Box', 'elementor'),
                    'button' => __('Button Only', 'elementor'),
                ),
                'default' => 'button',
                'separator' => 'none',
                'condition' => array(
                    'button!' => '',
                    'link[url]!' => '',
                ),
            )
        );

        $this->addControl(
            'button_icon',
            array(
                'label' => __('Button Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
                'condition' => array(
                    'button!' => '',
                ),
            )
        );

        $this->addControl(
            'button_icon_align',
            array(
                'label' => __('Icon Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => __('Before', 'elementor'),
                    'right' => __('After', 'elementor'),
                ),
                'condition' => array(
                    'button!' => '',
                    'button_icon!' => '',
                ),
            )
        );

        $this->addControl(
            'button_icon_indent',
            array(
                'label' => __('Icon Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'button!' => '',
                    'button_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_box',
            array(
                'label' => __('Box', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'min-height',
            array(
                'label' => __('Min. Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'size_units' => array('px', 'vh'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-content' => 'min-height: {{SIZE}}{{UNIT}}',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'alignment',
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
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-content' => 'text-align: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'vertical_position',
            array(
                'label' => __('Vertical Position', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => array(
                    'top' => array(
                        'title' => __('Top', 'elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => __('Middle', 'elementor'),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => __('Bottom', 'elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'prefix_class' => 'elementor-cta--valign-',
                'separator' => 'none',
            )
        );

        $this->addResponsiveControl(
            'padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_ribbon',
            array(
                'label' => __('Ribbon', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'ribbon_title!' => '',
                ),
            )
        );

        $ribbon_distance_transform = is_rtl() ?
            'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

        $this->addResponsiveControl(
            'ribbon_distance',
            array(
                'label' => __('Distance', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
                ),
            )
        );

        $this->addControl(
            'ribbon_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'ribbon_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'ribbon_typography',
                'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'graphic_element' => 'image',
                ),
            )
        );

        $this->addControl(
            'graphic_image_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'graphic_image_width',
            array(
                'label' => __('Size (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('%'),
                'default' => array(
                    'unit' => '%',
                ),
                'range' => array(
                    '%' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-image img' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addControl(
            'graphic_image_opacity',
            array(
                'label' => __('Opacity', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-image' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'graphic_image_border',
                'selector' => '{{WRAPPER}} .elementor-cta-image img',
            )
        );

        $this->addControl(
            'graphic_image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'graphic_element' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'icon_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
                'separator' => '',
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'icon_size',
            array(
                'label' => __('Icon Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_padding',
            array(
                'label' => __('Icon Padding', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'separator' => '',
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'icon_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
                ),
                'separator' => '',
                'condition' => array(
                    'icon_view' => 'framed',
                ),
            )
        );

        $this->addControl(
            'icon_rotate',
            array(
                'label' => __('Icon Rotate', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'unit' => 'deg',
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->addControl(
            'icon_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'title',
                            'operator' => '!==',
                            'value' => '',
                        ),
                        array(
                            'name' => 'description_text',
                            'operator' => '!==',
                            'value' => '',
                        ),
                        array(
                            'name' => 'button',
                            'operator' => '!==',
                            'value' => '',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'heading_style_title',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Title', 'elementor'),
                'separator' => 'before',
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addResponsiveControl(
            'title_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cta-title',
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addControl(
            'heading_style_description',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Description', 'elementor'),
                'separator' => 'before',
                'condition' => array(
                    'description_text!' => '',
                ),
            )
        );

        $this->addResponsiveControl(
            'description_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'description_text!' => '',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-cta-description',
                'condition' => array(
                    'description_text!' => '',
                ),
            )
        );

        $this->addControl(
            'heading_content_colors',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Colors', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->startControlsTabs('color_tabs');

        $this->startControlsTab(
            'colors_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Title Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-title' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => __('Description Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-description' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'description_text!' => '',
                ),
            )
        );

        $this->addControl(
            'content_bg_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-content' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'skin' => 'classic',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'colors_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'title_color_hover',
            array(
                'label' => __('Title Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-title' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->addControl(
            'description_color_hover',
            array(
                'label' => __('Description Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-description' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'description_text!' => '',
                ),
            )
        );

        $this->addControl(
            'content_bg_color_hover',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-content' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'skin' => 'classic',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_button',
            array(
                'label' => __('Button', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'button!' => '',
                ),
            )
        );

        $this->addControl(
            'button_size',
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
                'name' => 'button_typography',
                'label' => __('Typography', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-button',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            )
        );

        $this->startControlsTabs('button_tabs');

        $this->startControlsTab(
            'button_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'button_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'button-hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'button_hover_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_hover_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_hover_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
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
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'button_border_radius',
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
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_hover_effects',
            array(
                'label' => __('Hover Effects', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'content_hover_heading',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Content', 'elementor'),
                'separator' => 'before',
                'condition' => array(
                    'skin' => 'cover',
                ),
            )
        );

        $this->addControl(
            'content_animation',
            array(
                'label' => __('Hover Animation', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => 'None',
                    'enter-from-right' => 'Slide In Right',
                    'enter-from-left' => 'Slide In Left',
                    'enter-from-top' => 'Slide In Up',
                    'enter-from-bottom' => 'Slide In Down',
                    'enter-zoom-in' => 'Zoom In',
                    'enter-zoom-out' => 'Zoom Out',
                    'fade-in' => 'Fade In',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'move-right' => 'Move Right',
                    'move-left' => 'Move Left',
                    'move-up' => 'Move Up',
                    'move-down' => 'Move Down',
                    'exit-to-right' => 'Slide Out Right',
                    'exit-to-left' => 'Slide Out Left',
                    'exit-to-top' => 'Slide Out Up',
                    'exit-to-bottom' => 'Slide Out Down',
                    'exit-zoom-in' => 'Zoom In',
                    'exit-zoom-out' => 'Zoom Out',
                    'fade-out' => 'Fade Out',
                ),
                'default' => 'grow',
                'condition' => array(
                    'skin' => 'cover',
                ),
            )
        );

        /*
         *
         * Add class 'elementor-animated-content' to widget when assigned content animation
         *
         */
        $this->addControl(
            'animation_class',
            array(
                'label' => 'Animation',
                'type' => ControlsManager::HIDDEN,
                'default' => 'animated-content',
                'prefix_class' => 'elementor-',
                'condition' => array(
                    'content_animation!' => '',
                ),
            )
        );

        $this->addControl(
            'content_animation_duration',
            array(
                'label' => __('Animation Duration', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => array(
                    'size' => 1000,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 3000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-content-item' => 'transition-duration: {{SIZE}}ms',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(2)' => 'transition-delay: calc( {{SIZE}}ms / 3 )',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(3)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 2 )',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(4)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 3 )',
                ),
                'condition' => array(
                    'content_animation!' => '',
                    'skin' => 'cover',
                ),
            )
        );

        $this->addControl(
            'sequenced_animation',
            array(
                'label' => __('Sequenced Animation', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('On', 'elementor'),
                'label_off' => __('Off', 'elementor'),
                'return_value' => 'elementor-cta--sequenced-animation',
                'prefix_class' => '',
                'condition' => array(
                    'content_animation!' => '',
                    'skin' => 'cover',
                ),
            )
        );

        $this->addControl(
            'background_hover_heading',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Background', 'elementor'),
                'separator' => 'before',
                'condition' => array(
                    'skin' => 'cover',
                ),
            )
        );

        $this->addControl(
            'transformation',
            array(
                'label' => __('Hover Animation', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => 'None',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'move-left' => 'Move Left',
                    'move-right' => 'Move Right',
                    'move-up' => 'Move Up',
                    'move-down' => 'Move Down',
                ),
                'default' => 'zoom-in',
                'prefix_class' => 'elementor-bg-transform elementor-bg-transform-',
            )
        );

        $this->startControlsTabs('bg_effects_tabs');

        $this->startControlsTab(
            'normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'overlay_color',
            array(
                'label' => __('Overlay Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta:not(:hover) .elementor-cta-bg-overlay' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'overlay_blend_mode',
            array(
                'label' => __('Blend Mode', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('Normal', 'elementor'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'color-burn' => 'Color Burn',
                    'hue' => 'Hue',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'exclusion' => 'Exclusion',
                    'luminosity' => 'Luminosity',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'overlay_color_hover',
            array(
                'label' => __('Overlay Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-bg-overlay' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'effect_duration',
            array(
                'label' => __('Transition Duration', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => array(
                    'size' => 1500,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 3000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-cta .elementor-cta-bg, {{WRAPPER}} .elementor-cta .elementor-cta-bg-overlay' => 'transition-duration: {{SIZE}}ms',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();
        $title_tag = $settings['title_tag'];
        $wrapper_tag = 'div';
        $button_tag = 'a';
        $link_url = empty($settings['link']['url']) ? false : $settings['link']['url'];
        $content_animation = $settings['content_animation'];
        $animation_class = '';
        $print_bg = true;
        $print_content = true;

        if (!empty($settings['bg_image']['url'])) {
            $bg_image = &$settings['bg_image'];

            $this->addRenderAttribute('bg_image', array(
                'src' => Helper::getMediaLink($bg_image['url']),
                'alt' => isset($bg_image['alt']) ? $bg_image['alt'] : '',
                'loading' => 'lazy',
            ));
        } elseif ('classic' === $settings['skin']) {
            $print_bg = false;
        }

        if (empty($settings['title']) && empty($settings['description_text']) && empty($settings['button']) && 'none' == $settings['graphic_element']) {
            $print_content = false;
        }

        $this->addRenderAttribute('title', 'class', array(
            'elementor-cta-title',
            'elementor-content-item',
        ));
        $this->addRenderAttribute('description', 'class', array(
            'elementor-cta-description',
            'elementor-content-item',
        ));
        $this->addRenderAttribute('button', 'class', array(
            'elementor-button',
            'elementor-size-' . $settings['button_size'],
        ));
        $this->addRenderAttribute('graphic_element', 'class', 'elementor-content-item');

        if ('icon' === $settings['graphic_element']) {
            $this->addRenderAttribute(
                'graphic_element',
                'class',
                array(
                    'elementor-icon-wrapper',
                    'elementor-cta-icon',
                )
            );
            $this->addRenderAttribute('graphic_element', 'class', 'elementor-view-' . $settings['icon_view']);

            if ('default' != $settings['icon_view']) {
                $this->addRenderAttribute('graphic_element', 'class', 'elementor-shape-' . $settings['icon_shape']);
            }
            if (!empty($settings['icon'])) {
                $this->addRenderAttribute('icon', 'class', $settings['icon']);
            }
        } elseif ('image' === $settings['graphic_element'] && !empty($settings['graphic_image']['url'])) {
            $this->addRenderAttribute('graphic_element', 'class', 'elementor-cta-image');
        }

        if (!empty($content_animation) && 'cover' == $settings['skin']) {
            $animation_class = 'elementor-animated-item--' . $content_animation;

            $this->addRenderAttribute('title', 'class', $animation_class);
            $this->addRenderAttribute('graphic_element', 'class', $animation_class);
            $this->addRenderAttribute('description', 'class', $animation_class);
        }

        if (!empty($link_url)) {
            if ('box' === $settings['link_click'] || empty($settings['button'])) {
                $wrapper_tag = 'a';
                $button_tag = 'button';
                $this->addRenderAttribute('wrapper', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('wrapper', 'target', '_blank');
                }
            } else {
                $this->addRenderAttribute('button', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('button', 'target', '_blank');
                }
            }
        }
        ?>
        <<?php echo $wrapper_tag . ' ' . $this->getRenderAttributeString('wrapper'); ?> class="elementor-cta">
        <?php if ($print_bg) : ?>
            <div class="elementor-cta-bg-wrapper">
                <?php if (isset($bg_image)) : ?>
                    <img class="elementor-cta-bg elementor-bg" <?php echo $this->getRenderAttributeString('bg_image'); ?>>
                <?php endif; ?>
                <div class="elementor-cta-bg-overlay"></div>
            </div>
        <?php endif;?>
        <?php if ($print_content) : ?>
            <div class="elementor-cta-content">
                <?php if ('image' === $settings['graphic_element'] && !empty($settings['graphic_image']['url'])) : ?>
                    <div <?php echo $this->getRenderAttributeString('graphic_element'); ?>>
                        <?php echo GroupControlImageSize::getAttachmentImageHtml($settings, 'graphic_image'); ?>
                    </div>
                <?php elseif ('icon' === $settings['graphic_element'] && !empty($settings['icon'])) : ?>
                    <div <?php echo $this->getRenderAttributeString('graphic_element'); ?>>
                        <div class="elementor-icon">
                            <i <?php echo $this->getRenderAttributeString('icon'); ?>></i>
                        </div>
                    </div>
                <?php endif;?>

                <?php if (!empty($settings['title'])) : ?>
                    <<?php echo $title_tag . ' ' . $this->getRenderAttributeString('title'); ?>>
                        <?php echo $settings['title']; ?>
                    </<?php echo $title_tag; ?>>
                <?php endif;?>

                <?php if (!empty($settings['description_text'])) : ?>
                    <div <?php echo $this->getRenderAttributeString('description'); ?>>
                        <?php echo $settings['description_text']; ?>
                    </div>
                <?php endif;?>

                <?php if (!empty($settings['button'])) : ?>
                    <div class="elementor-cta-button-wrapper elementor-content-item <?php echo $animation_class; ?>">
                    <<?php echo $button_tag . ' ' . $this->getRenderAttributeString('button'); ?>>
                        <?php if (!empty($settings['button_icon'])) : ?>
                            <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($settings['button_icon_align']); ?>">
                                <i class="<?php echo esc_attr($settings['button_icon']); ?>"></i>
                            </span>
                        <?php endif;?>
                        <span class="elementor-button-text"><?php echo $settings['button']; ?></span>
                    </<?php echo $button_tag; ?>>
                    </div>
                <?php endif;?>
            </div>
        <?php endif;?>
        <?php if (!empty($settings['ribbon_title'])) : ?>
            <?php
            $this->addRenderAttribute('ribbon-wrapper', 'class', 'elementor-ribbon');

            if (!empty($settings['ribbon_horizontal_position'])) {
                $this->addRenderAttribute('ribbon-wrapper', 'class', 'elementor-ribbon-' . $settings['ribbon_horizontal_position']);
            }
            ?>
            <div <?php echo $this->getRenderAttributeString('ribbon-wrapper'); ?>>
                <div class="elementor-ribbon-inner"><?php echo $settings['ribbon_title']; ?></div>
            </div>
        <?php endif;?>
        </<?php echo $wrapper_tag; ?>>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var view = new CeView(),
            wrapperTag = 'div',
            buttonTag = 'a',
            animationClass,
            btnSizeClass = 'elementor-size-' + settings.button_size,
            printBg = true,
            printContent = true;

        if ( 'box' === settings.link_click || !settings.button ) {
            wrapperTag = 'a';
            buttonTag = 'button';
            view.addRenderAttribute( 'wrapper', 'href', '#' );
        }

        if ( settings.bg_image.url ) {
            view.addRenderAttribute( 'bg_image', 'src', elementor.imagesManager.getImageUrl( settings.bg_image ) );
        } else if ( 'classic' === settings.skin ) {
            printBg = false;
        }

        if ( ! settings.title && ! settings.description_text && ! settings.button && 'none' == settings.graphic_element ) {
            printContent = false;
        }

        if ( 'icon' === settings.graphic_element ) {
            var iconWrapperClasses = 'elementor-icon-wrapper';
                iconWrapperClasses += ' elementor-cta-image';
                iconWrapperClasses += ' elementor-view-' + settings.icon_view;
            if ( 'default' !== settings.icon_view ) {
                iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
            }
            view.addRenderAttribute( 'graphic_element', 'class', iconWrapperClasses );

        } else if ( 'image' === settings.graphic_element && settings.graphic_image.url ) {
            var imageUrl = elementor.imagesManager.getImageUrl( settings.graphic_image );

            view.addRenderAttribute( 'graphic_element', 'class', 'elementor-cta-image' );
        }

        if ( settings.content_animation && 'cover' === settings.skin ) {
            var animationClass = 'elementor-animated-item--' + settings.content_animation;

            view.addRenderAttribute( 'title', 'class', animationClass );
            view.addRenderAttribute( 'description', 'class', animationClass );
            view.addRenderAttribute( 'graphic_element', 'class', animationClass );
        }

        view.addRenderAttribute( 'title', 'class', [ 'elementor-cta-title', 'elementor-content-item' ] );
        view.addRenderAttribute( 'description', 'class', [ 'elementor-cta-description', 'elementor-content-item' ] );
        view.addRenderAttribute( 'button', 'class', [ 'elementor-button', btnSizeClass ] );
        view.addRenderAttribute( 'graphic_element', 'class', 'elementor-content-item' );
        #>

        <{{ wrapperTag }} class="elementor-cta" {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

        <# if ( printBg ) { #>
            <div class="elementor-cta-bg-wrapper">
                <# if ( settings.bg_image.url ) { #>
                    <img class="elementor-cta-bg elementor-bg" {{{ view.getRenderAttributeString( 'bg_image' ) }}}>
                <# } #>
                <div class="elementor-cta-bg-overlay"></div>
            </div>
        <# } #>
        <# if ( printContent ) { #>
            <div class="elementor-cta-content">
                <# if ( imageUrl ) { #>
                    <div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
                        <img src="{{ imageUrl }}">
                    </div>
                <#  } else if ( 'icon' === settings.graphic_element && settings.icon ) { #>
                    <div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
                        <div class="elementor-icon">
                            <i class="{{ settings.icon }}"></i>
                        </div>
                    </div>
                <# } #>
                <# if ( settings.title ) { #>
                    <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</{{ settings.title_tag }}>
                <# } #>

                <# if ( settings.description_text ) { #>
                    <div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description_text }}}</div>
                <# } #>

                <# if ( settings.button ) { #>
                    <div class="elementor-cta-button-wrapper elementor-content-item {{ animationClass }}">
                        <{{ buttonTag }} href="#" {{{ view.getRenderAttributeString( 'button' ) }}}>
                            <# if ( settings.button_icon ) { #>
                            <span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
                                <i class="{{ settings.button_icon }}"></i>
                            </span>
                            <# } #>
                            <span class="elementor-button-text">{{{ settings.button }}}</span>
                        </{{ buttonTag }}>
                    </div>
                <# } #>
            </div>
        <# } #>
        <# if ( settings.ribbon_title ) {
            var ribbonClasses = 'elementor-ribbon';

            if ( settings.ribbon_horizontal_position ) {
                ribbonClasses += ' elementor-ribbon-' + settings.ribbon_horizontal_position;
            } #>
            <div class="{{ ribbonClasses }}">
                <div class="elementor-ribbon-inner">{{{ settings.ribbon_title }}}</div>
            </div>
        <# } #>
        </{{ wrapperTag }}>
        <?php
    }
}
