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

$filename = $_REQUEST['filename'];

header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Content-Length: ' . filesize($filename));
//header('Cache-Control: no-cache, no-store, must-revalidate');
//header('Pragma: no-cache');
readfile($filename);
?>