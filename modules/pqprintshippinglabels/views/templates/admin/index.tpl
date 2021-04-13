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
{$media|escape:'quotes':'UTF-8'}
<table border="0" style="width: 100%;" id="psl_box" class="psl radius5-top" bgcolor="#F7F7F7">
	<tr>
		<td>
			<div align="left" style="margin-top: 5px;">
				<table border="0">
					<tr>
						<td><img border="0" src="{$psl_module_path|escape:'quotes':'UTF-8'}views/img/pqprintshippinglabels.png"></td>
						<td><b>{$psl_module_version|escape:'quotes':'UTF-8'}</b></td>
					</tr>
				</table>
			</div>
			<div id="psl_tabs">
				<ul>
					<li><a href="#psl_tabs_1" style="line-height: 30px;">{l s='Configuration' mod='pqprintshippinglabels'}</a></li>
				</ul>
				<div id="psl_tabs_1">
					<table border="0" id="">
						<tr>
							<td valign="top">
								<table cellspacing="0" cellpadding="0" class="psl main-table">
									<tr>
										<td style="border-bottom: 1px solid #dddddd; background-color: #f7f7f7">
											<table border="0">
												<tr>
													<td><span class="psl label-tooltip">{l s='Load settings:' mod='pqprintshippinglabels'}</span></td>
													<td><span style="padding-right: 5px;" id="settings_ajax_span">
														{include file="$psl_module_templates_back_dir/ajax.settings.tpl"}
														</span><span style="padding-right: 5px;">
														<a href="javascript:{}" name="psl_delete_settings" id="psl_delete_settings" class="psl button" title="Delete settings"><span class="icon-trash fa fa-trash-o"></span> {l s='Delete' mod='pqprintshippinglabels'}</a>
														<a href="javascript:{}" name="psl_save_settings" id="psl_save_settings" class="psl button" title="Save below settings"><span class="icon-save fa fa-save"></span> {l s='Save below settings' mod='pqprintshippinglabels'}</a>
														<a href="javascript:{}" name="psl_save_as_settings" id="psl_save_as_settings" class="psl button" title="Save AS below settings"><span class="icon-save fa fa-save"></span> {l s='Save AS below settings' mod='pqprintshippinglabels'}</a>
														<a href="javascript:{}" name="psl_make_default_setting" id="psl_make_default_setting" class="psl button" title="Make this setting as default will appear selected when you print labels on the orders page."><span class="fa fa-thumb-tack"></span> {l s='Make default' mod='pqprintshippinglabels'}</a>
														</span>
													<span id="settings_ajax_load_span" style="padding-left: 0px;"></span></td>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style="padding-left: 0px;">
											<table border="0" width="100%" style="">
												<tr>
													<td valign="top">
														<table border="0">
															<input type="hidden" name="psl_order_state" id="psl_order_state" size="3" style="text-align: center" value="none" class="psl formfield1">
																														
															<tr>
																<td><span class="psl label-tooltip2">{l s='Page type:' mod='pqprintshippinglabels'}</span></td>
																<td><span style="padding-right: 5px;" id="page_type_ajax_span">{include file="$psl_module_templates_back_dir/ajax.pagetypes.tpl"}</span>
																	<span style="padding-right: 5px;">
																	<a href="javascript:{}" name="edit_page_type" id="edit_page_type" class="psl button" title="Edit page type"><span class="icon-edit fa fa-edit"></span></a>
																	<a href="javascript:{}" name="delete_page_type" id="delete_page_type" class="psl button" title="Delete page type"><span class="icon-trash fa fa-trash-o"></span></a>
																	<a href="javascript:{}" name="add_page_type" id="add_page_type" class="psl button" title="Add page type"><span class="icon-plus fa fa-plus"></span></a></span>
																<span id="page_type_ajax_load_span" style="padding-left: 0px;"></span></td>
															</tr>
															<tr>
																<td><span class="psl label-tooltip2">{l s='Label copies:' mod='pqprintshippinglabels'}</span></td>
																<td><input type="text" name="label_copies" id="label_copies" size="3" style="text-align: center" value="1" class="psl formfield1"></td>
															</tr>
															<tr>
																<td><span class="psl label-tooltip2">{l s='Labels per page x/y:' mod='pqprintshippinglabels'}</span></td>
																<td>
																	<input type="text" name="labels_horizontally" id="labels_horizontally" size="3" style="text-align: center" value="2" class="psl formfield1">
																	<input type="text" name="labels_vertically" id="labels_vertically" size="3" style="text-align: center" value="2" class="psl formfield1"></td>
																</tr>
																<tr>
																	<td><span class="psl label-tooltip2">{l s='Spacing between labels x/y (mm):' mod='pqprintshippinglabels'}</span></td>
																	<td>
																		<input type="text" name="spacing_between_labels_horizontally" id="spacing_between_labels_horizontally" size="3" style="text-align: center" value="1" class="psl formfield1">
																		<input type="text" name="spacing_between_labels_vertically" id="spacing_between_labels_vertically" size="3" style="text-align: center" value="1" class="psl formfield1"></td>
																	</tr>
																	<tr>
																		<td><span class="psl label-tooltip2">{l s='Page padding left/top (mm):' mod='pqprintshippinglabels'}</span></td>
																		<td>
																			<input type="text" name="page_padding_left" id="page_padding_left" size="3" style="text-align: center" value="0" class="psl formfield1">
																			<input type="text" name="page_padding_top" id="page_padding_top" size="3" style="text-align: center" value="0" class="psl formfield1"></td>
																		</tr>

																			<tr>
																				<td><span class="psl label-tooltip2">{l s='Labels border:' mod='pqprintshippinglabels'}</span></td>
																				<td><input type="checkbox" name="labels_border" id="labels_border" value="ON"></td>
																			</tr>
																			<tr>
																				<td><span class="psl label-tooltip2">{l s='Rounded corners radius (px):' mod='pqprintshippinglabels'}</span></td>
																				<td><input type="text" name="rounded_corners_radius" id="rounded_corners_radius" size="3" style="text-align: center" value="3" class="psl formfield1"></td>
																			</tr>
																			<tr>
																				<td><span class="psl label-tooltip2">{l s='Print one label per each product of order:' mod='pqprintshippinglabels'}</span></td>
																				<td><input type="checkbox" name="one_label_for_each_product" id="one_label_for_each_product" value="ON"></td>
																			</tr>
																			
																			<tr>
																			<td><span class="psl label-tooltip2">{l s='Export automatically on new order:' mod='pqprintshippinglabels'}</span></td>
																			<td>
																				<input type="checkbox" name="export_automatically_on_new_order" id="export_automatically_on_new_order" value=""><br>
																				<p class="psl help-block">{l s='The labels will be placed in:' mod='pqprintshippinglabels'} {$psl_module_path}pdfs/autoexported/</p>
																			</td>
																			</tr>
																			
																			<tr>
																				<td><span class="psl label-tooltip2">{l s='Barcodes settings:' mod='pqprintshippinglabels'}</span></td>
																				<td>
																					<table border="0">
																						<tr>
																							<td align="center">{l s='Type' mod='pqprintshippinglabels'}</td>
																							<td align="center">{l s='Width' mod='pqprintshippinglabels'}</td>
																							<td align="center">{l s='Height' mod='pqprintshippinglabels'}</td>
																						</tr>
																						<tr>
																							<td align="center">
																							<select size="1" name="barcodes_type" id="barcodes_type" style="padding: 2px;" class="psl formfield1">
																								<option value="code39">code39</option>
																								<option value="code128">code128</option>
																								<option value="ean13">ean13</option>
																								<option value="upc">upc</option>
																							</select>
																							</td>
																							<td align="center"><input type="text" name="barcodes_width" id="barcodes_width" size="3" style="text-align: center" value="200" class="psl formfield1"></td>
																							<td align="center"><input type="text" name="barcodes_height" id="barcodes_height" size="3" style="text-align: center" value="60" class="psl formfield1"></td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																		<table border="0">
																			<tr>
																				<td><span class="psl label-tooltip2">{l s='Label template:' mod='pqprintshippinglabels'}</span></td>
																				<td><span style="padding-right: 5px;" id="templates_ajax_span">{include file="$psl_module_templates_back_dir/ajax.templates.tpl"}</span>
																					<span style="padding-right: 5px;">
																					<a href="javascript:{}" name="edit_template" id="edit_template" class="psl button" title="Edit template"><span class="icon-edit fa fa-edit"></span></a>
																					<a href="javascript:{}" name="delete_template" id="delete_template" class="psl button" title="Delete template"><span class="icon-trash fa fa-trash-o"></span></a>
																					<a href="javascript:{}" name="add_template" id="add_template" class="psl button" title="Add template"><span class="icon-plus fa fa-plus"></span></a>
																					<a href="javascript:{}" name="save_template" id="save_template" class="psl button" title="Save template"><span class="icon-save fa fa-save"></span> {l s='Save below template' mod='pqprintshippinglabels'}</a>
																					</span>
																				<span id="select_template_ajax_load_span" style="padding-left: 0px;"></span></td>
																			</tr>
																		</table>
																	</td>
																	<td valign="top" align="right">
																		<table border="0">
																			<tr>
																				<td align="center">
																				<span class="psl label-tooltip2">{l s='Quick preview:' mod='pqprintshippinglabels'}</span></td>
																			</tr>
																			<tr>
																				<td align="right">
																					<div id="quick_preview"></div>
																				</td>
																			</tr>
																			<tr>
																				<td align="center" style="padding-top: 10px">
																				<span style="padding-right: 0px;"><a href="javascript:{}" name="preview_template" id="preview_template" class="psl button" title="Full preview"><span class="icon-eye-open fa fa-eye"></span> {l s='Full Preview' mod='pqprintshippinglabels'}</a></span></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>

																<tr>
																	<td style="border-top: 1px solid #CCCCCC">
																		<table border="0" width="100%" cellpadding="0" cellspacing="0">
																			<tr>
																				<td>
																				<p class="psl help-block">{l s='*Use the browser Mozilla Firefox so you can drag the layers. To delete, select them and press the key `Delete`.' mod='pqprintshippinglabels'}<br>{l s='**The template text must be inside a layer, any text outside will now be shown in PDF.' mod='pqprintshippinglabels'}</p>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					<table id="layer_controls" border="0" width="100%" style="" cellspacing="0" cellpadding="0">
																						<tr>
																							<td>
																								<table border="0" width="100%">
																									<tr>
																										<td align="left">
																											<input type="hidden" name="focused_layer_id" id="focused_layer_id" size="20" value="">
																											<table border="0" cellspacing="0" cellpadding="0">
																												<tr>
																													<td>{l s='From left:' mod='pqprintshippinglabels'}</td>
																													<td style="padding-right: 15px">
																														<table border="0" width="100%" bgcolor="#FFFFFF" style="border: 1px solid #CCCCCC" class="psl radius5">
																															<tr>
																																<td>
																																<span id="position_left_decrement" class="icon-minus-sign fa fa-minus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																<td>
																																	<input title="" type="text" name="position_left" id="position_left" size="2" style="text-align: right" value="" class="psl formfield1"></td>
																																	<td><span id="position_left_increment" class="icon-plus-sign fa fa-plus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																</tr>
																															</table>
																														</td>
																														<td>{l s='From top:' mod='pqprintshippinglabels'}</td>
																														<td style="padding-right: 15px">
																															<table border="0" width="100%" style="border: 1px solid #CCCCCC" bgcolor="#FFFFFF" class="psl radius5">
																																<tr>
																																	<td>
																																	<span id="position_top_decrement" class="icon-minus-sign fa fa-minus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																	<td>
																																		<input title="" type="text" name="position_top" id="position_top" size="2" style="text-align: right" value="" class="psl formfield1"></td>
																																		<td>
																																		<span id="position_top_increment" class="icon-plus-sign fa fa-plus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																	</tr>
																																</table>
																															</td>
																															<td>{l s='Width:' mod='pqprintshippinglabels'}</td>
																															<td style="padding-right: 15px">
																																<table border="0" width="100%" style="border: 1px solid #CCCCCC" bgcolor="#FFFFFF" class="psl radius5">
																																	<tr>
																																		<td>
																																		<span id="size_width_decrement" class="icon-minus-sign fa fa-minus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																		<td>
																																			<input title="" type="text" name="size_width" id="size_width" size="2" style="text-align: right" value="" class="psl formfield1"></td>
																																			<td><span id="size_width_increment" class="icon-plus-sign fa fa-plus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																		</tr>
																																	</table>
																																</td>
																																<td>{l s='Height:' mod='pqprintshippinglabels'}</td>
																																<td style="padding-right: 15px">
																																	<table border="0" width="100%" style="border: 1px solid #CCCCCC" bgcolor="#FFFFFF" class="psl radius5">
																																		<tr>
																																			<td>
																																			<span id="size_height_decrement" class="icon-minus-sign fa fa-minus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																			<td>
																																				<input title="" type="text" name="size_height" id="size_height" size="2" style="text-align: right" value="" class="psl formfield1"></td>
																																				<td><span id="size_height_increment" class="icon-plus-sign fa fa-plus-circle" style="font-size: 1em; cursor: hand; cursor: pointer;"></span></td>
																																			</tr>
																																		</table>
																																	</td>
																																	<td><a href="javascript:{}" name="remove_layer" id="remove_layer" class="psl button" title="{l s='Remove the selected layer from template' mod='pqprintshippinglabels'}"><span class="icon-trash fa fa-trash-o"></span> {l s='Remove this layer' mod='pqprintshippinglabels'}</a></td>
																																</tr>
																															</table>
																														</td>
																														<td align="right">
																															<select size="1" name="languages" id="languages" style="padding: 2px;" class="psl formfield1">
																																{foreach from=$languages key=key item=value}
																																<option value="{$value.id_lang|escape:'quotes':'UTF-8'}" {if $value.id_lang == $employee_default_language_id}selected{/if}>{$value.iso_code|escape:'quotes':'UTF-8'}</option>
																																{/foreach}
																															</select>
																														</td>
																													</tr>
																												</table>
																											</td>
																										</tr>
																									</table>
																									<div id="template_content_div">
																										{include file="{$textarea_file}" class_name='autoload_rte' content_name='template_content_div' input_name='template_content' input_value=$input_value}
																									</div>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																				<tr>
																					<td>&nbsp;</td>
																				</tr>
																			</table>
																		</td>
																		<td valign="top" width="10">
																		&nbsp;</td>
																		<td valign="top">
																			{literal}
																			<table cellspacing="0" cellpadding="0" class="psl menu">
																				<tr>
																					<th><span class="icon-list fa fa-list"></span> {/literal}{l s='VARIABLES' mod='pqprintshippinglabels'}{literal}</th>
																				</tr>
																				<tr class="psl title" id="help_tr" style="background-color: #f7f7f7">
																					<td><span class="fa fa-question-circle"></span><b><span class="psl label-tooltip">{/literal}{l s='HOW TO INSERT?' mod='pqprintshippinglabels'}{literal}</span></b></td>
																				</tr>

																				<tr class="psl title" id="order_related_tr">
																					<td><span class="fa fa-chevron-down"></span><span class="psl label-tooltip">{/literal}{l s='Order related (array):' mod='pqprintshippinglabels'}{literal}</span></td>
																				</tr>
																				<tbody id="order_related_tbody" style="display:none">
																					{/literal}
																					{foreach from=$order_fields key=key item=value}
																					<tr>
																						<td class="psl add-var">{literal}{$order.{/literal}{$value|escape:'quotes':'UTF-8'}{literal}}{/literal}</td>
																					</tr>
																					{/foreach}
																					{literal}
																				</tbody>
																				<tr class="psl title" id="order_products_related_tr">
																					<td><span class="fa fa-chevron-down"></span><span class="psl label-tooltip">{/literal}{l s='Order products related (loop):' mod='pqprintshippinglabels'}{literal}</span></td>
																				</tr>
																				<tbody id="order_products_related_tbody" style="display:none">
																					<tr>
																						<td class="psl" style="color: red;"><b>{/literal}{l s='Use only in foreach loops:' mod='pqprintshippinglabels'}</b><br>{literal}{foreach from=$order_products item=product}<br>{$product.field_name_from_database}<br>{/foreach}</td>
																					</tr>
																					<tr><td class="psl add-var">{$product.product_features.feature_field_from_database}</td></tr>
																					<tr><td class="psl add-var">{$product.product_attributes.attribute_field_from_database}</td></tr>
																					<tr><td class="psl add-var">{$product.product_combinations.combination_field_from_database}</td></tr>
																					{/literal}
																					{foreach from=$order_products_fields key=key item=value}
																					<tr>
																						<td class="psl add-var">{literal}{$product.{/literal}{$value|escape:'quotes':'UTF-8'}{literal}}{/literal}</td>
																					</tr>
																					{/foreach}
																					{literal}
																				</tbody>

																				<tr class="psl title" id="product_related_tr">
																					<td><span class="fa fa-chevron-down"></span><span class="psl label-tooltip">{/literal}{l s='Product related (array):' mod='pqprintshippinglabels'}{literal}</span></td>
																				</tr>
																				<tbody id="product_related_tbody" style="display:none">
																					<tr>
																						<td class="psl" style="color: red;">{/literal}
																						{l s='Use only when you check the option:' mod='pqprintshippinglabels'}<br>
																						<b>{l s='"print one label per each product of order"' mod='pqprintshippinglabels'}</b>
																						{literal}</td>
																					</tr>
																					<tr><td class="psl add-var">{$product_features.feature_field_from_database}</td></tr>
																					<tr><td class="psl add-var">{$product_attributes.attribute_field_from_database}</td></tr>
																					<tr><td class="psl add-var">{$product_combinations.combination_field_from_database}</td></tr>
																					{/literal}
																					{foreach from=$order_products_fields key=key item=value}
																					<tr>
																						<td class="psl add-var">{literal}{$product.{/literal}{$value|escape:'quotes':'UTF-8'}{literal}}{/literal}</td>
																					</tr>
																					{/foreach}
																					{literal}
																				</tbody>

																				<tr class="psl title" id="custom_related_tr">
																					<td><span class="fa fa-chevron-down"></span><span class="psl label-tooltip">{/literal}{l s='Custom:' mod='pqprintshippinglabels'}{literal}</span></td>
																				</tr>
																				<tbody id="custom_related_tbody" style="display:none">
																					{/literal}
																					{foreach from=$custom_vars_fields item=value}
																					<tr>
																						<td class="psl add-var">{$value|escape:'quotes':'UTF-8'}</td>
																					</tr>
																					{/foreach}
																					{literal}
																				</tbody>
																			</table>
																			{/literal}
																		</td>
																	</tr>
																</table>
															</div>
														</div>
													</td>
												</tr>
											</table>
											<div id="dialog-help" title="How to insert variables">
												<table border="0" width="100%">
													<tr>
														<td><span class="psl label-tooltip"><b>VARIABLES:</b></span></td>
													</tr>
													<tr>
														<td>
															{l s='You can insert the variables below in the template where your cursor is if you click on them.' mod='pqprintshippinglabels'}<br>
															{l s='You can use all the functions and modifiers from smarty template engine' mod='pqprintshippinglabels'} (<a href="http://www.smarty.net/" target="_blank">http://www.smarty.net/</a>)<br>
															<span style="color: red;">{l s='Don`t forget that when you use smarty functions in your label template design, press the `HTML` button in the template editor below and wrap the smarty function in HTML comment tags like this:' mod='pqprintshippinglabels'}<br>
															<b>{literal}&lt;!--{foreach from=$array item=item}--&gt;{$item.field_name}&lt;!--{/foreach}--&gt;{/literal}</b></span><br>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td><span class="psl label-tooltip"><b>{l s='LOOPS:' mod='pqprintshippinglabels'}</b></span></td>
													</tr>
													<tr>
														<td>
															{l s='Use the code below if you want to look to order`s products.' mod='pqprintshippinglabels'}<br>
															{literal}<b>&lt;!--{foreach from=$order_products item=product}--&gt;<br>
															&nbsp;&nbsp;&nbsp;&nbsp;{$product.product_name}<br>
															&lt;!--{/foreach}--&gt;</b><br>
															{/literal}
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td><span class="psl label-tooltip"><b>{l s='BARCODES:' mod='pqprintshippinglabels'}</b></span></td>
													</tr>
													<tr>
														<td>{l s='You can use any of the above variables with the keyword barcode inserted before to generate the barcode.' mod='pqprintshippinglabels'}<br>
														Ex.: {literal}<b>{barcode from=$order_number}</b> or <b>{barcode from="AnyString"}</b>{/literal}</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>

												</table>
											</div>
											<div id="dialog-form-page-types" title="{l s='Edit/Add page type' mod='pqprintshippinglabels'}" style="display:none">
												<p class="psl validateTips">{l s='All form fields are required.' mod='pqprintshippinglabels'}</p>
												<form>
													<input type="hidden" name="id_pt" id="id_pt" class="">
													<input type="hidden" name="default_pt" id="default_pt" class="">
													<table border="0">
														<tr>
															<td style="padding-right: 10px; padding-bottom: 5px">{l s='Name' mod='pqprintshippinglabels'}</td>
															<td style="padding-bottom: 5px" align="left">
																<input type="text" name="name_pt" id="name_pt" class=""></td>
															</tr>
															<tr>
																<td style="padding-right: 10px; padding-bottom: 5px">{l s='Width (mm)' mod='pqprintshippinglabels'}</td>
																<td style="padding-bottom: 5px" align="left">
																	<input type="text" name="width_pt" id="width_pt" class="" size="4" style="text-align: center"></td>
																</tr>
																<tr>
																	<td style="padding-right: 10px">{l s='Height (mm)' mod='pqprintshippinglabels'}</td>
																	<td align="left">
																		<input type="text" name="height_pt" id="height_pt" class="" size="4" style="text-align: center"></td>
																	</tr>
																</table>
															</form>
														</div>
														<div id="dialog-form-templates" title="{l s='Edit/Add template' mod='pqprintshippinglabels'}" style="display:none">
															<p class="psl validateTips">{l s='All form fields are required.' mod='pqprintshippinglabels'}</p>
															<form>
																<input type="hidden" name="id_t" id="id_t" class="">
																<input type="hidden" name="default_t" id="default_t" class="">
																<table border="0">
																	<tr>
																		<td style="padding-right: 10px; padding-bottom: 5px">{l s='Name' mod='pqprintshippinglabels'}</td>
																		<td style="padding-bottom: 5px" align="left">
																			<input type="text" name="name_t" id="name_t" class=""></td>
																		</tr>
																		<tr>
																			<td style="padding-right: 10px; padding-bottom: 5px">{l s='Width (mm)' mod='pqprintshippinglabels'}</td>
																			<td style="padding-bottom: 5px" align="left">
																				<input type="text" name="width_t" id="width_t" class="" size="4" style="text-align: center"></td>
																			</tr>
																			<tr>
																				<td style="padding-right: 10px; padding-bottom: 5px">{l s='Height (mm)' mod='pqprintshippinglabels'}</td>
																				<td align="left">
																					<input type="text" name="height_t" id="height_t" class="" size="4" style="text-align: center"></td>
																				</tr>

																				</table>
																			</form>
																		</div>
																		<script type="text/javascript">
																		{literal}
																		$( "#dialog-help" ).dialog({
																			autoOpen: false,
																			//height: 400,
																			width: 650,
																			modal: false,
																		});
																		{/literal}
																		</script>