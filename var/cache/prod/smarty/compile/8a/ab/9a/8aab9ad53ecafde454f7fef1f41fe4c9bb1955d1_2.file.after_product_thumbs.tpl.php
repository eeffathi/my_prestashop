<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 14:16:02
  from '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/after_product_thumbs.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_607882e2054fa2_37295522',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8aab9ad53ecafde454f7fef1f41fe4c9bb1955d1' => 
    array (
      0 => '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/after_product_thumbs.tpl',
      1 => 1617642169,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./right_column_product.tpl' => 1,
  ),
),false)) {
function content_607882e2054fa2_37295522 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:./right_column_product.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
echo '<script'; ?>
>
if (typeof $ != 'undefined') {
    $(function () {
        seosaproductlabels.replaceStickersOnProductPage();
    })
}
<?php echo '</script'; ?>
>
<?php }
}
