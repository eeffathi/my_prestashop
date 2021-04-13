{*
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
*}

<div id="template_content_div" style="display:block;">
	<textarea cols="100" rows="15" type="text" id="template_content" name="template_content" class="psl autoload_rte">{$input_value|escape:'quotes':'UTF-8'}</textarea>
	<span class="psl counter" max="{if isset($max)}{$max|escape:'quotes':'UTF-8'}{else}none{/if}"></span>
</div>

<script type="text/javascript">

	var iso     = '{$iso_tiny_mce|escape:'quotes':'UTF-8'}';
	var pathCSS = '{$psl_module_path|escape:'quotes':'UTF-8'}views/css/';
	var ad = '{$ad|escape:'quotes':'UTF-8'}';

	 function isTinyHigherVersion() {
	 	if (tinyMCE.majorVersion >= 4)
	 		return true;
	 	return false;
	};

	jQuery(document).ready(function() {

		var default_config = {

			setup : function(ed) {

				 if (isTinyHigherVersion()) {
					ed.on('init', function(e){
						// tinyProductsInit(ed);
					});
				 } else {
				 	ed.onInit.add(function(ed) {
				 		//tinyProductsInit(ed);
				 	});	
				 	
				 }

			}
		};
		
		default_config['editor_selector'] = "{if isset($class_name)}{$class_name|escape:'quotes':'UTF-8'}{else}autoload_rte{/if}";
		default_config['content_css'] = pathCSS+"back.css";
		default_config['convert_urls'] = false;
		default_config['statusbar'] = true;
		default_config['entity_encoding'] = "numeric";
	
		// V4
		 if (isTinyHigherVersion()) 
		 {
		 	default_config['plugins'] = "layer advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table contextmenu directionality template textcolor paste fullpage textcolor colorpicker";
			default_config['toolbar1'] = "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect";
			default_config['toolbar2'] = "cut copy paste | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image code | insertdatetime preview | forecolor backcolor";
			default_config['toolbar3'] = "insertlayer | table | hr removeformat | charmap | ltr rtl | visualchars visualblocks nonbreaking restoredraft";
			default_config['menubar'] = false;
			default_config['toolbar_items_size'] = 'small';
			//default_config['object_resizing'] = 'div';
			//default_config['skin'] = 'prestashop';
			default_config['height'] = "450";
			default_config['nowrap'] = true;
			default_config['remove_linebreaks'] = false;

		} 
		// V3
		else 
		{
			default_config['plugins'] = "layer"; 
			default_config['object_resizing'] = true;
			default_config['force_p_newlines'] = false;
			default_config['remove_linebreaks'] = false;
			default_config['force_br_newlines'] = true;
			default_config['forced_root_block'] = '';
			//default_config['preformatted'] = true;
			default_config['mode'] = "textareas";
			default_config['theme'] = "advanced";
			default_config['theme_advanced_buttons1'] = "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,image,insertlayer,hr,separator,fontsizeselect,formatselect,separator,code";
			default_config['theme_advanced_buttons2'] = "";
			default_config['theme_advanced_buttons3'] = "";
			default_config['theme_advanced_toolbar_location'] = "top";
			default_config['theme_advanced_toolbar_align'] = "left";
			default_config['theme_advanced_statusbar_location'] = "bottom";
			default_config['theme_advanced_resizing'] = false;
			default_config['theme_advanced_fonts'] = "Helvetica=helvetica;Courier New=courier new,courier;Times New Roman=times new roman,times;";
			default_config['theme_advanced_font_sizes'] = "4pt,6pt,8pt,10pt,12pt,14pt,16pt,18pt,20pt,24pt,36pt";
		 	default_config['width'] = "100%";
		 	default_config['height'] = "500";
		 	default_config['nowrap'] = true;
 			default_config['setup'] = function(ed) {

								        ed.onClick.add(function(ed, e) 
								        {
								        	try
								        	{
									            if (e.target.nodeName.toLowerCase() == 'div') 
									            	var element = e.target;
									            else
									            {
									            	var element = $(e.target).closest("div[style*='position: absolute']");
									            	element = element.get(0);
									            }
	
								            	var position_top = $(element).css('top'); $("#position_top").val( position_top.substring(position_top, position_top.length - 2) );
								            	var position_left = $(element).css('left'); $("#position_left").val( position_left.substring(position_left, position_left.length - 2) );
								            	var size_width = $(element).css('width'); $("#size_width").val( size_width.substring(size_width, size_width.length - 2) );
								            	var size_height = $(element).css('height'); $("#size_height").val( size_height.substring(size_height, size_height.length - 2) );							            	
								            	var id = $(element).attr('id');
								            
								            	if (typeof id !== typeof undefined && id !== false) 
								            		$('#focused_layer_id').val( $(element).attr('id') );
								            	else
								            	{
								            		var time = new Date().getTime();
								            		element.setAttribute('id', time );
								            		$('#focused_layer_id').val( $(element).attr('id') );
								            	}

											} catch(e){}
								        });
					        		};
		}
		tinyMCE.init( default_config );
	});
</script>