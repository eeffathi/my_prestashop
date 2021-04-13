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

{foreach from=$seosa_product_labels item=product_label}
    {if ($product_label['image_url'] && !$product_label['text']) || $product_label['text']}
      {if  $product_label['quantity'] <=$product_label['quantity_prod'] && $product_label['quantity_max'] >=$product_label['quantity_prod'] || $product_label['quantity'] == ' ' || ! isset($product_label['select_for']) || $product_label['quantity_max'] == ""}
        {if $product_label['max_price'] > $product_label['price'] && $product_label['mini_price'] < $product_label['price'] || $product_label['max_price'] == 0 &&  $product_label['mini_price'] == 0}
            {if $product_label['label_type'] == 'image'}
                <div class="seosa_product_label _catalog {$product_label['position']|escape:'quotes':'UTF-8'}"{if $product_label['label_type'] == 'image' && $product_label['image_css']} style="{$product_label['image_css']|escape:'quotes':'UTF-8'}"{elseif $product_label['label_type'] == 'text' && $product_label['text']} style="width: auto; height: auto;"{/if}>
                    {if $product_label['url']}
                    <a target="_blank" href="{$product_label['url']|escape:'quotes':'UTF-8'}">
                        {/if}
                        <img src="{$product_label['image_url']|escape:'quotes':'UTF-8'}"{if $product_label['image_css']}{/if} alt="{$product_label['name']|escape:'quotes':'UTF-8'}" />
                        {if $product_label['url']}
                    </a>
                    {/if}
                    {if $product_label['hint']}
                        <div class="seosa_label_hint seosa_label_hint_{$product_label['id_product_label']|intval} {if $product_label['id_product']}seosa_label_hint_{$product_label['id_product_label']|intval}_{$product_label['id_product']|intval}{/if}">
                            {$product_label['hint']|cleanHtml nofilter}
                        </div>
                    {/if}
                </div>
            {elseif $product_label['label_type'] == 'text'}
                {if $product_label['text']}
                    <div class="seosa_product_label _catalog {$product_label['position']|escape:'quotes':'UTF-8'}"{if $product_label['label_type'] == 'image' && $product_label['image_css']} style="{$product_label['image_css']|escape:'quotes':'UTF-8'}"{elseif $product_label['label_type'] == 'text' && $product_label['text']} style="width: auto; height: auto;"{/if}>
                        {if $product_label['url']}
                        <a target="_blank" href="{$product_label['url']|escape:'quotes':'UTF-8'}">
                            {/if}
                            <span style="{$product_label['text_css']|escape:'quotes':'UTF-8'}">{$product_label['text']|escape:'quotes':'UTF-8'}</span>
                            {if $product_label['url']}
                        </a>
                        {/if}
                        {if $product_label['hint']}
                            <div class="seosa_label_hint seosa_label_hint_{$product_label['id_product_label']|intval} {if $product_label['id_product']}seosa_label_hint_{$product_label['id_product_label']|intval}_{$product_label['id_product']|intval}{/if}">
                                {$product_label['hint']|cleanHtml nofilter}
                            </div>
                        {/if}
                    </div>
                {/if}
            {/if}
    <style>
        .seosa_label_hint_{$product_label['id_product_label']|intval}_{$product_label['id_product']|intval} {
            {if $product_label['fix_hint_position'] == 'left'}right{else}left{/if}: auto;
        {$product_label['fix_hint_position']|escape:'html':'UTF-8'}: -10px;
            {if $product_label['fix_hint_position'] == 'left'}margin-right{else}margin-left{/if}: 0;
          {if $product_label['position'] != 'top-center' && $product_label['position'] != 'center-center' && $product_label['position'] != 'bottom-center'}
            margin-{$product_label['fix_hint_position']|escape:'html':'UTF-8'}: -150px;
        }
        .seosa_label_hint_{$product_label['id_product_label']|intval}_{$product_label['id_product']|intval}:after {
            border-{$product_label['fix_hint_position']|escape:'html':'UTF-8'}: solid {if isset($product_label['hint_background']) && $product_label['hint_background']}{$product_label['hint_background']|escape:'html':'UTF-8'}{else}#000000{/if} 10px;
        {$product_label['fix_hint_position']|escape:'html':'UTF-8'}: 100%;

            {if $product_label['fix_hint_position'] == 'left'}border-right{else}border-left{/if}: 0;
            {if $product_label['fix_hint_position'] == 'left'}right{else}left{/if}: auto;
        {/if}
        }
    </style>
    {/if}
      {/if}
{/if}
{/foreach}

