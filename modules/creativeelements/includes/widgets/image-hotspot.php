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

class WidgetImageHotspot extends WidgetBase
{
    public function getName()
    {
        return 'image-hotspot';
    }

    public function getTitle()
    {
        return __('Image Hotspot', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-image-hotspot';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            array(
                'label' => __('Image', 'elementor'),
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
            )
        );

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
                ),
                'default' => 'center',
                'selectors' => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_hotspots',
            array(
                'label' => __('Hotspots', 'elementor'),
            )
        );

        $this->addControl(
            'hotspots',
            array(
                'label' => '',
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        '_id' => Utils::generateRandomString(),
                        'title' => __('Hotspot #1', 'elementor'),
                        'description' => '<p>' . __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementor') . '</p>',
                        'x' => array(
                            'size' => 25,
                            'unit' => '%',
                        ),
                        'y' => array(
                            'size' => 50,
                            'unit' => '%',
                        ),
                        'link' => array(
                            'url' => '',
                        ),
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'title',
                        'label' => __('Title & Description', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'default' => __('Hotspot Title', 'elementor'),
                        'label_block' => true,
                    ),
                    array(
                        'name' => 'description',
                        'type' => ControlsManager::WYSIWYG,
                        'default' => '<p>' . __('Hotspot Description', 'elementor') . '</p>',
                        'show_label' => false,
                    ),
                    array(
                        'name' => 'x',
                        'label' => __('X Position', 'elementor'),
                        'type' => ControlsManager::SLIDER,
                        'default' => array(
                            'size' => 50,
                            'unit' => '%',
                        ),
                        'range' => array(
                            '%' => array(
                                'min' => 0,
                                'max' => 100,
                            ),
                        ),
                        'selectors' => array(
                            '{{WRAPPER}} .elementor-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                        ),
                    ),
                    array(
                        'name' => 'y',
                        'label' => __('Y Position', 'elementor'),
                        'type' => ControlsManager::SLIDER,
                        'default' => array(
                            'size' => 50,
                            'unit' => '%',
                        ),
                        'range' => array(
                            '%' => array(
                                'min' => 0,
                                'max' => 100,
                            ),
                        ),
                        'selectors' => array(
                            '{{WRAPPER}} .elementor-image-hotspot-wrapper{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                        ),
                    ),
                    array(
                        'name' => 'link',
                        'label' => __('Link', 'elementor'),
                        'type' => ControlsManager::URL,
                        'default' => array('url' => ''),
                        'placeholder' => 'http://your-link.com',
                        'separator' => 'before',
                    ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'default' => __('Default', 'elementor'),
                    'stacked' => __('Stacked', 'elementor'),
                    'framed' => __('Framed', 'elementor'),
                ),
                'default' => 'framed',
                'prefix_class' => 'elementor-view-',
            )
        );

        $this->addControl(
            'icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::ICON,
                'default' => '',
            )
        );

        $this->addControl(
            'title_size',
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
                'default' => 'h4',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => __('Size (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_opacity',
            array(
                'label' => __('Opacity (%)', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot > img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'image_border',
                'label' => __('Image Border', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-image-hotspot > img',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-image-hotspot > img',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'icon_size',
            array(
                'label' => __('Icon Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 22,
                    'unit' => 'px',
                ),
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
                'default' => array(
                    'size' => 0.4,
                    'unit' => 'em',
                ),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'icon_rotate',
            array(
                'label' => __('Icon Rotate', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->addControl(
            'icon_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view' => 'framed',
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
                    'view!' => 'default',
                ),
            )
        );

        $this->startControlsTabs('icon_tabs');

        $this->startControlsTab(
            'icon_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );

        $this->addControl(
            'icon_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'icon_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );

        $this->addControl(
            'hover_primary_color',
            array(
                'label' => __('Primary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_secondary_color',
            array(
                'label' => __('Secondary Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'icon_animation',
            array(
                'label' => __('Animation', 'elementor'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_box',
            array(
                'label' => __('Box', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'box_width',
            array(
                'label' => __('Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'width: {{SIZE}}px',
                ),
            )
        );

        $this->addResponsiveControl(
            'box_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            array(
                'name' => 'box_background',
                'types' => array('classic'),
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-content',
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-content',
            )
        );

        $this->addControl(
            'box_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ),
            )
        );

        $this->addControl(
            'box_shadow_type',
            array(
                'label' => _x('Box Shadow', 'Box Shadow Control', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'outset' => __('Custom', 'elementor'),
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'box_shadow',
            array(
                'type' => ControlsManager::BOX_SHADOW,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ),
                'condition' => array(
                    'box_shadow_type!' => '',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'text_align',
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
                    'justify' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-hotspot-content' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'heading_title',
            array(
                'label' => __('Title', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'title_bottom_space',
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
                    '{{WRAPPER}} .elementor-image-hotspot-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
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
                    '{{WRAPPER}} .elementor-image-hotspot-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-image-hotspot-title',
            )
        );

        $this->addControl(
            'heading_description',
            array(
                'label' => __('Description', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
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
                    '{{WRAPPER}} .elementor-image-hotspot .elementor-image-hotspot-description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-image-hotspot .elementor-image-hotspot-description',
            )
        );

        $this->endControlsSection();
    }

    protected function render($instance = array())
    {
        $settings = $this->getSettings();

        if (empty($settings['image']['url'])) {
            return;
        }

        empty($settings['icon']) or $this->addRenderAttribute('icon', 'class', $settings['icon']);
        ?>
        <div class="elementor-image-hotspot">
            <?php echo GroupControlImageSize::getAttachmentImageHtml($settings); ?>
            <?php foreach ($settings['hotspots'] as $i => $item) : ?>
                <div class="elementor-image-hotspot-wrapper elementor-repeater-item-<?php echo $item['_id']; ?>">
                    <?php
                    $icon_tag = 'div';
                    $this->addRenderAttribute("icon-wrapper-$i", 'class', 'elementor-icon');
                    empty($settings['icon_animation']) or $this->addRenderAttribute("icon-wrapper-$i", 'class', 'elementor-animation-' . $settings['icon_animation']);

                    if (!empty($item['link']['url'])) {
                        $icon_tag = 'a';
                        $this->addRenderAttribute("icon-wrapper-$i", 'href', $item['link']['url']);

                        if (!empty($item['link']['is_external'])) {
                            $this->addRenderAttribute("icon-wrapper-$i", 'target', '_blank');
                        }
                    }
                    ?>
                    <<?php echo $icon_tag . ' ' . $this->getRenderAttributeString("icon-wrapper-$i"); ?>>
                        <i <?php echo $this->getRenderAttributeString('icon'); ?>></i>
                    </<?php echo $icon_tag; ?>>
                    <div class="elementor-image-hotspot-content">
                    <?php if (!empty($item['title'])) : ?>
                        <<?php echo $settings['title_size']; ?> class="elementor-image-hotspot-title"><?php echo $item['title']; ?></<?php echo $settings['title_size']; ?>>
                    <?php endif; ?>
                    <?php if (!empty($item['description'])) : ?>
                        <div class="elementor-image-hotspot-description"><?php echo $item['description']; ?></div>
                    <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        if (!settings.image.url) {
            return;
        }
        var animation = settings.icon_animation ? 'elementor-animation-' + settings.icon_animation : '';
        #>
        <div class="elementor-image-hotspot">
            <img class="elementor-image" src="{{ elementor.imagesManager.getImageUrl( settings.image ) }}" />
            <#  _.each( settings.hotspots, function( item ) { #>
                <div class="elementor-image-hotspot-wrapper elementor-repeater-item-{{{item._id}}}">
                    <# var link = item.link.url ? 'href="' + item.link.url + '"' : '', iconTag = link ? 'a' : 'div'; #>
                    <{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.icon_animation }}" {{{ link }}}>
                        <i class="{{ settings.icon }}"></i>
                    </{{{ iconTag }}}>
                    <div class="elementor-image-hotspot-content">
                    <# if (item.title) { #>
                        <{{{settings.title_size}}} class="elementor-image-hotspot-title">{{{item.title}}}</{{{settings.title_size}}}>
                    <# } #>
                    <# if (item.description) { #>
                        <div class="elementor-image-hotspot-description">{{{item.description}}}</div>
                    <# } #>
                    </div>
                </div>
           <# }) #>
        </div>
        <?php
    }
}
