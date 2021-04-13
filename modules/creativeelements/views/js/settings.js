/*!
 * Creative Elements - Elementor based PageBuilder
 * Copyright 2019-2021 WebshopWorks.com
 */

$(function() {
	var $regenerate = $('#page-header-desc-configuration-regenerate-css');

	$regenerate
		.attr({
			title: '<p style="margin:0 -14px; width:190px;">' + $regenerate.attr('onclick').substr(2) + '</p>',
		})
		.tooltip({
			html: true,
			placement: 'bottom',
		})
		.on('click.ce', function onClickRegenerateCss() {
			if ($regenerate.find('.process-icon-loading').length) {
				return;
			}
			$regenerate.find('i').attr('class', 'process-icon-loading');

			$.post(
				location.href,
				{
					ajax: true,
					action: 'regenerate_css',
				},
				function onSuccessRegenerateCss(data) {
					$regenerate.find('i').attr('class', 'process-icon-ok');
				},
				'json'
			);
		})
		.removeAttr('onclick')
	;
});
