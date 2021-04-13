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

class WidgetFacebookPage extends WidgetBase
{
    public function getName()
    {
        return 'facebook-page';
    }

    public function getTitle()
    {
        return __('Facebook Page', 'elementor');
    }

    public function getIcon()
    {
        return 'eicon-facebook-like-box';
    }

    public function getCategories()
    {
        return array('general-elements');
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            array(
                'label' => __('Facebook Page', 'elementor'),
            )
        );

        $this->addControl(
            'url',
            array(
                'label' => __('URL', 'elementor'),
                'placeholder' => 'https://www.facebook.com/your-page/',
                'default' => 'https://www.facebook.com/webshopworks/',
                'label_block' => true,
                'description' => __('Paste the URL of the Facebook page.', 'elementor'),
            )
        );

        $this->addControl(
            'tabs',
            array(
                'label' => __('Tabs', 'elementor'),
                'type' => ControlsManager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => array(
                    'timeline',
                ),
                'options' => array(
                    'timeline' => __('Timeline', 'elementor'),
                    'events' => __('Events', 'elementor'),
                    'messages' => __('Messages', 'elementor'),
                ),
            )
        );

        $this->addControl(
            'small_header',
            array(
                'label' => __('Small Header', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
            )
        );

        $this->addControl(
            'show_cover',
            array(
                'label' => __('Cover', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
            )
        );

        $this->addControl(
            'show_facepile',
            array(
                'label' => __('Profile Photos', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
            )
        );

        $this->addControl(
            'show_cta',
            array(
                'label' => __('Custom CTA Button', 'elementor'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
                'condition' => array(
                    'small_header' => '',
                ),
            )
        );

        $this->addControl(
            'height',
            array(
                'label' => __('Height', 'elementor'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'unit' => 'px',
                    'size' => 500,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 70,
                        'max' => 1000,
                    ),
                ),
                'size_units' => array('px'),
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();
    }

    public function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['url'])) {
            echo $this->getTitle() . ': ' . __('Please enter a valid URL', 'elementor');

            return;
        }

        $this->addRenderAttribute('frame', array(
            'height' => $settings['height']['size'],
            'src' => 'about:blank',
            'loading' => 'lazy',
            'data-url' => 'https://www.facebook.com/plugins/page.php?' . http_build_query(array(
                'href' => $settings['url'],
                'tabs' => implode(',', $settings['tabs']),
                'small_header' => $settings['small_header'] ? 'true' : 'false',
                'hide_cover' => $settings['show_cover'] ? 'false' : 'true',
                'show_facepile' => $settings['show_facepile'] ? 'true' : 'false',
                'hide_cta' => $settings['show_cta'] ? 'false' : 'true',
                'height' => $settings['height']['size'],
                'width' => '',
            )),
            'onload' => "this.removeAttribute('onload'),this.src=this.getAttribute('data-url')+this.offsetWidth",
            'style' => implode(';', array(
                'border: none',
                'min-height: 70px',
                'min-width: 180px',
                'max-width: 500px',
            )),
            'frameborder' => '0',
            'scrolling' => 'no',
            'allow' => 'encrypted-media',
            'allowFullscreen' => 'true',
        ));

        echo "<\x69frame {$this->getRenderAttributeString('frame')}></\x69frame>";
    }

    public function renderPlainContent()
    {
    }
}
