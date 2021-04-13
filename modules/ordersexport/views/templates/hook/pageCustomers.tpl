<tbody>
{foreach $customers as $key => $customer}
  <tr>
    <td>
      <input type="checkbox" class="checkbox_table customers_checkbox table_in" name="customers[]_{$customer['id']|escape:'htmlall':'UTF-8'}" id="customers[]_{$customer['id']|escape:'htmlall':'UTF-8'}" {if in_array($customer['id'], $customers_check)}checked="checked" {/if} value="{$customer['id']|escape:'htmlall':'UTF-8'}"  />
    </td>
    {if $customer['id']}
      <td>{$customer['id']|escape:'htmlall':'UTF-8'}</td>
    {/if}
    <td>
      <label for="customers[]_{$customer['id']|escape:'htmlall':'UTF-8'}">{$customer['name']|escape:'htmlall':'UTF-8'}</label>
    </td>
  </tr>
{/foreach}
</tbody>