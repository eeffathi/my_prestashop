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

// added this to support the new version of prestashop.
var matched, browser;
jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};
matched = jQuery.uaMatch( navigator.userAgent );
browser = {};
if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}
// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}
jQuery.browser = browser;

/* new ajax controller function, better than the old one */
function pslAjaxController(params, callback)
{
	/*
params.load*           = type: string (required)
                       = desc: php switch to load

params.divs*           = type: object (required)
                       = desc: object of divs to be populated

params.params*         = type: object (required)
                       = desc: object of vars to be sent to the php switch

params.preloader       = type: object (optional)
                       = desc: object of preloader proprietes
(
	params.preloader.divs  = type: object (required if preloader active)
	                       = desc: object of divs to populate the preloader

	params.preloader.type  = type: string (optional: 1 ; 2)
	                       = desc: type of preloader (when preloader is in the same div as the response ; when preloader is in the other div as the response)

	params.preloader.style = type: string (optional: 1 ; 2)
	                       = desc: style of preloader (style of animated image)
)

callback               = type: callback function (optional)
					   = desc: function to put the ajax result
*/
	//verifying null args
	params.divs = typeof(params.divs) != 'undefined' ? params.divs : null;
	params.preloader = typeof(params.preloader) != 'undefined' ? params.preloader : null;
	callback = typeof(callback) != 'undefined' ? callback : null;
	var timestamp = Math.round(new Date().getTime() / 1000);
	//controller and params
	var load_url = psl_url + 'ajax.php?time=' + timestamp;
	//extending the params
	params.params = $.extend(
	{
		'type': params.load,
		'token': psl_token
	}, params.params);

	//preloader start
	if (params.preloader && params.preloader.divs != null)
	{
		preload(params.preloader.divs, 'on', params.preloader.style);
	}
	$.ajax(
	{
		url: load_url,
		// beforeSend: function(xhr) {
		//   xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		// },
		//headers: { 'Access-Control-Allow-Origin': '*' },
		type: 'POST',
		data: params.params,
		//dataType: 'json',
		success: function(result)
		{
			if (isJson(result))
			{
				var res = JSON.parse(result);
				if (typeof(res.response) != 'undefined')
				{
					if (res.success === true)
					{
						if (params.divs)
						{
							$.each(params.divs, function(k, v)
							{
								$('#' + v).html(res.response);
							});
						}
					}
				}
				else
				{
					if (params.divs)
					{
						$.each(params.divs, function(k, v)
						{
							$('#' + v).html(result);
						});
					}
				}
			}
			else
			{
				if (params.divs)
				{
					$.each(params.divs, function(k, v)
					{
						$('#' + v).html(result);
					});
				}
			}
			//preloader end
			if (params.preloader && params.preloader.type == 2)
			{
				preload(params.preloader.divs, 'off', params.preloader.style);
			}
			//callback
			if (typeof(callback) === "function") callback(result);
			return true;
		},
		error: function()
		{
			if (params.divs)
			{
				$.each(params.divs, function(k, v)
				{
					$('#' + v).html(l('Ajax error, please retry...'));
				});
			}
			return false;
		},
	});
} //end function
function preload(divs, action, style)
{
	if (style == 1)
	{
		var html = '<div style="height: 100%; width: 100%;" align="center"><img border="0" src="' + psl_path + 'views/img/ajax-loader.gif"></div>';
	}
	else if (style == 2)
	{
		var html = '<table cellpadding="0" cellspacing="0"><tr><td>Loading...&nbsp;&nbsp;</td><td><img border="0" src="' + psl_path + 'views/img/ajax-loader.gif"></td></tr></table>';
	}
	else if (style == 3)
	{
		var html = '<img border="0" src="' + psl_path + 'views/img/ajax-loader.gif">';
	}
	if (action == 'on')
	{
		$.each(divs, function(k, v)
		{
			$('#' + v).html(html);
		});
	}
	else
	{
		$.each(divs, function(k, v)
		{
			$('#' + v).html('');
		});
	}
} //end function
function getTemplateWidth()
{
	try
	{
		var res1 = $('#select_template option:selected').text();
		var res2 = res1.split("(");
		var res3 = res2[1].split("/");
		var res4 = res3[0].trim();
		//console.log(res4);
		return res4;
	}
	catch (e)
	{}
}

function getTemplateHeight()
{
	try
	{
		var res1 = $('#select_template option:selected').text();
		var res2 = res1.split("(");
		var res3 = res2[1].split("/");
		var res4 = res3[1].split(")");
		var res5 = res4[0].trim();
		//console.log(res5);
		return res5;
	}
	catch (e)
	{}
}

