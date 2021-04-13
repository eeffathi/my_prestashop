{*
* NOTICE OF LICENSE
*
* This source file is subject to a commercial license from BSofts.
* Use, copy, modification or distribution of this source file without written
* license agreement from the BSofts is strictly forbidden.
*
* @author    BSofts Inc.
* @copyright Copyright 2020 © BSofts Inc.
* @license   Single domain commerical license
*}
<div class="panel">
    <div class="form-group col-lg-4 pull-right">
        <div class="col-lg-3 item">
            <a href="https://addons.prestashop.com/en/contact-us" target="_blank"  title="{l s='Support' mod='google_address'}">
                <img id="support" src="{$smarty.const.__PS_BASE_URI__}modules/google_address/views/img/support.png" title="{l s='Support' mod='google_address'}" alt="{l s='Support' mod='google_address'}">
                <label class="caption" for="support">{l s='Support' mod='google_address'}</label>
            </a>
        </div>
        <div class="col-lg-3 item">
            <a href="https://addons.prestashop.com/en/ratings.php" target="_blank" title="{l s='Rate Us' mod='google_address'}">
                <img id="rate" src="{$smarty.const.__PS_BASE_URI__}modules/google_address/views/img/fav.png" title="{l s='Rate Us' mod='google_address'}" alt="{l s='Rate Us' mod='google_address'}">
                <label class="caption" for="rate">{l s='Rate Us' mod='google_address'}</label>
            </a>
        </div>
        <div class="col-lg-3 item">
            <a href="{$doc_path|escape:'htmlall':'UTF-8'}" target="_blank"  title="{l s='User Guide' mod='google_address'}">
                <img id="guide" src="{$smarty.const.__PS_BASE_URI__}modules/google_address/views/img/guide.png" title="{l s='User Manual' mod='google_address'}" alt="{l s='Support' mod='google_address'}">
                <label class="caption" for="guide">{l s='User Manual' mod='google_address'}</label>
            </a>
        </div>
        <div class="col-lg-3 item">
            <a href="https://addons.prestashop.com/en/2_community-developer?contributor=521928" target="_blank" title="{l s='Find Our Other Modules' mod='google_address'}">
                <img id="more-products" src="{$smarty.const.__PS_BASE_URI__}modules/google_address/views/img/products.png" title="{l s='Find Our Other Modules' mod='google_address'}" alt="{l s='Find Our Other Modules' mod='google_address'}">
                <label class="caption" for="more-products">{l s='Other Modules' mod='google_address'}</label>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
{literal}
<style type="text/css">
    div.item {
    /* To correctly align image, regardless of content height: */
    vertical-align: top;
    display: inline-block;
    /* To horizontally center images and caption */
    text-align: center;
    /* The width of the container also implies margin around the images. */
    width: 120px;
    }
    div.item img {
        width: 80px;
    }
    .caption {
        /* Make the caption a block so it occupies its own line. */
        display: block;
        cursor: pointer;
    }
</style>
{/literal}
