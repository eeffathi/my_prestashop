{*
* 2007-2016 PrestaShop
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
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2021 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<div id="product-labels" class="panel product-tab">


    <input type="hidden" name="submitted_tabs[]" value="Suppliers">

    <h3>{l s='Labels of current product' mod='seosaproductlabels'}</h3>

    <div class="alert alert-info">
        {l s='This interface allows you to specify the labels of the current product.' mod='seosaproductlabels'}<br>
        {l s='Labels will be displayed on the pages a product in the list of goods.' mod='seosaproductlabels'}<br>
    </div>

    <table class="table" style="width: 100%;">
        <colgroup>
            <col width="5%">
            <col width="30%">
            <col width="35%">
            <col width="20%">
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>{l s='ID' mod='seosaproductlabels'}</th>
            <th>{l s='Name' mod='seosaproductlabels'}</th>
            <th>{l s='Image or text' mod='seosaproductlabels'}</th>
            <th>{l s='Position' mod='seosaproductlabels'}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            {foreach from=$current_product_labels item=current_product_label}
                <tr>
                    <td>{$current_product_label['id_product_label']|escape:'quotes':'UTF-8'}</td>
                    <td>{$current_product_label['name']|escape:'quotes':'UTF-8'}</td>
                    <td>
                        {if $current_product_label['image_url']}
                            {$current_product_label['image_url']|escape:'quotes':'UTF-8'}
                        {elseif $current_product_label['text']}
                            {$current_product_label['text']|escape:'quotes':'UTF-8'}
                        {/if}
                    </td>
                    <td>
                        {if $current_product_label['position'] == 'top-left'}
                            {l s='Top left'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'top-center'}
                            {l s='Top center'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'top-right'}
                            {l s='Top right'  mod='seosaproductlabels'}
                        {/if}

                        {if $current_product_label['position'] == 'center-left'}
                            {l s='Center left'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'center-center'}
                            {l s='Center center'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'center-right'}
                            {l s='Center right'  mod='seosaproductlabels'}
                        {/if}


                        {if $current_product_label['position'] == 'bottom-left'}
                            {l s='Bottom left'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'bottom-center'}
                            {l s='Bottom center'  mod='seosaproductlabels'}
                        {/if}
                        {if $current_product_label['position'] == 'bottom-right'}
                            {l s='Bottom right'  mod='seosaproductlabels'}
                        {/if}
                    </td>
                    <td>
                        {if array_key_exists('id_product_label_location', $current_product_label)}
                            <button type="button" class="btn btn-default remove_product_label_location">{l s='Remove' mod='seosaproductlabels'}</button>
                            <input style="display: none" type="checkbox" name="seosa_remove_product_label_location[]" value="{$current_product_label['id_product_label_location']|escape:'quotes':'UTF-8'}"/>
                        {else}
                            {l s='Assigned to categorie or manufacturer' mod='seosaproductlabels'}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <hr/>
    <a class="btn btn-link bt-icon confirm_leave"
       href="{$link->getAdminLink('AdminProductLabels')|escape:'html':'UTF-8'}&addseosaproductlabels">
        <i class="icon-plus"></i> {l s='Create a new label'  mod='seosaproductlabels'} <i class="icon-external-link-sign"></i>
    </a>
    <hr/>
    <!-- new_product_label_form_label -->
    <div id="new_product_label_form" class="form new_product_label_form">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-5">
                    <label for="id_product_label" class="new_product_label_form_label control-label float-left margin-right">{l s='Product label' mod='seosaproductlabels'}:</label>
                    <div class="float-left">
                        <select name="seosa_id_product_label" id="id_product_label" class="new_product_label_form_select fixed-width-lg">
                            <option value=""></option>
                            {foreach from=$product_labels item=product_label}
                                <option value="{$product_label['id_product_label']|escape:'quotes':'UTF-8'}">{$product_label['name']|escape:'quotes':'UTF-8'}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <div class="col-lg-5">
                    <label class="control-label float-left margin-right" for="new_product_label_position">{l s='Position' mod='seosaproductlabels'}:</label>
                    <input type="hidden" value="center-center" id="new_product_label_position" name="seosa_product_label_position"/>
                    <table class="table">
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="top" data-h-pos="left"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="top" data-h-pos="center"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="top" data-h-pos="right"></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="center" data-h-pos="left"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position active" data-v-pos="center" data-h-pos="center"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="center" data-h-pos="right"></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="bottom" data-h-pos="left"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="bottom" data-h-pos="center"></button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default position" data-v-pos="bottom" data-h-pos="right"></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
	{if $ps_version < 1.7}
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i
					class="process-icon-cancel"></i> {l s='Cancel'  mod='seosaproductlabels'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default"><i
					class="process-icon-save"></i> {l s='Save'  mod='seosaproductlabels'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default"><i
					class="process-icon-save"></i> {l s='Save and stay'  mod='seosaproductlabels'}</button>
	</div>
	{/if}


    <style scoped="scoped">
        .new_product_label_form .table {
            width: auto;
        }
        .new_product_label_form .table td {
            padding: 1px !important;
            border: none !important;
        }
        .new_product_label_form .table button {
            width: 35px;
            height: 35px;
        }
        .new_product_label_form .table button.active {
            background-color: #00aff0;
            border-color: #008abd;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
    </style>

    <script>
        (function () {
            $('document').ready(function () {
                var tab = $('#product-labels');
                if (tab.length) {
                    var buttons = tab.find('button.position');

                    tab.on('click', 'button.position', function () {
                        buttons.removeClass('active');
                        $(this).addClass('active');
                        var vPos = $(this).data('v-pos');
                        var hPos = $(this).data('h-pos');
                        $('#new_product_label_position').val(vPos+'-'+hPos)
                    });

                    tab.on('click', 'button.remove_product_label_location', function () {
                        $(this).siblings('input[type=checkbox]').attr('checked', true);
                        $(this).closest('tr').fadeOut();
                    });
                }
            });

            {if $ps_version == 1.7}
                $('[name="submitAddproduct"], [name="submitAddproductAndStay"]').on('click', function (e) {
                    e.preventDefault();

                    var seosa_id_product_label = $('[name="seosa_id_product_label"]').val();
                    var seosa_product_label_position = $('[name="seosa_product_label_position"]').val();
                    var seosa_remove_product_label_location = [];

                    $('[name="seosa_remove_product_label_location[]"]:checked').each(function () {
                        seosa_remove_product_label_location.push($(this).val());
                    });

                    $.ajax({
                        url: document.location.href.replace('#'+document.location.hash, ''),
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            ajax: true,
                            action: 'save_product_label',
                            seosa_id_product_label: seosa_id_product_label,
                            seosa_product_label_position: seosa_product_label_position,
                            seosa_remove_product_label_location: seosa_remove_product_label_location,
                            id_product: $('#form_id_product').val()
                        },
                        success: function (r) {
                            if (!r.hasError)
                                document.location.reload();
                        }
                    });
                });
            {/if}
        })();
    </script>
</div>


