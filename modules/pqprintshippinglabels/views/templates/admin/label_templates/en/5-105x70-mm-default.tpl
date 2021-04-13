<!--
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
-->
<!-- HTML CONTENT -->
<div style="position: absolute; width: 373px; height: 1px; left:1px; top:49px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 373px; height: 3px; left:1px; top:52px;">
<hr style="color: black; background-color: black; height: 3px;"></div>

<div style="position: absolute; width: 180px; height: 40px; vertical-align: middle; text-align: center; left:100px; top:5px;">
<span style=" font-size: 10pt;"><img style="width: 180px; height: 40px;" src="{$ps_img}logo.jpg" alt="" /></span></div>

<div style="position: absolute; width: 176px; height: 30px; left: 195px; top: 56px;">
<div align="right"><span style=" font-size: 6pt;"><span id="order_number_barcode_block">{barcode from=$order_number}</span></span></div></div>



<div style="position: absolute; width: 373px; height: 1px; left:1px; top:131px;">
<hr style="color: black; background-color: black; height: 1px;"></div>


<div style="position: absolute; width: 50%; background-color: white; left: 5px; top: 138px;">
<span style=" font-size: 8pt;">
 <b>BILLED TO:</b><br>
{if $invoice_customer_company_name}{$invoice_customer_company_name|capitalize}<br>{/if}
{if $invoice_customer_name}{$invoice_customer_name|capitalize}<br>{/if}
{if $invoice_customer_address}{$invoice_customer_address|capitalize}<br>{/if}
{if $invoice_customer_phone}Phone: {$invoice_customer_phone|capitalize}<br>{/if}
{if $invoice_customer_email}{$invoice_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 50%; background-color: white; left: 190px; top: 138px;">
<span style=" font-size: 8pt;">
 <b>DELIVERED TO:</b><br>
{if $delivery_customer_company_name}{$delivery_customer_company_name|capitalize}<br>{/if}
{if $delivery_customer_name}{$delivery_customer_name|capitalize}<br>{/if}
{if $delivery_customer_address}{$delivery_customer_address|capitalize}<br>{/if}
{if $delivery_customer_phone}Phone: {$delivery_customer_phone|capitalize}<br>{/if}
{if $delivery_customer_email}{$delivery_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 373px; height: 1px; left:1px; top:219px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 100%; background-color: white; left: 5px; top: 223px;">
<span style=" font-size: 6pt;"><span id="return_address_block">RETURN ADDRESS: {if $company_name}{$company_name|capitalize}{/if}{if $company_address}, {$company_address|capitalize}{/if}{if $company_employee_name}, {$company_employee_name|capitalize}{/if}{if $company_phone}, {$company_phone}{/if}{if $company_email}, {$company_email}{/if}</span></span></div>


<div style="position: absolute; width: 373px; background-color: white; left: 5px; top: 61px;">
<span style=" font-size: 6pt;">
<span id="order_date_block">Order date: <b>{$order_date}</b><br></span>
<span id="order_number_block">Order number: <b>{$order_number}</b><br></span>
<span id="total_weight_block">Total weight: <b>{$total_weight} Kg.</b><br></span>
<span id="delivery_date_block">Delivery date: <b>{$delivery_date}</b><br></span>
<span id="invoice_number_block">Invoice number: <b>{$invoice_number}</b><br></span>
<span id="items_block">Items: <b>{$items}</b><br></span>
<span id="carrier_block">Carrier: <b>{$carrier|capitalize}</b></span>
</span>
</div>