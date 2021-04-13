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

$sql_array = array();

$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_pagetypes`;';
$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_settings`;';
$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_templates`;';
$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_templates_lang`;';