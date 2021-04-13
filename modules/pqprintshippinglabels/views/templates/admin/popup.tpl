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

<div id="dialog-form-generate-labels" title="{l s='Generate labels' mod='pqprintshippinglabels'}" style="display:none; text-align: center !important;">
	<table class="psl preview-table">
		<tr>
		<th align="center">{l s='Settings' mod='pqprintshippinglabels'}</th>
	<th align="center" style="display: none;">{l s='Preview Label' mod='pqprintshippinglabels'}</th>
</tr>
<tr>
	<td align="center" style="padding:10px" valign="top">
		<table border="0" width="100%">
			<tr>
				<td align="center">{l s='Load settings' mod='pqprintshippinglabels'}</td>
			</tr>
			<tr>
				<td align="center">
					<table border="0">
						<tr>
							<td>

								<span style="padding-right: 5px;" id="settings2_ajax_span">{include file="$psl_module_templates_back_dir/ajax.settings2.tpl"}</span>

							</td>
							<td>
								<select size="1" name="languages" id="psl_languages2" style="padding: 2px;" class="psl formfield1" autocomplete="off">
									{foreach from=$languages key=key item=value}
									<option value="{$value.id_lang|escape:'quotes':'UTF-8'}" {if $value.id_lang == $employee_default_language_id}selected{/if}>{$value.iso_code|escape:'quotes':'UTF-8'}</option>
									{/foreach}
								</select>
							</td>
							<td>
								<span id="psl_settings_ajax_load_span" style="padding-left: 5px;">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center">&nbsp;</td>
			</tr>
			<tbody id="change_orders_statuses_tbody">
			<tr>
				<td align="center">{l s='Change orders statuses' mod='pqprintshippinglabels'}</td>
			</tr>
			<tr>
				<td align="center">
					<select size="1" name="update_status" id="psl_update_status" class="psl formfield1">
						<option value="no">{l s='-don`t update-' mod='pqprintshippinglabels'}</option>
						{foreach from=$order_states key=key item=value}
						<option value="{$value.id_order_state|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			</tbody>
			<tr>
				<td align="center" style="padding-top:20px; padding-bottom:20px;"><div id="quick_preview"></div></td>
			</tr>
			<tr>
				<td style="border-bottom: 1px solid #eaedef" align="center">
					<span style="padding-top: 10px;"><a href="javascript:{}" name="export" id="psl_export" class="psl button"><span class="icon-download fa fa-download"></span> {l s='Export to .PDF' mod='pqprintshippinglabels'}</a></span>
				</td>
			</tr>
		</table>
	</td>
	<td id="psl_pdf_preview_td" align="center" style="padding:10px; display: none;">
		<object id="psl_pdf_preview_object" data="" type="application/pdf" style="width: 100%; height: 100%;">
			<embed id="psl_pdf_preview_embed" src="" type="application/pdf" />
		</object>
	</td>
</table>
<a href="{$psl_module_link|escape:'quotes':'UTF-8'}" style="line-height: 30px;">{l s='Go to module configuration page.' mod='pqprintshippinglabels'}</a>
</div>
<script type="text/javascript">
{literal}
		$( "#dialog-form-generate-labels" ).dialog({
			autoOpen: false,
			width: 'auto',
			modal: false,
		});
{/literal}
</script>