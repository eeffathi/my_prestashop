<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks.com, Elementor.com
 * @copyright 2019 WebshopWorks & Elementor
 * @license   One domain support license
 */

namespace CE;

defined('_PS_VERSION_') or exit;

class WidgetFacebookButton extends WidgetBase
{
    public function getName()
    {
        return 'facebook-button';
    }

    public function getTitle()
    {
        return __('Facebook Button', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-facebook-like-box';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    public function getHeight($layout, $size, $share)
    {
        $small = 'small' == $size;

        if ('box_count' == $layout) {
            return $share
                ? ($small ? 64 : 90)
                : ($small ? 40 : 58)
            ;
        }
        return $small ? 20 : 28;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            array(
                'label' => __('Button', 'elementor'),
            )
        );

        $this->addControl(
            'type',
            array(
                'label' => __('Type', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'like',
                'options' => array(
                    'like' => __('Like', 'elementor'),
                    'recommend' => __('Recommend', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'layout',
            array(
                'label' => __('Layout', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'standard',
                'options' => array(
                    'standard' => __('Standard', 'elementor'),
                    'button' => __('Button', 'elementor'),
                    'button_count' => __('Button Count', 'elementor'),
                    'box_count' => __('Box Count', 'elementor'),
                ),
                'prefix_class' => 'elementor-type-',
                'render_type' => 'template',
            )
        );

        $this->addControl(
            'size',
            array(
                'label' => __('Size', 'elementor'),
                'type' => ControlsManager::SELECT,
                'default' => 'small',
                'options' => array(
                    'small' => __('Small', 'elementor'),
                    'large' => __('Large', 'elementor'),
                ),
                'prefix_class' => 'elementor-size-',
                'render_type' => 'template',
            )
        );

        $this->addControl(
            'show_share',
            array(
                'label' => __('Share Button', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
            )
        );

        $this->addControl(
            'url_type',
            array(
                'label' => __('Target URL', 'elementor'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'current' => __('Current Page', 'elementor'),
                    'custom' => __('Custom', 'elementor'),
                ),
                'separator' => 'before',
                'default' => 'current',
            )
        );

        $this->addControl(
            'url',
            array(
                'label' => __('Link', 'elementor'),
                'placeholder' => __('https://your-link.com', 'elementor'),
                'label_block' => true,
                'condition' => array(
                    'url_type' => 'custom',
                ),
            )
        );

        $this->endControlsSection();
    }

    public function render()
    {
        $settings = $this->getSettings();

        if ($settings['url_type'] == 'current') {
            $url = \Tools::getShopProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } elseif (!empty($settings['url']) && \Validate::isAbsoluteUrl($settings['url'])) {
            $url = $settings['url'];
        } else {
            return print Helper::transError('Invalid URL');
        }

        $this->addRenderAttribute('frame', array(
            'src' => 'about:blank',
            'loading' => 'lazy',
            'data-url' => 'https://www.facebook.com/plugins/like.php?' . http_build_query(array(
                'href' => $url,
                'action' => $settings['type'],
                'layout' => $settings['layout'],
                'size' => $settings['size'],
                'share' => $settings['show_share'] ? 'true' : 'false',
            )),
            'style' => 'height: ' . $this->getHeight($settings['layout'], $settings['size'], $settings['show_share']) . 'px;',
            'onload' => "this.removeAttribute('onload'),this.src=this.getAttribute('data-url')",
            'frameborder' => '0',
        ));

        echo "<\x69frame {$this->getRenderAttributeString('frame')}></\x69frame>";
    }

    public function renderPlainContent()
    {
    }
}
