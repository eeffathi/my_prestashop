<?php
/* Smarty version 3.1.34-dev-7, created on 2021-04-15 15:01:53
  from '/var/www/html/prestashop/themes/justcar/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_60788da1ca7bf7_27238996',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '05b711f82feb21a87a553bc36ff02c56fca8f335' => 
    array (
      0 => '/var/www/html/prestashop/themes/justcar/templates/page.tpl',
      1 => 1618128907,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60788da1ca7bf7_27238996 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_152083355460788da1ca05a7_20091409', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_152350402160788da1ca1db4_88805673 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header class="page-header">
          <h1><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h1>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_85411830260788da1ca1051_95285512 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_152350402160788da1ca1db4_88805673', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_144863544060788da1ca4169_66696366 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_167115957560788da1ca4d52_58980371 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_37046522660788da1ca38f8_28633152 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_144863544060788da1ca4169_66696366', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_167115957560788da1ca4d52_58980371', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_163179094860788da1ca67b7_86085638 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_129963147560788da1ca5f75_99836221 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_163179094860788da1ca67b7_86085638', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_152083355460788da1ca05a7_20091409 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_152083355460788da1ca05a7_20091409',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_85411830260788da1ca1051_95285512',
  ),
  'page_title' => 
  array (
    0 => 'Block_152350402160788da1ca1db4_88805673',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_37046522660788da1ca38f8_28633152',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_144863544060788da1ca4169_66696366',
  ),
  'page_content' => 
  array (
    0 => 'Block_167115957560788da1ca4d52_58980371',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_129963147560788da1ca5f75_99836221',
  ),
  'page_footer' => 
  array (
    0 => 'Block_163179094860788da1ca67b7_86085638',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_85411830260788da1ca1051_95285512', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_37046522660788da1ca38f8_28633152', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_129963147560788da1ca5f75_99836221', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
