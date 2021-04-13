/**
 * ProQuality (c) All rights reserved.
 *
 * DISCLAIMER
 *
 * Do not edit, modify or copy this file.
 * If you wish to customize it, contact us at addons4prestashop@gmail.com.
 *
 * @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
 * @copyright 2015-2016 ProQuality
 * @license   Do not edit, modify or copy this file
 */
$(window).resize(function() {}).resize();
$(window).on('load', function()
{
	if (getUrlParameter('controller') == 'AdminModules' && getUrlParameter('configure') == 'pqprintshippinglabels')
	{
		$("#psl_settings").trigger("change");
	}
});
var test;
$(document).ready(function()
{
	//adding header buttons
	if (getUrlParameter('controller') == 'AdminModules' && getUrlParameter('configure') == 'pqprintshippinglabels')
	{
		pslAddToolbarBtn('separator');
		pslAddToolbarBtn('video');
		pslAddToolbarBtn('documentation');
		pslAddToolbarBtn('contact');
		pslAddToolbarBtn('modules');
	}
	else if (getUrlParameter('controller') == 'AdminOrders' || window.location.href.indexOf("sell/orders") > -1)
	{
		pslAddToolbarPrintLabelsBtn();
	}

	$("#psl_tabs").tabs();
	////////////////////////////////////////////////// datatables //////////////////////////////////////////////////
	var settings_table = psl_db_prefix + psl_module_name + '_settings';
	var settings_editor = new $.fn.dataTable.Editor(
	{
		ajax: psl_grid_path + 'editorSettings',
		fields: [
			{
				label: l("Name:"),
				name: settings_table + ".name" /*, type: "hidden"*/
			},
			{
				name: settings_table + ".id_order_state",
				type: "hidden"
			},
			{
				name: settings_table + ".id_pagetype",
				type: "hidden"
			},
			{
				name: settings_table + ".label_copies",
				type: "hidden"
			},
			{
				name: settings_table + ".labels_horizontally",
				type: "hidden"
			},
			{
				name: settings_table + ".labels_vertically",
				type: "hidden"
			},
			{
				name: settings_table + ".spacing_between_labels_vertically",
				type: "hidden"
			},
			{
				name: settings_table + ".spacing_between_labels_horizontally",
				type: "hidden"
			},
			{
				name: settings_table + ".page_padding_left",
				type: "hidden"
			},
			{
				name: settings_table + ".page_padding_top",
				type: "hidden"
			},
			{
				name: settings_table + ".labels_border",
				type: "hidden"
			},
			{
				name: settings_table + ".rounded_corners_radius",
				type: "hidden"
			},
			{
				name: settings_table + ".one_label_for_each_product",
				type: "hidden"
			},
			{
				name: settings_table + ".export_automatically_on_new_order",
				type: "hidden"
			},
			{
				name: settings_table + ".barcodes_type",
				type: "hidden"
			},
			{
				name: settings_table + ".barcodes_width",
				type: "hidden"
			},
			{
				name: settings_table + ".barcodes_height",
				type: "hidden"
			},
			{
				name: settings_table + ".id_template",
				type: "hidden"
			},
			{
				name: settings_table + ".is_default",
				type: "hidden"
			},
		]
	});
	var templates_table = psl_db_prefix + psl_module_name + '_templates';
	var templates_lang_table = psl_db_prefix + psl_module_name + '_templates_lang';
	var template_editor = new $.fn.dataTable.Editor(
	{
		ajax: psl_grid_path + 'editorTemplate',
		fields: [
		{
			name: templates_table + ".id_template",
			type: "hidden"
		},
		{
			label: l("Name:"),
			name: templates_lang_table + ".name"
		},
		{
			name: templates_lang_table + ".id_lang",
			type: "hidden"
		},
		{
			name: templates_lang_table + ".iso_code",
			type: "hidden"
		},
		{
			name: templates_lang_table + ".html",
			type: "hidden"
		},
		{
			label: l("Width:"),
			name: templates_table + ".width"
		},
		{
			label: l("Height:"),
			name: templates_table + ".height"
		}, ]
	});
	var pagetypes_table = psl_db_prefix + psl_module_name + '_pagetypes';
	var page_type_editor = new $.fn.dataTable.Editor(
	{
		ajax: psl_grid_path + 'editorPageType',
		fields: [
		{
			label: l("Name:"),
			name: pagetypes_table + ".name"
		},
		{
			label: l("Width:"),
			name: pagetypes_table + ".width" /*fieldInfo: "Input value is in seconds",*/
		},
		{
			label: l("Height:"),
			name: pagetypes_table + ".height"
		}, ]
	});

	$(".dataTables_filter input").addClass("psl formfield1");
	$(".date_range_filter, .text_filter").addClass("psl formfield1");
	$("#orders_grid_range_from_7").after('<br>');

	////////////////////////////////////////////////// END datatables //////////////////////////////////////////////////

	$(document).on('click', '#psl_print_labels', function()
	{
		orders_ids = [];
		if (typeof(getUrlParameter('id_order')) == 'undefined' && window.location.href.indexOf("/view") == -1)
		{
			$("input:checkbox.noborder, input:checkbox.js-bulk-action-checkbox").each(function()
			{
				if ($(this).is(":checked") == true && $.isNumeric($(this).val()))
					orders_ids.push($(this).val());
				
			});
			if (orders_ids.length === 0)
			{
				alert(l('You have to check at least an order!'));
				return;
			}
		}
		else
		{
			if (typeof(getUrlParameter('id_order')) == 'undefined')
			{
				var order_id_exp = window.location.href.split("orders/");
				var order_id = order_id_exp[1].split("/");
				orders_ids.push(order_id[0]);
			} 
			else	
				orders_ids.push(getUrlParameter('id_order'));
		}
		var orders_ids_ser = '';
		for (i = 0; i < orders_ids.length; i++) 
			orders_ids_ser += orders_ids[i] + ',';
		
		var params = {
		 'load': 'showSettings2Dropdown',
		 'divs': {
		     0 : 'settings2_ajax_span'
		 },
		 'params':
		 {
		     'orders_ids': orders_ids,
		 },
		 'preloader':
		 {
		     'divs':
		     {
		         0: 'psl_settings_ajax_load_span',
		     },
		     'type': 2,
		     'style': 3,
		 }
		};

		pslAjaxController(params, function(result)
		{
			if (!result)
				alert('No templates are assigned to this order status!');
			else
			{
				$("#dialog-form-generate-labels").dialog("open");
				$('select[id^="psl_settings2"]').trigger("change");
			}
		});
	
	});
	$("#preview_template").on('click', function()
	{
		hidden_labels = [];
		$(".label_out").each(function()
		{
			if ($(this).find("td.label_in").css('visibility') == 'hidden')
				hidden_labels.push($(this).find("td.label_in").html());
			
		});

		var hidden_labels_ser = '';
		for (i = 0; i < hidden_labels.length; i++) {
			hidden_labels_ser += hidden_labels[i] + ',';
		}
		//fac un ajax sa iau dimensiunile la tipul paginii
		var params = {
			'load': 'getPageType',
			'divs': null,
			'params':
			{
				'id_pagetype': $('#page_type').val(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'page_type_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			var width = res.width;
			var height = res.height;
			var spacing_between_labels_vertically = $("#spacing_between_labels_vertically").val();
			var spacing_between_labels_horizontally = $("#spacing_between_labels_horizontally").val();
			var page_padding_left = $("#page_padding_left").val();
			var page_padding_top = $("#page_padding_top").val();
			if ($("#labels_border").prop('checked') == true) var labels_border = 1;
			else var labels_border = 0;
			var rounded_corners_radius = $("#rounded_corners_radius").val();
			if ($("#one_label_for_each_product").prop('checked') == true) var one_label_for_each_product = 1;
			else var one_label_for_each_product = 0;
			if ($("#export_automatically_on_new_order").prop('checked') == true) var export_automatically_on_new_order = 1;
			else var export_automatically_on_new_order = 0;
			var labels_horizontally = $("#labels_horizontally").val();
			var labels_vertically = $("#labels_vertically").val();
			var barcodes_type = $("#barcodes_type").val();
			var barcodes_width = $("#barcodes_width").val();
			var barcodes_height = $("#barcodes_height").val();
			var template_content = tinyMCE.get('template_content').getContent();
			var template_width = getTemplateWidth();
			var template_height = getTemplateHeight();
			var data2 = {
				'width': width,
				'height': height,
				'label_copies': $("#label_copies").val(),
				'labels_horizontally': labels_horizontally,
				'labels_vertically': labels_vertically,
				'spacing_between_labels_vertically': spacing_between_labels_vertically,
				'spacing_between_labels_horizontally': spacing_between_labels_horizontally,
				'page_padding_left': page_padding_left,
				'page_padding_top': page_padding_top,
				'labels_border': labels_border,
				'rounded_corners_radius': rounded_corners_radius,
				'one_label_for_each_product' : one_label_for_each_product,
				'export_automatically_on_new_order' : export_automatically_on_new_order,
				'barcodes_type': barcodes_type,
				'barcodes_width': barcodes_width,
				'barcodes_height': barcodes_height,
				'hidden_labels': hidden_labels_ser,
				'template_width': template_width,
				'template_height': template_height,
				'template_content': template_content,
			};
			var params2 = {
				'load': 'previewTemplate',
				'divs': null,
				'params':
				{
					'data': data2,
				},
				'preloader':
				{
					'divs':
					{
						0: 'page_type_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			pslAjaxController(params2, function(result)
			{
				var timestamp = Math.round(new Date().getTime() / 1000);
				location.href = psl_path + 'pdfs/shipping-labels.php?filename=' + result + '&time=' + timestamp;
			});
		});
	});
	$(document).on('change', 'select[id^="psl_languages2"]', function()
	{
		$('#psl_settings2').trigger('change');
	});
	$(document).on('change', 'select[id^="psl_settings2"]', function()
	{
		orders_ids = [];
		if (typeof(getUrlParameter('id_order')) == 'undefined' && window.location.href.indexOf("/view") == -1)
		{
			$("input:checkbox.noborder, input:checkbox.js-bulk-action-checkbox").each(function()
			{
				if ($(this).is(":checked") == true && $.isNumeric($(this).val()))
					orders_ids.push($(this).val());
				
			});
		}
		else
		{
			if (typeof(getUrlParameter('id_order')) == 'undefined')
			{
				var order_id_exp = window.location.href.split("orders/");
				var order_id = order_id_exp[1].split("/");
				orders_ids.push(order_id[0]);
			} 
			else	
				orders_ids.push(getUrlParameter('id_order'));
		}
		
		var orders_ids_ser = '';
		for (i = 0; i < orders_ids.length; i++)
		{
			orders_ids_ser += orders_ids[i] + ',';
		}
		var params = {
			'load': 'getSetting',
			'divs': null,
			'params':
			{
				'id_setting': $(this).val(),
				'iso_code': $('#psl_languages2').find('option:selected').text(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'psl_settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		var _this = this;
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			var page_width = res.width;
			var page_height = res.height;
			var labels_horizontally = res.labels_horizontally;
			var labels_vertically = res.labels_vertically;
			var spacing_between_labels_horizontally = res.spacing_between_labels_horizontally;
			var spacing_between_labels_vertically = res.spacing_between_labels_vertically;
			var page_padding_left = res.page_padding_left;
			var page_padding_top = res.page_padding_top;
			var labels_border = res.labels_border;
			var rounded_corners_radius = res.rounded_corners_radius;
			var one_label_for_each_product = res.one_label_for_each_product;
			var export_automatically_on_new_order = res.export_automatically_on_new_order;
			var barcodes_type = res.barcodes_type;
			var barcodes_width = res.barcodes_width;
			var barcodes_height = res.barcodes_height;
			var template_content = res.template_html;
			var template_width = res.template_width;
			var template_height = res.template_height;

			if (page_width <= 100 || page_height <= 100)
				var percentage = 100;
			else
				var percentage = 60;

			$("#quick_preview").html(generateQuickPreview(page_width, page_height, template_width, template_height, labels_horizontally, labels_vertically, spacing_between_labels_horizontally, spacing_between_labels_vertically, page_padding_left, page_padding_top, labels_border, rounded_corners_radius, percentage));
			//$('#psl_export').trigger('click');
		});
	});
	$(document).on('change', 'select[id^="psl_update_status"]', function()
	{
		var val = $(this).val();
		if (val != 'no') $("#psl_export").html('<span class="icon-download"></span> ' + l("Export to .PDF & update statuses"));
		else $("#psl_export").html('<span class="icon-download"></span> ' + l("Export to .PDF"));
	});
	$(document).on('click', 'a[id^="psl_export"]', function()
	{
		hidden_labels = [];
		$(".label_out").each(function()
		{
			if ($(this).find("td.label_in").css('visibility') == 'hidden')
				hidden_labels.push($(this).find("td.label_in").html());
			
		});

		var hidden_labels_ser = '';
		for (i = 0; i < hidden_labels.length; i++)
			hidden_labels_ser += hidden_labels[i] + ',';
		
		orders_ids = [];
		if (typeof(getUrlParameter('id_order')) == 'undefined' && window.location.href.indexOf("/view") == -1)
		{
			$("input:checkbox.noborder, input:checkbox.js-bulk-action-checkbox").each(function()
			{
				if ($(this).is(":checked") == true && $.isNumeric($(this).val()))
					orders_ids.push($(this).val());
				
			});
		}
		else
		{
			if (typeof(getUrlParameter('id_order')) == 'undefined')
			{
				var order_id_exp = window.location.href.split("orders/");
				var order_id = order_id_exp[1].split("/");
				orders_ids.push(order_id[0]);
			} 
			else	
				orders_ids.push(getUrlParameter('id_order'));
		}
		var orders_ids_ser = '';
		for (i = 0; i < orders_ids.length; i++)
		{
			orders_ids_ser += orders_ids[i] + ',';
		}

		/* fac un ajax sa iau dimensiunile la tipul paginii */
		var params = {
			'load': 'getSetting',
			'divs': null,
			'params':
			{
				'id_setting': $('#psl_settings2').val(),
				'iso_code': $('#psl_languages2').find('option:selected').text(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'psl_settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			var orders_ids = orders_ids_ser;
			var update_status = $("#psl_update_status").val();
			var page_width = res.width;
			var page_height = res.height;
			var labels_horizontally = res.labels_horizontally;
			var labels_vertically = res.labels_vertically;
			var spacing_between_labels_horizontally = res.spacing_between_labels_horizontally;
			var spacing_between_labels_vertically = res.spacing_between_labels_vertically;
			var page_padding_left = res.page_padding_left;
			var page_padding_top = res.page_padding_top;
			var labels_border = res.labels_border;
			var rounded_corners_radius = res.rounded_corners_radius;
			var one_label_for_each_product = res.one_label_for_each_product;
			var export_automatically_on_new_order = res.export_automatically_on_new_order;
			var barcodes_type = res.barcodes_type;
			var barcodes_width = res.barcodes_width;
			var barcodes_height = res.barcodes_height;
			var template_content = res.template_html;
			var template_width = res.template_width;
			var template_height = res.template_height;
			var data2 = {
				'id_setting': $('#psl_settings2').val(),
				'width': page_width,
				'height': page_height,
				'label_copies': res.label_copies,
				'labels_horizontally': labels_horizontally,
				'labels_vertically': labels_vertically,
				'spacing_between_labels_vertically': spacing_between_labels_vertically,
				'spacing_between_labels_horizontally': spacing_between_labels_horizontally,
				'page_padding_left': page_padding_left,
				'page_padding_top': page_padding_top,
				'labels_border': labels_border,
				'rounded_corners_radius': rounded_corners_radius,
				'one_label_for_each_product' : one_label_for_each_product,
				'export_automatically_on_new_order' : export_automatically_on_new_order,
				'barcodes_type': barcodes_type,
				'barcodes_width': barcodes_width,
				'barcodes_height': barcodes_height,
				'hidden_labels': hidden_labels_ser,
				'template_width': template_width,
				'template_height': template_height,
				'template_content': template_content,
				'orders_ids': orders_ids,
				'update_status': update_status,
			};

			var params2 = {
				'load': 'exportToPdf',
				'divs': null,
				'params':
				{
					'data': data2,
				},
				'preloader':
				{
					'divs':
					{
						0: 'psl_settings_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			pslAjaxController(params2, function(result)
			{
				var timestamp = Math.round(new Date().getTime() / 1000);
				location.href = psl_path + 'pdfs/shipping-labels.php?filename=' + result + '&time=' + timestamp;
			});
		});
	});
	$("#help_tr").on('click', function()
	{
		$("#dialog-help").dialog("open");
	});
	$("#position_top").on('keyup change', function()
	{
		var editor = tinyMCE.get('template_content');
		$(editor.dom.select("#" + $('#focused_layer_id').val())).css('top', $(this).val());
	});
	$("#position_left").on('keyup change', function()
	{
		var editor = tinyMCE.get('template_content');
		$(editor.dom.select("#" + $('#focused_layer_id').val())).css('left', $(this).val());
	});
	$("#size_width").on('keyup change', function()
	{
		var editor = tinyMCE.get('template_content');
		$(editor.dom.select("#" + $('#focused_layer_id').val())).css('width', $(this).val());
	});
	$("#size_height").on('keyup change', function()
	{
		var editor = tinyMCE.get('template_content');
		$(editor.dom.select("#" + $('#focused_layer_id').val())).css('height', $(this).val());
	});
	$("#position_top_decrement").mousehold(100, function()
	{
		$("#position_top").val(parseInt($("#position_top").val()) - 1);
		$("#position_top").trigger('change');
	});
	$("#position_top_increment").mousehold(100, function()
	{
		$("#position_top").val(parseInt($("#position_top").val()) + 1);
		$("#position_top").trigger('change');
	});
	$("#position_left_decrement").mousehold(100, function()
	{
		$("#position_left").val(parseInt($("#position_left").val()) - 1);
		$("#position_left").trigger('change');
	});
	$("#position_left_increment").mousehold(100, function()
	{
		$("#position_left").val(parseInt($("#position_left").val()) + 1);
		$("#position_left").trigger('change');
	});
	$("#size_width_decrement").mousehold(100, function()
	{
		$("#size_width").val(parseInt($("#size_width").val()) - 1);
		$("#size_width").trigger('change');
	});
	$("#size_width_increment").mousehold(100, function()
	{
		$("#size_width").val(parseInt($("#size_width").val()) + 1);
		$("#size_width").trigger('change');
	});
	$("#size_height_decrement").mousehold(100, function()
	{
		$("#size_height").val(parseInt($("#size_height").val()) - 1);
		$("#size_height").trigger('change');
	});
	$("#size_height_increment").mousehold(100, function()
	{
		$("#size_height").val(parseInt($("#size_height").val()) + 1);
		$("#size_height").trigger('change');
	});
	$("#remove_layer").on('click', function()
	{
		var editor = tinyMCE.get('template_content');
		$(editor.dom.select("#" + $('#focused_layer_id').val())).remove();
	});
	$(document).on('change', "#languages", function()
	{
		var iso_code = $('#languages').find('option:selected').text();
		var data = {
			'id_template': $("#select_template").val(),
			'iso_code': iso_code,
		};
		var params = {
			'load': 'getTemplate',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'select_template_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			try
			{
				var res = JSON.parse(result);
				if (res == false) tinyMCE.get('template_content').setContent('');
				else tinyMCE.get('template_content').setContent(res.html);
			}
			catch (e)
			{}
		});
	});
	$(document).on('change', "#select_template", function()
	{
		$('#languages').val($('#languages').find('option:first').val());

		if ($('#select_template').val() != 1 && $('#select_template').val() != 2)
		{
			$('#invoice_number_barcode').prop('checked', false);
			$('#invoice_number_barcode').prop('disabled', true);
		}
		var iso_code = $('#languages').find('option:selected').text();
		var data = {
			'id_template': $("#select_template").val(),
			'iso_code': iso_code,
		};
		var params = {
			'load': 'getTemplate',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'select_template_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			try
			{
				var res = JSON.parse(result);
				if (res == false) tinyMCE.get('template_content').setContent('');
				else tinyMCE.get('template_content').setContent(res.html);
			}
			catch (e) {}
			$('#page_type').trigger('change');
		});
	});
	$('.popup').click(function(event)
	{
		event.preventDefault();
		window.open($(this).attr("href"), "popupWindow", "width=834,height=1152,scrollbars=yes,resizable=no,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no");
	});
	$('.popup-small').click(function(event)
	{
		event.preventDefault();
		window.open($(this).attr("href"), "popupWindow", "width=834,height=1152,scrollbars=yes,resizable=no,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no");
	});
	$('.popup-large').click(function(event)
	{
		event.preventDefault();
		window.open($(this).attr("href"), "popupWindow", "width=1152,height=824,scrollbars=yes,resizable=no,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no");
	});
	//----------------------------------------------------------------------------
	$(".add-var").on('click', function()
	{
		tinyMCE.get('template_content').execCommand('mceInsertContent', false, $(this).html());
	});
	$("#order_related_tr, #order_products_related_tr, #product_related_tr, #custom_related_tr").on('click', function()
	{
		var name = $(this).attr('id').split('_tr')[0];
		$("#" + name + "_tbody").toggle();
		if ($(this).find('span.fa').hasClass('fa-chevron-down'))
		{
			$(this).find('span.fa').removeClass('fa-chevron-down');
			$(this).find('span.fa').addClass('fa-chevron-up');
		}
		else
		{
			$(this).find('span.fa').addClass('fa-chevron-down');
			$(this).find('span.fa').removeClass('fa-chevron-up');
		}
	});
	$("#psl_delete_settings").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var settings_val = $('#psl_settings').val();
		var q = confirm(l("Are you sure you want to delete the settings with the name") + " `" + $('#psl_settings option:selected').text() + "`?");
		if (q == true)
		{
			var params = {
				'load': 'deleteSettings',
				'divs':
				{
					0: 'settings_ajax_span',
				},
				'params':
				{
					'id_setting': $('#psl_settings').val(),
				},
				'preloader':
				{
					'divs':
					{
						0: 'settings_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			pslAjaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true) $("#psl_settings").trigger("change");
				else alert(res.response);
			});
		}
		else
		{
			return false;
		}

	});
	$("#psl_save_settings").on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		if ($("#labels_border").prop('checked') == true) var labels_border = 1;
		else var labels_border = 0;

		if ($("#one_label_for_each_product").prop('checked') == true) var one_label_for_each_product = 1;
		else var one_label_for_each_product = 0;

		if ($("#export_automatically_on_new_order").prop('checked') == true) var export_automatically_on_new_order = 1;
		else var export_automatically_on_new_order = 0;

		preload(
		{
			0: 'settings_ajax_load_span'
		}, 'on', 3);
		settings_editor.edit($('#psl_settings').val(), false)
		.set(settings_table + '.name', $('#psl_settings option:selected').text())
		.set(settings_table + '.id_order_state', $('#psl_order_state').val())
		.set(settings_table + '.id_pagetype', $('#page_type').val())
		.set(settings_table + '.label_copies', $('#label_copies').val())
		.set(settings_table + '.labels_horizontally', $('#labels_horizontally').val())
		.set(settings_table + '.labels_vertically', $('#labels_vertically').val())
		.set(settings_table + '.spacing_between_labels_vertically', $('#spacing_between_labels_vertically').val())
		.set(settings_table + '.spacing_between_labels_horizontally', $('#spacing_between_labels_horizontally').val())
		.set(settings_table + '.page_padding_left', $('#page_padding_left').val())
		.set(settings_table + '.page_padding_top', $('#page_padding_top').val())
		.set(settings_table + '.labels_border', labels_border)
		.set(settings_table + '.rounded_corners_radius', $('#rounded_corners_radius').val())
		.set(settings_table + '.one_label_for_each_product', one_label_for_each_product)
		.set(settings_table + '.export_automatically_on_new_order', export_automatically_on_new_order)
		.set(settings_table + '.barcodes_type', $('#barcodes_type').val())
		.set(settings_table + '.barcodes_width', $('#barcodes_width').val())
		.set(settings_table + '.barcodes_height', $('#barcodes_height').val())
		.set(settings_table + '.id_template', $('#select_template').val())
		.set(settings_table + '.is_default', 0).submit();

		// trigger an action one editing is completed
		settings_editor.one('edit', function(e, json, data)
		{
			preload(
			{
				0: 'settings_ajax_load_span'
			}, 'off', 3);
			$("#save_template").trigger("click");
			alert('The settings have been saved!');

		});
	});
	$("#psl_save_as_settings").on('click', function()
	{
		if ($("#labels_border").prop('checked') == true) var labels_border = 1;
		else var labels_border = 0;

		if ($("#one_label_for_each_product").prop('checked') == true) var one_label_for_each_product = 1;
		else var one_label_for_each_product = 0;

		if ($("#export_automatically_on_new_order").prop('checked') == true) var export_automatically_on_new_order = 1;
		else var export_automatically_on_new_order = 0;

		settings_editor.create(false).title(l('Add new record')).buttons(
		{
			label: l("Save"),
			fn: function()
			{
				this.submit();
			}
		})
		.set(settings_table + '.id_order_state', $('#psl_order_state').val())
		.set(settings_table + '.id_pagetype', $('#page_type').val())
		.set(settings_table + '.label_copies', $('#label_copies').val())
		.set(settings_table + '.labels_horizontally', $('#labels_horizontally').val())
		.set(settings_table + '.labels_vertically', $('#labels_vertically').val())
		.set(settings_table + '.spacing_between_labels_vertically', $('#spacing_between_labels_vertically').val())
		.set(settings_table + '.spacing_between_labels_horizontally', $('#spacing_between_labels_horizontally').val())
		.set(settings_table + '.page_padding_left', $('#page_padding_left').val())
		.set(settings_table + '.page_padding_top', $('#page_padding_top').val())
		.set(settings_table + '.labels_border', labels_border)
		.set(settings_table + '.rounded_corners_radius', $('#rounded_corners_radius').val())
		.set(settings_table + '.one_label_for_each_product', one_label_for_each_product)
		.set(settings_table + '.export_automatically_on_new_order', export_automatically_on_new_order)
		.set(settings_table + '.barcodes_type', $('#barcodes_type').val())
		.set(settings_table + '.barcodes_width', $('#barcodes_width').val())
		.set(settings_table + '.barcodes_height', $('#barcodes_height').val())
		.set(settings_table + '.id_template', $('#select_template').val())
		.set(settings_table + '.is_default', 0).open();

		settings_editor.one('submitComplete', function(e, json, data)
		{
			if (json.fieldErrors != '')
			{
				var text = '';
				for (i = 0; i < json.fieldErrors.length; i++)
				{
					text += json.fieldErrors[i].status + ': "' + json.fieldErrors[i].name.split('.')[1] + "\"\n";
				}
				alert(text);
			}
		});
		// trigger an action one editing is completed
		settings_editor.one('create', function(e, json, data)
		{
			settings_text = data[settings_table].name; //json.row[settings_table].name;
			settings_val = data.DT_RowId.split('_')[1]; //json.row.DT_RowId.split('_')[1];
			$('#psl_settings').append($('<option>',
			{
				value: settings_val,
				text: settings_text
			}));
			$('#psl_settings').val(settings_val);
			$("#save_template").trigger("click");

		});
	});
	$(document).on('change', "#psl_settings", function()
	{
		//fac request ajax sa iau setarile
		var params = {
			'load': 'getSetting',
			'divs': null,
			'params':
			{
				'id_setting': $('#psl_settings').val(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			$("#psl_order_state").val(res.id_order_state);
			$("#page_type").val(res.id_pagetype);
			$('#label_copies').val(res.label_copies);
			$("#labels_horizontally").val(res.labels_horizontally);
			$("#labels_vertically").val(res.labels_vertically);
			$("#spacing_between_labels_vertically").val(res.spacing_between_labels_vertically);
			$("#spacing_between_labels_horizontally").val(res.spacing_between_labels_horizontally);
			$("#page_padding_left").val(res.page_padding_left);
			$("#page_padding_top").val(res.page_padding_top);
			if (res.labels_border == 1) $("#labels_border").prop('checked', true);
			else $("#labels_border").prop('checked', false);
			$("#rounded_corners_radius").val(res.rounded_corners_radius);
			if (res.one_label_for_each_product == 1) $("#one_label_for_each_product").prop('checked', true);
			else $("#one_label_for_each_product").prop('checked', false);
			if (res.export_automatically_on_new_order == 1) $("#export_automatically_on_new_order").prop('checked', true);
			else $("#export_automatically_on_new_order").prop('checked', false);
			$("#barcodes_type").val(res.barcodes_type);
			$("#barcodes_width").val(res.barcodes_width);
			$("#barcodes_height").val(res.barcodes_height);
			$("#select_template").val(res.id_template);
			$("#page_type").trigger("change");
			$("#select_template").trigger("change");
		});
	});
	$("#psl_make_default_setting").on('click', function()
	{
		var data = {
			'id_setting': $('#psl_settings').val(),
		};
		/*fac request ajax sa iau setarile*/
		var params = {
			'load': 'makeDefaultSetting',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'settings_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			alert(res.response);
		});
	});
	$(document).on('change', "#page_type", function()
	{
		//fac un ajax sa iau dimensiunile la tipul paginii
		var params = {
			'load': 'getPageType',
			'divs': null,
			'params':
			{
				'id_pagetype': $('#page_type').val(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'page_type_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			try
			{
				var res = JSON.parse(result);
				var page_width = res.width;
				var page_height = res.height;
				var labels_horizontally = $("#labels_horizontally").val();
				var labels_vertically = $("#labels_vertically").val();
				var spacing_between_labels_horizontally = $("#spacing_between_labels_horizontally").val();
				var spacing_between_labels_vertically = $("#spacing_between_labels_vertically").val();
				var page_padding_left = $("#page_padding_left").val();
				var page_padding_top = $("#page_padding_top").val();
				if ($("#labels_border").prop('checked') == true) var labels_border = 1;
				else var labels_border = 0;
				var rounded_corners_radius = $("#rounded_corners_radius").val();
				if ($("#one_label_for_each_product").prop('checked') == true) var one_label_for_each_product = 1;
				else var one_label_for_each_product = 0;
				if ($("#export_automatically_on_new_order").prop('checked') == true) var export_automatically_on_new_order = 1;
				else var export_automatically_on_new_order = 0;
				if (page_width <= 100 || page_height <= 100)
					var percentage = 100;
				else
					var percentage = 60;

				$("#quick_preview").html(generateQuickPreview(page_width, page_height, getTemplateWidth(), getTemplateHeight(), labels_horizontally, labels_vertically, spacing_between_labels_horizontally, spacing_between_labels_vertically, page_padding_left, page_padding_top, labels_border, rounded_corners_radius, percentage));
			}
			catch (e) {}
		});
	});
	$("#labels_horizontally, #labels_vertically, #spacing_between_labels_horizontally, #spacing_between_labels_vertically, #page_padding_left, #page_padding_top, #labels_border, #rounded_corners_radius").each(function()
	{
		$(this).on('keyup change', function()
		{
			$("#page_type").trigger("change");
		});
	});

	$('#edit_page_type').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;

		//fac o cerere ajax sa iau campurile
		var params = {
			'load': 'getPageType',
			'divs': null,
			'params':
			{
				'id_pagetype': $('#page_type').val(),
			},
			'preloader':
			{
				'divs':
				{
					0: 'page_type_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			page_type_editor.edit(res.id_pagetype, false).title(l('Edit record')).buttons(
			{
				label: l("Save"),
				fn: function()
				{
					this.submit();
				}
			})
			.set(pagetypes_table + '.name', res.name) // Get the value from Javascript
			.set(pagetypes_table + '.width', res.width).set(pagetypes_table + '.height', res.height).open();
			// trigger an action one editing is completed
			page_type_editor.one('edit', function(e, json, data)
			{
				page_type_text = data[pagetypes_table].name + ' (' + data[pagetypes_table].width + ' / ' + data[pagetypes_table].height + ') mm';
				page_type_val = res.id_pagetype;
				$("#page_type").find("option[value='" + page_type_val + "']").remove();
				$('#page_type').append($('<option>',
				{
					value: page_type_val,
					text: page_type_text
				}));
				$('#page_type').val(page_type_val);
				$("#page_type").trigger("change");
			});
		});
	});
	$('#delete_page_type').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var page_type_val = $('#page_type').val();
		var q = confirm(l("Are you sure you want to delete the page type") + " `" + $('#page_type option:selected').text() + "`?");
		if (q == true)
		{
			//ajax aici:
			var params = {
				'load': 'deletePageType',
				'divs':
				{
					0: 'page_type_ajax_span',
				},
				'params':
				{
					'id_pagetype': $('#page_type').val(),
				},
				'preloader':
				{
					'divs':
					{
						0: 'page_type_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			pslAjaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true) $("#page_type").trigger("change");
				else alert(res.response);
			});
		}
		else
		{
			return false;
		}

	});
	$('#add_page_type').on('click', function()
	{
		page_type_editor.create(false).title(l('Add new record')).buttons(
		{
			label: l("Save"),
			fn: function()
			{
				this.submit();
			}
		})

		.open();
		// trigger an action one editing is completed
		page_type_editor.one('create', function(e, json, data)
		{
			page_type_text = data[pagetypes_table].name + ' (' + data[pagetypes_table].width + ' / ' + data[pagetypes_table].height + ') mm';
			page_type_val = data.DT_RowId.split('_')[1];
			$('#page_type').append($('<option>',
			{
				value: page_type_val,
				text: page_type_text
			}));
			$('#page_type').val(page_type_val);
			$("#page_type").trigger("change");
		});
	});
	$('#save_template').on('click', function()
	{
		var iso_code = $('#languages').find('option:selected').text();
		var data = {
			'id_template': $('#select_template').val(),
			'html': tinyMCE.get('template_content').getContent(),
			'iso_code': iso_code,
		};
		var params = {
			'load': 'updateTemplate',
			'divs':
			{
				0: '',
			},
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'select_template_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			if (res.success == false) alert(res.response);
		});
	});
	$('#edit_template').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var iso_code = $('#languages').find('option:selected').text();
		var data = {
			'id_template': $("#select_template").val(),
			'iso_code': iso_code,
		};
		//fac o cerere ajax sa iau campurile
		var params = {
			'load': 'getTemplate',
			'divs': null,
			'params':
			{
				'data': data,
			},
			'preloader':
			{
				'divs':
				{
					0: 'select_template_ajax_load_span',
				},
				'type': 2,
				'style': 3,
			}
		};
		pslAjaxController(params, function(result)
		{
			var res = JSON.parse(result);
			template_editor.edit(res.id_template, false).title(l('Edit record')).buttons(
			{
				label: l("Save"),
				fn: function()
				{
					this.submit();
				}
			})
			.set(templates_table + '.id_template', res.id_template).set(templates_lang_table + '.name', res.name)
			.set(templates_table + '.width', res.width).set(templates_table + '.height', res.height).open();
			// trigger an action one editing is completed
			template_editor.one('preSubmit', function(e, data, action)
			{
				var name_field = this.field(templates_lang_table + '.name');
				var width_field = this.field(templates_table + '.width');
				var height_field = this.field(templates_table + '.height');
				if (!name_field.val()) name_field.error(l('Name cannot be empty.'));
				if (!width_field.val() || !$.isNumeric(width_field.val())) width_field.error(l('Width cannot be empty.'));
				if (!height_field.val() || !$.isNumeric(height_field.val())) height_field.error(l('Height cannot be empty.'));
				if (this.inError()) return false;
				var template_val = res.id_template;
				template_text = name_field.val() + ' (' + width_field.val() + ' / ' + height_field.val() + ') mm';
				$("#select_template").find("option[value='" + template_val + "']").remove();
				$('#select_template').append($('<option>',
				{
					value: template_val,
					text: template_text
				}));
				$('#select_template').val(template_val);
				$("#select_template").trigger("change");
				$("#page_type").trigger("change");
			});
		});
	});
	$('#delete_template').on('click', function()
	{
		if ($(this).attr('class') == 'button-disabled') return false;
		var template_val = $('#select_template').val();
		var q = confirm(l("Are you sure you want to delete the template") + " `" + $('#select_template option:selected').text() + "`?");
		if (q == true)
		{
			//ajax aici:
			var params = {
				'load': 'deleteTemplate',
				'divs':
				{
					0: 'templates_ajax_span',
				},
				'params':
				{
					'id_template': $('#select_template').val(),
				},
				'preloader':
				{
					'divs':
					{
						0: 'select_template_ajax_load_span',
					},
					'type': 2,
					'style': 3,
				}
			};
			pslAjaxController(params, function(result)
			{
				var res = JSON.parse(result);
				if (res.success == true)
				{
					$("#select_template").trigger("change");
					$("#page_type").trigger("change");
				}
				else alert(res.response);
			});
		}
		else
		{
			return false;
		}

	});
	$('#add_template').on('click', function()
	{
		var iso_code = $('#languages').find('option:selected').text();
		var id_lang = $('#languages').find('option:selected').val();
		template_editor.create(false).title(l('Add new record')).buttons(
		{
			label: l("Save"),
			fn: function()
			{
				this.submit();
			}
		}).set(templates_lang_table + '.id_lang', id_lang).set(templates_lang_table + '.iso_code', iso_code).set(templates_lang_table + '.html', tinyMCE.get('template_content').getContent()).open();
		template_editor.one('preSubmit', function(e, data, action)
		{
			var name_field = this.field(templates_lang_table + '.name');
			var width_field = this.field(templates_table + '.width');
			var height_field = this.field(templates_table + '.height');
			template_text = name_field.val() + ' (' + width_field.val() + ' / ' + height_field.val() + ') mm';
			if (!name_field.val()) name_field.error(l('Name cannot be empty.'));
			if (!width_field.val() || !$.isNumeric(width_field.val())) width_field.error(l('Width cannot be empty.'));
			if (!height_field.val() || !$.isNumeric(height_field.val())) height_field.error(l('Height cannot be empty.'));
			if (this.inError()) return false;
		});
		template_editor.one('postSubmit', function(e, json, data, action)
		{
			template_val = json.data[0][templates_table].id_template;
			template_text = json.data[0][templates_lang_table].name + ' (' + json.data[0][templates_table].width + ' / ' + json.data[0][templates_table].height + ') mm';
			$('#select_template').append($('<option>',
			{
				value: template_val,
				text: template_text
			}));
			$('#select_template').val(template_val);
			$("#select_template").trigger("change");
			$("#page_type").trigger("change");
		});
	});

	$(document).on('click', ".label_out", function()
	{
		if ($(this).find("td.label_in").css('visibility') == 'visible') $(this).find("td.label_in").css('visibility', 'hidden');
		else $(this).find("td.label_in").css('visibility', 'visible');
	});
}); //end document.onload