<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-12 21:51:52
  from '/var/www/html/prestashop/modules/ordersexport/views/templates/hook/config.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6074f938e32bb4_60643229',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b040e71c4f31204b6e57392b2bd33a21cdf4180' => 
    array (
      0 => '/var/www/html/prestashop/modules/ordersexport/views/templates/hook/config.tpl',
      1 => 1617643886,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6074f938e32bb4_60643229 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="alert alert-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'To execute your cron tasks, please insert the following line in your cron tasks manager:','mod'=>'ordersexport'),$_smarty_tpl ) );?>

  <br>
  <br>
  <ul class="list-unstyled<?php if ($_smarty_tpl->tpl_vars['schedule_tab']->value) {?> schedule_tab<?php }?>">
    <li><code>0 * * * * curl "<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['schedule_url']->value,'htmlall','UTF-8' ));?>
"</code></li>
  </ul>
</div><?php }
}
