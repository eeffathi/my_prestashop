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

class WidgetImageSlider extends WidgetBase
{
    public function getName()
    {
        return 'image-slider';
    }

    public function getTitle()
    {
        return __('Image Slider', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-slideshow';
    }

    public function getCategories()
    {
        return array('prestashop');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image_slider',
            array(
                'label' => __('Image Slider', 'elementor'),
            )
        );

        $this->addControl(
            'speed',
            array(
                'label' => __('Speed (ms)', 'elementor'),
                'type' => ControlsManager::NUMBER,
                'default' => '5000',
            )
        );

        $this->addControl(
            'pause',
            array(
                'label' => __('Pause on Hover', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => __('Yes', 'elementor'),
                    '' => __('No', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'wrap',
            array(
                'label' => __('Loop forever', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => __('Yes', 'elementor'),
                    '' => __('No', 'elementor'),
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_slides_list',
            array(
                'label' => __('Slides List', 'elementor'),
            )
        );

        $modules = basename(_MODULE_DIR_);
        $img = 'ps_imageslider/images/sample';
        $desc = '<h2>EXCEPTEUR OCCAECAT</h2>' .
            '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tristique in tortor et dignissim. Quisque non tempor leo. Maecenas egestas sem elit</p>';

        $this->addControl(
            'slides',
            array(
                'type' => ControlsManager::REPEATER,
                'default' => array(
                    array(
                        'image' =>array(
                            'url' => file_exists(_PS_MODULE_DIR_ . $img . '-1.jpg') ? "$modules/$img-1.jpg" : Utils::getPlaceholderImageSrc(),
                            'id' => 0,
                        ),
                        'title' => 'SAMPLE 1',
                        'description' => $desc,
                        'active' => '1',
                    ),
                    array(
                        'image' =>array(
                            'url' => file_exists(_PS_MODULE_DIR_ . $img . '-2.jpg') ? "$modules/$img-2.jpg" : Utils::getPlaceholderImageSrc(),
                            'id' => 0,
                        ),
                        'title' => 'SAMPLE 2',
                        'description' => $desc,
                        'active' => '1',
                    ),
                    array(
                        'image' =>array(
                            'url' => file_exists(_PS_MODULE_DIR_ . $img . '-3.jpg') ? "$modules/$img-3.jpg" : Utils::getPlaceholderImageSrc(),
                            'id' => 0,
                        ),
                        'title' => 'SAMPLE 3',
                        'legend' => 'Excepteur Occaecat',
                        'description' => $desc,
                        'active' => '1',
                    ),
                ),
                'fields' => array(
                    array(
                        'name' => 'image',
                        'label' => __('Choose Image', 'elementor'),
                        'type' => ControlsManager::MEDIA,
                        'seo' => true,
                        'default' => array(
                            'url' => Utils::getPlaceholderImageSrc(),
                        ),
                    ),
                    array(
                        'name' => 'title',
                        'label' => __('Title & Description', 'elementor'),
                        'label_block' => true,
                        'type' => ControlsManager::TEXT,
                        'placeholder' => __('Enter your title', 'elementor'),
                        'default' => __('This is heading element', 'elementor'),
                    ),
                    array(
                        'name' => 'description',
                        'label' => __('Description', 'elementor'),
                        'type' => ControlsManager::WYSIWYG,
                        'default' => '',
                    ),
                    array(
                        'name' => 'url',
                        'label' => __('Link', 'elementor'),
                        'type' => ControlsManager::TEXT,
                        'label_block' => true,
                        'placeholder' => __('http://your-link.com', 'elementor'),
                    ),
                    array(
                        'name' => 'active',
                        'label' => __('Enabled', 'elementor'),
                        'type' => ControlsManager::SELECT,
                        'default' => '1',
                        'options' => array(
                            '1' => __('Yes', 'elementor'),
                            '' => __('No', 'elementor'),
                        ),
                    ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }

        $settings = $this->getSettings();
        $editSettings = Plugin::instance()->editor->isEditMode() ? $this->getData('editSettings') : null;
        $activeItemIndex = isset($editSettings['activeItemIndex']) ? $editSettings['activeItemIndex'] : 0;
        $slides = array();

        foreach ($settings['slides'] as $i => &$slide) {
            if (!empty($slide['active']) && !$activeItemIndex || $i + 1 == $activeItemIndex) {
                $slides[] = array(
                    'image_url' => Helper::getMediaLink($slide['image']['url']),
                    'title' => $slide['title'],
                    'legend' => !empty($slide['image']['alt']) ? $slide['image']['alt'] : '',
                    'description' => $slide['description'],
                    'url' => $slide['url'],
                );
            }
        }

        $context = \Context::getContext();
        $context->smarty->assign(array(
            'homeslider' => array(
                'speed' => $settings['speed'],
                'pause' => $settings['pause'] ? 'true' : 'false',
                'wrap' => $settings['wrap'] ? 'true' : 'false',
                'slides' => $slides,
            ),
        ));

        $tpl = 'modules/ps_imageslider/views/templates/hook/slider.tpl';

        if (file_exists(_PS_THEME_DIR_ . $tpl)) {
            $tpl_path = _PS_THEME_DIR_ . $tpl;
        } elseif (!_CE_PS16_ && ($parent = $context->shop->theme->get('parent')) && file_exists(_PS_ALL_THEMES_DIR_ . "$parent/$tpl")) {
            $tpl_path = _PS_ALL_THEMES_DIR_ . "$parent/$tpl";
        } else {
            $tpl_path = _PS_ROOT_DIR_ . $tpl;
        }

        echo $context->smarty->fetch($tpl_path);
    }

    public function renderPlainContent()
    {
    }
}
