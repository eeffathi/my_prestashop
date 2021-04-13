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

		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_pagetypes` (
	`id_pagetype` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TINYTEXT NULL,
	`width` TINYTEXT NULL,
	`height` TINYTEXT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_pagetype`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=49
;";


		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_settings` (
	`id_setting` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TINYTEXT NULL,
	`id_order_state` TINYTEXT NULL,
	`id_pagetype` TINYTEXT NULL,
	`label_copies` TINYTEXT NULL,
	`labels_horizontally` TINYTEXT NULL,
	`labels_vertically` TINYTEXT NULL,
	`spacing_between_labels_vertically` TINYTEXT NULL,
	`spacing_between_labels_horizontally` TINYTEXT NULL,
	`page_padding_left` TINYTEXT NULL,
	`page_padding_top` TINYTEXT NULL,
	`labels_border` INT(1) NULL DEFAULT '1',
	`rounded_corners_radius` INT(11) NULL DEFAULT NULL,
	`one_label_for_each_product` INT(11) NULL DEFAULT NULL,
	`export_automatically_on_new_order` INT(11) NULL DEFAULT NULL,
	`barcodes_type` TINYTEXT NULL,
	`barcodes_width` TINYTEXT NULL,
	`barcodes_height` TINYTEXT NULL,
	`id_template` INT(11) NULL DEFAULT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_setting`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=19
;";
		

		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_templates` (
	`id_template` INT(11) NOT NULL AUTO_INCREMENT,
	`width` TINYTEXT NULL,
	`height` TINYTEXT NULL,
	`per_page` TINYTEXT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_template`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=14
;";
		

		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_templates_lang` (
	`id_template` INT(11) UNSIGNED NOT NULL,
	`id_lang` INT(11) UNSIGNED NULL DEFAULT NULL,
	`iso_code` TINYTEXT NULL,
	`name` TINYTEXT NULL,
	`html` LONGTEXT NULL,
	INDEX `id_template` (`id_template`),
	INDEX `id_lang` (`id_lang`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;";

		foreach ($sql_array as $sql)
			$sql_execution[] = Db::getInstance()->Execute($sql);

		$sql_array = array();

		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_pagetypes` (`id_pagetype`, `name`, `width`, `height`, `is_default`) VALUES (1, 'A4', '210', '297', 1);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_pagetypes` (`id_pagetype`, `name`, `width`, `height`, `is_default`) VALUES (2, 'Letter', '215.9', '279.4', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_pagetypes` (`id_pagetype`, `name`, `width`, `height`, `is_default`) VALUES (3, 'Dymo1', '54', '25', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_pagetypes` (`id_pagetype`, `name`, `width`, `height`, `is_default`) VALUES (4, 'Dymo2', '89', '28', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_pagetypes` (`id_pagetype`, `name`, `width`, `height`, `is_default`) VALUES (5, 'Dymo3', '89', '36', 0);";

		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (1, '1-labels', 'none', '1', '1', '1', '1', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 1, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (2, '2-labels', 'none', '1', '1', '1', '2', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 2, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (3, '4-labels', 'none', '1', '1', '2', '2', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 3, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (4, '6-labels', 'none', '1', '1', '2', '3', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 4, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (5, '8-labels', 'none', '1', '1', '2', '4', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 5, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (6, '10-labels', 'none', '1', '1', '2', '5', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 6, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (7, '1-labels-dymo1', 'none', '3', '1', '1', '1', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 7, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (8, '1-labels-dymo2', 'none', '4', '1', '1', '1', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 8, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (9, '1-labels-dymo3', 'none', '5', '1', '1', '1', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 9, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (10, '1-labels-products', 'none', '1', '1', '1', '1', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 10, 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `name`, `id_order_state`, `id_pagetype`, `label_copies`, `labels_horizontally`, `labels_vertically`, `spacing_between_labels_vertically`, `spacing_between_labels_horizontally`, `page_padding_left`, `page_padding_top`, `labels_border`, `rounded_corners_radius`, `one_label_for_each_product`, `export_automatically_on_new_order`, `barcodes_type`, `barcodes_width`, `barcodes_height`, `id_template`, `is_default`) VALUES (11, '4-labels-products', 'none', '1', '1', '2', '2', '1', '1', '0', '0', 1, 3, 0, 0, 'code39', '200', '60', 11, 0);";

		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (1, '210', '292', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (2, '210', '148.5', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (3, '105', '148.5', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (4, '105', '99', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (5, '105', '70', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (6, '105', '57', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (7, '54', '25', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (8, '89', '28', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (9, '89', '36', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (10, '210', '292', '0', 0);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_templates` (`id_template`, `width`, `height`, `per_page`, `is_default`) VALUES (11, '105', '148.5', '0', 0);";
