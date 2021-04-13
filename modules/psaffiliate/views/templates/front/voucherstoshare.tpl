{*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* You must not modify, adapt or create derivative works of this source code.
*
*  @author Active Design <office@activedesign.ro>
*  @copyright  2017-2018 Active Design
*  @license LICENSE.txt
*}
{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">{l s='My account' mod='psaffiliate'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    <a href="{$link->getModuleLink('psaffiliate', 'myaccount', array(), true)|escape:'html':'UTF-8'}">{l s='My affiliate account' mod='psaffiliate'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    <span>{if $id_voucher_to_share}{l s='Voucher #%s' mod='psaffiliate' sprintf=$id_voucher_to_share}{else}{l s='Create a voucher' mod='psaffiliate'}{/if}</span>
{/capture}
<div id="voucherstoshare">
    {if (!isset($hasErrorNoVoucherFound) || !$hasErrorNoVoucherFound) && (!isset($hasErrorNotYourVoucher) || !$hasErrorNotYourVoucher)}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="h3 m-t-sm m-b-sm">
                    {if $id_voucher_to_share}
                        {l
                        s='Voucher "%s"'
                        sprintf=$voucher.name
                        d='Modules.Psaffiliate.Shop'
                        }
                    {else}
                        {l s='New Voucher' mod='psaffiliate'}
                    {/if}</h2>
            </div>
            <div class="panel-body">
                {if isset($savedSuccess)}
                    {if $savedSuccess}
                        <div class="alert alert-success">{l s='Voucher saved successfully!' mod='psaffiliate'}</div>
                    {else}
                        <div class="alert alert-warning">{l s='Error! Could not save voucher, please try again later.' mod='psaffiliate'}</div>
                    {/if}
                {/if}
                {if isset($voucherTemplateNotExistsError) && $voucherTemplateNotExistsError}
                    <div class="alert alert-warning">{l s='Voucher Template does not exist. Please choose a valid Voucher Template.' mod='psaffiliate'}</div>
                {/if}
                {if isset($voucherTemplateWrongDataError) && $voucherTemplateWrongDataError}
                    <div class="alert alert-warning">{l s='Please make sure to fill all fields with ALPHA-NUMERIC values.' mod='psaffiliate'}</div>
                {/if}
                {if isset($voucherCodeExistsError) && $voucherCodeExistsError}
                    <div class="alert alert-warning">{l s='This Voucher code already exists, please enter another one.' mod='psaffiliate'}</div>
                {/if}
                <form method="POST" class="form-horizontal" id="vouchers_to_share_form">
                    {if !$voucher.id}
                        <div class="form-group row">
                            <p class="voucher_templates_area_title">{l s='Choose a voucher template below:' mod='psaffiliate'}</p>
                            <div class="voucher_templates_area">
                                {foreach from=$voucher_templates item=voucher_template}
                                    <div class="voucher_template {if isset($smarty.post.id_voucher_template) && $smarty.post.id_voucher_template == $voucher_template.id}active{/if}" data-id_voucher_template="{$voucher_template.id|escape:'html':'UTF-8'}" data-voucher_code="{$voucher_template.code}">
                                        <p><strong>{$voucher_template.name|escape:'html':'UTF-8'}</strong></p>
                                        {if trim($voucher_template.description)}
                                            <p>{$voucher_template.description|escape:'html':'UTF-8'|nl2br}</p>
                                        {/if}
                                        <p><small>{$voucher_template.date_from|date_format|escape:'html':'UTF-8'} - {$voucher_template.date_to|date_format|escape:'html':'UTF-8'}</small></p>
                                    </div>
                                {/foreach}
                            </div>
                            <input type="hidden" name="id_voucher_template" id="id_voucher_template" value="" />
                        </div>
                    {/if}
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3"
                               for="name">{l s='Voucher code' mod='psaffiliate'}</label>
                        <div class="col-sm-5 col-md-4">
                            <div class="input-group">
                                <div class="input-group-addon {if $voucher.code_prefix}voucher_hasprefix{/if}" {if !$voucher.code_prefix}style="display: none;"{/if}>
                                    <span class="input-group-text" id="voucher_code_prefix">{if $voucher.code_prefix}{$voucher.code_prefix|escape:'htmlall':'UTF-8'}_{/if}</span>
                                </div>
                                <input type="text" aria-describedby="voucher_code_prefix" name="voucher_code" id="voucher_code" class="form-control" value="{if isset($smarty.post.voucher_code)}{$smarty.post.voucher_code|escape:'htmlall':'UTF-8'}{else}{$voucher.code_noprefix|escape:'htmlall':'UTF-8'}{/if}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3"
                               for="description">{l s='Voucher name' mod='psaffiliate'}</label>
                        <div class="col-sm-5 col-md-4">
                            <input type="text" name="voucher_name" id="voucher_name"
                                   value="{if isset($smarty.post.voucher_name)}{$smarty.post.voucher_name|escape:'htmlall':'UTF-8'}{else}{$voucher.name|escape:'htmlall':'UTF-8'}{/if}" class="form-control" required />
                        </div>
                    </div>
                    {if $id_voucher_to_share}
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Description' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{$voucher.description|escape:'html':'UTF-8'}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Date created' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{$voucher.date_add|date_format|escape:'html':'UTF-8'}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Date last used' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{$voucher.date_lastused|date_format|escape:'html':'UTF-8'}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Approved sales' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{l s='%s sales' sprintf=$voucher.sales_approved|escape:'htmlall':'UTF-8' mod='psaffiliate'}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Total sales' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{l s='%s sales' sprintf=$voucher.sales|escape:'htmlall':'UTF-8' mod='psaffiliate'}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">{l s='Approved earnings' mod='psaffiliate'}</label>
                            <div class="col-sm-5 col-md-4">
                                <div class="form-control-static">{Psaffiliate::displayPriceOverride(Tools::convertPrice($voucher.total_earnings_approved, $currency))}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label class="col-form-label col-sm-3">{l s='Total earnings' mod='psaffiliate'}</label>
                        <div class="col-sm-5 col-md-4">
                            <div class="form-control-static">{Psaffiliate::displayPriceOverride(Tools::convertPrice($voucher.total_earnings, $currency))}</div>
                        </div>
                    </div>
                    {/if}
                    <input type="hidden" name="submitSaveVoucher" value="1"/>
                    <input type="hidden" name="id_vts" id="id_vts" value="{$voucher.id|escape:'htmlall':'UTF-8'}" />
                    <div class="row">
                        <div class="offset-sm-3 col-sm-5 col-md-4 m-t">
                            <button type="submit"
                                    class="btn btn-lg btn-success btn-block" id="vouchers_to_share_form_submit">{l s='Save' mod='psaffiliate'}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {else}
        <div class="alert alert-warning">
            {if isset($hasErrorNoVoucherFound) && $hasErrorNoVoucherFound}
                {l s='Voucher not found' mod='psaffiliate'}
            {elseif isset($hasErrorNotYourVoucher) && $hasErrorNotYourVoucher}
                {l s='This is not your voucher, nice try.' mod='psaffiliate'}
            {/if}
        </div>
    {/if}
</div>