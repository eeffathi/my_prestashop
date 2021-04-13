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
<select size="1" name="order_state" id="psl_order_state" class="psl formfield1">
    <option value="none">{l s='-don`t lock-' mod='pqprintshippinglabels'}</option>
    <option value="all">{l s='-all statuses-' mod='pqprintshippinglabels'}</option>
    {foreach from=$order_states key=key item=value}
    <option value="{$value.id_order_state|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
    {/foreach}
</select>