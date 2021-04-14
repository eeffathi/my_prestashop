<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-13 10:56:19
  from '/var/www/html/prestashop/admin876j0mvg3/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6075b113041303_14307896',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '854fc7a42165bccbbc4207acf7c43f65f39a567e' => 
    array (
      0 => '/var/www/html/prestashop/admin876j0mvg3/themes/default/template/content.tpl',
      1 => 1617188544,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6075b113041303_14307896 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
