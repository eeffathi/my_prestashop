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
<div align="right"><span style=" font-size: 8pt;"><span id="order_number_barcode_block">Numero de orden:<br>{barcode from=$order_number}</span></span></div></div>

<div style="position: absolute; width: 176px; background-color: white; left: 559px; top: 116px">
<div align="right"><span style=" font-size: 8pt;"><span id="invoice_number_barcode_block">Numero de factura:<br>{barcode from=$invoice_number}</span></span></div></div>


<div style="position: absolute; width: 745px; height: 1px; left:1px; top:211px;">
<hr style="color: black; background-color: black; height: 1px;"></div>


<div style="position: absolute; width: 50%; background-color: white; left: 15px; top: 277px;">
<span style=" font-size: 14pt;">
 <b>FACTURADOS A:</b><br>
{if $invoice_customer_company_name}{$invoice_customer_company_name|capitalize}<br>{/if}
{if $invoice_customer_name}{$invoice_customer_name|capitalize}<br>{/if}
{if $invoice_customer_address}{$invoice_customer_address|capitalize}<br>{/if}
{if $invoice_customer_phone}Telefono: {$invoice_customer_phone|capitalize}<br>{/if}
{if $invoice_customer_email}{$invoice_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 50%; background-color: white; left: 383px; top: 277px;">
<span style=" font-size: 14pt;">
 <b>ENTREGADO A:</b><br>
{if $delivery_customer_company_name}{$delivery_customer_company_name|capitalize}<br>{/if}
{if $delivery_customer_name}{$delivery_customer_name|capitalize}<br>{/if}
{if $delivery_customer_address}{$delivery_customer_address|capitalize}<br>{/if}
{if $delivery_customer_phone}Telefono: {$delivery_customer_phone|capitalize}<br>{/if}
{if $delivery_customer_email}{$delivery_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 745px; height: 1px; left:1px; top:986px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 100%; background-color: white; left: 5px; top: 993px;">
<span style=" font-size: 8pt;"><span id="return_address_block">REMITE: {if $company_name}{$company_name|capitalize}{/if}{if $company_address}, {$company_address|capitalize}{/if}{if $company_employee_name}, {$company_employee_name|capitalize}{/if}{if $company_phone}, {$company_phone}{/if}{if $company_email}, {$company_email}{/if}</span></span></div>


<div style="position: absolute; width: 370px; background-color: white; left: 5px; top: 116px;">
<span style=" font-size: 8pt;">
<span id="order_date_block">Fecha del pedido: <b>{$order_date}</b><br></span>
<span id="order_number_block">Numero de orden: <b>{$order_number}</b><br></span>
<span id="total_weight_block">El peso total: <b>{$total_weight} Kg.</b><br></span>
<span id="delivery_date_block">Fecha de entrega: <b>{$delivery_date}</b><br></span>
<span id="invoice_number_block">Numero de factura: <b>{$invoice_number}</b><br></span>
<span id="items_block">Articulos: <b>{$items}</b><br></span>
<span id="carrier_block">Portador: <b>{$carrier|capitalize}</b></span>
</span>
</div>