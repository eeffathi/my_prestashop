/*
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code.
 *
 * @author    Active Design <office@activedesign.ro>
 * @copyright 2016-2018 Active Design
 * @license   LICENSE.txt
 */
$(function () {
    if ($('#myaffiliateaccount-summary .progress').length) {
        $('#myaffiliateaccount-summary .progress').tooltip();
    }
    $(document).on('change', '.requestapayment-form select[name="payment_method"]', function () {
        var val = $(this).val();
        $('.paymentmethodfields-container').find('.fields').hide();
        if ($('.paymentmethodfields-container').find('.fields-' + val).length) {
            $('.paymentmethodfields-container').find('.fields-' + val).show();
        }
    });
    $(document).on('submit', 'form.requestapayment-form', function (e) {
        if (typeof doNotPrevent == 'undefined' || doNotPrevent == false) {
            e.preventDefault();
            var amount = parseFloat($('.requestapayment-form input[name="amount"]').val());
            var min = $('.requestapayment-form input[name="amount"]').data('min');
            var max = $('.requestapayment-form input[name="amount"]').data('max');
            if ($('select[name="payment_method"]').val() == "0") {
                alert(choosemethod_error);
            }
            else if (!$.isNumeric(amount) || amount < min || amount > max) {
                alert(wrongamount_error.replace('%min%', formatCurrency(min, currencyFormat, currencySign, currencyBlank)).replace('%max%', formatCurrency(max, currencyFormat, currencySign, currencyBlank)));
            }
            else {
                doNotPrevent = true;
                $(this).trigger('submit');
            }
        }
    });
    $(document).on('click', '.text-container .icon-copy-container, .banner-container .icon-copy-container', function (e) {
        e.preventDefault();
        $this = $(this);
        var content = $this.closest('.text-container, .banner-container').find('pre .copy-content');
        // Select the email link anchor text  
        var copy = content[0];
        var range = document.createRange();
        range.selectNode(copy);
        window.getSelection().addRange(range);
        var success = false;

        try {
            // Now that we've selected the anchor text, execute the copy command  
            var successful = document.execCommand('copy');
            if (!successful) {
                $.fancybox({
                    moda: true,
                    content: "Your browser does not support copy commands. Please copy manually."
                });
            }
            else {
                success = true;
                $this.removeClass('icon-copy').addClass('icon-thumbs-up');
            }
            //var msg = successful ? 'successful' : 'unsuccessful';  
            //console.log('Copy email command was ' + msg);  
        } catch (err) {
            $.fancybox({
                moda: true,
                content: "Your browser does not support copy commands. Please copy manually."
            });
        }
        if (!success) {
            $this.removeClass('icon-copy').addClass('icon-thumbs-down');
        }
        setTimeout(function () {
            $this.removeClass('icon-thumbs-down').removeClass('icon-thumbs-up').addClass('icon-copy');
        }, 1000);

        // Remove the selections - NOTE: Should use
        // removeRange(range) when it is supported  
        window.getSelection().removeAllRanges();
    });

    // Clipboard
    var clipboard = new Clipboard('.btn-copy');

    $('.btn-copy').popover({
        trigger: 'manual',
        placement: 'top'
    });

    function setTooltip(btn, message) {
        $(btn).attr('data-content', message)
            .popover('show');
    }

    function hideTooltip(btn) {
        setTimeout(function () {
            $(btn).popover('hide');
        }, 1000);
    }

    clipboard.on('success', function (e) {
        setTooltip(e.trigger, 'Copied!');
        hideTooltip(e.trigger);
    });

    // BS Tooltips
    $('[data-toggle="tooltip"]').tooltip({container: "body"});

    // Terms checkbox
    $('#psaff_terms_and_conditions').change(function () {
        $('#psaff_register').prop('disabled', !$(this).is(':checked'));
    });

    // Voucher exchange rate preview
    $('#psaff_voucher_amount').on('keyup', function () {
        if (!$('#psaff_voucher_amount_final').length) {
            return;
        }

        var exchange_rate = parseFloat($(this).data('exchange-rate')),
            amount = parseFloat($(this).val());

        if (!isNaN(exchange_rate) && !isNaN(amount)) {
            $('#psaff_voucher_amount_final').val(Number(exchange_rate * amount).toFixed(2));
        }
    });

    $(document).on('click', '.voucher_templates_area .voucher_template', function(e) {
        changeVoucherTemplate($(this));
    });
    //aff details modal
    $(document).on('click', '.affiliate_modal .aff_details_close', function(){
        $('.affiliate_modal').fadeOut();
    });
    $('#myaffiliateaccount-mlm td').on('click', function(){
        $('.affiliate_modal').fadeOut();
        $('#affiliate_modal_'+$(this).parent().attr("data-id-aff")).fadeIn();
    });
    $('#myaffiliateaccount .alert').on('click', function(){
        $('.affiliate_modal').fadeOut();
        $('#affiliate_modal_'+$(this).attr("data-id-aff")).fadeIn();
    });

    function checkIfVoucherTemplateIsSet()
    {
        if ($("#id_vts").val()) {
            return;
        }
        var id_voucher_template = $('#id_voucher_template').val();

        if (!id_voucher_template || id_voucher_template == '0') {
            $('#vouchers_to_share_form_submit').prop('disabled', true).addClass('disabled');
        } else {
            $('#vouchers_to_share_form_submit').prop('disabled', false).removeClass('disabled');
        }
    }

    function changeVoucherTemplate(el)
    {
        if ($('#voucher_code_prefix').parent().hasClass('voucher_hasprefix')) {
            return;
        }
        if (typeof el == 'object') {
            el.parent().find('.voucher_template.active').removeClass('active');
            el.addClass('active');
        }
        $('#id_voucher_template').val($('.voucher_template.active').data('id_voucher_template'));
        var code_prefix = $('.voucher_template.active').data('voucher_code');
        if (code_prefix) {
            $('#voucher_code_prefix').text(code_prefix + '_').parent().show();
        } else {
            $('#voucher_code_prefix').text('').parent().hide();
        }
        checkIfVoucherTemplateIsSet();
    }

    $(document).ready(changeVoucherTemplate);
    $(document).ready(checkIfVoucherTemplateIsSet);
});