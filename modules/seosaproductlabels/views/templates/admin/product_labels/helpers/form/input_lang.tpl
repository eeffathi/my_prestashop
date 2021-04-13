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

{if isset($input.lang) AND $input.lang}
    {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}
        {if $languages|count > 1}
            <div class="form-group">
        {else}
            <div class="img-thumbnail-block">
        {/if}
    {else}
        <div class="translatable">
    {/if}
    {foreach $languages as $language}
        {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}
            {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
            {if $languages|count > 1}
                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                <div class="col-lg-9">
            {/if}
        {else}
            <div class="lang_{$language.id_lang|intval}" style="display:{if $language.id_lang == $defaultFormLanguage}block{else}none{/if}; float: left;">
        {/if}
        <input data-file-text="{l s='Select Image' mod='seosaproductlabels'}" class="fileStyle" type="file" name="{$input.name|escape:'quotes':'UTF-8'}_{$language.id_lang|intval}">
        {$fields_value.image[$language.id_lang]|escape:'quotes':'UTF-8'}
        {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}
            {if $languages|count > 1}
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                        {$language.iso_code|escape:'quotes':'UTF-8'}
                        <i class="icon-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$languages item=language}
                            <li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'quotes':'UTF-8'}</a></li>
                        {/foreach}
                    </ul>
                </div>
                </div>
            {/if}
        {else}
            </div>
        {/if}
    {/foreach}

    {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '>=')}
        </div>
    {else}
        </div>
    {/if}
{/if}
<script>
    hideOtherLanguage({$current_lang|escape:'html':'UTF-8'});
</script>