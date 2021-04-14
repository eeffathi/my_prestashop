<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-13 04:00:54
  from '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/product_list_reviews.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60754fb6ea9ff6_13106084',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e749584add1c0fa94b43bed5135c4929ab4c767b' => 
    array (
      0 => '/var/www/html/prestashop/modules/seosaproductlabels/views/templates/hook/product_list_reviews.tpl',
      1 => 1617642169,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60754fb6ea9ff6_13106084 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['seosa_product_labels']->value, 'product_label');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['product_label']->value) {
?>
    <?php if (($_smarty_tpl->tpl_vars['product_label']->value['image_url'] && !$_smarty_tpl->tpl_vars['product_label']->value['text']) || $_smarty_tpl->tpl_vars['product_label']->value['text']) {?>
      <?php if ($_smarty_tpl->tpl_vars['product_label']->value['quantity'] <= $_smarty_tpl->tpl_vars['product_label']->value['quantity_prod'] && $_smarty_tpl->tpl_vars['product_label']->value['quantity_max'] >= $_smarty_tpl->tpl_vars['product_label']->value['quantity_prod'] || $_smarty_tpl->tpl_vars['product_label']->value['quantity'] == ' ' || !isset($_smarty_tpl->tpl_vars['product_label']->value['select_for']) || $_smarty_tpl->tpl_vars['product_label']->value['quantity_max'] == '') {?>
        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['max_price'] > $_smarty_tpl->tpl_vars['product_label']->value['price'] && $_smarty_tpl->tpl_vars['product_label']->value['mini_price'] < $_smarty_tpl->tpl_vars['product_label']->value['price'] || $_smarty_tpl->tpl_vars['product_label']->value['max_price'] == 0 && $_smarty_tpl->tpl_vars['product_label']->value['mini_price'] == 0) {?>
            <?php if ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'image') {?>
                <div class="seosa_product_label _catalog <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['position'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'image' && $_smarty_tpl->tpl_vars['product_label']->value['image_css']) {?> style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php } elseif ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'text' && $_smarty_tpl->tpl_vars['product_label']->value['text']) {?> style="width: auto; height: auto;"<?php }?>>
                    <?php if ($_smarty_tpl->tpl_vars['product_label']->value['url']) {?>
                    <a target="_blank" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['url'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                        <?php }?>
                        <img src="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_url'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['product_label']->value['image_css']) {
}?> alt="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['name'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
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
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'text') {?>
                <?php if ($_smarty_tpl->tpl_vars['product_label']->value['text']) {?>
                    <div class="seosa_product_label _catalog <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['position'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'image' && $_smarty_tpl->tpl_vars['product_label']->value['image_css']) {?> style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['image_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"<?php } elseif ($_smarty_tpl->tpl_vars['product_label']->value['label_type'] == 'text' && $_smarty_tpl->tpl_vars['product_label']->value['text']) {?> style="width: auto; height: auto;"<?php }?>>
                        <?php if ($_smarty_tpl->tpl_vars['product_label']->value['url']) {?>
                        <a target="_blank" href="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['url'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
                            <?php }?>
                            <span style="<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['text_css'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['text'],'quotes','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
</span>
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
                        <?php }?>
                    </div>
                <?php }?>
            <?php }?>
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
        }
        .seosa_label_hint_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product_label']), ENT_QUOTES, 'UTF-8');?>
_<?php echo htmlspecialchars(intval($_smarty_tpl->tpl_vars['product_label']->value['id_product']), ENT_QUOTES, 'UTF-8');?>
:after {
            border-<?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: solid <?php if (isset($_smarty_tpl->tpl_vars['product_label']->value['hint_background']) && $_smarty_tpl->tpl_vars['product_label']->value['hint_background']) {
echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['hint_background'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');
} else { ?>#000000<?php }?> 10px;
        <?php echo htmlspecialchars(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'],'html','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
: 100%;

            <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>border-right<?php } else { ?>border-left<?php }?>: 0;
            <?php if ($_smarty_tpl->tpl_vars['product_label']->value['fix_hint_position'] == 'left') {?>right<?php } else { ?>left<?php }?>: auto;
        <?php }?>
        }
    </style>
    <?php }?>
      <?php }
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php }
}
