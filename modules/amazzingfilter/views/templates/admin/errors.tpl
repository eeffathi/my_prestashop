{*
* 2007-2020 Amazzing
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
*
*  @author    Amazzing <mail@amazzing.ru>
*  @copyright 2007-2020 Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*
*}
{if !empty($errors)}
	<div class="alert alert-danger thrown-errors">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<ul>
		{foreach $errors as $msg}
			<li>{$msg}{* can not be escaped *}</li>
		{/foreach}
		</ul>
	</div>
{/if}
{* since 3.1.3 *}
