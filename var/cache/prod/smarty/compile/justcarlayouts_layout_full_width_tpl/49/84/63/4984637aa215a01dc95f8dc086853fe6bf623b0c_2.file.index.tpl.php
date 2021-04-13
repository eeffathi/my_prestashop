<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-11 04:29:54
  from '/var/www/html/prestashop/themes/justcar/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_6072b382da1785_77268317',
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
function content_6072b382da1785_77268317 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11552727456072b382d9db31_11615264', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_14738648916072b382d9e452_59573286 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_15635012416072b382d9f9f9_86494139 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_3931488066072b382d9f1e8_17546670 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15635012416072b382d9f9f9_86494139', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_11552727456072b382d9db31_11615264 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_11552727456072b382d9db31_11615264',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_14738648916072b382d9e452_59573286',
  ),
  'page_content' => 
  array (
    0 => 'Block_3931488066072b382d9f1e8_17546670',
  ),
  'hook_home' => 
  array (
    0 => 'Block_15635012416072b382d9f9f9_86494139',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14738648916072b382d9e452_59573286', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3931488066072b382d9f1e8_17546670', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
