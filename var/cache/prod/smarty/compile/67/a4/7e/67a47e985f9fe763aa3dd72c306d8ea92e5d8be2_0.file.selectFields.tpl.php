<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-12 21:51:53
  from '/var/www/html/prestashop/modules/ordersexport/views/templates/hook/selectFields.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6074f9391dfc52_73670447',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '67a47e985f9fe763aa3dd72c306d8ea92e5d8be2' => 
    array (
      0 => '/var/www/html/prestashop/modules/ordersexport/views/templates/hook/selectFields.tpl',
      1 => 1617643886,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6074f9391dfc52_73670447 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="content_fields">
    <div class="productTabs ">
        <div class="fields_list list-group">
            <a class="list-group-item active" data-tab="exportTabOrdersData"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Orders Data','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
            <a class="list-group-item" data-tab="exportTabProducts"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Products','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
            <a class="list-group-item" data-tab="exportTabCustomers"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Customers','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
            <a class="list-group-item" data-tab="exportTabShippingAddress"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Shipping Address','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
            <a class="list-group-item" data-tab="exportTabInvoiceAddress"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Invoice Address','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
            <a class="list-group-item" data-tab="exportTabPayment"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Payment','mod'=>'ordersexport'),$_smarty_tpl ) );?>
</a>
        </div>
    </div>
    <div class="block_all_fields">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_fields']->value, 'block', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['block']->value) {
?>
            <div class="field_list_base field_list_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'htmlall','UTF-8' ));?>
 <?php if ($_smarty_tpl->tpl_vars['key']->value == 'exportTabOrdersData') {?>active<?php }?>">
                <div class="field_list_header">
                    <input data-page="filter_fields" class="search_base_fields" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search','mod'=>'ordersexport'),$_smarty_tpl ) );?>
">
                </div>
                <ul class="block_base_fields">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['block']->value, 'value');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['value']->value) {
?>
                        <?php if (!$_smarty_tpl->tpl_vars['saved_field_ids']->value || !in_array($_smarty_tpl->tpl_vars['value']->value['id'],$_smarty_tpl->tpl_vars['saved_field_ids']->value)) {?>
                            <li data-tab="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['tab'],'htmlall','UTF-8' ));?>
"  data-name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['name'],'htmlall','UTF-8' ));?>
" data-value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['val'],'htmlall','UTF-8' ));?>
" <?php if (isset($_smarty_tpl->tpl_vars['value']->value['hint']) && $_smarty_tpl->tpl_vars['value']->value['hint']) {?>class="isset_hint"  data-hint="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['hint'],'htmlall','UTF-8' ));?>
"<?php }?>>
                                <span class="mpm-oe-selected-field-name">
                                    <?php if (isset($_smarty_tpl->tpl_vars['value']->value['hint']) && $_smarty_tpl->tpl_vars['value']->value['hint']) {?>
                                        <i class="icon-info icon-info-fields"></i>
                                    <?php }?>

                                    <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['name'],'htmlall','UTF-8' ));?>

                                </span>

                                <i class="icon-pencil mpm-oe-edit-field-name-btn"></i>

                                <div class="mpm-oe-edit-field-name-container form-inline">
                                    <div class="form-group mpm-oe-edit-field-name-container">
                                        <input type="text" class="form-control mpm-oe-edit-field-name" placeholder="Custom field name" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['value']->value['name'],'htmlall','UTF-8' ));?>
" aria-label="...">
                                    </div>
                                    <span class="mpm-oe-save-field-name"><i class="icon-check"></i></span>
                                    <span class="mpm-oe-close-field-edit"><i class="icon-times"></i></span>
                                </div>
                            </li>
                        <?php }?>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </div>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
    <div class="navigation-fields navigation-fields-base">
        <div class="navigation-button">
            <button data-page="filter_fields" type="button" class="btn btn-default add_base_filds_all add_fild_right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add all ','mod'=>'ordersexport'),$_smarty_tpl ) );?>
<i class="icon-arrow-right"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default add_base_filds add_fild_right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add ','mod'=>'ordersexport'),$_smarty_tpl ) );?>
<i class="icon-arrow-right"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default remove_base_filds add_fild_right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Remove ','mod'=>'ordersexport'),$_smarty_tpl ) );?>
<i class="icon-arrow-left"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default remove_base_filds_all add_fild_right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Remove all ','mod'=>'ordersexport'),$_smarty_tpl ) );?>
<i class="icon-arrow-left"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default add-extra-field add_fild_right"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add Custom Field ','mod'=>'ordersexport'),$_smarty_tpl ) );?>
<i class="icon-plus"></i></button>
        </div>
    </div>
    <div class="block_selected_fields">
        <div class="field_list_selected">
            <div class="field_list_header">
                <input data-page="filter_fields" class="search_selected_fields" placeholder="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Search','mod'=>'ordersexport'),$_smarty_tpl ) );?>
">
            </div>
            <ul class="selected_fields">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['selected']->value, 'select', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['select']->value) {
?>
                    <li data-tab="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['tab'],'htmlall','UTF-8' ));?>
"  <?php if ($_smarty_tpl->tpl_vars['select']->value['is_extra']) {?>class="mpm-oe-extra-field" data-default-value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['default_value'],'htmlall','UTF-8' ));?>
"<?php }?> data-name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['name'],'htmlall','UTF-8' ));?>
" data-value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['key']->value,'htmlall','UTF-8' ));?>
" class="<?php if (isset($_smarty_tpl->tpl_vars['select']->value['hint']) && $_smarty_tpl->tpl_vars['select']->value['hint']) {?> isset_hint <?php }?> <?php if (isset($_smarty_tpl->tpl_vars['select']->value['disabled']) && $_smarty_tpl->tpl_vars['select']->value['disabled']) {?> disable_fields <?php }?>"  <?php if (isset($_smarty_tpl->tpl_vars['select']->value['hint']) && $_smarty_tpl->tpl_vars['select']->value['hint']) {?>data-hint="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['hint'],'htmlall','UTF-8' ));?>
"<?php }?>>
                        <span class="mpm-oe-selected-field-name">
                            <?php if (isset($_smarty_tpl->tpl_vars['select']->value['hint']) && $_smarty_tpl->tpl_vars['select']->value['hint']) {?>
                                <i class="icon-info icon-info-fields"></i>
                            <?php }?>
                            <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['name'],'htmlall','UTF-8' ));?>

                        </span>

                        <i class="icon-pencil mpm-oe-edit-field-name-btn"></i>

                        <div class="mpm-oe-edit-field-name-container <?php if ($_smarty_tpl->tpl_vars['select']->value['is_extra']) {?>mpm-oe-edit-field-value-container<?php }?> form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control mpm-oe-edit-field-name" placeholder="Custom field name" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['name'],'htmlall','UTF-8' ));?>
" aria-label="...">
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['select']->value['is_extra']) {?>
                                <div class="form-group">
                                    <input type='text' class='mpm-oe-edit-field-default-val' placeholder='Default field value' value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['select']->value['default_value'],'htmlall','UTF-8' ));?>
">
                                </div>
                            <?php }?>
                            <span class="mpm-oe-save-field-name"><i class="icon-check"></i></span>
                            <span class="mpm-oe-close-field-edit"><i class="icon-times"></i></span>
                        </div>

                        <i class="icon-arrows icon-arrows-select-fields"></i>
                    </li>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </ul>
        </div>
    </div>
</div>

<?php }
}
