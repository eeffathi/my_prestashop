<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Uplift
 * @copyright Uplift
 * @license   GPLv3
 */

if (! defined('_PS_VERSION_')) {
    exit();
}

use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class BiggerCartsSearchProvider implements ProductSearchProviderInterface
{
    public function __construct()
    {
    }

    /**
     *
     * @param ProductSearchContext $context
     * @param ProductSearchQuery $query
     *
     * @return ProductSearchResult
     */
    public function runQuery(ProductSearchContext $context, ProductSearchQuery $query)
    {
        $result = new ProductSearchResult();
        $result->setProducts(array(
            array(
                'id_product' => 1
            )
        ))->setTotalProductsCount(1);
        return $result;
    }
}
