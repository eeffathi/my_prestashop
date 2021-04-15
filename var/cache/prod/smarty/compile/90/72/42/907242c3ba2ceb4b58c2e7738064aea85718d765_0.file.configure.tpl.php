<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-13 00:00:06
  from '/var/www/html/prestashop/modules/brandlist/views/templates/admin/configure.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60751746a80d03_60289531',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '907242c3ba2ceb4b58c2e7738064aea85718d765' => 
    array (
      0 => '/var/www/html/prestashop/modules/brandlist/views/templates/admin/configure.tpl',
      1 => 1617854643,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60751746a80d03_60289531 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Brands List','mod'=>'brandlist'),$_smarty_tpl ) );?>
</h3>
	<p>
		<strong><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'List brands in your home page.','mod'=>'brandlist'),$_smarty_tpl ) );?>
</strong><br />
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Let\'s configure brands list module.','mod'=>'brandlist'),$_smarty_tpl ) );?>

	</p>
</div><?php }
}