function updateTips(t)
{
	tips = $(".validateTips");
	tips.text(t).addClass("ui-state-highlight");
	setTimeout(function()
	{
		tips.removeClass("ui-state-highlight", 1500);
	}, 500);
}

function checkLength(o, n, min, max)
{
	if (o.val().length > max || o.val().length < min)
	{
		o.addClass("ui-state-error");
		updateTips(l("Length of") + " " + n + " " + l("must be between") + " " + min + " " + l("and") + " " + max + ".");
		return false;
	}
	else
	{
		return true;
	}
}

function checkRegexp(o, regexp, n)
{
	if (!(regexp.test(o.val())))
	{
		o.addClass("ui-state-error");
		updateTips(n);
		return false;
	}
	else
	{
		return true;
	}
}

function getUrlParameter(sParam)
{
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++)
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam)
		{
			return sParameterName[1];
		}
	}
}

function pslAddToolbarPrintLabelsBtn()
{
	if (_PS_VERSION_.substr(0,5) == '1.7.7')
	{
		$('div.toolbar-icons .wrapper').prepend('<a id="psl_print_labels" class="btn btn-outline-secondary" title="'+l('Print Shipping Labels')+'" href="javascript:{}"><div style="margin-top: 4px !important;"><i class="fa fa-print"></i> '+l('Print Labels')+'</div></a>');
	}
	else
	{
		$('div.btn-toolbar > ul, div.toolbarBox > ul').prepend('<li style="line-height: 40px; vertical-align: middle; color: #222222 !important; opacity: 0.3; filter: alpha(opacity=30);">|</li>');
		$('div.btn-toolbar > ul, div.toolbarBox > ul').prepend('<li><a id="psl_print_labels" class="psl toolbar_btn" title="'+l('Print Shipping Labels')+'" href="javascript:{}"><i style="margin-top: 2px !important;" class="fa fa-print fa-2x"></i><div style="margin-top: 4px !important;">'+l('Print Labels')+'</div></a></li>');
	}
}

function pslAddToolbarBtn(type)
{
	if (type == 'video') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-video" class="psl toolbar_btn" title="Module Video Tutorial" href="' + psl_video_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-youtube fa-2x"></i><div style="margin-top: 4px !important;">Module Video</div></a></li>');
	if (type == 'documentation') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-documentation" class="psl toolbar_btn" title="Module Documentation" href="' + psl_doc_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-life-ring fa-2x"></i><div style="margin-top: 4px !important;">Documentation</div></a></li>');
	if (type == 'contact') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-contact" class="psl toolbar_btn" title="Contact Developer" href="' + psl_support_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-code fa-2x"></i><div style="margin-top: 4px !important;">Contact Dev. (suggestions)</div></a></li>');
	if (type == 'modules') $('div.btn-toolbar > ul').prepend('<li><a id="desc-module-modules" class="psl toolbar_btn" title="Modules by this developer" href="' + psl_dev_modules_link + '" target="_blank"><i style="margin-top: 2px !important;" class="fa fa-puzzle-piece fa-2x"></i><div style="margin-top: 4px !important;">Dev. Modules</div></a></li>');
	if (type == 'separator') $('div.btn-toolbar > ul').prepend('<li style="line-height: 40px; vertical-align: middle; color: #222222 !important; opacity: 0.3; filter: alpha(opacity=30);">|</li>');
}

function sendSelectedOrders(selected)
{
	return $.ajax(
	{
		url: psl_grid_path + 'getSelectedOrders',
		type: 'POST',
		dataType: 'json',
		data:
		{
			'selected': selected
		},
		error: function(data) {}
	}).promise();
}

