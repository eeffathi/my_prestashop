<?php

class ordersExportDataModel
{
    private $_context;

    public function __construct()
    {
        include_once(dirname(__FILE__) . '/../../config/config.inc.php');
        include_once(dirname(__FILE__) . '/../../init.php');
        $this->_context = Context::getContext();
    }


    public function getOrders($idLang = false, $idShop = false, $orderIds, $export_filters)
    {
        $join_product = '';
        $and_product = 'WHERE 1 ';

        if ($idShop === false) {
            $idShop = $this->_context->shop->id;
        }
        if ($idLang === false) {
            $idLang = $this->_context->language->id;
        }

        if (!$orderIds) {
            return array();
        }

        if ($export_filters['product_supplier'] || $export_filters['product_manufacturer']) {
            $join_product .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product prod ON od2.product_id = prod.id_product ';

            if ($export_filters['product_supplier']) {
                $join_product .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product_supplier prod_supplier ON od2.product_id = prod_supplier.id_product ';
                $and_product .= ' AND prod_supplier.id_supplier IN (' . $export_filters['product_supplier'] . ')';
            }

            if ($export_filters['product_manufacturer']) {
                $and_product .= ' AND prod.id_manufacturer IN (' . $export_filters['product_manufacturer'] . ')';
            }

            $and_product .= ' GROUP BY product_id, id_order ';
        }

        $where = ' WHERE o.id_order IN(' . pSQL($orderIds) . ') ';
        $where .= $idShop == 'all' ? '' : ' AND o.id_shop = ' . (int)$idShop;


        /**
         * Remove fields from query for retro compatibility with PS version older then 1.6.1.0
         *
         *
         */
        $new_slip_fields = '';
        $new_osd2_fields = '';

        if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
            $new_slip_fields = 'slip.total_products_tax_excl as total_products_tax_excl_slip, 
                      slip.total_products_tax_incl as total_products_tax_incl_slip,
                      slip.total_shipping_tax_excl as total_shipping_tax_excl_slip, 
                      slip.total_shipping_tax_incl as total_shipping_tax_incl_slip,';

            $new_osd2_fields = '(SELECT GROUP_CONCAT(unit_price_tax_excl) as unit_price_tax_excl_slip FROM (
        SELECT osd2.unit_price_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as unit_price_tax_excl_slip, 
           
           (SELECT GROUP_CONCAT(unit_price_tax_incl) as unit_price_tax_incl_slip FROM (
              SELECT osd2.unit_price_tax_incl, od2.id_order
                      FROM ' . _DB_PREFIX_ . 'order_detail as od2
                      LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                      ' . $join_product . '
                      ' . $and_product . '
                    ) as pnm
                    WHERE id_order = o.id_order 
           ) as unit_price_tax_incl_slip,  
               
           (SELECT GROUP_CONCAT(total_price_tax_excl) as total_price_tax_excl_slip FROM (
        SELECT osd2.total_price_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as total_price_tax_excl_slip, 
           
          (SELECT GROUP_CONCAT(total_price_tax_incl) as total_price_tax_incl_slip FROM (
        SELECT osd2.total_price_tax_incl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as total_price_tax_incl_slip,';
        }

        /**
         * Customization fields query parts for different PrestaShop versions
         */
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $cust_select = '(SELECT GROUP_CONCAT(id_customization) as id_customization FROM (
                SELECT od2.id_customization, od2.id_order  
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order
           ) as id_customization,';
            $cust_join = '';
        } else {
            $customization_join_product = '';
            $customization_and_product = 'WHERE 1 ';

            if ($export_filters['product_supplier'] || $export_filters['product_manufacturer']) {
                $customization_join_product .= ' 
                LEFT JOIN ' . _DB_PREFIX_ . 'orders as o2 ON cart.id_cart = o2.id_cart
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON o2.id_order = od2.id_order 
                LEFT JOIN ' . _DB_PREFIX_ . 'product as prod ON od2.product_id = prod.id_product ';

                if ($export_filters['product_supplier']) {
                    $customization_join_product .= ' LEFT JOIN ' . _DB_PREFIX_ . 'product_supplier prod_supplier ON od2.product_id = prod_supplier.id_product ';
                    $customization_and_product .= ' AND prod_supplier.id_supplier IN (' . $export_filters['product_supplier'] . ')';
                }

                if ($export_filters['product_manufacturer']) {
                    $customization_and_product .= ' AND prod.id_manufacturer IN (' . $export_filters['product_manufacturer'] . ')';
                }

                $customization_and_product .= ' GROUP BY od2.product_id, od2.id_order ';
            }

            $cust_select = '(SELECT GROUP_CONCAT(id_customization) as id_customization FROM (
                SELECT cart.id_cart, cust.id_customization
                FROM ' . _DB_PREFIX_ . 'cart as cart
                LEFT JOIN ' . _DB_PREFIX_ . 'customization cust 
                ON cart.id_cart = cust.id_cart 
                ' . $customization_join_product . '
                ' . $customization_and_product . '
              ) as pnm
              WHERE id_cart = o.id_cart
           ) as id_customization,';

            $cust_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'customization as cust
                          ON o.id_cart = cust.id_cart';
        }

