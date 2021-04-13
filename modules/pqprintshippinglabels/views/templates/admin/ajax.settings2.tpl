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
{if !empty($settings)}
<select size="1" name="settings" id="psl_settings2" class="psl formfield1" autocomplete="off">
    {foreach from=$settings key=key item=value}
    <option value="{$value.id_setting|escape:'quotes':'UTF-8'}" {if $value.is_default == '1'}selected{/if}>{$value.name|escape:'quotes':'UTF-8'}</option>
    {/foreach}
</select>
{/if}
