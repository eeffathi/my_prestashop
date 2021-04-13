<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or exit;

class CreativeElementsAjaxModuleFrontController extends ModuleFrontController
{
    protected $content_only = true;

    public function postProcess()
    {
        Tools::getValue('submitMessage') && $this->ajaxProcessSubmitMessage();

        Tools::getValue('submitNewsletter') && $this->ajaxProcessSubmitNewsletter();
    }

    public function ajaxProcessSubmitMessage()
    {
        if (_CE_PS16_) {
            require_once _PS_FRONT_CONTROLLER_DIR_ . 'ContactController.php';

            $contact = new ContactController();
            $contact->postProcess();

            $this->ajaxDie(array(
                'success' => empty($contact->errors)
                    ? $GLOBALS['_LANG']['contact-form_' . md5('Your message has been successfully sent to our team.')]
                    : '',
                'errors' => $contact->errors,
            ));
        }

        if ($contact = Module::getInstanceByName('contactform')) {
            $contact->sendMessage();

            $this->ajaxDie(array(
                'success' => implode(nl2br("\n", false), $this->success),
                'errors' => $this->errors,
            ));
        }

        $this->ajaxDie(array(
            'errors' => array('Error: Contact Form module should be enabled!'),
        ));
    }

    public function ajaxProcessSubmitNewsletter()
    {
        $name = _CE_PS16_ ? 'blocknewsletter' : 'ps_emailsubscription';
        $newsletter = Module::getInstanceByName($name);

        if (!$newsletter) {
            $this->ajaxDie(array(
                'errors' => array("Error: $name module should be enabled!"),
            ));
        }

        if (_CE_PS16_) {
            $rm = new ReflectionMethod($newsletter, 'newsletterRegistration');
            $rm->setAccessible(true);
            $rm->invoke($newsletter);
        } else {
            $newsletter->newsletterRegistration(${'_POST'}['blockHookName'] = 'displayCE');
        }

        $this->ajaxDie(array(
            'success' => empty($newsletter->valid) ? '' : array($newsletter->valid),
            'errors' => empty($newsletter->error) ? array() : array($newsletter->error),
        ));
    }

    protected function ajaxDie($value = null, $controller = null, $method = null)
    {
        if (null === $controller) {
            $controller = get_class($this);
        }
        if (null === $method) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $method = $bt[1]['function'];
        }
        if (version_compare(_PS_VERSION_, '1.6.1.1', '<')) {
            Hook::exec('actionBeforeAjaxDie', array('controller' => $controller, 'method' => $method, 'value' => $value));
            Hook::exec('actionBeforeAjaxDie' . $controller . $method, array('value' => $value));
        } else {
            Hook::exec('actionAjaxDie' . $controller . $method . 'Before', array('value' => $value));
        }
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        die(json_encode($value));
    }
}
