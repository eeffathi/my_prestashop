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

(function ($) {
    $(function () {
        $('.seosa_product_label._product_page').each(function () {
            var image_block = $('#image-block');
            if (!image_block.length)
                image_block = $('#content-wrapper .product-cover');
            $(this).appendTo(image_block);
            $(this).show();
        });
    });

    $(function () {
        $('.seosa_product_label._catalog').each(function () {
            $(this).appendTo($(this).closest('.product-container, .ajax_block_product, .product-miniature').find('.product_img_link, .thumbnail-container, .product_image'));
            $(this).show();
        });
    });

    $(document).ajaxComplete(function(e) {
        $('.seosa_product_label._catalog').each(function () {
            $(this).appendTo($(this).closest('.product-container, .ajax_block_product, .product-miniature').find('.product_img_link, .thumbnail-container, .product_image'));
            $(this).show();
        });
    });

})(jQuery)

$(document).ready(function(){
    $('body').on('mouseenter mouseleave', '.seosa_product_label', function () {
        $(this).find('.seosa_label_hint').toggle();
    });
});