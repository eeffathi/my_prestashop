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

header('Access-Control-Allow-Origin: *');
require_once (dirname(__FILE__) . '/../../config/config.inc.php');
require_once (dirname(__FILE__) . '/../../init.php');
ini_set('max_execution_time', '2880');
$module = Module::getInstanceByName('pqprintshippinglabels');
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

if (Tools::isSubmit('token')) 
{
	if (!$module->isTokenValid(Tools::getValue('token'))) 
		die('Invalid Token!');
} 
else 
	die('Invalid Token!');

$type = Tools::isSubmit('type') ? Tools::getValue('type') : false;

switch ($type) 
{
	case 'orders':
		$response = $module->fillGridDataTables($_POST);

	case 'getSelectedOrders':
		$selected = Tools::getValue('selected');
		$data = $module->getSelectedOrders($selected);
		die($data);
		
		
	case 'previewTemplate':
		$data = $module->generatePDF(true, Tools::getValue('data'));
		die($data);
		
	case 'exportToPdf':
		
		$data = Tools::getValue('data');
		if ($data['update_status'] != 'no') $module->updateOrdersStatuses(Tools::getValue('data'));
		$data = $module->generatePDF(false, Tools::getValue('data'));
		die($data);
		
	case 'showSettings2Dropdown':
		$response = $module->showSettings2Dropdown(Tools::getValue('orders_ids'));
		die($response);

	case 'deleteSettings':
		$result = $module->deleteSettings(Tools::getValue('id_setting'));
		
		if ($result == true) $response = array(
			'success' => true,
			'response' => $module->showSettingsDropdown()
		);
		else $response = array(
			'success' => false,
			'response' => 'You can`t delete the settings in DEMO MODE!'
		);
		die(Tools::jsonEncode($response));
		
	case 'deletePageType':
		$result = $module->deletePageType(Tools::getValue('id_pagetype'));
		
		if ($result == true) $response = array(
			'success' => true,
			'response' => $module->showPageTypesDropdown()
		);
		else $response = array(
			'success' => false,
			'response' => 'You can`t delete the pagetypes in DEMO MODE!'
		);
		die(Tools::jsonEncode($response));
		
	case 'deleteTemplate':
		$result = $module->deleteTemplate(Tools::getValue('id_template'));
		
		if ($result == true) $response = array(
			'success' => true,
			'response' => $module->showTemplatesDropdown()
		);
		else $response = array(
			'success' => false,
			'response' => 'You can`t delete the default templates in DEMO MODE!'
		);
		
		die(Tools::jsonEncode($response));
		
		
	case 'getTemplate':
		$data = $module->getTemplate(Tools::getValue('data'));
		die(Tools::jsonEncode($data));
		
		
	case 'getSetting':
		$data = $module->getSetting(Tools::getValue('id_setting') , Tools::getValue('iso_code'));
		die(Tools::jsonEncode($data[0]));
		
		
	case 'getPageType':
		$data = $module->getPageType(Tools::getValue('id_pagetype'));
		die(Tools::jsonEncode($data));
		
		
	case 'editorSettings':
		$response = $module->editorSettings($_POST);
		break;

	case 'editorPageType':
		$response = $module->editorPageType($_POST);
		break;

	case 'editorTemplate':
		$response = $module->editorTemplate($_POST);
		die(Tools::jsonEncode($response));
		
	case 'makeDefaultSetting':
		$result = $module->makeDefaultSetting(Tools::getValue('data'));
		
		if ($result === true) $response = array(
			'success' => true,
			'response' => $module->l('The default setting was saved!', $filename)
		);
		elseif ($result == 'ERROR_DEMO_MODE') $response = array(
			'success' => false,
			'response' => $module->l('You can`t update the template in DEMO MODE!', $filename)
		);
		
		die(Tools::jsonEncode($response));
		
	case 'updateTemplate':
		
		$result = $module->updateTemplate(Tools::getValue('data'));
		
		if ($result === true) $response = array(
			'success' => true,
			'response' => $module->showTemplatesDropdown()
		);
		elseif ($result == 'ERROR_DEMO_MODE') $response = array(
			'success' => false,
			'response' => $module->l('You can`t update the template in DEMO MODE!', $filename)
		);
		
		die(Tools::jsonEncode($response));
		
}

?>