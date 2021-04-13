{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2021 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<select name="{$input.name|escape:'html':'UTF-8'}[]"
        class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-xl"
        id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
        {if isset($input.multiple) && $input.multiple} multiple="multiple"{/if}
        {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}"{/if}
        {if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if}
        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}>
    {foreach $input.options.query AS $option}
													{if is_object($option)}
														<option value="{$option->$input.options.id}"
															{if isset($input.multiple)}
																{foreach $fields_value[$input.name] as $field_value}
																	{if $field_value == $option->$input.options.id}
																		selected="selected"
																	{/if}
																{/foreach}
															{else}
																{if $fields_value[$input.name] == $option->$input.options.id}
																	selected="selected"
																{/if}
															{/if}
														>{$option->$input.options.name}</option>
													{elseif $option == "-"}
														<option value="">-</option>
													{else}
														<option value="{$option[$input.options.id]}"
															{if isset($input.multiple)}
																{foreach $fields_value[$input.name] as $field_value}
																	{if $field_value == $option[$input.options.id]}
																		selected="selected"
																	{/if}
																{/foreach}
															{else}
																{if $fields_value[$input.name] == $option[$input.options.id]}
																	selected="selected"
																{/if}
															{/if}
														>{$option[$input.options.name]}</option>

													{/if}
												{/foreach}
</select>
<script>
	$('[name="{$input.name}[]"]').select2();
</script>