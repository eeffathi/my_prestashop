{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2013-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="form-group">
    <div class="col-lg-12">
        <div class="form-control-static row">
            <div class="col-xs-6">
                <select {if isset($input.size)}size="{$input.size|escape:'html':'UTF-8'}"{/if}{if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if} class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}" id="availableSwap" name="{$input.name|escape:'html':'UTF-8'}_available[]" multiple="multiple">
                    {foreach $input.options.query AS $option}
                        {if is_object($option)}
                            {if !in_array($option->$input.options.id, $fields_value[$input.name])}
                                <option value="{$option->$input.options.id}">{$option->$input.options.name}</option>
                            {/if}
                        {elseif $option == "-"}
                            <option value="">-</option>
                        {else}
                            {if !in_array($option[$input.options.id], $fields_value[$input.name])}
                                <option value="{$option[$input.options.id]}">{$option[$input.options.name]}</option>
                            {/if}
                        {/if}
                    {/foreach}
                </select>
                <a href="#" id="addSwap" class="btn btn-default btn-block">{l s='Add' mod='seosaproductlabels'} <i class="icon-arrow-right"></i></a>
            </div>
            <div class="col-xs-6">
                <select {if isset($input.size)}size="{$input.size|escape:'html':'UTF-8'}"{/if}{if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if} class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}" id="selectedSwap" name="{$input.name|escape:'html':'UTF-8'}_selected[]" multiple="multiple">
                    {foreach $input.options.query AS $option}
                        {if is_object($option)}
                            {if in_array($option->$input.options.id, $fields_value[$input.name])}
                                <option value="{$option->$input.options.id}">{$option->$input.options.name}</option>
                            {/if}
                        {elseif $option == "-"}
                            <option value="">-</option>
                        {else}
                            {if in_array($option[$input.options.id], $fields_value[$input.name])}
                                <option value="{$option[$input.options.id]}">{$option[$input.options.name]}</option>
                            {/if}
                        {/if}
                    {/foreach}
                </select>
                <a href="#" id="removeSwap" class="btn btn-default btn-block"><i class="icon-arrow-left"></i> {l s='Remove' mod='seosaproductlabels'}</a>
            </div>
        </div>
    </div>
</div>