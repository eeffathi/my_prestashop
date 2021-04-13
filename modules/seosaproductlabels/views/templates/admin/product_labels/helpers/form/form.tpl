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

{extends file="helpers/form/form.tpl"}

{block name="fieldset"}
    {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}
    <div class="panel" id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
        {foreach $fieldset.form as $key => $field}
            {if $key == 'legend'}
                {block name="legend"}
                    <div class="panel-heading">
                        {if isset($field.image) && isset($field.title)}<img src="{$field.image}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                        {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                        {$field.title}
                    </div>
                {/block}
            {elseif $key == 'description' && $field}
                <div class="alert alert-info">{$field}</div>
            {elseif $key == 'warning' && $field}
                <div class="alert alert-warning">{$field}</div>
            {elseif $key == 'success' && $field}
                <div class="alert alert-success">{$field}</div>
            {elseif $key == 'error' && $field}
                <div class="alert alert-danger">{$field}</div>
            {elseif $key == 'input'}

                <div class="form-wrapper">

                    <!-- Enabled -->
                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Enabled' mod='seosaproductlabels'}
                    </label>

                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="row">

                                <div class="col-lg-3">
                                    <!-- switch_active -->
                                    {foreach $field as $input}
                                        {block name="input"}
                                            {if $input.type == 'switch_active'}
                                                <div class="float-left">
                                            <span class="switch switch_active prestashop-switch fixed-width-lg">
										{foreach $input.values as $value}
                                            <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
										{strip}
                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
											{if $value.value == 1}
                                                {l s='Yes' mod='seosaproductlabels'}
                                            {else}
                                                {l s='No' mod='seosaproductlabels'}
                                            {/if}
										</label>
                                        {/strip}
                                        {/foreach}
                                                <a class="slide-button btn"></a>
                                </span></div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>

                                <div class="col-lg-6">
                                    <!-- text_name -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_name'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="{if is_array($input.hint)}
                                        {/if}">
                                        {$input.label}
                                        </label>

                                    {/if}
                                    {/if}
                                    {/block}
                                    {block name="input"}
                                    {if $input.type == 'text_name'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-wrapper">

                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Arrangement' mod='seosaproductlabels'}
                    </label>
                    <div class="col-lg-9">

                        <div class="form-group">
                            <!-- free2 -->
							
                            {foreach $field as $input}
                                {block name="input"}
                                    {if $input.type == 'free2'}
                                        {$fields_value[$input.name]}
                                    {/if}
                                {/block}
                            {/foreach}
                        </div>

                        <div class="form-group js-child-category">
                            <!-- switch_include_category_product -->
                            {foreach $field as $input}
                                {block name="label"}
                                    {if $input.type == 'switch_include_category_product'}
                                        {if isset($input.label)}
                                            {if isset($input.hint)}
                                                <label class="control-label float-left margin-right" data-toggle="tooltip" data-html="true" title="{if is_array($input.hint)}
													{foreach $input.hint as $hint}
														{if is_array($hint)}
															{$hint.text|escape:'quotes':'UTF-8'}
														{else}
															{$hint|escape:'quotes':'UTF-8'}
														{/if}
													{/foreach}
												{else}
													{$input.hint|escape:'quotes':'UTF-8'}
												{/if}">
                                            {/if}
                                            {$input.label}
                                            {if isset($input.hint)}
                                                </label>
                                            {/if}
                                        {/if}
                                    {/if}
                                {/block}
                                {block name="input"}
                                    {if $input.type == 'switch_include_category_product'}
                                        <div class="float-left">
                                            <span class="switch switch_include_category_product prestashop-switch fixed-width-lg">
										{foreach $input.values as $value}
                                            <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
										{strip}
                                            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
											{if $value.value == 1}
                                                {l s='Yes' mod='seosaproductlabels'}
                                            {else}
                                                {l s='No' mod='seosaproductlabels'}
                                            {/if}
										</label>
                                        {/strip}
                                        {/foreach}
                                                <a class="slide-button btn"></a>
                                </span>
                                        </div>
                                    {/if}
                                {/block}
                            {/foreach}
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <!-- excluded_select2 -->
                                {foreach $field as $input}
                                    {block name="label"}
                                        {if $input.type == 'exlc_incl_select2'}
                                            {if isset($input.label)}
                                                <div class="col-lg-3">
                                                    <label class="control-label float-left margin-right">
                                                        {if isset($input.hint)}
                                                        <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{if is_array($input.hint)}
													{foreach $input.hint as $hint}
														{if is_array($hint)}
															{$hint.text|escape:'quotes':'UTF-8'}
														{else}
															{$hint|escape:'quotes':'UTF-8'}
														{/if}
													{/foreach}
												{else}
													{$input.hint|escape:'quotes':'UTF-8'}
												{/if}">
										{/if}
                                                        {$input.label}
                                                            {if isset($input.hint)}
										</span>
                                                        {/if}
                                                    </label>
                                                </div>
                                            {/if}
                                        {/if}
                                    {/block}
                                    {block name="input"}
                                        {if $input.type == 'exlc_incl_select2'}
                                            <div class="excluded_select_block col-lg-9">
                                                {assign var="field_value" value=$fields_value[$input.name]}
                                                {*<button id="excluded_btn" type="button" class="btn btn-default" style="float: left;">Показать</button>*}
                                                <select id="pl_{$input.name|escape:'quotes':'UTF-8'}" name="{$input.name|escape:'quotes':'UTF-8'}[]" multiple>

                                                </select>
                                                <script>
                                                    $('[name="{$input.name|escape:'quotes':'UTF-8'}[]"]').select2();
                                                </script>
                                            </div>
                                        {/if}
                                    {/block}
                                {/foreach}
                            </div>
                        </div>

                    </div>

                </div>

                <div class="form-wrapper">

                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Price and Valid' mod='seosaproductlabels'}
                    </label>

                    <div class="col-lg-9">

                        <div class="row">

                            <div class="col-lg-5">
                                <div class="clearfix form-group">
                                    <!-- text_mini_price -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_mini_price'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}

                                    {block name="input"}
                                    {if $input.type == 'text_mini_price'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                                        <span class="text-count-down">{$input.maxchar|intval}</span>
                                                    </span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
                                                        {$input.prefix}
                                                    </span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
                                                        {$input.suffix}
                                                    </span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
                                                    {$input.prefix}
                                                </span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if} fixed-width-sm"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
                                                    {$input.suffix}
                                                </span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>
                                <div class="clearfix form-group">
                                    <!-- text_max_price -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_max_price'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}
                                    {block name="input"}
                                    {if $input.type == 'text_max_price'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
                                                        <span class="text-count-down">{$input.maxchar|intval}</span>
                                                    </span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
                                                        {$input.prefix}
                                                    </span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
                                                        {$input.suffix}
                                                    </span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
                                                    {$input.prefix}
                                                </span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if} fixed-width-sm"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
                                                    {$input.suffix}
                                                </span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="clearfix form-group">
                                    {foreach $field as $input}
                                        {block name="label"}
                                            {if $input.type == 'date_from_to'}
                                                <label class="control-label float-left margin-right">
                                                    {$input.label}
                                                    {if isset($input.hint)}
                                                        </span>
                                                    {/if}
                                                </label>
                                            {/if}
                                        {/block}
                                        {block name="input"}
                                            {if $input.type == 'date_from_to'}
                                                <div class="date_from_to_block">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">{l s='From' mod='seosaproductlabels'}</span>
                                                                <input
                                                                        id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name_from|escape:'html':'UTF-8'}{/if}"
                                                                        type="text"
                                                                        data-hex="true"
                                                                        {if isset($input.class)} class="{$input.class}"
                                                                        {else}class="datepicker"{/if}
                                                                        name="{$input.name_from|escape:'html':'UTF-8'}"
                                                                        value="{$fields_value[$input.name_from]|escape:'html':'UTF-8'}" />
                                                                <span class="input-group-addon">
                                                                    <i class="icon-calendar-empty"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>

                                <div class="clearfix form-group">
                                    {foreach $field as $input}
                                        {block name="label"}
                                            {if $input.type == 'date_from_to'}
                                                <label class="control-label float-left margin-right">
                                                    {$input.label}
                                                    {if isset($input.hint)}
                                                        </span>
                                                    {/if}
                                                </label>
                                            {/if}
                                        {/block}
                                        {block name="input"}
                                            {if $input.type == 'date_from_to'}
                                                <div class="date_from_to_block">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">{l s='To' mod='seosaproductlabels'}</span>
                                                                <input
                                                                        id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name_to|escape:'html':'UTF-8'}{/if}"
                                                                        type="text"
                                                                        data-hex="true"
                                                                        {if isset($input.class)} class="{$input.class}"
                                                                        {else}class="datepicker"{/if}
                                                                        name="{$input.name_to|escape:'html':'UTF-8'}"
                                                                        value="{$fields_value[$input.name_to]|escape:'html':'UTF-8'}" />
                                                                <span class="input-group-addon">
                                                                    <i class="icon-calendar-empty"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="form-wrapper">

                    <!-- Position -->
                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Position and Access' mod='seosaproductlabels'}
                    </label>

                    <div class="col-lg-9">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3">
                                    <!-- position -->
                                    {foreach $field as $input}
                                        {block name="input"}
                                            {if $input.type == 'position'}
                                                <div class="position_block">
                                                    {assign var="field_value" value=$fields_value[$input.name]}
                                                    <!-- table position -->
                                                    <table class="table position">
                                                        {foreach from=['top', 'center', 'bottom'] item='pos_ver'}
                                                            <tr>
                                                                {foreach from=['left', 'center', 'right'] item='pos_hor'}
                                                                    {assign var="position" value="`$pos_ver`-`$pos_hor`"}
                                                                    <td>
                                                                        <label for="{$input.name|escape:'quotes':'UTF-8'}_{$position|escape:'quotes':'UTF-8'}">
                                                                            <input id="{$input.name|escape:'quotes':'UTF-8'}_{$position|escape:'quotes':'UTF-8'}" type="radio" {if $field_value}{if $field_value == $position}checked{/if}{else}{if $position == 'top-right'}checked{/if}{/if} value="{$position|escape:'quotes':'UTF-8'}" name="{$input.name|escape:'quotes':'UTF-8'}">
                                                                            <span class="btn btn-default"></span>
                                                                        </label>
                                                                    </td>
                                                                {/foreach}
                                                            </tr>
                                                        {/foreach}
                                                    </table>
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>

                                <div class="col-lg-9">


                                        <!-- group2 -->
                                        {foreach $field as $input}
                                            {block name="label"}
                                                {if $input.type == 'group2'}
                                                    {if isset($input.label)}
                                                        {if isset($input.hint)}
                                                            <label class="control-label float-left margin-right">
                                                                {$input.label}
                                                            </label>
                                                        {/if}
                                                    {/if}
                                                {/if}
                                            {/block}
                                            {block name="input"}
                                                {if $input.type == 'group2'}
                                                    <div class="float-left">
                                                        {assign var=groups value=$input.values}
                                                        {include file='helpers/form/form_group.tpl'}
                                                    </div>
                                                {/if}
                                            {/block}
                                        {/foreach}


                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="form-wrapper">

                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Sticker' mod='seosaproductlabels'}
                    </label>

                    <div class="col-lg-9">

                        <div class="form-group">
                            <!-- button -->
                            {foreach $field as $input}

                                {block name="input"}
                                    {if $input.type == 'button'}
                                        <div class="button_block float-left">
                                            <div class="btn-group-radio">
                <span class="switch prestashop-switch fixed-width-xxl switch-mono-color margin-right">
                    {foreach from=$input.values item=button}
                        <input type="radio"
                                {if $fields_value[$input.name] == $button.value} checked{/if}
                                {if $fields_value[$input.name] == '' && $button.value == 'image'} checked{/if}
                               name="{$input.name|escape:'quotes':'UTF-8'}"
                               value="{$button.value|escape:'quotes':'UTF-8'}"
                               id="{$button.id|escape:'html':'UTF-8'}"
                        />
                        <label for="{$button.id|escape:'html':'UTF-8'}">
                            {$button.label|escape:'quotes':'UTF-8'}
                        </label>
                    {/foreach}
                    <a class="slide-button btn"></a>
                </span>
                                            </div>
                                        </div>
                                    {/if}
                                {/block}
                            {/foreach}
                        </div>

                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group img-block">
                                    <!-- file_style -->
                                    {foreach $field as $input}
                                        {block name="input"}
                                            {if $input.type == 'file_style'}
                                                <div class="file_style_block">
                                                    {assign var="field_value" value=$fields_value[$input.name]}
                                                    {include file="./input_lang.tpl"}

                                                    <span>{l s='Default image size 80x80' mod='seosaproductlabels'}</span>
                                                    <script>
                                                        $('.fileStyle').fileStyle();
                                                    </script>
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>

                                <div class="form-group text-block">
                                    {foreach $field as $input}

                                    {block name="label"}
                                    {if $input.type == 'text_text'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}

                                    {block name="input"}
                                    {if $input.type == 'text_text'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <!-- text_label -->
                                        <div class="float-left">
                                            <div class="row fixed-width-xxl">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                            </div>
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="flost-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}

                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="form-group img-block">
                                    <!-- text_index_image_css -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_index_image_css'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}
                                    {block name="input"}
                                    {if $input.type == 'text_index_image_css'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="fixed-width-xl text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>

                                <div class="form-group img-block">
                                    <!-- text_product_image_css -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_product_image_css'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}
                                    {block name="input"}
                                    {if $input.type == 'text_product_image_css'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="fixed-width-xl text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>

                                <div class="form-group img-block">
                                    <!-- text_category_image_css -->
                                    {foreach $field as $input}
                                    {block name="label"}
                                    {if $input.type == 'text_category_image_css'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}
                                    {block name="input"}
                                    {if $input.type == 'text_category_image_css'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- text2 -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="fixed-width-xl text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}
                                </div>

                                <div class="form-group text-block ">
                                    <!-- text_url -->
                                    {foreach $field as $input}

                                    {block name="label"}
                                    {if $input.type == 'text_text_css'}
                                    {if isset($input.label)}
                                        <label class="control-label float-left margin-right">
                                            {$input.label}
                                        </label>
                                    {/if}
                                    {/if}
                                    {/block}

                                    {block name="input"}
                                    {if $input.type == 'text_text_css'}
                                    {if isset($input.lang) AND $input.lang}
                                    {if $languages|count > 1}
                                        <div class="form-group">
                                            {/if}
                                            {foreach $languages as $language}
                                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                {if $languages|count > 1}
                                                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                    <div class="col-lg-9">
                                                {/if}
                                                {if $input.type == 'tags'}
                                                {literal}
                                                    <script type="text/javascript">
                                                        $().ready(function () {
                                                            var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                            $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                            $({/literal}'#{$table}{literal}_form').submit( function() {
                                                                $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                            });
                                                        });
                                                    </script>
                                                {/literal}
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                                {if isset($input.maxchar) && $input.maxchar}
                                                    <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                                {/if}
                                                {if isset($input.prefix)}
                                                    <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                                {/if}
                                                <input type="text"
                                                       id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                       name="{$input.name}_{$language.id_lang}"
                                                       class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required} required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                                {if isset($input.suffix)}
                                                    <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                                {/if}
                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                </div>
                                            {/if}
                                                {if $languages|count > 1}
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                            {$language.iso_code}
                                                            <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item=language}
                                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <script type="text/javascript">
                                                    $(document).ready(function(){
                                                        {foreach from=$languages item=language}
                                                        countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                        {/foreach}
                                                    });
                                                </script>
                                            {/if}
                                            {if $languages|count > 1}
                                        </div>
                                    {/if}
                                        {else}
                                    {if $input.type == 'tags'}
                                    {literal}
                                        <script type="text/javascript">
                                            $().ready(function () {
                                                var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                                $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                                $({/literal}'#{$table}{literal}_form').submit( function() {
                                                    $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                });
                                            });
                                        </script>
                                    {/literal}
                                    {/if}
                                        {assign var='value_text' value=$fields_value[$input.name]}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                            {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                            {/if}

                                            <!-- CSS for text -->
                                            <div class="float-left">
                                                <input type="text"
                                                       name="{$input.name}"
                                                       id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                       class="fixed-width-xl text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                        {if isset($input.size)} size="{$input.size}"{/if}
                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                        {if isset($input.required) && $input.required } required="required" {/if}
                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                                />
                                            </div>
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                            {/if}

                                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                            });
                                        </script>
                                    {/if}
                                    {/if}
                                    {/if}
                                    {/block}
                                    {/foreach}

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- text_url -->
                            {foreach $field as $input}

                            {block name="label"}
                            {if $input.type == 'text_url'}
                            {if isset($input.label)}
                                <label class="control-label float-left margin-right">
                                    {$input.label}
                                </label>
                            {/if}
                            {/if}
                            {/block}

                            {block name="input"}
                            {if $input.type == 'text_url'}
                            {if isset($input.lang) AND $input.lang}
                            {if $languages|count > 1}
                                <!-- text_url_block -->
                                <div class="float-left">
                                    <div class="row fixed-width-xxl">
                                        {/if}
                                        {foreach $languages as $language}
                                            {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                            {if $languages|count > 1}
                                                <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                <div class="col-lg-9">
                                            {/if}
                                            {if $input.type == 'tags'}
                                            {literal}
                                                <script type="text/javascript">
                                                    $().ready(function () {
                                                        var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                        $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                        $({/literal}'#{$table}{literal}_form').submit( function() {
                                                            $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                        });
                                                    });
                                                </script>
                                            {/literal}
                                            {/if}
                                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                            <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                        {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                            {/if}
                                            {if isset($input.prefix)}
                                                <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                            {/if}
                                            <input type="text"
                                                   id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                                   name="{$input.name}_{$language.id_lang}"
                                                   class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                   value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                   onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                    {if isset($input.size)} size="{$input.size}"{/if}
                                                    {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                    {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                    {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                    {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                    {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                    {if isset($input.required) && $input.required} required="required" {/if}
                                                    {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                            {if isset($input.suffix)}
                                                <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                            {/if}
                                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                            </div>
                                        {/if}
                                            {if $languages|count > 1}
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                        {$language.iso_code}
                                                        <i class="icon-caret-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        {foreach from=$languages item=language}
                                                            <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                                </div>
                                            {/if}
                                        {/foreach}
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    {foreach from=$languages item=language}
                                                    countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                    {/foreach}
                                                });
                                            </script>
                                        {/if}
                                        {if $languages|count > 1}
                                    </div>
                                </div>
                            {/if}
                                {else}
                            {if $input.type == 'tags'}
                            {literal}
                                <script type="text/javascript">
                                    $().ready(function () {
                                        var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                        $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                        $({/literal}'#{$table}{literal}_form').submit( function() {
                                            $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                        });
                                    });
                                </script>
                            {/literal}
                            {/if}
                                {assign var='value_text' value=$fields_value[$input.name]}
                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                    {/if}
                                    {if isset($input.prefix)}
                                        <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                    {/if}

                                    <!-- text input block -->
                                    <div class="float-left">
                                        <input type="text"
                                               name="{$input.name}"
                                               id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                               value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                               class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                {if isset($input.size)} size="{$input.size}"{/if}
                                                {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                {if isset($input.required) && $input.required } required="required" {/if}
                                                {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                        />
                                    </div>
                                    {if isset($input.suffix)}
                                        <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                    {/if}

                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                </div>
                            {/if}
                            {if isset($input.maxchar) && $input.maxchar}
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                    });
                                </script>
                            {/if}
                            {/if}
                            {/if}
                            {/block}
                            {/foreach}

                        </div>

                    </div>
                </div>

                <div class="form-wrapper">

                    <label class="control-label col-lg-3 font-weight-bold">
                        {l s='Hint' mod='seosaproductlabels'}
                    </label>

                    <div class="col-lg-9">

                        <div class="form-group">
                            <!-- textarea2 -->
                            {foreach $field as $input}
                                {block name="input"}
                                    {if $input.type == 'textarea'}
                                        {if isset($input.maxchar) && $input.maxchar}<div class="input-group">{/if}
                                        {assign var=use_textarea_autosize value=true}
                                        {if isset($input.lang) AND $input.lang}
                                        {foreach $languages as $language}
                                        {if $languages|count > 1}
                                            <!-- form-group translatable-field -->
                                            <div class="form-group translatable-field lang-{$language.id_lang}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                                            <div class="col-lg-11">
                                        {/if}
                                            {if isset($input.maxchar) && $input.maxchar}
                                                <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
															<span class="text-count-down">{$input.maxchar|intval}</span>
														</span>
                                            {/if}
                                            <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name}_{$language.id_lang}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_{$language.id_lang}" class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}</textarea>
                                        {if $languages|count > 1}
                                            </div>
                                            <div class="col-lg-1">
                                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                    {$language.iso_code}
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    {foreach from=$languages item=language}
                                                        <li>
                                                            <a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                            </div>
                                        {/if}
                                        {/foreach}
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    {foreach from=$languages item=language}
                                                    countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                    {/foreach}
                                                });
                                            </script>
                                        {/if}
                                        {else}
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
												<span class="text-count-down">{$input.maxchar|intval}</span>
											</span>
                                        {/if}
                                            <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name}" id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}" {if isset($input.cols)}cols="{$input.cols}"{/if} {if isset($input.rows)}rows="{$input.rows}"{/if} class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                                });
                                            </script>
                                        {/if}
                                        {/if}
                                        {if isset($input.maxchar) && $input.maxchar}</div>{/if}
                                    {/if}
                                {/block}
                            {/foreach}
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-5">
                                    {foreach $field as $input}
                                        {block name="label"}
                                            {if $input.type == 'color'}
                                                {if isset($input.label)}
                                                    <label class="control-label float-left margin-right">
                                                        {$input.label}
                                                    </label>
                                                {/if}
                                            {/if}
                                        {/block}
                                        {block name="input"}
                                            {if $input.type == 'color'}
                                                <div class="row input-group fixed-width-md">
												
                                                    <input type="color"
                                                           data-hex="true"
                                                            {if isset($input.class)} class="{$input.class}"
                                                            {else} class="color mColorPickerInput"{/if}
                                                           name="{$input.name}"
                                                           value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>
                                <div class="col-lg-6">
                                    {foreach $field as $input}
                                        {block name="label"}
                                            {if $input.type == 'color_hint_background'}
                                                {if isset($input.label)}
                                                    <label class="control-label float-left margin-right">
                                                        {$input.label}
                                                    </label>
                                                {/if}
                                            {/if}
                                        {/block}
                                        {block name="input"}
                                            {if $input.type == 'color_hint_background'}
                                                <div class="row input-group fixed-width-md">
                                                    <input type="color"
                                                           data-hex="true"
                                                            {if isset($input.class)} class="{$input.class}"
                                                            {else} class="color mColorPickerInput"{/if}
                                                           name="{$input.name}"
                                                           value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                </div>
                                            {/if}
                                        {/block}
                                    {/foreach}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">

                            <!-- text_name -->
                            {foreach $field as $input}
                            {block name="label"}
                            {if $input.type == 'text_hint_opacity'}
                            {if isset($input.label)}
                                <!-- label hint -->
                                <div class="float-left">
                                    <label class="control-label margin-right">
                                        {$input.label}
                                    </label>
                                </div>
                            {/if}
                            {/if}
                            {/block}

                            {block name="input"}
                                {if $input.type == 'text_hint_opacity'}
                                    {if isset($input.label)}
                                        <!-- text_hint_opacity -->
                                        <div class="col-lg-3">
                                    {/if}
                                {/if}
                            {if $input.type == 'text_hint_opacity'}
                            {if isset($input.lang) AND $input.lang}
                            {if $languages|count > 1}
                                <div class="form-group">
                                    {/if}
                                    {foreach $languages as $language}
                                        {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                        {if $languages|count > 1}
                                            <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                            <div class="col-lg-9">
                                        {/if}
                                        {if $input.type == 'tags'}
                                        {literal}
                                            <script type="text/javascript">
                                                $().ready(function () {
                                                    var input_id = '{/literal}{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}{literal}';
                                                    $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1}{literal}'});
                                                    $({/literal}'#{$table}{literal}_form').submit( function() {
                                                        $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                                    });
                                                });
                                            </script>
                                        {/literal}
                                        {/if}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                    {/if}
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <span id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter" class="input-group-addon">
													<span class="text-count-down">{$input.maxchar|intval}</span>
												</span>
                                        {/if}
                                        {if isset($input.prefix)}
                                            <span class="input-group-addon">
													  {$input.prefix}
													</span>
                                        {/if}
                                        <input type="text"
                                               id="{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"
                                               name="{$input.name}_{$language.id_lang}"
                                               class="{if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                               value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                               onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                {if isset($input.size)} size="{$input.size}"{/if}
                                                {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                {if isset($input.required) && $input.required} required="required" {/if}
                                                {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
                                        {if isset($input.suffix)}
                                            <span class="input-group-addon">
													  {$input.suffix}
													</span>
                                        {/if}
                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                        </div>
                                    {/if}
                                        {if $languages|count > 1}
                                            </div>
                                            <div class="col-lg-2">
                                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                    {$language.iso_code}
                                                    <i class="icon-caret-down"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    {foreach from=$languages item=language}
                                                        <li><a href="javascript:hideOtherLanguage({$language.id_lang});" tabindex="-1">{$language.name}</a></li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    <!-- Hint opacity -->
                                    <div>
                                        {if isset($input.maxchar) && $input.maxchar}
                                            <script type="text/javascript">
                                                $(document).ready(function(){
                                                    {foreach from=$languages item=language}
                                                    countDown($("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}"), $("#{if isset($input.id)}{$input.id}_{$language.id_lang}{else}{$input.name}_{$language.id_lang}{/if}_counter"));
                                                    {/foreach}
                                                });
                                            </script>
                                        {/if}
                                    </div>

                                    {if $languages|count > 1}
                                </div>
                            {/if}
                                {else}
                            {if $input.type == 'tags'}
                            {literal}
                                <script type="text/javascript">
                                    $().ready(function () {
                                        var input_id = '{/literal}{if isset($input.id)}{$input.id}{else}{$input.name}{/if}{literal}';
                                        $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag'}{literal}'});
                                        $({/literal}'#{$table}{literal}_form').submit( function() {
                                            $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                        });
                                    });
                                </script>
                            {/literal}
                            {/if}
                                {assign var='value_text' value=$fields_value[$input.name]}
                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                <div class="input-group{if isset($input.class)} {$input.class}{/if}">
                                    {/if}
                                    {if isset($input.maxchar) && $input.maxchar}
                                        <span id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                    {/if}
                                    {if isset($input.prefix)}
                                        <span class="input-group-addon">
										  {$input.prefix}
										</span>
                                    {/if}

                                    <!-- text2 -->
                                    <input type="text"
                                           name="{$input.name}"
                                           id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
                                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           class="text2 {if isset($input.class)}{$input.class}{/if}{if $input.type == 'tags'} tagify{/if}"
                                            {if isset($input.size)} size="{$input.size}"{/if}
                                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                            {if isset($input.required) && $input.required } required="required" {/if}
                                            {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder}"{/if}
                                    />
                                    {if isset($input.suffix)}
                                        <span class="input-group-addon">
										  {$input.suffix}
										</span>
                                    {/if}

                                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                </div>
                            {/if}
                            {if isset($input.maxchar) && $input.maxchar}
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        countDown($("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"), $("#{if isset($input.id)}{$input.id}{else}{$input.name}{/if}_counter"));
                                    });
                                </script>
                            {/if}
                            {/if}
                            {/if}
                            {if $input.name == 'hint_opacity'}
                                <div class="hint_opacity_block">
                                    <script>
                                        $(document).ready(function () {
                                            $("#hint_opacity").ionRangeSlider({
                                                min: 0,
                                                max: 1,
                                                from: $('#hint_opacity').val(),
                                                step: 0.01
                                            });
                                        });
                                    </script>
                                </div>
                            {/if}
                                {if $input.type == 'text_hint_opacity'}
                                    {if isset($input.label)}
                                        </div>
                                    {/if}
                                {/if}
                            {/block}
                            {/foreach}
                        </div>
                    </div>

                </div>

            {elseif $key == 'desc'}
                <div class="alert alert-info col-lg-offset-3">
                    {if is_array($field)}
                        {foreach $field as $k => $p}
                            {if is_array($p)}
                                <span{if isset($p.id)} id="{$p.id}"{/if}>{$p.text}</span><br />
                            {else}
                                {$p}
                                {if isset($field[$k+1])}<br />{/if}
                            {/if}
                        {/foreach}
                    {else}
                        {$field}
                    {/if}
                </div>
            {/if}
            {block name="other_input"}{/block}
        {/foreach}
        {block name="footer"}
            {capture name='form_submit_btn'}{counter name='form_submit_btn'}{/capture}
            {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
                <div class="panel-footer">
                    {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary btn-lg pull-right{/if}">
                            {$fieldset['form']['submit']['title']}
                        </button>
                    {/if}
                    {if isset($show_cancel_button) && $show_cancel_button}
                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-outline-secondary btn-lg" onclick="window.history.back();">
                            {l s='Cancel' mod='seosaproductlabels'}
                        </a>
                    {/if}
                    {if isset($fieldset['form']['reset'])}
                        <button
                                type="reset"
                                id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']}{else}{$table}_form_reset_btn{/if}"
                                class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']}{else}btn btn-default{/if}"
                        >
                            {if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']}"></i> {/if} {$fieldset['form']['reset']['title']}
                        </button>
                    {/if}
                    {if isset($fieldset['form']['buttons'])}
                        {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                            {if isset($btn.href) && trim($btn.href) != ''}
                                <a href="{$btn.href}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</a>
                            {else}
                                <button type="{if isset($btn['type'])}{$btn['type']}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']}{/if}" name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']}" ></i> {/if}{$btn.title}</button>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            {/if}
        {/block}
    </div>
{/block}