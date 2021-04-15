<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 14:16:02
  from '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/right_column_product.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_607882e236cad2_31761076',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '22e07413ef30209eff524efd6c8bf5dee2e061e9' => 
    array (
      0 => '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/right_column_product.tpl',
      1 => 1617642169,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_607882e236cad2_31761076 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['seosa_product_labels']->value, 'product_label');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product_label']->value) {
?>
  <?php if ($_smarty_tpl->tpl_vars['product_label']->value['quantity'] <= $_smarty_tpl->tpl_vars['product_label']->value['quantity_prod'] && $_smarty_tpl->tpl_vars['product_label']->value['quantity_max'] >= $_smarty_tpl->tpl_vars['product_label']->value['quantity_prod'] || $_smarty_tpl->tpl_vars['product_label']->value['quantity'] == ' ' || !isset($_smarty_tpl->tpl_vars['product_label']->value['select_for']) || $_smarty_tpl->tpl_vars['product_label']->value['quantity_max'] == '') {?>
  <?php if ($_smarty_tpl->tpl_vars['product_label']->value['max_price'] > $_smarty_tpl->tpl_vars['product_label']->value['price'] && $_smarty_tpl->tpl_vars['product_label']->value['mini_price'] < $_smarty_tpl->tpl_vars['product_label']->value['price'] || $_smarty_tpl->tpl_vars['product_label']->value['max_price'] == 0 && $_smarty_tpl->tpl_vars['product_label']->value['mini_price'] == 0) {?>
    <div class="seosa_product_label _product_page <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['position'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"
            <?php if ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'image' && $_smarty_tpl->tpl_vars['product_label']->value['image_css']) {?>
    style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php } elseif ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'text' && $_smarty_tpl->tpl_vars['product_label']->value['text']) {?> style="width: auto; height: auto;"<?php }?>>
               <p id="mini_price" style="display: none" data-min="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product_label']->value['mini_price'], ENT_QUOTES, 'UTF-8');?>
"></p>
      <p id="max_price" style="display: none"  data-max="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['product_label']->value['max_price'], ENT_QUOTES, 'UTF-8');?>
"></p>
        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['url']) {?>
        <a target="_blank" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['url'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'image') {?>
            <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_url'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['product_label']->value['image_css']) {?> style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php }?> alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['name'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"/>
        <?php } elseif ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'text') {?>
            <span style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['text_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['text'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['url']) {?>
            </a>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['hint']) {?>
            <div class="seosa_label_hint seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
 <?php if ($_smarty_tpl->tpl_vars['product_label']->value['id_product']) {?>seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product']), ENT_QUOTES, 'UTF-8');
}?>">
                <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'cleanHtml' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['hint'] ));?>

            </div>

          <style>
            .seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product']), ENT_QUOTES, 'UTF-8');?>
 {
              <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>right<?php } else { ?>left<?php }?>: auto;
            <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: -10px;
              <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>margin-right<?php } else { ?>margin-left<?php }?>: 0;
              <?php if ($_smarty_tpl->tpl_vars['product_label']->value['position'] != 'top-center' && $_smarty_tpl->tpl_vars['product_label']->value['position'] != 'center-center' && $_smarty_tpl->tpl_vars['product_label']->value['position'] != 'bottom-center') {?>
                margin-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: -150px;
              <?php }?>
            }
                                      
              <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>border-right<?php } else { ?>border-left<?php }?>: 0;
              <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>right<?php } else { ?>left<?php }?>: auto;
            }
          </style>
        <?php }?>
    </div>
    <?php }?>
  <?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
