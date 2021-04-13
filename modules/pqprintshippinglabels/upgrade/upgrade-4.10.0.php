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

/**
 * Function used to update your module from previous versions to the version 5.0.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_4_10_0($module)
{
	$result = true;
	
	if (!$module->active)
		$result = false;

	try {

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `export_automatically_on_new_order` INT(11) NULL DEFAULT NULL AFTER `one_label_for_each_product`;")) $result = false;

	} 
	catch (Exception $e) 
	{
		$result = false;
	}

	/* clear cache */
	if (version_compare(_PS_VERSION_, '1.5.5.0', '>='))
		Tools::clearSmartyCache();

	return $result;
}
