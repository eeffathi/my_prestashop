/**
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
 */

jQuery('document').ready(function ($) {
    var excluded_field$ = $('#pl_excluded').next('.select2');

    setTimeout(requestProduct, 500);

    $('[name="select_for"], ' +
        '[name="include_category_product"], ' +
        '[name="categories[]"], ' +
        '[name="manufacturer[]"], ' +
        '[name="feature[]"], ' +
        '[name="supplier[]"], ' +
        '[name="cart_rules_selected[]"]').change({field:excluded_field$}, function(event){
        requestProduct(event.data.field);
    });

    $('#seosaproductlabels_form_submit_btn').live('click', function (e) {
        e.preventDefault();
        $('[name="cart_rules_available[]"] option:not(:selected)').remove();
        $(this).closest('form').submit();
    });

    $('#product_status-btn-group').find('button').live('click', function () {
        requestProduct(false);
    });

    $('[name="cart_rules_available[]"]').closest('.form-control-static').find('#addSwap, #removeSwap').live('click', function (e) {
        e.preventDefault();
        requestProduct(false);
    });

    if (typeof bindSwapButton == 'undefined') {
        bindSwapButton('add', 'available', 'selected');
        bindSwapButton('remove', 'selected', 'available');
        $('button:submit').click(bindSwapSave);

        function bindSwapSave()
        {
            if ($('#selectedSwap option').length !== 0)
                $('#selectedSwap option').attr('selected', 'selected');
            else
                $('#availableSwap option').attr('selected', 'selected');
        }

        function bindSwapButton(prefix_button, prefix_select_remove, prefix_select_add)
        {
            $('#'+prefix_button+'Swap').on('click', function(e) {
                e.preventDefault();
                $('#' + prefix_select_remove + 'Swap option:selected').each(function() {
                    $('#' + prefix_select_add + 'Swap').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
                    $(this).remove();
                });
                $('#selectedSwap option').prop('selected', true);
            });
        }
    }

    $('#product_condition-btn-group').find('button').live('click', function () {
        requestProduct(false);
    });

	
    $('.btn-group-radio [name=label_type]').change(function(){
        var image_row = $('[name*=image]').parents('.img-block');
        var text_row = $('[name^=text]').parents('.text-block');
        if($('#select_type_image').prop('checked')){
            text_row.hide();
            image_row.show();
        } else {
            image_row.hide();
            text_row.show();
        }
    });
    $('.btn-group-radio [name=label_type]').trigger('change');

    $('.button-tab').live('click', function () {
        $('[name="select_for"]').val($(this).data('for'));
        $('.button-tab').removeClass('btn-success').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-success');
        $('.tab-content').slideUp();
        $('.content_' + $(this).data('tab')).slideDown();

        // bnt child-category
        if ($('.categories').hasClass('btn-success')) {
            $('.js-child-category').addClass('active');
        } else {
            $('.js-child-category').removeClass('active');
        }

        requestProduct();
    });

    // bnt child-category
    if ($('.categories').hasClass('btn-success')) {
        $('.js-child-category').addClass('active');
    } else {
        $('.js-child-category').removeClass('active');
    }

    $('.content_categories').find('a[onclick*="checkAllAssociatedCategories"]').live('click', requestProduct);
    $('#quantity, #quantity_max').on('keyup', requestProduct);

    //   datepicker icon
    $('.input-group-addon').on('click', function () {
        $(this).siblings('input').focus();
    });
});

function requestProduct(field){
    var categories$ = $('[name="categories[]"]:checked');
    var manufacturers$ = $('[name="manufacturer[]"]');
    var features$ = $('[name="feature[]"]');
    var suppliers$ = $('[name="supplier[]"]');
    var status$ = $('[name="product_status"]');
    var cart_rules$ = $('[name="cart_rules_selected[]"]');
    var cart_rules_selected = [];
    cart_rules$.find('option').each(function (i, el) {
        cart_rules_selected.push($(el).val());
    });
    var condition$ = $('[name="product_condition"]');
    var count_min$ = $('[name="quantity"]');
    var count_max$ = $('[name="quantity_max"]');

    var data = {};
    data.id_product_label = $('#id_product_label').val();
    data.ajax = true;
    data.action = 'get_product_for_excl_incl';
    data.activ = $('.button-tab.btn-success').data('tab');

    if ($('.btn-success').data('tab') == 'categories') {
        data.categories = [];
        categories$.each(function () {
            data.categories.push($(this).val());
        });
    }

    data.manufacturers = manufacturers$.val();
    if (data.manufacturers == null) {
        data.manufacturers = [];
    }
    data.suppliers = suppliers$.val();
    if (data.suppliers == null) {
        data.suppliers = [];
    }
    data.product_status = status$.val();
    data.cart_rules_selected = cart_rules_selected;
    if (data.cart_rules_selected == null) {
        data.cart_rules_selected = [];
    }
    data.product_condition = condition$.val();
    data.include_category_product = $('input[name="include_category_product"]:checked').val();

    data.count_range = (parseInt(count_min$.val()) || 0) + '-' + (parseInt(count_max$.val()) || 0);

    $.ajax({
        url: document.location.href,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (r) {
            $('#pl_excluded').empty();
            $('#pl_included').empty();
            if (!r.hasError){
                for(var i in r.data) {
                    $('<option value="'+r.data[i]['id_product']+'" '+r.data[i]['excl_selected']+'>'+r.data[i]['id_product']+' '+r.data[i]['name']+'</option>').appendTo($('#pl_excluded'));
                    $('<option value="'+r.data[i]['id_product']+'" '+r.data[i]['incl_selected']+'>'+r.data[i]['id_product']+' '+r.data[i]['name']+'</option>').appendTo($('#pl_included'));
                }
            }
            $('[name="excluded[]"]').select2();
            $('[name="included[]"]').select2();
        }
    });
}