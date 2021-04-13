{**
 * Creative Elements - Elementor based PageBuilder
 *
 * @author    WebshopWorks
 * @Copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 *}

{if isset($ce_content)}
	{$ce_content.content|cefilter}
{else}
	{$ce_template.content|cefilter}
{/if}
