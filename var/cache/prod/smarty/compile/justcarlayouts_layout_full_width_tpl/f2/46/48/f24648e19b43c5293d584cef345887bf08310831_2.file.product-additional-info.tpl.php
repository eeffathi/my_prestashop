<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 14:16:02
  from '/var/www/html/prestashop/themes/justcar/templates/catalog/_partials/product-additional-info.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_607882e219ac46_12419751',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f24648e19b43c5293d584cef345887bf08310831' => 
    array (
      0 => '/var/www/html/prestashop/themes/justcar/templates/catalog/_partials/product-additional-info.tpl',
      1 => 1618128907,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_607882e219ac46_12419751 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="product-additional-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductAdditionalInfo','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

</div>
<?php }
}