function generateQuickPreview(page_width, page_height, template_width, template_height, labels_horizontally, labels_vertically, spacing_between_labels_horizontally, spacing_between_labels_vertically, page_padding_left, page_padding_top, labels_border, rounded_corners_radius, percentage)
{
	percentage = typeof(percentage) != 'undefined' ? percentage : 60;
	spacing_between_labels_horizontally = typeof(spacing_between_labels_horizontally) != 'undefined' ? spacing_between_labels_horizontally : 1;
	spacing_between_labels_vertically = typeof(spacing_between_labels_vertically) != 'undefined' ? spacing_between_labels_vertically : 1;
	page_padding_left = typeof(page_padding_left) != 'undefined' ? page_padding_left : 0;
	page_padding_top = typeof(page_padding_top) != 'undefined' ? page_padding_top : 0;
	labels_border = typeof(labels_border) != 'undefined' ? labels_border : 1;
	rounded_corners_radius = typeof(rounded_corners_radius) != 'undefined' ? rounded_corners_radius : 3;
	var width = Math.floor(parseInt(page_width * percentage / 100)) + parseInt(spacing_between_labels_horizontally * labels_horizontally) + parseInt(1 * labels_horizontally); /*force borders*/
	var height = Math.floor(parseInt(page_height * percentage / 100)) + parseInt(spacing_between_labels_vertically * labels_vertically) + parseInt(1 * labels_vertically); /*force borders*/
	var inner_div_width = Math.floor(parseInt(template_width * percentage / 100)); //- (labels_horizontally * spacing_between_labels_horizontally);
	var inner_div_height = Math.floor(parseInt(template_height * percentage / 100)); //- (labels_vertically * spacing_between_labels_vertically);
	var preview = '<div align="left" style="vertical-align: left;  -webkit-box-shadow: 3px 3px 5px 0px rgba(50, 50, 50, 0.27); -moz-box-shadow: 3px 3px 5px 0px rgba(50, 50, 50, 0.27); box-shadow: 3px 3px 5px 0px rgba(50, 50, 50, 0.27);border:1px solid #acacac; background-color: #ffffff; width: ' + width + 'px; height: ' + height + 'px;">';
	preview += '<table cellspacing="0" cellpadding="0" style="border-collapse: separate; position: absolute; padding-left: ' + page_padding_left + 'px; padding-top: ' + page_padding_top + 'px;">';
	//set the browser settings
	$.browser.chrome = (typeof window.chrome === "object");
	if ($.browser.mozilla) var top = 'top: 50%;';
	else if ($.browser.chrome) var top = 'top: 10%;';
	else var top = 'top: 50%;';
	// general labelurile
	var cell_count = 1;
	for (i = 1; i <= labels_vertically; i++)
	{
		preview += '<tr>';
		for (j = 1; j <= labels_horizontally; j++)
		{
			preview += '<td class="label_out" align="center" style="margin:0px; padding: 0px; border-top: ' + spacing_between_labels_vertically + 'px solid #ffffff; border-bottom: ' + spacing_between_labels_vertically + 'px solid #ffffff; border-left: ' + spacing_between_labels_horizontally + 'px solid #ffffff; border-right: ' + spacing_between_labels_horizontally + 'px solid #ffffff; background-color: #ffffff; color: #555555; cursor:hand;cursor:pointer; width: ' + inner_div_width + 'px; height: ' + inner_div_height + 'px;">';
			preview += '<table width="100%" height="100%" cellspacing="0" cellpadding="0" style="border-collapse: separate; position: relative;"><tr><td align="center" class="label_in" style="visibility: visible; ' + ((labels_border == 0) ? '' : 'border: 1px solid #cccccc;') + ' background-color: #f0f0f0; border-radius: ' + rounded_corners_radius + 'px; -moz-border-radius: ' + rounded_corners_radius + 'px; -webkit-border-radius: ' + rounded_corners_radius + 'px; ">'
			preview += cell_count;
			preview += '</td></tr></table>';
			preview += '</td>';
			cell_count++;
		}
		preview += '</tr>';
	}
	preview += '</table></div>';
	return preview;
}

function l(name, prefix)
{
	prefix = typeof(prefix) != 'undefined' ? prefix : 'psl';	

	if (LANG[prefix].hasOwnProperty(name)) 
		return LANG[prefix][name];
	else
	{
		console.warn('Js translation ' + name + ' do not exists.')
		return name;
	}
}

function validateInput(text)
{
	if (/[^a-zA-Z0-9]/.test(text))
	{
		return false;
	}
	return true;
}

function hexc(colorval)
{
	var parts = colorval.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	delete(parts[0]);
	for (var i = 1; i <= 3; ++i)
	{
		parts[i] = parseInt(parts[i]).toString(16);
		if (parts[i].length == 1) parts[i] = '0' + parts[i];
	}
	return '#' + parts.join('');
}

function toObject(arr)
{
	var rv = {};
	for (var i = 0; i < arr.length; ++i)
		if (arr[i] !== undefined) rv[i] = arr[i];
	return rv;
}

function isNumeric(n)
{
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function isJson(str)
{
	try
	{
		JSON.parse(str);
	}
	catch (e)
	{
		return false;
	}
	return true;
}

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
	return true;
}