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

class WidgetEmailSubscription extends WidgetBase
{
    protected $context;

    protected $translator;

    protected $locale;

    protected $gdpr;

    protected $gdpr_msg;

    protected $gdpr_cfg;

    public function getName()
    {
        return 'email-subscription';
    }

    public function getTitle()
    {
        return __('Email Subscription', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-email-field';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    public function getModuleLink($module)
    {
        return empty($this->context->employee) ? '#' : $this->context->link->getAdminLink('AdminModules') . '&configure=' . $module;
    }

    protected function trans($id, array $params = array(), $domain = null, $locale = null)
    {
        if (_CE_PS16_) {
            $key = $domain . '_' . md5($id);

            return isset($GLOBALS['_LANG'][$key]) ? $GLOBALS['_LANG'][$key] : $id;
        }
        try {
            return $this->translator->trans($id, $params, $domain, $locale ? $locale : $this->locale);
        } catch (\Exception $ex) {
            return $id;
        }
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_email_subscription',
            array(
                'label' => __('Form Fields', 'elementor'),
            )
        );

        $this->addResponsiveControl(
            'layout',
            array(
                'label' => __('Layout', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'inline' => __('Inline', 'elementor'),
                    'multiline' => __('Multiline', 'elementor'),
                ),
                'default' => 'inline',
                'prefix_class' => 'elementor%s-layout-',
            )
        );

        $this->addControl(
            'input_height',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 50,
                ),
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'height: {{SIZE}}{{UNIT}}; padding: 0 calc({{SIZE}}{{UNIT}} / 3);',
                    '{{WRAPPER}} button[type=submit]' => 'height: {{SIZE}}{{UNIT}}; padding: 0 calc({{SIZE}}{{UNIT}} / 3);',
                ),
            )
        );

        $this->addControl(
            'heading_email',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Email', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'input_align',
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
                    '{{WRAPPER}} input[type=email]' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'placeholder',
            array(
                'label' => __('Placeholder', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->email_placeholder,
            )
        );

        $this->addControl(
            'heading_button',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Button', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'button_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]' => 'margin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;',
                ),
            )
        );

        $this->addResponsiveControl(
            'button_align',
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
                'prefix_class' => 'elementor%s-align-',
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'layout',
                            'value' => 'multiline',
                        ),
                        array(
                            'name' => 'layout_tablet',
                            'value' => 'multiline',
                        ),
                        array(
                            'name' => 'layout_mobile',
                            'value' => 'multiline',
                        ),
                    ),
                ),
            )
        );

        $this->addControl(
            'button',
            array(
                'label' => __('Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->button_placeholder,
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
            'icon_align',
            array(
                'label' => __('Icon Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => __('Before', 'elementor'),
                    'right' => __('After', 'elementor'),
                ),
                'separator' => '',
                'condition' => array(
                    'icon!' => '',
                ),
            )
        );

        $this->addControl(
            'icon_indent',
            array(
                'label' => __('Icon Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button-icon:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button-icon:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
                'separator' => '',
                'condition' => array(
                    'icon!' => '',
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
                'label' => __('Additional Options', 'elementor'),
                'type' => ControlsManager::SECTION,
            )
        );

        $this->addControl(
            'configure_module',
            array(
                'raw' => __('Email Subscription', 'elementor') . '<br><br>' .
                    '<a class="elementor-button elementor-button-default" href="' . esc_attr($this->getModuleLink(_CE_PS16_ ? 'blocknewsletter' : 'ps_emailsubscription')) . '" target="_blank">' .
                        '<i class="fa fa-external-link"></i> ' . __('Configure Module', 'elementor') .
                    '</a>',
                'type' => ControlsManager::RAW_HTML,
                'classes' => 'elementor-control-descriptor',
            )
        );

        empty($this->gdpr) or $this->addControl(
            'configure_gdpr',
            array(
                'raw' => __('GDPR', 'elementor') . '<br><br>' .
                    '<a class="elementor-button elementor-button-default" href="' . esc_attr($this->gdpr_cfg) . '" target="_blank">' .
                        '<i class="fa fa-external-link"></i> ' . __('Configure Module', 'elementor') .
                    '</a>',
                'type' => ControlsManager::RAW_HTML,
                'classes' => 'elementor-control-descriptor',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_form_style',
            array(
                'label' => __('Form', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
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
                    '{{WRAPPER}} .elementor-widget-container, {{WRAPPER}} .elementor-field-label' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'max_width',
            array(
                'label' => __('Max Width', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1600,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} form' => 'max-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_input_style',
            array(
                'label' => __('Email', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'input_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} input[type=email]',
            )
        );

        $this->startControlsTabs('tabs_input_colors');

        $this->startControlsTab(
            'tab_input_normal',
            array(
                'label' => __('Normal'),
            )
        );

        $this->addControl(
            'input_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]::placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]::-ms-input-placeholder ' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'input_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'input_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_input_focus',
            array(
                'label' => __('Focus', 'elementor'),
            )
        );

        $this->addControl(
            'input_focus_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]::placeholder:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]:-ms-input-placeholder:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} input[type=email]::-ms-input-placeholder:focus ' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'input_background_focus_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]:focus' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'input_focus_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]:focus' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'input_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'input_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'input_padding',
            array(
                'label' => __('Text Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            array(
                'label' => __('Button', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'button_typography',
                'label' => __('Typography', 'elementor'),
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} button[type=submit]',
            )
        );

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
            array(
                'label' => __('Normal'),
            )
        );

        $this->addControl(
            'button_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} button[type=submit]' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} button[type=submit]' => 'border-color: {{VALUE}};',
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
            'button_hover_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_background_hover_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} button[type=submit]:hover' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Animation', 'elementor'),
                'label_block' => false,
                'type' => ControlsManager::HOVER_ANIMATION,
                'separator' => '',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'button_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'button_padding',
            array(
                'label' => __('Text Padding', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} button[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_gdpr_style',
            array(
                'label' => __('GDPR', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'view' => $this->gdpr ? 'traditional' : 'hide',
                ),
            )
        );

        $this->addControl(
            'row_gap',
            array(
                'label' => __('Rows Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'separator' => '',
                'default' => array(
                    'size' => 10,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 60,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-type-gdpr' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'heading_style_label',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Label', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'label_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} label.elementor-field-label' => 'color: {{VALUE}};',
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
                'name' => 'label_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} label.elementor-field-label',
            )
        );

        $this->addControl(
            'heading_style_checkbox',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Checkbox', 'elementor'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'checkbox_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 5,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 60,
                    ),
                ),
                'selectors' => !$this->gdpr ? array() : array(
                    '{{WRAPPER}} input[type=checkbox]' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_messages_style',
            array(
                'label' => __('Messages', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'messages_position',
            array(
                'label' => __('Position', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'before' => __('Before', 'elementor'),
                    'after' => __('After', 'elementor'),
                ),
                'default' => 'after',
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'messages_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-message',
            )
        );

        $this->addControl(
            'heading_style_success',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Success', 'elementor'),
            )
        );

        $this->addControl(
            'success_message_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
                ),
            )
        );

        $this->addControl(
            'heading_style_error',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Error', 'elementor'),
            )
        );

        $this->addControl(
            'error_message_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('form', array(
            'action' => $this->context->link->getModuleLink('creativeelements', 'ajax', array(), null, null, null, true),
            'method' => 'post',
            'data-msg' => $settings['messages_position'],
        ));
        $this->addRenderAttribute('email', array(
            'placeholder' => $settings['placeholder'] ? $settings['placeholder'] : $this->email_placeholder,
        ));
        $this->addRenderAttribute('button', 'class', 'elementor-button');

        if ($settings['hover_animation']) {
            $this->addRenderAttribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }
        ?>
        <form class="elementor-email-subscription" <?php echo $this->getRenderAttributeString('form'); ?>>
            <input type="hidden" name="action" value="0">
            <div class="elementor-field-type-subscribe">
                <input type="email" name="email" class="elementor-field elementor-field-textual" <?php echo $this->getRenderAttributeString('email'); ?> required>
                <button type="submit" name="submitNewsletter" value="1" <?php echo $this->getRenderAttributeString('button'); ?>>
                    <span class="elementor-button-inner">
                    <?php if ($settings['icon'] && 'left' == $settings['icon_align']) : ?>
                        <span class="elementor-button-icon"><i class="<?php echo esc_attr($settings['icon']); ?>"></i></span>
                    <?php endif; ?>
                    <?php if (trim($settings['button']) || !$settings['button']) : ?>
                        <span class="elementor-button-text"><?php echo $settings['button'] ? $settings['button'] : $this->button_placeholder; ?></span>
                    <?php endif ?>
                    <?php if ($settings['icon'] && 'right' == $settings['icon_align']) : ?>
                        <span class="elementor-button-icon"><i class="<?php echo esc_attr($settings['icon']); ?>"></i></span>
                    <?php endif; ?>
                    </span>
                </button>
            </div>
            <?php if ($this->gdpr) : ?>
                <div class="elementor-field-type-gdpr">
                    <label class="elementor-field-label">
                        <input type="checkbox" name="<?php echo $this->gdpr; ?>" value="1" required><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                    </label>
                </div>
            <?php endif; ?>
        </form>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <# var placeholder = settings.email_placeholder || <?php echo json_encode($this->email_placeholder); ?> #>
        <form class="elementor-email-subscription">
            <div class="elementor-field-type-subscribe">
                <input type="email" placeholder="{{ placeholder }}" class="elementor-field elementor-field-textual" required>
                <button type="submit" class="elementor-button elementor-animation-{{ settings.hover_animation }}">
                    <span class="elementor-button-inner">
                    <# if (settings.icon && 'left' == settings.icon_align) { #>
                        <span class="elementor-button-icon"><i class="{{ settings.icon }}"></i></span>
                    <# } #>
                    <# if (settings.button.trim() || !settings.button) { #>
                        <span class="elementor-button-text">{{ settings.button || <?php echo json_encode($this->button_placeholder); ?> }}</span>
                    <# } #>
                    <# if (settings.icon && 'right' == settings.icon_align) { #>
                        <span class="elementor-button-icon"><i class="{{ settings.icon }}"></i></span>
                    <# } #>
                    </span>
                </button>
            </div>
            <?php if ($this->gdpr) : ?>
                <div class="elementor-field-type-gdpr">
                    <label class="elementor-field-label">
                        <input type="checkbox"><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                    </label>
                </div>
            <?php endif; ?>
        </form>
        <?php
    }

    public function __construct($data = array(), $args = array())
    {
        $this->context = \Context::getContext();
        $this->translator = _CE_PS16_ ? null : $this->context->getTranslator();

        $id_lang = (int) \Tools::getValue('id_lang');
        $lang = $id_lang ? new \Language($id_lang) : null;
        $this->locale = isset($lang->locale) ? $lang->locale : null;

        if (_CE_PS16_) {
            $this->email_placeholder = $this->trans('Email address', array(), 'contact-form');
            $this->button_placeholder = $this->trans('Submit', array(), 'order-address');
        } else {
            $this->email_placeholder = $this->trans('Your email address', array(), 'Shop.Forms.Labels');
            $this->button_placeholder = $this->trans('Subscribe', array(), 'Shop.Theme.Actions');
        }

        $this->initGDPR($id_lang);

        parent::__construct($data, $args);
    }

    protected function initGDPR($id_lang)
    {
        empty($id_lang) && $id_lang = $this->context->language->id;

        if (\Module::isEnabled('psgdpr') && \Module::getInstanceByName('psgdpr') &&
            call_user_func('GDPRConsent::getConsentActive', $id_module = \Module::getModuleIdByName('ps_emailsubscription'))
        ) {
            $this->gdpr = 'psgdpr_consent_checkbox';
            $this->gdpr_msg = call_user_func('GDPRConsent::getConsentMessage', $id_module, $id_lang);
            $this->gdpr_cfg = $this->getModuleLink('psgdpr&page=dataConsent');
        } elseif (\Module::isEnabled('gdprpro') && \Configuration::get('gdpr-pro_consent_newsletter_enable')) {
            $this->gdpr = 'gdpr_consent_chkbox';
            $this->gdpr_msg = \Configuration::get('gdpr-pro_consent_newsletter_text', $id_lang);
            $this->gdpr_cfg = empty($this->context->employee) ? '#' : $this->context->link->getAdminLink('AdminGdprConfig');
        }

        // Strip <p> tags from GDPR message
        empty($this->gdpr_msg) or $this->gdpr_msg = preg_replace('~</?p\b.*?>~i', '', $this->gdpr_msg);
    }
}
