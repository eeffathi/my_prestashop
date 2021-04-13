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

<div style="position: absolute; width: 120px; height: 30px; vertical-align: middle; text-align: center; left:200px; top:5px;">
<span style=" font-size: 10pt;"><img style="width: 120px; height: 30px;" src="{$ps_img}logo.jpg" alt="" /></span></div>

<div style="position: absolute; width: 50%; background-color: white; left: 5px; top: 5px;">
<span style=" font-size: 8pt;">
 <b>ENTREGADO A:</b><br>
{if $delivery_customer_company_name}{$delivery_customer_company_name|capitalize}<br>{/if}
{if $delivery_customer_name}{$delivery_customer_name|capitalize}<br>{/if}
{if $delivery_customer_address}{$delivery_customer_address|capitalize}<br>{/if}
{if $delivery_customer_phone}Phone: {$delivery_customer_phone|capitalize}<br>{/if}
{if $delivery_customer_email}{$delivery_customer_email}{/if}
</span>
</div>


