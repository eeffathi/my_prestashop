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

class WidgetCountdown extends WidgetBase
{
    public function getName()
    {
        return 'countdown';
    }

    public function getTitle()
    {
        return __('Countdown', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-countdown';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_countdown',
            array(
                'label' => __('Countdown', 'elementor'),
            )
        );

        $this->addControl(
            'due_date',
            array(
                'label' => __('Due Date', 'elementor'),
                'type' => ControlsManager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
                'description' => sprintf(__('Date set according to your timezone: %s.', 'elementor'), Utils::getTimezoneString()),
            )
        );

        $this->addControl(
            'label_display',
            array(
                'label' => __('View', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'block' => __('Block', 'elementor'),
                    'inline' => __('Inline', 'elementor'),
                ),
                'default' => 'block',
                'prefix_class' => 'elementor-countdown--label-',
            )
        );

        $this->addResponsiveControl(
            'inline_align',
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
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-wrapper' => 'text-align: {{VALUE}};',
                ),
                'condition' => array(
                    'label_display' => 'inline',
                ),
            )
        );

        $this->addControl(
            'show_days',
            array(
                'label' => __('Days', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_hours',
            array(
                'label' => __('Hours', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_minutes',
            array(
                'label' => __('Minutes', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_seconds',
            array(
                'label' => __('Seconds', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->addControl(
            'show_labels',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->addControl(
            'custom_labels',
            array(
                'label' => __('Custom Label', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'return_value' => 'yes',
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addControl(
            'label_days',
            array(
                'label' => __('Days', 'elementor'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Days', 'elementor'),
                'placeholder' => __('Days', 'elementor'),
                'condition' => array(
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_days' => 'yes',
                ),
            )
        );

        $this->addControl(
            'label_hours',
            array(
                'label' => __('Hours', 'elementor'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Hours', 'elementor'),
                'placeholder' => __('Hours', 'elementor'),
                'condition' => array(
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_hours' => 'yes',
                ),
            )
        );

        $this->addControl(
            'label_minutes',
            array(
                'label' => __('Minutes', 'elementor'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Minutes', 'elementor'),
                'placeholder' => __('Minutes', 'elementor'),
                'condition' => array(
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_minutes' => 'yes',
                ),
            )
        );

        $this->addControl(
            'label_seconds',
            array(
                'label' => __('Seconds', 'elementor'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Seconds', 'elementor'),
                'placeholder' => __('Seconds', 'elementor'),
                'condition' => array(
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_seconds' => 'yes',
                ),
            )
        );

        $this->addControl(
            'expire_actions',
            array(
                'label' => __('Actions After Expire', 'elementor'),
                'type' => ControlsManager::SELECT2,
                'options' => array(
                    'redirect' => __('Redirect', 'elementor'),
                    'hide' => __('Hide', 'elementor'),
                    'message' => __('Show Message', 'elementor'),
                ),
                'label_block' => true,
                'separator' => 'before',
                'render_type' => 'none',
                'multiple' => true,
            )
        );

        $this->addControl(
            'message_after_expire',
            array(
                'label' => __('Message', 'elementor'),
                'type' => ControlsManager::TEXTAREA,
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'message',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'expire_redirect_url',
            array(
                'label' => __('Redirect URL', 'elementor'),
                'type' => ControlsManager::URL,
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'redirect',
                        ),
                    ),
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_box_style',
            array(
                'label' => __('Boxes', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'container_width',
            array(
                'label' => __('Container Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'tablet_default' => array(
                    'unit' => '%',
                ),
                'mobile_default' => array(
                    'unit' => '%',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 2000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units' => array('%', 'px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'box_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-item' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'box_border',
                'label' => __('Border', 'elementor'),
                'selector' => '{{WRAPPER}} .elementor-countdown-item',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'box_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'box_spacing',
            array(
                'label' => __('Space Between', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 10,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body:not(.rtl) {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body.rtl {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body.rtl {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                ),
            )
        );

        $this->addResponsiveControl(
            'box_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_content_style',
            array(
                'label' => __('Content', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'heading_digits',
            array(
                'label' => __('Digits', 'elementor'),
                'type' => ControlsManager::HEADING,
            )
        );

        $this->addControl(
            'digits_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-digits' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'digits_typography',
                'selector' => '{{WRAPPER}} .elementor-countdown-digits',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->addControl(
            'heading_label',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'label_color',
            array(
                'label' => __('Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .elementor-countdown-label',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_expire_message_style',
            array(
                'label' => __('Message', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => array(
                    'terms' => array(
                        array(
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'message',
                        ),
                    ),
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
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-countdown-expire--message',
            )
        );

        $this->addResponsiveControl(
            'message_padding',
            array(
                'label' => __('Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();
    }

    private function getStrftime($instance)
    {
        $string = '';
        if ($instance['show_days']) {
            $string .= $this->renderCountdownItem($instance, 'label_days', 'elementor-countdown-days');
        }
        if ($instance['show_hours']) {
            $string .= $this->renderCountdownItem($instance, 'label_hours', 'elementor-countdown-hours');
        }
        if ($instance['show_minutes']) {
            $string .= $this->renderCountdownItem($instance, 'label_minutes', 'elementor-countdown-minutes');
        }
        if ($instance['show_seconds']) {
            $string .= $this->renderCountdownItem($instance, 'label_seconds', 'elementor-countdown-seconds');
        }

        return $string;
    }

    private $_default_countdown_labels;

    private function _initDefaultCountdownLabels()
    {
        $this->_default_countdown_labels = array(
            'label_months' => __('Months', 'elementor'),
            'label_weeks' => __('Weeks', 'elementor'),
            'label_days' => __('Days', 'elementor'),
            'label_hours' => __('Hours', 'elementor'),
            'label_minutes' => __('Minutes', 'elementor'),
            'label_seconds' => __('Seconds', 'elementor'),
        );
    }

    public function getDefaultCountdownLabels()
    {
        if (!$this->_default_countdown_labels) {
            $this->_initDefaultCountdownLabels();
        }

        return $this->_default_countdown_labels;
    }

    private function renderCountdownItem($instance, $label, $part_class)
    {
        $string = '<div class="elementor-countdown-item"><span class="elementor-countdown-digits ' . $part_class . '"></span>';

        if ($instance['show_labels']) {
            $default_labels = $this->getDefaultCountdownLabels();
            $label = ($instance['custom_labels']) ? $instance[$label] : $default_labels[$label];
            $string .= ' <span class="elementor-countdown-label">' . $label . '</span>';
        }

        $string .= '</div>';

        return $string;
    }

    private function getActions($settings)
    {
        if (empty($settings['expire_actions'])) {
            return false;
        }
        $actions = array();

        foreach ($settings['expire_actions'] as $action) {
            $action_to_run = array('type' => $action);
            if ('redirect' === $action) {
                if (empty($settings['expire_redirect_url']['url'])) {
                    continue;
                }
                $action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
                $action_to_run['redirect_is_external'] = $settings['expire_redirect_url']['is_external'];
            }
            $actions[] = $action_to_run;
        }

        return $actions;
    }

    protected function render()
    {
        $instance = $this->getSettings();
        $due_date = $instance['due_date'];
        $string = $this->getStrftime($instance);
        $actions = $this->getActions($instance);

        $due_date = strtotime($due_date);
        ?>
        <div class="elementor-countdown-wrapper" data-date="<?php echo $due_date; ?>" data-expire-actions='<?php echo json_encode($actions); ?>'>
            <?php echo $string; ?>
        </div>
        <?php
        if ($actions && is_array($actions)) {
            foreach ($actions as $action) {
                if ('message' !== $action['type']) {
                    continue;
                }
                echo '<div class="elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>';
            }
        }
    }
}
