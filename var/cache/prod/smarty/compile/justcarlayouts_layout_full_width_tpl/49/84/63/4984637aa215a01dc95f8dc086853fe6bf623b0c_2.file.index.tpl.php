<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 15:01:53
  from '/var/www/html/prestashop/themes/justcar/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60788da1c9cdf7_95310682',
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
function content_60788da1c9cdf7_95310682 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_113693166160788da1c98899_10851550', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_90779627360788da1c992d5_95404777 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_173079015560788da1c9aa00_44830734 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_62742616460788da1c9a111_70504989 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_173079015560788da1c9aa00_44830734', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_113693166160788da1c98899_10851550 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_113693166160788da1c98899_10851550',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_90779627360788da1c992d5_95404777',
  ),
  'page_content' => 
  array (
    0 => 'Block_62742616460788da1c9a111_70504989',
  ),
  'hook_home' => 
  array (
    0 => 'Block_173079015560788da1c9aa00_44830734',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_90779627360788da1c992d5_95404777', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_62742616460788da1c9a111_70504989', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
