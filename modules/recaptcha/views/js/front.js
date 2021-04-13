/*
 * 2007-2019 PrestaShop
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
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

var onloadCallback = function() {
    grecaptcha.render('g-recaptcha', {});
};
var onloadCallback_1_7 = function() {
    grecaptcha.render('g-recaptcha', {});
	$(document).ready(function() {
		var captchaNotification = $('#notifications');
		if(captchaNotification.length){
			var checkCaptchaText = captchaNotification.html();
			var captchaBlock = $('#g-recaptcha');
			if(checkCaptchaText.indexOf('danger') !== -1 && captchaBlock.length){
				captchaBlock.after(captchaNotification.find('.alert.alert-danger'));
                $('#checkout-personal-information-step').trigger('click');
				$('html, body').animate({
					scrollTop: captchaBlock.offset().top
				}, 1000);
			}
		}
	});
};


$(document).ready(function() {
    $.ajaxSetup({
        beforeSend: function(jqXHR, settings) {
			if ($('#g-recaptcha-response').length) {
				if (settings.data.indexOf('submitAccount') !== -1 && settings.data.indexOf('g-recaptcha-response') === -1) {
					settings.data += '&g-recaptcha-response=' + $('#g-recaptcha-response').val();
				}
			}
        },
		complete: function(jqXHR,status) {
			if ($('#g-recaptcha-response').length) {
				grecaptcha.reset();
			}
		}
    });
});
