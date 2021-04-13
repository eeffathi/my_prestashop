{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2013-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="{$name|escape:'html':'UTF-8'}-btn-group">
	<div class="btn-group">
        {foreach from=$buttons key="input_value" item="button_name"}
			<button type="submit" data-action="{$input_value|escape:'html':'UTF-8'}" class="btn btn-default{if $input_value == $default_value} active{/if}">{$button_name|escape:'html':'UTF-8'}</button>
        {/foreach}
	</div>
	<input type="hidden" name="{$name|escape:'html':'UTF-8'}">
</div>
<script>
    $('#{$name|escape:'html':'UTF-8'}-btn-group button').on('click', function (e) {
        e.preventDefault();
        $('#{$name|escape:'html':'UTF-8'}-btn-group button').removeClass('active');
        $(this).addClass('active');
        $('#{$name|escape:'html':'UTF-8'}-btn-group input').val($(this).data('action'));
    });
    $('#{$name|escape:'html':'UTF-8'}-btn-group button').each(function () {
        if ($(this).hasClass('active')) {
            $(this).trigger('click');
        }
    });
</script>