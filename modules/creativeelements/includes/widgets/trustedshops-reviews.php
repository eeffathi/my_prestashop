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

class WidgetTrustedShopsReviews extends WidgetBase
{
    public function getName()
    {
        return 'trustedshops-reviews';
    }

    public function getTitle()
    {
        return __('TrustedShops Reviews', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-carousel';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            array(
                'label' => __('TrustedShops Reviews', 'elementor'),
            )
        );

        $this->addControl(
            'ts_id',
            array(
                'label' => __('TrustedShops Id', 'elementor'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'description' => __('You received your personal TS ID when you registered with Trusted Shops.', 'elementor'),
            )
        );

        $show_from = __('Show from %d Stars', 'elementor');

        $this->addControl(
            'min_rating',
            array(
                'label' => __('Filter Reviews', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => __('Show All', 'elementor'),
                    '2' => sprintf($show_from, 2),
                    '3' => sprintf($show_from, 3),
                    '4' => sprintf($show_from, 4),
                    '5' => sprintf($show_from, 5),
                ),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
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
            'section_style_reviews',
            array(
                'label' => __('Reviews', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'reviews_min_height',
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
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'min-height: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addControl(
            'reviews_vertical_alignment',
            array(
                'label' => __('Vertical Alignment', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'top' => __('Top', 'elementor'),
                    'middle' => __('Middle', 'elementor'),
                    'bottom' => __('Bottom', 'elementor'),
                ),
                'default' => 'middle',
                'selectors' => array(
                    '{{WRAPPER}} .slick-slide' => 'vertical-align: {{VALUE}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'reviews_spacing',
            array(
                'label' => __('Space Between', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 10,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'margin: 0 calc({{SIZE}}{{UNIT}} / 2);',
                ),
                'condition' => array(
                    'slides_to_show!' => '1',
                ),
            )
        );

        $this->addControl(
            'reviews_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'reviews_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'reviews_border',
                'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide-inner',
            )
        );

        $this->addControl(
            'reviews_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'heading_style_header',
            array(
                'label' => __('Header', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'header_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'header_gap',
            array(
                'label' => __('Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2);',
                ),
            )
        );

        $this->addControl(
            'header_separator',
            array(
                'label' => __('Separator', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'solid',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-style: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'header_separator_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-color: {{VALUE}};',
                ),
                'condition' => array(
                    'header_separator!' => '',
                ),
            )
        );

        $this->addControl(
            'header_separator_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-header' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'header_separator!' => '',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_text',
            array(
                'label' => __('Text', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'heading_style_date',
            array(
                'label' => __('Date', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'date_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-date' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'date_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-trustedshops-reviews-date',
            )
        );

        $this->addControl(
            'heading_style_comment',
            array(
                'label' => __('Comment', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'comment_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-comment' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'comment_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-trustedshops-reviews-comment',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_rating',
            array(
                'label' => __('Rating', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'rating_icon',
            array(
                'label' => __('Icon', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => array(
                    '' => __('Font Awesome', 'elementor'),
                    'unicode' => __('Unicode', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'rating_unmarked_style',
            array(
                'label' => __('Unmarked Style', 'elementor'),
                'label_block' => false,
                'type' => ControlsManager::CHOOSE,
                'default' => 'star',
                'options' => array(
                    'star' => array(
                        'title' => __('Solid', 'elementor'),
                        'icon' => 'fa fa-star',
                    ),
                    'star-o' => array(
                        'title' => __('Outline', 'elementor'),
                        'icon' => 'fa fa-star-o',
                    ),
                ),
            )
        );

        $this->addControl(
            'rating_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'rating_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'rating_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#f0ad4e',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'rating_unmarked_color',
            array(
                'label' => __('Unmarked Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#ccd6df',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-trustedshops-reviews-stars .elementor-unmarked-star' => 'color: {{VALUE}};',
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
                'label' => __('Background Color', 'elementor'),
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

    protected function getReviews($tsId)
    {
        if (\Tools::strlen($tsId) != 33) {
            return false;
        }

        $reviews = get_transient('ts_' . $tsId);
        if (false === $reviews) {
            $result = \Tools::file_get_contents("http://api.trustedshops.com/rest/public/v2/shops/$tsId/reviews.json");
            if (empty($result)) {
                return false;
            }

            $result = json_decode($result, true);
            if (empty($result['response']['data']['shop']['reviews'])) {
                return false;
            }

            $reviews = &$result['response']['data']['shop']['reviews'];
            set_transient('ts_' . $tsId, $reviews, 24 * 3600);
        }

        return $reviews;
    }

    protected function render()
    {
        $settings = $this->getSettings();
        $reviews = $this->getReviews($settings['ts_id']);

        if (empty($reviews)) {
            return;
        }

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

        $date_format = \Context::getContext()->language->date_format_lite;
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

        if (!empty($settings['rating_icon'])) {
            $carousel_classes[] = 'elementor-icon-unicode';
        }

        $star = '<i class="fa fa-star"></i>';
        $unstar = '<i class="fa fa-' . $settings['rating_unmarked_style'] . ' elementor-unmarked-star"></i>';
        ?>
        <div class="elementor-image-carousel-wrapper elementor-slick-slider elementor-trustedshops-reviews" dir="<?php echo $direction; ?>">
            <div class="<?php echo implode(' ', $carousel_classes); ?>" data-slider_options='<?php echo json_encode($slick_options); ?>'>
            <?php foreach ($reviews as &$review) : ?>
                <?php if (($rating = round($review['mark'])) >= (int) $settings['min_rating']) : ?>
                <div><div class="slick-slide-inner">
                    <div class="elementor-trustedshops-review">
                        <div class="elementor-trustedshops-reviews-header">
                            <div class="elementor-trustedshops-reviews-date"><?php echo date($date_format, strtotime($review['changeDate'])); ?></div>
                            <div class="elementor-trustedshops-reviews-stars"><?php echo str_repeat($star, $rating) . str_repeat($unstar, 5 - $rating); ?></div>
                        </div>
                        <div class="elementor-trustedshops-reviews-comment"><?php echo $review['comment']; ?></div>
                    </div>
                </div></div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
