<div class="panel-heading-gomakoil heading_{$tab|escape:'htmlall':'UTF-8'}">
  <ul class="tabs_block">
    <li class="{if !isset($smarty.get.module_tab)}active{/if}">
      <a href="{$location_href|escape:'htmlall':'UTF-8'}">
        {l s='Welcome' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="step_1{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'step_1'} active{/if}">
      <a href="{$location_href|escape:'htmlall':'UTF-8'}&module_tab=step_1">
        {l s='Step 1' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="step_2{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'step_2'} active{/if}">
      <a href="#">
        {l s='Step 2' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'schedule'} active{/if}">
      <a href="{$location_href|escape:'htmlall':'UTF-8'}&module_tab=schedule">
        {l s='Schedule Tasks' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'support'} active{/if}">
      <a target="_blank" href="https://addons.prestashop.com/en/contact-us?id_product=19091">
        {l s='Support' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'modules'} active{/if}">
      <a href="{$location_href|escape:'htmlall':'UTF-8'}&module_tab=modules">
        {l s='Related Modules' mod='simpleimportproduct'}
      </a>
    </li>
    <li class="{if isset($smarty.get.module_tab) && $smarty.get.module_tab == 'documentation'} active{/if}">
      <a href="http://faq.myprestamodules.com/product-catalog-csv-excel-import.html" target="_blank">
        {l s='Documentation' mod='simpleimportproduct'}
      </a>
    </li>
    <li>
      <a href="http://demo16.myprestamodules.com/example_import.xlsx">
        {l s='Example of import file (XLSX)' mod='simpleimportproduct'}
      </a>
    </li>
  </ul>
</div>