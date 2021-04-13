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
<!-- tabs -->
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                {foreach from=$tabs_for key="key" item="tab_for"}
                    <a class="btn {if $product_label->select_for == $tab_for.bind}btn-success{else}btn-default{/if} button-tab {$key|escape:'html':'UTF-8'}" style="width: 100%" data-tab="{$key|escape:'html':'UTF-8'}" data-for="{$tab_for.bind|intval}">{$tab_for.label|escape:'html':'UTF-8'}</a>
                {/foreach}
                <input type="hidden" name="select_for" value="{$product_label->select_for}">
            </div>
            <div class="col-lg-9">
                {foreach from=$tabs_for key="key" item="tab_for"}
                    <div class="tab-content content_{$key|escape:'html':'UTF-8'}" style="display: {if $product_label->select_for == $tab_for.bind}block{else}none{/if}">
                        <h4>{$tab_for.label|escape:'html':'UTF-8'}</h4>
                        <hr>
                        <!-- tab_for content -->
                        {$tab_for.content}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
