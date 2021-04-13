{*
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
*}
<select size="1" name="page_type" id="page_type" class="psl formfield1">
	{foreach from=$page_types key=key item=value}
	<option value="{$value.id_pagetype|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'} ({$value.width|escape:'quotes':'UTF-8'} / {$value.height|escape:'quotes':'UTF-8'}) mm</option>
	{/foreach}
</select>