<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or exit;

class WidgetTestimonialCarousel extends WidgetBase
{
    public function getName()
    {
        return 'testimonial-carousel';
    }

    public function getTitle()
    {
        return __('Testimonial Carousel', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-post-slider';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_testimonials',
            array(
                'label' => __('Testimonials', 'elementor'),
            )
        );

        $sample = array(
            'content' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor'),
            'image' => array(
                'url' => Utils::getPlaceholderImageSrc(),
            ),
            'name' => 'John Doe',
            'title' => 'Designer',
        );

        $this->addControl(
            'slides',
            array(
                'type' => ControlsManager::REPEATER,
                'default' => array($sample, $sample, $sample),
                'fields' => array(
                    array(
                        'name' => 'content',
                        'label' => __('Content', 'elementor'),
                        'type' => ControlsManager::TEXTAREA,
                        'label_block' => true,
                        'rows' => '8',
                        'default' => __('List Item', 'elementor'),
                    ),
                    array(
                        'name' => 'image',
                        'label' => __('Add Image', 'elementor'),
                        'type' => ControlsManager::MEDIA,
                        'label_block' => true,
                        'seo' => 'true',
                        'default' => array(
                            'url' => Utils::getPlaceholderImageSrc(),
                        ),
                    ),
                    array(
                        'name' => 'name',
                        'label' => __('Name', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'John Doe',
                    ),
                    array(
                        'name' => 'title',
                        'label' => __('Job', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'Designer',
                    ),
                    array(
                        'name' => 'link',
                        'label' => __('Link', 'elementor'),
                        'type' => ControlsManager::URL,
                        'placeholder' => __('https://your-link.com', 'elementor'),
                    ),
                ),
                'title_field' => '{{{ name }}}',
            )
        );

        $this->addControl(
            'layout',
            array(
                'label' => __('Layout', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'image_inline',
                'options' => array(
                    'image_inline' => __('Image Inline', 'elementor'),
                    'image_stacked' => __('Image Stacked', 'elementor'),
                    'image_above' => __('Image Above', 'elementor'),
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'alignment',
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
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();

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
                'default' => '1',
                'options' => $slides_to_show,
            )
        );

        $this->addControl(
            'slides_to_scroll',
            array(
                'label' => __('Slides to Scroll', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
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
            'section_style_testimonials',
            array(
                'label' => __('Testimonials', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'space_between',
            array(
                'label' => __('Space Between', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .slick-list' => 'margin: 0 calc({{SIZE}}{{UNIT}} / -2);',
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'margin: 0 calc({{SIZE}}{{UNIT}} / 2);',
                ),
            )
        );

        $this->addResponsiveControl(
            'slide_min_height',
            array(
                'label' => __('Min. Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', 'vh'),
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
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'min-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'slide_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'slide_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}}  .slick-slider .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'slide_border_size',
            array(
                'label' => _x('Border Width', 'Border Control', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}}  .slick-slider .slick-slide-inner' => 'border-style: solid; border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ),
            )
        );

        $this->addControl(
            'slide_border_color',
            array(
                'label' => _x('Border Color', 'Border Control', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'slide_border_size[top]!' => '',
                ),
            )
        );

        $this->addControl(
            'slide_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px', '%'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
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

        $this->startControlsTabs('tabs_style_content');

        $this->startControlsTab(
            'tab_style_content',
            array(
                'label' => __('Content', 'elementor'),
            )
        );

        $this->addResponsiveControl(
            'content_gap',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'content_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'content_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-testimonial-content',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_name',
            array(
                'label' => __('Name', 'elementor'),
            )
        );

        $this->addControl(
            'name_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'name_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-testimonial-name',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_title',
            array(
                'label' => __('Job', 'elementor'),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-testimonial-job',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            array(
                'label' => __('Image', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'image_gap',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'layout!' => 'image_inline',
                ),
            )
        );

        $this->addResponsiveControl(
            'image_size',
            array(
                'label' => __('Image Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_border',
            array(
                'label' => __('Border', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-style: solid;',
                ),
            )
        );

        $this->addControl(
            'image_border_color',
            array(
                'label' => _x('Border Color', 'Border Control', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'image_border!' => '',
                ),
            )
        );

        $this->addResponsiveControl(
            'image_border_size',
            array(
                'label' => _x('Border Width', 'Border Control', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 20,
                    ),
                ),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'image_border!' => '',
                ),
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

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
                'default' => 'outside',
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
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );
        $this->addControl(
            'arrows_bg_color',
            array(
                'label' => __('Arrows background', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-carousel-wrapper  .slick-slider .slick-prev, {{WRAPPER}} .elementor-image-carousel-wrapper  .slick-slider .slick-next' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'color: {{VALUE}};',
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
        $settings = $this->getSettings();
        $is_slideshow = '1' === $settings['slides_to_show'];
        $is_rtl = ('rtl' === $settings['direction']);
        $direction = $is_rtl ? 'rtl' : 'ltr';
        $show_dots = (in_array($settings['navigation'], array('dots', 'both')));
        $show_arrows = (in_array($settings['navigation'], array('arrows', 'both')));

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

        $carousel_classes = array('elementor-image-carousel');

        if ($show_arrows) {
            $carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
        }

        if ($show_dots) {
            $carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
        }

        if (!$is_slideshow) {
            $slick_options['slidesToScroll'] = absint($settings['slides_to_scroll']);
        } else {
            $slick_options['fade'] = ('fade' === $settings['effect']);
        }

        $layout_class = 'elementor-testimonial-image-position-' . ('image_inline' == $settings['layout'] ? 'aside' : 'top');
        ?>
        <div class="elementor-image-carousel-wrapper elementor-slick-slider elementor-testimonial-carousel" dir="<?php echo $direction; ?>">
            <div class="<?php echo implode(' ', $carousel_classes); ?>" data-slider_options='<?php echo json_encode($slick_options); ?>'>
            <?php foreach ($settings['slides'] as $slide) : ?>
                <div class="slick-slide-inner">
                    <div class="elementor-testimonial-wrapper">
                    <?php if ('image_above' == $settings['layout'] && !empty($slide['image']['url'])) : ?>
                        <div class="elementor-testimonial-meta <?php echo $layout_class; ?>">
                            <div class="elementor-testimonial-meta-inner">
                                <div class="elementor-testimonial-image">
                                    <?php if (!empty($slide['link']['url'])) : ?>
                                        <a href="<?php echo esc_attr($slide['link']['url']) ?>"<?php echo $slide['link']['is_external'] ? ' target="_blank"' : ''; ?>>
                                            <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto'); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if (!empty($slide['content'])) : ?>
                        <div class="elementor-testimonial-content"><?php echo $slide['content'] ?></div>
                    <?php endif;?>
                        <div class="elementor-testimonial-meta <?php echo $layout_class; ?>">
                            <div class="elementor-testimonial-meta-inner">
                            <?php if ('image_above' != $settings['layout'] && !empty($slide['image']['url'])) : ?>
                                <div class="elementor-testimonial-image">
                                <?php if (!empty($slide['link']['url'])) : ?>
                                    <a href="<?php echo esc_attr($slide['link']['url']) ?>"<?php echo $slide['link']['is_external'] ? ' target="_blank"' : ''; ?>>
                                        <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto'); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto'); ?>
                                <?php endif;?>
                                </div>
                            <?php endif; ?>
                                <div class="elementor-testimonial-details">
                                <?php if (!empty($slide['name'])) : ?>
                                    <div class="elementor-testimonial-name">
                                    <?php if (!empty($slide['link']['url'])) : ?>
                                        <a href="<?php echo esc_attr($slide['link']['url']) ?>"<?php echo $slide['link']['is_external'] ? ' target="_blank"' : ''; ?>><?php echo $slide['name'] ?></a>
                                    <?php else : ?>
                                        <?php echo $slide['name'] ?>
                                    <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($slide['title'])) : ?>
                                    <div class="elementor-testimonial-job">
                                    <?php if (!empty($slide['link']['url'])) : ?>
                                        <a href="<?php echo esc_attr($slide['link']['url']) ?>"<?php echo $slide['link']['is_external'] ? ' target="_blank"' : ''; ?>><?php echo $slide['title'] ?></a>
                                    <?php else : ?>
                                        <?php echo $slide['title'] ?>
                                    <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            </div>
        </div>
        <?php
    }
}
