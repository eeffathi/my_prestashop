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

<div style="position: absolute; width: 176px; background-color: white; left: 190px; top: 56px">
<div align="right"><span style=" font-size: 6pt;"><span id="order_number_barcode_block">{barcode from=$order_number}</span></span></div></div>

<div style="position: absolute; width: 373px; height: 1px; left:1px; top:131px;">
<hr style="color: black; background-color: black; height: 1px;"></div>

<div style="position: absolute; width: 50%; background-color: white; left: 5px; top: 138px;">
<span style=" font-size: 6pt;">
 <b>FACTURE A:</b><br>
{if $invoice_customer_company_name}{$invoice_customer_company_name|capitalize}<br>{/if}
{if $invoice_customer_name}{$invoice_customer_name|capitalize}<br>{/if}
{if $invoice_customer_address}{$invoice_customer_address|capitalize}<br>{/if}
{if $invoice_customer_phone}Telephone: {$invoice_customer_phone|capitalize}<br>{/if}
{if $invoice_customer_email}{$invoice_customer_email}{/if}
</span>
</div>

<div style="position: absolute; width: 50%; background-color: white; left: 190px; top: 138px;">
<span style=" font-size: 6pt;">
 <b>RENDU DE:</b><br>
{if $delivery_customer_company_name}{$delivery_customer_company_name|capitalize}<br>{/if}
{if $delivery_customer_name}{$delivery_customer_name|capitalize}<br>{/if}
{if $delivery_customer_address}{$delivery_customer_address|capitalize}<br>{/if}
{if $delivery_customer_phone}Telephone: {$delivery_customer_phone|capitalize}<br>{/if}
{if $delivery_customer_email}{$delivery_customer_email}{/if}
</span>
</div>


<div style="position: absolute; width: 373px; background-color: white; left: 5px; top: 61px;">
<span style=" font-size: 6pt;">
<span id="order_date_block">Date de la commande: <b>{$order_date}</b><br></span>
<span id="order_number_block">Numero de commande: <b>{$order_number}</b><br></span>
<span id="total_weight_block">Poids total: <b>{$total_weight} Kg.</b><br></span>
<span id="delivery_date_block">Date de livraison: <b>{$delivery_date}</b><br></span>
<span id="invoice_number_block">Numero de facture: <b>{$invoice_number}</b><br></span>
<span id="items_block">Articles: <b>{$items}</b><br></span>
<span id="carrier_block">Transporteur: <b>{$carrier|capitalize}</b></span>
</span>
</div>