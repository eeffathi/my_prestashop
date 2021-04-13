<?php

if (!defined('_PS_VERSION_')){
  exit;
}

class Ordersexport extends Module {
  private $_model;
  private $_html;
  private $_exportTabOrdersData;
  private $_exportTabProducts;
  private $_exportTabCustomers;
  private $_exportTabShippingAddress;
  private $_exportTabInvoiceAddress;
  private $_exportTabPayment;

  public $default_shop_id;
  public $default_shop_group_id;

  public function __construct(){

    include_once(_PS_MODULE_DIR_ . 'ordersexport/datamodel.php');
    $this->_model = new ordersExportDataModel();

    $this->name = 'ordersexport';
    $this->tab = 'export';
    $this->version = '4.7.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;
    $this->module_key = "f2843b00bda29560ff516537cf5e2df0";

    parent::__construct();

    $this->displayName = $this->l('Orders export');
    $this->description = $this->l('The Orders Export Module allows you to do a CSV or EXCEL export of your data from your orders function.');

    $this->default_shop_id = (int)Configuration::get('PS_SHOP_DEFAULT');
    $this->default_shop_group_id = Shop::getGroupFromShop((int)Configuration::get('PS_SHOP_DEFAULT'));

    $this->_exportTabOrdersData = array(
      array(
        'id' => 'id_order',
        'val' => 'id_order',
        'name' => $this->l('Order ID'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'reference',
        'val' => 'reference',
        'name' => $this->l('Order reference code'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'current_state',
        'val' => 'current_state',
        'name' => $this->l('Order status ID'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'status_order',
        'val' => 'status_order',
        'name' => $this->l('Order status name'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'date_add_state',
        'val' => 'date_add_state',
        'name' => $this->l('Date update order status'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'id_cart',
        'val' => 'id_cart',
        'name' => $this->l('Cart ID'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'weight',
        'val' => 'weight',
        'name' => $this->l('Shipping weight'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'secure_key',
        'val' => 'secure_key',
        'name' => $this->l('Secure key'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'id_currency',
        'val' => 'id_currency',
        'name' => $this->l('Currency ID'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'iso_currency',
        'val' => 'iso_currency',
        'name' => $this->l('Currency iso code'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'order_customer_message',
        'val' => 'order_customer_message',
        'name' => $this->l('Order comment (message)'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'id_carrier',
        'val' => 'id_carrier',
        'name' => $this->l('ID Carrier'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'carrier_name',
        'val' => 'carrier_name',
        'name' => $this->l('Carrier name'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'conversion_rate',
        'val' => 'conversion_rate',
        'name' => $this->l('Conversion rate'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'module',
        'val' => 'module',
        'name' => $this->l('Module'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'recyclable',
        'val' => 'recyclable',
        'name' => $this->l('Recyclable'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'gift',
        'val' => 'gift',
        'name' => $this->l('Gift'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'gift_message',
        'val' => 'gift_message',
        'name' => $this->l('Gift message'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'mobile_theme',
        'val' => 'mobile_theme',
        'name' => $this->l('Mobile theme'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'tracking_number',
        'val' => 'tracking_number',
        'name' => $this->l('Tracking number'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_products',
        'val' => 'total_products',
        'name' => $this->l('Total products (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_products_wt',
        'val' => 'total_products_wt',
        'name' => $this->l('Total products with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_products_tax',
        'val' => 'total_products_tax',
        'name' => $this->l('Total products Tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_discounts_tax_incl',
        'val' => 'total_discounts_tax_incl',
        'name' => $this->l('Total discounts with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_discounts_tax_excl',
        'val' => 'total_discounts_tax_excl',
        'name' => $this->l('Total discounts (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_paid_tax_incl',
        'val' => 'total_paid_tax_incl',
        'name' => $this->l('Total paid with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_paid_tax_excl',
        'val' => 'total_paid_tax_excl',
        'name' => $this->l('Total paid (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_paid_tax',
        'val' => 'total_paid_tax',
        'name' => $this->l('Total paid Tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_paid_real',
        'val' => 'total_paid_real',
        'name' => $this->l('Total paid real'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_shipping_tax_incl',
        'val' => 'total_shipping_tax_incl',
        'name' => $this->l('Total shipping with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_shipping_tax_excl',
        'val' => 'total_shipping_tax_excl',
        'name' => $this->l('Total shipping (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),

      array(
        'id' => 'voucher_name',
        'val' => 'voucher_name',
        'name' => $this->l('Voucher name'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'voucher_code',
        'val' => 'voucher_code',
        'name' => $this->l('Voucher code'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'voucher_tax_incl',
        'val' => 'voucher_tax_incl',
        'name' => $this->l('Total voucher with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'voucher_tax_exc',
        'val' => 'voucher_tax_exc',
        'name' => $this->l('Total voucher (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),

      array(
        'id' => 'carrier_tax_rate',
        'val' => 'carrier_tax_rate',
        'name' => $this->l('Carrier tax rate'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_wrapping_tax_incl',
        'val' => 'total_wrapping_tax_incl',
        'name' => $this->l('Wrapping Cost with tax'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'total_wrapping_tax_excl',
        'val' => 'total_wrapping_tax_excl',
        'name' => $this->l('Wrapping Cost (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'valid',
        'val' => 'valid',
        'name' => $this->l('Valid'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'date_add',
        'val' => 'date_add',
        'name' => $this->l('Date add'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'date_upd',
        'val' => 'date_upd',
        'name' => $this->l('Date update'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'delivery_number',
        'val' => 'delivery_number',
        'name' => $this->l('Delivery number'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'delivery_date',
        'val' => 'delivery_date',
        'name' => $this->l('Delivery date'),
        'tab'   => 'exportTabOrdersData'
      ),
      
      array(
        'id' => 'credit_number',
        'val' => 'credit_number',
        'name' => $this->l('Credit slip number'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'shipping_cost_slip',
        'val' => 'shipping_cost_slip',
        'name' => $this->l('Credit slip shipping cost'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'amount_slip',
        'val' => 'amount_slip',
        'name' => $this->l('Credit slip amount'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'shipping_cost_amount_slip',
        'val' => 'shipping_cost_amount_slip',
        'name' => $this->l('Credit slip shipping cost amount'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'date_add_slip',
        'val' => 'date_add_slip',
        'name' => $this->l('Credit slip date add'),
        'tab'   => 'exportTabOrdersData'
      ),
      array(
          'id' => 'source_date',
          'val' => 'source_date',
          'name' => $this->l('Source date'),
          'tab'   => 'exportTabOrdersData'
      ),
      array(
          'id' => 'source_from',
          'val' => 'source_from',
          'name' => $this->l('Source from'),
          'tab'   => 'exportTabOrdersData'
      ),
      array(
          'id' => 'source_to',
          'val' => 'source_to',
          'name' => $this->l('Source to'),
          'tab'   => 'exportTabOrdersData'
      ),
      array(
        'id' => 'id_warehouse',
        'val' => 'id_warehouse',
        'name' => $this->l('Warehouse ID'),
        'tab'   => 'exportTabOrdersData'
      ),
    );

    /**
     *Add this fields only for PS of version 1.6.1.0 and newer
     *
     */
    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
      $this->_exportTabOrdersData[] = array(
        'id' => 'total_products_tax_excl_slip',
        'val' => 'total_products_tax_excl_slip',
        'name' => $this->l('Credit slip total products (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      );

      $this->_exportTabOrdersData[] = array(
        'id' => 'total_products_tax_incl_slip',
        'val' => 'total_products_tax_incl_slip',
        'name' => $this->l('Credit slip total products (tax incl.)'),
        'tab'   => 'exportTabOrdersData'
      );

      $this->_exportTabOrdersData[] = array(
        'id' => 'total_shipping_tax_excl_slip',
        'val' => 'total_shipping_tax_excl_slip',
        'name' => $this->l('Credit slip total shipping (tax excl.)'),
        'tab'   => 'exportTabOrdersData'
      );

      $this->_exportTabOrdersData[] = array(
        'id' => 'total_shipping_tax_incl_slip',
        'val' => 'total_shipping_tax_incl_slip',
        'name' => $this->l('Credit slip total shipping (tax incl.)'),
        'tab'   => 'exportTabOrdersData'
      );
    }


    $this->_exportTabProducts = array(
      array(
        'id' => 'product_id',
        'val' => 'product_id',
        'name' => $this->l('Product ID'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_attribute_id',
        'val' => 'product_attribute_id',
        'name' => $this->l('Product Attribute ID'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'combinations_value',
        'val' => 'combinations_value',
        'name' => $this->l('Attribute value (each value in separate field)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_name_clean',
        'val' => 'product_name_clean',
        'name' => $this->l('Product name'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_name',
        'val' => 'product_name',
        'name' => $this->l('Product name (include combination name)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'customization',
        'val' => 'customization',
        'name' => $this->l('Customization'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'customization_separate',
        'val' => 'customization_separate',
        'name' => $this->l('Customization in separate columns'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'cover_image',
        'val' => 'cover_image',
        'name' => $this->l('Product Cover Image'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'cover_url',
        'val' => 'cover_url',
        'name' => $this->l('Product Image url'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_reference',
        'val' => 'product_reference',
        'name' => $this->l('Product Reference'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_category',
        'val' => 'product_category',
        'name' => $this->l('Product Category Default'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'supplier_reference',
        'val' => 'supplier_reference',
        'name' => $this->l('Supplier Reference'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'supplier_price',
        'val' => 'supplier_price',
        'name' => $this->l('Supplier Price'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'supplier_name',
        'val' => 'supplier_name',
        'name' => $this->l('Supplier Name'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'manufacturer_name',
        'val' => 'manufacturer_name',
        'name' => $this->l('Manufacturer Name'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity',
        'val' => 'product_quantity',
        'name' => $this->l('Product quantity'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity_in_stock',
        'val' => 'product_quantity_in_stock',
        'name' => $this->l('Product quantity in stock'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity_refunded',
        'val' => 'product_quantity_refunded',
        'name' => $this->l('Product quantity refunded'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity_return',
        'val' => 'product_quantity_return',
        'name' => $this->l('Product quantity returned'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity_reinjected',
        'val' => 'product_quantity_reinjected',
        'name' => $this->l('Product quantity reinjected'),
        'tab'   => 'exportTabProducts'
      ),

      array(
        'id' => 'unit_product_price_tax_excl',
        'val' => 'unit_product_price_tax_excl',
        'name' => $this->l('Product Unit Price (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'unit_product_price_tax_incl',
        'val' => 'unit_product_price_tax_incl',
        'name' => $this->l('Product Unit Price (tax incl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'total_product_price_tax_excl',
        'val' => 'total_product_price_tax_excl',
        'name' => $this->l('Product Total Price (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'total_product_price_tax_incl',
        'val' => 'total_product_price_tax_incl',
        'name' => $this->l('Product Total Price (tax incl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'item_unit_price_tax_incl',
        'val' => 'item_unit_price_tax_incl',
        'name' => $this->l('Item Unit Price (tax incl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'item_unit_price_tax_excl',
        'val' => 'item_unit_price_tax_excl',
        'name' => $this->l('Item Unit Price (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'item_unit_type',
        'val' => 'item_unit_type',
        'name' => $this->l('Item Unit Type'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_weight',
        'val' => 'product_weight',
        'name' => $this->l('Product weight'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_width',
        'val' => 'product_width',
        'name' => $this->l('Product width'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_height',
        'val' => 'product_height',
        'name' => $this->l('Product height'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_depth',
        'val' => 'product_depth',
        'name' => $this->l('Product depth'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_ean13',
        'val' => 'product_ean13',
        'name' => $this->l('Product EAN 13'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_upc',
        'val' => 'product_upc',
        'name' => $this->l('Product UPC barcode'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_ecotax',
        'val' => 'product_ecotax',
        'name' => $this->l('Ecotax (tax incl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_ecotax_tax_excl',
        'val' => 'product_ecotax_tax_excl',
        'name' => $this->l('Ecotax (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_tax_rate',
        'val' => 'product_tax_rate',
        'name' => $this->l('Tax rate'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_total_tax_amount',
        'val' => 'product_total_tax_amount',
        'name' => $this->l('Total tax amount'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_unit_tax_amount',
        'val' => 'product_unit_tax_amount',
        'name' => $this->l('Unit tax amount'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_reduction_percent',
        'val' => 'product_reduction_percent',
        'name' => $this->l('Product reduction percent'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_reduction_amount_tax_incl',
        'val' => 'product_reduction_amount_tax_incl',
        'name' => $this->l('Product reduction amount with tax'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_reduction_amount_tax_excl',
        'val' => 'product_reduction_amount_tax_excl',
        'name' => $this->l('Product reduction amount (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'link_rewrite',
        'val' => 'link_rewrite',
        'name' => $this->l('Product link'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'product_quantity_slip',
        'val' => 'product_quantity_slip',
        'name' => $this->l('Credit slip product quantity'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'amount_tax_excl_slip',
        'val' => 'amount_tax_excl_slip',
        'name' => $this->l('Credit slip amount (tax excl.)'),
        'tab'   => 'exportTabProducts'
      ),
      array(
        'id' => 'amount_tax_incl_slip',
        'val' => 'amount_tax_incl_slip',
        'name' => $this->l('Credit slip amount (tax incl.)'),
        'tab'   => 'exportTabProducts'
      ),
    );


    /**
     *Add this fields only for PS of version 1.6.1.0 and newer
     *
     */
    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
      $this->_exportTabProducts[] = array(
        'id' => 'unit_price_tax_excl_slip',
        'val' => 'unit_price_tax_excl_slip',
        'name' => $this->l('Credit slip unit price (tax excl.)'),
        'tab'   => 'exportTabProducts'
      );

      $this->_exportTabProducts[] = array(
        'id' => 'unit_price_tax_incl_slip',
        'val' => 'unit_price_tax_incl_slip',
        'name' => $this->l('Credit slip unit price (tax incl.)'),
        'tab'   => 'exportTabProducts'
      );

      $this->_exportTabProducts[] = array(
        'id' => 'total_price_tax_excl_slip',
        'val' => 'total_price_tax_excl_slip',
        'name' => $this->l('Credit slip total price (tax excl.)'),
        'tab'   => 'exportTabProducts'
      );

      $this->_exportTabProducts[] = array(
        'id' => 'total_price_tax_incl_slip',
        'val' => 'total_price_tax_incl_slip',
        'name' => $this->l('Credit slip total price (tax incl.)'),
        'tab'   => 'exportTabProducts'
      );
    }

    $this->_exportTabCustomers = array(
      array(
        'id' => 'id_customer',
        'val' => 'id_customer',
        'name' => $this->l('Customer ID'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_firstname',
        'val' => 'customer_firstname',
        'name' => $this->l('Customer first name'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_lastname',
        'val' => 'customer_lastname',
        'name' => $this->l('Customer last name'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_fio',
        'val' => 'customer_fio',
        'name' => $this->l('Customer full name'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_birthday',
        'val' => 'customer_birthday',
        'name' => $this->l('Customer birthday'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_email',
        'val' => 'customer_email',
        'name' => $this->l('Customer email address'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_company',
        'val' => 'customer_company',
        'name' => $this->l('Company'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'default_customer_group',
        'val' => 'default_customer_group',
        'name' => $this->l('Default customer group'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_groups',
        'val' => 'customer_groups',
        'name' => $this->l('Customer groups'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
          'id' => 'new_client',
          'val' => 'new_client',
          'name' => $this->l('New Client'),
          'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'private_note',
        'val' => 'private_note',
        'name' => $this->l('Private Note'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_is_guest',
        'val' => 'customer_is_guest',
        'name' => $this->l('Is guest'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'customer_website',
        'val' => 'customer_website',
        'name' => $this->l('Website'),
        'tab'   => 'exportTabCustomers'
      ),
      array(
        'id' => 'newsletter',
        'val' => 'newsletter',
        'name' => $this->l('Newsletter Subscription'),
        'tab'   => 'exportTabCustomers'
      ),
    );

    $this->_exportTabShippingAddress = array(
      array(
        'id' => 'id_address_delivery',
        'val' => 'id_address_delivery',
        'name' => $this->l('Shipping Address ID'),
        'tab'   => 'exportTabShippingAddress'
      ),

      array(
        'id' => 'full_shipping_address',
        'val' => 'full_shipping_address',
        'name' => $this->l('Full Shipping Address'),
        'tab'   => 'exportTabShippingAddress'
      ),

      array(
        'id' => 'shipping_customer_firstname',
        'val' => 'shipping_customer_firstname',
        'name' => $this->l('Shipping customer first name'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_customer_lastname',
        'val' => 'shipping_customer_lastname',
        'name' => $this->l('Shipping customer last name'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_customer_fullname',
        'val' => 'shipping_customer_fullname',
        'name' => $this->l('Shipping customer full name'),
        'tab'   => 'exportTabShippingAddress'
      ),

      array(
        'id' => 'shipping_customer_dni',
        'val' => 'shipping_customer_dni',
        'name' => $this->l('Shipping DNI / NIF / NIE'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_alias',
        'val' => 'shipping_alias',
        'name' => $this->l('Shipping address alias'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_id_country',
        'val' => 'shipping_id_country',
        'name' => $this->l('Shipping Country ID'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_name_country',
        'val' => 'shipping_name_country',
        'name' => $this->l('Shipping Country name'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_iso_country',
        'val' => 'shipping_iso_country',
        'name' => $this->l('Shipping Country ISO code'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_id_state',
        'val' => 'shipping_id_state',
        'name' => $this->l('Shipping ID State'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_name_state',
        'val' => 'shipping_name_state',
        'name' => $this->l('Shipping State name'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_state_iso',
        'val' => 'shipping_state_iso',
        'name' => $this->l('Shipping State ISO code'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_address',
        'val' => 'shipping_address',
        'name' => $this->l('Shipping Address (1, 2)'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_address1',
        'val' => 'shipping_address1',
        'name' => $this->l('Shipping Address 1'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_address2',
        'val' => 'shipping_address2',
        'name' => $this->l('Shipping Address 2'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_postcode',
        'val' => 'shipping_postcode',
        'name' => $this->l('Shipping Zip/Postal Code'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_city',
        'val' => 'shipping_city',
        'name' => $this->l('Shipping City'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_other',
        'val' => 'shipping_other',
        'name' => $this->l('Shipping Other'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_phone',
        'val' => 'shipping_phone',
        'name' => $this->l('Shipping Phone'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_phone_mobile',
        'val' => 'shipping_phone_mobile',
        'name' => $this->l('Shipping Mobile phone'),
        'tab'   => 'exportTabShippingAddress'
      ),

      array(
        'id' => 'shipping_company',
        'val' => 'shipping_company',
        'name' => $this->l('Shipping Company'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'shipping_vat_number',
        'val' => 'shipping_vat_number',
        'name' => $this->l('Shipping Vat number'),
        'tab'   => 'exportTabShippingAddress'
      ),
    );

    $this->_exportTabInvoiceAddress = array(
      array(
        'id' => 'invoice_number',
        'val' => 'invoice_number',
        'name' => $this->l('Invoice number'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'id_address_invoice',
        'val' => 'id_address_invoice',
        'name' => $this->l('Invoice Address ID'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'full_invoice_address',
        'val' => 'full_invoice_address',
        'name' => $this->l('Full Invoice Address'),
        'tab'   => 'exportTabShippingAddress'
      ),
      array(
        'id' => 'invoice_customer_firstname',
        'val' => 'invoice_customer_firstname',
        'name' => $this->l('Invoice customer first name'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_customer_lastname',
        'val' => 'invoice_customer_lastname',
        'name' => $this->l('Invoice customer last name'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_customer_fullname',
        'val' => 'invoice_customer_fullname',
        'name' => $this->l('Invoice customer full name'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_customer_dni',
        'val' => 'invoice_customer_dni',
        'name' => $this->l('Invoice DNI / NIF / NIE'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_alias',
        'val' => 'invoice_alias',
        'name' => $this->l('Invoice address alias'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_id_country',
        'val' => 'invoice_id_country',
        'name' => $this->l('Invoice ID Country'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_name_country',
        'val' => 'invoice_name_country',
        'name' => $this->l('Invoice Country name'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_iso_country',
        'val' => 'invoice_iso_country',
        'name' => $this->l('Invoice Country ISO code'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_id_state',
        'val' => 'invoice_id_state',
        'name' => $this->l('Invoice ID State'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_name_state',
        'val' => 'invoice_name_state',
        'name' => $this->l('Invoice State name'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_state_iso',
        'val' => 'invoice_state_iso',
        'name' => $this->l('Invoice State ISO code'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_address1',
        'val' => 'invoice_address1',
        'name' => $this->l('Invoice Address 1'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_address2',
        'val' => 'invoice_address2',
        'name' => $this->l('Invoice Address 2'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_postcode',
        'val' => 'invoice_postcode',
        'name' => $this->l('Invoice Zip/Postal Code'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_city',
        'val' => 'invoice_city',
        'name' => $this->l('Invoice City'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_other',
        'val' => 'invoice_other',
        'name' => $this->l('Invoice Other'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_phone',
        'val' => 'invoice_phone',
        'name' => $this->l('Invoice Phone'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_phone_mobile',
        'val' => 'invoice_phone_mobile',
        'name' => $this->l('Invoice Mobile phone'),
        'tab'   => 'exportTabInvoiceAddress'
      ),

      array(
        'id' => 'invoice_company',
        'val' => 'invoice_company',
        'name' => $this->l('Invoice Company'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_vat_number',
        'val' => 'invoice_vat_number',
        'name' => $this->l('Invoice Vat number'),
        'tab'   => 'exportTabInvoiceAddress'
      ),

      array(
        'id' => 'note',
        'val' => 'note',
        'name' => $this->l('Invoice note'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
      array(
        'id' => 'invoice_date',
        'val' => 'invoice_date',
        'name' => $this->l('Invoice date'),
        'tab'   => 'exportTabInvoiceAddress'
      ),
    );

    $this->_exportTabPayment = array(
      array(
        'id' => 'payment',
        'val' => 'payment',
        'name' => $this->l('Payment'),
        'tab'   => 'exportTabPayment'
      ),
      array(
        'id' => 'payment_amount',
        'val' => 'payment_amount',
        'name' => $this->l('Payment amount'),
        'tab'   => 'exportTabPayment'
      ),
      array(
        'id' => 'payment_currency',
        'val' => 'payment_currency',
        'name' => $this->l('Payment currency'),
        'tab'   => 'exportTabPayment'
      ),
      array(
        'id' => 'payment_method',
        'val' => 'payment_method',
        'name' => $this->l('Payment method'),
        'tab'   => 'exportTabPayment'
      ),
      array(
        'id' => 'payment_transaction_id',
        'val' => 'payment_transaction_id',
        'name' => $this->l('Payment Transaction ID'),
        'tab'   => 'exportTabPayment'
      ),
      array(
        'id' => 'payment_date',
        'val' => 'payment_date',
        'name' => $this->l('Payment date'),
        'tab'   => 'exportTabPayment'
      ),
    );

  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('ActionAdminControllerSetMedia')
      || !$this->registerHook('actionOrderStatusPostUpdate')
      || !Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS', '', false, $this->default_shop_group_id, $this->default_shop_id)
      || !Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS_SETTINGS', '', false, $this->default_shop_group_id, $this->default_shop_id)
      || !Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', '', false, $this->default_shop_group_id, $this->default_shop_id)
    )
      return false;


    Configuration::updateGlobalValue('GOMAKOIL_ORDERS_EXPORT_TASKS_KEY', md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME')));
    $this->installDb();
    $this->_createTab();

    return true;
  }

  public function upgradeExport_4_5_0()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ordersexport_tasks(
			`id_task` int(11) NOT NULL AUTO_INCREMENT,
      `description` varchar(255) NOT NULL,
      `export_settings` varchar(255) NOT NULL,
      `hour` int(11) NOT NULL,
      `day` int(11) NOT NULL,
      `month` int(11) NOT NULL,
      `day_of_week` int(11) NOT NULL,
      `last_start` varchar(45) NOT NULL,
      `last_finish` varchar(45) NOT NULL,
      `active` int(1) NOT NULL,
      `one_shot` int(1) NOT NULL,
      `id_shop` int(11) NOT NULL,
      `id_shop_group` int(11) NOT NULL,
      PRIMARY KEY (`id_task`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);
    Configuration::updateGlobalValue('GOMAKOIL_ORDERS_EXPORT_TASKS_KEY', md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME')));
    $this->registerHook('actionOrderStatusPostUpdate');
    return true;
  }

  private function _createTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminOrdersExport';
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = 'Orders Export';
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab()
  {
    $id_tab = (int)Tab::getIdFromClassName('AdminOrdersExport');
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }


  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'exported_order';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'exported_order(
				id_exported_order int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_order  int(11) NULL,
		    settings varchar(255) NULL,
				PRIMARY KEY (`id_exported_order`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'ordersexport_tasks';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ordersexport_tasks(
			`id_task` int(11) NOT NULL AUTO_INCREMENT,
      `description` varchar(255) NOT NULL,
      `export_settings` varchar(255) NOT NULL,
      `hour` int(11) NOT NULL,
      `day` int(11) NOT NULL,
      `month` int(11) NOT NULL,
      `day_of_week` int(11) NOT NULL,
      `last_start` varchar(45) NOT NULL,
      `last_finish` varchar(45) NOT NULL,
      `active` int(1) NOT NULL,
      `one_shot` int(1) NOT NULL,
      `id_shop` int(11) NOT NULL,
      `id_shop_group` int(11) NOT NULL,
      PRIMARY KEY (`id_task`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);
  }


  public function uninstall(){
    if (!parent::uninstall()
      || !Configuration::deleteByName('GOMAKOIL_EXPORT_ORDERS')
      || !Configuration::deleteByName('GOMAKOIL_CUSTOMERS_CHECKED')
      || !Configuration::deleteByName('GOMAKOIL_EXPORT_ORDERS_SETTINGS')
    ) {
      return false;
    }

    Configuration::deleteByName('GOMAKOIL_ORDERS_EXPORT_TASKS_KEY');
    $this->uninstallDb();
    $this->_removeTab();


    return true;
  }

  public function uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'exported_order';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'ordersexport_tasks';
    Db::getInstance()->execute($sql);
  }

  public function hookActionAdminControllerSetMedia()
  {
    if(Tools::getValue('configure') == 'ordersexport'){
      $this->context->controller->addCSS($this->_path . 'views/css/ordersexport.css');

      if( version_compare(_PS_VERSION_, '1.6.0.8') >= 0 && version_compare(_PS_VERSION_, '1.8.0.0') < 0) {
        $this->context->controller->addCSS($this->_path . 'views/css/ordersexport.css');
      }
      else{
        $this->context->controller->addCSS($this->_path . 'views/css/ordersexport1607.css');
      }

      $this->context->controller->addJS($this->_path.'views/js/ordersexport.js');
      $this->context->controller->addJqueryUI('ui.sortable');
    }
  }

  public function hookActionOrderStatusPostUpdate($params)
  {
    $all_configs = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', $this->default_shop_group_id, $this->default_shop_id));
    if( !$all_configs ){
		$all_configs = array();
    }

    foreach ($all_configs as $key => $config) {
      $order_status_changed_autoexport = $config['order_status_changed_autoexport'];
      $order_status_for_autoexport = $config['order_status_autoexport_filter'];
      $id_shop_from_config = $config['shop_id'];

      if ($order_status_changed_autoexport == 1 && !empty($order_status_for_autoexport)) {
        $id_order_state = $params['newOrderStatus']->id;
        $id_order = $params['id_order'];
        $order = new Order($id_order);

        if ($id_shop_from_config == 'all' || ($id_shop_from_config != 'all' && $id_shop_from_config == $order->id_shop)) {

          if (in_array($id_order_state, $order_status_for_autoexport)) {
            try {
              $this->autoExport($config, $id_order, $key, null, $config['shop_id'], null, 0);
            } catch(Exception $e) {
              if ($e->getCode() != 777 || ($e->getCode() == 777 && $config['email_on_no_orders'] == 1)) {
                $this->sendEmail($config, $e->getMessage());
              }
            }
          }
        }
      }
    }

    return true;
  }

  public function autoExport($config, $id_order = null, $config_name, $secure_key = null, $id_shop = null, $id_lang = null, $limit = 0)
  {
    require_once _PS_MODULE_DIR_.'ordersexport/export.php';

    $id_shop = $id_shop == null ? Context::getContext()->shop->id : $id_shop;
    $id_lang = $id_lang == null ? Context::getContext()->language->id : $id_lang;

    $config['name_settings'] = $config_name;

    $config['file_name'] = $this->setFileName($config['specific_export_file'], $config['file_name'], $id_order);

    Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS', serialize($config), false, $this->default_shop_group_id, $this->default_shop_id);


    $export = new exportOrders();
    $res = $export->export($config, $id_shop, $id_lang, $limit, $id_order);

    if( $res !== true && $secure_key != null){
      Tools::redirect(Tools::getShopDomain(true, true).__PS_BASE_URI__.basename(_PS_MODULE_DIR_).'/ordersexport/automatic_export.php?settings='.$config_name.'&id_shop='.$id_shop.'&id_lang='.$id_lang.'&secure_key='.$secure_key.'&limit='.$res);
      return false;
    } elseif ($res !== true) {
      return false;
    }

    if( $config['specific_export_file'] && $config['file_name'] ){
      $link = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/ordersexport/files/'. $config['file_name'] .'.'.$config['format'];
    } else {
      $link = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/ordersexport/files/export_orders_'.Configuration::get('EXPORT_ORDERS_TIME','', $this->default_shop_group_id, $this->default_shop_id).'.'.$config['format'];
    }

    if (!$this->sendEmail($config, false, $link)) {
      return false;
    }

    return true;
  }

  public function sendEmail($config, $error = false, $link = false )
  {
    $export_file_attachment = null;
    $emails = $config['notification_emails'];
    $emails = trim($emails);
    if( !$emails ){
      return false;
    }
    $emails = explode("\n", $emails);

    foreach ($emails as $users_email){
      $users_email = trim($users_email);
      $mailMessage = '';
      $mailMessage .= '<div style="width: 50%; min-width: 160px;margin: 0 auto;margin-top: 40px;margin-bottom: 40px;border: 1px solid #dadada;border-radius: 6px;    ">';
      $mailMessage .= '<div style="padding: 20px;border-bottom: 1px solid #dadada;font-size: 20px;text-align: center;
        border-radius: 6px 6px 0px 0px;
        background-image: -ms-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -moz-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -o-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(20, #FFFFFF), color-stop(40, #FCFCFC), color-stop(60, #FAFAFA), color-stop(80, #FAFAFA), color-stop(100, #EDEDED));
        background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: linear-gradient(to bottom, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);">'.$this->l('Orders Export Report', 'automatic_export').'</div><div style="padding: 30px;font-size: 14px;">';

      if( $error ){
        $mailMessage .= '<div style="margin-bottom: 10px;"><div style="margin: 2px 10px 2px 0px;color: red"><strong>'.$this->l('Error:', 'automatic_export'). '</strong> ' . $error . '</div><div style="clear: both;"></div></div>';
      }

      $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 50%;margin: 2px 10px 2px 0px;"><strong>'.$this->l('Export settings name:', 'automatic_export').'</strong></div><div style="float: left;margin-top: 2px;"> ' . $config['name_settings'] .'</div><div style="clear: both;"></div></div>';
      $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 50%;margin: 2px 10px 2px 0px;"><strong>'.$this->l('Export date:', 'automatic_export').'</strong></div><div style="float: left;margin-top: 2px;"> ' . date('d/m/Y G:i:s') .'</div><div style="clear: both;"></div></div>';

      if( $config['feed_target'] == 'ftp' && !$error ){
        $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;margin: 2px 10px 2px 0px;"><strong>'.$this->l('Exported file successfully uploaded on your FTP Server!', 'automatic_export').'</strong></div><div style="clear: both;"></div></div>';
      } else{
        $mailMessage .= '<div style="margin-bottom: 10px;"><div style="float: left;width: 50%;margin: 2px 10px 2px 0px;"><strong>'.$this->l('Exported file:', 'automatic_export').'</strong></div><div style="float: left;margin-top: 2px;"> <a style="color: #00aff0;" href="' . $link . '">' . $link . '</a></div><div style="clear: both;"></div></div>';
      }

      if ($config['attach_file_to_mail'] == 1 && !$error) {
        if ($config['specific_export_file'] == 1 && !empty($config['file_name']) && $config['format']) {
          $export_file_name = $config['file_name'] .'.'.$config['format'];
        } else {
          $export_file_name = 'export_orders_'.Configuration::get('EXPORT_ORDERS_TIME','', $this->default_shop_group_id, $this->default_shop_id).'.'.$config['format'];
        }

        $export_file_path =  _PS_MODULE_DIR_.'/ordersexport/files/'.$export_file_name;
        $export_file = file_get_contents($export_file_path);
        if ($config['format'] == 'csv') {
          $export_file_mime = 'application/csv';
        } elseif ($config['format'] == 'xlsx') {
          $export_file_mime = 'application/vnd.ms-excel';
        }

        $export_file_attachment = array('name' => $export_file_name, 'mime' => $export_file_mime, 'content' => $export_file);
      }

      $mailMessage .= '<div style="clear: both;display: block !important;"></div><div style="clear: both;display: block !important;"></div><div style="clear: both; display: block !important;">';
      $template_vars = array('{content}' => $mailMessage);
      $mail = Mail::Send(
        Configuration::get('PS_LANG_DEFAULT'),
        'notification',
        $this->l('Orders Export Report', 'automatic_export'),
        $template_vars,
        "$users_email",
        NULL,
        Tools::getValue('email') ? Tools::getValue('email') : NULL,
        Tools::getValue('fio') ? Tools::getValue('fio') : NULL,
        $export_file_attachment,
        NULL,
        dirname(__FILE__).'/mails/');
      if( !$mail ){
        echo $this->l('Some error occurred please contact us!', 'automatic_export');
        die;
      }
    }

    return true;
  }

  public function getDateFromFileName($file_name)
  {
    if (!$file_name) {
      return false;
    }

    $matches = array();
    $preg_match_date_pattern = preg_match('/\{[\s\S]+\}/i', $file_name, $matches);

    if ($preg_match_date_pattern && isset($matches[0]) && !empty($matches[0]) && $matches[0]) {
      $date_pattern = $matches[0];
      $date_pattern = trim($date_pattern, '{}');
      $date = date($date_pattern);

      if (!$date) {
        return false;
      }

      return $date;
    }

    return false;
  }

  public function setFileName($specific_export_file_name, $initial_file_name, $id_order = null)
  {
    if( $specific_export_file_name && $initial_file_name){
      $date_from_file_name = $this->getDateFromFileName($initial_file_name);
      $file_name_with_date = preg_replace('/\{[\s\S]+\}/i', $date_from_file_name, $initial_file_name);

      $file_name = $date_from_file_name ? $file_name_with_date : $initial_file_name;

      if ($id_order) {
          $order = new Order($id_order);
          $file_name = preg_replace('/\[\$ID_ORDER\]/', $id_order, $file_name);
          $file_name = preg_replace('/\[\$ORDER_REFERENCE\]/', $order->reference , $file_name);
      }

      return $file_name;
    }

    return $initial_file_name;
  }

  public function getContent()
  {
    Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', '', false, $this->default_shop_group_id, $this->default_shop_id);

    $logo = '<img class="logo_myprestamodules" src="../modules/'.$this->name.'/logo.png" />';
    $name = '<h2 id="bootstrap_orders_export">'.$logo.$this->displayName.'</h2>';

    $this->displayForm();

    return $name.$this->_html;
  }

  public function displayTabDocumentation(){
    return $this->display(__FILE__, 'views/templates/hook/documentation.tpl');
  }

  public function displayTabSupport(){
    return $this->display(__FILE__, 'views/templates/hook/tabSuppor.tpl');
  }

  public function displayTabModules(){
    return $this->display(__FILE__, 'views/templates/hook/modules.tpl');
  }

  public function displayForm()
  {
    $show_del = 'hide_block';
    if(Tools::getValue('settings')) {
      $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','',$this->default_shop_group_id, $this->default_shop_id));
      $set = $config[Tools::getValue('settings')];
      $settings = $config[Tools::getValue('settings')];
      if($settings['format'] && $settings['format'] == 'csv'){
        $show_del = 'show_block';
      }
    }
    else{
      $set = false;

    }



    $delimiter = array(
      array(
        'id' => ',',
        'name' => ',',
      ),
      array(
        'id' => ';',
        'name' => ';',
      ),
      array(
        'id' => ':',
        'name' => ':',
      ),
      array(
        'id' => '.',
        'name' => '.',
      ),
      array(
        'id' => '/',
        'name' => '/',
      ),
      array(
        'id' => '|',
        'name' => '|',
      ),
      array(
        'id' => '-',
        'name' => '-',
      ),
      array(
        'id' => 'space',
        'name' => 'space',
      ),
      array(
        'id' => 'tab',
        'name' => 'tab',
      ),
    );

    $seperatop = array(
      array(
        'id' => '1',
        'name' => ' " " ',
      ),
      array(
        'id' => '2',
        'name' => ' ` ` ',
      ),
      array(
        'id' => '3',
        'name' => 'no',
      ),
    );

    $invoice = array(
      array(
        'id' => '0',
        'name' => $this->l('No'),
      ),
      array(
        'id' => '1',
        'name' => $this->l('With invoices'),
      ),
      array(
        'id' => '2',
        'name' => $this->l('Without invoices'),
      ),
    );

    $period = array(
      array(
        'id' => 'no',
        'name' => $this->l('Blank')
      ),
      array(
        'id' => 'today',
        'name' => $this->l('Today')
      ),
      array(
        'id' => 'yesterday',
        'name' => $this->l('Yesterday')
      ),
      array(
        'id' => 'this_week',
        'name' => $this->l('This week')
      ),
      array(
        'id' => 'last_week',
        'name' => $this->l('Last week')
      ),
      array(
        'id' => 'this_month',
        'name' => $this->l('This month')
      ),
      array(
        'id' => 'last_month',
        'name' => $this->l('Last month')
      ),
      array(
        'id' => 'period',
        'name' => $this->l('Select date')
      ),
    );

    $date_type = array(
      array(
        'id' => 'date_add',
        'name' => $this->l('Order Add Date')
      ),
      array(
        'id' => 'date_upd',
        'name' => $this->l('Order Update Date')
      ),
      array(
        'id' => 'delivery_date',
        'name' => $this->l('Order Delivery Date')
      ),
      array(
        'id' => 'invoice_date',
        'name' => $this->l('Invoice Add Date')
      ),
      array(
        'id' => 'payment_date',
        'name' => $this->l('Payment Add Date')
      ),
      array(
        'id' => 'date_add_state',
        'name' => $this->l('Order Status Update Date')
      ),
      array(
        'id' => 'date_add_slip',
        'name' => $this->l('Credit Slip Add Date')
      ),
    );


    $round_value = array(
      array(
        'id' => '0',
        'name' => '0',
      ),
      array(
        'id' => '1',
        'name' => '1',
      ),
      array(
        'id' => '2',
        'name' => '2',
      ),
      array(
        'id' => '3',
        'name' => '3',
      ),
      array(
        'id' => '4',
        'name' => '4',
      ),
      array(
        'id' => '5',
        'name' => '5',
      ),
      array(
        'id' => '6',
        'name' => '6',
      ),
    );


    $sort = array(
      array(
        'name' => 'ID',
        'id' => 'id_order',
      ),
      array(
        'name' => 'Date add',
        'id' => 'date_add',
      ),
      array(
        'name' => 'Date update',
        'id' => 'date_update',
      ),
      array(
        'name' => 'Total order price',
        'id' => 'price',
      ),
      array(
        'name' => 'Number of order products',
        'id' => 'quantity',
      ),

    );

    $date_format = array(
      array(
        'id' => 'Y-m-d H:i:s',
        'name' => 'Y-m-d H:i:s',
      ),
      array(
        'id' => 'Y-m-d',
        'name' => 'Y-m-d',
      ),
      array(
        'id' => 'd.m.Y H:i:s',
        'name' => 'd.m.Y H:i:s',
      ),
      array(
        'id' => 'd.m.Y',
        'name' => 'd.m.Y',
      ),
      array(
        'id' => 'Y.m.d H:i:s',
        'name' => 'Y.m.d H:i:s',
      ),
      array(
        'id' => 'Y.m.d',
        'name' => 'Y.m.d',
      ),
      array(
        'id' => 'm/d/Y H:i:s',
        'name' => 'm/d/Y H:i:s',
      ),
      array(
        'id' => 'm/d/Y',
        'name' => 'm/d/Y',
      ),
      array(
        'id' => 'd/m/Y H:i:s',
        'name' => 'd/m/Y H:i:s',
      ),
      array(
        'id' => 'd/m/Y',
        'name' => 'd/m/Y',
      ),
      array(
        'id' => 'Y-M-D G:i:s',
        'name' => 'Y-M-D G:i:s',
      ),
      array(
        'id' => 'Y-M-D',
        'name' => 'Y-M-D',
      ),
      array(
        'id' => 'Y M D G:i:s',
        'name' => 'Y M D G:i:s',
      ),
      array(
        'id' => 'Y M D',
        'name' => 'Y M D',
      ),
    );

    $shop_options = Shop::getShops();
    $export_from_all_shops_option = array('id_shop' => 'all', 'name' => 'All Shops');
    array_unshift($shop_options, $export_from_all_shops_option);

    $file_url = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/ordersexport/files/';

    //$nameDescription .= '<p>'.$this->l('The file will be available by link below:').'</p>';
    //$nameDescription .= '<p><strong><a target="_blank" class="href_export_file"  href="" data-file-url="'.$file_url.'"></a></strong></p>';

    $description = '<p>'.$this->l('You must save this order export settings before enable automatic export.').'</p>';
    if( Tools::getValue('settings') ){
      $description = '<p>'.$this->l('You can place the following URL in your crontab file, or you can click it yourself regularly').'</p>';
      $description .= '<p><strong><a href="'.Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.basename(_PS_MODULE_DIR_).'/ordersexport/automatic_export.php?settings='.Tools::getValue('settings').'&id_shop='.Context::getContext()->shop->id.'&id_lang='.Context::getContext()->language->id.'&secure_key='.md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME')).'" onclick="return !window.open($(this).attr(\'href\'));">'.Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.basename(_PS_MODULE_DIR_).'/ordersexport/automatic_export.php?settings='.Tools::getValue('settings').'&id_shop='.Context::getContext()->shop->id.'&id_lang='.Context::getContext()->language->id.'&secure_key='.md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME')).'</a></strong></p>';
    }

    $manufacturers_ids = array();
    $manufacturers = array_filter(Manufacturer::getManufacturers(), function($manufacturer) use (&$manufacturers_ids) {
      if (in_array($manufacturer['id_manufacturer'], $manufacturers_ids)) {
        return false;
      }

      array_push($manufacturers_ids, $manufacturer['id_manufacturer']);
      return true;
    });

    $url_base = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=ordersexport';

    if( Tools::isSubmit('add_task') && Tools::getValue('module_tab') == 'schedule_tasks' ){
      $addRes =  $this->_addTask();
    }

    $this->fields_form[0]['form'] = array(
      'tabs' => array(
        'welcome' => $this->l('Welcome'),
        'export' => $this->l('General settings'),
        'filter_orders' => $this->l('Filter orders'),
        'filter_fields' => $this->l('Filter fields'),
        'automatic_export' => $this->l('Automatic orders export'),
        'schedule_tasks' => $this->l('Schedule Tasks'),
        'settings' => $this->l('Settings'),
        'documentation' => $this->l('Documentation'),
        'support' => $this->l('Support'),
        'modules' => $this->l('Related Modules'),
      ),
      'input' => array(
        array(
          'type' => 'html',
          'form_group_class' => 'form_group_welcome',
          'tab' => 'welcome',
          'name' => $this->initFormWelcome(),
        ),
        array(
          'type' => 'html',
          'form_group_class' => 'form_group_schedule',
          'tab' => 'schedule_tasks',
          'name' => $this->initFormScheduleTasks(),
        ),
        array(
          'type' => 'html',
          'form_group_class' => 'form_group_module_hind',
          'tab' => 'export',
          'name' => '<div class="alert alert-info">' . $this->l('If no filter is selected, module will export all orders!') . '</div>',
        ),
        array(
          'type' => 'html',
          'form_group_class' => 'form_group_module_hind',
          'tab' => 'automatic_export',
          'name' => '<div class="alert alert-info">' . $this->l('Do not forget save settings after automatic export editing') . '</div>',
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Shop ID'),
          'name' => 'shop_id',
          'class' => 'shop_id',
          'tab' => 'export',
          'form_group_class' => 'shop_id_block',
          'options' => array(
            'query' =>$shop_options,
            'id' => 'id_shop',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'radio',
          'label' => $this->l('Select file format:'),
          'name' => 'format_file',
          'required' => true,
          'class' => 'format_file',
          'br' => true,
          'tab' => 'export',
          'values' => array(
            array(
              'id' => 'format_csv',
              'value' => 'csv',
              'label' => $this->l('CSV')
            ),
            array(
              'id' => 'format_xlsx',
              'value' => 'xlsx',
              'label' => $this->l('XLSX')
            )
          ),
          'desc' => $this->l('Choose a file format you wish to export'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Delimiter'),
          'name' => 'delimiter_val',
          'class' => 'delimiter_val',
          'tab' => 'export',
          'form_group_class' => 'csv_delimiter block_csv_settings '.$show_del,
          'options' => array(
            'query' =>$delimiter,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Seperatop'),
          'name' => 'seperatop_val',
          'class' => 'seperatop_val',
          'tab' => 'export',
          'form_group_class' => 'csv_seperatop block_csv_settings '.$show_del,
          'options' => array(
            'query' =>$seperatop,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Filter date type'),
          'name' => 'date_type',
          'class' => 'date_type',
          'form_group_class' => 'date_type_form',
          'tab' => 'filter_orders',
          'required' => true,
          'options' => array(
            'query' =>$date_type,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Filter date period'),
          'name' => 'date_period',
          'class' => 'date_period',
          'form_group_class' => 'date_period_form',
          'tab' => 'filter_orders',
          'required' => true,
          'options' => array(
            'query' =>$period,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'date',
          'label' => $this->l('From:'),
          'name' => 'date_from',
          'tab' => 'filter_orders',
          'maxlength' => 10,
          'form_group_class' => 'date_from',
          'hint' => $this->l('Format: 2011-12-31 (inclusive).'),
        ),
        array(
          'type' => 'date',
          'label' => $this->l('To:'),
          'name' => 'date_to',
          'tab' => 'filter_orders',
          'form_group_class' => 'date_to',
          'maxlength' => 10,
          'hint' => $this->l('Format: 2011-12-31 (inclusive).')
        ),




        array(
          'type' => 'select',
          'label' => $this->l('Invoice filter'),
          'name' => 'isset_invoice',
          'class' => 'isset_invoice',
          'tab' => 'filter_orders',
          'form_group_class' => 'display_invoice_block',
          'options' => array(
            'query' =>$invoice,
            'id' => 'id',
            'name' => 'name'
          )
        ),





        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by order status'),
          'name' => 'status[]',
          'hint' => '',
          'tab' => 'filter_orders',
          'class_block' => 'table_bl',
          'class_input' => 'table_in statusOrders',
          'values'  => array(
            'query' =>  OrderState::getOrderStates(Context::getContext()->language->id),
            'id'    => 'id_order_state',
            'name'  => 'name',
          ),
        ),
        array(
          'type' => 'group',
          'label' => $this->l('Filter by customers group'),
          'name' => 'groupBox',
          'form_group_class' => 'table_bl',
          'tab' => 'filter_orders',
          'values' => Group::getGroups(Context::getContext()->language->id),
        ),
        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by customers'),
          'name' => 'customers[]',
          'hint' => '',
          'tab' => 'filter_orders',
          'class_block' => 'table_bl',
          'class_input' => 'table_in customers_checkbox',
          'search' => true,
          'values'  => array(
            'query' =>  $this->_model->getCustomers(),
            'id'    => 'id_customer',
            'name'  => 'lastname',
            'name2'   => 'firstname'
          ),
        ),

        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by payment method'),
          'name' => 'payment[]',
          'hint' => '',
          'class_block' => 'table_bl',
          'tab' => 'filter_orders',
          'class_input' => 'table_in paymentOrders',
          'values'  => array(
            'query' =>  $this->_model->getPayment(Context::getContext()->shop->id),
            'id'    => 'payment',
            'name'  => 'payment',
          ),
        ),
        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by carriers'),
          'name' => 'carrier[]',
          'hint' => '',
          'tab' => 'filter_orders',
          'class_block' => 'table_bl',
          'class_input' => 'table_in carrierOrders',
          'values'  => array(
            'query' =>  Carrier::getCarriers(Context::getContext()->language->id, false, false, false, null, false),
            'id'    => 'id_reference',
            'name'  => 'name',
          ),
        ),
        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by product suppliers'),
          'name' => 'supplier[]',
          'hint' => '',
          'tab' => 'filter_orders',
          'class_block' => 'table_bl',
          'class_input' => 'table_in supplierOrders',
          'values'  => array(
            'query' =>  Supplier::getSuppliers(),
            'id'    => 'id_supplier',
            'name'  => 'name',
          ),
        ),
        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Filter by product manufacturers'),
          'name' => 'manufacturer[]',
          'hint' => '',
          'tab' => 'filter_orders',
          'class_block' => 'table_bl',
          'class_input' => 'table_in manufacturerOrders',
          'values'  => array(
            'query' =>  $manufacturers,
            'id'    => 'id_manufacturer',
            'name'  => 'name',
          ),
        ),
        array(
          'type' => 'html',
          'name' => '<div></div>',
          'tab' => 'filter_orders',
          'form_group_class' => 'form_group_clear',
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Feed target'),
          'name' => 'feed_target',
          'tab' => 'export',
          'class' => 'feed_target',
          'options' => array(
            'query' => array(
              array(
                'id' => 'file_system',
                'name' => 'File System',
              ),
              array(
                'id' => 'ftp',
                'name' => 'FTP',
              ),
            ),
            'id' => 'id',
            'name' => 'name'
          ),
          'desc' => $this->l('Choose a feed target'),
        ),
        array(
          'type'     => 'select',
          'label'    => $this->l('Transfer Protocol'),
          'required' => true,
          'name'     => 'ftp_transfer_protocol',
          'options' => [
              'query' => [
                  [
                      'id' => 'ftp',
                      'name' => 'FTP',
                  ],
                  [
                      'id' => 'sftp',
                      'name' => 'SFTP',
                  ],
              ],
              'id' => 'id',
              'name' => 'name'
          ],
          'form_group_class' => 'ftp_target',
          'tab' => 'export',
        ),
        array(
          'type'     => 'text',
          'label'    => $this->l('FTP Server'),
          'required' => true,
          'name'     => 'ftp_server',
          'form_group_class' => 'ftp_target',
          'tab' => 'export',
        ),
        array(
          'type'     => 'text',
          'label'    => $this->l('User Name'),
          'required' => true,
          'name'     => 'ftp_user',
          'form_group_class' => 'ftp_target',
          'tab' => 'export',
        ),
        array(
          'type'     => 'text',
          'label'    => $this->l('Password'),
          'required' => true,
          'name'     => 'ftp_password',
          'form_group_class' => 'ftp_target',
          'tab' => 'export',
        ),
        array(
          'type'     => 'text',
          'label'    => $this->l('Absolute path to folder'),
          'name'     => 'ftp_folder_path',
          'form_group_class' => 'ftp_target',
          'tab' => 'export',
        ),
          array(
              'type'     => 'text',
              'label'    => $this->l('FTP Port'),
              'required' => true,
              'name'     => 'ftp_port',
              'form_group_class' => 'ftp_target',
              'tab' => 'export',
          ),
        array(
          'type' => 'switch',
          'label' => $this->l('Set specific file name'),
          'name' => 'specific_export_file',
          'class' => 'specific_export_file',
          'form_group_class' => 'form_group_class_hide',
          'is_bool' => true,
          'tab' => 'export',
          'values' => array(
            array(
              'id' => 'specific_export_file_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'specific_export_file_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
          'desc' => $this->l('You can set name for file or name will be given by system.'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Name for exported file'),
          'name' => 'file_name',
          'desc' => 'To add current date/time to file name, put <strong>{DATE PATTERN}</strong> variable into name field and replace "DATE_PATTERN" with valid PHP date pattern."<br>
                      <strong>Example: You type - "exported_orders_{d-m-Y H:i:s}" and your file name will be "exported_orders_17-09-2017 12:30:00".</strong><br>
                     Learn more about PHP date on official documentation page: <a>http://php.net/manual/en/function.date.php</a><br><br>
                      
                    <span id="append_id_order_to_file_name_desc">
                    To add id_order or reference of newly created order, put following variables in file name.<br>
                    <strong>[$ID_ORDER]</strong>-for id_order, <strong>[$ORDER_REFERENCE]</strong>-for order_reference. </span><br>',
          'tab' => 'export',
          'form_group_class' => 'form_group_name_file',
        ),
/*        array(
          'type' => 'html',
          'name' => $nameDescription,
          'tab' => 'export',
          'form_group_class' => 'file_description form_group_name_file',
        ),*/
        array(
          'type' => 'switch',
          'label' => $this->l('Display headers'),
          'name' => 'display_headers',
          'class' => 'display_headers',
          'tab' => 'export',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'display_headers_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'display_headers_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
          'desc' =>  $this->l('Add a first line in the file with columns names. You can modify the names with the translation tool'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Date format'),
          'name' => 'date_format',
          'tab' => 'export',
          'class' => 'date_format',
          'form_group_class' => 'date_format_block ',
          'options' => array(
            'query' =>$date_format,
            'id' => 'id',
            'name' => 'name'
          ),
          'desc' => $this->l('Formats the output system date / time'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Separator of decimal points'),
          'name' => 'separator_decimal_points',
          'tab' => 'export',
          'class' => 'separator_decimal_points',
          'form_group_class' => 'separator_decimal_points_block',
          'options' => array(
            'query' => array(
              array('id' => '.', 'name' => '.'),
              array('id' => ',', 'name' => ','),
            ),
            'id' => 'id',
            'name' => 'name'
          ),

        ),


        array(
          'type' => 'select',
          'label' => $this->l('Number of decimal points'),
          'name' => 'round_value',
          'class' => 'round_value',
          'tab' => 'export',
          'form_group_class' => 'round_value_block ',
          'options' => array(
            'query' =>$round_value,
            'id' => 'id',
            'name' => 'name'
          ),
          'desc' =>  $this->l('Will be used in the prices and size. You can choose to have 5.12 instead of 5.121123.'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Sort by'),
          'name' => 'sort',
          'class' => 'sort',
          'tab' => 'export',
          'form_group_class' => 'sort_block ',
          'options' => array(
            'query' =>$sort,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'radio',
          'label' => $this->l(' '),
          'name' => 'orderway',
          'required' => true,
          'tab' => 'export',
          'form_group_class' => 'sort_block_orderway',
          'br' => true,
          'values' => array(
            array(
              'id' => 'orderway_asc',
              'value' => 'asc',
              'label' => $this->l('ASC')
            ),
            array(
              'id' => 'orderway_desc',
              'value' => 'desc',
              'label' => $this->l('DESC')
            )
          )
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Each product in a separate line'),
          'name' => 'separate',
          'class' => 'separate',
          'tab' => 'export',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'separate_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'separate_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
          'desc' => $this->l('If activated, a line will be created for each products'),
        ),
        array(
          'type' => 'html',
          'tab' => 'filter_fields',
          'form_group_class' => 'exportFields',
          'name' => $this->initFields($set)
        ),
        array(
          'type' => 'html',
          'tab' => 'documentation',
          'form_group_class' => 'support_tab_content',
          'name' => $this->displayTabDocumentation()
        ),
        array(
          'type' => 'html',
          'tab' => 'support',
          'form_group_class' => 'support_tab_content',
          'name' => $this->displayTabSupport()
        ),
        array(
          'type' => 'html',
          'tab' => 'modules',
          'form_group_class' => 'support_tab_content',
          'name' => $this->displayTabModules()
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Automatically export by link'),
          'name' => 'automatic',
          'class' => 'automatic',
          'form_group_class' => 'autoexport-by-link',
          'tab' => 'automatic_export',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'automatic_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'automatic_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Automatically export new orders or on status change'),
          'name' => 'order_status_changed_autoexport',
          'form_group_class' => 'order-status-changed-autoexport',
          'tab' => 'automatic_export',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'order_status_changed_autoexport_on',
              'value' => 1,
              'label' => $this->l('Yes')
            ),
            array(
              'id' => 'order_status_changed_autoexport_off',
              'value' => 0,
              'label' => $this->l('No')
            )
          ),
        ),
        array(
          'type' => 'checkbox_table',
          'label' => $this->l('Automatically export new orders with following status'),
          'name' => 'order_status_autoexport_filter[]',
          'hint' => '',
          'tab' => 'automatic_export',
          'class_block' => 'table_bl order-status-autoexport-filter-block',
          'class_input' => 'table_in order-status-autoexport-filter-field',
          'values'  => array(
            'query' =>  OrderState::getOrderStates(Context::getContext()->language->id),
            'id'    => 'id_order_state',
            'name'  => 'name',
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Attach exported file to e-mail notification'),
          'name' => 'attach_file_to_mail',
          'form_group_class' => 'attach-file-to-mail',
          'tab' => 'automatic_export',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'attach_file_to_mail_on',
              'value' => 1,
              'label' => $this->l('Yes')
            ),
            array(
              'id' => 'attach_file_to_mail_off',
              'value' => 0,
              'label' => $this->l('No')
            )
          ),
        ),
        array(
          'type' => 'html',
          'tab' => 'automatic_export',
          'name' => $description,
          'form_group_class' => 'auto_description',
        ),
        array(
          'type'  => 'textarea',
          'label' => $this->l('Emails For Orders Export Report'),
          'name'  => 'notification_emails',
          'tab' => 'automatic_export',
          'class' => 'notification_emails',
          'hint'  => $this->l('Each email in a separate line'),
          'form_group_class' => 'auto_notif',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Export not exported'),
          'name' => 'not_exported',
          'tab' => 'automatic_export',
          'class' => 'not_exported',
          'form_group_class' => 'export_not_exported',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'not_exported_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'not_exported_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Send e-mail notification when there\'s no orders for export'),
          'name' => 'email_on_no_orders',
          'tab' => 'automatic_export',
          'form_group_class' => 'email-on-no-orders',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'email_on_no_orders_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'email_on_no_orders_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          ),
        ),
        array(
          'type' => 'html',
          'form_group_class' => 'save_settings_reset_filters',
          'tab' => 'settings',
          'name' => '<div class="url_base_setting"><a href="'.$url_base.'" ><i class="icon-refresh process-icon-refresh"></i>'.$this->l('Reset filters').'</a></div>'
        ),
        array(
          'label' => $this->l('Setting name'),
          'type' => 'text',
          'form_group_class' => 'new_settings_form',
          'tab' => 'settings',
          'name' => 'save_setting',
        ),
        array(
          'type' => 'html',
          'tab' => 'settings',
          'form_group_class' => 'saveSettingsExportButton',
          'name' => '<button type="button" class="btn btn-default saveOrdersExport" style="padding: 4px 30px;font-size: 16px;">'.$this->l('Save').'</button>'
        ),
        array(
          'type' => 'html',
          'tab' => 'settings',
          'form_group_class' => 'form_group_clear',
          'name' => '<div></div>'
        ),
        array(
          'type' => 'html',
          'tab' => 'settings',
          'form_group_class' => 'form_group_list_settings',
          'name' => $this->initSavedSettings(),
        ),

      )
    );

    $this->fields_form[1]['form'] = array(
      'input' => array(
        array(
          'type' => 'html',
          'form_group_class' => 'ordersExportButton',
          'name' => '<button type="button" class="btn btn-default ordersExport" style="padding: 4px 30px;font-size: 16px;">'.$this->l('Export').'</button>'
        ),
        array(
          'type' => 'hidden',
          'name' => 'id_shop',
        ),
        array(
          'type' => 'hidden',
          'name' => 'id_lang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'base_settings',
        ),
        array(
          'type' => 'hidden',
          'name' => 'export_token',
        ),
      )
    );

    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $helper = new HelperForm();
    $helper->module = $this;
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;

    $groups = Group::getGroups(Context::getContext()->language->id);

    if(Tools::getValue('settings')){
      $config_save = serialize($settings['customers_list']);
      Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', $config_save, false, $this->default_shop_group_id, $this->default_shop_id);

      $helper->fields_value['shop_id'] = $settings['shop_id'];
      $helper->fields_value['delimiter_val'] = $settings['delimiter_val'];
      $helper->fields_value['seperatop_val'] = $settings['seperatop_val'];

      $helper->fields_value['format_file'] = $settings['format'];
      $helper->fields_value['separate'] = $settings['separate'];
      $helper->fields_value['separator_decimal_points'] = $settings['separator_decimal_points'];
      $helper->fields_value['round_value'] = $settings['round_value'];
      $helper->fields_value['display_headers'] = $settings['display_headers'];
      $helper->fields_value['isset_invoice'] = $settings['isset_invoice'];
      $helper->fields_value['date_format'] = $settings['date_format'];
      $helper->fields_value['sort'] = $settings['sort'];
      $helper->fields_value['orderway'] = $settings['orderway'];
      $helper->fields_value['specific_export_file'] = $settings['specific_export_file'];
      $helper->fields_value['feed_target'] = $settings['feed_target'];
        $helper->fields_value['ftp_transfer_protocol'] = $settings['ftp_transfer_protocol'];
        $helper->fields_value['ftp_port'] = $settings['ftp_port'];
      $helper->fields_value['ftp_server'] = $settings['ftp_server'];
      $helper->fields_value['ftp_user'] = $settings['ftp_user'];
      $helper->fields_value['ftp_password'] = $settings['ftp_password'];
      $helper->fields_value['ftp_folder_path'] = $settings['ftp_folder_path'];
      $helper->fields_value['file_name'] = $settings['file_name'];
      $helper->fields_value['automatic'] = $settings['automatic'];
      $helper->fields_value['attach_file_to_mail'] = $settings['attach_file_to_mail'];
      $helper->fields_value['order_status_changed_autoexport'] = $settings['order_status_changed_autoexport'];
      $helper->fields_value['notification_emails'] = $settings['notification_emails'];
      $helper->fields_value['not_exported'] = $settings['not_exported'];
      $helper->fields_value['email_on_no_orders'] = $settings['email_on_no_orders'];
      $helper->fields_value['date_from'] = $settings['data']['time_from'];
      $helper->fields_value['date_to'] = $settings['data']['time_to'];
      $helper->fields_value['date_period'] = $settings['data']['date_period'];
      $helper->fields_value['date_type'] = $settings['data']['date_type'];

      foreach ($groups as $group){
        if($settings['group_list'] && in_array($group['id_group'], $settings['group_list'])){
          $helper->fields_value['groupBox_'.$group['id_group']] = 1;
        }
        else{
          $helper->fields_value['groupBox_'.$group['id_group']] = 0;
        }
      }

      foreach($this->_model->getCustomers() as $customer){
        if($settings['customers_list'] && in_array($customer['id_customer'], $settings['customers_list'])){
          $helper->fields_value['customers[]_'.$customer['id_customer']] = 1;
        }
        else{
          $helper->fields_value['customers[]_'.$customer['id_customer']] = 0;
        }
      }

      foreach(OrderState::getOrderStates(Context::getContext()->language->id) as $status){
        if($settings['status_list'] && in_array($status['id_order_state'], $settings['status_list'])){
          $helper->fields_value['status[]_'.$status['id_order_state']] = 1;
        }
        else{
          $helper->fields_value['status[]_'.$status['id_order_state']] = 0;
        }

        if($settings['order_status_autoexport_filter'] && in_array($status['id_order_state'], $settings['order_status_autoexport_filter'])){
          $helper->fields_value['order_status_autoexport_filter[]_'.$status['id_order_state']] = 1;
        }
        else{
          $helper->fields_value['order_status_autoexport_filter[]_'.$status['id_order_state']] = 0;
        }
      }

      foreach($this->_model->getPayment(Context::getContext()->shop->id) as $payment){
        if($settings['payment_list'] && in_array($payment['payment'], $settings['payment_list'])){
          $helper->fields_value['payment[]_'.$payment['payment']] = 1;
        }
        else{
          $helper->fields_value['payment[]_'.$payment['payment']] = 0;
        }
      }

      foreach(Carrier::getCarriers(Context::getContext()->language->id, false, false, false, null, false) as $carrier){
        if($settings['carrier_list'] && in_array($carrier['id_reference'], $settings['carrier_list'])){
          $helper->fields_value['carrier[]_'.$carrier['id_reference']] = 1;
        }
        else{
          $helper->fields_value['carrier[]_'.$carrier['id_reference']] = 0;
        }
      }

      foreach(Supplier::getSuppliers() as $supplier){
        if($settings['supplier_list'] && in_array($supplier['id_supplier'], $settings['supplier_list'])){
          $helper->fields_value['supplier[]_'.$supplier['id_supplier']] = 1;
        }
        else{
          $helper->fields_value['supplier[]_'.$supplier['id_supplier']] = 0;
        }
      }

      foreach(Manufacturer::getManufacturers() as $manufacturer){
        if($settings['manufacturer_list'] && in_array($manufacturer['id_manufacturer'], $settings['manufacturer_list'])){
          $helper->fields_value['manufacturer[]_'.$manufacturer['id_manufacturer']] = 1;
        }
        else{
          $helper->fields_value['manufacturer[]_'.$manufacturer['id_manufacturer']] = 0;
        }
      }

      $helper->fields_value['save_setting'] = Tools::getValue('settings');

    }
    else{
      foreach ($groups as $group)
        $helper->fields_value['groupBox_'.$group['id_group']] = 0;

      foreach($this->_model->getCustomers() as $customer)
        $helper->fields_value['customers[]_'.$customer['id_customer']] = 0;

      foreach(OrderState::getOrderStates(Context::getContext()->language->id) as $status) {
        $helper->fields_value['status[]_'.$status['id_order_state']] = 0;
        $helper->fields_value['order_status_autoexport_filter[]_'.$status['id_order_state']] = 0;
      }


      foreach($this->_model->getPayment(Context::getContext()->shop->id) as $payment)
        $helper->fields_value['payment[]_'.$payment['payment']] = 0;

      foreach(Carrier::getCarriers(Context::getContext()->language->id, false, false, false, null, false) as $carrier) {
        $helper->fields_value['carrier[]_'.$carrier['id_reference']] = 0;
      }

      foreach(Supplier::getSuppliers() as $supplier) {
        $helper->fields_value['supplier[]_'.$supplier['id_supplier']] = 0;
      }

      foreach(Manufacturer::getManufacturers() as $manufacturer) {
        $helper->fields_value['manufacturer[]_'.$manufacturer['id_manufacturer']] = 0;
      }

      Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', '', false, $this->default_shop_group_id, $this->default_shop_id);
      $helper->fields_value['save_setting'] = '';
      $helper->fields_value['format_file'] = 'xlsx';
      $helper->fields_value['date_from'] = '';
      $helper->fields_value['date_to'] = '';
      $helper->fields_value['date_period'] = 'no';
      $helper->fields_value['date_type'] = 'no';
      $helper->fields_value['separate'] = 1;
      $helper->fields_value['separator_decimal_points'] = '.';
      $helper->fields_value['display_headers'] = 1;
      $helper->fields_value['isset_invoice'] = 0;
      $helper->fields_value['round_value'] = 2;
      $helper->fields_value['date_format'] = 'Y-m-d H:i:s';
      $helper->fields_value['sort'] = 'id_order';
      $helper->fields_value['orderway'] = 'asc';
      $helper->fields_value['specific_export_file'] = 0;
      $helper->fields_value['feed_target'] = 'file_system';
        $helper->fields_value['ftp_transfer_protocol'] = '';
        $helper->fields_value['ftp_port'] = '';
      $helper->fields_value['ftp_server'] = '';
      $helper->fields_value['ftp_user'] = '';
      $helper->fields_value['ftp_password'] = '';
      $helper->fields_value['ftp_folder_path'] = '';
      $helper->fields_value['file_name'] = '';
      $helper->fields_value['automatic'] = 0;
      $helper->fields_value['attach_file_to_mail'] = 0;
      $helper->fields_value['order_status_changed_autoexport'] = 0;
      $helper->fields_value['notification_emails'] = '';
      $helper->fields_value['not_exported'] = 0;
      $helper->fields_value['email_on_no_orders'] = 0;
      $helper->fields_value['delimiter_val'] = ',';
      $helper->fields_value['shop_id'] = Context::getContext()->shop->id;
      $helper->fields_value['seperatop_val'] = 1;

    }

    $helper->fields_value['id_shop'] = Context::getContext()->shop->id;
    $helper->fields_value['id_lang'] = Context::getContext()->language->id;
    $helper->fields_value['export_token'] = Tools::getAdminTokenLite('AdminOrdersExport');
    $helper->fields_value['base_settings'] = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=ordersexport';
    $helper->fields_value['search_field'] = '';

    $this->_html .= $helper->generateForm($this->fields_form);

    if( Tools::getValue('module_tab') == 'newcronjob' && !Tools::getValue('deleteordersexport') ){
      $this->_html .= $this->initFormAddTask();
    }

    if( Tools::getValue('statusordersexport') !== false ){
      $this->_updateTaskStatus();
    }
    if( Tools::getValue('oneshotordersexport') !== false ){
      $this->_updatetaskOneShot();
    }
    if( Tools::getValue('deleteordersexport') !== false ){
      $this->_deleteTask();
    }


    if( Tools::isSubmit('add_task') && Tools::getValue('module_tab') == 'schedule_tasks' ){
      if( !$addRes ){
        $this->_html .= $this->initFormAddTask( $this->_errors );
      }
    }
  }

  public function initFormWelcome()
  {
    $this->context->controller->addCSS('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600');

    $filePerms = Tools::substr(sprintf('%o', fileperms(_PS_MODULE_DIR_ . 'ordersexport/send.php')), -3);
    $folderPerms = Tools::substr(sprintf('%o', fileperms(_PS_MODULE_DIR_ . 'ordersexport/')), -3);

    $allowUrl = false;
    if( in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) ){
      $allowUrl = true;
    }

    $requirementsOk = false;
    if( $filePerms == 644 && $folderPerms == 755 && $allowUrl == true && class_exists('ZipArchive' ) ){
      $requirementsOk = true;
    }

    $currentVersion = $this->version;
    $lastVersion = Configuration::getGlobalValue('GOMAKOIL_ORDERS_EXPORT_VERSION');


    $this->context->smarty->assign(
      array(
        'module_path'           => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . basename(_PS_MODULE_DIR_) . '/ordersexport/',
        'file_perms'            => $filePerms,
        'folder_perms'          => $folderPerms,
        'orders_export_token' => Tools::getAdminTokenLite('AdminOrdersExport'),
        'php_zip'               => class_exists('ZipArchive'),
        'max_execution_time'    => ini_get('max_execution_time'),
        'memory_limit'          => ini_get('memory_limit'),
        'allow_url_fopen'       => $allowUrl,
        'requirements_ok'       => $requirementsOk,
        'current_version'       => $currentVersion,
        'last_version'          => $lastVersion,
      )
    );

    return $this->display(__FILE__, 'views/templates/hook/welcome.tpl');
  }

  private function _updateTaskStatus()
  {
    $idTask = (int)Tools::getValue('id_task');
    if( $idTask ){
      Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ordersexport_tasks
            SET `active` = IF (`active`, 0, 1) WHERE `id_task` = \''.(int)$idTask.'\'');

      Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
        .'&token='.Tools::getAdminTokenLite('AdminModules').'&module_tab=schedule_tasks');
    }
  }

  private function _updatetaskOneShot()
  {
    $idTask = (int)Tools::getValue('id_task');
    if( $idTask ){
      Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ordersexport_tasks
            SET `one_shot` = IF (`one_shot`, 0, 1) WHERE `id_task` = \''.(int)$idTask.'\'');

      Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
        .'&token='.Tools::getAdminTokenLite('AdminModules').'&module_tab=schedule_tasks');
    }
  }

  private function _deleteTask()
  {
    $idTask = (int)Tools::getValue('id_task');
    if( $idTask ){
      Db::getInstance()->delete('ordersexport_tasks', "id_task=$idTask");
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
        .'&token='.Tools::getAdminTokenLite('AdminModules').'&module_tab=schedule_tasks');
    }
  }

  private function _addTask()
  {
    $values = $this->_getScheduleValues();
    if( !$values['export_settings'] ){
      $this->_errors = $this->l('You must select settings for export!');
      return false;
    }

    $values['id_shop'] = Context::getContext()->shop->id;
    $values['id_shop_group'] = Context::getContext()->shop->id_shop_group;
    $values['active'] = 1;

    if( Tools::getValue('id_task')){
      Db::getInstance(_PS_USE_SQL_SLAVE_)->update('ordersexport_tasks', $values, 'id_task='.(int)Tools::getValue('id_task'));
    }
    else{
      Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('ordersexport_tasks', $values);
    }

    return true;
  }

  public function initFormScheduleTasks()
  {
    $html = '';
    $helper = new HelperList();
    $helper->title = $this->l('Cron tasks');
    $helper->table = $this->name;
    $helper->no_link = true;
    $helper->shopLinkType = '';
    $helper->identifier = 'id_task';
    $helper->actions = array('edit', 'delete');

    $values = $this->_getAddedTasks();
    $helper->listTotal = count($values);
    $helper->tpl_vars = array('show_filters' => false);

    $helper->toolbar_btn['new'] = array(
      'href' => $this->context->link->getAdminLink('AdminModules', false)
        .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
        .'&module_tab=newcronjob&token='.Tools::getAdminTokenLite('AdminModules') . '',
      'desc' => $this->l('Add new task')
    );

    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
      .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&module_tab=newcronjob';
    $helper->fields_value['location_href'] = AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=ordersexport';

    $token = Configuration::getGlobalValue('GOMAKOIL_ORDERS_EXPORT_TASKS_KEY');
    $admin_folder = str_replace(_PS_ROOT_DIR_.'/', null, basename(_PS_ADMIN_DIR_));
    $id_shop = (int)Context::getContext()->shop->id;
    $id_shop_group = (int)Context::getContext()->shop->id_shop_group;

    if (version_compare(_PS_VERSION_, '1.7', '<') == true) {
      $path = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.$admin_folder.'/';
      $schedule_url = $path.Context::getContext()->link->getAdminLink('AdminOrdersExport', false);
      $schedule_url .= '&id_shop='.$id_shop.'&id_shop_group='.$id_shop_group.'&secure_key='.$token;
    } else {
      $schedule_url = Context::getContext()->link->getAdminLink('AdminOrdersExport', false);
      $schedule_url .= '&id_shop='.$id_shop.'&id_shop_group='.$id_shop_group.'&secure_key='.$token;
    }

    $scheduleTab = false;
    if( Tools::getValue('module_tab') == 'schedule_tasks' || Tools::getValue('module_tab') == 'newcronjob' ){
      $scheduleTab = true;
    }

    $this->context->smarty->assign(
      array(
        'schedule_url'  => $schedule_url,
        'schedule_tab'  => $scheduleTab
      )
    );

    $html .= $this->display(__FILE__, 'views/templates/hook/config.tpl');
//
    $html .=  $helper->generateList($values, $this->getTasksList());

    return $html;
  }

  public function initFormAddTask( $error = false )
  {
    $form = array(
      'form' => array(
        'form' => array(
          'id_form' => 'orders_add_task',
          'legend' => array(
            'title' => $this->l('Add cron task'),
            'icon' => 'icon-plus',
          ),
          'error' => '',
          'input' => array(),
          'submit' => array('title' => $this->l('Save'), 'type' => 'submit'),
        ),
      )
    );

    $form['form']['form']['input'][] = array(
      'type' => 'text',
      'name' => 'description',
      'label' => $this->l('Task description'),
      'desc' => $this->l('Enter a description for this task.'),
      'placeholder' => $this->l('My export'),
    );


    $form['form']['form']['input'][] = array(
      'type' => 'select',
      'name' => 'export_settings',
      'label' => $this->l('Export Settings'),
      'desc' => $this->l('Select saved automatically export settings'),
      'options' => array(
        'query' => $this->_getAutomaticSettings(),
        'id' => 'id', 'name' => 'name'
      ),
    );

    $form['form']['form']['input'][] = array(
      'type' => 'select',
      'name' => 'hour',
      'label' => $this->l('Task frequency'),
      'desc' => $this->l('At what time should this task be executed?'),
      'options' => array(
        'query' => $this->_getHoursFormOptions(),
        'id' => 'id', 'name' => 'name'
      ),
    );
    $form['form']['form']['input'][] = array(
      'type' => 'select',
      'name' => 'day',
      'desc' => $this->l('On which day of the month should this task be executed?'),
      'options' => array(
        'query' => $this->_getDaysFormOptions(),
        'id' => 'id', 'name' => 'name'
      ),
    );
    $form['form']['form']['input'][] = array(
      'type' => 'select',
      'name' => 'month',
      'desc' => $this->l('On what month should this task be executed?'),
      'options' => array(
        'query' => $this->_getMonthsFormOptions(),
        'id' => 'id', 'name' => 'name'
      ),
    );
    $form['form']['form']['input'][] = array(
      'type' => 'select',
      'name' => 'day_of_week',
      'desc' => $this->l('On which day of the week should this task be executed?'),
      'options' => array(
        'query' => $this->_getDaysofWeekFormOptions(),
        'id' => 'id', 'name' => 'name'
      ),
    );

    if( $error ){
      $form['form']['form']['error'] = $error;
    }


    $helper = new HelperForm();

    $helper->show_toolbar = false;
    $helper->module = $this;
    $helper->default_form_language = $this->context->language->id;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
    $helper->submit_action = 'add_task';

    $helper->identifier = $this->identifier;
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
      .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&module_tab=schedule_tasks';
    if( Tools::getValue('id_task') ){
      $helper->currentIndex .= '&id_task='.(int)Tools::getValue('id_task');
    }

    $helper->token = Tools::getAdminTokenLite('AdminModules');

    $helper->tpl_vars['fields_value'] = $this->_getScheduleValues(true);

    return $helper->generateForm($form);
  }

  private function _getAutomaticSettings()
  {
    $settings = array();
    $savedSettings = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS',null,Context::getContext()->shop->id_shop_group,Context::getContext()->shop->id));
    $settings[] = array('id' => 0, 'name' => '-');

    if(isset($savedSettings) && $savedSettings){
      foreach( $savedSettings as $key=>$set ){
        if( $set['automatic'] ){
          $settings[] = array('id' => $key, 'name' => $key);
        }
      }
    }

    return $settings;
  }

  private function _getHoursFormOptions()
  {
    $data = array(array('id' => '-1', 'name' => $this->l('Every hour')));

    for ($hour = 0; $hour < 24; $hour += 1) {
      $data[] = array('id' => $hour, 'name' => date('H:i', mktime($hour, 0, 0, 0, 1)));
    }

    return $data;
  }

  private function _getDaysFormOptions()
  {
    $data = array(array('id' => '-1', 'name' => $this->l('Every day of the month')));

    for ($day = 1; $day <= 31; $day += 1) {
      $data[] = array('id' => $day, 'name' => $day);
    }

    return $data;
  }

  private function _getMonthsFormOptions()
  {
    $data = array(array('id' => '-1', 'name' => $this->l('Every month')));

    for ($month = 1; $month <= 12; $month += 1) {
      $data[] = array('id' => $month, 'name' => $this->l(date('F', mktime(0, 0, 0, $month, 1))));
    }

    return $data;
  }

  private function _getDaysofWeekFormOptions()
  {
    $data = array(array('id' => '-1', 'name' => $this->l('Every day of the week')));

    for ($day = 1; $day <= 7; $day += 1) {
      $data[] = array('id' => $day, 'name' => $this->l(date('l', strtotime('Sunday +' . $day . ' days'))));
    }

    return $data;
  }

  private function _getScheduleValues( $formValues = false )
  {
    if( Tools::getValue('id_task') && $formValues ){
      $sql = '
      SELECT * 
      FROM ' . _DB_PREFIX_ . 'ordersexport_tasks as t
      WHERE id_task = "'.(int)Tools::getValue('id_task').'"
    ';

      $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
      return $res[0];
    }
    return array(
      'description' => Tools::safeOutput(Tools::getValue('description', null)),
      'export_settings' => Tools::safeOutput(Tools::getValue('export_settings', 0)),
      'hour' => (int)Tools::getValue('hour', -1),
      'day' => (int)Tools::getValue('day', -1),
      'month' => (int)Tools::getValue('month', -1),
      'day_of_week' => (int)Tools::getValue('day_of_week', -1),
    );
  }

  private function _getAddedTasks()
  {
    $id_shop = (int)Context::getContext()->shop->id;
    $id_shop_group = (int)Context::getContext()->shop->id_shop_group;

    $sql = '
      SELECT * 
      FROM ' . _DB_PREFIX_ . 'ordersexport_tasks as t
      WHERE id_shop = ' . $id_shop . '
      AND id_shop_group = ' . $id_shop_group . '
    ';

    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

    if( $res ){
      foreach( $res as $key => &$task ){
        $task['hour'] = ($task['hour'] == -1) ? $this->l('Every hour') : date('H:i', mktime((int)$task['hour'], 0, 0, 0, 1));
        $task['day'] = ($task['day'] == -1) ? $this->l('Every day') : (int)$task['day'];
        $task['month'] = ($task['month'] == -1) ? $this->l('Every month') : $this->l(date('F', mktime(0, 0, 0, (int)$task['month'], 1)));
        $task['day_of_week'] = ($task['day_of_week'] == -1) ? $this->l('Every day of the week') : $this->l(date('l', strtotime('Sunday +' . $task['day_of_week'] . ' days')));
        $task['last_start'] = ($task['last_start'] == 0) ? $this->l('Never') : date('Y-m-d H:i:s', ($task['last_start']));
        $task['last_finish'] = ($task['last_finish'] == 0) ? $this->l('') : date('Y-m-d H:i:s', ($task['last_finish']));
        $task['one_shot'] = (bool)$task['one_shot'];
        $task['active'] = (bool)$task['active'];
      }
    }
    return $res;
  }

  public function getTasksList()
  {
    return array(
      'description' => array('title' => $this->l('Task description'), 'type' => 'text', 'orderby' => false),
      'export_settings' => array('title' => $this->l('Export Setting'), 'type' => 'text', 'orderby' => false),
      'hour' => array('title' => $this->l('Hour'), 'type' => 'text', 'orderby' => false),
      'day' => array('title' => $this->l('Day'), 'type' => 'text', 'orderby' => false),
      'month' => array('title' => $this->l('Month'), 'type' => 'text', 'orderby' => false),
      'day_of_week' => array('title' => $this->l('Day of week'), 'type' => 'text', 'orderby' => false),
      'last_start' => array('title' => $this->l('Last export start'), 'type' => 'text', 'orderby' => false),
      'last_finish' => array('title' => $this->l('Last export finish'), 'type' => 'text', 'orderby' => false),
      'one_shot' => array('title' => $this->l('One shot'), 'active' => 'oneshot', 'type' => 'bool', 'align' => 'center'),
      'active' => array('title' => $this->l('Active'), 'active' => 'status', 'type' => 'bool', 'align' => 'center', 'orderby' => false),
    );
  }

  public function initFields($saved_setting){
    $saved_field_names = array();
    $saved_field_ids = array();
    $selected = array();

    if( $saved_setting && !empty($saved_setting['field']) && !empty($saved_setting['field_name'])){
        $all_fields_merged = array_merge($this->_exportTabOrdersData, $this->_exportTabProducts, $this->_exportTabCustomers, $this->_exportTabShippingAddress, $this->_exportTabInvoiceAddress, $this->_exportTabPayment);
        foreach($saved_setting['field'] as $saved_field_key => $field_id) {
            foreach ($all_fields_merged as $field) {
                if ($field_id == $field['id']) {
                    $selected[$field['val']] = array('name' => $saved_setting['field_name'][$saved_field_key],
                                                     'tab' => $field['tab'],
                                                     'is_extra' => false,
                                                     'default_value' => false);

                    array_push($saved_field_ids, $field['id']);
                } else if (preg_match('/^extra_field_\d+$/', $field_id)) {
                    $selected[$field_id] = array('name' => $saved_setting['field_name'][$saved_field_key],
                                                 'tab' => 'exportTabOrdersData',
                                                 'is_extra' => true,
                                                 'default_value' => $saved_setting['extra_fields'][$field_id]['value']);

                    array_push($saved_field_ids, $field_id);
                }
            }
        }
    }

    $all_fields = array(
      'exportTabOrdersData'        => $this->_exportTabOrdersData,
      'exportTabProducts'          => $this->_exportTabProducts,
      'exportTabCustomers'         => $this->_exportTabCustomers,
      'exportTabShippingAddress'   => $this->_exportTabShippingAddress,
      'exportTabInvoiceAddress'    => $this->_exportTabInvoiceAddress,
      'exportTabPayment'           => $this->_exportTabPayment,
    );
      
    $this->context->smarty->assign(
      array(
        'url_base'              => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=ordersexport',
        'saved_field_names'     => $saved_field_names,
        'saved_field_ids'       => $saved_field_ids,
        'selected'              => $selected,
        'all_fields'            => $all_fields,
        'num_of_extra_fields'   => is_array($saved_setting['extra_fields']) ? count($saved_setting['extra_fields']) : 0,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/selectFields.tpl');

  }


  public function initSavedSettings(){
    $html = '';
    $config = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS','', $this->default_shop_group_id, $this->default_shop_id));

    $active = Tools::getValue('settings');


    if( !$config ){
      $config = array();
    }
    $html .= '<div><ul>';

    $i = 1;

    foreach($config as $key=>$value){
      $class = 'not_active';
      if(isset($active) && $active && $key == $active){
        $class = 'active_setting';
      }

      $html .= '<li class="'.$class.'"> <span class="settings_key">'.$i.'.</span><a href="'.AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=ordersexport&settings='.$key.'" class="one_config">'.$key.'</a><a class="delete_config btn btn-default" settings="'.$key.'"><i class="icon-trash"></i></a></li>';
      $i++;
    }

    $html .= '</ul></div>';

    return $html;
  }


  public function pageCustomers($search){
    $customers = $this->_model->getCustomers(Context::getContext()->shop->id, $search);
    $customers_check = Tools::unserialize(Configuration::get('GOMAKOIL_CUSTOMERS_CHECKED','', $this->default_shop_group_id, $this->default_shop_id));
    $this->context->smarty->assign(
      array(
        'customers'           => $customers,
        'customers_check'      => $customers_check,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/pageCustomers.tpl');
  }

  public function upgradeExport_3_7_1()
  {

    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'exported_order';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'exported_order(
				id_exported_order int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_order  int(11) NULL,
		    settings varchar(255) NULL,
				PRIMARY KEY (`id_exported_order`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    return true;
  }

  public function upgradeExport_4_4_5()
  {
    $this->_createTab();
    return true;
  }

  public function upgradeExport_4_4_0()
  {
    $all_shops = Shop::getShops();
    $main_config = array();

    foreach ($all_shops as $shop) {
      $configs = Configuration::get('GOMAKOIL_EXPORT_ORDERS_SETTINGS', null, $shop['id_shop_group'], $shop['id_shop']);
      $configs = Tools::unSerialize($configs);

      if ($configs) {
        foreach ($configs as $config_name => $config) {
          $configs[$config_name]['shop_id'] = $shop['id_shop'];
          $configs[$config_name]['order_status_autoexport_filter'] = '';
          $configs[$config_name]['attach_file_to_mail'] = 0;
          $configs[$config_name]['order_status_changed_autoexport'] = 0;
          $configs[$config_name]['email_on_no_orders'] = 0;

          if (array_key_exists($config_name, $main_config)) {
            $new_config_name = $config_name . '_shop_' . $shop['id_shop'] . '_' . date('His');
            $configs[$new_config_name] = $configs[$config_name];

            $main_config[$new_config_name] = $configs[$config_name];
          } else {
            $main_config[$config_name] = $configs[$config_name];
          }
        }
      }
    }

    $main_config = serialize($main_config);

    if (!Configuration::deleteByName('GOMAKOIL_EXPORT_ORDERS_SETTINGS') ||
        !Configuration::deleteByName('GOMAKOIL_EXPORT_ORDERS') ||
        !Configuration::deleteByName('GOMAKOIL_CUSTOMERS_CHECKED') ||
        !Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS_SETTINGS', $main_config, null, $this->default_shop_group_id, $this->default_shop_id) ||
        !Configuration::updateValue('GOMAKOIL_EXPORT_ORDERS', null, null, $this->default_shop_group_id, $this->default_shop_id) ||
        !Configuration::updateValue('GOMAKOIL_CUSTOMERS_CHECKED', null, null, $this->default_shop_group_id, $this->default_shop_id) ||
        !$this->registerHook('ActionOrderHistoryAddAfter')
    ) {
      return false;
    }

    return true;
  }
}