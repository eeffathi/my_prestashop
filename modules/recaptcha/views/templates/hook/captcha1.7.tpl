{*
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<style>
	#notifications{
		display: none;
	}
	#g-recaptcha + .alert {
		margin-top: 1rem;
	}
	#g-recaptcha + .alert ul {
		margin: 0;
	}
</style>

<div class="form-group row">
  <div class="col-md-6 offset-md-3">
    <div id="g-recaptcha" class="g-recaptcha" data-sitekey="{$site_key|escape:'html':'UTF-8'}" data-theme="{$theme}"></div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback_1_7&render=explicit&?hl={$lang}" async defer></script> 