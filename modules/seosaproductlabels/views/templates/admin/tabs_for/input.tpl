{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2021 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<!-- quantity_max -->
<div class="form-group">
  <div class="col-lg-12">
    <label class="control-label float-left margin-right" for="quantity">{l s='From' mod='seosaproductlabels'}:</label>
    <input type="text" name="quantity" id="quantity" value="{$fields_value}" class="fixed-width-sm float-left">
  </div>
</div>

<div class="form-group">
  <div class="col-lg-12">
    <label class="control-label float-left margin-right" for="quantity_max">{l s='To' mod='seosaproductlabels'}:</label>
    <input type="text" name="quantity_max" id="quantity_max" value="{$fields_value_do}" class="fixed-width-sm float-left">
  </div>
</div>