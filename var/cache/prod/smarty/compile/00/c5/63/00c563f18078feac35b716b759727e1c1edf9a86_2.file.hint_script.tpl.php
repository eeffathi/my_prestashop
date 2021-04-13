<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-11 04:29:54
  from '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/hint_script.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6072b3824655f5_76091801',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00c563f18078feac35b716b759727e1c1edf9a86' => 
    array (
      0 => '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/hint_script.tpl',
      1 => 1617642169,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6072b3824655f5_76091801 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['seosa_labels']->value, 'label');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['label']->value) {
?>
    <style>
        .seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
 {
            display: none;
            position: absolute;
            background: <?php if ($_smarty_tpl->tpl_vars['label']->value['hint_background']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['hint_background'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else { ?>#000000<?php }?>;
            color: <?php if ($_smarty_tpl->tpl_vars['label']->value['hint_text_color']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['hint_text_color'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else { ?>white<?php }?>;
            border-radius: 3px;
            <?php if ($_smarty_tpl->tpl_vars['label']->value['position'] == 'top-center' || $_smarty_tpl->tpl_vars['label']->value['position'] == 'center-center') {?>
                top: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['image_height'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;
            <?php } elseif ($_smarty_tpl->tpl_vars['label']->value['position'] == 'bottom-center') {?>
                bottom: <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['image_height'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
;
            <?php } else { ?>
                top: 0;
          <?php if (isset($_smarty_tpl->tpl_vars['label']->value['fix_hint_position'])) {?>
                <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: -10px;
                margin-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: -150px;
        <?php }?>
            <?php }?>
            z-index: 1000;
            opacity: <?php if ($_smarty_tpl->tpl_vars['label']->value['hint_opacity']) {
echo htmlspecialchars(floatval($_smarty_tpl->tpl_vars['label']->value['hint_opacity']), ENT_QUOTES, 'UTF-8');
} else { ?>1<?php }?>;
            width: 150px;
            padding: 5px;
        }
        .seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
:after {
            border-bottom: solid transparent 7px;
            border-top: solid transparent 7px;
        <?php if (isset($_smarty_tpl->tpl_vars['label']->value['fix_hint_position'])) {?>
            border-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: solid <?php if ($_smarty_tpl->tpl_vars['label']->value['hint_background']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['hint_background'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else { ?>#000000<?php }?> 10px;
          <?php }?>
          top: 10%;
            content: " ";
            height: 0;
        <?php if (isset($_smarty_tpl->tpl_vars['label']->value['fix_hint_position'])) {?>
            <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: 100%;
          <?php }?>
            position: absolute;
            width: 0;
        }
    </style>
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
