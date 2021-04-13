<?php
/**
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*/

if (!defined('_PS_VERSION_'))
	exit;

require_once dirname(__FILE__).'/config/config.inc.php';


class Pqprintshippinglabels extends Module
{
	public $ps_version;
	public $add_shop_sql_restriction;
	public $context;
	public $module_token;
	public $ajax_token;
	public $input_value;
	public $module_path;
	public $lang_iso_codes;
	public $lang_ids;
	public $config;
	public $custom_template_vars;
	const TOKEN = 'pqprintshippinglabels';
	public $id_product;
	public $url;


	public function dev()
	{
		#$this->registerHook('displayOrderConfirmation');
	}

	public static function getToken()
	{
		return Tools::encrypt(self::TOKEN);
	}

	public static function isTokenValid($token)
	{
		return (self::getToken() === $token);
	}

	public function __construct()
	{
		$this->name                   = 'pqprintshippinglabels';
		$this->tab                    = 'shipping_logistics';
		$this->version                = '4.10.10';
		$this->author                 = 'ProQuality';
		$this->module_key             = '029d0db6808779655c118b9717bacea4';
		$this->need_instance          = 0;
		$this->ps_versions_compliancy = array(
			'min' => '1.5',
			'max' => '1.7'
		);
		
		parent::__construct();

		$this->displayName = $this->l('Print Shipping Labels');

		$this->description = $this->l('Automatize the printing of your shipping labels and save time with your shipping process. Just print and stick on packages you want to deliver to your customers.');
		
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		
		$this->ps_version = (Tools::substr(_PS_VERSION_, 0, 5));

		$this->id_lang = (int)$this->context->language->id;

		$this->iso_lang = Language::getIsoById($this->id_lang);
		
		$this->id_product = '16885';		
	
		$this->add_shop_sql_restriction = Shop::addSqlRestriction(false, 'o');
		$this->add_shop_sql_restriction = '';

		#$admin_cookie = new Cookie('psAdmin');

		$this->context->employee = new Employee($this->context->cookie->id_employee);		

		$this->ajax_token = $this->getToken();

		$this->module_token = Tools::getAdminToken( 'AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id);
		
		$this->module_path = $this->_path;

		$this->dev();
	}
	
	
	
	public function install()
	{
		@unlink(_PS_CACHE_DIR_.'class_index.php');

		$sql_array = array();
		$sql_execution = array();

		include(dirname(__FILE__).'/sql/install.php');

		foreach ($sql_array as $sql)
			$sql_execution[] = Db::getInstance()->Execute($sql);

		$languages = Language::getLanguages(false);
		$default_lang = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));

		foreach ($languages as $value) 
		{
			if (file_exists(dirname(__FILE__).'/views/templates/admin/label_templates/en'))
			{
				$count = 1;

				$files = array();
				foreach (glob(dirname(__FILE__).'/views/templates/admin/label_templates/en/*default.tpl') as $file)
					$files[] = basename($file);
				natsort($files);

				$files2 = array();
				foreach ($files as $file)
					$files2[] = dirname(__FILE__).'/views/templates/admin/label_templates/en/'.$file;

				foreach ($files2 as $file)
				{
					$file_contents = @Tools::file_get_contents($file);
					$file_contents = mb_convert_encoding($file_contents, 'UTF-8', 'ISO-8859-1'); 
					$filename      = explode('/en/', $file);
					$filename      = $filename[1];
					$filename      = str_replace('.tpl', '', $filename);
					$template_name = $filename;
					$filename      = explode('-', $filename);
					$template_name = Tools::substr($template_name, Tools::strlen($filename[0].'-'));
					$filename      = $filename[1];
					$sql = 'INSERT INTO `'._DB_PREFIX_.$this->name.'_templates_lang` (`id_template`, `id_lang`, `iso_code`, `name`, `html`) VALUES ('.pSQL($count).', '.pSQL($value['id_lang']).", '".pSQL($value['iso_code'])."', '".pSQL($template_name)."', '".pSQL($file_contents, true)."');";
					Db::getInstance()->Execute($sql);
					$count++;
				}
			}
		}
		
		Configuration::updateValue(Tools::strtoupper($this->name).'_SETTINGS', serialize(array(
			'name' => $this->name,
			'ajax_token' => $this->ajax_token,
			'module_token' => $this->module_token,
		)));
		
