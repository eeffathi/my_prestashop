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

class WidgetFlipBox extends WidgetBase
{
    public function getName()
    {
        return 'flip-box';
    }

    public function getTitle()
    {
        return __('Flip Box', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-flip-box';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_a',
            array(
                'label' => __('Front', 'elementor'),
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
                'default' => 'icon',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'image',
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
            'title_text_a',
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
            'description_text_a',
            array(
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementor'),
                'placeholder' => __('Enter your description', 'elementor'),
                'separator' => '',
            )
        );

        $this->addControl(
            'title_size_a',
            array(
                'label' => __('Title HTML Tag', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'h1' => __('H1', 'elementor'),
                    'h2' => __('H2', 'elementor'),
                    'h3' => __('H3', 'elementor'),
                    'h4' => __('H4', 'elementor'),
                    'h5' => __('H5', 'elementor'),
                    'h6' => __('H6', 'elementor'),
                    'div' => __('div', 'elementor'),
                    'span' => __('span', 'elementor'),
                    'p' => __('p', 'elementor'),
                ),
                'default' => 'h3',
            )
        );

        $this->EndControlsSection();

        $this->StartControlsSection(
            'section_b',
            array(
                'label' => __('Back', 'elementor'),
            )
        );

        $this->addControl(
            'graphic_element_b',
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
            )
        );

        $this->addControl(
            'image_b',
            array(
                'label' => __('Choose Image', 'elementor'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => array(
                    'url' => Utils::getPlaceholderImageSrc(),
                ),
                'condition' => array(
                    'graphic_element_b' => 'image',
                ),
            )
        );

        $this->addControl(
            'icon_b',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'default' => 'fa fa-star',
                'condition' => array(
                    'graphic_element_b' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_view_b',
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
                    'graphic_element_b' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_shape_b',
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
                    'graphic_element_b' => 'icon',
                    'icon_view_b!' => 'default',
                ),
            )
        );

        $this->addControl(
            'title_text_b',
            array(
                'label' => __('Title & Description', 'elementor'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'default' => __('This is the heading', 'elementor'),
                'placeholder' => __('Enter your title', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_text_b',
            array(
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementor'),
                'placeholder' => __('Enter your description', 'elementor'),
                'separator' => '',
            )
        );

        $this->addControl(
            'title_size_b',
            array(
                'label' => __('Title HTML Tag', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'h1' => __('H1', 'elementor'),
                    'h2' => __('H2', 'elementor'),
                    'h3' => __('H3', 'elementor'),
                    'h4' => __('H4', 'elementor'),
                    'h5' => __('H5', 'elementor'),
                    'h6' => __('H6', 'elementor'),
                    'div' => __('div', 'elementor'),
                    'span' => __('span', 'elementor'),
                    'p' => __('p', 'elementor'),
                ),
                'default' => 'h3',
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

        $this->EndControlsSection();

        $this->StartControlsSection(
            'section_flip_box',
            array(
                'label' => __('Flip Box', 'elementor'),
            )
        );

        $this->addResponsiveControl(
            'height',
            array(
                'label' => __('Height', 'elementor'),
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
                    '{{WRAPPER}} .elementor-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', '%'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-side, {{WRAPPER}} .elementor-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addControl(
            'heading_hover_animation',
            array(
                'label' => __('Hover Animation', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'flip_effect',
            array(
                'label' => __('Flip Effect', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'flip',
                'options' => array(
                    'flip' => 'Flip',
                    'slide' => 'Slide',
                    'push' => 'Push',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'fade' => 'Fade',
                ),
                'prefix_class' => 'elementor-flip-box--effect-',
            )
        );

        $this->addControl(
            'flip_direction',
            array(
                'label' => __('Flip Direction', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'up',
                'options' => array(
                    'left' => __('Left', 'elementor'),
                    'right' => __('Right', 'elementor'),
                    'up' => __('Up', 'elementor'),
                    'down' => __('Down', 'elementor'),
                ),
                'separator' => '',
                'prefix_class' => 'elementor-flip-box--direction-',
                'condition' => array(
                    'flip_effect!' => array(
                        'fade',
                        'zoom-in',
                        'zoom-out',
                    ),
                ),
            )
        );

        $this->addControl(
            'flip_3d',
            array(
                'label' => __('3D Depth', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('On', 'elementor'),
                'label_off' => __('Off', 'elementor'),
                'return_value' => 'elementor-flip-box--3d',
                'prefix_class' => '',
                'condition' => array(
                    'flip_effect' => 'flip',
                ),
            )
        );

        $this->EndControlsSection();

        $this->StartControlsSection(
            'section_style_a',
            array(
                'label' => __('Front', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->startControlsTabs('tabs_style_a');

        $this->startControlsTab(
            'tab_box_a',
            array(
                'label' => __('Box', 'elementor'),
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'background_a',
                'types' => array('none', 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            )
        );

        $this->addControl(
            'background_overlay_a',
            array(
                'label' => __('Background Overlay', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'background_a_background' => 'classic',
                    'background_a_image[url]!' => '',
                ),
            )
        );

        $this->addControl(
            'alignment_a',
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
                    '{{WRAPPER}} .elementor-flip-box-front' => 'text-align: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'vertical_position_a',
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
                'separator' => '',
                'prefix_class' => 'elementor-flip-box-front--valign-',
            )
        );

        $this->addResponsiveControl(
            'padding_a',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_a',
                'label' => __('Border Style', 'elementor'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay',
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'shadow_a',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_a',
            array(
                'label' => __('Icon', 'elementor'),
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    implode(', ', array(
                        '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon',
                        '{{WRAPPER}} .elementor-flip-box-front .elementor-view-default .elementor-icon',
                    )) => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'icon_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_padding',
            array(
                'label' => __('Icon Padding', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon_view!' => 'default',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_a',
            array(
                'label' => __('Image', 'elementor'),
                'condition' => array(
                    'graphic_element' => 'image',
                ),
            )
        );

        $this->addControl(
            'image_spacing',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_width',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addControl(
            'image_opacity',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'image_border_radius',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_a',
            array(
                'label' => __('Content', 'elementor'),
            )
        );

        $this->addControl(
            'heading_style_title_a',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::HEADING,
            )
        );

        $this->addControl(
            'title_spacing_a',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color_a',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title',
            )
        );

        $this->addControl(
            'heading_style_description_a',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_color_a',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->EndControlsSection();

        $this->StartControlsSection(
            'section_style_b',
            array(
                'label' => __('Back', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->startControlsTabs('tabs_style_b');

        $this->startControlsTab(
            'tab_box_b',
            array(
                'label' => __('Box', 'elementor'),
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'background_b',
                'types' => array('none', 'classic', 'gradient'),
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            )
        );

        $this->addControl(
            'background_overlay_b',
            array(
                'label' => __('Background Overlay', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'background_b_background' => 'classic',
                    'background_b_image[url]!' => '',
                ),
            )
        );

        $this->addControl(
            'alignment_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back' => 'text-align: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'vertical_position_b',
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
                'separator' => '',
                'prefix_class' => 'elementor-flip-box-back--valign-',
            )
        );

        $this->addResponsiveControl(
            'padding_b',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_b',
                'label' => __('Border Style', 'elementor'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay',
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'shadow_b',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_b',
            array(
                'label' => __('Image', 'elementor'),
                'condition' => array(
                    'graphic_element_b' => 'image',
                ),
            )
        );

        $this->addControl(
            'image_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_width_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addControl(
            'image_opacity_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img',
            )
        );

        $this->addControl(
            'image_border_radius_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_b',
            array(
                'label' => __('Icon', 'elementor'),
                'condition' => array(
                    'graphic_element_b' => 'icon',
                ),
            )
        );

        $this->addControl(
            'icon_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_primary_color_b',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-flip-box-back .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'icon_secondary_color_b',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
                'separator' => '',
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'icon_size_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_padding_b',
            array(
                'label' => __('Icon Padding', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon_view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'icon_rotate_b',
            array(
                'label' => __('Icon Rotate', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'unit' => 'deg',
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->addControl(
            'icon_border_radius_b',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon_view_b!' => 'default',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_b',
            array(
                'label' => __('Content', 'elementor'),
            )
        );

        $this->addControl(
            'heading_style_title_b',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::HEADING,
            )
        );

        $this->addControl(
            'title_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color_b',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title',
            )
        );

        $this->addControl(
            'heading_description_style_b',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-button' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'button!' => '',
                ),
            )
        );

        $this->addControl(
            'description_color_b',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->EndControlsSection();

        $this->StartControlsSection(
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

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
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
            'tab_button_hover',
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
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
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

        $this->EndControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $flipbox_b_html_tag = 'div';
        $button_tag = 'div';
        $link_url = empty($settings['link']['url']) ? false : $settings['link']['url'];

        $this->addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');
        $this->addRenderAttribute('button', 'class', array( 'elementor-button', 'elementor-size-' . $settings['button_size']));

        if (!empty($link_url)) {
            if ($settings['link_click'] == 'box' || empty($settings['button'])) {
                $flipbox_b_html_tag = 'a';
                $button_tag = 'button';
                $this->addRenderAttribute('flipbox-back', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('flipbox-back', 'target', '_blank');
                }
            } else {
                $button_tag = 'a';
                $this->addRenderAttribute('button', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('button', 'target', '_blank');
                }
            }
        }
        if ('icon' === $settings['graphic_element']) {
            $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-icon-wrapper');
            $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-view-' . $settings['icon_view']);

            if ('default' != $settings['icon_view']) {
                $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-shape-' . $settings['icon_shape']);
            }
            if (!empty($settings['icon'])) {
                $this->addRenderAttribute('icon_front', 'class', $settings['icon']);
            }
        }
        if ('icon' === $settings['graphic_element_b']) {
            $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-icon-wrapper');
            $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-view-' . $settings['icon_view_b']);

            if ('default' != $settings['icon_view_b']) {
                $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-shape-' . $settings['icon_shape_b']);
            }
            if (!empty($settings['icon_b'])) {
                $this->addRenderAttribute('icon_b', 'class', $settings['icon_b']);
            }
        }
        ?>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                        <?php if ('icon' === $settings['graphic_element']) : ?>
                            <div <?php echo $this->getRenderAttributeString('icon-wrapper-front'); ?>>
                                <div class="elementor-icon">
                                    <i <?php echo $this->getRenderAttributeString('icon_front'); ?>></i>
                                </div>
                            </div>
                        <?php elseif ('image' === $settings['graphic_element']) : ?>
                            <div class="elementor-flip-box-image">
                                <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>
                            </div>
                        <?php endif;?>
                        <?php if (!empty($settings['title_text_a'])) : ?>
                            <<?php echo $settings['title_size_a']; ?> class="elementor-flip-box-title"><?php echo $settings['title_text_a']; ?></<?php echo $settings['title_size_a']; ?>>
                        <?php endif;?>
                        <?php if (!empty($settings['description_text_a'])) : ?>
                            <div class="elementor-flip-box-description"><?php echo $settings['description_text_a']; ?></div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <<?php echo $flipbox_b_html_tag . ' ' . $this->getRenderAttributeString('flipbox-back'); ?>>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                        <?php if ('none' != $settings['graphic_element_b']) : ?>
                            <?php if ('image' === $settings['graphic_element_b']) : ?>
                            <div class="elementor-flip-box-image">
                                <?php echo GroupControlImageSize::getAttachmentImageHtml($settings, 'image_b'); ?>
                            </div>
                            <?php elseif ('icon' == $settings['graphic_element_b']) : ?>
                                <div <?php echo $this->getRenderAttributeString('icon-wrapper-back'); ?>>
                                    <div class="elementor-icon">
                                        <i <?php echo $this->getRenderAttributeString('icon_b'); ?>></i>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if (!empty($settings['title_text_b'])) : ?>
                            <<?php echo $settings['title_size_b']; ?> class="elementor-flip-box-title"><?php echo $settings['title_text_b']; ?></<?php echo $settings['title_size_b']; ?>>
                        <?php endif;?>
                        <?php if (!empty($settings['description_text_b'])) : ?>
                            <div class="elementor-flip-box-description"><?php echo $settings['description_text_b']; ?></div>
                        <?php endif;?>
                        <?php if (!empty($settings['button'])) : ?>
                            <<?php echo $button_tag. ' '. $this->getRenderAttributeString('button'); ?>>
                                <?php if (!empty($settings['button_icon'])) : ?>
                                    <span class="elementor-button-icon elementor-align-icon-<?php echo esc_attr($settings['button_icon_align']); ?>">
                                        <i class="<?php echo esc_attr($settings['button_icon']); ?>"></i>
                                    </span>
                                <?php endif;?>
                                <span class="elementor-button-text"><?php echo $settings['button']; ?></span>
                            </<?php echo $button_tag; ?>>
                        <?php endif;?>
                    </div>
                </div>
            </<?php echo $flipbox_b_html_tag; ?>>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        if ( 'icon' === settings.graphic_element ) {
            var iconWrapperClasses = 'elementor-icon-wrapper';
            iconWrapperClasses += ' elementor-view-' + settings.icon_view;

            if ( 'default' !== settings.icon_view ) {
                iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
            }
        }
        if ( 'icon' === settings.graphic_element_b ) {
            var iconWrapperClassesBack = 'elementor-icon-wrapper';
            iconWrapperClassesBack += ' elementor-view-' + settings.icon_view_b;

            if ( 'default' !== settings.icon_view_b ) {
                iconWrapperClassesBack += ' elementor-shape-' + settings.icon_shape_b;
            }
        }

        var view = new CeView(),
            titleTagFront = settings.title_size_a,
            titleTagBack = settings.title_size_b,
            btnSizeClass = 'elementor-size-' + settings.button_size,
            wrapperTag = 'div',
            buttonTag = 'div';

        view.addRenderAttribute('button', 'class', ['elementor-button', btnSizeClass]);
        view.addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');

        if (settings.link) {
            if ( 'box' === settings.link_click || !settings.button ) {
                wrapperTag = 'a';
                buttonTag = 'button';
                view.addRenderAttribute( 'flipbox-back', 'href', settings.link.url );

                if (settings.link.is_external) {
                    view.addRenderAttribute('flipbox-back', 'target', '_blank');
                }
            } else {
                buttonTag = 'button';
                view.addRenderAttribute('button', 'href', settings.link.url);

                if (settings.link.is_external) {
                    view.addRenderAttribute('button', 'target', '_blank');
                }
            }
        }
        #>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                        <# if ( 'icon' === settings.graphic_element ) { #>
                            <div class="{{ iconWrapperClasses }}">
                                 <div class="elementor-icon">
                                    <i class="{{ settings.icon }}"></i>
                                </div>
                            </div>
                        <# } else if ( 'image' === settings.graphic_element && settings.image.url ) { #>
                            <div class="elementor-flip-box-image">
                                <img src="{{ elementor.imagesManager.getImageUrl( settings.image ) }}">
                            </div>
                        <# } #>
                        <# if ( settings.title_text_a ) { #>
                            <{{ titleTagFront }} class="elementor-flip-box-title">{{{ settings.title_text_a }}}</{{ titleTagFront }}>
                        <# } #>
                        <# if ( settings.description_text_a ) { #>
                            <div class="elementor-flip-box-description">{{{ settings.description_text_a }}}</div>
                        <# } #>
                    </div>
                </div>
            </div>
            <{{ wrapperTag }} {{{ view.getRenderAttributeString('flipbox-back') }}}>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                        <# if ( 'icon' === settings.graphic_element_b ) { #>
                            <div class="{{ iconWrapperClassesBack }}">
                                 <div class="elementor-icon">
                                    <i class="{{ settings.icon_b }}"></i>
                                </div>
                            </div>
                        <# } else if ( 'image' === settings.graphic_element_b && settings.image_b.url ) { #>
                            <div class="elementor-flip-box-image">
                                <img src="{{ elementor.imagesManager.getImageUrl( settings.image_b ) }}">
                            </div>
                        <# } #>
                        <# if ( settings.title_text_b ) { #>
                            <{{ titleTagBack }} class="elementor-flip-box-title">{{{ settings.title_text_b }}}</{{ titleTagBack }}>
                        <# } #>
                        <# if ( settings.description_text_b ) { #>
                            <div class="elementor-flip-box-description">{{{ settings.description_text_b }}}</div>
                        <# } #>
                        <# if (settings.button) { #>
                            <{{ buttonTag }} {{{ view.getRenderAttributeString('button') }}}>
                                <# if (settings.button_icon) {#>
                                <span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
                                        <i class="{{ settings.button_icon }}"></i>
                                </span>
                                <# } #>
                                <span class="elementor-button-text">{{{ settings.button }}}</span>
                            </{{ buttonTag }}>
                        <# } #>
                    </div>
                </div>
            </{{ wrapperTag }}>
        </div>
        <?php
    }
}