        $sql = '
        SELECT oi.note,
            oi.id_order_invoice, 
            oc.tracking_number, 
            oh.date_add as date_add_state, 
            o.*,carr.name as carrier_name, 
            (o.total_products_wt - o.total_products) as total_products_tax,
            (o.total_paid_tax_incl - o.total_paid_tax_excl) as total_paid_tax,
            c.firstname as customer_firstname, 
            c.email as customer_email,
            c.company as customer_company, 
            c.lastname as customer_lastname, 
            c.birthday as customer_birthday,
            c.note as private_note, 
            c.newsletter,
            c.is_guest as customer_is_guest,
            c.website as customer_website,
            gl.name as default_customer_group,
            st.iso_code as shipping_state_iso, 
            osl.name as status_order,
            ad.lastname as shipping_customer_lastname, 
            ad.firstname as shipping_customer_firstname,
            ad.alias as shipping_alias, 
            ad.company as shipping_company, 
            ad.id_country as shipping_id_country,
            ad.vat_number as shipping_vat_number, 
            cl.name as shipping_name_country,
            con.iso_code as shipping_iso_country, 
            ad.id_state as shipping_id_state, 
            st.name as shipping_name_state,
            ad.address1 as shipping_address1,
            ad.address2 as shipping_address2, 
            ad.postcode as shipping_postcode,
            ad.city as shipping_city, 
            ad.other as shipping_other, 
            ad.phone as shipping_phone,
            ad.phone_mobile as shipping_phone_mobile, 
            ad.dni as shipping_customer_dni,
            od.product_supplier_reference as supplier_reference, 
            od.id_warehouse as id_warehouse,
            slip.id_order_slip, 
            ' . $new_slip_fields . '
            slip.shipping_cost as shipping_cost_slip, 
            slip.amount as amount_slip, 
            slip.shipping_cost_amount as shipping_cost_amount_slip,  
            slip.date_add as date_add_slip, 
            adi.lastname as invoice_customer_lastname,
            adi.firstname as invoice_customer_firstname, 
            adi.alias as invoice_alias, 
            adi.company as invoice_company, 
            adi.id_country as invoice_id_country,
            adi.vat_number as invoice_vat_number, 
            cli.name as invoice_name_country, 
            coni.iso_code as invoice_iso_country, 
            adi.id_state as invoice_id_state, 
            sti.name as invoice_name_state, 
            sti.iso_code as invoice_state_iso,
            adi.address1 as invoice_address1, 
            adi.address2 as invoice_address2, 
            adi.postcode as invoice_postcode, 
            adi.city as invoice_city, 
            adi.other as invoice_other, 
            adi.phone as invoice_phone, 
            adi.phone_mobile as invoice_phone_mobile, 
            adi.dni as invoice_customer_dni,
            cm.message as order_customer_message,
           (SELECT  GROUP_CONCAT( gl2.name ) FROM ' . _DB_PREFIX_ . 'customer_group as cg2   LEFT JOIN ' . _DB_PREFIX_ . 'group_lang gl2 ON gl2.id_group = cg2.id_group AND gl2.id_lang = ' . (int)$idLang . '  WHERE c.id_customer = cg2.id_customer) as customer_groups,
           (SELECT  GROUP_CONCAT( ocr.name) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_name,
           (SELECT  GROUP_CONCAT( ocr.value) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_tax_incl,
           (SELECT  GROUP_CONCAT( ocr.value_tax_excl) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_tax_exc,
           (SELECT  GROUP_CONCAT( cr2.code) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr  LEFT JOIN ' . _DB_PREFIX_ . 'cart_rule cr2 ON cr2.id_cart_rule = ocr.id_cart_rule  WHERE ocr.id_order = o.id_order) as voucher_code,
           
            (SELECT GROUP_CONCAT(product_id) as product_id FROM (
                SELECT od2.product_id, od2.id_order 
                FROM ' . _DB_PREFIX_ . 'order_detail as od2  
                ' . $join_product . ' 
                ' . $and_product . '
              ) as pid 
              WHERE id_order = o.id_order
            ) as product_id,
            
            (SELECT GROUP_CONCAT(purchase_supplier_price) as supplier_price FROM (
                SELECT od2.purchase_supplier_price, od2.id_order 
                FROM ' . _DB_PREFIX_ . 'order_detail as od2  
                ' . $join_product . ' 
                ' . $and_product . '
              ) as pid 
              WHERE id_order = o.id_order
            ) as supplier_price,
            
           (SELECT GROUP_CONCAT(product_attribute_id) as product_attribute_id FROM (
                SELECT od2.product_attribute_id, od2.id_order  
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as paid
              WHERE id_order = o.id_order
           ) as product_attribute_id,
           
           (SELECT GROUP_CONCAT(product_name) as product_name FROM (
                SELECT od2.product_name, od2.id_order  
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order
           ) as product_name,
           
           (SELECT GROUP_CONCAT(name) as product_category FROM (
                SELECT catl.name, od2.id_order, catl.id_shop, catl.id_lang
                FROM ' . _DB_PREFIX_ . 'category_lang as catl
                LEFT JOIN ' . _DB_PREFIX_ . 'product p ON catl.id_category = p.id_category_default 
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON od2.product_id = p.id_product
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
              AND id_shop = o.id_shop 
              AND id_lang = ' . (int)$idLang . '
           ) as product_category,
           
           (SELECT GROUP_CONCAT(name) as product_name_clean FROM (
                SELECT pl.name, pl.id_shop, pl.id_lang, od2.id_order
                FROM ' . _DB_PREFIX_ . 'product_lang as pl
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON od2.product_id = pl.id_product
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
              AND id_shop = o.id_shop 
              AND id_lang = ' . (int)$idLang . '
           ) as product_name_clean,
           
           (SELECT GROUP_CONCAT(product_quantity) as product_quantity FROM (
                SELECT od2.product_quantity, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity,
           
           ' . $cust_select . '
           
           (SELECT GROUP_CONCAT(quantity) as product_quantity_in_stock FROM (
                SELECT sa.quantity, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'stock_available sa ON sa.id_product = od2.product_id 
                AND sa.id_product_attribute = od2.product_attribute_id
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity_in_stock,
           
           (SELECT GROUP_CONCAT(product_quantity_refunded) as product_quantity_refunded FROM (
                SELECT od2.product_quantity_refunded, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity_refunded,
           
           (SELECT GROUP_CONCAT(product_quantity_return) as product_quantity_return FROM (
                SELECT od2.product_quantity_return, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity_return,
           
           (SELECT GROUP_CONCAT(product_quantity_reinjected) as product_quantity_reinjected FROM (
                SELECT od2.product_quantity_reinjected, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity_reinjected, 
          
           (SELECT GROUP_CONCAT(unit_price_tax_excl) as unit_product_price_tax_excl FROM (
                SELECT od2.unit_price_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as unit_product_price_tax_excl, 
           
           (SELECT GROUP_CONCAT(unit_price_tax_incl) as unit_product_price_tax_incl FROM (
                SELECT od2.unit_price_tax_incl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as unit_product_price_tax_incl,
           
           (SELECT GROUP_CONCAT(total_price_tax_excl) as total_product_price_tax_excl FROM (
                SELECT od2.total_price_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as total_product_price_tax_excl,
           
           (SELECT GROUP_CONCAT(total_price_tax_incl) as total_product_price_tax_incl FROM (
                SELECT od2.total_price_tax_incl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as total_product_price_tax_incl, 
          
           (SELECT GROUP_CONCAT(product_reference) as product_reference FROM (
                SELECT od2.product_reference, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_reference, 
           
           (SELECT GROUP_CONCAT(product_weight) as product_weight FROM (
                SELECT od2.product_weight, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_weight,  
           
           (SELECT GROUP_CONCAT(product_weight) as product_weight FROM (
                SELECT od2.product_weight, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_weight,
           
           (SELECT GROUP_CONCAT(product_quantity) as product_quantity_slip FROM (
                SELECT osd2.product_quantity, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_quantity_slip,  
           
           ' . $new_osd2_fields . '
          
          (SELECT GROUP_CONCAT(amount_tax_excl) as amount_tax_excl_slip FROM (
                SELECT osd2.amount_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as amount_tax_excl_slip,
          
          (SELECT GROUP_CONCAT(amount_tax_incl) as amount_tax_incl_slip FROM (
                SELECT osd2.amount_tax_incl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail osd2 ON osd2.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as amount_tax_incl_slip,
          
          (SELECT GROUP_CONCAT(width) as product_width FROM (
                SELECT p.width, od2.id_order
                FROM ' . _DB_PREFIX_ . 'product as p 
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON od2.product_id = p.id_product 
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_width,
           
           (SELECT GROUP_CONCAT(height) as product_height FROM (
                SELECT p.height, od2.id_order
                FROM ' . _DB_PREFIX_ . 'product as p 
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON od2.product_id = p.id_product 
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_height,
          
           (SELECT GROUP_CONCAT(depth) as product_depth FROM (
                SELECT p.depth, od2.id_order
                FROM ' . _DB_PREFIX_ . 'product as p 
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od2 ON od2.product_id = p.id_product 
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_depth,
           
           (SELECT GROUP_CONCAT(product_ean13) as product_ean13 FROM (
                SELECT od2.product_ean13, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_ean13,
           
           (SELECT GROUP_CONCAT(ecotax) as product_ecotax_tax_excl FROM (
                SELECT od2.ecotax, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_ecotax_tax_excl,
           
           (SELECT GROUP_CONCAT(product_ecotax) as product_ecotax FROM (
                SELECT od2.ecotax*(1+od2.ecotax_tax_rate/100) product_ecotax, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_ecotax,
           
           (SELECT GROUP_CONCAT(product_upc) as product_upc FROM (
                SELECT od2.product_upc, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_upc,
           
    
           
           (SELECT GROUP_CONCAT(rate) as product_tax_rate FROM (
                SELECT t.rate, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail_tax odt ON odt.id_order_detail = od2.id_order_detail 
                LEFT JOIN ' . _DB_PREFIX_ . 'tax t ON t.id_tax = odt.id_tax 
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_tax_rate,
           
           (SELECT GROUP_CONCAT(unit_amount) as product_unit_tax_amount FROM (
                SELECT odt.unit_amount, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail_tax odt ON odt.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_unit_tax_amount,
           
           (SELECT GROUP_CONCAT(total_amount) as product_total_tax_amount FROM (
                SELECT odt.total_amount, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                LEFT JOIN ' . _DB_PREFIX_ . 'order_detail_tax odt ON odt.id_order_detail = od2.id_order_detail
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_total_tax_amount,
           
           (SELECT GROUP_CONCAT(reduction_percent) as product_reduction_percent FROM (
                SELECT od2.reduction_percent, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_reduction_percent,
           
           (SELECT GROUP_CONCAT(reduction_amount_tax_incl) as product_reduction_amount_tax_incl FROM (
                SELECT od2.reduction_amount_tax_incl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_reduction_amount_tax_incl,
           
           (SELECT GROUP_CONCAT(reduction_amount_tax_excl) as product_reduction_amount_tax_excl FROM (
                SELECT od2.reduction_amount_tax_excl, od2.id_order
                FROM ' . _DB_PREFIX_ . 'order_detail as od2
                ' . $join_product . '
                ' . $and_product . '
              ) as pnm
              WHERE id_order = o.id_order 
           ) as product_reduction_amount_tax_excl,
           
           
           
           (SELECT  GROUP_CONCAT( op.amount ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_amount,
           (SELECT  GROUP_CONCAT( op.payment_method ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_method,
           (SELECT  GROUP_CONCAT( op.transaction_id ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_transaction_id,
           (SELECT  GROUP_CONCAT( op.date_add ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_date,
           (SELECT  GROUP_CONCAT( opc.iso_code) FROM ' . _DB_PREFIX_ . 'order_payment as op LEFT JOIN ' . _DB_PREFIX_ . 'currency as opc ON op.id_currency = opc.id_currency WHERE op.order_reference = o.reference) as payment_currency,
            IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = o.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) as new_client
        FROM ' . _DB_PREFIX_ . 'orders as o
        LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od
        ON o.id_order = od.id_order
        ' . $cust_join . '
        LEFT JOIN ' . _DB_PREFIX_ . 'order_state_lang as osl
        ON o.current_state = osl.id_order_state AND osl.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'order_history as oh
        ON o.id_order = oh.id_order AND o.current_state =  oh.id_order_state
        LEFT JOIN ' . _DB_PREFIX_ . 'order_carrier as oc
        ON o.id_order = oc.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'order_invoice as oi
        ON o.id_order = oi.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'order_slip as slip
        ON o.id_order = slip.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'customer as c
        ON o.id_customer= c.id_customer
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_group as cg
        ON cg.id_customer= o.id_customer
        LEFT JOIN ' . _DB_PREFIX_ . 'group_lang gl
        ON gl.id_group = c.id_default_group AND gl.id_lang = ' . (int)$idLang . '  
        LEFT JOIN ' . _DB_PREFIX_ . 'address as ad
        ON o.id_address_delivery= ad.id_address
        LEFT JOIN ' . _DB_PREFIX_ . 'country_lang as cl
        ON ad.id_country= cl.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'country as con
        ON ad.id_country= con.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'state as st
        ON ad.id_state= st.id_state
        LEFT JOIN ' . _DB_PREFIX_ . 'address as adi
        ON o.id_address_invoice = adi.id_address
        LEFT JOIN ' . _DB_PREFIX_ . 'country_lang as cli
        ON adi.id_country= cli.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'country as coni
        ON adi.id_country= coni.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'state as sti
        ON adi.id_state= sti.id_state
        LEFT JOIN ' . _DB_PREFIX_ . 'carrier as carr
        ON o.id_carrier= carr.id_carrier
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_thread as ct
        ON o.id_order = ct.id_order AND ct.id_shop = o.id_shop AND ct.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_message as cm
        ON cm.id_customer_thread = ct.id_customer_thread
        ' . $where . '
        GROUP BY o.id_order
        ORDER BY FIELD(o.id_order, ' . pSQL($orderIds) . ')
			';

        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('SET SESSION group_concat_max_len = 100000');
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $fields = Tools::unserialize(Configuration::get('GOMAKOIL_EXPORT_ORDERS', '', Module::getInstanceByName('ordersexport')->default_shop_group_id, Module::getInstanceByName('ordersexport')->default_shop_id));
        $fields = $fields['field'];
        if (in_array('supplier_name', $fields)) {
            foreach ($res as $key => $order) {
                $products = explode(',', $order['product_id']);
                $supplier = '';
                foreach ($products as $product) {
                    $sql = '
            SELECT  sup.name
            FROM ' . _DB_PREFIX_ . 'product AS p2
            LEFT JOIN ' . _DB_PREFIX_ . 'supplier AS sup
            ON sup.id_supplier = p2.id_supplier
            WHERE p2.id_product = ' . (int)$product . '
          ';

                    $pRes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if ($pRes) {
                        $supplier .= $pRes[0]['name'] . ', ';

                    } else {
                        $supplier .= ', ';
                    }
                }
                $supplier = rtrim($supplier, ' ');
                $supplier = rtrim($supplier, ',');
                $res[$key]['supplier_name'] = $supplier;
            }
        }

        if (in_array('manufacturer_name', $fields)) {
            foreach ($res as $key => $order) {
                $products = explode(',', $order['product_id']);
                $manufacturer = '';
                foreach ($products as $product) {
                    $sql = '
            SELECT  man.name
            FROM ' . _DB_PREFIX_ . 'product AS p2
            LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer AS man
            ON man.id_manufacturer = p2.id_manufacturer
            WHERE p2.id_product = ' . (int)$product . '
          ';

                    $pRes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
                    if ($pRes) {
                        $manufacturer .= $pRes[0]['name'] . ', ';

                    } else {
                        $manufacturer .= ', ';
                    }
                }
                $manufacturer = rtrim($manufacturer, ' ');
                $manufacturer = rtrim($manufacturer, ',');
                $res[$key]['manufacturer_name'] = $manufacturer;
            }
        }

        return $res;
    }


    public function getOrdersSeparate($idLang = false, $idShop = false, $orderIds, $export_filters)
    {
        if ($idShop === false) {
            $idShop = $this->_context->shop->id;
        }

        if ($idLang === false) {
            $idLang = $this->_context->language->id;
        }

        if (!$orderIds) {
            return array();
        }

        $where = ' WHERE o.id_order IN(' . pSQL($orderIds) . ') ';
        $where .= $idShop == 'all' ? '' : ' AND o.id_shop = ' . (int)$idShop;

        if ($export_filters['product_manufacturer']) {
            $where .= ' AND p.id_manufacturer IN (' . $export_filters['product_manufacturer'] . ') ';
        }

        if ($export_filters['product_supplier']) {
            $where .= ' AND ps.id_supplier IN (' . $export_filters['product_supplier'] . ') ';
        }

        /**
         * Remove fields from query for retro compatibility with PS version older then 1.6.1.0
         *
         *
         */
        $new_slip_fields = '';
        $new_osd2_fields = '';

        if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
            $new_slip_fields = 'slip.total_products_tax_excl as total_products_tax_excl_slip, 
                          slip.total_products_tax_incl as total_products_tax_incl_slip,
                          slip.total_shipping_tax_excl as total_shipping_tax_excl_slip, 
                          slip.total_shipping_tax_incl as total_shipping_tax_incl_slip,';

            $new_osd2_fields = 'osd.unit_price_tax_excl as unit_price_tax_excl_slip,
                          osd.unit_price_tax_incl as unit_price_tax_incl_slip,
                          osd.total_price_tax_excl as total_price_tax_excl_slip,
                          osd.total_price_tax_incl as total_price_tax_incl_slip,';
        }

        /**
         * Customization fields query parts for different PrestaShop versions
         */
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $cust_select = 'od.id_customization as id_customization,';
            $cust_join = '';
            $cust_groupby = '';
        } else {
            $cust_select = 'cust.id_customization as id_customization,';
            $cust_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'customization as cust
                        ON o.id_cart = cust.id_cart AND od.product_id = cust.id_product';

            $cust_groupby = ',cust.id_customization';
        }

        $sql = '
        SELECT oi.note,
             oi.id_order_invoice, 
             oc.tracking_number ,
             oh.date_add as date_add_state, 
             o.*,carr.name as carrier_name, 
             (o.total_products_wt - o.total_products) as total_products_tax,
             (o.total_paid_tax_incl - o.total_paid_tax_excl) as total_paid_tax,
             c.newsletter, 
             c.firstname as customer_firstname, 
             c.email as customer_email, 
             c.company as customer_company, 
             c.lastname as customer_lastname, 
             c.birthday as customer_birthday, 
             c.note as private_note, 
             c.is_guest as customer_is_guest, 
             c.website as customer_website,
             gl.name as default_customer_group,
             st.iso_code as shipping_state_iso, 
             osl.name as status_order,
             ad.lastname as shipping_customer_lastname, 
             ad.firstname as shipping_customer_firstname, 
             ad.alias as shipping_alias, 
             ad.company as shipping_company, 
             ad.id_country as shipping_id_country, 
             ad.vat_number as shipping_vat_number, 
             cl.name as shipping_name_country, 
             con.iso_code as shipping_iso_country, 
             ad.id_state as shipping_id_state, 
             st.name as shipping_name_state,
             ad.address1 as shipping_address1, 
             ad.address2 as shipping_address2, 
             ad.postcode as shipping_postcode, 
             ad.city as shipping_city, 
             ad.other as shipping_other, 
             ad.phone as shipping_phone, 
             ad.phone_mobile as shipping_phone_mobile, 
             ad.dni as shipping_customer_dni, 
             od.product_supplier_reference as supplier_reference, 
             od.purchase_supplier_price as supplier_price,
             adi.lastname as invoice_customer_lastname, 
             adi.firstname as invoice_customer_firstname, 
             adi.alias as invoice_alias, 
             adi.company as invoice_company, 
             adi.id_country as invoice_id_country, 
             adi.vat_number as invoice_vat_number, 
             cli.name as invoice_name_country, 
             coni.iso_code as invoice_iso_country, 
             adi.id_state as invoice_id_state, 
             sti.name as invoice_name_state,
             sti.iso_code as invoice_state_iso,
             adi.address1 as invoice_address1, 
             adi.address2 as invoice_address2, 
             adi.postcode as invoice_postcode, 
             adi.city as invoice_city, 
             adi.other as invoice_other, 
             adi.phone as invoice_phone, 
             adi.phone_mobile as invoice_phone_mobile, 
             adi.dni as invoice_customer_dni,
             od.product_id as product_id ,
             od.product_attribute_id as product_attribute_id ,
             od.product_name as product_name,
             pl.name as product_name_clean,
             catl.name as product_category,
             od.product_quantity as product_quantity,
             sa.quantity as product_quantity_in_stock,
             od.id_warehouse as id_warehouse,
             od.product_quantity_refunded as product_quantity_refunded,
             od.product_quantity_return as product_quantity_return,
             od.product_quantity_reinjected as product_quantity_reinjected,
             od.unit_price_tax_excl as unit_product_price_tax_excl,
             od.unit_price_tax_incl as unit_product_price_tax_incl,
             od.total_price_tax_excl as total_product_price_tax_excl,
             od.total_price_tax_incl as total_product_price_tax_incl,
             od.product_reference as product_reference,
             od.product_weight as product_weight,
             ' . $cust_select . '
             slip.id_order_slip,
             ' . $new_slip_fields . '
             slip.shipping_cost as shipping_cost_slip, 
             slip.amount as amount_slip, 
             slip.shipping_cost_amount as shipping_cost_amount_slip,  
             slip.date_add as date_add_slip, 
             p.width as product_width,
             p.height as product_height,
             p.depth as product_depth,
             osd.product_quantity as product_quantity_slip,
             ' . $new_osd2_fields . '
             osd.amount_tax_excl as amount_tax_excl_slip,
             osd.amount_tax_incl as amount_tax_incl_slip,
             od.product_ean13 as product_ean13,
             od.ecotax as product_ecotax_tax_excl,
             (od.ecotax*(1+od.ecotax_tax_rate/100)) as product_ecotax,
             od.product_upc as product_upc,
             t.rate as product_tax_rate,
             odt.unit_amount as product_unit_tax_amount,
             odt.total_amount as product_total_tax_amount,
             sup.name as supplier_name,
             man.name as manufacturer_name,
             od.reduction_percent as product_reduction_percent,
             od.reduction_amount_tax_incl as product_reduction_amount_tax_incl,
             od.reduction_amount_tax_excl as product_reduction_amount_tax_excl,
             cm.message as order_customer_message,   
            (SELECT  GROUP_CONCAT( gl2.name ) FROM ' . _DB_PREFIX_ . 'customer_group as cg2   LEFT JOIN ' . _DB_PREFIX_ . 'group_lang gl2 ON gl2.id_group = cg2.id_group AND gl2.id_lang = ' . (int)$idLang . '  WHERE c.id_customer = cg2.id_customer) as customer_groups,
            (SELECT  GROUP_CONCAT( op.amount ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_amount,
            (SELECT  GROUP_CONCAT( op.payment_method ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_method,
            (SELECT  GROUP_CONCAT( op.transaction_id ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_transaction_id,
            (SELECT  GROUP_CONCAT( op.date_add ) FROM ' . _DB_PREFIX_ . 'order_payment as op WHERE op.order_reference = o.reference) as payment_date,
            (SELECT  GROUP_CONCAT( opc.iso_code) FROM ' . _DB_PREFIX_ . 'order_payment as op LEFT JOIN ' . _DB_PREFIX_ . 'currency as opc ON op.id_currency = opc.id_currency WHERE op.order_reference = o.reference) as payment_currency,
            (SELECT  GROUP_CONCAT( ocr.name) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_name,
            (SELECT  GROUP_CONCAT( ocr.value) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_tax_incl,
            (SELECT  GROUP_CONCAT( ocr.value_tax_excl) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr WHERE ocr.id_order = o.id_order) as voucher_tax_exc,
            (SELECT  GROUP_CONCAT( cr2.code) FROM ' . _DB_PREFIX_ . 'order_cart_rule as ocr  LEFT JOIN ' . _DB_PREFIX_ . 'cart_rule cr2 ON cr2.id_cart_rule = ocr.id_cart_rule  WHERE ocr.id_order = o.id_order) as voucher_code,
            IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = o.id_customer AND so.id_order < o.id_order LIMIT 1) > 0, 0, 1) as new_client
        FROM ' . _DB_PREFIX_ . 'orders as o
        LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od
        ON o.id_order = od.id_order
        ' . $cust_join . '
        LEFT JOIN ' . _DB_PREFIX_ . 'order_slip_detail as osd
        ON od.id_order_detail = osd.id_order_detail
        LEFT JOIN ' . _DB_PREFIX_ . 'order_detail_tax as odt
        ON od.id_order_detail = odt.id_order_detail
        LEFT JOIN ' . _DB_PREFIX_ . 'tax as t
        ON t.id_tax = odt.id_tax
        LEFT JOIN ' . _DB_PREFIX_ . 'order_carrier as oc
        ON o.id_order = oc.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'order_invoice as oi
        ON o.id_order = oi.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'order_slip as slip
        ON o.id_order = slip.id_order 
        LEFT JOIN ' . _DB_PREFIX_ . 'product as p
        ON p.id_product = od.product_id
        LEFT JOIN ' . _DB_PREFIX_ . 'product_supplier as ps
        ON ps.id_product = od.product_id
        LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl
        ON pl.id_product = od.product_id AND pl.id_shop = o.id_shop AND pl.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'category_lang as catl
        ON p.id_category_default = catl.id_category AND catl.id_shop = o.id_shop AND catl.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'stock_available as sa
        ON sa.id_product = od.product_id AND sa.id_product_attribute = od.product_attribute_id
        LEFT JOIN ' . _DB_PREFIX_ . 'supplier as sup
        ON p.id_supplier = sup.id_supplier
        LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer as man
        ON p.id_manufacturer = man.id_manufacturer
        LEFT JOIN ' . _DB_PREFIX_ . 'order_state_lang as osl
        ON o.current_state = osl.id_order_state AND osl.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'order_history as oh
        ON o.id_order = oh.id_order AND o.current_state =  oh.id_order_state
        LEFT JOIN ' . _DB_PREFIX_ . 'customer as c
        ON o.id_customer= c.id_customer
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_group as cg
        ON cg.id_customer= o.id_customer
        LEFT JOIN ' . _DB_PREFIX_ . 'group_lang gl
        ON gl.id_group = c.id_default_group AND gl.id_lang = ' . (int)$idLang . '  
        LEFT JOIN ' . _DB_PREFIX_ . 'address as ad
        ON o.id_address_delivery= ad.id_address
        LEFT JOIN ' . _DB_PREFIX_ . 'country_lang as cl
        ON ad.id_country= cl.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'country as con
        ON ad.id_country= con.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'state as st
        ON ad.id_state= st.id_state
        LEFT JOIN ' . _DB_PREFIX_ . 'address as adi
        ON o.id_address_invoice = adi.id_address
        LEFT JOIN ' . _DB_PREFIX_ . 'country_lang as cli
        ON adi.id_country= cli.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'country as coni
        ON adi.id_country= coni.id_country
        LEFT JOIN ' . _DB_PREFIX_ . 'state as sti
        ON adi.id_state= sti.id_state
        LEFT JOIN ' . _DB_PREFIX_ . 'carrier as carr
        ON o.id_carrier= carr.id_carrier
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_thread as ct
        ON o.id_order = ct.id_order AND ct.id_shop = o.id_shop AND ct.id_lang = ' . (int)$idLang . '
        LEFT JOIN ' . _DB_PREFIX_ . 'customer_message as cm
        ON cm.id_customer_thread = ct.id_customer_thread
        ' . $where . '
        GROUP BY od.id_order_detail' . $cust_groupby . '
        ORDER BY FIELD(o.id_order, ' . pSQL($orderIds) . ')
			';

        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('SET SESSION group_concat_max_len = 100000');
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getOrdersIds($idShop = false, $limit, $count = false, $not_exported, $setting, $limitN = 2, $orderby = false, $orderway = false, $separateAttribute = false, $id_order = null, $export_filters)
    {
        $ids = '';

        $customer = $export_filters['customer'];
        $group_list = $export_filters['customer_group'];
        $status = $export_filters['status'];
        $data = $export_filters['date'];
        $carrier = $export_filters['carrier'];
        $payment = $export_filters['payment_method'];
        $isset_invoice = $export_filters['isset_invoice'];
        $supplier = $export_filters['product_supplier'];
        $manufacturer = $export_filters['product_manufacturer'];

        if (!$limit) {
            $limit = " LIMIT 0," . (int)$limitN . " ";
        } else {
            $limit = " LIMIT " . ((int)$limit * (int)$limitN) . "," . (int)$limitN . " ";
        }

        if ($idShop === false) {
            $idShop = $this->_context->shop->id;
        }

        $where_id_shop = $idShop == 'all' ? 'WHERE o.id_shop IN (' . implode(',', Shop::getShops(false, null, true)) . ')' : 'WHERE o.id_shop = ' . (int)$idShop;

        $where = '';

        if ($id_order != null) {
            $where .= ' AND o.id_order = ' . (int)$id_order;
        }

        if ($group_list) {
            $where .= " AND cg.id_group IN (" . pSQL($group_list) . ")  ";
        }

        if ($customer) {
            $where .= " AND o.id_customer IN (" . pSQL($customer) . ")  ";
        }

        if ($status) {
            $where .= " AND o.current_state IN (" . pSQL($status) . ") ";
        }
        if ($carrier) {
            $where .= " AND carr.id_reference IN (" . pSQL($carrier) . ") ";
        }
        if ($payment) {
            $where .= " AND o.payment IN ('$payment') ";
        }

        if ($data['date_period'] != 'no') {
            switch ($data['date_type']) {
                case 'payment_date':
                    $data['date_type'] = 'op.date_add';
                    break;
                case 'date_add_state':
                    $data['date_type'] = 'oh.date_add';
                    break;
                case 'date_add_slip':
                    $data['date_type'] = 'slip.date_add';
                    break;
                default:
                    $data['date_type'] = 'o.' . $data['date_type'];
            }
        }

        if ($data['date_period'] == 'period') {
            if ($data['time_from'] && !$data['time_to']) {
                $where .= " AND " . $data['date_type'] . " >= '" . pSQL($data['time_from']) . "' ";
            }
            if (!$data['time_from'] && $data['time_to']) {
                $where .= " AND " . $data['date_type'] . " <= '" . pSQL($data['time_to']) . "' ";
            }
            if ($data['time_from'] && $data['time_to']) {
                $where .= " AND " . $data['date_type'] . "  BETWEEN '" . pSQL($data['time_from']) . "' AND DATE_ADD('" . pSQL($data['time_to']) . "',INTERVAL 1 DAY) ";
            }
        }
        if ($data['date_period'] == 'today') {
            $where .= " AND " . $data['date_type'] . "  BETWEEN '" . pSQL(date('Y-m-d', time())) . "' AND DATE_ADD('" . pSQL(date('Y-m-d', time())) . "',INTERVAL 1 DAY) ";
        }

        if ($data['date_period'] == 'yesterday') {
            $where .= " AND " . $data['date_type'] . "  BETWEEN '" . pSQL(date('Y-m-d', strtotime("-1 days"))) . "' AND DATE_ADD('" . pSQL(date('Y-m-d', strtotime("-1 days"))) . "',INTERVAL 1 DAY) ";
        }

        if ($data['date_period'] == 'this_week') {
            $where .= " AND YEARWEEK(" . $data['date_type'] . ", 1) = YEARWEEK(CURDATE(), 1) ";
        }

        if ($data['date_period'] == 'last_week') {
            $where .= " AND " . $data['date_type'] . " BETWEEN CURDATE()-INTERVAL 1 WEEK AND CURDATE() ";
        }

        if ($data['date_period'] == 'this_month') {
            $where .= " AND YEAR(" . $data['date_type'] . ") = YEAR(NOW())
                   AND MONTH(" . $data['date_type'] . ") = MONTH(NOW()) ";
        }

        if ($data['date_period'] == 'last_month') {
            $where .= " AND YEAR(" . $data['date_type'] . ") = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)
                   AND MONTH(" . $data['date_type'] . ") = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ";
        }

        if (isset($isset_invoice) && $isset_invoice) {

            if ($isset_invoice == 1) {
                $where .= " AND oi.id_order_invoice != 0";
            }

            if ($isset_invoice == 2) {
                $where .= " AND oi.id_order_invoice is null";
            }

        }


        $select = ' DISTINCT o.id_order ';
        $order = '';

        if ($count) {
            $select = ' count(DISTINCT o.id_order) as count ';
        } else {

            if ($orderway == 'asc') {
                $order_way = ' ASC';
            } else {
                $order_way = ' DESC';
            }

            if ($orderby == 'id_order') {
                $order = ' ORDER BY o.id_order' . $order_way;
            }
            if ($orderby == 'date_add') {
                $order = ' ORDER BY o.date_add' . $order_way;
            }
            if ($orderby == 'date_update') {
                $order = ' ORDER BY o.date_upd' . $order_way;
            }
            if ($orderby == 'quantity') {
                $order = ' ORDER BY count_all_products' . $order_way;
            }
            if ($orderby == 'price') {
                $order = ' ORDER BY o.total_paid' . $order_way;
            }

            $order .= ' , o.id_order ASC';

        }


        $exported_order = '';
        $exported = '';
        if ($not_exported && !$id_order) {
            $exported = ' AND eo.settings is null';

            $exported_order = '      LEFT JOIN ' . _DB_PREFIX_ . 'exported_order as eo
      ON eo.id_order= o.id_order  AND eo.settings= "' . pSQL($setting) . '"';
        }

        if ($separateAttribute) {
            $sql = '
      SELECT DISTINCT a.id_attribute_group
      FROM ' . _DB_PREFIX_ . 'orders AS o
      LEFT JOIN ' . _DB_PREFIX_ . 'carrier AS carr
      ON carr.id_carrier = o.id_carrier
      LEFT JOIN ' . _DB_PREFIX_ . 'order_invoice AS oi
      ON o.id_order = oi.id_order
      LEFT JOIN ' . _DB_PREFIX_ . 'order_payment AS op
      ON o.reference = op.order_reference
      LEFT JOIN ' . _DB_PREFIX_ . 'order_history AS oh
      ON o.id_order = oh.id_order AND o.current_state =  oh.id_order_state
      LEFT JOIN ' . _DB_PREFIX_ . 'order_slip AS slip
      ON o.id_order = slip.id_order
      LEFT JOIN ' . _DB_PREFIX_ . 'customer_group AS cg
      ON cg.id_customer= o.id_customer
      LEFT JOIN ' . _DB_PREFIX_ . 'order_detail od
      ON od.id_order = o.id_order
      LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute_combination AS pac
      ON od.product_attribute_id = pac.id_product_attribute
      LEFT JOIN ' . _DB_PREFIX_ . 'attribute AS a
      ON a.id_attribute = pac.id_attribute
        ' . $exported_order
                . $where_id_shop
                . $where
                . $exported;

            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            return $res;
        }

        $sql = '
      SELECT ' . $select . ', carr.id_reference,
      (SELECT  sum( od2.product_quantity)  FROM ' . _DB_PREFIX_ . 'order_detail as od2 WHERE od2.id_order = o.id_order) as count_all_products
      FROM ' . _DB_PREFIX_ . 'orders as o
      LEFT JOIN ' . _DB_PREFIX_ . 'carrier as carr
      ON carr.id_carrier = o.id_carrier
      LEFT JOIN ' . _DB_PREFIX_ . 'customer_group as cg
      ON cg.id_customer= o.id_customer
      LEFT JOIN ' . _DB_PREFIX_ . 'order_invoice as oi
      ON o.id_order = oi.id_order
      LEFT JOIN ' . _DB_PREFIX_ . 'order_payment as op
      ON o.reference = op.order_reference
      LEFT JOIN ' . _DB_PREFIX_ . 'order_history as oh
      ON o.id_order = oh.id_order AND o.current_state =  oh.id_order_state
      LEFT JOIN ' . _DB_PREFIX_ . 'order_slip as slip
      ON o.id_order = slip.id_order
        ' . $exported_order
            . $where_id_shop . '
        ' . $where . '
        ' . $exported . '
        ' . $order . '
        ' . $limit . '
    ';
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$res) {
            return false;
        }

        if ($count) {
            return $res[0]['count'];
        }


        foreach ($res as $orderId) {
            $ids .= $orderId['id_order'] . ',';
        }

        $ids = rtrim($ids, ',');

        if ($supplier) {
            $supplier_filter = explode(',', $supplier);
            $ids = implode(',', $this->filterOrdersByProductSuppliers($ids, $supplier_filter));
        }

        if ($manufacturer) {
            $manufacturer_filter = explode(',', $manufacturer);
            $ids = implode(',', $this->filterOrdersByProductManufacturer($ids, $manufacturer_filter));
        }
        return $ids;
    }

    public function getPayment($id_shop = false)
    {
        if ($id_shop === false) {
            $id_shop = $this->_context->shop->id;
        }
        $sql = '
			SELECT DISTINCT o.payment
      FROM ' . _DB_PREFIX_ . 'orders AS o
      WHERE o.id_shop = ' . (int)$id_shop . '
			';
        $rez = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        return $rez;
    }

    public function getCustomers($id_shop = false, $search = false)
    {
        /*if($id_shop === false){
          $id_shop =  $this->_context->shop->id ;
        }*/

        $where = "";
        if ($search) {
            $where = " WHERE (c.firstname LIKE '%" . pSQL($search) . "%' OR c.lastname LIKE '%" . pSQL($search) . "%' OR c.id_customer LIKE '%" . pSQL($search) . "%')";
        }
        $sql = '
			SELECT c.id_customer AS id, c.id_customer,
			(SELECT concat(c.firstname, " ", c.lastname)) AS name,
			c.firstname, c.lastname
      FROM ' . _DB_PREFIX_ . 'customer  AS c
      ' . $where . '
      LIMIT 250
			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * @param $id_order
     * @return array
     */
    private function getOrderProductsIds($id_order)
    {
        $order = new Order($id_order);
        $order_products = $order->getProducts();
        $order_products_formatted = array();

        foreach ($order_products as $order) {
            array_push($order_products_formatted, $order['product_id']);
        }

        return $order_products_formatted;
    }

    /**
     * @param $id_product
     * @return array
     */
    private function getProductSuppliersIds($id_product)
    {
        $supplier_collection = ProductSupplier::getSupplierCollection($id_product);
        $product_suppliers = $supplier_collection->getResults();

        $product_suppliers_formatted = array();

        foreach ($product_suppliers as $product_supplier) {
            array_push($product_suppliers_formatted, $product_supplier->id_supplier);
        }

        return $product_suppliers_formatted;
    }

    /**
     * Returns only orders in which products has specific suppliers
     *
     * @param $order_ids
     * @param $filter_supplier_ids
     * @return array
     */
    private function filterOrdersByProductSuppliers($order_ids, $filter_supplier_ids)
    {
        $order_ids = explode(',', $order_ids);

        $filtered_order_ids = array_filter($order_ids, function ($order_id) use ($filter_supplier_ids) {
            $order_products = $this->getOrderProductsIds($order_id);

            foreach ($order_products as $key => $product_id) {
                $product_suppliers = $this->getProductSuppliersIds($product_id);

                if (array_intersect($product_suppliers, $filter_supplier_ids)) {
                    return true;
                }
            }

            return false;
        });

        return $filtered_order_ids;
    }

    /**
     * @param $order_ids
     * @param $filter_manufacturer_ids
     * @return array
     */
    private function filterOrdersByProductManufacturer($order_ids, $filter_manufacturer_ids)
    {
        $order_ids = explode(',', $order_ids);

        $filtered_order_ids = array_filter($order_ids, function ($order_id) use ($filter_manufacturer_ids) {
            $order_products = $this->getOrderProductsIds($order_id);

            foreach ($order_products as $product_id) {
                $product = new Product($product_id);
                $id_manufacturer = $product->id_manufacturer;

                if (in_array($id_manufacturer, $filter_manufacturer_ids)) {
                    return true;
                }

            }

            return false;
        });

        return $filtered_order_ids;
    }

    public static function getAllCustomizedDatas($id_cart, $id_lang = null, $only_in_cart = true, $id_shop = null, $id_customization = null)
    {
        if (!Customization::isFeatureActive()) {
            return false;
        }

        // No need to query if there isn't any real cart!
        if (!$id_cart) {
            return false;
        }

        if ($id_customization === 0) {
            // Backward compatibility: check if there are no products in cart with specific `id_customization` before returning false
            $product_customizations = (int)Db::getInstance()->getValue('
                SELECT COUNT(`id_customization`) FROM `' . _DB_PREFIX_ . 'cart_product`
                WHERE `id_cart` = ' . (int)$id_cart .
                ' AND `id_customization` != 0');
            if ($product_customizations) {
                return false;
            }
        }

        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        if (Shop::isFeatureActive() && !$id_shop) {
            $id_shop = (int)Context::getContext()->shop->id;
        }


        if (!$result = Db::getInstance()->executeS('
			SELECT cd.`id_customization`, c.`id_address_delivery`, c.`id_product`, cfl.`id_customization_field`, c.`id_product_attribute`,
				cd.`type`, cd.`index`, cd.`value`, cfl.`name`
			FROM `' . _DB_PREFIX_ . 'customized_data` cd
			NATURAL JOIN `' . _DB_PREFIX_ . 'customization` c
			LEFT JOIN `' . _DB_PREFIX_ . 'customization_field_lang` cfl ON (cfl.id_customization_field = cd.`index` AND id_lang = ' . (int)$id_lang .
            ($id_shop ? ' AND cfl.`id_shop` = ' . (int)$id_shop : '') . ')
			WHERE c.`id_cart` = ' . (int)$id_cart .
            ($only_in_cart ? ' AND c.`in_cart` = 1' : '') .
            ((int)$id_customization ? ' AND cd.`id_customization` = ' . (int)$id_customization : '') . '
			ORDER BY `id_product`, `id_product_attribute`, `type`, `index`')
        ) {
            return false;
        }

        $customized_datas = array();

        foreach ($result as $row) {
            $customized_datas[(int)$row['id_product']][(int)$row['id_product_attribute']][(int)$row['id_address_delivery']][(int)$row['id_customization']]['datas'][(int)$row['type']][] = $row;
        }

        if (!$result = Db::getInstance()->executeS(
            'SELECT `id_product`, `id_product_attribute`, `id_customization`, `id_address_delivery`, `quantity`, `quantity_refunded`, `quantity_returned`
			FROM `' . _DB_PREFIX_ . 'customization`
			WHERE `id_cart` = ' . (int)$id_cart .
            ((int)$id_customization ? ' AND `id_customization` = ' . (int)$id_customization : '') .
            ($only_in_cart ? ' AND `in_cart` = 1' : ''))
        ) {
            return false;
        }

        foreach ($result as $row) {
            $customized_datas[(int)$row['id_product']][(int)$row['id_product_attribute']][(int)$row['id_address_delivery']][(int)$row['id_customization']]['quantity'] = (int)$row['quantity'];
            $customized_datas[(int)$row['id_product']][(int)$row['id_product_attribute']][(int)$row['id_address_delivery']][(int)$row['id_customization']]['quantity_refunded'] = (int)$row['quantity_refunded'];
            $customized_datas[(int)$row['id_product']][(int)$row['id_product_attribute']][(int)$row['id_address_delivery']][(int)$row['id_customization']]['quantity_returned'] = (int)$row['quantity_returned'];
            $customized_datas[(int)$row['id_product']][(int)$row['id_product_attribute']][(int)$row['id_address_delivery']][(int)$row['id_customization']]['id_customization'] = (int)$row['id_customization'];
        }

        return $customized_datas;
    }
}