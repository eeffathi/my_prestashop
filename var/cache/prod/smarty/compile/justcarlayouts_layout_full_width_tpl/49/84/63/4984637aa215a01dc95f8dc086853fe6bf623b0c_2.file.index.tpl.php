<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 14:20:18
  from '/var/www/html/prestashop/themes/justcar/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_607883e2188672_63963927',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4984637aa215a01dc95f8dc086853fe6bf623b0c' => 
    array (
      0 => '/var/www/html/prestashop/themes/justcar/templates/index.tpl',
      1 => 1618128907,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_607883e2188672_63963927 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_922716500607883e2183eb0_75740230', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_1412627273607883e21848f8_85991723 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_1277255309607883e2186163_55890143 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_31424428607883e21857f6_27167133 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1277255309607883e2186163_55890143', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_922716500607883e2183eb0_75740230 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_922716500607883e2183eb0_75740230',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1412627273607883e21848f8_85991723',
  ),
  'page_content' => 
  array (
    0 => 'Block_31424428607883e21857f6_27167133',
  ),
  'hook_home' => 
  array (
    0 => 'Block_1277255309607883e2186163_55890143',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1412627273607883e21848f8_85991723', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_31424428607883e21857f6_27167133', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
