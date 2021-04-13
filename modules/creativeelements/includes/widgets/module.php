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

class WidgetModule extends WidgetBase
{
    public function getName()
    {
        return 'ps-widget-module';
    }

    public function getTitle()
    {
        return __('Module', 'elementor');
    }

    public function getIcon()
    {
        return 'fa fa-puzzle-piece';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function getModuleOptions()
    {
        $modules = array(
            __('- Select Module -', 'elementor'),
        );
        if (\Context::getContext()->controller instanceof \AdminCEEditorController) {
            $exclude_tabs = array(
                'administration',
                'analytics_stats',
                'billing_invoicing',
                'checkout',
                'dashboard',
                'export',
                'emailing',
                'i18n_localization',
                'migration_tools',
                'payments_gateways',
                'payment_security',
                'quick_bulk_update',
                'seo',
                'shipping_logistics',
                'market_place',
            );
            $table = _DB_PREFIX_ . 'module';
            $rows = \Db::getInstance()->executeS(
                "SELECT m.name FROM $table AS m " . \Shop::addSqlAssociation('module', 'm') .
                " WHERE m.active = 1 AND m.name NOT IN ('creativeelements', 'creativepopup', 'layerslider', 'messengerchat')"
            );
            if ($rows) {
                foreach ($rows as &$row) {
                    try {
                        $mod = \Module::getInstanceByName($row['name']);

                        if (!empty($mod->active) && !in_array($mod->tab, $exclude_tabs)) {
                            $modules[$mod->name] = !empty($mod->displayName) ? $mod->displayName : $mod->name;
                        }
                    } catch (\Exception $ex) {
                        // TODO
                    }
                }
            }
        }
        return $modules;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_pswidget_options',
            array(
                'label' => __('Module Settings', 'elementor'),
            )
        );

        $this->addControl(
            'module',
            array(
                'label' => __('Module', 'elementor'),
                'label_block' => true,
                'description' => __('Specify the required hook if needed.', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getModuleOptions(),
                'default' => '0',
            )
        );

        $this->addControl(
            'hook',
            array(
                'label' => __('Hook', 'elementor'),
                'type' => ControlsManager::TEXT,
                'input_list' => array(
                    'displayHome',
                    'displayTop',
                    'displayBanner',
                    'displayNav1',
                    'displayNav2',
                    'displayNavFullWidth',
                    'displayTopColumn',
                    'displayLeftColumn',
                    'displayRightColumn',
                    'displayFooterBefore',
                    'displayFooter',
                    'displayFooterAfter',
                    'displayFooterProduct',
                ),
                'separator' => '',
                'condition' => array(
                    'module!' => '0',
                ),
            )
        );

        $this->endControlsSection();
    }

    protected function renderModule($module, $hook_name, $hook_args = array())
    {
        $res = '';
        try {
            $mod = \Module::getInstanceByName($module);

            if (!empty($mod->active)) {
                if (method_exists($mod, "hook$hook_name")) {
                    $res = $mod->{"hook$hook_name"}($hook_args);
                } elseif (method_exists($mod, 'renderWidget')) {
                    $res = $mod->renderWidget($hook_name, $hook_args);
                }
            }
        } catch (\Exception $ex) {
            // TODO
        }
        return $res;
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }

        $settings = $this->getSettings();

        if ($settings['module']) {
            echo $this->renderModule($settings['module'], !empty($settings['hook']) ? $settings['hook'] : 'displayCEWidget');
        }
    }

    public function renderPlainContent($instance = array())
    {
    }
}
