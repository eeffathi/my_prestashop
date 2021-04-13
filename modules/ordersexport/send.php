<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
//Context::getContext()->controller = 'AdminController';
//include(dirname(__FILE__).'/../../init.php');

  if ( Tools::getValue('phpinfo') ){
    phpinfo();
    die;
  }
  
if ( !Tools::getValue('ajax') ){
  header('HTTP/1.0 403 Forbidden');
  echo 'You are forbidden!';  die;
}

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

$default_shop_id = Module::getInstanceByName('ordersexport')->default_shop_id;
$default_shop_group_id = Module::getInstanceByName('ordersexport')->default_shop_group_id;

try {
  $write_fd = fopen(_PS_MODULE_DIR_ . 'ordersexport/error.log', 'w');
  fwrite($write_fd, " ");
  fclose($write_fd);
  ini_set("log_errors", 1);
  ini_set("error_log", _PS_MODULE_DIR_ . "ordersexport/error.log");

  include_once(dirname(__FILE__).'/datamodel.php');
  include_once(dirname(__FILE__).'/export.php');
  require_once(dirname(__FILE__).'/ordersexport.php');
  $ordersexport = new Ordersexport();
  $model = new ordersExportDataModel();
  $json = array();


  if( Tools::getValue('search') !== false){
    $json['customers'] = Module::getInstanceByName('ordersexport')->pageCustomers(Tools::getValue('search'));
  }

  if ( Tools::getValue('save') == true){
    $error_list = array();
    //var_dump(Tools::getAllValues());die;
    $data = array(
      'time_from'     => Tools::getValue('time_from'),
      'time_to'       => Tools::getValue('time_to'),
      'date_period'   => Tools::getValue('date_period'),
      'date_type'   => Tools::getValue('date_type'),
    );
    $field = array();
    $name = array();
    $field_export = array();
    $field_export = Tools::getValue('field_export');


    if($field_export){
      foreach($field_export as $fieldName ){
        $tmpFieldData = explode("-", $fieldName);
        $name[] = str_replace('-'.end($tmpFieldData), '', $fieldName);
        $field[] = end($tmpFieldData);
      }
    }
    else{
      $error_list[] = array('tab' => 'filter_fields', 'field' => false, 'msg' => Module::getInstanceByName('ordersexport')->l('Please select field for export', 'send'));
    }

    if( Tools::getValue('specific_export_file') && !Tools::getValue('file_name') ){
      $error_list[] = array('tab' => 'export', 'field' => 'file_name', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter name for exported file', 'send'));
    }

    if (Tools::getValue('order_status_changed_autoexport') == 1 && !Tools::getValue('order_status_autoexport_filter')) {
      $error_list[] = array('tab' => 'automatic_export', 'field' => 'order_status_autoexport_filter', 'msg' => Module::getInstanceByName('ordersexport')->l('Please choose order status for autoexport of new orders', 'send'));
    }

    if( Tools::getValue('feed_target') == 'ftp' ){
      if( !Tools::getValue('ftp_server') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_server', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP Server', 'send'));
      }

      if( !Tools::getValue('ftp_user') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_user', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP User Name', 'send'));
      }

      if( !Tools::getValue('ftp_password') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_password', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP Password', 'send'));
      }
    }

    if(!$error_list){
      $customers_list = Tools::unserialize(Configuration::get('GOMAKOIL_CUSTOMERS_CHECKED','', $default_shop_group_id, $default_shop_id));

      $config = array(
        'shop_id'              => Tools::getValue('shop_id'),
        'group_list'           => Tools::getValue('group_list'),
        'customers_list'       => $customers_list,
        'status_list'          => Tools::getValue('status_list'),
        'order_status_autoexport_filter' => Tools::getValue('order_status_autoexport_filter'),
        'payment_list'         => Tools::getValue('payment_list'),
        'carrier_list'         => Tools::getValue('carrier_list'),
        'supplier_list'         => Tools::getValue('supplier_list'),
        'manufacturer_list'         => Tools::getValue('manufacturer_list'),
        'format'               => Tools::getValue('format'),
        'orderway'             => Tools::getValue('orderway'),
        'sort'                 => Tools::getValue('sort'),
        'date_format'          => Tools::getValue('date_format'),
        'round_value'          => Tools::getValue('round_value'),
        'display_headers'      => Tools::getValue('display_headers'),
        'isset_invoice'        => Tools::getValue('isset_invoice'),
        'feed_target'          => Tools::getValue('feed_target'),
        'ftp_transfer_protocol'           => Tools::getValue('ftp_transfer_protocol'),
        'ftp_port'           => Tools::getValue('ftp_port'),
        'ftp_server'           => Tools::getValue('ftp_server'),
        'ftp_user'             => Tools::getValue('ftp_user'),
        'ftp_password'         => Tools::getValue('ftp_password'),
        'ftp_folder_path'      => Tools::getValue('ftp_folder_path'),
        'automatic'            => Tools::getValue('automatic'),
        'attach_file_to_mail'  => Tools::getValue('attach_file_to_mail'),
        'order_status_changed_autoexport' => Tools::getValue('order_status_changed_autoexport'),
        'separate' => Tools::getValue('separate'),
        'separator_decimal_points' => Tools::getValue('separator_decimal_points'),
        'delimiter_val'        => Tools::getValue('delimiter_val'),
        'seperatop_val'        => Tools::getValue('seperatop_val'),
        'specific_export_file' => Tools::getValue('specific_export_file'),
        'email_on_no_orders'         => Tools::getValue('email_on_no_orders'),
        'file_name'            => Tools::getValue('file_name'),
        'field'                => $field,
        'data'                 => $data,
        'field_name'           => $name,
        'extra_fields'              => Tools::getValue('extra_fields')
      );

      $config['file_name'] = Module::getInstanceByName('ordersexport')->setFileName($config['specific_export_file'], $config['file_name']);
      $config_save = serialize($config);

      Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS', $config_save, false, $default_shop_group_id, $default_shop_id);
      $export = new exportOrders();

      $exportRes = $export->export($config, $config['shop_id'], Tools::getValue('id_lang'), Tools::getValue('page_limit'));

      if( $exportRes === true ){
        if( Tools::getValue('feed_target') == 'ftp' ){
          $json['success'] = $ordersexport->l('Exported file successfully uploaded on your FTP Server!', 'send');
        } else{
          $json['success'] = $ordersexport->l('Data successfully saved!', 'send');
          if( $config['specific_export_file'] && $config['file_name'] ){
            $json['export_file'] = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/ordersexport/files/'. $config['file_name'] .'.'.Tools::getValue('format');
          } else{
            $json['export_file'] = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/ordersexport/files/export_orders_'.Configuration::get('EXPORT_ORDERS_TIME','', $default_shop_group_id, $default_shop_id).'.'.Tools::getValue('format');
          }
        }
      }
      else{
        $json['page_limit'] = $exportRes;
      }
    }
    else{
      $json['error_list'] = $error_list;
    }
  }

  if ( Tools::getValue('removeSettings') == true){
    $config = array();
    $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', $default_shop_group_id, $default_shop_id));
    $key = Tools::getValue('key');
    Db::getInstance()->delete('exported_order', 'settings="'.trim($key).'"');
    unset($config[trim($key)]);
    $config_save =serialize($config);
    Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS_SETTINGS', $config_save, false, $default_shop_group_id, $default_shop_id);
    $json['success'] = $ordersexport->l('Data successfully saved!', 'send');
  }

  if ( Tools::getValue('returnCount') == true){

    $versionDateId = ConfigurationCore::getIdByName('GOMAKOIL_ORDERS_EXPORT_VERSION', 0 ,0);
    $needUpdate = false;
    if( $versionDateId ){
      $versionConf = new Configuration($versionDateId);
      if(( time()-strtotime($versionConf->date_upd) ) > ( 10*24*3600 ) ){
        $needUpdate = true;
      }
    }

    if( !$versionDateId || $needUpdate ){
      $url = 'https://myprestamodules.com/modules/mpm_newsletters/send.php?get_module_version=true&ajax=true&module=35';

      $res = Tools::file_get_contents($url);

      if( $res ){
        $version = Tools::jsonDecode($res);
        $version = $version->module_version;
        Configuration::updateGlobalValue('GOMAKOIL_ORDERS_EXPORT_VERSION', $version);
      }

      if( $versionDateId ){
        $versionConf->date_upd = date('Y-m-d H:i:s');
        $versionConf->update();
      }
    }

    $ordersCount = Configuration::get('EXPORT_ORDERS_COUNT','', $default_shop_group_id, $default_shop_id);
    $currentExportedOrders = Configuration::get('EXPORT_ORDERS_CURRENT_COUNT','', $default_shop_group_id, $default_shop_id);
    $json['export_notification'] = $ordersexport->l('Successfully exported ' . $currentExportedOrders . ' from ' . $ordersCount . ' orders', 'send');
  }

  if ( Tools::getValue('saveSettings') == true){
    $data = array(
      'time_from'     => Tools::getValue('time_from'),
      'time_to'       => Tools::getValue('time_to'),
      'date_period'   => Tools::getValue('date_period'),
      'date_type'   => Tools::getValue('date_type'),
    );
    $field = array();
    $error_list = array();
    $name = array();
    $field_export = array();
    $field_export = Tools::getValue('field_export');

    if($field_export){
      foreach($field_export as $fieldName ){
        $tmpFieldData = explode("-", $fieldName);
        $name[] = str_replace('-'.end($tmpFieldData), '', $fieldName);
        $field[] = end($tmpFieldData);
      }
    }
    else{
      $error_list[] = array('tab' => 'filter_fields', 'field' => false, 'msg' => Module::getInstanceByName('ordersexport')->l('Please select field for export', 'send'));
    }

    if(!Tools::getValue('save_setting')){
      $error_list[] = array('tab' => 'settings', 'field' => 'save_setting', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter settings name', 'send'));
    }

    if (Tools::getValue('order_status_changed_autoexport') == 1 && !Tools::getValue('order_status_autoexport_filter')) {
      $error_list[] = array('tab' => 'automatic_export', 'field' => 'order_status_autoexport_filter', 'msg' => Module::getInstanceByName('ordersexport')->l('Please choose order status for autoexport of new orders', 'send'));
    }

    if( Tools::getValue('automatic') && !Tools::getValue('notification_emails') ){
//      $error_list[] = array('tab' => 'automatic_export', 'field' => 'notification_emails', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter at least one email for Automatic Order Export Notification', 'send'));
    }

    if( Tools::getValue('specific_export_file') && !Tools::getValue('file_name') ){
      $error_list[] = array('tab' => 'export', 'field' => 'file_name', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter name for exported file', 'send'));
    }

    if( Tools::getValue('feed_target') == 'ftp' ){
      if( !Tools::getValue('ftp_server') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_server', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP Server', 'send'));
      }

      if( !Tools::getValue('ftp_user') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_user', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP User Name', 'send'));
      }

      if( !Tools::getValue('ftp_password') ){
        $error_list[] = array('tab' => 'export', 'field' => 'ftp_password', 'msg' => Module::getInstanceByName('ordersexport')->l('Please enter FTP Password', 'send'));
      }
    }

    if(!$error_list){
      $config = array();
      $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', $default_shop_group_id, $default_shop_id));
      $customers_list = Tools::unserialize(Configuration::get('GOMAKOIL_CUSTOMERS_CHECKED','', $default_shop_group_id, $default_shop_id));
      $config[trim(Tools::getValue('save_setting'))] = array(
        'shop_id'              => Tools::getValue('shop_id'),
        'group_list'           => Tools::getValue('group_list'),
        'customers_list'       => $customers_list,
        'status_list'          => Tools::getValue('status_list'),
        'order_status_autoexport_filter' => Tools::getValue('order_status_autoexport_filter'),
        'payment_list'         => Tools::getValue('payment_list'),
        'carrier_list'         => Tools::getValue('carrier_list'),
        'supplier_list'          => Tools::getValue('supplier_list'),
        'manufacturer_list'          => Tools::getValue('manufacturer_list'),
        'format'               => Tools::getValue('format'),
        'date_format'          => Tools::getValue('date_format'),
        'sort'                 => Tools::getValue('sort'),
        'orderway'             => Tools::getValue('orderway'),
        'round_value'          => Tools::getValue('round_value'),
        'display_headers'      => Tools::getValue('display_headers'),
        'isset_invoice'        => Tools::getValue('isset_invoice'),
        'feed_target'          => Tools::getValue('feed_target'),
        'ftp_transfer_protocol'           => Tools::getValue('ftp_transfer_protocol'),
        'ftp_port'           => Tools::getValue('ftp_port'),
        'ftp_server'           => Tools::getValue('ftp_server'),
        'ftp_user'             => Tools::getValue('ftp_user'),
        'ftp_password'         => Tools::getValue('ftp_password'),
        'ftp_folder_path'      => Tools::getValue('ftp_folder_path'),
        'separate'             => Tools::getValue('separate'),
        'separator_decimal_points'             => Tools::getValue('separator_decimal_points'),
        'delimiter_val'        => Tools::getValue('delimiter_val'),
        'seperatop_val'        => Tools::getValue('seperatop_val'),
        'specific_export_file' => Tools::getValue('specific_export_file'),
        'file_name'            => Tools::getValue('file_name'),
        'automatic'            => Tools::getValue('automatic'),
        'attach_file_to_mail'  => Tools::getValue('attach_file_to_mail'),
        'order_status_changed_autoexport'  => Tools::getValue('order_status_changed_autoexport'),
        'notification_emails'  => Tools::getValue('notification_emails'),
        'not_exported'         => Tools::getValue('not_exported'),
        'email_on_no_orders'         => Tools::getValue('email_on_no_orders'),
        'field'                => $field,
        'data'                 => $data,
        'field_name'           => $name,
        'extra_fields'              => Tools::getValue('extra_fields')
      );

      $config_save =serialize($config);
      Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS_SETTINGS', $config_save, false, $default_shop_group_id, $default_shop_id);

      $automatic = Tools::getValue('automatic');
      $not_exported = Tools::getValue('not_exported');
      if(isset($automatic) && $automatic && isset($not_exported) && $not_exported ){
        Db::getInstance()->delete('exported_order', 'settings="'.trim(Tools::getValue('save_setting')).'"');
      }
      $json['success'] = $ordersexport->l('Data successfully saved!', 'send');
    }
    else{
      $json['error_list'] = $error_list;
    }

  }

  if( Tools::getValue('add_customer') !== false){
    $config = Tools::unserialize(Configuration::get('GOMAKOIL_CUSTOMERS_CHECKED','', $default_shop_group_id, $default_shop_id));
    if( !$config ){
      $config = array();
    }
    if (!in_array( Tools::getValue('id_customer'), $config)) {
      array_push($config, Tools::getValue('id_customer'));
    }
    else{
      $key = array_search(Tools::getValue('id_customer'), $config);
      if ($key !== false)
      {
        unset($config[$key]);
      }
    }
    $customer =serialize($config);
    Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', $customer, false, $default_shop_group_id, $default_shop_id);
  }
  echo Tools::jsonEncode($json);
}
catch( Exception $e ){
  $json['error'] = $e->getMessage();
  echo Tools::jsonEncode($json);
}