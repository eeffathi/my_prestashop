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
<select size="1" name="select_template" id="select_template" class="psl formfield1">
	{foreach from=$templates key=key item=value}
	<option value="{$value.id_template|escape:'quotes':'UTF-8'}" {if $value.id_template == '1'}selected{/if}>{$value.name|escape:'quotes':'UTF-8'} ({$value.width|escape:'quotes':'UTF-8'} / {$value.height|escape:'quotes':'UTF-8'}) mm</option>
	{/foreach}
</select>