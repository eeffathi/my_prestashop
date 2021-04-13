<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-12 21:51:53
  from '/var/www/html/prestashop/modules/ordersexport/views/templates/admin/_configure/helpers/form/form.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6074f93925cc30_06174208',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0ec4bd49978ca087514eeaa6e20ad15e191735a0' => 
    array (
      0 => '/var/www/html/prestashop/modules/ordersexport/views/templates/admin/_configure/helpers/form/form.tpl',
      1 => 1617643886,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6074f93925cc30_06174208 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20725224616074f93921bbf5_97607585', "legend");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15946746616074f939227725_02218468', "input_row");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "helpers/form/form.tpl");
}
/* {block "legend"} */
class Block_20725224616074f93921bbf5_97607585 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'legend' => 
  array (
    0 => 'Block_20725224616074f93921bbf5_97607585',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <div class="panel-heading">
    <?php if (isset($_smarty_tpl->tpl_vars['field']->value['image']) && isset($_smarty_tpl->tpl_vars['field']->value['title'])) {?><img src="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['image'],'htmlall','UTF-8' ));?>
" alt="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['title'],'html','UTF-8' ));?>
" /><?php }?>
    <?php if (isset($_smarty_tpl->tpl_vars['field']->value['icon'])) {?><i class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['icon'],'htmlall','UTF-8' ));?>
"></i><?php }?>
    <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['field']->value['title'],'htmlall','UTF-8' ));?>

  </div>
<?php
}
}
/* {/block "legend"} */
/* {block "input_row"} */
class Block_15946746616074f939227725_02218468 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'input_row' => 
  array (
    0 => 'Block_15946746616074f939227725_02218468',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php if ($_smarty_tpl->tpl_vars['input']->value['type'] == 'checkbox_table') {?>
  <?php $_smarty_tpl->_assignInScope('all_setings', $_smarty_tpl->tpl_vars['input']->value['values']);?>
  <?php $_smarty_tpl->_assignInScope('id', $_smarty_tpl->tpl_vars['all_setings']->value['id']);?>
  <?php $_smarty_tpl->_assignInScope('name', $_smarty_tpl->tpl_vars['all_setings']->value['name']);?>

  <?php if (isset($_smarty_tpl->tpl_vars['all_setings']->value) && count($_smarty_tpl->tpl_vars['all_setings']->value) > 0) {?>

    <div class="form-group <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['class_block'],'htmlall','UTF-8' ));?>
" <?php if (isset($_smarty_tpl->tpl_vars['input']->value['tab'])) {?>data-tab-id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['tab'],'htmlall','UTF-8' ));?>
"<?php }?> >
      <label class="control-label col-lg-3">
        <span class="<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['hint'],'htmlall','UTF-8' ))) {?>label-tooltip<?php } else { ?>control-label<?php }?>" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['hint'],'htmlall','UTF-8' ));?>
">
          <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['label'],'htmlall','UTF-8' ));?>

        </span>
      </label>
      <div class="col-lg-9">
        <div class="row">
          <div class="col-lg-6">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th class="fixed-width-xs">
                <span class="title_box">
                  <input type="checkbox" name="checkme"  id="checkme" onclick="$(this).parents('.form-group').find('.checkbox_table').prop('checked', this.checked)" />
                 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select all','mod'=>'ordersexport'),$_smarty_tpl ) );?>

                </span>
                </th>
                <?php if ($_smarty_tpl->tpl_vars['all_setings']->value['id'] && $_smarty_tpl->tpl_vars['name']->value !== 'payment') {?>
                  <th>
                    <span class="id-box">
                     <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'ID','mod'=>'ordersexport'),$_smarty_tpl ) );?>

                    </span>
                  </th>
                <?php }?>
                <th>
              <span class="title_box">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Name','mod'=>'ordersexport'),$_smarty_tpl ) );?>

                                <?php if (isset($_smarty_tpl->tpl_vars['input']->value['search']) && $_smarty_tpl->tpl_vars['input']->value['search']) {?>
                  <input type="text" class="search_checkbox_table" onkeyup="searchCheckboxtable($(this).parents('table').find('tbody'), $(this).val());return false;">
                <?php }?>
              </span>
                </th>
              </tr>
              </thead>
              <tbody>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_setings']->value['query'], 'setings', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['setings']->value) {
?>
                <tr>
                  <td>
                    <input type="checkbox" class="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['type'],'htmlall','UTF-8' ));?>
 <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['class_input'],'htmlall','UTF-8' ));?>
" name="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'htmlall','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value],'htmlall','UTF-8' ));?>
" id="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'htmlall','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value],'htmlall','UTF-8' ));?>
" value="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value],'htmlall','UTF-8' ));?>
" <?php if ($_smarty_tpl->tpl_vars['fields_value']->value[((string)$_smarty_tpl->tpl_vars['input']->value['name'])."_".((string)$_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value])]) {?>checked="checked"<?php }?>  />
                  </td>
                  <?php if ($_smarty_tpl->tpl_vars['all_setings']->value['id'] && $_smarty_tpl->tpl_vars['name']->value !== 'payment') {?>
                    <td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value],'htmlall','UTF-8' ));?>
</td>
                  <?php }?>
                  <td>
                    <label for="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['input']->value['name'],'htmlall','UTF-8' ));?>
_<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['id']->value],'htmlall','UTF-8' ));?>
"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['name']->value],'htmlall','UTF-8' ));
if (isset($_smarty_tpl->tpl_vars['all_setings']->value['name2']) && $_smarty_tpl->tpl_vars['all_setings']->value['name2']) {?> <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['setings']->value[$_smarty_tpl->tpl_vars['all_setings']->value['name2']],'htmlall','UTF-8' ));
}?></label>
                  </td>
                </tr>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
   <?php }?>
	<?php } else { ?>
		<?php 
$_smarty_tpl->inheritance->callParent($_smarty_tpl, $this, '{$smarty.block.parent}');
?>

	<?php }
}
}
/* {/block "input_row"} */
}
