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
{include file="$tpl_dir./errors.tpl"}
{if isset($successRegistration) && $successRegistration}
    <div class="alert alert-success">
        {if $affiliate.active}
            {l s='Success! You have just registered as an affiliate.' mod='psaffiliate'}
        {else}
            {l s='Success! You have just registered as an affiliate. Your request will be reviewed soon by an administrator.' mod='psaffiliate'}
        {/if}
    </div>
{/if}
<div id="myaffiliateaccount">
    {capture name=path}
        <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">{l s='My account' mod='psaffiliate'}</a>
        <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
        <span>{l s='My affiliate account' mod='psaffiliate'}</span>
    {/capture}

    {if $affiliate.active}
        <div class="row gap-sm">
            <div class="col-md-3">
                <ul class="nav nav-pills nav-panel nav-stacked">
                    <li{if $current_tab == 'home'} class="active"{/if}><a href="#myaffiliateaccount-home"
                                                                          data-toggle="tab">{l s='My affiliate account' mod='psaffiliate'}</a>
                    </li>
                    <li><a href="#myaffiliateaccount-summary" data-toggle="tab">{l s='Summary' mod='psaffiliate'}</a>
                    </li>
                    <li><a href="#myaffiliateaccount-campaigns-list"
                           data-toggle="tab">{l s='Campaigns' mod='psaffiliate'}</a></li>
                    <li{if $current_tab == 'products'} class="active"{/if}><a
                                href="#myaffiliateaccount-product-commissions"
                                data-toggle="tab">{l s='Products & commisions' mod='psaffiliate'}</a></li>
                    <li{if $current_tab == 'sales'} class="active"{/if}><a href="#myaffiliateaccount-sales-commissions"
                                                                           data-toggle="tab">{l s='Sales & commisions' mod='psaffiliate'}</a>
                    </li>
                    {if $voucher_payments_enabled}
                        <li><a href="#myaffiliateaccount-myvouchers"
                               data-toggle="tab">{l s='My vouchers' mod='psaffiliate'}</a></li>
                    {/if}
                    {if $voucher_to_share_enabled}
                        <li><a href="#myaffiliateaccount-voucherstoshare"
                               data-toggle="tab">{l s='Vouchers to share' mod='psaffiliate'}</a></li>
                    {/if}
                    {if $lifetime_affiliates_enabled}
                        <li><a href="#myaffiliateaccount-mylifetimeaffiliations"
                               data-toggle="tab">{l s='My lifetime affiliations' mod='psaffiliate'}</a></li>
                    {/if}
                    {if $mlm_enable}
                        <li><a href="#myaffiliateaccount-mlm"
                               data-toggle="tab">{l s='Multi level marketing' mod='psaffiliate'}</a>
                        </li>
                    {/if}
                </ul>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane{if $current_tab == 'home'} active{/if}" id="myaffiliateaccount-home">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix text-center-xs">
                                <h2 class="h3 m-t-sm m-b-sm pull-left pull-none-xs">{l s='My affiliate account' mod='psaffiliate'}</h2>
                                <div class="pull-right pull-none-xs m-t-xs">
                                    {if $hasTexts}
                                        <a href="{$link->getModuleLink('psaffiliate', 'texts', array(), true)|escape:'html':'UTF-8'}"
                                           class="btn btn-default m-t-xs"><i
                                                    class="icon-font"></i> {l s='Text Ads' mod='psaffiliate'}</a>
                                    {/if}
                                    {if $hasBanners}
                                        <a href="{$link->getModuleLink('psaffiliate', 'banners', array(), true)|escape:'html':'UTF-8'}"
                                           class="btn btn-default m-t-xs"><i
                                                    class="icon-bullhorn"></i> {l s='Banner ads' mod='psaffiliate'}</a>
                                    {/if}
                                </div>
                            </div>
                            <div class="panel-body">
                                <form action="#" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{l s='Your affiliate link' mod='psaffiliate'}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="my_affiliate_link"
                                                       value="{$myaffiliatelink|escape:'html':'UTF-8'}" readonly>
                                                <span class="input-group-btn">
												<button type="button" class="btn btn-default btn-copy"
                                                        data-clipboard-target="#my_affiliate_link"><i
                                                            class="icon-clipboard"></i></button>
											</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{l s='Your affiliate ID' mod='psaffiliate'}</label>
                                        <div class="col-sm-9">
                                            <p class="form-control-static">{$affiliate.id_affiliate|escape:'htmlall':'UTF-8'}</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="myaffiliateaccount-summary">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix text-center-xs">
                                <h3 class="m-t-sm m-b-sm pull-left pull-none-xs">{l s='Summary' mod='psaffiliate'}</h3>
                                {if $affiliate.balance >= $minimum_payment_amount}
                                    <div class="pull-right pull-none-xs m-t-xs">
                                        <a href="{$link->getModuleLink('psaffiliate', 'requestpayment', array(), true)|escape:'html':'UTF-8'}"
                                           class="btn btn-default m-t-xs"><i
                                                    class="icon-credit-card"></i> {l s='Request a payment' mod='psaffiliate'}
                                        </a>
                                        {if $voucher_payments_enabled}
                                            <a href="{$link->getModuleLink('psaffiliate', 'requestvoucherpayment', array(), true)|escape:'html':'UTF-8'}"
                                               class="btn btn-default m-t-xs"><i
                                                        class="icon-gift"></i> {l s='Request a voucher' mod='psaffiliate'}
                                            </a>
                                        {/if}
                                    </div>
                                {/if}
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="h4 font-bold m-b">{l s='Last %s days' mod='psaffiliate' sprintf=$days_current_summary}</div>
                                        <div class="list-group list-group-hover">
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.clicks|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Clicks' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.unique_clicks|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Unique clicks' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.pending_sales|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Pending sales' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.approved_sales|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Approved sales' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="pull-right font-bold">{Psaffiliate::displayPriceOverride(Tools::convertPrice($affiliate.earnings))|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Earnings' mod='psaffiliate'}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="h4 font-bold m-b">{l s='Total' mod='psaffiliate'}</div>
                                        <div class="list-group list-group-hover">
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.clicks_total|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Clicks' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.unique_clicks_total|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Unique clicks' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="badge">{$affiliate.approved_sales_total|escape:'htmlall':'UTF-8'}</span>
                                                <span>{l s='Approved sales' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="pull-right font-bold">{Psaffiliate::displayPriceOverride(Tools::convertPrice($affiliate.earnings_total))|escape:'html':'UTF-8'}</span>
                                                <span>{l s='Total earnings' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="pull-right font-bold">{Psaffiliate::displayPriceOverride(Tools::convertPrice($affiliate.payments))|escape:'html':'UTF-8'}</span>
                                                <span>{l s='Total payments' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="pull-right font-bold">{Psaffiliate::displayPriceOverride(Tools::convertPrice($affiliate.pending_payments))|escape:'html':'UTF-8'}</span>
                                                <span>{l s='Pending payments' mod='psaffiliate'}</span>
                                            </div>
                                            <div class="list-group-item">
                                                <span class="pull-right font-bold">{Psaffiliate::displayPriceOverride(Tools::convertPrice($affiliate.balance))|escape:'html':'UTF-8'}</span>
                                                <span>{l s='Balance' mod='psaffiliate'}</span>
                                                {if isset($affiliate.balance) && $affiliate.balance}
                                                    {$barBalance = {$affiliate.balance} / {$minimum_payment_amount} * 100}
                                                    {if $barBalance > 100}
                                                        {$barBalance = 100}
                                                    {/if}
                                                    <div class="progress" style="margin-bottom: 0; margin-top: 5px;" data-toggle="tooltip" data-placement="top" title="{l s='Minimum checkout value progress' mod='psaffiliate'}">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$barBalance}"
                                                             aria-valuemin="0" aria-valuemax="{$minimum_payment_amount}" style="width:{$barBalance}%; background-color: #5cb85c;">
                                                            {$barBalance}% {l s='Complete' mod='psaffiliate'}
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="myaffiliateaccount-campaigns-list">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix text-center-xs">
                                <h3 class="m-t-sm m-b-sm pull-left pull-none-xs">{l s='Campaigns' mod='psaffiliate'}</h3>
                                <div class="pull-right pull-none-xs m-t-xs">
                                    <a href="{$link->getModuleLink('psaffiliate', 'campaign', array(), true)|escape:'html':'UTF-8'}"
                                       class="btn btn-default m-t-xs"><i
                                                class="icon-plus"></i> {l s='Create a campaign' mod='psaffiliate'}</a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <p class="subtitle">
                                    {if sizeof($campaigns)}
                                        {l s='You have %s campaigns' sprintf=sizeof($campaigns) mod='psaffiliate'}
                                    {else}
                                        {l s='You do not have any campaign' mod='psaffiliate'}
                                    {/if}
                                </p>
                            </div>
                            {if sizeof($campaigns)}
                                <div class="table-responsive m-n">
                                    <table id="campaigns" class="table table-hover table-striped b-t m-n">
                                        <thead>
                                        <tr class="text-sm">
                                            <th>{l s='ID' mod='psaffiliate'}</th>
                                            <th>{l s='Name' mod='psaffiliate'}</th>
                                            <th>{l s='Link' mod='psaffiliate'}</th>
                                            <th class="text-center">{l s='Clicks' mod='psaffiliate'}</th>
                                            <th class="text-center">{l s='Approved sales' mod='psaffiliate'}</th>
                                            <th class="text-right">{l s='Total earnings' mod='psaffiliate'}</th>
                                            <th class="text-center">{l s='Details' mod='psaffiliate'}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$campaigns item=campaign}
                                            <tr>
                                                <td class="text-center">{$campaign.id_campaign|escape:'htmlall':'UTF-8'}</td>
                                                <td>{$campaign.name|escape:'htmlall':'UTF-8'}</td>
                                                <td style="min-width: 200px;">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                               id="my_affiliate_link_{$campaign.id_campaign|escape:'htmlall':'UTF-8'}"
                                                               value="{$campaign.campaign_link|escape:'htmlall':'UTF-8'}"
                                                               readonly>
                                                        <span class="input-group-btn">
														<button type="button" class="btn btn-default btn-copy"
                                                                data-clipboard-target="#my_affiliate_link_{$campaign.id_campaign|escape:'htmlall':'UTF-8'}"><i
                                                                    class="icon-clipboard"></i></button>
													</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">{$campaign.clicks|escape:'htmlall':'UTF-8'}</td>
                                                <td class="text-center">{$campaign.sales|escape:'htmlall':'UTF-8'}</td>
                                                <td class="text-right">{Psaffiliate::displayPriceOverride(Tools::convertPrice($campaign.total_earnings_clicks + $campaign.total_earnings_sales))|escape:'html':'UTF-8'}</td>
                                                <td class="text-center"><a
                                                            href='{$campaigns_links[$campaign.id_campaign]|escape:'html':'UTF-8'}'
                                                            class="btn btn-default" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{l s='More info' mod='psaffiliate'}"><i
                                                                class="icon-info-circle icon-lg"></i></a></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {/if}
                        </div>
                    </div>
                    <div class="tab-pane{if $current_tab == 'products'} active{/if}"
                         id="myaffiliateaccount-product-commissions">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix text-center-xs">
                                <h3 class="m-t-sm m-b-sm pull-left pull-none-xs">{l s='Products & commisions listing' mod='psaffiliate'}</h3>
                            </div>
                            <div class="panel-body">
                                <form action="{$link->getModuleLink('psaffiliate', 'myaccount', array(), true)}"
                                      method="get">
                                    <input type="hidden" name="t" value="products">
                                    <input type="hidden" name="p" value="1">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="s"
                                               placeholder="{l s='Filter products by the name or reference' mod='psaffiliate'}"
                                               value="{if $search_terms}{$search_terms|escape:'html':'UTF-8'}{/if}">
                                        <span class="input-group-btn">
										<button type="submit" class="btn btn-primary btn-sm"><i class="icon-search"></i></button>
									</span>
                                    </div>

                                </form>
                            </div>
                            {if ! $product_commisions}
                                <div class="panel-body">
                                    <div class="alert alert-warning">{l s='No products found.' mod='psaffiliate'}</div>
                                </div>
                            {else}
                                <table class="table table-hover table-striped b-t m-n">
                                    <thead>
                                    <tr>
                                        <th style="width: 1px;">{l s='Cover' mod='psaffiliate'}</th>
                                        <th>{l s='Name / Affiliate link' mod='psaffiliate'}</th>
                                        <th class="text-center">{l s='Reference' mod='psaffiliate'}</th>
                                        <th class="text-center">{l s='Commission' mod='psaffiliate'}</th>
                                        {if $show_cost}
                                            <th class="text-center">{l s='Product Cost' mod='psaffiliate'}</th>
                                        {/if}
                                        {if $show_percent}
                                            <th class="text-center">{l s='Commission Rate' mod='psaffiliate'}</th>
                                        {/if}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {foreach $product_commisions as $product}
                                        <tr>
                                            <td style="width: 1px;" class="v-middle">
                                                {if $product.image}
                                                    <a href="{$product.link|escape:'html':'UTF-8'}" target="_blank">
                                                        <img src="{$product.image|escape:'html':'UTF-8'}"
                                                             alt="{$product.name|escape:'html':'UTF-8'}">
                                                    </a>
                                                {/if}
                                            </td>
                                            <td class="v-middle">
                                                <div class="h4">
                                                    <a href="{$product.link}"
                                                       target="_blank">{$product.name|escape:'html':'UTF-8'}</a>
                                                </div>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                           id="product_affiliate_link_{$product.id_product|escape:'html':'UTF-8'}"
                                                           value="{$product.aff_link|escape:'html':'UTF-8'}"
                                                           readonly>
                                                    <span class="input-group-btn">
														<button type="button" class="btn btn-primary btn-sm btn-copy"
                                                                data-clipboard-target="#product_affiliate_link_{$product.id_product|escape:'html':'UTF-8'}"><i
                                                                    class="material-icons">filter_none</i></button>
													</span>
                                                </div>
                                            </td>
                                            <td class="v-middle text-center">
                                                {if $product.reference}
                                                    {$product.reference|escape:'html':'UTF-8'}
                                                {else}
                                                    <span class="text-muted">n/a</span>
                                                {/if}
                                            </td>
                                            <td class="text-center v-middle">
                                                <strong>{$product.commision|escape:'html':'UTF-8'}</strong>
                                            </td>
                                            {if $show_cost}
                                                <td class="text-center v-middle">
                                                    <strong>{$product.cost|escape:'html':'UTF-8'}</strong>
                                                </td>
                                            {/if}
                                            {if $show_percent}
                                                <td class="text-center v-middle">
                                                    <strong>{$product.commission_rate|escape:'html':'UTF-8'} %</strong>
                                                </td>
                                            {/if}
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                                {if count($product_commisions_pages) > 1}
                                    <div class="panel-body">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                {if $current_page != 1}
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'products', 'p' => 1, 's' => $search_terms], true)|escape:'html':'UTF-8'}">
                                                            <span>&laquo;</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                                {foreach $product_commisions_pages as $page}
                                                    <li class="page-item{if $current_page == $page} active{/if}">
                                                        <a class="page-link"
                                                           href="{if $page == '...'}javascript:void(0);{else}{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'products', 'p' => $page, 's' => $search_terms], true)|escape:'html':'UTF-8'}{/if}">{$page|escape:'html':'UTF-8'}</a>
                                                    </li>
                                                {/foreach}
                                                {if $current_page != $product_commisions_last}
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'products', 'p' => $product_commisions_last, 's' => $search_terms], true)|escape:'html':'UTF-8'}">
                                                            <span>&raquo;</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </nav>
                                    </div>
                                {/if}
                            {/if}
                        </div>
                    </div>

                    <div class="tab-pane{if $current_tab == 'sales'} active{/if}"
                         id="myaffiliateaccount-sales-commissions">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix text-center-xs">
                                <h3 class="m-t-sm m-b-sm">{l s='My sales & commissions' mod='psaffiliate'}</h3>
                            </div>
                            {if ! $sale_commissions}
                                <div class="panel-body">
                                    <div class="alert alert-warning">{l s='You haven\'t recieved any commisions yet.' mod='psaffiliate'}</div>
                                </div>
                            {else}
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>{l s='ID' mod='psaffiliate'}</th>
                                        <th>{l s='Date' mod='psaffiliate'}</th>
                                        <th>{l s='Order total' mod='psaffiliate'}</th>
                                        <th>{l s='Your commission' mod='psaffiliate'}</th>
                                        <th class="text-center">{l s='Approved' mod='psaffiliate'}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {foreach $sale_commissions as $commission}
                                        <tr>
                                            <td class="v-middle">#{$commission.id_sale|escape:'html':'UTF-8'}</td>
                                            <td class="v-middle">{$commission.date|escape:'html':'UTF-8'}</td>
                                            <td class="v-middle">{$commission.order_total|escape:'html':'UTF-8'}</td>
                                            <td class="v-middle">{$commission.commission|escape:'html':'UTF-8'}</td>
                                            <td class="v-middle text-center">
                                                {if $commission.approved}
                                                    <span class="label label-success"><i
                                                                class="icon-check">&nbsp;</i></span>
                                                {else}
                                                    <span class="label label-danger"><i
                                                                class="icon-times">&nbsp;</i></span>
                                                {/if}
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                                {if count($sale_commissions_pages) > 1}
                                    <div class="panel-body">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                {if $current_page != 1}
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'sales', 'p' => 1], true)|escape:'html':'UTF-8'}">
                                                            <span>&laquo;</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                                {foreach $sale_commissions_pages as $page}
                                                    <li class="page-item{if $current_page == $page} active{/if}">
                                                        <a class="page-link"
                                                           href="{if $page == '...'}javascript:void(0);{else}{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'sales', 'p' => $page], true)|escape:'html':'UTF-8'}{/if}">{$page|escape:'html':'UTF-8'}</a>
                                                    </li>
                                                {/foreach}
                                                {if $current_page != $sale_commissions_last}
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                           href="{$link->getModuleLink('psaffiliate', 'myaccount', ['t' => 'sales', 'p' => $sale_commissions_last], true)|escape:'html':'UTF-8'}">
                                                            <span>&raquo;</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </nav>
                                    </div>
                                {/if}
                            {/if}
                        </div>
                    </div>

                    {if $voucher_payments_enabled}
                        <div class="tab-pane" id="myaffiliateaccount-myvouchers">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix text-center-xs">
                                    <h3 class="m-t-sm m-b-sm">{l s='My payment vouchers' mod='psaffiliate'}</h3>
                                </div>
                                {if ! $vouchers}
                                    <div class="panel-body">
                                        <div class="alert alert-warning">{l s='No payment vouchers requested yet.' mod='psaffiliate'}</div>
                                    </div>
                                {else}
                                    <table class="table table-hover table-striped b-t m-n">
                                        <thead>
                                        <tr>
                                            <th>{l s='Created at' mod='psaffiliate'}</th>
                                            <th>{l s='Code' mod='psaffiliate'}</th>
                                            <th>{l s='Amount' mod='psaffiliate'}</th>
                                            <th class="text-center">{l s='Approved' mod='psaffiliate'}</th>
                                            <th class="text-center">{l s='Used' mod='psaffiliate'}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach $vouchers as $voucher}
                                            <tr>
                                                <td>{$voucher->date_add|escape:'html':'UTF-8'}</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                               id="my_voucher_{$voucher->id|escape:'htmlall':'UTF-8'}"
                                                               value="{$voucher->code|escape:'html':'UTF-8'}" readonly>
                                                        <span class="input-group-btn">
															<button type="button" class="btn btn-default btn-copy"
                                                                    data-clipboard-target="#my_voucher_{$voucher->id|escape:'htmlall':'UTF-8'}"><i
                                                                        class="icon-clipboard"></i></button>
														</span>
                                                    </div>
                                                </td>
                                                <td>{Psaffiliate::displayPriceOverride($voucher->reduction_amount, intval($voucher->reduction_currency))|escape:'html':'UTF-8'}</td>
                                                <td class="text-center">
                                                    {if $voucher->active}
                                                        <span class="label label-success"><i
                                                                    class="icon-check">&nbsp;</i></span>
                                                    {else}
                                                        <span class="label label-danger"><i
                                                                    class="icon-times">&nbsp;</i></span>
                                                    {/if}
                                                </td>
                                                <td class="text-center">
                                                    {if ! $voucher->quantity}
                                                        <span class="label label-success"><i
                                                                    class="icon-check">&nbsp;</i></span>
                                                    {else}
                                                        <span class="label label-danger"><i
                                                                    class="icon-times">&nbsp;</i></span>
                                                    {/if}
                                                </td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                {/if}
                            </div>
                        </div>
                    {/if}
                    {if $voucher_to_share_enabled}
                        <div class="tab-pane" id="myaffiliateaccount-voucherstoshare">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix text-center-xs">
                                    <h3 class="m-t-sm m-b-sm pull-left pull-none-xs">{l s='Vouchers to share' mod='psaffiliate'}</h3>
                                    {if $hasVouchersToShare}
                                        <div class="pull-right pull-none-xs m-t-xs">
                                            <a href="{$link->getModuleLink('psaffiliate', 'voucherstoshare', ['add_voucher' => true], true)|escape:'html':'UTF-8'}"
                                               class="btn btn-default m-t-xs"><i
                                                        class="icon-plus"></i> {l s='Create a voucher' mod='psaffiliate'}</a>
                                        </div>
                                    {/if}
                                </div>
                                <div class="alert alert-info">{l s='In this area you see and can create vouchers to share. Customers who use your vouchers will be considered affiliated by you.' mod='psaffiliate'}</div>
                                {if !$vouchers_to_share}
                                    <div class="alert alert-warning">{l s='You have created no voucher yet.' mod='psaffiliate'}</div>
                                {else}
                                    <table class="table table-hover table-striped b-t m-n">
                                        <thead>
                                        <tr>
                                            <th>{l s='Created at' mod='psaffiliate'}</th>
                                            <th>{l s='Name' mod='psaffiliate'}</th>
                                            <th>{l s='Last used' mod='psaffiliate'}</th>
                                            <th>{l s='Sales' mod='psaffiliate'}</th>
                                            <th>{l s='Commission earned' mod='psaffiliate'}</th>
                                            <th>{l s='Details' mod='psaffiliate'}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach $vouchers_to_share as $voucher}
                                            <tr>
                                                <td>{$voucher->date_add|date_format|escape:'html':'UTF-8'}</td>
                                                <td>
                                                    {$voucher->name|escape:'htmlall':'UTF-8'}<br />
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                               id="voucher_to_share_{$voucher->id|escape:'htmlall':'UTF-8'}"
                                                               value="{$voucher->code|escape:'htmlall':'UTF-8'}"
                                                               readonly>
                                                        <span class="input-group-btn">
														<button type="button" class="btn btn-default btn-copy"
                                                                data-clipboard-target="#voucher_to_share_{$voucher->id|escape:'htmlall':'UTF-8'}"><i
                                                                    class="icon-clipboard"></i></button>
													</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {if $voucher->date_lastused}
                                                        {dateFormat date=$voucher->date_lastused full=1}
                                                    {else}
                                                        {l s='never' mod='psaffiliate'}
                                                    {/if}
                                                </td>
                                                <td>{$voucher->sales_approved|escape:'htmlall':'UTF-8'} / {$voucher->sales|escape:'htmlall':'UTF-8'}</td>
                                                <td>{Psaffiliate::displayPriceOverride(Tools::convertPrice($voucher->total_earnings_approved, $currency))|escape:'htmlall':'UTF-8'} / {Psaffiliate::displayPriceOverride(Tools::convertPrice($voucher->total_earnings, $currency))|escape:'htmlall':'UTF-8'}</td>
                                                <td class="text-center"><a
                                                            href='{$link->getModuleLink('psaffiliate', 'voucherstoshare', ['id_vts' => $voucher->id], true)|escape:'html':'UTF-8'}'
                                                            class="btn btn-default" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="{l s='More info' mod='psaffiliate'}"><i
                                                                class="icon-info-circle icon-lg"></i></a></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                {/if}
                            </div>
                        </div>
                    {/if}
                    {if $lifetime_affiliates_enabled}
                        <div class="tab-pane" id="myaffiliateaccount-mylifetimeaffiliations">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix text-center-xs">
                                    <h3 class="m-t-sm m-b-sm">{l s='My lifetime affiliations' mod='psaffiliate'}</h3>
                                </div>
                                {if ! $lifetime_affiliations}
                                    <div class="panel-body">
                                        <div class="alert alert-warning">{l s='You do not have any lifetime affiliations yet.' mod='psaffiliate'}</div>
                                    </div>
                                {else}
                                    <table class="table table-hover table-striped b-t m-n">
                                        <thead>
                                        <tr>
                                            <th>{l s='Date' mod='psaffiliate'}</th>
                                            <th>{l s='Customer name' mod='psaffiliate'}</th>
                                            <th>{l s='Total commission made' mod='psaffiliate'}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach $lifetime_affiliations as $row}
                                            <tr>
                                                <td>{dateFormat date=$row.date|escape:'html':'UTF-8' full=0}</td>
                                                <td>{$row.customer_name|escape:'html':'UTF-8'}</td>
                                                <td>{Psaffiliate::displayPriceOverride(Tools::convertPrice($row.commission))}</td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                {/if}
                            </div>
                        </div>
                    {/if}
                    {if $mlm_enable}
                        <div class="tab-pane" id="myaffiliateaccount-mlm">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix text-center-xs">
                                    <h3 class="m-t-sm m-b-sm">{l s='Multi level marketing' mod='psaffiliate'}</h3>
                                </div>
                                <div class="panel-body">
                                    {if $mlm_rates}
                                        <h4>{l s='MLM Rates' mod='psaffiliate'}</h4>
                                        <p class="alert alert-info">{l s='Here are the commission percents you will get from sales generated by affiliates brought by you. You will get X% from what they\'ve got.' mod='psaffiliate'}</p>
                                        <p class="alert alert-info">{l s='Level 1 - affiliates brought directly by you' mod='psaffiliate'}<br />{l s='Level 2 - affiliates brought by your Level 1 affiliates' mod='psaffiliate'}<br />{l s='Level 3 - affiliates brought by your Level 2 affiliates' mod='psaffiliate'} {l s='etc.' mod='psaffiliate'}</p>
                                        <table class="table table-hover table-striped b-t m-n">
                                            <thead>
                                            <tr>
                                                <th>{l s='Level' mod='psaffiliate'}</th>
                                                <th>{l s='Commission percent' mod='psaffiliate'}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {foreach $mlm_rates as $key => $val}
                                                {if $val > 0}
                                                    <tr>
                                                        <td>{l s='Level %s' mod='psaffiliate' sprintf=[$key]}</td>
                                                        <td>{$val}%</td>
                                                    </tr>
                                                {/if}
                                            {/foreach}
                                            </tbody>
                                        </table>
                                    {/if}
                                    {$parent_aff_label = '-'}
                                    {if isset($mlm_parent_info) && $mlm_parent_info && $mlm_parent_info.id_parent_affiliate}
                                        {$parent_aff_label = '#'|cat:{$mlm_parent_info.id_parent_affiliate}|cat:' - '|cat:{$mlm_parent_info.affiliate_name}}
                                    {/if}
                                    <br />
                                    <p class="alert alert-info">{l s='The affiliate who brought you here: %s' mod='psaffiliate' sprintf=[$parent_aff_label]}</p>
                                    {if isset($mlm_affiliates)}
                                        {foreach $mlm_affiliates as $level => $affiliates}
                                            {if $affiliates}
                                                <h4>{l s='Affiliates brought by you: Level %s' mod='psaffiliate' sprintf=[$level]}</h4>
                                                <table class="table table-hover table-striped b-t m-n">
                                                    <thead>
                                                    <tr>
                                                        <th>{l s='Affiliate name' mod='psaffiliate'}</th>
                                                        <th>{l s='Brought by' mod='psaffiliate'}</th>
                                                        <th>{l s='Commission generated' mod='psaffiliate'}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {foreach $affiliates as $key => $aff}
                                                        {if $enable_affiliates_details}
                                                        <div id="affiliate_modal_{$aff.id_affiliate}" class="affiliate_modal" style="display:none;">
                                                            <h1>Affiliate Details</h1>
                                                            <span class="aff_details_close">x</span>
                                                            <div class="form-wrapper">
                                                                <div class="form-group row">
                                                                    <label class="control-label col-lg-4"><span class="label-tooltip" title="" >First Name</span></label><div class="col-lg-8"><input type="text" name="firstname" id="firstname" value="{$aff.cust.0.firstname}" class="" disabled></div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="control-label col-lg-4"><span class="label-tooltip" title="" >Last Name</span></label><div class="col-lg-8"><input type="text" name="lastname" id="lastname" value="{$aff.cust.0.lastname}" class="" disabled></div>
                                                                </div>
                                                                <div class="form-group row"><label class="control-label col-lg-4"><span class="label-tooltip" title="">Company</span></label><div class="col-lg-8"><input type="text" name="company" id="company" value="{$aff.cust.0.company}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Address</label><div class="col-lg-8"><input type="text" name="address1" id="address1" value="{$aff.cust.0.address1}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Address (2)</label><div class="col-lg-8"><input type="text" name="address2" id="address2" value="{$aff.cust.0.address2}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Zip/postal code</label><div class="col-lg-4"><input type="text" name="postcode" id="postcode" value="{$aff.cust.0.postcode}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">City</label><div class="col-lg-8"><input type="text" name="city" id="city" value="{$aff.cust.0.city}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Country</label><div class="col-lg-8"><input type="text" name="country" id="country" value="{$aff.cust.0.country}" class="" disabled></div></div>
                                                                <div class="form-group row" id="contains_states"><label class="control-label col-lg-4">State</label><div class="col-lg-8"><input type="text" name="state" id="state" value="{$aff.cust.0.state} " class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Phone</label><div class="col-lg-8"><input type="text" name="phone" id="phone" value="{$aff.cust.0.phone}" class="" disabled></div></div>
                                                                <div class="form-group row"><label class="control-label col-lg-4">Email</label><div class="col-lg-8"><input type="text" name="email" id="email" value="{$aff.cust.0.email}" class="" disabled></div></div>
                                                            </div>
                                                        </div>
                                                        {/if}
                                                        <tr>
                                                            <td>#{$aff.id_affiliate} - {$aff.affiliate_name}</td>
                                                            <td>{if $aff.id_parent_affiliate != $affiliate.id_affiliate}#{$aff.id_parent_affiliate} - {$aff.parent_affiliate_name}{else}{l s='YOU' mod='psaffiliate'}{/if}</td>
                                                            <td>{Psaffiliate::displayPriceOverride(Tools::convertPrice($aff.sales_generated, $currency))}</td>
                                                        </tr>
                                                    {/foreach}
                                                    </tbody>
                                                </table>
                                                <br />
                                            {/if}
                                        {/foreach}
                                    {/if}
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    {elseif !(isset($successRegistration) && $successRegistration)}
        <div class="alert alert-warning"><p>
                {if $affiliate.has_been_reviewed}
                    {l s='Your affiliate account is not active.' mod='psaffiliate'}
                {else}
                    {l s='Your account has not been reviewed by an administrator yet.' mod='psaffiliate'}
                {/if}
            </p></div>
    {/if}
</div>