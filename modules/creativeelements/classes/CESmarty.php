<?php
/**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2020 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or die;

class CESmarty
{
    protected static $tpls = array();

    protected static function getTemplate($path)
    {
        if (isset(self::$tpls[$path])) {
            return self::$tpls[$path];
        }

        $tpl = Context::getContext()->smarty->createTemplate($path);
        CE\do_action('smarty/before_fetch', $tpl->smarty);
        $tpl->fetch();
        CE\do_action('smarty/after_fetch', $tpl->smarty);

        return self::$tpls[$path] = $tpl;
    }

    public static function call($path, $func, $params = array(), $nocache = true)
    {
        $tpl = self::getTemplate($path);
        CE\do_action('smarty/before_call', $tpl->smarty);
        _CE_PS174P_
            ? $tpl->smarty->ext->_tplFunction->callTemplateFunction($tpl, $func, $params, $nocache)
            : call_user_func("smarty_template_function_$func", $tpl, $params)
        ;
        CE\do_action('smarty/after_call', $tpl->smarty);
    }

    public static function capture($path, $func, $params = array(), $nocache = true)
    {
        ob_start();

        self::call($path, $func, $params, $nocache);

        return ob_get_clean();
    }

    public static function get($path, $buffer)
    {
        $tpl = self::getTemplate($path);

        return _CE_PS174P_
            ? $tpl->smarty->ext->_capture->getBuffer($tpl, $buffer)
            : Smarty::$_smarty_vars['capture'][$buffer]
        ;
    }

    public static function write($path, $buffer)
    {
        $tpl = self::getTemplate($path);

        echo _CE_PS174P_
            ? $tpl->smarty->ext->_capture->getBuffer($tpl, $buffer)
            : Smarty::$_smarty_vars['capture'][$buffer]
        ;
    }

    public static function printf($path, $buffer)
    {
        $args = func_get_args();
        array_shift($args);
        $args[0] = self::get($path, $buffer);

        call_user_func_array(__FUNCTION__, $args);
    }

    public static function sprintf($path, $buffer)
    {
        $args = func_get_args();
        array_shift($args);
        $args[0] = self::get($path, $buffer);

        return call_user_func_array(__FUNCTION__, $args);
    }
}

function ce__($text, $domain = 'creativeelements')
{
    return CE\translate($text, $domain);
}
