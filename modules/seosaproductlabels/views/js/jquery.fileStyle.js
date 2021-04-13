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

(function () {
    var containers = [];

    function FileStyle(elem)
    {
        var _this = this;
        this.input = $(elem);
        this.files = [];
        this.init = function ()
        {
            var wrapp = $('<label></label>')
                .attr('for', 'file_style_'+this.input.data('file-style'))
                .addClass('file_style');
            var text = $('<span></span>').addClass('input_file_text')
                .html(this.input.data('file-text'));
            this.input.attr('id', 'file_style_'+this.input.data('file-style'));
            wrapp.append(text);

            this.input.live('change', function () {
                var file = $(this).get(0);
                var self = $(this);
                var text_input = self.parent().find('.input_file_text');
                if (text_input.length)
                {
                    var files = [];
                    if (file.files.length)
                    {
                        $.each(file.files, function (index, file) {
                            files.push('<span class="file_item" title="'+file.name+'">'+file.name.substr(0, 10)+'...</span>');
                        });
                        _this.files = files;
                        text_input.html(files.join(''));
                        self.closest('div').find('img').css({opacity: 0.2});
                    }
                    else
                        text_input.html(self.data('file-text'));
                }
            });

            wrapp.append(this.input.clone(true));
            this.input.replaceWith(wrapp);
        };
        
        this.getFiles = function () {
          return this.files;  
        };
    }

    $.fn.fileStyle = function (method) {
        var response = null;
        $.each(this, function (index, item) {
            var elem = $(item);
            var fileStyle = null;
            var id = null;

            if (!elem.is('[data-file-style]'))
            {
                id = containers.length;
                elem.attr('data-file-style', id);
                fileStyle = new FileStyle(item);
                fileStyle.init();
                containers.push(fileStyle);
            }
            else
            {
                id = elem.attr('data-file-style');
                fileStyle = containers[id];
            }

            if (method && fileStyle != null)
            {
                if (typeof fileStyle[method] != 'undefined')
                    response = fileStyle[method](arguments);
                else
                    console.error('Method "'+method+'" not exists in fileStyle.jquery');
            }
        });
        return response;
    }
})();