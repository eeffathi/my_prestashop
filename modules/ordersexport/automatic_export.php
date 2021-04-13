<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.01.16
 * Time: 15:38
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
//Context::getContext()->controller = 'AdminController';

if( !(int)Configuration::get('PS_SHOP_ENABLE') ){
  if (!in_array(Tools::getRemoteAddr(), explode(',', Configuration::get('PS_MAINTENANCE_IP')))) {
    if( !Configuration::get('PS_MAINTENANCE_IP') ){
      Configuration::updateValue('PS_MAINTENANCE_IP', Tools::getRemoteAddr() );
    }
    else{
      Configuration::updateValue('PS_MAINTENANCE_IP', Configuration::get('PS_MAINTENANCE_IP') . ',' . Tools::getRemoteAddr());
    }
  }
}

include(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/export.php');

try{
  checkConfig();

  if (Tools::getValue('secure_key')) {
    $secureKey = md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME'));

    if ( ( $secureKey === Tools::getValue('secure_key')) || Tools::getValue('secure_key') == Configuration::getGlobalValue('GOMAKOIL_ORDERS_EXPORT_TASKS_KEY') ) {
      if( !Tools::getValue('id_lang') ){
        throw new Exception('id_lang is Empty');
      } else {
        $id_lang = Tools::getValue('id_lang');
      }

      $config_name = Tools::getValue('settings');
      $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', Module::getInstanceByName('ordersexport')->default_shop_group_id, Module::getInstanceByName('ordersexport')->default_shop_id));
      $config = $config[$config_name];
      $config['name_settings'] = $config_name;
      $id_shop = $config['shop_id'];
      $id_shop_group = Shop::getGroupFromShop($id_shop);
      $limit = !empty(Tools::getValue('limit')) ? Tools::getValue('limit') : 0;

      Module::getInstanceByName('ordersexport')->autoExport($config, null, $config_name, $secureKey, $id_shop, $id_lang, $limit);
      echo Module::getInstanceByName('ordersexport')->l('Export Report sent on your email (if you set up it in settings)!','automatic_export');
    } else{
      echo (Module::getInstanceByName('ordersexport')->l('Secure key is wrong','automatic_export'));
      die;
    }
  } else{
    echo (Module::getInstanceByName('ordersexport')->l('Secure key is wrong','automatic_export'));
    die;
  }
} catch( Exception $e ){
  if ($e->getCode() != 777 || ($e->getCode() == 777 && $config['email_on_no_orders'] == 1)) {
    Module::getInstanceByName('ordersexport')->sendEmail($config, $e->getMessage());
  }

  echo '<strong>Error: </strong>' . $e->getMessage();
}

function checkConfig()
{
  $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', Module::getInstanceByName('ordersexport')->default_shop_group_id, Module::getInstanceByName('ordersexport')->default_shop_id));
  if( !isset($config[Tools::getValue('settings')]) ){
    echo Module::getInstanceByName('ordersexport')->l('Export Settings does not exists!', 'automatic_export');
    die;
  }
}