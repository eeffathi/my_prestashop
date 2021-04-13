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
<div style="position: absolute; width: 745px; height: 1px; left:1px; top:103px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 745px; height: 3px; left:1px; top:106px;">
<hr style="color: black; background-color: black; height: 3px;"></div>

<div style="position: absolute; width: 340px; height: 95px; vertical-align: middle; text-align: center; left:181px; top:5px;">
<span style=" font-size: 10pt;"><img style="width: 340px; height: 95px;" src="{$ps_img}logo.jpg" alt="" /></span></div>

<div style="position: absolute; width: 176px; background-color: white; left: 395px; top: 116px">
<div align="right"><span style=" font-size: 8pt;"><span id="order_number_barcode_block">Order number barcode:<br>{barcode from=$order_number}</span></span></div></div>

<div style="position: absolute; width: 176px; background-color: white; left: 559px; top: 116px">
<div align="right"><span style=" font-size: 8pt;"><span id="invoice_number_barcode_block">Invoice number barcode:<br>{barcode from=$invoice_number}</span></span></div></div>



<div style="position: absolute; width: 745px; height: 1px; left:1px; top:211px;">
<hr style="color: black; background-color: black; height: 1px;"></div>


<div style="position: absolute; width: 50%; background-color: white; left: 15px; top: 277px;">
<span style=" font-size: 14pt;">
 <b>BILLED TO:</b><br>
{if $invoice_customer_company_name}{$invoice_customer_company_name|capitalize}<br>{/if}
{if $invoice_customer_name}{$invoice_customer_name|capitalize}<br>{/if}
{if $invoice_customer_address}{$invoice_customer_address|capitalize}<br>{/if}
{if $invoice_customer_phone}Phone: {$invoice_customer_phone|capitalize}<br>{/if}
{if $invoice_customer_email}{$invoice_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 50%; background-color: white; left: 383px; top: 277px;">
<span style=" font-size: 14pt;">
 <b>DELIVERED TO:</b><br>
{if $delivery_customer_company_name}{$delivery_customer_company_name|capitalize}<br>{/if}
{if $delivery_customer_name}{$delivery_customer_name|capitalize}<br>{/if}
{if $delivery_customer_address}{$delivery_customer_address|capitalize}<br>{/if}
{if $delivery_customer_phone}Phone: {$delivery_customer_phone|capitalize}<br>{/if}
{if $delivery_customer_email}{$delivery_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 745px; height: 1px; left:1px; top:496px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 100%; background-color: white; left: 5px; top: 502px;">
<span style=" font-size: 8pt;"><span id="return_address_block">RETURN ADDRESS: {if $company_name}{$company_name|capitalize}{/if}{if $company_address}, {$company_address|capitalize}{/if}{if $company_employee_name}, {$company_employee_name|capitalize}{/if}{if $company_phone}, {$company_phone}{/if}{if $company_email}, {$company_email}{/if}</span></span></div>


<div style="position: absolute; width: 370px; background-color: white; left: 5px; top: 116px;">
<span style=" font-size: 8pt;">
<span id="order_date_block">Order date: <b>{$order_date}</b><br></span>
<span id="order_number_block">Order number: <b>{$order_number}</b><br></span>
<span id="total_weight_block">Total weight: <b>{$total_weight} Kg.</b><br></span>
<span id="delivery_date_block">Delivery date: <b>{$delivery_date}</b><br></span>
<span id="invoice_number_block">Invoice number: <b>{$invoice_number}</b><br></span>
<span id="items_block">Items: <b>{$items}</b><br></span>
<span id="carrier_block">Carrier: <b>{$carrier|capitalize}</b></span>
</span>
</div>