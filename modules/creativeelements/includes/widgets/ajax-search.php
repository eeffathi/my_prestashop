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

class WidgetAjaxSearch extends WidgetBase
{
    public function getName()
    {
        return 'ajax-search';
    }

    public function getTitle()
    {
        return __('AJAX Search');
    }

    public function getIcon()
    {
        return 'eicon-search';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function _registerControls()
    {

        $this->startControlsSection(
            'search_content',
            array(
                'label' => __('AJAX Search'),
            )
        );

        $this->addControl(
            'skin',
            array(
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'default' => 'classic',
                'options' => array(
                    'classic' => __('Classic'),
                    'minimal' => __('Minimal'),
                ),
                'prefix_class' => 'skin-',
                'render_type' => 'template',
            )
        );

        $this->addControl(
            'size',
            array(
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 50,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-ajax-search-submit' => 'min-width: {{SIZE}}{{UNIT}}',
                    'body:not(.rtl) {{WRAPPER}} .elementor-ajax-search-icon' => 'padding-left: calc({{SIZE}}{{UNIT}} / 3)',
                    'body.rtl {{WRAPPER}} .elementor-ajax-search-icon' => 'padding-right: calc({{SIZE}}{{UNIT}} / 3)',
                    '{{WRAPPER}}.button-type-text .elementor-ajax-search-submit, ' .
                    '{{WRAPPER}} .elementor-ajax-search-field' => 'padding-left: calc({{SIZE}}{{UNIT}} / 3); padding-right: calc({{SIZE}}{{UNIT}} / 3)',
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'placeholder',
            array(
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search our catalog'),
            )
        );

        $this->addControl(
            'heading_button_content',
            array(
                'label' => __('Button'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'skin' => 'classic',
                ),
            )
        );

        $this->addControl(
            'button_type',
            array(
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'icon',
                'options' => array(
                    'icon' => __('Icon'),
                    'text' => __('Text'),
                ),
                'prefix_class' => 'button-type-',
                'render_type' => 'template',
                'condition' => array(
                    'skin' => 'classic',
                ),
            )
        );

        $this->addControl(
            'button_text',
            array(
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search'),
                'condition' => array(
                    'button_type' => 'text',
                    'skin' => 'classic',
                ),
            )
        );

        $this->addControl(
            'icon',
            array(
                'label' => __('Icon'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'default' => 'search',
                'options' => array(
                    'search' => array(
                        'title' => __('Search'),
                        'icon' => 'fa fa-search',
                    ),
                    'arrow' => array(
                        'title' => __('Arrow'),
                        'icon' => 'fa fa-arrow-right',
                    ),
                ),
                'separator' => '',
                'render_type' => 'template',
                'condition' => array(
                    'button_type' => 'icon',
                    'skin' => 'classic',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_input_style',
            array(
                'label' => __('Input'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'button_border_width',
            array(
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'border_radius',
            array(
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 200,
                    ),
                ),
                'default' => array(
                    'size' => 3,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->addResponsiveControl(
            'icon_size_minimal',
            array(
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'skin' => 'minimal',
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} input[type="search"].elementor-ajax-search-field',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
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
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-field, {{WRAPPER}} .elementor-ajax-search-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'input_background_color',
            array(
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-wrapper' => 'background-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->addControl(
            'input_border_color',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-wrapper' => 'border-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-ajax-search-wrapper',
                'separator' => '',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_input_focus',
            array(
                'label' => __('Focus'),
            )
        );

        $this->addControl(
            'input_text_color_focus',
            array(
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search--focus .elementor-ajax-search-field, ' .
                    '{{WRAPPER}} .elementor-ajax-search--focus .elementor-ajax-search-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'input_background_color_focus',
            array(
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search--focus .elementor-ajax-search-wrapper' => 'background-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->addControl(
            'input_border_color_focus',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search--focus .elementor-ajax-search-wrapper' => 'border-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'input_box_shadow_focus',
                'selector' => '{{WRAPPER}} .elementor-ajax-search--focus .elementor-ajax-search-wrapper',
                'separator' => '',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            array(
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => array(
                    'skin' => 'classic',
                ),
            )
        );

        $this->addResponsiveControl(
            'button_width',
            array(
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit' => 'min-width: {{SIZE}}%',
                ),
            )
        );

        $this->addResponsiveControl(
            'icon_size',
            array(
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'button_type' => 'icon',
                ),
                'separator' => '',
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-ajax-search-submit',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'condition' => array(
                    'button_type' => 'text',
                ),
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
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'button_background_color',
            array(
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit' => 'background-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_button_hover',
            array(
                'label' => __('Hover'),
            )
        );

        $this->addControl(
            'button_text_color_hover',
            array(
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->addControl(
            'button_background_color_hover',
            array(
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-ajax-search-submit:hover' => 'background-color: {{VALUE}}',
                ),
                'separator' => '',
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute(
            'input',
            array(
                'placeholder' => $settings['placeholder'],
                'class' => 'elementor-ajax-search-field',
                'type' => 'search',
                'name' => _CE_PS16_ ? 'search_query' : 's',
                'arial-label' => __('Search'),
                'value' => \Tools::getValue('controller') == 'search' ? \Tools::getValue('s', '') : '',
            )
        );

        // Set the selected icon.
        if ('icon' == $settings['button_type']) {
            $icon_class = 'search';

            if ('arrow' == $settings['icon']) {
                $icon_class = is_rtl() ? 'arrow-left' : 'arrow-right';
            }

            $this->addRenderAttribute('icon', array(
                'class' => 'fa fa-' . $icon_class,
            ));
        }
        ?>
        <form class="elementor-ajax-search" role="search" action="<?php echo \Context::getContext()->link->getPageLink('search') ?>" method="get">
            <input type="hidden" name="controller" value="search">
            <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
            <div class="elementor-ajax-search-wrapper">
                <?php if ('minimal' === $settings['skin']) : ?>
                    <div class="elementor-ajax-search-icon">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                <?php endif;?>
                <input <?php echo $this->getRenderAttributeString('input'); ?>>
                <?php if ('classic' === $settings['skin']) : ?>
                <button class="elementor-ajax-search-submit" type="submit">
                    <?php if ('icon' === $settings['button_type']) : ?>
                        <i <?php echo $this->getRenderAttributeString('icon'); ?> aria-hidden="true"></i>
                    <?php elseif (!empty($settings['button_text'])) : ?>
                        <?php echo $settings['button_text']; ?>
                    <?php endif;?>
                </button>
                <?php endif;?>
            </div>
        </form>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var iconClass = 'fa fa-search';

        if ( 'arrow' === settings.icon ) {
            if ( elementor.config.is_rtl ) {
                iconClass = 'fa fa-arrow-left';
            } else {
                iconClass = 'fa fa-arrow-right';
            }
        }
        #>
        <form class="elementor-ajax-search" action="" role="search">
            <input type="hidden" name="controller" value="search">
            <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
            <div class="elementor-ajax-search-wrapper">
                <# if ( 'minimal' === settings.skin ) { #>
                    <div class="elementor-ajax-search-icon">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                <# } #>
                <input type="search"
                       name="<?php echo _CE_PS16_ ? 'search_query' : 's'; ?>"
                       title="<?php esc_attr_e('Search', 'elementor');?>"
                       class="elementor-ajax-search-field"
                       placeholder="{{ settings.placeholder }}">

                <# if ( 'classic' === settings.skin ) { #>
                    <button class="elementor-ajax-search-submit" type="submit">
                        <# if ( 'icon' === settings.button_type ) { #>
                            <i class="{{ iconClass }}" aria-hidden="true"></i>
                        <# } else if ( settings.button_text ) { #>
                            {{{ settings.button_text }}}
                        <# } #>
                    </button>
                <# } #>
            </div>
        </form>
        <?php
    }
}