		return parent::install()
				&& $this->registerHook('displayBackOfficeHeader')
				&& $this->registerHook('displayBackOfficeTop')
				&& $this->registerHook('displayOrderConfirmation')
				&& (!in_array(false, $sql_execution));
		
	}
	
	public function uninstall()
	{
		$sql_array = array();
		$sql_execution = array();

		include(dirname(__FILE__).'/sql/uninstall.php');
		
		foreach ($sql_array as $sql)
			$sql_execution[] = Db::getInstance()->Execute($sql);

		#$configuration_array = unserialize(Configuration::get(Tools::strtoupper($this->name).'_SETTINGS'));
		
		return parent::uninstall() 
				&& Configuration::deleteByName($this->name);
	}
	
	public function hookOrderConfirmation()
	{
		if (Tools::getValue('id_order') && Tools::getValue('id_cart'))
		{
			$settings_with_autoexport = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings` WHERE export_automatically_on_new_order = "1"');

			foreach($settings_with_autoexport as $key => $value)
			{
				$value['update_status'] = 'no';
				$value['called_from_hook'] = 'yes';
				$value['orders_ids'] = Tools::getValue('id_order');
				$this->generatePDF(false, $value);
			}
			#@header('Content-Type: text/html');
		}
	}


	public function hookBackOfficeHeader($params)
	{
		if ((Tools::isSubmit('configure') && Tools::getValue('configure') == $this->name) || Tools::getValue('controller') == 'AdminOrders')
		{
			$this->assignModuleVars();

			$media = '';

			//add juqery
			$this->context->controller->addJquery();
			
			//load jquery ui plugins
			$this->context->controller->addJqueryUI('ui.tabs');
			$this->context->controller->addJqueryUI('ui.dialog');

			//module media
			//js
			$this->context->controller->addJS($this->_path.'views/js/jquery.mousehold.plugin.js');
			$this->context->controller->addJS($this->_path.'libraries/datatables/datatables.min.js');
			$media  .= '<script type="text/javascript">'.$this->context->smarty->fetch(dirname(__FILE__).'/views/js/assigned_vars.js').'</script>
						<script type="text/javascript">'.$this->context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/translations.js.tpl').'</script>';
			$this->context->controller->addJS($this->_path.'views/js/hashtable.js');
			$this->context->controller->addJS($this->_path.'views/js/jquery.numberformatter-1.2.4.js');
			
			if (_PS_VERSION_ >= '1.7.7' && Tools::getValue('controller') == 'AdminOrders')
				$this->context->controller->addJS($this->_path.'views/js/jquery/jquery-migrate-3.3.2.js');
			
			$this->context->controller->addJS($this->_path.'views/js/back.js');
			$this->context->controller->addJS($this->_path.'views/js/global.functions.js');

			//css
			$this->context->controller->addCSS($this->_path.'views/css/back.css', 'all');
			$this->context->controller->addCSS($this->_path.'views/css/jquery_themes/bootstrap/jquery.ui.theme.css', 'all');
			$this->context->controller->addCSS($this->_path.'libraries/datatables/datatables.min.css', 'all');
			$this->context->controller->addCSS($this->_path.'views/css/fontawesome/font-awesome.min.css', 'all');
			
			//tinymce de la mine
			$this->context->controller->addJS($this->_path.'libraries/tinymce3/tiny_mce_src.js');	
				
			return $media;
		}
		
	}


	public function hookBackOfficeTop()
	{	
		if (Tools::getValue('controller') == 'AdminOrders')
		{		
			$this->assignModuleVars();

			$templates = $this->getTemplates();
			$settings = $this->getSettings();
			$languages = Language::getLanguages(false);
			$order_states = $this->object_to_array(OrderStateCore::getOrderStates($this->id_lang));

			$this->context->smarty->assign(array(
				'checked_orders' => '',
				'orders_ids' => '',
				'settings' => $settings,
				'languages' => $languages,
				'employee_default_language_id' => $this->id_lang,
				'employee_default_language_iso_code' => $this->iso_lang,
				'templates' => $templates,
				'order_states' => $order_states,
			));
			#return $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/popup.tpl');
			return $this->display(dirname(__FILE__), '/views/templates/admin/popup.tpl');
		}
	}
	
	
	public function getContent()
	{
		$output = null;
		
		if (Tools::isSubmit('submit'.$this->name))
		{
			if (!$this->name || empty($this->name) || !Validate::isGenericName($this->name))
				$output .= $this->displayError($this->l('Invalid Configuration value'));
			else
			{
				Configuration::updateValue(Tools::strtoupper($this->name).'_SETTINGS', serialize(array(
					'name' => $this->name,
					'ajax_token' => $this->ajax_token,
					'module_token' => $this->module_token,
				)));
				
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		
		return $output.$this->displayForm();
	}
	
	
	public function displayForm()
	{
		$order_states = $this->object_to_array(OrderStateCore::getOrderStates($this->id_lang));
		$page_types = $this->getPageTypes();
		$settings   = $this->getSettings();
		$templates  = $this->getTemplates();
		$order_fields = $this->getOrderFields();
		$order_products_fields = $this->getProductFields();
		$custom_vars_fields = @$this->getCustomTemplateVars(null, true);
		$languages = Language::getLanguages(false);
		$orders_ids = Tools::getValue('orders_ids');
		$template = $this->getTemplate(array('id_template' => 1));

		if ($orders_ids)
			$orders_ids = Tools::jsonEncode(explode(',', addcslashes(trim($orders_ids, ','), "'")));
		else
			$orders_ids = Tools::jsonEncode(array());
		
		$this->assignModuleVars();
		
		// d(Tools::jsonEncode(explode(trim(addcslashes($orders_ids, "'"), ','), ',')));
		$this->context->smarty->assign(array(
			'order_states' => $order_states,
			'page_types' => $page_types,
			'templates' => $templates,
			'settings' => $settings,
			'order_fields' => $order_fields,
			'order_products_fields' => $order_products_fields,
			'custom_vars_fields' => $custom_vars_fields,
			'orders_ids' => $orders_ids,
			'languages' => $languages,
			'employee_default_language_id' => $this->id_lang,
			'employee_default_language_iso_code' => $this->iso_lang,
			'rss_code' => '',
			'textarea_file' => dirname(__FILE__).'/views/templates/admin/textarea.tpl',
			'iso_tiny_mce' => (file_exists(_PS_JS_DIR_.'tiny_mce/langs/'.$this->context->language->iso_code.'.js') ? $this->context->language->iso_code : 'en'),
			'ad' => dirname($_SERVER['PHP_SELF']),
			'input_value' => $template['html'],
			'media' => '',
		));

		return $this->display(dirname(__FILE__), '/views/templates/admin/index.tpl');
	}
	
	
	public function getOrderFields()
	{
		$result = Db::getInstance()->executeS('SHOW COLUMNS FROM `'._DB_PREFIX_.'orders`');
		
		$fields = array();
		foreach ($result as $value)
			$fields[] = $value['Field'];
		
		return $fields;
	}	

	public function getProductFields()
	{
		$result = Db::getInstance()->executeS('SHOW COLUMNS FROM `'._DB_PREFIX_.'order_detail`');
		
		$fields = array();
		foreach ($result as $value) {
			$fields[] = $value['Field'];
		}
		array_push($fields, 'discounted_price');

		return $fields;
	}	
	
	public function getMysqlTablesByColumnName($column_name)
	{
		$result = Db::getInstance()->executeS('SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ("'.pSQL($column_name).'") AND TABLE_SCHEMA="prestashop16"');
		return $result;
	}
	
	public function getTemplates()
	{
		$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_templates` pt LEFT JOIN '._DB_PREFIX_.$this->name.'_templates_lang ptl ON (pt.id_template = ptl.id_template) WHERE ptl.iso_code = "'.pSQL($this->iso_lang).'"');
		#d($result);
		return $result;
	}

	public function getTemplate($data = '')
	{
		$iso_code = empty($data['iso_code']) ? $this->iso_lang : $data['iso_code'];

		$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_templates` pt 
	    LEFT JOIN '._DB_PREFIX_.$this->name.'_templates_lang ptl ON (pt.id_template = ptl.id_template)
	    WHERE pt.id_template = "'.(int)$data['id_template'].'" AND ptl.iso_code = "'.pSQL($iso_code).'"');

		if ($result) 
			return $result[0];
		else
			return false;
	}	

	public function getOrderProducts($id_order)
	{
		$arr_info = array();
		$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'order_detail` od WHERE od.id_order = "'.(int)$id_order.'"');
		//$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'order_detail` od LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON (od.`product_id` = sa.`id_product`) WHERE od.id_order = "'.(int)$id_order.'"');
		foreach($result as $value)
		{
			$res0 = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'product_lang` WHERE id_product = "'.(int)$value['product_id'].'" AND id_lang = "'.(int)$this->id_lang.'" LIMIT 1');
			$res = Db::getInstance()->executeS('SELECT *  FROM `'._DB_PREFIX_.'category_product` WHERE id_product = "'.(int)$value['product_id'].'"');
			$res2 = Db::getInstance()->executeS('SELECT *  FROM `'._DB_PREFIX_.'category_lang` WHERE id_category = "'.(int)$res[0]['id_category'].'" LIMIT 1');
			$value['product_description'] = $res0[0]['description'];
			$value['product_description_short'] = $res0[0]['description_short'];
			$value['category_name'] = $res2[0]['name'];
			$value['discounted_price'] = $value['product_price'];
			$value['product_price'] = Product::getPriceStatic($value['product_id'], true, false, 6, null, false, false);
			$value['discounted_price'] = Product::getPriceStatic($value['product_id']);
			$value['product_features'] = $this->getProductFeatures($value['product_id']);
			$value['product_attributes'] = $this->getProductAttributes($value['product_id']);
			$value['product_combinations'] = $this->getProductCombinations($value['product_id']);

			$arr_info[] = $value;
		}
		#d($arr_info);
		return $arr_info;
	}	

	public function getCategoriesProducts($id_order)
	{
		$arr_info = array();
		$result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'order_detail` od WHERE od.id_order = "'.(int)$id_order.'"');
		
		foreach($result as $value)
		{
			$res = Db::getInstance()->executeS('SELECT *  FROM `'._DB_PREFIX_.'category_product` WHERE id_product = "'.(int)$value['product_id'].'" ORDER BY position DESC LIMIT 1');
			$res2 = Db::getInstance()->executeS('SELECT *  FROM `'._DB_PREFIX_.'category_lang` WHERE id_category = "'.(int)$res[0]['id_category'].'" LIMIT 1');
			$value['category_name'] = $res2[0]['name'];
			$arr_info[] = $value;
		}

		$arr = array();

		foreach($arr_info as $key => $value)
		{
			//$arr[$value['category_name']][$key] = $value;
			$arr[$value['category_name']][] = $value;
		}

		ksort($arr, SORT_NUMERIC);

		$arr_info = array();
		foreach($arr as $key => $value)
		{
			$arr_info[$key]['products_count'] = count($arr[$key]);
			$arr_info[$key]['products'] = $value;
		}

		return $arr_info;
	}
	
	public function http_or_https()
	{
		return Tools::getProtocol(Tools::usingSecureMode()); 
	}	

	public function assignModuleVars()
	{
		$module_dir                = dirname(__FILE__);
		$module_templates_back_dir = dirname(__FILE__).'/views/templates/admin/';
		$module_templates_front_dir = dirname(__FILE__).'/views/templates/front/';
		$module_js_dir             = dirname(__FILE__).'/views/js/';

		$module_link = 'index.php?controller=AdminModules&configure='.$this->name.'&token='.$this->module_token.'&module_name='.$this->name;

		$doc_iso = file_exists(_PS_MODULE_DIR_.$this->name.'/readme_'.$this->iso_lang.'.pdf') ? $this->iso_lang : 'en';
		$module_url = $this->http_or_https().Tools::getShopDomain().$this->_path;

		$this->context->smarty->assign(array(
			'psl_module_version' => $this->version,
			'psl_dev_modules_link' => 'http://addons.prestashop.com/'.$this->iso_lang.'/93_proquality',
			'psl_support_link' => 'http://addons.prestashop.com/'.$this->iso_lang.'/contact-community.php?id_product='.$this->id_product,
			'psl_doc_link' => '../modules/'.$this->name.'/readme_'.$doc_iso.'.pdf',
			'psl_video_link' => 'https://www.youtube.com/watch?v=KJU2uqQVwQ8',
			'psl_ps_version' => $this->ps_version,
			'psl_id_employee' => $this->context->employee->id,
			'psl_id_cart' => $this->context->cookie->id_cart,
			'psl_module_name' => $this->name,
			'psl_db_prefix' => _DB_PREFIX_,
			'psl_module_path' => $this->_path,
			'psl_module_url' => $module_url,
			'psl_module_dir' => $module_dir,
			'psl_module_token' => $this->module_token,
			'psl_ajax_token' => $this->ajax_token,
			'psl_module_link' => $module_link,
			'psl_module_templates_back_dir' => $module_templates_back_dir,
			'psl_module_templates_front_dir' => $module_templates_front_dir,
			'psl_module_js_dir' => $module_js_dir,
			'psl_current_url' => $this->http_or_https()."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
			'ps_img' => _PS_IMG_,
		));
	}


	
	public function getEmployeesByProfile($id_profile, $active_only = false)
	{
		return EmployeeCore::getEmployeesByProfile(1);
	}
	

	public function deleteSettings($id_setting)
	{
		// nu stergem defaulturile
		if ($this->isDemo() == true)
			return false;
		
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_settings WHERE id_setting = "'.(int)$id_setting.'"';
		Db::getInstance()->Execute($sql);
		#return $this->getPageTypes();
		return true;
	}

	public function getFirstStore()
	{
		return Db::getInstance()->executeS('
		SELECT *, 
		( 
			SELECT `name` 
			FROM  `'._DB_PREFIX_.'country_lang` cl 
			WHERE cl.`id_country` = s.`id_country` LIMIT 1
		) `country_name`, 	
		(
			SELECT `iso_code` 
			FROM  `'._DB_PREFIX_.'country` c 
			WHERE c.`id_country` = s.`id_country`  LIMIT 1
		) `country_code` 
		FROM `'._DB_PREFIX_.'store` s ORDER BY s.`id_store` ASC LIMIT 1');
	}

	
	public function getStoreById($id_store)
	{
		return Db::getInstance()->executeS('
		SELECT *, 
		( 
			SELECT `name` 
			FROM  `'._DB_PREFIX_.'country_lang` cl 
			WHERE cl.`id_country` = s.`id_country` LIMIT 1
		) `country_name`, 	
		(
			SELECT `iso_code` 
			FROM  `'._DB_PREFIX_.'country` c 
			WHERE c.`id_country` = s.`id_country`  LIMIT 1
		) `country_code` 
		FROM `'._DB_PREFIX_.'store` s  
		WHERE s.`id_store` = '.(int)$id_store.'');
	}
	
	
	public function editorSettings($data = '')
	{
		$db = '';
		#d($data);
		include('libraries/datatables/PHP/DataTables.php');

		// Build our Editor instance and process the data coming from _POST
		return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_settings', 'id_setting')->fields(
				//DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.id_setting' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.name' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.id_order_state' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.id_pagetype' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.label_copies' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.labels_horizontally' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.labels_vertically' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.spacing_between_labels_vertically' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.spacing_between_labels_horizontally' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.page_padding_left' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.page_padding_top' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.labels_border' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.rounded_corners_radius' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.one_label_for_each_product' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.export_automatically_on_new_order' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.barcodes_type' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.barcodes_width' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.barcodes_height' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.id_template' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_settings.is_default' )
			)->process($data)->json();
	}

	public function copyr($src, $dst) 
	{
		$dir = opendir($src); 
		
		@mkdir($dst); 
		
		while (false !== ($file = readdir($dir))) 
		{
			if (($file != '.' ) && ( $file != '..')) 
			{
				if (is_dir($src.'/'.$file)) 
					$this->copyr($src.'/'.$file, $dst.'/'.$file); 
				else 
					copy($src.'/'.$file, $dst.'/'.$file); 
			} 
		} 
		closedir($dir);
	}
	
	public function editorTemplate($data = '')
	{
		if ($data['action'] == 'edit')
		{
			$id_template = key($data['data']);
			#d($data);
			$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_templates pt
		    SET width = "'.pSQL($data['data'][$id_template][_DB_PREFIX_.$this->name.'_templates']['width']).'",
		        height = "'.pSQL($data['data'][$id_template][_DB_PREFIX_.$this->name.'_templates']['height']).'",
		        per_page = "0",
		        is_default = "0"
		    WHERE id_template = "'.(int)$id_template.'"';
			Db::getInstance()->Execute($sql);

			$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_templates_lang ptl SET name = "'.pSQL($data['data'][$id_template][_DB_PREFIX_.$this->name.'_templates_lang']['name']).'" WHERE id_template = "'.(int)$id_template.'"';
			Db::getInstance()->Execute($sql);

			$data['data'][$id_template]['DT_RowId'] = 'row_' + $id_template;
		}
		elseif ($data['action'] == 'create')
		{
			#d($data);
			$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_templates 
			(
				width, 
				height, 
				per_page
			) 
			VALUES 
			(
				"'.pSQL($data['data'][0][_DB_PREFIX_.$this->name.'_templates']['width']).'", 
				"'.pSQL($data['data'][0][_DB_PREFIX_.$this->name.'_templates']['height']).'", 
				"0"
			)';
			
			Db::getInstance()->Execute($sql);
			
			$id_template = $this->getLastTemplateId();

			$languages = Language::getLanguages(false);
		
			foreach ($languages as $value) 
			{
				$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_templates_lang 
				(
					id_template, 
					id_lang, 
					iso_code,
					name,
					html
				) 
				VALUES 
				(
					"'.pSQL($id_template).'", 
					"'.pSQL(Language::getIdByIso($value['iso_code'])).'", 
					"'.pSQL($value['iso_code']).'",
					"'.pSQL($data['data'][0][_DB_PREFIX_.$this->name.'_templates_lang']['name']).'",
					"'.pSQL($data['data'][0][_DB_PREFIX_.$this->name.'_templates_lang']['html'], true).'"
				)';
				
				Db::getInstance()->Execute($sql);
			}
			$data['data'][0][_DB_PREFIX_.$this->name.'_templates']['id_template'] = $id_template;
		}
		
		unset($data['action']);

		return $data;
	}

	public function editorPageType($data = '')
	{
		$db = '';

		include('libraries/datatables/PHP/DataTables.php');	

		// Build our Editor instance and process the data coming from _POST
		return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_pagetypes', 'id_pagetype')->fields(
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_pagetypes.name' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_pagetypes.width' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_pagetypes.height' )->validator( 'Validate::notEmpty' )->validator( 'Validate::numeric' )
			)->process($data)->json();
	}	



	public function fillGridDataTables($data = '')
	{
		$db = '';

		include('libraries/datatables/PHP/DataTables.php');

		// Build our Editor instance and process the data coming from _POST
		return DataTables\Editor::inst( $db, _DB_PREFIX_.'orders', 'id_order')->fields(
				DataTables\Editor\Field::inst( _DB_PREFIX_.'orders.id_order' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'orders.total_paid' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'order_state_lang.name' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'carrier.name' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.firstname' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.lastname' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'orders.reference' )->validator( 'Validate::notEmpty' ),
				DataTables\Editor\Field::inst( _DB_PREFIX_.'orders.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 )
			)->leftJoin( _DB_PREFIX_.'customer', _DB_PREFIX_.'customer.id_customer', '=', _DB_PREFIX_.'orders.id_customer' )->leftJoin( _DB_PREFIX_.'order_state_lang', _DB_PREFIX_.'order_state_lang.id_order_state', '=', _DB_PREFIX_.'orders.current_state' )->leftJoin( _DB_PREFIX_.'carrier', _DB_PREFIX_.'carrier.id_carrier', '=', _DB_PREFIX_.'orders.id_carrier' )->where( _DB_PREFIX_.'order_state_lang.id_lang', (int)$this->id_lang )->process( $data )->json();

	}
	
	
	public function getSelectedOrders($selected)
	{
		return Tools::jsonEncode($selected);
	}
	

	public function getSettings($orders_states = '')
	{
		return Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings`');
	}
	

	public function getSetting($id_setting, $iso_code = '')
	{
		$iso_code = empty($iso_code) ? Language::getIsoById($this->id_lang) : $iso_code;

		$result = Db::getInstance()->executeS('
		SELECT *, 
		(
			SELECT `width` 
			FROM  `'._DB_PREFIX_.$this->name.'_templates` pt 
			WHERE ps.`id_template` = pt.`id_template` 
		) `template_width`, 
		( 
			SELECT `height` 
			FROM  `'._DB_PREFIX_.$this->name.'_templates` pt 
			WHERE ps.`id_template` = pt.`id_template` 
		) `template_height`,
		( 
			SELECT `html` 
			FROM  `'._DB_PREFIX_.$this->name.'_templates_lang` ptl 
			WHERE ps.`id_template` = ptl.`id_template` AND ptl.iso_code = "'.pSQL($iso_code).'" 
		) `template_html`,
		ps.name AS name, ps.is_default AS is_default 
		FROM `'._DB_PREFIX_.$this->name.'_settings` ps
		LEFT JOIN `'._DB_PREFIX_.$this->name.'_pagetypes` pp ON (ps.`id_pagetype` = pp.`id_pagetype`) 
		WHERE ps.id_setting = "'.(int)$id_setting.'"');

		return $result;
	}
	
	public function getPageTypes()
	{
		return Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_pagetypes`');
	}
	
	public function getPageType($id_pagetype)
	{
		$pagetype = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_pagetypes` WHERE id_pagetype = "'.(int)$id_pagetype.'"');
		return $pagetype[0];
	}
	
	public function makeDefaultSetting($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_settings ps SET ps.is_default = "0"';
		Db::getInstance()->Execute($sql);
	
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_settings ps SET ps.is_default = "1" WHERE ps.id_setting = "'.pSQL($data['id_setting']).'"';
		Db::getInstance()->Execute($sql);

		return true;
	}

	public function updateTemplate($data = '')
	{
		if ($this->isDemo() == true)
			return 'ERROR_DEMO_MODE';
		
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_templates_lang ptl
	    SET html = "'.pSQL($data['html'], true).'"
	    WHERE id_template = "'.(int)$data['id_template'].'" AND iso_code = "'.pSQL($data['iso_code']).'"';
		
		Db::getInstance()->Execute($sql);
		
		return true;
		
	}



	public function deletePageType($id_pagetype)
	{
		// nu stergem defaulturile
		if ($this->isDemo() == true)
			return false;
		
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_pagetypes WHERE id_pagetype = "'.(int)$id_pagetype.'"';
		Db::getInstance()->Execute($sql);
		
		return true;
	}	


	public function getLastTemplateId()
	{
		$sql = 'SELECT id_template FROM '._DB_PREFIX_.$this->name.'_templates ORDER BY id_template DESC LIMIT 1';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

		if ($result)
			return $result[0]['id_template'];
		else
			return 0;
	}	

	public function isDemo()
	{
		$is_demo = (stristr($_SERVER['SERVER_NAME'], 'prestashopaddonsmodules.com') || stristr($_SERVER['SERVER_NAME'], '4prestashop.com')) ? true : false;
		
		return $is_demo;
	}
	
	public function deleteTemplate($id_template)
	{
		if ($this->isDemo() == true)
			return false;
		
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_templates WHERE id_template = "'.(int)$id_template.'"';
		Db::getInstance()->Execute($sql);
		
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_templates_lang WHERE id_template = "'.(int)$id_template.'"';
		Db::getInstance()->Execute($sql);

		return true;
	}

	
	public function showPageTypesDropdown()
	{
		$page_types                = $this->getPageTypes();
		
		$this->context->smarty->assign(array(
			'page_types' => $page_types,
		));
		
		return $this->display(dirname(__FILE__), '/views/templates/admin/ajax.pagetypes.tpl');
		
	}
	
	public function showTemplatesDropdown()
	{
		$templates                 = $this->getTemplates();
		
		$this->context->smarty->assign(array(
			'templates' => $templates,
		));
		
		return $this->display(dirname(__FILE__), '/views/templates/admin/ajax.templates.tpl');
		
	}

	public function countCustomerOrders($id_customer)
	{
		$result = Db::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.'orders WHERE id_customer = "'.$id_customer.'"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	public function showSettingsDropdown()
	{
		$settings      = $this->getSettings();
		
		$this->context->smarty->assign(array(
			'settings' => $settings,
		));
		
		return $this->display(dirname(__FILE__), '/views/templates/admin/ajax.settings.tpl');
	}
	
	public function showSettings2Dropdown($orders_ids = '')
	{
		if (!empty($orders_ids))
		{
			$ids_orders_states = '';
			foreach($orders_ids as $value)
			{
				$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT current_state FROM '._DB_PREFIX_.'orders WHERE id_order = "'.pSQL($value).'"');
				$ids_orders_states .= $res[0]['current_state'].',';
			}
			$ids_orders_states = Tools::substr($ids_orders_states, 0, -1);
		}
		else
			$ids_orders_states = '';

		$settings      = $this->getSettings($ids_orders_states);

		$this->context->smarty->assign(array(
			'settings' => $settings,
		));

		return $this->display(dirname(__FILE__), '/views/templates/admin/ajax.settings2.tpl');
	}

	public function removeHtmlComments($content = '') 
	{		
		preg_match_all('/<!--(.|\s)*?-->/', $content, $matches);

		foreach ($matches[0] as $key => $value)
		{
			if (strpos($value, 'foreach') !== false) 
				$content = str_replace($value, substr($value,4,-3), $content);
		}

		return preg_replace('/<!--(.|\s)*?-->/', '', $content);
	}
	
	
	public function printPreview()
	{
		#$this->assignTemplateVars(true);
		return $this->display(dirname(__FILE__), '/views/templates/admin/print_preview.tpl');
	}
	
	
	/*
	config['width']
	config['height']
	config['orientation']
	config['font_type']
	config['font_size']
	$config['labels_horizontally'] 
	$config['labels_vertically'] 
	$config['barcodes_type']
	$config['barcodes_width']
	$config['barcodes_height']
	$config['spacing_between_labels'] vert, horiz
	$config['page_padding_left']
	$config['page_padding_top']
	$config['labels_border']
	$config['rounded_corners_radius']
	$config['one_label_for_each_product']
	$config['export_automatically_on_new_order']
	$config['hidden_labels']
	$config['label_preview']
	*/
	public function generatePDF($preview = true, $config = '')
	{
		if (!isset($this->context->smarty->registered_plugins['function']['barcode'])) 
			$this->context->smarty->registerPlugin('function', 'barcode', 'smartyFunctionBarcode');

		if (!class_exists('TCPDF'))
			require_once('libraries/tcpdf/tcpdf.php');

		require_once('libraries/phpquery/phpquery.php');

		$this->assignModuleVars();

		// remove last comma if exists
		if (isset($config['orders_ids']) && Tools::substr($config['orders_ids'], -1) == ',')
			$config['orders_ids'] = Tools::substr($config['orders_ids'], 0, -1);
		
		// set the width
		if (empty($config['width']) || empty($config['height']))
		{
			$pagetype = $this->getPageType($config['id_pagetype']);
			$width = $pagetype['width'];
			$height = $pagetype['height'];
		}
		else
		{
			$width  = $config['width'];
			$height = $config['height'];
		}

		// set template
		if (empty($config['template_content']))
		{
			$template = $this->getTemplate($config['id_template']);
			$template_width = $template['width'];
			$template_height = $template['height'];
			$template_content = $template['html'];
		}
		else
		{
			$template_content = $config['template_content'];
			$template_width = $config['template_width'];
			$template_height = $config['template_height'];
		}
		#echo "<pre>"; echo($template_content); exit;

		if ($width <= $height) $orientation = 'P';
		else $orientation = 'L';
		
		$spacing_between_labels_vertically   = (!isset($config['spacing_between_labels_vertically']) ? '1' : $config['spacing_between_labels_vertically']);
		$spacing_between_labels_horizontally = (!isset($config['spacing_between_labels_horizontally']) ? '1' : $config['spacing_between_labels_horizontally']);
		$page_padding_left                   = (!isset($config['page_padding_left']) ? '0' : $config['page_padding_left']);
		$page_padding_top                    = (!isset($config['page_padding_top']) ? '0' : $config['page_padding_top']);
		$labels_border                       = (!isset($config['labels_border']) ? '1' : $config['labels_border']);
		$rounded_corners_radius              = (!isset($config['rounded_corners_radius']) ? '3' : $config['rounded_corners_radius']);
		$one_label_for_each_product          = (!isset($config['one_label_for_each_product']) ? '0' : $config['one_label_for_each_product']);
		$export_automatically_on_new_order   = (!isset($config['export_automatically_on_new_order']) ? '0' : $config['export_automatically_on_new_order']);
		$called_from_hook                    = (!isset($config['called_from_hook']) ? 'no' : $config['called_from_hook']);

		$hidden_labels = array();
		if (!empty($config['hidden_labels']))
		{
			$config['hidden_labels'] = Tools::substr($config['hidden_labels'], 0, -1);
			$ex = explode(',', $config['hidden_labels']);

			foreach ($ex as $key => $value)
				$hidden_labels[] = $value;
		}
		else
			$hidden_labels = array();
		
		//d( $hidden_labels );
		#$pdf->addFormat("custom", $width, $height);  
		#$pdf->reFormat("custom", $orientation);  
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		#$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// scoatem headerul si footerul
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set margins
		#$pdf->SetMargins("-5", "-5", "-5");
		$pdf->SetMargins(0, 0, 0);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->setCellMargins(0, 0, 0, 0);
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		#$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		#$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(true, 0);
		//$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
		//$pdf->SetAutoPageBreak(false, 0);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setJPEGQuality(75);
		
		// set font
		/*$core_fonts = array(
			'courier',
			'courierB',
			'courierI',
			'courierBI',
			'helvetica',
			'helveticaB',
			'helveticaI',
			'helveticaBI',
			'times',
			'timesB',
			'timesI',
			'timesBI',
			'symbol',
			'zapfdingbats'
		);*/
		
		
		$pdf->SetFont('freeserif'); // pentru aproape toate caracterele
		//$pdf->SetFont('kozminproregular'); // pentru caracterele asiatice
		//$pdf->SetFont('stsongstdlight'); // pentru caracterele asiatice 22
		//$pdf->SetFont('helvetica', '', 11);
		//$pdf->SetFont($config['font_type'], '', $config['font_size']);
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		//$pdf->SetFontSize(10);

		if ($preview == true)
		{
			if (empty($config['orders_ids']))
				$where_cond = 'WHERE 1';
			else
				$where_cond = 'WHERE 1 AND o.id_order IN ('.pSQL($config['orders_ids']).')';

			$limit_cond = 'LIMIT 1';
		}
		else
		{
			$where_cond = 'WHERE 1 AND o.id_order IN ('.pSQL($config['orders_ids']).')';
			$limit_cond = '';
		}
		
			$sql = '
			SELECT *, 
			(
				SELECT osl.name FROM '._DB_PREFIX_.'order_state_lang osl
				WHERE osl.id_order_state = o.current_state AND osl.id_lang = "'.(int)$this->id_lang.'" 
				LIMIT 1
	        ) `state_name`, 
			(
				SELECT SUM(product_weight * product_quantity) 
				FROM `'._DB_PREFIX_.'order_detail` od 
				WHERE od.`id_order` = o.`id_order` 
				LIMIT 1
			)  `total_weight`, 
			(
				SELECT `name` 
				FROM  `'._DB_PREFIX_.'carrier` c 
				WHERE c.`id_carrier` = o.`id_carrier` 
				LIMIT 1
			) `carrier`, 
			(
				SELECT `tracking_number` 
				FROM  `'._DB_PREFIX_.'order_carrier` oc 
				WHERE oc.`id_order` = o.`id_order` 
				LIMIT 1
			) `tracking_number`, 
			(
				SELECT `message` 
				FROM  `'._DB_PREFIX_.'customer_thread` ct 
				LEFT JOIN `'._DB_PREFIX_.'customer_message` cm ON (ct.id_customer_thread = cm.id_customer_thread) 
				WHERE ct.`id_order` = o.`id_order` 
				ORDER BY ct.id_customer_thread DESC LIMIT 1
			) `message`, 
			(
				SELECT SUM(product_quantity) 
				FROM  `'._DB_PREFIX_.'order_detail` od 
				WHERE o.`id_order` = od.`id_order` 
				LIMIT 1
			) `items`, 
			o.date_add AS date_add
		    FROM '._DB_PREFIX_.'orders o
		    LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer = o.id_customer) 
		    '.pSQL($where_cond).' 
		    '.pSQL($this->add_shop_sql_restriction).' 
		    ORDER BY o.date_add DESC 
		    '.pSQL($limit_cond).' ';
		
		$result = Db::getInstance()->executeS($sql);
		
		$template_content1 = array();
		$template_content2 = array();
		#echo "<pre>";print_r($result); exit;

		$counter = 0;
		foreach ($result as $key => $value) //loop trough orders
		{
			$order_products = $this->getOrderProducts($value['id_order']);
			$categories_products = $this->getCategoriesProducts($value['id_order']);

			if (empty($config['one_label_for_each_product']))
				$labels = [0];
			else
				$labels = $order_products;

			
			foreach ($labels as $value_product)
			{
				#d($labels);
				// in case of one label per each product
				$this->context->smarty->assign(array('product' => $value_product));
				$this->context->smarty->assign(array('product_features' => $this->getProductFeatures($value_product['product_id'])));
				$this->context->smarty->assign(array('product_attributes' => $this->getProductAttributes($value_product['product_id'])));
				$this->context->smarty->assign(array('product_combinations' => $this->getProductCombinations($value_product['product_id'])));
				
				// other case
				$this->context->smarty->assign(array('order_products' => $order_products));
				$this->context->smarty->assign(array('categories_products' => $categories_products));

				$custom_template_vars = $this->getCustomTemplateVars($value);

				//fac assign in template la custom_template_vars
				foreach ($custom_template_vars as $key2 => $value2)
				{
					$k = Tools::substr($key2, 2, -1);
					$this->context->smarty->assign($k, $value2);
				}
				
				//assign the config
				$template_content1[$counter] = $this->removeHtmlComments($template_content); 
				$template_content2[$counter] = $this->context->smarty->fetch('string:'.$template_content1[$counter]);	
				#echo "<pre>"; print_r($template_content2[$counter]); exit;
				$counter++;
			} // end foreach

		} // end foreach

		//////////////////// aici fac o copie in functie de label_copies ////////////////////////////////////////
		$template_content3 = array();
		foreach ($template_content2 as $key => $value)
		{
			for ($i = 0; $i < $config['label_copies']; $i++)
				$template_content3[] = $value;
		} // end foreach
		$template_content2 = $template_content3;
		////////////////////////////////////////////////////////////////////////////////////////////////////

		#d($template_content2);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if ($preview == true)
			$nl = $config['labels_horizontally'] * $config['labels_vertically'];
		else
			$nl = $config['labels_horizontally'] * $config['labels_vertically'] * $config['label_copies'];

		//daca e preview, umplem toata pagina
		if ($preview == true)
		{
			$template_content_preview = $template_content2[0];
			$template_content2 = array();
			$template_content2[0] = $template_content_preview;
			
			for ($i = 0; $i < $nl; $i++)
				$template_content2[$i] = $template_content_preview;
		}
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//adaugam hidden labels in template content
		$template_content3 = array();
		//d($nl);
		if (count($template_content2) < $nl)
		{
			for ($i = 0; $i < $nl; $i++)
			{
				if (in_array($i + 1, $hidden_labels))
				{
					//array_unshift($template_content2, "");
					$template_content3 = $this->array_insert($template_content2, '', $i);
				}
			}
		}
		else
		{
			foreach ($template_content2 as $k => $v)
			{	
				$v = $v;
				if (in_array($k + 1, $hidden_labels))
				{
					//array_unshift($template_content2, "");
					$template_content3 = $this->array_insert($template_content2, '', $k);
				}
			}
		}

		if (!empty($template_content3))
			$template_content2 = $template_content3;
		#d($template_content2);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		$divs_html = $divs_attrs = $divs_attrs2 = array();
	
		foreach ($template_content2 as $k => $v)
		{
			$doc = phpQuery::newDocument($template_content2[$k]);
			
			foreach ($doc["div[style*='position: absolute']"] as $div)
			{
				$divs_attrs[$k][] = pq($div)->attr('style');
				$divs_html[$k][]  = pq($div)->html();
			}
		}
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		foreach ($divs_attrs as $key => $value)
		{
			//$divs_attrs2[$key]['html'] = ''; 
			foreach ($divs_attrs[$key] as $key2 => $value2)
			{
				preg_match_all('/(width:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
				{
					$divs_attrs2[$key][$key2]['width'] = trim($patterns[2][0]);
					
					if (Tools::substr($divs_attrs2[$key][$key2]['width'], -1) == '%')
					{
						//avem procent la width si convertim in pixeli in functie de label width care e
						$divs_attrs2[$key][$key2]['width'] = round((Tools::substr($divs_attrs2[$key][$key2]['width'], 0, -1) / 100) * $template_width, 2);
						$divs_attrs2[$key][$key2]['width'] .= 'mm';
						//d($divs_attrs2[$key][$key2]['width']);
					}
				}
				else
					$divs_attrs2[$key][$key2]['width'] = '0px';
				
				
				preg_match_all('/(height:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
					$divs_attrs2[$key][$key2]['height'] = trim($patterns[2][0]);
				else
					$divs_attrs2[$key][$key2]['height'] = '0px';
				
				
				preg_match_all('/(left:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
					$divs_attrs2[$key][$key2]['left'] = trim($patterns[2][0]);
				else
					$divs_attrs2[$key][$key2]['left'] = '0px';
				
				
				preg_match_all('/(top:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
					$divs_attrs2[$key][$key2]['top'] = trim($patterns[2][0]);
				else
					$divs_attrs2[$key][$key2]['top'] = '0px';
				
				preg_match_all('/(text-align:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
					$divs_attrs2[$key][$key2]['text-align'] = trim($patterns[2][0]);
				else
					$divs_attrs2[$key][$key2]['text-align'] = 'left';

				preg_match_all('/(rotate:)(.*)(;)/sU', $value2, $patterns);
				#d($patterns);
				#$divs_attrs2[$key]['units'] = Tools::substr(trim($patterns[2][0]), -2);
				if (!empty($patterns[2]))
					$divs_attrs2[$key][$key2]['rotate'] = trim($patterns[2][0]);

				$divs_attrs2[$key][$key2]['attr'] = $value2;
				$divs_attrs2[$key][$key2]['html'] = $divs_html[$key][$key2];
				
			}
		}
		
		$divs = $divs_attrs2;
		#d($divs);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		#d($template_content2);
		$pages = ceil(count($template_content2) / ($config['labels_horizontally'] * $config['labels_vertically']));

		#$hidden_cells = array(1, 2);
		
		$cell_count = 0;
		for ($k = 1; $k <= $pages; $k++)
		{
			// adding a page
			$pdf->AddPage($orientation, array($width, $height));
			
	
			$y = $page_padding_top;
			for ($i = 1; $i <= $config['labels_vertically']; $i++)
			{
				$x = $page_padding_left;
				for ($j = 1; $j <= $config['labels_horizontally']; $j++)
				{
					if (!empty($divs[$cell_count]))
					{
						if ($divs[$cell_count]) 
						{
							foreach ($divs[$cell_count] as $key => $value)
							{
								if (!empty($value['rotate']))
								{
									$pdf->StartTransform();
									$pdf->Rotate($value['rotate'], 10, 10); // (angle, pixels-x, pixels-y)
								}
								$pdf->MultiCell(
									(Tools::substr($value['width'], -2) == 'px') ? $pdf->pixelsToUnits(Tools::substr($value['width'], 0, -2)) : Tools::substr($value['width'], 0, -2), 
									$pdf->pixelsToUnits(Tools::substr($value['height'], 0, -2)), 
									$pdf->unhtmlentities($value['html']), 
									0, 
									($value['text-align'] == 'left') ? 'L' : 'R',//'L', 
									0, 
									0, 
									$pdf->pixelsToUnits(Tools::substr($value['left'], 0, -2)) + $x, 
									$pdf->pixelsToUnits(Tools::substr($value['top'], 0, -2)) + $y, 
									true, 
									0, 
									true
								);
								if (!empty($value['rotate']))
									$pdf->StopTransform();
								
							}
						}
						
						//generate the surrounding border
						if (!empty($labels_border))
						{
							$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(204, 204, 204)));
							$pdf->RoundedRect($x, $y, $template_width, $template_height, $rounded_corners_radius, '1111'); // 2mm in stanga si 2mm in sus
							$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
						}
					}
					$x += $template_width + $spacing_between_labels_horizontally;
					$cell_count++;
				}
				$y += $template_height + $spacing_between_labels_vertically;
			}

			if (ob_get_level()) ob_end_clean();
			#echo $cell_count.',';
			// reset pointer to the last page
			$pdf->lastPage();
			
		} #end foreach	

		//detele old pdf
		//@unlink( dirname(__FILE__).'/pdfs/shipping-labels.pdf' );
		
		if (!empty($export_automatically_on_new_order) && $called_from_hook == 'yes')
		{
			$new_filename = 'auto-'.$config['orders_ids'].'-'.time().'.pdf';
			#$output = $pdf->Output(dirname(__FILE__).'/pdfs/autoexported/'.$new_filename, 'S');
			#file_put_contents(dirname(__FILE__).'/pdfs/autoexported/'.$new_filename, $output);
			$pdf->Output(dirname(__FILE__).'/pdfs/autoexported/'.$new_filename, 'F');
		}
		else
		{
			foreach (glob(dirname(__FILE__).'/pdfs/*.pdf') as $filename) {
				@unlink($filename); //echo "$filename size ".filesize($filename)."\n";
			}
			$new_filename = 'shipping-labels-'.time().'.pdf';
			$pdf->Output(dirname(__FILE__).'/pdfs/'.$new_filename, 'F');
			return $new_filename;
		}		
	}



	
	public function getAddressById($id_address)
	{
		return Db::getInstance()->executeS('
		SELECT *, 
			(
				SELECT `name` 
				FROM  `'._DB_PREFIX_.'state` s 
				WHERE a.`id_state` = s.`id_state` 
	       ) `state`,
	       ( 
				SELECT `name` 
				FROM  `'._DB_PREFIX_.'country_lang` cl 
				WHERE a.`id_country` = cl.`id_country` GROUP BY cl.`id_country`
	       ) `country`,
	       ( 
				SELECT `iso_code` 
				FROM  `'._DB_PREFIX_.'country` c 
				WHERE a.`id_country` = c.`id_country` GROUP BY c.`id_country`
	       ) `iso_code`   
		FROM `'._DB_PREFIX_.'address` a 
		WHERE a.id_address = '.(int)$id_address.'');
	}
	
	
	
	public function to_mm($val)
	{
		$u = 3.779528;
		
		if (Tools::substr($val, -2) == 'px')
			$res = round(Tools::substr($val, 0, -2) / $u);
		elseif (Tools::substr($val, -2) == 'mm')
			$res = round(Tools::substr($val, 0, -2));
		
		
		return $res;
	}

	public function getCustomTemplateVars($value, $only_keys = false)
	{
		if ($value)
		{
			$store = $this->getFirstStore();
			if ($store)
				$store = $store[0];

			$order_date_add = explode(' ', $value['date_add']);
			$order_date = $order_date_add[0];

			$invoice_address          = $this->getAddressById($value['id_address_invoice']);
			$value['invoice_address'] = $invoice_address[0];
			
			$delivery_address          = $this->getAddressById($value['id_address_delivery']);
			$value['delivery_address'] = $delivery_address[0];
			
			$order_messages = Db::getInstance()->executeS('SELECT `message` FROM  `'._DB_PREFIX_.'message` m WHERE m.`id_order` = "'.pSQL($value['id_order']).'"');
			$value['order_messages'] = $order_messages;

			$this->context->smarty->assign(array('order' => $value));
		}
		$vars = array(
			//modify custom fields
			'{$ps_img}'                         => _PS_IMG_,
			'{$total_paid}'                     => $value['total_paid'],
			'{$tracking_number}'                => $value['tracking_number'],
			'{$state_name}'                		=> $value['state_name'],
			'{$message}'                		=> $value['message'],
			'{$order_date}'                     => $order_date,
			'{$order_number}'                   => $value['reference'],
			'{$order_messages}'                 => !empty($value['order_messages']) ? $value['order_messages'] : '', //array
			'{$order_payment}'                  => !empty($value['payment']) ? $value['payment'] : '',
			'{$order_module}'                   => !empty($value['module']) ? $value['module'] : '',
			'{$id_order}'                       => $value['id_order'],
			'{$total_weight}'                   => round($value['total_weight'], 3),
			'{$delivery_date}'                  => $value['delivery_date'], //date('Y-m-d', time()),
			'{$carrier}'                        => !empty($value['carrier']) ? $value['carrier'] : '',
			'{$items}'                          => !empty($value['items']) ? $value['items'] : '',
			'{$company_country}'                => !empty($store['country_name']) ? $store['country_name'] : '',
			'{$company_country_code}'           => !empty($store['country_code']) ? $store['country_code'] : '',
			'{$company_name}'                   => !empty($store['name']) ? $store['name'] : '',
			'{$company_address}'                => @$store['address1'].', '.@$store['postcode'].', '.@$store['city'],
			'{$company_employee_name}'          => $this->context->employee->firstname.' '.$this->context->employee->lastname,
			'{$company_phone}'                  => !empty($store['phone']) ? $store['phone'] : '',
			'{$company_email}'                  => !empty($store['email']) ? $store['email'] : '',
			'{$return_address}'                 => @$store['name'].', '.@$store['address1'].', '.@$store['postcode'].', '.@$store['city'].', '.@$store['phone'].', '.@$store['email'],
			'{$invoice_customer_company_name}'  => !empty($value['invoice_address']['company']) ? $value['invoice_address']['company'] : '',
			'{$invoice_customer_name}'          => $value['invoice_address']['firstname'].' '.$value['invoice_address']['lastname'],
			'{$invoice_customer_firstname}'     => !empty($value['invoice_address']['firstname']) ? $value['invoice_address']['firstname'] : '',
			'{$invoice_customer_lastname}'      => !empty($value['invoice_address']['lastname']) ? $value['invoice_address']['lastname'] : '',
			'{$invoice_customer_dni}'           => !empty($value['invoice_address']['dni']) ? $value['invoice_address']['dni'] : '',
			'{$invoice_customer_address}'       => $value['invoice_address']['address1'].', '.$value['invoice_address']['postcode'].', '.$value['invoice_address']['state'].', '.$value['invoice_address']['city'],
			'{$invoice_customer_address1}'      => !empty($value['invoice_address']['address1']) ? $value['invoice_address']['address1'] : '',
			'{$invoice_customer_address2}'      => !empty($value['invoice_address']['address2']) ? $value['invoice_address']['address2'] : '',
			'{$invoice_customer_postcode}'      => !empty($value['invoice_address']['postcode']) ? $value['invoice_address']['postcode'] : '',
			'{$invoice_customer_country}'       => !empty($value['invoice_address']['country']) ? $value['invoice_address']['country'] : '',
			'{$invoice_customer_country_code}'  => !empty($value['invoice_address']['iso_code']) ? $value['invoice_address']['iso_code'] : '',
			'{$invoice_customer_state}'         => !empty($value['invoice_address']['state']) ? $value['invoice_address']['state'] : '',
			'{$invoice_customer_city}'          => !empty($value['invoice_address']['city']) ? $value['invoice_address']['city'] : '',
			'{$invoice_customer_phone}'         => !empty($value['invoice_address']['phone']) ? $value['invoice_address']['phone'] : '',
			'{$invoice_customer_phone_mobile}'  => !empty($value['invoice_address']['phone_mobile']) ? $value['invoice_address']['phone_mobile'] : '',
			'{$invoice_customer_other}' 		=> !empty($value['invoice_address']['other']) ? $value['invoice_address']['other'] : '',
			'{$invoice_customer_email}'         => !empty($value['email']) ? $value['email'] : '',
			'{$invoice_number}'                 => !empty($value['invoice_number']) ? $value['invoice_number'] : '',
			'{$delivery_customer_company_name}' => !empty($value['delivery_address']['company']) ? $value['delivery_address']['company'] : '',
			'{$delivery_customer_name}'         => $value['delivery_address']['firstname'].' '.$value['delivery_address']['lastname'],
			'{$delivery_customer_firstname}'    => !empty($value['delivery_address']['firstname']) ? $value['delivery_address']['firstname'] : '',
			'{$delivery_customer_lastname}'     => !empty($value['delivery_address']['lastname']) ? $value['delivery_address']['lastname'] : '',
			'{$delivery_customer_dni}'          => !empty($value['delivery_address']['dni']) ? $value['delivery_address']['dni'] : '',
			'{$delivery_customer_address}'      => $value['delivery_address']['address1'].', '.$value['delivery_address']['postcode'].', '.$value['delivery_address']['state'].', '.$value['delivery_address']['city'],
			'{$delivery_customer_address1}'     => !empty($value['delivery_address']['address1']) ? $value['delivery_address']['address1'] : '',
			'{$delivery_customer_address2}'     => !empty($value['delivery_address']['address2']) ? $value['delivery_address']['address2'] : '',
			'{$delivery_customer_postcode}'     => !empty($value['delivery_address']['postcode']) ? $value['delivery_address']['postcode'] : '',
			'{$delivery_customer_country}'      => !empty($value['delivery_address']['country']) ? $value['delivery_address']['country'] : '',
			'{$delivery_customer_country_code}' => !empty($value['delivery_address']['iso_code']) ? $value['delivery_address']['iso_code'] : '',
			'{$delivery_customer_state}'        => !empty($value['delivery_address']['state']) ? $value['delivery_address']['state'] : '',
			'{$delivery_customer_city}'         => !empty($value['delivery_address']['city']) ? $value['delivery_address']['city'] : '',
			'{$delivery_customer_phone}'        => !empty($value['delivery_address']['phone']) ? $value['delivery_address']['phone'] : '',
			'{$delivery_customer_phone_mobile}' => !empty($value['delivery_address']['phone_mobile']) ? $value['delivery_address']['phone_mobile'] : '',
			'{$delivery_customer_other}' => !empty($value['delivery_address']['other']) ? $value['delivery_address']['other'] : '',
			'{$delivery_customer_email}'        => !empty($value['email']) ? $value['email'] : '',
			'{$customer_note}'                	=> !empty($value['note']) ? $value['note'] : '',
			'{$is_new_customer}' => (($this->countCustomerOrders($value['id_customer']) <= 1) ? 'Customer is new' : 'Customer is old'),

		);

		if ($only_keys == true)
			return array_keys($vars);
		else
			return $vars;
	}

	public function getProductFeatures($id_product, $return_field_names = false)
	{
		$Product = new Product($id_product);
		$product_features = $Product->getFeatures();

		$arr_info = array();
		foreach ($product_features as $value) 
		{	
			$s = Db::getInstance()->executeS('SELECT name FROM `'._DB_PREFIX_.'feature_lang` WHERE id_feature = "'.pSQL($value['id_feature']).'"');
			$value['feature_name'] = $s[0]['name'];

			$s = Db::getInstance()->executeS('SELECT value FROM `'._DB_PREFIX_.'feature_value_lang` WHERE id_feature_value = "'.pSQL($value['id_feature_value']).'"');
			$value['feature_value'] = $s[0]['value'];

			$arr_info[] = $value;
		}

		$product_features = $arr_info;

		if ($return_field_names == true)
			return array_keys($product_features[0]);
		else
			return $product_features;
		
	}	


	public function getProductAttributes($id_product, $return_field_names = false)
	{
		$product_attributes = Product::getAttributesInformationsByProduct($id_product);

		if ($return_field_names == true)
			return array_keys($product_attributes[0]);
		else
			return $product_attributes;
	}	

	public function getProductCombinations($id_product, $return_field_names = false)
	{
		$Product = new Product();
		$Product->id = $id_product;
		$product_combinations = $Product->getAttributeCombinations($this->id_lang);

		if (!empty($product_combinations))
		{
			$result = array();
			foreach($product_combinations as $value) {
				$result[$value['id_product_attribute']][] = $value;
			}

			$product_combinations_keys = array_keys($product_combinations[0]);
			$product_combinations = $result;
		}
		else
		{
			$product_combinations_keys = array();
			$product_combinations = array();
		}

		if ($return_field_names == true)
			return $product_combinations_keys;
		else
			return $product_combinations;
	}		


	public function updateOrdersStatuses($data = '')
	{
		$orders_ids_exp = explode(',', Tools::substr($data['orders_ids'], 0, -1));
		#d($orders_ids_exp);
		foreach ($orders_ids_exp as $value)
		{
			$order = new Order($value);
			$order->setCurrentState($data['update_status'], $this->context->employee->id);
		}

	}
	
	
	public function aasort(&$array, $key) 
	{
		$sorter = array();

		$ret = array();

		reset($array);

		foreach ($array as $ii => $va) 
			$sorter[$ii] = $va[$key];

		asort($sorter);

		foreach ($sorter as $ii => $va) 
			$ret[$ii] = $array[$ii];

		$array = $ret;

		return $array;
	}
	
/**
 * Convert object to array.
 */
public function object_to_array($obj)
{
	if (is_array($obj) || is_object($obj))
	{
		$result = array();
		foreach ($obj as $key => $value)
			$result[$key] = $this->object_to_array($value);
		
		return $result;
	}
	return $obj;
}
	

public function arrayToObject($array) 
{
  $obj = new stdClass;
  foreach($array as $k => $v) 
  {
     if(Tools::strlen($k)) 
     {
        if(is_array($v)) 
        {
           $obj->{$k} = $this->arrayToObject($v); //RECURSION
        } 
        else 
        {
           $obj->{$k} = $v;
        }
     }
  }
  return $obj;
} 
	
	/**
	 * Convert array to object.
	 */
	public function array_to_object($array)
	{
		$obj = new stdClass;
		
		if (!isset($array) || empty($array))
			return false;
		
		
		foreach ($array as $k => $v)
		{
			if (is_array($v))
				$obj->{$k} = $this->array_to_object($v); //RECURSION
			else
				$obj->{$k} = $v;
		}
		return $obj;
	}
	
	public function generateBarcode($code='', $type='code39', $width=200, $height=60)
	{
		#d($width);
		// Define variable to prevent hacking
		@define('IN_CB',true);
		// Including all required classes
		require_once('libraries/barcodes/barcode-module/index.php');
		require_once('libraries/barcodes/barcode-module/FColor.php');
		require_once('libraries/barcodes/barcode-module/BarCode.php');
		require_once('libraries/barcodes/barcode-module/FDrawing.php');
		
		// Creating some Color (arguments are R, G, B)
		$color_black = new FColor(0,0,0);
		$color_white = new FColor(255,255,255);

		// including the barcode technology
		/* Here is the list of the arguments:
		1 - Thickness
		2 - Color of bars
		3 - Color of spaces
		4 - Resolution
		5 - Text
		6 - Text Font (0-5) */
		if ($type == 'ean13')
		{
			require_once('libraries/barcodes/barcode-module/ean13.barcode.php');

			$code_generated = new ean13($height,$color_black,$color_white,1,$code,2);
		}
		elseif($type == 'upc')
		{
			require_once('libraries/barcodes/barcode-module/upca.barcode.php');

			$code_generated = new upca($height,$color_black,$color_white,1,$code,2);
		}
		elseif($type == 'code39')
		{
			require_once('libraries/barcodes/barcode-module/code39.barcode.php');

			$code_generated = new code39($height,$color_black,$color_white,1,$code,2);
		}
		elseif($type == 'code128')
		{
			require_once('libraries/barcodes/barcode-module/code128.barcode.php');

			$code_generated = new code128($height,$color_black,$color_white,1,$code,2);
		}
				
		/* Here is the list of the arguments
		1 - Width
		2 - Height
		3 - Filename (empty : display on screen)
		4 - Background color */
		$filename = dirname(__FILE__).'/views/img/barcodes/'.$code.'.png';
		$drawing = new FDrawing($width, $height+15, $filename, $color_white);
		$drawing->init(); // You must call this method to initialize the image
		$drawing->add_barcode($code_generated);
		$drawing->draw_all();
		$im = $drawing->get_im();
		// Next line create the little picture, the barcode is being copied inside
		$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
		imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
		$drawing->set_im($im2);
		// Header that says it is an image (remove it if you save the barcode to a file)
		@header('Content-Type: image/png');
		// Draw (or save) the image into PNG format.
		$drawing->finish(IMG_FORMAT_PNG);
		
		// cand imi exporta din hook-ul cu comenzi
		@header('Content-Type: text/html');

		return '<img src="'.$this->_path.'views/img/barcodes/'.$code.'.png" height="'.$height.'" width="'.$width.'">';
	}	



	# $s='', $text_size=3, $bar_thick=3, $bar_thin=1
	public function generateBarcode1($s = '', $text_size = 3, $bar_thick = 3, $bar_thin = 1)
	{
		require_once('libraries/barcodes/Barcode39.php');
		//require_once('libraries/barcodes/BarcodeQR.php');
		
		if (!empty($s))
		{
			$barcode = new Barcode39($s);
			#$barcode = new BarcodeQR( $s );
			
			// set text size
			$barcode->barcode_text_size = $text_size;
			
			// set barcode bar thickness (thick bars)
			$barcode->barcode_bar_thick = $bar_thick;
			
			// set barcode bar thickness (thin bars)
			$barcode->barcode_bar_thin = $bar_thin;
			
			$barcode->draw(dirname(__FILE__).'/views/img/barcodes/'.$s.'.gif');
			
			return '<img src="'.$this->_path.'views/img/barcodes/'.$s.'.gif">';
		}
	}
	
	public function generateBarcode2($code='', $type='ean13', $font_size=3, $x=100, $y=40, $height=50, $width=1, $angle=0, $file=true)
	{
		require_once('libraries/barcodes/barcode-php/php-barcode.php');
		$font     = './views/fonts/arial.ttf';
		$font_size *= 3;   // GD1 in px ; GD2 in point
		$marge    = 5;   // between barcode and hri in pixel
		//$x        = 100;  // barcode center
		//$y        = 30;  // barcode center
		//$height   = 50;   // barcode height in 1D ; module size in 2D
		//$width    = 2;    // barcode height in 1D ; not use in 2D
		//$angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
		//$code     = '123456789012';
		
		if (!empty($code))
		{
			if ($file == true)
				$filename = dirname(__FILE__).'/views/img/barcodes/'.$code.'.gif';

			// init GD
			$im     = imagecreatetruecolor($x*2, $y*2);
			$black  = ImageColorAllocate($im,0x00,0x00,0x00);
			$white  = ImageColorAllocate($im,0xff,0xff,0xff);
			$red    = ImageColorAllocate($im,0xff,0x00,0x00);
			$blue   = ImageColorAllocate($im,0x00,0x00,0xff);
			imagefilledrectangle($im, 0, 0, $x*2, $y*2, $white);

			// draw barcode
			$data = Barcode::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);

			// draw text under
			if ( isset($font) )
			{
				$xt = $yt = 0;
				$box = imagettfbbox($font_size, 0, $font, $data['hri']);
				$len = $box[2] - $box[0];
				Barcode::rotate(-$len / 2, ($data['height'] / 2) + $font_size + $marge, $angle, $xt, $yt);
				imagettftext($im, $font_size, $angle, $x + $xt, $y + $yt, $black, $font, $data['hri']);
			}

			// export as image
			if($filename)
				imagegif($im, $filename);
			else 
			{
				header("Content-type: image/gif");
				imagegif($im);
			}
			imagedestroy($im);		

			return '<img src="'.$this->_path.'views/img/barcodes/'.$code.'.gif">';
		}
		else
			return false;
	}

	public function generateBarcode3($code='', $type='ean13', $width=1, $height=50)
	{
		require_once('libraries/barcodes/barcode-generator/BarcodeGenerator.php');
		require_once('libraries/barcodes/barcode-generator/BarcodeGeneratorPNG.php');
		#require_once('libraries/barcodes/barcode-generator/BarcodeGeneratorSVG.php');
		#require_once('libraries/barcodes/barcode-generator/BarcodeGeneratorHTML.php');

		$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
		#$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
		#$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
		$filename = dirname(__FILE__).'/views/img/barcodes/'.$code.'.png';

		$generatorPNG->getBarcode($code, $generatorPNG::TYPE_EAN_13, $width, $height, $filename);

		return '<img src="'.$this->_path.'views/img/barcodes/'.$code.'.png">';
	}



	/*
	# create the array
	$x = array("apples","bananas","pears");
	# insert "oranges" at position 1
	$x = array_insert($x,"oranges",1);
	var_dump($x);
	# insert "pineapples" 2 from the end
	$x = array_insert($x,"pineapples",-2);
	var_dump($x);
	# insert "strawberries" at the end
	$x = array_insert($x,"strawberries");
	var_dump($x);
	# insert "plums" at position 0 - (because the negative position goes beyond 0)
	$x = array_insert($x,"plums",-10);
	var_dump($x);
	*/
	public function array_insert(&$array, $element, $position = null)
	{
		if (count($array) == 0)
			$array[] = $element;
		
		elseif (is_numeric($position) && $position < 0)
		{
			if ((count($array) + $position) < 0)
				$array = $this->array_insert($array, $element, 0);
			else
				$array[count($array) + $position] = $element;
			
		}
		elseif (is_numeric($position) && isset($array[$position]))
		{
			$part1 = array_slice($array, 0, $position, true);
			$part2 = array_slice($array, $position, null, true);
			$array = array_merge($part1, array(
				$position => $element
			), $part2);

			foreach ($array as $key => $item)
			{
				if (is_null($item))
					unset($array[$key]);
				
			}
		}
		elseif (is_null($position))
			$array[] = $element;
		
		elseif (!isset($array[$position]))
			$array[$position] = $element;
		
		$array = array_merge($array);

		return $array;
	}
	
	
	
} #end class

if (!function_exists('smartyFunctionBarcode')) 
{
	function smartyFunctionBarcode($params, $smarty) 
	{
		require_once(dirname(__FILE__).'/../../config/config.inc.php');
		require_once(dirname(__FILE__).'/../../init.php');
		ini_set('max_execution_time', '2880');
		$module = Module::getInstanceByName('pqprintshippinglabels');
		$smarty = $smarty;
		if (empty($params['from']))
			return false;
		#d($module->config['barcodes_width']);
		$barcodes_type = empty($params['barcodes_type']) ? (empty($module->config['barcodes_type']) ? 'code39' : $module->config['barcodes_type']) : $params['barcodes_type'];
		$barcodes_width = empty($params['barcodes_width']) ? (empty($module->config['barcodes_width']) ? 200 : $module->config['barcodes_width']) : $params['barcodes_width'];
		$barcodes_height = empty($params['barcodes_height']) ? (empty($module->config['barcodes_height']) ? 60 : $module->config['barcodes_height']) : $params['barcodes_height'];
		
		return $module->generateBarcode($params['from'], $barcodes_type, $barcodes_width, $barcodes_height);
		#return $module->generateBarcode1($params['from'], 3, 3, 1);
		#return $module->generateBarcode2($params['from'], 'ean13', $barcodes_text_size);
		#return $module->generateBarcode3($params['from'], 'ean13');
		
	}
}

?>