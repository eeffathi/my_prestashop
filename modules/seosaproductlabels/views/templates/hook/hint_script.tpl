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

{foreach  from=$seosa_labels item=label}
    <style>
        .seosa_label_hint_{$label['id_product_label']|intval} {
            display: none;
            position: absolute;
            background: {if $label['hint_background']}{$label['hint_background']|escape:'html':'UTF-8'}{else}#000000{/if};
            color: {if $label['hint_text_color']}{$label['hint_text_color']|escape:'html':'UTF-8'}{else}white{/if};
            border-radius: 3px;
            {if $label['position'] == 'top-center' || $label['position'] == 'center-center'}
                top: {$label['image_height']|escape:'html':'UTF-8'};
            {elseif $label['position'] == 'bottom-center'}
                bottom: {$label['image_height']|escape:'html':'UTF-8'};
            {else}
                top: 0;
          {if isset($label['fix_hint_position'])}
                {$label['fix_hint_position']|escape:'html':'UTF-8'}: -10px;
                margin-{$label['fix_hint_position']|escape:'html':'UTF-8'}: -150px;
        {/if}
            {/if}
            z-index: 1000;
            opacity: {if $label['hint_opacity']}{$label['hint_opacity']|floatval}{else}1{/if};
            width: 150px;
            padding: 5px;
        }
        .seosa_label_hint_{$label['id_product_label']|intval}:after {
            border-bottom: solid transparent 7px;
            border-top: solid transparent 7px;
        {if isset($label['fix_hint_position'])}
            border-{$label['fix_hint_position']|escape:'html':'UTF-8'}: solid {if $label['hint_background']}{$label['hint_background']|escape:'html':'UTF-8'}{else}#000000{/if} 10px;
          {/if}
          top: 10%;
            content: " ";
            height: 0;
        {if isset($label['fix_hint_position'])}
            {$label['fix_hint_position']|escape:'html':'UTF-8'}: 100%;
          {/if}
            position: absolute;
            width: 0;
        }
    </style>
{/foreach}