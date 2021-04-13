{*
* NOTICE OF LICENSE.
*
* This source file is subject to a commercial license from BSofts.
* Use, copy, modification or distribution of this source file without written
* license agreement from the BSofts is strictly forbidden.
*
*  @author    BSoft Inc
*  @copyright 2020 BSoft Inc.
*  @license   Commerical License
*}

<div class="panel">
	<h3><i class="icon icon-info"></i> {l s='Info' mod='google_address'}</h3>
	<p>
		<stroong>{l s='To get a Google API Key follow these steps.' mod='google_address'}</strong>
		<ul>
			<ol>
				<li>{l s='Visit the' mod='google_address'} <a data-category="getKey" data-action="premiumLinkClick" data-label="body" href="https://cloud.google.com/console/google/maps-apis/overview" target="_blank">
					{l s='Google Cloud Platform Console' mod='google_address'}</a>.
				</li>
				<li>
					{l s='Click the project drop-down and select or create the project for which you want to add an API key' mod='google_address'}.
				</li>
				<li>
					{l s='Click the menu button' mod='google_address'} <img src="{$smarty.const.__PS_BASE_URI__|escape:'htmlall':'UTF-8'}modules/google_address/views/img/nav-menu.png" width="3%">
					{l s='and select' mod='google_address'} <strong>{l s='APIs' mod='google_address'} &amp; {l s='Services' mod='google_address'} &gt; {l s='Credentials' mod='google_address'}</strong>.
				</li>
				<li>
					{l s='On the' mod='google_address'} <strong>{l s='Credentials' mod='google_address'}</strong> {l s='page, click' mod='google_address'} <strong>{l s='Create credentials' mod='google_address'} &gt; {l s='API key' mod='google_address'}</strong>.
					<br>{l s='The' mod='google_address'} <strong>{l s='API key created' mod='google_address'}</strong> {l s='dialog displays your newly created API key' mod='google_address'}.
				</li>
				<li>
					{l s='Click' mod='google_address'} <b>{l s='Close' mod='google_address'}.</b> <br>
					{l s='The new API key is listed on the' mod='google_address'} <b>{l s='Credentials' mod='google_address'}</b> {l s='page under' mod='google_address'} <b>{l s='API keys' mod='google_address'}</b>. <br>
					<i>({l s='Remember to restrict the API key before using it in production' mod='google_address'}.)</i>
				</li>
			</ol>
		</ul>
	</p>
</div>

{include file="./header.tpl"}