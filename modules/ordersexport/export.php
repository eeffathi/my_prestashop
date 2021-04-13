<?php

require_once _PS_MODULE_DIR_ . 'ordersexport/MpmOEFTPManager.php';

class exportOrders
{
  private $_imageType;
  private $_orderIdCount = false;

  private $ftp_manager;

  public function __construct()
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', "0");

    if (!class_exists('PHPExcel')) {
      include_once(_PS_MODULE_DIR_ . 'ordersexport/libraries/PHPExcel_1.7.9/Classes/PHPExcel.php');
      include_once(_PS_MODULE_DIR_ . 'ordersexport/libraries/PHPExcel_1.7.9/Classes/PHPExcel/IOFactory.php');
    }

    include_once(_PS_MODULE_DIR_ . 'ordersexport/datamodel.php');
    require_once(dirname(__FILE__) . '/ordersexport.php');

    $this->_PHPExcel = new PHPExcel();
    $this->_model = new ordersExportDataModel();
    $this->ordersExport = new Ordersexport();

    $imageTypes = ImageTypeCore::getImagesTypes('products');
    foreach ($imageTypes as $type) {
      if ($type['height'] > 150) {
        $this->_imageType = $type['name'];
        break;
      }
    }
  }

  private function _getHeadFields($config, $attributeGroups)
  {
    $newFields = array();
    $newFieldNames = array();

    foreach ($config['field'] as $key => $field) {
      if ($field == 'combinations_value') {
        foreach ($attributeGroups as $group) {
          if ($group['id_attribute_group']) {
            $newFields[] = 'Attribute_' . $group['id_attribute_group'];
          }
        }
        $config['field_name'][$key] = 'combinations_value';
      } elseif ($field == 'customization_separate') {
        $this->customizationSeparateValues = Db::getInstance()->executeS("SELECT DISTINCT `name` FROM `" . _DB_PREFIX_ . "customization_field_lang` WHERE `name` != '';");

        foreach ($this->customizationSeparateValues as $cust) {
          $newFields[] = 'CUSTOMIZATION_' . $cust['name'];
        }
      } else {
        $newFields[] = $field;
      }
    }

    foreach ($config['field_name'] as $field) {

      if ($field == 'combinations_value') {
        foreach ($attributeGroups as $group) {
          if ($group['id_attribute_group']) {
            $attrName = new AttributeGroupCore($group['id_attribute_group'], Tools::getValue('id_lang'), Tools::getValue('id_shop'));
            $attrName = $attrName->name;
            $newFieldNames[] = 'Attribute ' . $attrName;
          }
        }
      } elseif ($field == 'Customization in separate columns') {
        foreach ($this->customizationSeparateValues as $cust) {
          $newFieldNames[] = 'CUSTOMIZATION_' . $cust['name'];
        }
      } else {
        $newFieldNames[] = $field;
      }
    }

    $config['field'] = $newFields;
    $config['field_name'] = $newFieldNames;

    return $config;
  }

  private function _checkAttributeValues($config)
  {
    foreach ($config['field'] as $field) {
      $newFields[] = $field;
      if ($field == 'combinations_value') {
        return true;
      }
    }

    return false;
  }

  private function _getAttributesName($combinationId, $id_lang, $attributeGroupId)
  {
    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT al.name
			FROM ' . _DB_PREFIX_ . 'product_attribute_combination pac
			JOIN ' . _DB_PREFIX_ . 'attribute_lang al ON (pac.id_attribute = al.id_attribute AND al.id_lang=' . (int)$id_lang . ')
			LEFT JOIN ' . _DB_PREFIX_ . 'attribute a ON (a.id_attribute = al.id_attribute)
			WHERE pac.id_product_attribute=' . (int)$combinationId . '
			AND a.id_attribute_group = ' . (int)$attributeGroupId . '
    ');

    if ($res) {
      return $res[0]['name'];
    }

    return '';
  }


  public function export($config, $id_shop = null, $id_lang = null, $limit = false, $id_order = null)
  {
    $this->ftp_manager = new MpmOEFTPManager($config);

    $limitN = 1000;
    $ordersexport = new Ordersexport();

    if ($id_shop == null) {
      $id_shop = Context::getContext()->shop->id;
    }

    if ($id_lang == null) {
      $id_lang = Context::getContext()->language->id;
    }

    $alfavit = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
      'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
      'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
      'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
      'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
      'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ',
      'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
      'GA', 'GB', 'GC', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GJ', 'GK', 'GL', 'GM', 'GN', 'GO', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GV', 'GW', 'GX', 'GY', 'GZ',
      'HA', 'HB', 'HC', 'HD', 'HE', 'HF', 'HG', 'HH', 'HI', 'HJ', 'HK', 'HL', 'HM', 'HN', 'HO', 'HP', 'HQ', 'HR', 'HS', 'HT', 'HU', 'HV', 'HW', 'HX', 'HY', 'HZ',
      'IA', 'IB', 'IC', 'ID', 'IE', 'IF', 'IG', 'IH', 'II', 'IJ', 'IK', 'IL', 'IM', 'IN', 'IO', 'IP', 'IQ', 'IR', 'IS', 'IT', 'IU', 'IV', 'IW', 'IX', 'IY', 'IZ',
      'JA', 'JB', 'JC', 'JD', 'JE', 'JF', 'JG', 'JH', 'JI', 'JJ', 'JK', 'JL', 'JM', 'JN', 'JO', 'JP', 'JQ', 'JR', 'JS', 'JT', 'JU', 'JV', 'JW', 'JX', 'JY', 'JZ',
      'KA', 'KB', 'KC', 'KD', 'KE', 'KF', 'KG', 'KH', 'KI', 'KJ', 'KK', 'KL', 'KM', 'KN', 'KO', 'KP', 'KQ', 'KR', 'KS', 'KT', 'KU', 'KV', 'KW', 'KX', 'KY', 'KZ',
      'LA', 'LB', 'LC', 'LD', 'LE', 'LF', 'LG', 'LH', 'LI', 'LJ', 'LK', 'LL', 'LM', 'LN', 'LO', 'LP', 'LQ', 'LR', 'LS', 'LT', 'LU', 'LV', 'LW', 'LX', 'LY', 'LZ',
      'MA', 'MB', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MI', 'MJ', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ',
    );

    $delimiter = $config['delimiter_val'];
    $seperatop = $config['seperatop_val'];
    $display_headers = $config['display_headers'];
    $isset_invoice = $config['isset_invoice'];

    $separator_decimal_points = $config['separator_decimal_points'];


    $date_format = $config['date_format'];
    $round_value = $config['round_value'];
    $orderby = $config['sort'];
    $orderway = $config['orderway'];
    $automatic = $config['automatic'];

    if ($config['automatic']) {
      $not_exported = $config['not_exported'];
      $name_settings = $config['name_settings'];
    } else {
      $not_exported = false;
      $name_settings = false;
    }

    if ($delimiter == 'space') {
      $delimiter = ' ';
    }
    if ($delimiter == 'tab') {
      $delimiter = "\t";
    }
    if ($seperatop == 3) {
      $sep = '';
    } elseif ($seperatop == 2) {
      $sep = "'";
    } else {
      $sep = '"';
    }

    if ($limit) {
      if ($config['format'] == 'xlsx' || $config['format'] == 'xls') {
        $this->_PHPExcel = PHPExcel_IOFactory::load(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$limit - 1) . '.' . $config['format']);
      }

      if ($config['format'] == 'csv') {
        $reader = PHPExcel_IOFactory::createReader("CSV");
        $reader->setDelimiter($delimiter);
        $reader->setEnclosure($sep);
        $this->_PHPExcel = $reader->load(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$limit - 1) . '.' . $config['format']);
      }
    }

    @$statusId = implode(',', $config['status_list']);
    $customerId = Tools::unserialize(Configuration::get('GOMAKOIL_CUSTOMERS_CHECKED', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id));
    $group_list = $config['group_list'];
    @$payment_list = implode("','", $config['payment_list']);
    @$carrier_list = implode(',', $config['carrier_list']);
    @$supplier_list = implode(',', $config['supplier_list']);
    @$manufacturer_list = implode(',', $config['manufacturer_list']);
    @$customerId = implode(',', $customerId);

    @$group_list = implode(',', $group_list);

    $export_filters = array(
      'date' => $config['data'],
      'isset_invoice' => $isset_invoice,
      'status' => $statusId,
      'customer' => $customerId,
      'customer_group' => $group_list,
      'payment_method' => $payment_list,
      'carrier' => $carrier_list,
      'product_supplier' => $supplier_list,
      'product_manufacturer' => $manufacturer_list,
    );


    //if ($this->_checkAttributeValues($config)) {
      $attributeGroups = $this->_model->getOrdersIds($id_shop, $limit, false, $not_exported, $name_settings, $limitN, $orderby, $orderway, true, $id_order, $export_filters);
      $config = $this->_getHeadFields($config, $attributeGroups);
    //}


    /**
     * Remove empty customization field columns if there is any.
     */
    if ($this->isActiveExportCustomizationInSeparateCols($config['field'])) {
      $ordersIdsWithoutLimit = $this->_model->getOrdersIds($id_shop, $limit, false, $not_exported, $name_settings, $limitN, $orderby, $orderway, false, $id_order, $export_filters);
      $used_customization_fields = $this->getAllUsedCustomizationFields(explode(',', $ordersIdsWithoutLimit), $id_lang);
      $config['field'] = $this->removeUnusedCustomizationFields($config['field'], $used_customization_fields);
      $config['field_name'] = $this->removeUnusedCustomizationFields($config['field_name'], $used_customization_fields);
    }

    $j = count($config['field']);

    $this->_PHPExcel->getProperties()->setCreator("PHP")
      ->setLastModifiedBy("Admin")
      ->setTitle("Office 2007 XLSX")
      ->setSubject("Office 2007 XLSX")
      ->setDescription(" Office 2007 XLSX, PHPExcel.")
      ->setKeywords("office 2007 openxml php")
      ->setCategory("File");
    $this->_PHPExcel->getActiveSheet()->setTitle('Export');
    $activeSheet = $this->_PHPExcel->getActiveSheet();
    if ($display_headers) {
      for ($col = 0; $col < $j; ++$col) {
        $this->_PHPExcel->setActiveSheetIndex(0)
          ->setCellValue($alfavit[$col] . '1', $config['field_name'][$col]);
        $activeSheet->getColumnDimension($alfavit[$col])->setWidth(30);
      }

      $activeSheet = $this->_PHPExcel->getActiveSheet();
      $activeSheet->getRowDimension('1')->setRowHeight(25);

    }

    $orderIds = $this->_model->getOrdersIds($id_shop, $limit, false, $not_exported, $name_settings, $limitN, $orderby, $orderway, false, $id_order, $export_filters);

    if (!$limit) {
      $ordersCount = $this->_model->getOrdersIds($id_shop, $limit, true, $not_exported, $name_settings, 1, false, false, false, $id_order, $export_filters);
      Configuration::updateValue('EXPORT_ORDERS_COUNT', $ordersCount, false, $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
      Configuration::updateValue('EXPORT_ORDERS_CURRENT_COUNT', 0, false, $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
    } else {
      $ordersCount = Configuration::get('EXPORT_ORDERS_COUNT', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
    }

    if ($config['separate']) {
      $orders = $this->_model->getOrdersSeparate($id_lang, $id_shop, $orderIds, $export_filters);
    } else {
      $orders = $this->_model->getOrders($id_lang, $id_shop, $orderIds, $export_filters);
    }

    if (!$orders) {
      throw new Exception($ordersexport->l('No of matching orders', 'export'), 777);
    }

    if ($display_headers) {
      $i = 2;
    } else {
      $i = 1;
    }

    if ($limit) {

      foreach ($this->_PHPExcel->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        break;
      }
      $i = $highestRow + 1;
    }

    $max_num_of_products = 0;

    foreach ($orders as $order) {
      for ($col = 0; $col < $j; ++$col) {
        $fields = $config['field'][$col];
        if ($fields == 'link_rewrite') {
          $prod_id = explode(',', $order['product_id']);
          $attr_id = explode(',', $order['product_attribute_id']);
          $url = '';
          foreach ($prod_id as $key => $pro_id) {
            $link = new LinkCore();
            $prod = new Product($pro_id);

            $url .= $link->getProductLink(
                $prod,
                $prod->link_rewrite[$id_lang],
                Category::getLinkRewrite($prod->id_category_default, $id_lang),
                $prod->ean13,
                $id_lang,
                $order['id_shop'],
                $attr_id[$key],
                false,
                false,
                true
            );

            $url .= ', ';
          }

          $url = rtrim($url, ', ');

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $url);
        } else if ($fields == 'date_add_state' || $fields == 'invoice_date' || $fields == 'delivery_date' || $fields == 'date_add' || $fields == 'date_upd' || $fields == 'payment_date' || $fields == 'date_add_slip') {
          $val = $order[$fields];

          if ($val == '0000-00-00 00:00:00' || !$val) {
            $res = '';
          } else {
            $res = date($date_format, strtotime($val));
          }
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $res);
        } else if (strpos($fields, 'extra_field_') !== false) {
          $extra_field_value = '';
          if (isset($config['extra_fields'][$fields]['value'])) {
            $extra_field_value = $config['extra_fields'][$fields]['value'];
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $extra_field_value);
        } else if ($fields == 'shipping_address') {
          $address = $order['shipping_address1'];
          if(isset($order['shipping_address2']) && $order['shipping_address2']){
              $address .=  ', '.$order['shipping_address2'];
          }
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $address, PHPExcel_Cell_DataType::TYPE_STRING);

        } else if ($fields == 'full_shipping_address') {
          $delivery = new Address((int)$order['id_address_delivery']);
          $address = $this->_getFormatedAddress($delivery, " ");
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $address, PHPExcel_Cell_DataType::TYPE_STRING);
        } else if ($fields == 'full_invoice_address') {
          $invoice = new Address((int)$order['id_address_invoice']);
          $address = $this->_getFormatedAddress($invoice, " ");
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $address, PHPExcel_Cell_DataType::TYPE_STRING);

        } else if ($fields == 'delivery_number') {
          $prefix = Configuration::get('PS_DELIVERY_PREFIX', Context::getContext()->language->id);
          if ($order['delivery_number']) {
            $deliveryNumber = sprintf('%1$s%2$06d', $prefix, $order['delivery_number']);
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $deliveryNumber, PHPExcel_Cell_DataType::TYPE_STRING);
          } else {
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, '', PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } else if ($fields == 'invoice_number') {
          $prefix = Configuration::get('PS_INVOICE_PREFIX', Context::getContext()->language->id);
          if ($order['invoice_number']) {
            $deliveryNumber = sprintf('%1$s%2$06d', $prefix, $order['invoice_number']);
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $deliveryNumber, PHPExcel_Cell_DataType::TYPE_STRING);
          } else {
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, '', PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } else if ($fields == 'credit_number') {
          $prefix = Configuration::get('PS_CREDIT_SLIP_PREFIX', Context::getContext()->language->id);
          if ($order['id_order_slip']) {
            $deliveryNumber = sprintf('%1$s%2$06d', $prefix, $order['id_order_slip']);
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $deliveryNumber, PHPExcel_Cell_DataType::TYPE_STRING);
          } else {
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, '', PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } else if ($fields == 'iso_currency') {
          $currency = new Currency($order['id_currency']);
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $currency->iso_code);
        } else if ($fields == 'weight') {
          $obj = new Order($order['id_order']);
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $obj->getTotalWeight());
        } else if ($fields == 'source_date') {
          $sources = ConnectionsSourceCore::getOrderSources($order['id_order']);
          $sourceData = array();
          $sourceData['source_date'] = '';

          foreach ($sources as $source) {
            $sourceData['source_date'] .= $source['date_add'] . ', ';
          }

          $sourceData['source_date'] = rtrim($sourceData['source_date'], ', ');
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $sourceData['source_date']);
        } else if ($fields == 'source_from') {
          $sources = ConnectionsSourceCore::getOrderSources($order['id_order']);
          $sourceData = array();
          $sourceData['source_from'] = '';

          foreach ($sources as $source) {
            $sourceData['source_from'] .= $source['http_referer'] . ', ';
          }

          $sourceData['source_from'] = rtrim($sourceData['source_from'], ', ');

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $sourceData['source_from']);
        } else if ($fields == 'source_to') {
          $sources = ConnectionsSourceCore::getOrderSources($order['id_order']);
          $sourceData = array();
          $sourceData['source_to'] = '';

          foreach ($sources as $source) {
            $sourceData['source_to'] .= $source['request_uri'] . ', ';
          }

          $sourceData['source_to'] = rtrim($sourceData['source_to'], ', ');

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $sourceData['source_to']);
        } elseif ($fields == 'cover_url') {

          if ($config['separate']) {
            $link = new Link();

            if ($order['product_attribute_id']) {
              $cover = $this->getCombinationImageById($order['product_attribute_id'], Context::getContext()->language->id);
            }

            if (!$order['product_attribute_id'] || !$cover) {
              $cover = Product::getCover($order['product_id']);
            }
            if ($cover['id_image']) {
              $url_cover = $link->getImageLink('image', $cover['id_image'], $this->_imageType);
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $url_cover, PHPExcel_Cell_DataType::TYPE_STRING);
            }
          } else {
            $url_cover = "";
            $ids = explode(",", $order['product_id']);
            foreach ($ids as $value) {
              $link = new Link();
              $cover = Product::getCover($value);
              if ($cover['id_image']) {
                $url_cover .= $link->getImageLink('image', $cover['id_image'], $this->_imageType);
              }
              $url_cover .= ',';
            }
            $url_cover = rtrim($url_cover, ",");
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $url_cover, PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } elseif ($fields == 'customer_fio') {

          $fio = '';

          if (isset($order['customer_firstname']) && $order['customer_firstname']) {
            $fio .= $order['customer_firstname'];
          }

          if (isset($order['customer_lastname']) && $order['customer_lastname']) {
            $fio .= ' ' . $order['customer_lastname'];
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $fio);
          $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth(35);
        } elseif ($fields === 'shipping_customer_fullname') {
          $fio = '';

          if (!empty($order['shipping_customer_firstname']) && !empty($order['shipping_customer_lastname'])) {
            $fio = $order['shipping_customer_firstname'] . ' ' . $order['shipping_customer_lastname'];
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $fio);
          $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth(35);
        } elseif ($fields === 'invoice_customer_fullname') {
          $fio = '';

          if (!empty($order['invoice_customer_firstname']) && !empty($order['invoice_customer_lastname'])) {
            $fio = $order['invoice_customer_firstname'] . ' ' . $order['invoice_customer_lastname'];
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $fio);
          $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth(35);
        } elseif ($fields == 'cover_image') {
          if ($config['separate']) {
            if ($order['product_attribute_id']) {
              $cover = $this->getCombinationImageById($order['product_attribute_id'], Context::getContext()->language->id);

              if (!$cover) {
                $cover = Product::getCover($order['product_id']);
              }
            } else {
              $cover = Product::getCover($order['product_id']);
            }

            if ($cover['id_image']) {
              $url_cover = _PS_ROOT_DIR_.'/img/p/'.Image::getImgFolderStatic($cover['id_image']).$cover['id_image'].'-'.$this->_imageType.'.jpg';
              if (($mime = @getimagesize($url_cover))) {
                $gdImage = $this->_getImageObject($mime, $url_cover);
                $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setImageResource($gdImage);
                $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setHeight(150);
                $objDrawing->setOffsetX(6);
                $objDrawing->setOffsetY(6);
                $objDrawing->setCoordinates($alfavit[$col] . $i);
                $objDrawing->setWorksheet($this->_PHPExcel->getActiveSheet(), $limit);
                $this->_PHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(121);
                $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth(20);
              }
            }

          } else {
            $product_ids = explode(',', $order['product_id']);
            $product_attribute_ids = explode(',', $order['product_attribute_id']);
            $num_of_products = count($product_ids);

            $max_num_of_products = $num_of_products > $max_num_of_products ? $num_of_products : $max_num_of_products;

            for ($k = 0; $k < $num_of_products; $k++) {
              if ($product_attribute_ids[$k]) {
                $cover = $this->getCombinationImageById($product_attribute_ids[$k], Context::getContext()->language->id);

                if (!$cover) {
                  $cover = Product::getCover($product_ids[$k]);
                }
              } else {
                $cover = Product::getCover($product_ids[$k]);
              }

              if ($cover['id_image']) {
                $url_cover = _PS_ROOT_DIR_.'/img/p/'.Image::getImgFolderStatic($cover['id_image']).$cover['id_image'].'-'.$this->_imageType.'.jpg';
                if ($mime = @getimagesize($url_cover)) {
                  $gdImage = $this->_getImageObject($mime, $url_cover);
                  $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                  $objDrawing->setImageResource($gdImage);
                  $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
                  $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                  $objDrawing->setHeight(150);
                  if ($k == 0) {
                    $objDrawing->setOffsetX(6);
                  } else {
                    $objDrawing->setOffsetX(($k) * 170);
                  }

                  $objDrawing->setOffsetY(6);
                  $objDrawing->setCoordinates($alfavit[$col] . $i);
                  $objDrawing->setWorksheet($this->_PHPExcel->getActiveSheet(), $limit);
                }
              }
            }

            $this->_PHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(121);
            $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth($max_num_of_products * 19);
          }
        } elseif (strpos($fields, 'Attribute_') !== false) {
          if ($config['separate']) {
            $groupId = explode('_', $fields);
            $groupId = end($groupId);
            if( $order['product_attribute_id'] ){
              $attrValue = $this->_getAttributesName($order['product_attribute_id'], $id_lang, $groupId);
            }
            else{
              $attrValue = '';
            }
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $attrValue, PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } elseif ($fields == 'customization') {
          $customization = $this->exportCustomizedFields($order, false, $config['separate'], 'customization', $id_lang);
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $customization);
        } elseif(strpos($fields, 'CUSTOMIZATION_') !== false) {
          $customization_name = preg_split('/_/', $fields);
          $customization_name = $customization_name[1];

          $customization = $this->exportCustomizedFields($order, true, $config['separate'], $customization_name, $id_lang);
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValue($alfavit[$col] . $i, $customization);
        } elseif ($fields == 'total_products'
          || $fields == 'total_products_wt'
          || $fields == 'supplier_price'
          || $fields == 'unit_product_price_tax_excl'
          || $fields == 'unit_product_price_tax_incl'
          || $fields == 'total_product_price_tax_excl'
          || $fields == 'total_product_price_tax_incl'
          || $fields == 'product_total_tax_amount'
          || $fields == 'product_unit_tax_amount'
          || $fields == 'product_tax_rate'
          || $fields == 'total_discounts_tax_incl'
          || $fields == 'total_discounts_tax_excl'
          || $fields == 'total_paid_tax_incl'
          || $fields == 'total_paid_tax_excl'
          || $fields == 'total_paid_real'
          || $fields == 'total_shipping_tax_incl'
          || $fields == 'total_shipping_tax_excl'
          || $fields == 'total_wrapping_tax_incl'
          || $fields == 'total_wrapping_tax_excl'
          || $fields == 'product_ecotax'
          || $fields == 'product_ecotax_tax_excl'
          || $fields == 'product_reduction_amount_tax_incl'
          || $fields == 'product_reduction_amount_tax_excl'
          || $fields == 'item_unit_price_tax_incl'
          || $fields == 'item_unit_price_tax_excl'
          || $fields == 'total_products_tax_excl_slip'
          || $fields == 'total_products_tax_incl_slip'
          || $fields == 'total_shipping_tax_excl_slip'
          || $fields == 'total_shipping_tax_incl_slip'
          || $fields == 'shipping_cost_slip'
          || $fields == 'amount_slip'
          || $fields == 'shipping_cost_amount_slip'
          || $fields == 'unit_price_tax_excl_slip'
          || $fields == 'unit_price_tax_incl_slip'
          || $fields == 'total_price_tax_excl_slip'
          || $fields == 'total_price_tax_incl_slip'
          || $fields == 'amount_tax_excl_slip'
          || $fields == 'amount_tax_incl_slip'
          || $fields == 'total_products_tax'
          || $fields == 'total_paid_tax'
        ) {
          if ($config['separate']) {

            if ($fields == 'item_unit_price_tax_excl') {
              $tmpProduct = new Product($order['product_id']);
              $tmpPrice = ($tmpProduct->unit_price_ratio != 0 ? $tmpProduct->price / $tmpProduct->unit_price_ratio : 0);
              $tmpPrice = Tools::ps_round((float)$tmpPrice, $round_value);
              $tmpPrice = number_format($tmpPrice, $round_value, $separator_decimal_points, '');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            } elseif ($fields == 'item_unit_price_tax_incl') {
              $tmpProduct = new Product($order['product_id']);
              $tmpPrice = ($tmpProduct->unit_price_ratio != 0 ? $tmpProduct->price / $tmpProduct->unit_price_ratio : 0);

              $address = null;
              if (is_object(Context::getContext()->cart) && Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')} != null) {
                $address = Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
              }

              $taxRate = $tmpProduct->getTaxesRate(new Address($address));
              $tmpPrice = $tmpPrice + ($tmpPrice * ($taxRate / 100));
              $tmpPrice = Tools::ps_round((float)$tmpPrice, $round_value);
              $tmpPrice = number_format($tmpPrice, $round_value, $separator_decimal_points, '');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            } else {
              $tmpPrice = Tools::ps_round((float)$order[$fields], $round_value);
              $tmpPrice = number_format($tmpPrice, $round_value, $separator_decimal_points, '');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            }
          } else {
            if ($fields == 'item_unit_price_tax_excl') {
              $tmpProductIds = explode(',', $order['product_id']);
              $tmpReadyPrice = '';
              foreach ($tmpProductIds as $product) {
                $tmpProduct = new Product($product);
                $tmpPrice = ($tmpProduct->unit_price_ratio != 0 ? $tmpProduct->price / $tmpProduct->unit_price_ratio : 0);
                $tmpPrice = Tools::ps_round((float)$tmpPrice, $round_value);
                $tmpPrice = number_format($tmpPrice, $round_value, $separator_decimal_points, '');
                $tmpReadyPrice .= $tmpPrice . ',';
              }
              $tmpReadyPrice = rtrim($tmpReadyPrice, ',');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpReadyPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            } elseif ($fields == 'item_unit_price_tax_incl') {
              $tmpProductIds = explode(',', $order['product_id']);
              $tmpReadyPrice = '';
              $address = null;
              if (is_object(Context::getContext()->cart) && Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')} != null) {
                $address = Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
              }

              foreach ($tmpProductIds as $product) {
                $tmpProduct = new Product($product);
                $tmpPrice = ($tmpProduct->unit_price_ratio != 0 ? $tmpProduct->price / $tmpProduct->unit_price_ratio : 0);

                $taxRate = $tmpProduct->getTaxesRate(new Address($address));
                $tmpPrice = $tmpPrice + ($tmpPrice * ($taxRate / 100));
                $tmpPrice = Tools::ps_round((float)$tmpPrice, $round_value);
                $tmpPrice = number_format($tmpPrice, $round_value, $separator_decimal_points, '');
                $tmpReadyPrice .= $tmpPrice . ',';
              }
              $tmpReadyPrice = rtrim($tmpReadyPrice, ',');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpReadyPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            } else {
              $tmpPrice = '';
              $order[$fields] = explode(',', $order[$fields]);
              foreach ($order[$fields] as $curPrice) {
                $curPrice = Tools::ps_round((float)$curPrice, $round_value);
                $curPrice = number_format($curPrice, $round_value, $separator_decimal_points, '');
                $tmpPrice .= $curPrice . ',';
              }
              $tmpPrice = rtrim($tmpPrice, ',');
              $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpPrice, PHPExcel_Cell_DataType::TYPE_STRING);
            }
          }
        } elseif ($fields == 'item_unit_type') {
          if ($config['separate']) {
            $tmpProduct = new Product($order['product_id']);
            $unity = $tmpProduct->unity;
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $unity, PHPExcel_Cell_DataType::TYPE_STRING);
          } else {
            $tmpProductIds = explode(',', $order['product_id']);
            $tmpReadyUnity = '';
            foreach ($tmpProductIds as $product) {
              $tmpProduct = new Product($product);
              $unity = $tmpProduct->unity;
              if (!$unity) {
                $unity = ' ';
              }
              $tmpReadyUnity .= $unity . ',';
            }
            $tmpReadyUnity = rtrim($tmpReadyUnity, ',');
            $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpReadyUnity, PHPExcel_Cell_DataType::TYPE_STRING);
          }
        } elseif ($fields == 'payment_amount'  ||
                  $fields == 'conversion_rate' ||
                  $fields == 'carrier_tax_rate'
        ) {
          $tmpPrice = number_format((float)$order[$fields], $round_value, $separator_decimal_points, '');
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $tmpPrice, PHPExcel_Cell_DataType::TYPE_STRING);
        } elseif ($fields == 'product_weight' ||
                  $fields == 'product_width'  ||
                  $fields == 'product_height' ||
                  $fields == 'product_depth'
        ) {
          if ($config['separate']) {
            $final_value = number_format((float)$order[$fields], $round_value, $separator_decimal_points, '');
          } else {
            $product_property = explode('_', $fields);
            $product_property = $product_property[1];
            $final_value = $this->getProductValueForNotSeparateExport($product_property, $order['product_id'], $round_value, $separator_decimal_points);
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $final_value, PHPExcel_Cell_DataType::TYPE_STRING);
        } elseif ($fields == 'product_reduction_percent') {
          if ($config['separate']) {
            $final_value = number_format((float)$order[$fields], $round_value, $separator_decimal_points, '');
          } else {
            $tmpValues = explode(',', $order[$fields]);
            $result = '';
            foreach ($tmpValues as $value) {
              $formatted_value = number_format((float)$value, $round_value, $separator_decimal_points, '');
              $formatted_value = !$formatted_value ? ' ' : $formatted_value;
              $result .= $formatted_value . ',';
            }
            $final_value = rtrim($result, ',');
          }

          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $final_value, PHPExcel_Cell_DataType::TYPE_STRING);
        } else {
          $this->_PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit($alfavit[$col] . $i, $order[$fields], PHPExcel_Cell_DataType::TYPE_STRING);


          if ($fields == 'id_order' || $fields == 'id_customer' || $fields == 'id_cart' || $fields == 'id_currency' || $fields == 'id_carrier') {
            $this->_PHPExcel->getActiveSheet()->getColumnDimension($alfavit[$col])->setWidth(15);
          }

        }
      }
      $i++;

      $currentExported = Configuration::get('EXPORT_ORDERS_CURRENT_COUNT', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);

      if ($order['id_order'] != $this->_orderIdCount) {
        Configuration::updateValue('EXPORT_ORDERS_CURRENT_COUNT', ((int)$currentExported + 1), false, $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
      }
      $this->_orderIdCount = $order['id_order'];
    }

    if ((int)$ordersCount <= ((int)$limit * (int)$limitN) + (int)$limitN) {

      $style_wrap = array(
        //рамки
        'borders' => array(
          'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK
          ),
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array(
              'rgb' => '696969'
            )
          )
        )
      );
      $activeSheet->getStyle('A1:' . $alfavit[$j - 1] . ($i - 1))->applyFromArray($style_wrap);

      if ($display_headers) {


        $style_hprice = array(
          'alignment' => array(
            'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
          ),
          'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color' => array(
              'rgb' => 'CFCFCF'
            )
          ),
          'font' => array(
            'bold' => true,
            'italic' => true,
            'name' => 'Times New Roman',
            'size' => 13
          ),
        );
        $activeSheet->getStyle('A1:' . $alfavit[$j - 1] . '1')->applyFromArray($style_hprice);
      } else {
        $style_hprice = array(
          'fill' => array(
            'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
            'color' => array(
              'rgb' => 'F2F2F5'
            )
          ),
        );
        $activeSheet->getStyle('A1:' . $alfavit[$j - 1] . '1')->applyFromArray($style_hprice);
      }


      $style_price = array(
        'alignment' => array(
          'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
        )
      );
      $activeSheet->getStyle('A2:' . $alfavit[$j - 1] . ($i - 1))->applyFromArray($style_price);

      $style_background1 = array(
        'fill' => array(
          'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
          'color' => array(
            'rgb' => 'F2F2F5'
          )
        ),
      );
      $activeSheet->getStyle('A2:' . $alfavit[$j - 1] . ($i - 1))->applyFromArray($style_background1);
    }


    if (!$limit) {
      if (isset($config['name_settings']) && $config['name_settings']) {
        Configuration::updateValue('EXPORT_ORDERS_TIME', $config['name_settings'] . '_' . Date('Y.m.d_G-i-s'), false, $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
      } else {
        Configuration::updateValue('EXPORT_ORDERS_TIME', Date('Y.m.d_G-i-s'), false, $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id);
      }
    }

    if ($config['format'] == 'xlsx') {
      $objWriter = PHPExcel_IOFactory::createWriter($this->_PHPExcel, 'Excel2007');

      if ($config['specific_export_file'] && $config['file_name']) {
        if ((int)$ordersCount <= ((int)$limit * (int)$limitN) + (int)$limitN) {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/' . $config['file_name'] . '.xlsx');

          if ($config['feed_target'] == 'ftp') {
            $local_path = _PS_MODULE_DIR_.'ordersexport/files/' . $config['file_name'] . '.xlsx';
            $file_name = $config['file_name'];
            $this->ftp_manager->copyFileToServer($local_path, $file_name);
          }

          for ($l = 0; $l < (int)$limit; $l++) {
            if (file_exists(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.xlsx')) {
              unlink(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.xlsx');
            }
          }
        } else {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . $limit . '.xlsx');
        }
      } else {
        if ((int)$ordersCount <= ((int)$limit * (int)$limitN) + (int)$limitN) {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.xlsx');
          if ($config['feed_target'] == 'ftp') {
            $local_path = _PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.xlsx';
            $file_name = 'export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.xlsx';
            $this->ftp_manager->copyFileToServer($local_path, $file_name);
          }

          for ($l = 0; $l < (int)$limit; $l++) {
            if (file_exists(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.xlsx')) {
              unlink(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.xlsx');
            }
          }
        } else {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . $limit . '.xlsx');
        }
      }
    } elseif ($config['format'] == 'csv') {
      $objWriter = PHPExcel_IOFactory::createWriter($this->_PHPExcel, 'CSV');
      $objWriter->setDelimiter($delimiter);
      $objWriter->setEnclosure($sep);
      $objWriter->setUseBOM(true);


      if ($config['specific_export_file'] && $config['file_name']) {
        if ((int)$ordersCount <= ((int)$limit * (int)$limitN) + (int)$limitN) {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/' . $config['file_name'] . '.csv');
          if ($config['feed_target'] == 'ftp') {
            $local_path = _PS_MODULE_DIR_.'ordersexport/files/' . $config['file_name'] . '.csv';
            $file_name = $config['file_name'];
            $this->ftp_manager->copyFileToServer($local_path, $file_name);
          }

          for ($l = 0; $l < (int)$limit; $l++) {
            if (file_exists(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.csv')) {
              unlink(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.csv');
            }
          }
        } else {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . $limit . '.csv');
        }
      } else {
        if ((int)$ordersCount <= ((int)$limit * (int)$limitN) + (int)$limitN) {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.csv');
          if ($config['feed_target'] == 'ftp') {
            $local_path = _PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.csv';
            $file_name = 'export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . '.csv';
            $this->ftp_manager->copyFileToServer($local_path, $file_name);
          }

          for ($l = 0; $l < (int)$limit; $l++) {
            if (file_exists(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.csv')) {
              unlink(_PS_MODULE_DIR_ . 'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . ((int)$l) . '.csv');
            }
          }
        } else {
          $objWriter->save(_PS_MODULE_DIR_.'ordersexport/files/export_orders_' . Configuration::get('EXPORT_ORDERS_TIME', '', $this->ordersExport->default_shop_group_id, $this->ordersExport->default_shop_id) . $limit . '.csv');
        }
      }
    }
    if ((int)$ordersCount > ((int)$limit * (int)$limitN) + (int)$limitN) {
      return (int)$limit + 1;
    }

    if ($automatic && $not_exported) {
      $orders_ids = $this->_model->getOrdersIds($id_shop, false, false, $not_exported, $name_settings, 100000, $orderby, $orderway, false, $id_order, $export_filters);
      $this->setInDbExportedOrders($name_settings, $orders_ids);
    }

    return true;
  }

  private function _getImageObject($mime, $image)
  {
    switch (Tools::strtolower($mime['mime'])) {
      case 'image/png':
        $img_r = imagecreatefrompng($image);
        break;
      case 'image/jpeg':
        $img_r = imagecreatefromjpeg($image);
        break;
      case 'image/gif':
        $img_r = imagecreatefromgif($image);
        break;
      default:
        $img_r = imagecreatefrompng($image);;
    }

    return $img_r;
  }

  public function setInDbExportedOrders($name_settings, $orderIds)
  {

    $ids = array();

    if ($name_settings && $orderIds) {
      $ids = explode(",", $orderIds);

      foreach ($ids as $id_order) {
        Db::getInstance()->insert('exported_order', array('id_order' => $id_order, 'settings' => trim($name_settings)));
      }

    }

  }

  protected function _getFormatedAddress(Address $the_address, $line_sep, $fields_style = array())
  {
    return AddressFormat::generateAddress($the_address, array('avoid' => array()), $line_sep, ' ', $fields_style);
  }

  protected function handleCustomizationExport($customization_data, $customization_name = 'customization', $in_separate_fields, $in_separate_columns, $order)
  {
      $customVal = array();
      $readyVal = '';

      if ($in_separate_fields == true) {
        if (isset($customization_data[$order['product_id']]) && $customization_data[$order['product_id']]) {
          $custom1 = $customization_data[$order['product_id']];
        } else {
          $custom1 = array();
        }

        if (is_array($customization_data)) {
          foreach ($custom1 as $cAttrId => $custom2) {
            $customVal[$cAttrId] = '';

            $this->handleCustomizationExportCoreLoop($customization_name, $custom2, $in_separate_columns, $customVal[$cAttrId]);

            $customVal[$cAttrId] = rtrim($customVal[$cAttrId], ' ');
            $customVal[$cAttrId] = rtrim($customVal[$cAttrId], ',');
          }

          $separateCustomization = '';

          if (isset($customVal[$order['product_attribute_id']]) && $customVal[$order['product_attribute_id']]) {
            $separateCustomization = $customVal[$order['product_attribute_id']];
          }

          return $separateCustomization;
        }
      } else {
        if (is_array($customization_data)) {
          foreach ($customization_data as $cAttrId => $custom1) {
            $customVal[$cAttrId] = '';
            foreach ($custom1 as $custom2) {
              $this->handleCustomizationExportCoreLoop($customization_name, $custom2, $in_separate_columns, $customVal[$cAttrId]);
            }

            $customVal[$cAttrId] = rtrim($customVal[$cAttrId], ' ');
            $customVal[$cAttrId] = rtrim($customVal[$cAttrId], ',');
          }

          return implode(',', $customVal);
        }
      }

  }

  private function handleCustomizationExportCoreLoop($customization_name, $custom2, $in_separate_columns, &$result)
  {
    foreach ($custom2 as $custom3) {
      foreach ($custom3 as $custom4) {
        foreach ($custom4['datas'] as $cType => $custom5) {
          if ($cType == Product::CUSTOMIZE_FILE) {
            foreach ($custom5 as $customFile) {
              if ($in_separate_columns == true) {
                if (strtolower($customFile['name']) == strtolower($customization_name)) {
                  $result .= _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'upload/' . $customFile['value'];
                  $result .= ',';
                }
              } else {
                $result .= $customFile['name'] . ': ';
                $result .= _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'upload/' . $customFile['value'];
                $result .= ',';
              }
            }
          } else {
            foreach ($custom5 as $customText) {
              if ($in_separate_columns == true) {
                if (strtolower($customText['name']) == strtolower($customization_name)) {
                  $result .= $customText['value'];
                  $result .= ',';
                }
              } else {
                $result .= $customText['name'] . ': ';
                $result .= $customText['value'];
                $result .= ',';
              }
            }
          }
        }
      }
    }
  }

  private function getProductValueForNotSeparateExport($product_property_name, $order_products_ids, $round_value, $separator_decimal_points)
  {
    $tmpProductIds = explode(',', $order_products_ids);
    $result = '';

    foreach ($tmpProductIds as $product) {
      $tmpProduct = new Product($product);
      $product_value = number_format((float)$tmpProduct->$product_property_name, $round_value, $separator_decimal_points, '');

      if (!$product_value) {
        $product_value = ' ';
      }
      $result .= $product_value . ',';
    }

    return rtrim($result, ',');
  }

  /**
   * @param array $export_field_names
   * @return bool
   */
  private function isActiveExportCustomizationInSeparateCols(array $export_field_names)
  {
    $is_active = false;

    foreach ($export_field_names as $field_name) {
      if (preg_match('/^CUSTOMIZATION_.+$/', $field_name)) {
        $is_active = true;
      }
    }

    return $is_active;
  }

  /**
   * Removes separate customization columns that are never used in any of orders
   *
   * @param array $column_names
   * @param array $used_customization_fields
   * @return array
   */
  private function removeUnusedCustomizationFields(array $column_names, array $used_customization_fields)
  {
    $filtered_column_names = array_filter($column_names, function($item) use ($used_customization_fields) {
      $is_used = true;

      if (preg_match('/^CUSTOMIZATION_.+$/', $item)) {
        $customization_field_name = explode('_', $item, 2);

        if (!in_array($customization_field_name[1], $used_customization_fields)) {
          $is_used = false;
        }
      }

      return $is_used === true ? true : false;
    });

    return array_values($filtered_column_names);
  }

  /**
   *
   * @param array $order_ids
   * @param $id_lang
   * @return array
   */
  private function getAllUsedCustomizationFields(array $order_ids, $id_lang)
  {
    $used_customizations = array();

    foreach ($order_ids as $order_id) {
      $order = new Order($order_id);
      $order_cart_id = $order->id_cart;
      $order_cart_customized_data = ordersExportDataModel::getAllCustomizedDatas($order_cart_id, (int)$id_lang, true, (int)$order->id_shop);

      if ($order_cart_customized_data) {
        array_walk_recursive($order_cart_customized_data, function($item, $key) use (&$used_customizations) {
          if ($key == 'name') {
            array_push($used_customizations, $item);
          }
        });
      }
    }

    return $used_customizations;
  }

  private function getCombinationImageById($id_product_attribute, $id_lang)
  {
    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
      return Product::getCombinationImageById($id_product_attribute, $id_lang);
    } else {
      if (!Combination::isFeatureActive() || !$id_product_attribute)
        return false;

      $result = Db::getInstance()->executeS('
			SELECT pai.`id_image`, pai.`id_product_attribute`, il.`legend`
			FROM `'._DB_PREFIX_.'product_attribute_image` pai
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (il.`id_image` = pai.`id_image`)
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_image` = pai.`id_image`)
			WHERE pai.`id_product_attribute` = '.(int)$id_product_attribute.' AND il.`id_lang` = '.(int)$id_lang.' ORDER by i.`position` LIMIT 1'
      );

      if (!$result)
        return false;

      return $result[0];
    }
  }

  private function exportCustomizedFields($order, $in_separate_columns, $separate, $customization_name, $id_lang)
  {
    if (empty($order['id_customization']) && empty($order['id_customization'][0])) {
        return '';
    }

    if ($separate) {
      $customized = ordersExportDataModel::getAllCustomizedDatas($order['id_cart'], $id_lang, true, $order['id_shop'], $order['id_customization']);
      return $this->handleCustomizationExport($customized, $customization_name, true, $in_separate_columns, $order);
    } else {
      $oneRowCustomization = '';
      $customizations_ids = explode(',', $order['id_customization']);

      foreach ($customizations_ids as $id_customization) {
        if ($id_customization == 0) {
          continue;
        }

        $customized = ordersExportDataModel::getAllCustomizedDatas($order['id_cart'], $id_lang, true, $order['id_shop'], $id_customization);
        $oneRowCustomization .= ',' . $this->handleCustomizationExport($customized, $customization_name, false, $in_separate_columns, $order);
      }

      return trim($oneRowCustomization, ', ');
    }
  }
}
