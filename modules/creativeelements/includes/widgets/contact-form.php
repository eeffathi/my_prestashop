<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

namespace CE;

defined('_PS_VERSION_') or exit;

define('_CE_CF_DOMAIN_', _CE_PS16_ ? 'contact-form' : 'Modules.Contactform.Shop');

class WidgetContactForm extends WidgetBase
{
    protected static $col_width = array(
        '100' => '100%',
        '80' => '80%',
        '75' => '75%',
        '66' => '66%',
        '60' => '60%',
        '50' => '50%',
        '40' => '40%',
        '33' => '33%',
        '25' => '25%',
        '20' => '20%',
    );

    protected $context;

    protected $translator;

    protected $locale;

    protected $locale_fo;

    protected $upload;

    protected $gdpr;

    protected $gdpr_msg;

    protected $gdpr_cfg;

    public function getName()
    {
        return 'contact-form';
    }

    public function getTitle()
    {
        return __('Contact Form', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-form-horizontal';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function getContactOptions()
    {
        $contacts = \Contact::getContacts($this->context->language->id);
        $opts = array(
            '0' => __('Select', 'elementor'),
        );
        foreach ($contacts as $contact) {
            $opts[$contact['id_contact']] = $contact['name'];
        }
        return $opts;
    }

    protected function getModuleLink($module)
    {
        if (empty($this->context->employee->id)) {
            return '#';
        }
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $module;
    }

    protected function getToken()
    {
        if (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
            if (empty($this->context->cookie->contactFormTokenTTL) || $this->context->cookie->contactFormTokenTTL < time()) {
                $this->context->cookie->contactFormToken = md5(uniqid());
                $this->context->cookie->contactFormTokenTTL = time() + 600;
            }
            return $this->context->cookie->contactFormToken;
        }
        if (version_compare(_PS_VERSION_, '1.6.1.17', '>=') && version_compare(_PS_VERSION_, '1.7', '<')) {
            if (empty($this->context->cookie->contactFormKey)) {
                $this->context->cookie->contactFormKey = md5(uniqid(microtime(), true));
            }
            return $this->context->cookie->contactFormKey;
        }
        return '';
    }

    protected function trans($id, array $params = array(), $domain = _CE_CF_DOMAIN_, $locale = null)
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
            'section_form_content',
            array(
                'label' => __('Form Fields', 'elementor'),
            )
        );

        $this->addControl(
            'subject_id',
            array(
                'label' => $this->trans('Subject Heading'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getContactOptions(),
                'default' => '0',
            )
        );

        $this->addControl(
            'show_upload',
            array(
                'label' => $this->trans('Attach File'),
                'type' => $this->upload ? ControlsManager::SWITCHER : ControlsManager::HIDDEN,
                'default' => 'yes',
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
            )
        );

        $this->addControl(
            'input_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'xs' => __('Extra Small', 'elementor'),
                    'sm' => __('Small', 'elementor'),
                    'md' => __('Medium', 'elementor'),
                    'lg' => __('Large', 'elementor'),
                    'xl' => __('Extra Large', 'elementor'),
                ),
                'default' => 'sm',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'show_labels',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_off' => __('Hide', 'elementor'),
                'label_on' => __('Show', 'elementor'),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_subject_content',
            array(
                'label' => $this->trans('Subject Heading'),
                'condition' => array(
                    'subject_id' => '0',
                ),
            )
        );

        $this->addControl(
            'subject_label',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Subject Heading', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => array(
                    'show_labels' => 'yes',
                ),
            )
        );

        $this->addResponsiveControl(
            'subject_width',
            array(
                'label' => __('Column Width', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_email_content',
            array(
                'label' => $this->trans('Email address'),
            )
        );

        $this->addControl(
            'email_label',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Email address', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addControl(
            'email_placeholder',
            array(
                'label' => __('Placeholder', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => _CE_PS16_ ? '' : $this->trans('your@email.com', array(), 'Shop.Forms.Help', $this->locale_fo),
            )
        );

        $this->addResponsiveControl(
            'email_width',
            array(
                'label' => __('Column Width', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_upload_content',
            array(
                'label' => $this->trans('Attach File'),
                'condition' => array(
                    'show_upload' => $this->upload ? 'yes' : 'hide',
                ),
            )
        );

        $this->addControl(
            'upload_label',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Attach File', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => array(
                    'show_labels' => 'yes',
                ),
            )
        );

        $this->addResponsiveControl(
            'upload_width',
            array(
                'label' => __('Column Width', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_message_content',
            array(
                'label' => $this->trans('Message'),
            )
        );

        $this->addControl(
            'message_label',
            array(
                'label' => __('Label', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Message', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addControl(
            'message_placeholder',
            array(
                'label' => __('Placeholder', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => _CE_PS16_ ? '' : $this->trans('How can we help?', array(), 'Shop.Forms.Help', $this->locale_fo),
            )
        );

        $this->addResponsiveControl(
            'message_width',
            array(
                'label' => __('Column Width', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            )
        );

        $this->addControl(
            'message_rows',
            array(
                'label' => __('Rows', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => '4',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_content',
            array(
                'label' => __('Button', 'elementor'),
            )
        );

        $this->addControl(
            'button_text',
            array(
                'label' => __('Text', 'elementor'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Send', array(), _CE_CF_DOMAIN_, $this->locale_fo),
            )
        );

        $this->addControl(
            'button_size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'xs' => __('Extra Small', 'elementor'),
                    'sm' => __('Small', 'elementor'),
                    'md' => __('Medium', 'elementor'),
                    'lg' => __('Large', 'elementor'),
                    'xl' => __('Extra Large', 'elementor'),
                ),
                'default' => 'sm',
            )
        );

        $this->addResponsiveControl(
            'button_width',
            array(
                'label' => __('Column Width', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            )
        );

        $this->addResponsiveControl(
            'button_align',
            array(
                'label' => __('Alignment', 'elementor'),
                'type' => ControlsManager::CHOOSE,
                'options' => array(
                    'start' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'elementor'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'end' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'fa fa-align-right',
                    ),
                    'stretch' => array(
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'default' => 'stretch',
                'prefix_class' => 'elementor%s-button-align-',
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
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'icon!' => '',
                ),
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

        defined(_CE_PS16_) && !_CE_PS16_ && $this->addControl(
            'configure_module',
            array(
                'raw' => __('Contact Form', 'elementor') . '<br><br>' .
                    '<a class="elementor-button elementor-button-default" href="' . esc_attr($this->getModuleLink('contactform')) . '" target="_blank">' .
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

        $this->addControl(
            'custom_messages',
            array(
                'label' => __('Messages', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    '' => __('Default', 'elementor'),
                    'yes' => __('Custom', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'success_message',
            array(
                'label' => __('Success', 'elementor'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Your message has been successfully sent to our team.', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => array(
                    'custom_messages!' => '',
                ),
            )
        );

        $this->addControl(
            'error_message',
            array(
                'label' => __('Error', 'elementor'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => _CE_PS16_
                    ? \Tools::displayError('An error occurred while sending the message.')
                    : $this->trans('An error occurred while sending the message.', array(), _CE_CF_DOMAIN_, $this->locale_fo),
                'separator' => '',
                'condition' => array(
                    'custom_messages!' => '',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_form',
            array(
                'label' => __('Form', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'column_gap',
            array(
                'label' => __('Columns Gap', 'elementor'),
                'type' => ControlsManager::SLIDER,
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
                    '{{WRAPPER}} .elementor-field-group' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
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
                    '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'heading_style_label',
            array(
                'type' => ControlsManager::HEADING,
                'label' => __('Label', 'elementor'),
                'separator' => 'before',
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addControl(
            'label_spacing',
            array(
                'label' => __('Spacing', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 0,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 60,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addControl(
            'label_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group label' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'label_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-field-group label',
                'condition' => array(
                    'show_labels!' => '',
                ),
            )
        );

        empty($this->gdpr) or $this->addControl(
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
                'type' => $this->gdpr ? ControlsManager::SLIDER : ControlsManager::HIDDEN,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
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
            'section_field_style',
            array(
                'label' => __('Field', 'elementor'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->addControl(
            'field_text_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
            )
        );

        $this->addControl(
            'field_background_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'field_border_color',
            array(
                'label' => __('Border Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'field_border_width',
            array(
                'label' => __('Border Width', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'field_border_radius',
            array(
                'label' => __('Border Radius', 'elementor'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'separator' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-button',
            )
        );

        $this->startControlsTabs('tabs_button_style');

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
                'default' => '',
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
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ),
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
            'button_hover_color',
            array(
                'label' => __('Text Color', 'elementor'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'button_background_hover_color',
            array(
                'label' => __('Background Color', 'elementor'),
                'separator' => '',
                'type' => ControlsManager::COLOR,
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

        $this->addControl(
            'button_hover_animation',
            array(
                'label' => __('Animation', 'elementor'),
                'type' => ControlsManager::HOVER_ANIMATION,
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
        $id = $this->getId();
        $token = $this->getToken();
        $show_labels = (bool) $settings['show_labels'];
        $input_classes = 'elementor-field elementor-size-' . esc_attr($settings['input_size']);

        $this->addRenderAttribute('form', array(
            'action' => $this->context->link->getModuleLink('creativeelements', 'ajax', array(), null, null, null, true),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'data-success' => $settings['custom_messages'] ? $settings['success_message'] : '',
            'data-error' => $settings['custom_messages'] ? $settings['error_message'] : '',
        ));
        $this->addRenderAttribute('email', array(
            'id' => 'from-' . $id,
            'value' => isset($this->context->customer->email) ? $this->context->customer->email : '',
            'placeholder' => $settings['email_placeholder'] ? $settings['email_placeholder'] : (
                _CE_PS16_ ? '' : $this->trans('your@email.com', array(), 'Shop.Forms.Help')
            ),
        ));
        $this->addRenderAttribute('message', array(
            'id' => 'message-' . $id,
            'placeholder' => $settings['message_placeholder'] ? $settings['message_placeholder'] : (
                _CE_PS16_ ? '' : $this->trans('How can we help?', array(), 'Shop.Forms.Help')
            ),
            'rows' => (int) $settings['message_rows'],
        ));
        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);

        if ($settings['button_hover_animation']) {
            $this->addRenderAttribute('button', 'class', 'elementor-animation-' . $settings['button_hover_animation']);
        }
        ?>
        <form class="elementor-contact-form" <?php echo $this->getRenderAttributeString('form'); ?>>
            <div class="elementor-form-fields-wrapper">
                <input type="hidden" name="url">
                <?php if ($token) : ?>
                    <input type="hidden" name="<?php echo _CE_PS16_ ? 'contactKey' : 'token'; ?>" value="<?php echo esc_attr($token); ?>">
                <?php endif; ?>
                <?php if ($settings['subject_id']) : ?>
                    <input type="hidden" name="id_contact" value="<?php echo (int) $settings['subject_id']; ?>">
                <?php else : ?>
                    <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['subject_width']; ?> elementor-field-type-select">
                        <?php if ($show_labels) : ?>
                            <label class="elementor-field-label" for="id-contact-<?php echo $id; ?>">
                                <?php echo $settings['subject_label'] ? $settings['subject_label'] : $this->trans('Subject Heading'); ?>
                            </label>
                        <?php endif; ?>
                        <div class="elementor-select-wrapper">
                            <select name="id_contact" id="id-contact-<?php echo $id; ?>" class="elementor-field elementor-field-textual elementor-size-<?php echo esc_attr($settings['input_size']); ?>">
                            <?php foreach (\Contact::getContacts($this->context->language->id) as $contact) : ?>
                                <option value="<?php echo (int) $contact['id_contact'] ?>"><?php echo $contact['name']; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['email_width']; ?> elementor-field-type-email">
                    <?php if ($show_labels) : ?>
                        <label class="elementor-field-label" for="from-<?php echo $id; ?>">
                            <?php echo $settings['email_label'] ? $settings['email_label'] : $this->trans('Email address'); ?>
                        </label>
                    <?php endif; ?>
                    <input type="email" name="from" <?php echo $this->getRenderAttributeString('email'); ?> class="<?php echo $input_classes; ?> elementor-field-textual" required>
                </div>
                <?php if ($this->upload && $settings['show_upload']) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['upload_width'] ?> elementor-field-type-file">
                        <?php if ($show_labels) : ?>
                            <label class="elementor-field-label" for="file-upload-<?php echo $id; ?>">
                                <?php echo $settings['upload_label'] ? $settings['upload_label'] : $this->trans('Attach File'); ?>
                            </label>
                        <?php endif; ?>
                        <input type="file" name="fileUpload" id="file-upload-<?php echo $id; ?>" class="<?php echo $input_classes; ?>">
                    </div>
                <?php endif; ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['message_width'] ?> elementor-field-type-textarea">
                    <?php if ($show_labels) : ?>
                        <label class="elementor-field-label" for="message-<?php echo $id; ?>">
                            <?php echo $settings['message_label'] ? $settings['message_label'] : $this->trans('Message'); ?>
                        </label>
                    <?php endif; ?>
                    <textarea name="message" <?php echo $this->getRenderAttributeString('message'); ?> class="<?php echo $input_classes; ?> elementor-field-textual" required></textarea>
                </div>
                <?php if ($this->gdpr) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-gdpr">
                        <label class="elementor-field-label">
                            <input type="checkbox" name="<?php echo $this->gdpr; ?>" value="1" required><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                        </label>
                    </div>
                <?php endif; ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['button_width'] ?> elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" <?php echo $this->getRenderAttributeString('button'); ?>>
                        <span class="elementor-button-inner">
                            <span class="elementor-button-text"><?php echo $settings['button_text'] ? $settings['button_text'] : $this->trans('Send'); ?></span>
                            <?php if (!empty($settings['icon'])) : ?>
                                <span class="elementor-align-icon-<?php echo esc_attr($settings['icon_align']); ?>">
                                    <i class="<?php echo esc_attr($settings['icon']); ?>"></i>
                                </span>
                            <?php endif; ?>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }

    protected function _contentTemplate()
    {
        $this->locale = $this->locale_fo;
        ?>
        <#
        var contacts = <?php echo json_encode(\Contact::getContacts($this->context->language->id)); ?>,
            email_placeholder = settings.email_placeholder || <?php echo json_encode(_CE_PS16_ ? '' : $this->trans('your@email.com', array(), 'Shop.Forms.Help')); ?>,
            message_placeholder = settings.message_placeholder || <?php echo json_encode(_CE_PS16_ ? '' : $this->trans('How can we help?', array(), 'Shop.Forms.Help')); ?>,
            upload = <?php echo $this->upload ? 1 : 0; ?>;
        #>
        <form class="elementor-contact-form">
            <div class="elementor-form-fields-wrapper">
                <# if (settings.subject_id <= 0) { #>
                    <div class="elementor-field-group elementor-column elementor-col-{{ settings.subject_width }} elementor-field-type-select">
                        <# if (settings.show_labels) { #>
                            <label class="elementor-field-label">{{ settings.subject_label || <?php echo json_encode($this->trans('Subject Heading')); ?> }}</label>
                        <# } #>
                        <div class="elementor-select-wrapper">
                            <select name="id_contact" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                            <# _.each(contacts, function(contact) { #>
                                <option>{{ contact.name }}</option>
                            <# }) #>
                            </select>
                        </div>
                    </div>
                <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.email_width }} elementor-field-type-email">
                    <# if (settings.show_labels) { #>
                        <label class="elementor-field-label">{{ settings.email_label || <?php echo json_encode($this->trans('Email address')); ?> }}</label>
                    <# } #>
                    <input type="email" name="from" placeholder="{{ email_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                </div>
                <# if (upload && settings.show_upload) { #>
                    <div class="elementor-field-group elementor-column elementor-col-{{ settings.upload_width }} elementor-field-type-file">
                        <# if (settings.show_labels) { #>
                            <label class="elementor-field-label">{{ settings.upload_label || <?php echo json_encode($this->trans('Attach File')); ?> }}</label>
                        <# } #>
                        <input type="file" name="fileUpload" class="elementor-field elementor-size-{{ settings.input_size }} ?>">
                    </div>
                <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.message_width }} elementor-field-type-textarea">
                    <# if (settings.show_labels) { #>
                        <label class="elementor-field-label">{{ settings.message_label || <?php echo json_encode($this->trans('Message')); ?> }}</label>
                    <# } #>
                    <textarea name="message" placeholder="{{ message_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" rows="{{ settings.message_rows }}"></textarea>
                </div>
                <?php if ($this->gdpr) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-gdpr">
                        <label class="elementor-field-label">
                            <input type="checkbox"><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                        </label>
                    </div>
                <?php endif; ?>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.button_width }} elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" class="elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_hover_animation }}">
                        <span class="elementor-button-inner">
                            <span class="elementor-button-text">{{ settings.button_text || <?php echo json_encode($this->trans('Send')); ?> }}</span>
                            <# if (settings.icon) { #>
                                <span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
                                    <i class="{{ settings.icon }}"></i>
                                </span>
                            <# } #>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
        $this->locale = null;
    }

    public function __construct($data = array(), $args = array())
    {
        $this->context = \Context::getContext();
        $this->translator = _CE_PS16_ ? null : $this->context->getTranslator();

        $id_lang = (int) \Tools::getValue('id_lang');
        $lang = $id_lang ? new \Language($id_lang) : null;
        $this->locale_fo = isset($lang->locale) ? $lang->locale : null;

        $this->upload = \Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD');
        $this->initGDPR($id_lang);

        parent::__construct($data, $args);
    }

    protected function initGDPR($id_lang)
    {
        empty($id_lang) && $id_lang = $this->context->language->id;

        if (\Module::isEnabled('psgdpr') && \Module::getInstanceByName('psgdpr') &&
            call_user_func('GDPRConsent::getConsentActive', $id_module = \Module::getModuleIdByName('contactform'))
        ) {
            $this->gdpr = 'psgdpr_consent_checkbox';
            $this->gdpr_msg = call_user_func('GDPRConsent::getConsentMessage', $id_module, $id_lang);
            $this->gdpr_cfg = $this->getModuleLink('psgdpr&page=dataConsent');
        } elseif (\Module::isEnabled('gdprpro') && \Configuration::get('gdpr-pro_consent_contact_enable')) {
            $this->gdpr = 'gdpr_consent_chkbox';
            $this->gdpr_msg = \Configuration::get('gdpr-pro_consent_contact_text', $id_lang);
            $this->gdpr_cfg = empty($this->context->employee) ? '#' : $this->context->link->getAdminLink('AdminGdprConfig');
        }

        // Strip <p> tags from GDPR message
        empty($this->gdpr_msg) or $this->gdpr_msg = preg_replace('~</?p\b.*?>~i', '', $this->gdpr_msg);
    }
}
