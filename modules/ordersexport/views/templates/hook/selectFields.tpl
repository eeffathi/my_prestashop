<div class="content_fields">
    <div class="productTabs ">
        <div class="fields_list list-group">
            <a class="list-group-item active" data-tab="exportTabOrdersData">{l s='Orders Data' mod='ordersexport'}</a>
            <a class="list-group-item" data-tab="exportTabProducts">{l s='Products' mod='ordersexport'}</a>
            <a class="list-group-item" data-tab="exportTabCustomers">{l s='Customers' mod='ordersexport'}</a>
            <a class="list-group-item" data-tab="exportTabShippingAddress">{l s='Shipping Address' mod='ordersexport'}</a>
            <a class="list-group-item" data-tab="exportTabInvoiceAddress">{l s='Invoice Address' mod='ordersexport'}</a>
            <a class="list-group-item" data-tab="exportTabPayment">{l s='Payment' mod='ordersexport'}</a>
        </div>
    </div>
    <div class="block_all_fields">
        {foreach $all_fields AS $key => $block}
            <div class="field_list_base field_list_{$key|escape:'htmlall':'UTF-8'} {if $key == 'exportTabOrdersData'}active{/if}">
                <div class="field_list_header">
                    <input data-page="filter_fields" class="search_base_fields" placeholder="{l s='Search' mod='ordersexport'}">
                </div>
                <ul class="block_base_fields">
                    {foreach  $block as $value}
                        {if !$saved_field_ids || !in_array($value['id'], $saved_field_ids)}
                            <li data-tab="{$value['tab']|escape:'htmlall':'UTF-8'}"  data-name="{$value['name']|escape:'htmlall':'UTF-8'}" data-value="{$value['val']|escape:'htmlall':'UTF-8'}" {if isset($value['hint']) && $value['hint']}class="isset_hint"  data-hint="{$value['hint']|escape:'htmlall':'UTF-8'}"{/if}>
                                <span class="mpm-oe-selected-field-name">
                                    {if isset($value['hint']) && $value['hint']}
                                        <i class="icon-info icon-info-fields"></i>
                                    {/if}

                                    {$value['name']|escape:'htmlall':'UTF-8'}
                                </span>

                                <i class="icon-pencil mpm-oe-edit-field-name-btn"></i>

                                <div class="mpm-oe-edit-field-name-container form-inline">
                                    <div class="form-group mpm-oe-edit-field-name-container">
                                        <input type="text" class="form-control mpm-oe-edit-field-name" placeholder="Custom field name" value="{$value['name']|escape:'htmlall':'UTF-8'}" aria-label="...">
                                    </div>
                                    <span class="mpm-oe-save-field-name"><i class="icon-check"></i></span>
                                    <span class="mpm-oe-close-field-edit"><i class="icon-times"></i></span>
                                </div>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            </div>
        {/foreach}
    </div>
    <div class="navigation-fields navigation-fields-base">
        <div class="navigation-button">
            <button data-page="filter_fields" type="button" class="btn btn-default add_base_filds_all add_fild_right">{l s='Add all ' mod='ordersexport'}<i class="icon-arrow-right"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default add_base_filds add_fild_right">{l s='Add ' mod='ordersexport'}<i class="icon-arrow-right"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default remove_base_filds add_fild_right">{l s='Remove ' mod='ordersexport'}<i class="icon-arrow-left"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default remove_base_filds_all add_fild_right">{l s='Remove all ' mod='ordersexport'}<i class="icon-arrow-left"></i></button>
            <button data-page="filter_fields"  type="button" class="btn btn-default add-extra-field add_fild_right">{l s='Add Custom Field ' mod='ordersexport'}<i class="icon-plus"></i></button>
        </div>
    </div>
    <div class="block_selected_fields">
        <div class="field_list_selected">
            <div class="field_list_header">
                <input data-page="filter_fields" class="search_selected_fields" placeholder="{l s='Search' mod='ordersexport'}">
            </div>
            <ul class="selected_fields">
                {foreach from=$selected key=key item=select}
                    <li data-tab="{$select['tab']|escape:'htmlall':'UTF-8'}"  {if $select['is_extra']}class="mpm-oe-extra-field" data-default-value="{$select['default_value']|escape:'htmlall':'UTF-8'}"{/if} data-name="{$select['name']|escape:'htmlall':'UTF-8'}" data-value="{$key|escape:'htmlall':'UTF-8'}" class="{if isset($select['hint']) && $select['hint']} isset_hint {/if} {if isset($select['disabled']) && $select['disabled']} disable_fields {/if}"  {if isset($select['hint']) && $select['hint']}data-hint="{$select['hint']|escape:'htmlall':'UTF-8'}"{/if}>
                        <span class="mpm-oe-selected-field-name">
                            {if isset($select['hint']) && $select['hint']}
                                <i class="icon-info icon-info-fields"></i>
                            {/if}
                            {$select['name']|escape:'htmlall':'UTF-8'}
                        </span>

                        <i class="icon-pencil mpm-oe-edit-field-name-btn"></i>

                        <div class="mpm-oe-edit-field-name-container {if $select['is_extra']}mpm-oe-edit-field-value-container{/if} form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control mpm-oe-edit-field-name" placeholder="Custom field name" value="{$select['name']|escape:'htmlall':'UTF-8'}" aria-label="...">
                            </div>
                            {if $select['is_extra']}
                                <div class="form-group">
                                    <input type='text' class='mpm-oe-edit-field-default-val' placeholder='Default field value' value="{$select['default_value']|escape:'htmlall':'UTF-8'}">
                                </div>
                            {/if}
                            <span class="mpm-oe-save-field-name"><i class="icon-check"></i></span>
                            <span class="mpm-oe-close-field-edit"><i class="icon-times"></i></span>
                        </div>

                        <i class="icon-arrows icon-arrows-select-fields"></i>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>

