{*
* 2007-2014 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}
{block name="legend"}
  <div class="panel-heading">
    {if isset($field.image) && isset($field.title)}<img src="{$field.image|escape:'htmlall':'UTF-8'}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
    {if isset($field.icon)}<i class="{$field.icon|escape:'htmlall':'UTF-8'}"></i>{/if}
    {$field.title|escape:'htmlall':'UTF-8'}
  </div>
{/block}
{block name="input_row"}

	{if $input.type == 'checkbox_table'}
  {assign var=all_setings value=$input.values}
  {assign var=id value=$all_setings['id']}
  {assign var=name value=$all_setings['name']}

  {if isset($all_setings) && count($all_setings) > 0}

    <div class="form-group {$input.class_block|escape:'htmlall':'UTF-8'}" {if isset($input.tab)}data-tab-id="{$input.tab|escape:'htmlall':'UTF-8'}"{/if} >
      <label class="control-label col-lg-3">
        <span class="{if $input.hint|escape:'htmlall':'UTF-8'}label-tooltip{else}control-label{/if}" data-toggle="tooltip" data-html="true" title="" data-original-title="{$input.hint|escape:'htmlall':'UTF-8'}">
          {$input.label|escape:'htmlall':'UTF-8'}
        </span>
      </label>
      <div class="col-lg-9">
        <div class="row">
          <div class="col-lg-6">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th class="fixed-width-xs">
                <span class="title_box">
                  <input type="checkbox" name="checkme"  id="checkme" onclick="$(this).parents('.form-group').find('.checkbox_table').prop('checked', this.checked)" />
                 {l s='Select all'  mod='ordersexport'}
                </span>
                </th>
                {if $all_setings['id'] && $name !== 'payment'}
                  <th>
                    <span class="id-box">
                     {l s='ID'  mod='ordersexport'}
                    </span>
                  </th>
                {/if}
                <th>
              <span class="title_box">
                {l s='Name'  mod='ordersexport'}
                {*{var_dump($all_setings)}*}
                {if isset($input.search) && $input.search}
                  <input type="text" class="search_checkbox_table" onkeyup="searchCheckboxtable($(this).parents('table').find('tbody'), $(this).val());return false;">
                {/if}
              </span>
                </th>
              </tr>
              </thead>
              <tbody>
              {foreach $all_setings['query'] as $key => $setings}
                <tr>
                  <td>
                    <input type="checkbox" class="{$input.type|escape:'htmlall':'UTF-8'} {$input.class_input|escape:'htmlall':'UTF-8'}" name="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}" value="{$setings[$id]|escape:'htmlall':'UTF-8'}" {if $fields_value["{$input.name}_{$setings[$id]}"]}checked="checked"{/if}  />
                  </td>
                  {if $all_setings['id'] && $name !== 'payment'}
                    <td>{$setings[$id]|escape:'htmlall':'UTF-8'}</td>
                  {/if}
                  <td>
                    <label for="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}">{$setings[$name]|escape:'htmlall':'UTF-8'}{if isset($all_setings['name2']) && $all_setings['name2']} {$setings[$all_setings['name2']]|escape:'htmlall':'UTF-8'}{/if}</label>
                  </td>
                </tr>
              {/foreach}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
   {/if}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
