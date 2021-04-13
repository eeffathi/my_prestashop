$(document).ready(function(){
  MpmImportCategoryLinking.init();

  $(document).on("click live", MpmImportCategoryLinking.add_btn, function(e) {
      e.preventDefault();
      MpmImportCategoryLinking.addNew();
  });

  $(document).on("click live", MpmImportCategoryLinking.delete_btn, function(e) {
      e.preventDefault();
      MpmImportCategoryLinking.remove($(this));
  });

  $(document).on("click", MpmImportCategoryLinking.toggle_switch, function() {
      MpmImportCategoryLinking.toggleVisibility();
  });


  if ($("#customization_one_column_on").is(":checked")) {
    $(".more_customization").hide();
    $(".panel.customization:not(.first_child)").hide();
  } else {
    $(".more_customization").show();
    $(".panel.customization:not(.first_child)").show();
  }

  $(document).on("click change", "input[name='customization_one_column']", function() {
      if ($("#customization_one_column_on").is(":checked")) {
          $(".more_customization").hide();
          $(".panel.customization:not(.first_child)").hide();
      } else {
          $(".more_customization").show();
          $(".panel.customization:not(.first_child)").show();
      }
  });

  saveConfig();

  $(document).on('click', '.list-group-item.import_tab', function(){
    var tab = $(this).attr('data-tab');
    if(tab == 'prices'){
      changeDiscount();
    }
  });

  if ($("input[name='import_attachments_from_single_column']:checked").val() == 1) {
    $(".btn.more_attachments").hide();
  } else {
    $(".btn.more_attachments").show();
  }

  $(document).on("live change", "input[name='import_attachments_from_single_column']", function() {
      if ($("input[name='import_attachments_from_single_column']:checked").val() == 1) {
          $(".btn.more_attachments, .additional-attachments").hide();
      } else {
          $(".btn.more_attachments").show();
          $("#fieldset_14_14, .additional-attachments").show();
      }
  });

  $(document).on('click', '.simpleimportproduct .more_settings', function(){
    if( $(this).hasClass('active') ){
      $(this).removeClass('active');
      $('.simpleimportproduct .use_headers').fadeOut();
      $('.simpleimportproduct .disable_hooks').fadeOut();
      $('.simpleimportproduct .search_index').fadeOut();
      $('.simpleimportproduct .products_range').fadeOut();
      $('.simpleimportproduct .from_range').fadeOut();
      $('.simpleimportproduct .to_range').fadeOut();
      $('.simpleimportproduct .iteration').fadeOut();
    }
    else{
      $(this).addClass('active');
      $('.simpleimportproduct .use_headers').fadeIn();
      $('.simpleimportproduct .disable_hooks').fadeIn();
      $('.simpleimportproduct .search_index').fadeIn();
      $('.simpleimportproduct .products_range').fadeIn();
      if( $('.simpleimportproduct .products_range input:checked').val() == 'range' ){
        $('.simpleimportproduct .from_range').fadeIn();
        $('.simpleimportproduct .to_range').fadeIn();
      }
      $('.simpleimportproduct .iteration').fadeIn();
    }
  });

  $(document).on('change', '.simpleimportproduct .products_range input', function(){
    if( $(this).filter(':checked').val() == 'all' ){
      $('.simpleimportproduct .from_range').fadeOut();
      $('.simpleimportproduct .to_range').fadeOut();
    }
    else {
      $('.simpleimportproduct .from_range').fadeIn();
      $('.simpleimportproduct .to_range').fadeIn();
    }
  });

  $(document).on('change', '.simpleimportproduct .field_settings_block select[name=condition]', function(){
    if( $(this).val() == 'empty' || $(this).val() == 'any' ){
      $(this).parents('.field_settings_block').find('.setting_condition_value').hide();
      if( $(this).val() == 'any' ){
        $(this).parents('.field_settings_block').find('.setting_field').hide();
      }
      else {
        $(this).parents('.field_settings_block').find('.setting_field').show();
      }
    }
    else {
      $(this).parents('.field_settings_block').find('.setting_condition_value').show();
      $(this).parents('.field_settings_block').find('.setting_field').show();
    }
  });

  $.each($('.simpleimportproduct #fieldset_0 .field_settings_block'), function( i ){
    var condition_value = $(this).find('.setting_condition select').val();
    if( condition_value == 'empty' || condition_value == 'any' ){
      $(this).find('.setting_condition_value').hide();
    }
    if( condition_value == 'any' ){
      $(this).find('.setting_field').hide();
    }
  });

  $(document).on('change', '.simpleimportproduct .price_settings_block select[name=condition]', function(){
    if( $(this).val() == 'zero' || $(this).val() == 'any' ){
      $(this).parents('.price_settings_block').find('.setting_condition_value').hide();
    }
    else {
      $(this).parents('.price_settings_block').find('.setting_condition_value').show();
    }
  });


  $.each($('.simpleimportproduct #fieldset_0 .price_settings_block'), function( i ){
    var condition_value = $(this).find('.setting_condition select').val();
    if( condition_value == 'zero' || condition_value == 'any' ){
      $(this).find('.setting_condition_value').hide();
    }
  });

  $(document).on('change', '.simpleimportproduct .quantity_settings_block select[name=condition]', function(){
    if( $(this).val() == 'zero' || $(this).val() == 'any' ){
      $(this).parents('.quantity_settings_block').find('.setting_condition_value').hide();
    }
    else {
      $(this).parents('.quantity_settings_block').find('.setting_condition_value').show();
      $(this).parents('.quantity_settings_block').find('.setting_quantity_formula').show();
    }
  });

  $.each($('.simpleimportproduct #fieldset_0 .quantity_settings_block'), function( i ){
    var condition_value = $(this).find('.setting_condition select').val();
    if( condition_value == 'zero' || condition_value == 'any' ){
      $(this).find('.setting_condition_value').hide();
    }
  });

  $(document).on('click', '.simpleimportproduct .show_more', function(){
    if( $(this).hasClass('active') ){
      $(this).removeClass('active');
      $(this).html(import_show_more);
      $('.simpleimportproduct #fieldset_1_1').attr('style','');
      $('.simpleimportproduct #fieldset_1_1').removeClass('show_all');
    }
    else{
      $(this).addClass('active');
      $(this).html(import_hide);
      $('.simpleimportproduct #fieldset_1_1').addClass('show_all');
      setTimeout(function(){
        $('.simpleimportproduct #fieldset_1_1').css('height', '100%')
      },500);
    }
  });

  $('.panel-heading-gomakoil .step_2, .next_button_import').click(function(){
    if( $('.simpleimportproduct select.feed_source').val() == 'file_url' || $('.simpleimportproduct select.feed_source').val() == 'ftp' ){
      stepTwoImportFromUrl();
    }
    else{
      stepTwoImport();
    }
  });

  $('.content_import_page #fieldset_12_12').addClass('suppliers');
  $('.content_import_page #fieldset_4_4').addClass('prices');
  // $('.content_import_page #fieldset_5_5').addClass('prices');
  $('.content_import_page #fieldset_8_8').addClass('associations');
  // $('.content_import_page #fieldset_9_9').addClass('associations');
  $('.content_import_page #fieldset_3_3').addClass('combinations');
  $('.content_import_page #fieldset_7_7').addClass('features');
  $('.content_import_page #fieldset_7_7').addClass('first_child');
  $('.content_import_page #fieldset_6_6').addClass('images');
  $('.content_import_page #fieldset_6_6').addClass('first_child');
  $('.content_import_page #fieldset_11_11').addClass('pack');
  $('.content_import_page #fieldset_13_13').addClass('customization');
  $('.content_import_page #fieldset_13_13').addClass('first_child');
  $('.content_import_page #fieldset_14_14').addClass('attachments');
  $('.content_import_page #fieldset_14_14').addClass('first_child');

  $('.content_import_page .import_tabs .import_tab').click(function(){
    $('.content_import_page .import_tabs .import_tab').removeClass('active');
    $(this).addClass('active');
    $('.content_import_page #fieldset_0 .form-group').hide();

    $('.content_import_page #fieldset_3_3').hide();
    $('.content_import_page #fieldset_4_4').hide();
    $('.content_import_page #fieldset_7_7').hide();
    $('.content_import_page #fieldset_6_6').hide();
    $('.content_import_page #fieldset_8_8').hide();
    $('.content_import_page #fieldset_10_10').hide();
    $('.content_import_page #fieldset_11_11').hide();
    $('.content_import_page #fieldset_12_12').hide();
    $('.content_import_page #fieldset_13_13').hide();
    $('.content_import_page #fieldset_14_14').hide();


    hasAccessories();
    hasDiscount();

    if( $(this).attr('data-tab') == 'combinations' || $(this).attr('data-tab') == 'features' || $(this).attr('data-tab') == 'images' || $(this).attr('data-tab') == 'pack' || $(this).attr('data-tab') == 'customization' || $(this).attr('data-tab') == 'attachments' || $(this).attr('data-tab') == 'suppliers' ){
      $('.content_import_page #fieldset_0').hide();
    }
    else {
      $('.content_import_page #fieldset_0').show();
    }

    $('.content_import_page #fieldset_0 .'+$(this).attr('data-tab')).show();
    $('.content_import_page .panel.'+$(this).attr('data-tab')).show();

    if( !$('.content_import_page #fieldset_0 .form-group.product_id').hasClass('active') ){
      $('.content_import_page #fieldset_0 .form-group.product_id').hide();
    }

    if( $('select[name=tax_method]').val() == 'tax_rate_method' ){
      $('.simpleimportproduct .tax_rule_method').hide();
      $('.simpleimportproduct .existing_tax_method').hide();
    }
    else if( $('select[name=tax_method]').val() == 'tax_rule_method' ){
      $('.simpleimportproduct .existing_tax_method').hide();
      $('.simpleimportproduct .tax_rate_method').hide();
    }
    else{
      $('.simpleimportproduct .tax_rate_method').hide();
      $('.simpleimportproduct .tax_rule_method').hide();
    }



    if( $('select[name=category_method]').val() == 'category_ids_method'){
      $('.simpleimportproduct .category_name_method').hide();
      $('.simpleimportproduct .category_tree_method').hide();
    }
    else if( $('select[name=category_method]').val() == 'category_tree_method' ){
      $('.simpleimportproduct .category_id_method').hide();
      $('.simpleimportproduct .more_subcategory').hide();
    }
    else {
      $('.simpleimportproduct .category_tree_method').hide();
      $('.simpleimportproduct .category_id_method').hide();
    }

    if( $('select[name=supplier_method]').val() == 'supplier_name_method' ){
      $('.simpleimportproduct .supplier_id_method').hide();
      $('.simpleimportproduct .existing_supplier_method').hide();
    }
    else if($('select[name=supplier_method]').val() == 'supplier_ids_method'){
      $('.simpleimportproduct .existing_supplier_method').hide();
      $('.simpleimportproduct .supplier_name_method').hide();
    }
    else{
      $('.simpleimportproduct .supplier_name_method').hide();
      $('.simpleimportproduct .supplier_id_method').hide();
    }

    if( $('select[name=manufacturer_method]').val() == 'manufacturer_name_method' ){
      $('.simpleimportproduct .manufacturer_id_method').hide();
      $('.simpleimportproduct .existing_manufacturer_method').hide();
    }
    else if( $('select[name=manufacturer_method]').val() == 'manufacturer_id_method' ){
      $('.simpleimportproduct .manufacturer_name_method').hide();
      $('.simpleimportproduct .existing_manufacturer_method').hide();
    }
    else {
      $('.simpleimportproduct .manufacturer_id_method').hide();
      $('.simpleimportproduct .manufacturer_name_method').hide();
    }



    $.each($('.panel.combinations'), function(i){
      var obj = $(this);

      var val = obj.find('#supplier_method_combination').val();

        if(val == 'supplier_name_method'){
          obj.find('.supplier_id_combination').hide();
          obj.find('.existing_supplier_combination').hide();
        }
        else if( val == 'supplier_ids_method' ){
          obj.find('.existing_supplier_combination').hide();
          obj.find('.supplier_name_combination').hide();
        }
        else{
          obj.find('.supplier_name_combination').hide();
          obj.find('.supplier_id_combination').hide();
        }
    });

  });

  $(document).on('change', '.simpleimportproduct select[name=supplier_method_combination]', function(){

    if($(this).val() == 'supplier_name_method'){
      $(this).parents('#fieldset_3_3').find('.supplier_id_combination').hide();
      $(this).parents('#fieldset_3_3').find('.existing_supplier_combination').hide();
      $(this).parents('#fieldset_3_3').find('.supplier_name_combination').show();
    }
    else if($(this).val() == 'supplier_ids_method'){
      $(this).parents('#fieldset_3_3').find('.supplier_id_combination').show();
      $(this).parents('#fieldset_3_3').find('.existing_supplier_combination').hide();
      $(this).parents('#fieldset_3_3').find('.supplier_name_combination').hide();
    }
    else{
      $(this).parents('#fieldset_3_3').find('.supplier_id_combination').hide();
      $(this).parents('#fieldset_3_3').find('.existing_supplier_combination').show();
      $(this).parents('#fieldset_3_3').find('.supplier_name_combination').hide();
    }
  });

  $(document).on('change', '.simpleimportproduct .format_file', function(){
    var format_file = $('.format_file').val();
    if(format_file == 'csv'){
      $('.step_1 .csv_delimiter').show();
    }
    else{
      $('.step_1 .csv_delimiter').hide();
    }
  });

  if( $('.format_file').val() == 'csv' ){
    $('.step_1 .csv_delimiter').show();
  }



  $(document).on('change', '.simpleimportproduct select[name=category_method]', function(){
    if($(this).val() == 'category_ids_method'){
      $('.simpleimportproduct .category_id_method').show();
      $('.simpleimportproduct .category_name_method').hide();
      $('.more_subcategory').show()
      $('.category_tree_method').hide()
    }
    else{
      $('.simpleimportproduct .category_id_method').hide();
      $('.simpleimportproduct .category_name_method').show();
      $('.more_subcategory').show();
      $('.category_tree_method').hide()
      if($(this).val() == 'category_tree_method'){
        $('.more_subcategory').hide();
        $('.category_tree_method').show();

        $.each($('.one_subcategory'), function(i){
          if(!$(this).hasClass('one_subcategory_1')){
            $(this).remove();
          }
        });

      }

    }

  })




  $(document).on('change', '.simpleimportproduct select[name=tax_method]', function(){
    if($(this).val() == 'tax_rate_method'){
      $('.simpleimportproduct .tax_rule_method').hide();
      $('.simpleimportproduct .existing_tax_method').hide();
      $('.simpleimportproduct .tax_rate_method').show();
    }
    else if( $(this).val() == 'existing_tax_method' ){
      $('.simpleimportproduct .tax_rule_method').hide();
      $('.simpleimportproduct .existing_tax_method').show();
      $('.simpleimportproduct .tax_rate_method').hide();
    }
    else{
      $('.simpleimportproduct .tax_rule_method').show();
      $('.simpleimportproduct .existing_tax_method').hide();
      $('.simpleimportproduct .tax_rate_method').hide();
    }
  })



  $(document).on('change', '.simpleimportproduct select[name=supplier_method]', function(){

    if($(this).val() == 'supplier_name_method'){
      $('.simpleimportproduct .supplier_id_method').hide();
      $('.simpleimportproduct .existing_supplier_method').hide();
      $('.simpleimportproduct .supplier_name_method').show();
    }
    else if( $(this).val() == 'supplier_ids_method' ){
      $('.simpleimportproduct .supplier_id_method').show();
      $('.simpleimportproduct .existing_supplier_method').hide();
      $('.simpleimportproduct .supplier_name_method').hide();
    }
    else{
      $('.simpleimportproduct .supplier_id_method').hide();
      $('.simpleimportproduct .existing_supplier_method').show();
      $('.simpleimportproduct .supplier_name_method').hide();
    }
  })



  $(document).on('change', '.simpleimportproduct select[name=manufacturer_method]', function(){
    if($(this).val() == 'manufacturer_name_method'){
      $('.simpleimportproduct .manufacturer_id_method').hide();
      $('.simpleimportproduct .existing_manufacturer_method').hide();
      $('.simpleimportproduct .manufacturer_name_method').show();
    }
    else if( $(this).val() == 'manufacturer_ids_method' ){
      $('.simpleimportproduct .manufacturer_id_method').show();
      $('.simpleimportproduct .manufacturer_name_method').hide();
      $('.simpleimportproduct .existing_manufacturer_method').hide();
    }
    else {
      $('.simpleimportproduct .manufacturer_id_method').hide();
      $('.simpleimportproduct .manufacturer_name_method').hide();
      $('.simpleimportproduct .existing_manufacturer_method').show();
    }
  });

  if( $('.simpleimportproduct .parser_import_val').val() == 'product_id' ){
    $('.simpleimportproduct .force_ids').show();
  }

  if( $('.simpleimportproduct select.feed_source').val() == 'file_url' ){
    $('.simpleimportproduct .file_import_select').hide();
    $('.simpleimportproduct .file_import_ftp').hide();
    $('.simpleimportproduct .file_import_url').show();
  }

  if( $('.simpleimportproduct select.feed_source').val() == 'ftp' ){
    $('.simpleimportproduct .file_import_select').hide();
    $('.simpleimportproduct .file_import_ftp').show();
    $('.simpleimportproduct .file_import_url').hide();
  }

  $(document).on('change', '.simpleimportproduct select.feed_source', function(){
    if($(this).val() == 'file_url'){
      $('.simpleimportproduct .file_import_select').hide();
      $('.simpleimportproduct .file_import_ftp').hide();
      $('.simpleimportproduct .file_import_url').show();
    }
    if($(this).val() == 'file_upload'){
      $('.simpleimportproduct .file_import_select').show();
      $('.simpleimportproduct .file_import_url').hide();
      $('.simpleimportproduct .file_import_ftp').hide();
    }
    if($(this).val() == 'ftp'){
      $('.simpleimportproduct .file_import_select').hide();
      $('.simpleimportproduct .file_import_url').hide();
      $('.simpleimportproduct .file_import_ftp').show();
    }
  });

  $(document).on('change', '.simpleimportproduct .parser_import_val', function(){
    if($(this).val() == 'product_id'){
      $('.simpleimportproduct .force_ids').show();
    }
    else {
      $('.simpleimportproduct .force_ids').hide();
    }
  });

  $(document).on('click', '.delete_suppliers', function(){
    $(this).parents('#fieldset_12_12').remove();
  });
  $(document).on('click', '.delete_image', function(){
    $(this).parents('#fieldset_6_6').remove();
  });
  $(document).on('click', '.delete_suppliers_combination', function(){
    $(this).parents('.full_combination_supplier_item').remove();
  });
  $(document).on('click', '.delete_price_condition', function(){
    $(this).parents('.price_settings_block').remove();
  });
  $(document).on('click', '.delete_field_condition', function(){
    addCustomFieldsAgain();
    $(this).parents('.field_settings_block').remove();
  });
  $(document).on('click', '.delete_quantity_condition', function(){
    $(this).parents('.quantity_settings_block').remove();
  });
  $(document).on('click', '.delete_combination', function(){
    $(this).parents('#fieldset_3_3').remove();
  });
  $(document).on('click', '.delete_discount', function(){
    $(this).parents('#fieldset_5_5').remove();
  });
  $(document).on('click', '.delete_featured', function(){
    $(this).parents('#fieldset_7_7').remove();
  });
  $(document).on('click', '.delete_customization', function(){
    $(this).parents('#fieldset_13_13').remove();
  });
  $(document).on('click', '.delete_attachments', function(){
    $(this).parents('#fieldset_14_14').remove();
  });
  $(document).on('change', 'input[name="has_discount"]', function(){
    hasDiscount()
  });
  $(document).on('change', 'input[name="has_featured"]', function(){
    hasFeatures()
  });
  $(document).on('change', 'input[name="has_accessories"]', function(){
    hasAccessories();
  });
  $(document).on('change', 'input[name="has_pack"]', function(){
    hasPackProducts();
  });
  $(document).on('change', 'input[name="save_config"]', function(){
    saveConfig();
  });

  $(document).on('change', 'select[name="combinations_import_type"]', function(){
    $(this).parents('#fieldset_3_3').find('.full_combination').show();
    $('.full_combination_supplier_item.additional').remove();

    if( $(this).val() != 'one_field_combinations' ){
      $(this).parents('#fieldset_3_3').find('.old_type').hide();
      if( $(this).val() == 'separated_field_value' ){
        $('.additional_combinations').remove();
        $('#fieldset_3_3').find('.full_combination').hide();
        $('#fieldset_3_3').find('.old_type').hide();
        $('#fieldset_3_3').find('.form-group-images').hide();
        $('#fieldset_3_3 select[name="combinations_import_type"]').val('separated_field_value');
        $('#fieldset_3_3 .combinations_import_type .chosen-single span').html($(this).find('option:selected').text());
        addAttribute($('#fieldset_3_3 select[name="combinations_import_type"]'));
      }
      else{
        addAttribute($(this));
        $('#fieldset_3_3').find('.form-group-images').show();
      }
    }
    else{
      $(this).parents('#fieldset_3_3').find('.single_attribute').remove();
      $(this).parents('#fieldset_3_3').find('.old_type').show();
      $(this).parents('#fieldset_3_3').find('.form-group-images').show();
    }

    if( $(this).val() == 'one_field_combinations' ){
      $(this).parents('#fieldset_3_3').find('.combinations_import_type .col-lg-9 .tutorial a').attr('href', 'http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-combination-in-one-field-method.html');
    }

    if( $(this).val() == 'single_field_value' ){
      $(this).parents('#fieldset_3_3').find('.combinations_import_type .col-lg-9 .tutorial a').attr('href', 'http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-each-attribute-and-value-in-separate-field-method.html');
    }

    if( $(this).val() == 'separate_combination_row' ){
      $(this).parents('#fieldset_3_3').find('.combinations_import_type .col-lg-9 .tutorial a').attr('href', 'http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-each-combination-in-separate-row-in-file-.html');
    }

    if( $(this).val() == 'separated_field_value' ){
      $('#fieldset_3_3').find('.combinations_import_type .col-lg-9 .tutorial a').attr('href', 'http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-generate-combinations-from-attribute-values-method.html');
    }

    $.each($('.panel.combinations'), function(i){
      var obj = $(this);

      var val = obj.find('#supplier_method_combination').val();

      if(val == 'supplier_name_method'){
        obj.find('.supplier_id_combination').hide();
        obj.find('.existing_supplier_combination').hide();
      }
      else if(val == 'supplier_ids_method'){
        obj.find('.supplier_name_combination').hide();
        obj.find('.existing_supplier_combination').hide();
      }
      else{
        obj.find('.supplier_name_combination').hide();
        obj.find('.supplier_id_combination').hide();
      }
    });
  });

  $.each($('.content_import_page .pre_defined'), function(i){
    $(this).find('.chosen-container').append('<div class="pre_defined_info"></div><div class="pre_defined_block"><div class="title">Pre-defined Values</div><div class="message">This field has pre-defined values that you can select for import</div></div>');
  });

  $( '.content_import_page .pre_defined_info' )
    .mouseover(function() {
      $( this ).next( ".pre_defined_block" ).fadeIn();
    })
    .mouseout(function() {
      $( this ).next( ".pre_defined_block" ).fadeOut();
    });

  $( '.import_type_task_panel .frequency .input-group-addon .time_block .icon' )
    .mouseover(function() {
      $( '.import_type_task_panel .help_info' ).fadeIn();
    })
    .mouseout(function() {
      $( '.import_type_task_panel .help_info' ).fadeOut();
    });

  $(document).on('change', 'select[name="single_attribute"]', function(){
    if( $(this).val() == 'enter_manually' ){
      $(this).parents('.single_attribute').next().show();
    } else{
      $(this).parents('.single_attribute').next().hide();
    }
  });

    if( $('select[name="single_type"]').val() == 'color' ){
      $(this).parents('.single_attribute').next().show();
    } else{
        $(this).parents('.single_attribute').next().hide();
        $(this).parents('.single_attribute').next().find("select option[value='no']").prop("selected", true).trigger("chosen:updated");
    }

  $(document).on('change', 'select[name="single_type"]', function(){
    if( $(this).val() == 'color' ){
        $(this).parents('.single_attribute').next().show();
      } else{
        $(this).parents('.single_attribute').next().hide();
        $(this).parents('.single_attribute').next().find("select option[value='no']").prop("selected", true).trigger("chosen:updated");
      }
  });

  $(document).on('change', 'select[name="features_name"]', function(){
    if( $(this).val() == 'enter_manually' ){
      $(this).parents('.form-group').next().show();
    }
    else{
      $(this).parents('.form-group').next().hide();
    }
  });

  if( $('select[name="features_name"]').val() == 'enter_manually' ){
    $('.content_import_page.step_2 #fieldset_7_7 .form-group.features_name_manually').show();
  }

  $(document).on('click', '.more_combination', function(){
    moreCombination($(this).parents('#fieldset_3_3'))
  });

  $(document).on('click', '.more_suppliers', function(){
    moreSuppliers($(this).parents('#fieldset_12_12'), 0)
  });

  $(document).on('click', '.more_suppliers_combination', function(){
    moreSuppliersCombination($(this).parents('.full_combination_supplier_item'))
  });

  $(document).on('click', '.add_price_condition', function(){
    addPriceCondition($(this).parents('.price_settings_block'))
  });

  $(document).on('click', '.add_field_condition', function(){
    addFieldCondition()
    addCustomFieldsAgain();
  });

  $(document).on('click', '.add_quantity_condition', function(){
    addQuantityCondition($(this).parents('.quantity_settings_block'))
  });

  $(document).on('keyup', '.setting_new_field input', function(){
    addCustomFieldsAgain();
  });

  $(document).on('click', '.add_attribute', function(){
    addAttribute($(this));
  });
  $(document).on('click', '.delete_attribute', function(){
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').prev().remove();
    $(this).parents('.single_attribute').remove();
  });

  // if( $('*').is('#combinations_import_type') ){
    if( $('#combinations_import_type').first().val() != 'one_field_combinations' && $('#combinations_import_type').length > 0 ){
      addAttribute($('#combinations_import_type').first(),0);
    }
  // }

    if( $('select[name="combinations_import_type"]').first().val() == 'separated_field_value' ){
      $('#fieldset_3_3 .old_type').hide();
      $('#fieldset_3_3 .full_combination').hide();
      $('#fieldset_3_3 .form-group-images').hide();
      $('#fieldset_3_3').first().find('.combinations_import_type .col-lg-9').append('<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-generate-combinations-from-attribute-values-method.html">Need Help?</a></div>');
    }
    if( $('select[name="combinations_import_type"]').first().val() == 'single_field_value' ){
      $('#fieldset_3_3').first().find('.old_type').hide();
      $('#fieldset_3_3').first().find('.combinations_import_type .col-lg-9').append('<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-each-attribute-and-value-in-separate-field-method.html">Need Help?</a></div>');
    }
  if( $('select[name="combinations_import_type"]').first().val() == 'separate_combination_row' ){
    $('#fieldset_3_3').first().find('.old_type').hide();
    $('#fieldset_3_3').first().find('.combinations_import_type .col-lg-9').append('<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-each-combination-in-separate-row-in-file-.html">Need Help?</a></div>');
  }
    if( $('#combinations_import_type').first().val() == 'one_field_combinations' ){
      $('#fieldset_3_3').first().find('.combinations_import_type .col-lg-9').append('<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/combinations-import-combination-in-one-field-method.html">Need Help?</a></div>');
    }

 $(document).on('click', '.more_discount', function(){
   moreDiscount($(this).parents('#fieldset_5_5'), $('#key_settings').val())
 });
  $(document).on('click', '.more_image', function(){
    moreImages($(this).parents('#fieldset_6_6'))
  });
  $(document).on('click', '.more_featured', function(){
    moreFeatures($(this).parents('#fieldset_7_7'))
  });

  $(document).on('click', '.more_customization', function(){
    moreCustomization($(this).parents('#fieldset_13_13'))
  });

  $(document).on('click', '.more_attachments', function(){
    moreAttachments($(this).parents('#fieldset_14_14'))
  });


  $(document).on('click', '.more_subcategory button', function(){
    moreSubcategory($(this).parent().attr('category'));
  });
  $(document).on('click', '.more_category button', function(){
    moreCategory();
  });
  $(document).on('click', '.more_image_comb button', function(){
    moreImagesCombination($(this).parent().prev());
  });

  $(document).on('click', '.delete_one_subcategory', function(){

    var val_count = $(this).parent().parent().find('.count_cat').html();
    var hidden_count_subcategory = parseInt($('.hidden_count_subcategory_'+val_count).val());
    $('.hidden_count_subcategory_'+val_count).val(hidden_count_subcategory - 1);

    $(this).parent().remove();
  });
  $(document).on('click', '.delete_one_category', function(){
    $(this).parent().remove();
  });

  $(document).on('click', '.delete_one_image', function(){
    $(this).parent().remove();
  });

  $(document).on('click', '.simpleimportproduct .button_import', function(){
    importProducts(0);
  });

  $(document).on('click', '.save_all_settings', function(){
    saveSettings();
  });

  $(document).on('click', '.delete_config', function(){
    removeSettings($(this).attr('settings'));
  });

  if($('#combinations_more').val() == 1 && ($('#key_settings').val())){
    moreSuppliers($('.content_import_page #fieldset_12_12').last(), $('#key_settings').val())
  }

  if($('#combinations_more').val() == 1 && ($('#key_settings').val())){
    addCombinations();
  }
  if($('#features_more').val() == 1 && ($('#key_settings').val())){
    addFeatures();
  }
  if($('#customization_more').val() == 1 && ($('#key_settings').val())){
    addCustomization();
  }
  if($('#attachments_more').val() == 1 && ($('#key_settings').val())){
    addAttachments();
  }
  if($('#discount_more').val() == 1 && ($('#key_settings').val())){
    addDiscount();
  }
  if($('#key_settings').val()){
    addCategories();
  }

  if($('#field_images').val() == 1 && ($('#key_settings').val())){
    addImages();
  }

  $(document).on('click', '.info_items .info_block .content .subscribe .error, .info_items .info_block .content .subscribe .success', function(){
    $(this).removeClass('error');
    $(this).removeClass('success');
    $(this).val('');
  });

  $(document).on('change', '.add_settings input', function(){
    if( $(this).val() ){
      $('.upload_settings').show();
      $(this).parent().addClass('hover');
    }
    else {
      $('.upload_settings').hide();
      $(this).parent().removeClass('hover');
    }
  });

  $(document).on('click', '.upload_settings', function(){
    var xlsxData = new FormData();
    xlsxData.append('file', $('.add_settings input')[0].files[0]);
    xlsxData.append('ajax', true);
    xlsxData.append('token', $('input[name=products_import_token]').val());
    xlsxData.append('controller', 'AdminProductsimport');
    xlsxData.append('action', 'uploadSettings');

    $.ajax({
      url: 'index.php',
      type: 'post',
      data: xlsxData,
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function(){
        $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
      },
      success: function(json) {
        $('.alert-danger, .alert-success').remove();
        if( !json ){
          $('.alert-danger, .alert-success').remove();
          $(".progres_bar_ex").remove();
          $(document).scrollTop(0);
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
        }
        if (json['error']) {
          $('.progres_bar_ex').remove();
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">' +  json['error'] + '</div>');
          $(document).scrollTop(0);
        }
        else if(json['success']){
          location.href = location.href;
        }
        else{
          $('.alert-danger, .alert-success').remove();
          $(".progres_bar_ex").remove();
          $(document).scrollTop(0);
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
        }

      },
      error: function(e){
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        if( e.responseText.indexOf('AdminProductsimport') > 0 ){
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
        }
        else {
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
        }
      }
    });
  });

  $(document).on('click', '.save_scroll .download_settings', function(){
    $.ajax({
      type: "POST",
      url: "index.php",
      dataType: 'json',
      data: {
        ajax	: true,
        token: $('input[name=products_import_token]').val(),
        controller: 'AdminProductsimport',
        action: 'downloadSettings',
        settings: $(this).attr('data-settings'),
      },
      success: function(json) {
        if(json['download']){
          location.href = json['download'];
        }
      }
    });
  });

  $(document).on('click', '.info_items .info_block .content .subscribe .send', function(){
    $('.info_items .info_block .content .subscribe input').removeClass('error');
    $('.info_items .info_block .content .subscribe input').removeClass('success');
    $.ajax({
      type: "POST",
      url: "index.php",
      dataType: 'json',
      data: {
        ajax	: true,
        token: $('input[name=products_import_token]').val(),
        controller: 'AdminProductsimport',
        action: 'subscribe',
        email: $('.info_items .info_block .content .subscribe input').val()
      },
      success: function(json) {
        if(json['success']){
          $('.info_items .info_block .content .subscribe input').val(json['success']);
          $('.info_items .info_block .content .subscribe input').addClass('success');
        }
        if(json['error']){
          $('.info_items .info_block .content .subscribe input').val(json['error']);
          $('.info_items .info_block .content .subscribe input').addClass('error');
        }
      }
    });
  });

  $(document).on('click', '.version_block .check_updates', function(){
    $('.version_block .module_version .last_version').html('---');
    $.ajax({
      type: "POST",
      url: "index.php",
      dataType: 'json',
      data: {
        ajax	: true,
        token: $('input[name=products_import_token]').val(),
        controller: 'AdminProductsimport',
        action: 'checkVersion',
      },
      success: function(json) {
        if(json['module_version']){
          $('.version_block .module_version .last_version').html(json['module_version']);
          var currentVersion = $.trim($('.version_block .module_version .current_version').html());
          if( json['module_version'] != currentVersion ){
            $('.version_block .update').css('display','inline-block');
            $('.version_block .version_ok').hide();
            $('.version_block .version_not_ok').show();
          }
        }
        if(json['error']){
          $('.version_block .module_version .last_version').html(json['error']);
        }
      }
    });
  });

  $(document).on('click', '.import_progress .back_to_settings', function(){
    $(".import_progress").hide();
    $("#configuration_form").show();
    $(".productTabs").show();
  });

  $(document).on('click', '.import_progress .re_import', function(){
    importProducts(0);
  });

  $(document).on('click', '.import_progress .stop', function(){
    $('.import_progress .import_info .status span').html('Stopping');
    stopImport = 1;
    returnAddProducts($('input[name=id_shop]').val(), 0, 3);
  });

  addHelpButtons();
  addFieldsLabel();



  $(document).on('click', '.field_settings .add_to_fieldlist', function(){
    var customFields = [];
    var error = false;
    $.each($('.field_settings .setting_new_field input'), function(i){
      if( $(this).val() ){
        customFields[i] = $(this).val();
      }
      else {
        error = true;
        importError.show($(this), importError.ERROR_TYPE_ERROR, 'Please enter Field Name!');
      }
    });

    if( !error ){
      saveSettings();
    }
  });

  if( $('.import_settings_name').length > 0 ){
    $('.search_settings_block').appendTo('.import_settings_name .col-lg-9');

    $(document).on('click', 'body', function(event){
      if( $('.search_settings_block .saved_settings').is(':visible') && !$(event.target).hasClass('icon') ){
        $('.search_settings_block .saved_settings').hide();
      }
    });
  }

  $(document).on('change', '.import_type_task_panel .email_notification input', function(){
    if( $(this).val() == 1 ){
      $('.import_type_task_panel .emails_form').show();
    }
    else {
      $('.import_type_task_panel .emails_form').hide();
    }
  });

  if( $('.import_type_task_panel').length > 0 ){
    if( $('.import_type_task_panel .email_notification input:checked').val() == 1 ){
      $('.import_type_task_panel .emails_form').show();
    }
  }

  $(document).on('click', '.search_settings_block .icon', function(){
    $('.search_settings_block .saved_settings').show();
  });

  $(document).on('keyup', '.import_type_task_panel #frequency', function(){
    var expression = $(this).val();
    if( !expression ){
      $('.import_type_task_panel .frequency').addClass('error');
    }
    else {
      $.ajax({
        type: "POST",
        url: "index.php",
        dataType: 'json',
        data: {
          ajax	: true,
          token: $('input[name=products_import_token]').val(),
          controller: 'AdminProductsimport',
          action: 'checkExpression',
          expression : expression
        },
        success: function(json) {
          if(json['human_description']){
            $('.frequency_description .description_text').html('«' + json['human_description'] + "»");
            $('.import_type_task_panel .frequency').removeClass('error');
          }
          if( json['next_run'] ){
            $('.import_type_task_panel .frequency .input-group-addon .time').html( json['next_run'] );
          }
          if( json['expression'] ){
            $('.frequency_description .minutes .value').html(json['expression']['min']);
            $('.frequency_description .hours .value').html(json['expression']['hour']);
            $('.frequency_description .day_of_month .value').html(json['expression']['day_of_month']);
            $('.frequency_description .month .value').html(json['expression']['month']);
            $('.frequency_description .day_of_week .value').html(json['expression']['day_of_week']);
          }
          if(json['error']){
            $('.import_type_task_panel .frequency').addClass('error');
          }
        }
      });
    }
  });

  if( $('.import_type_task_panel .frequency').length > 0 ){
    $('.import_type_task_panel #frequency').trigger('keyup');
  }

  $(document).on('click', '.field_list_block .field_list .copy', function(){
    copyToClipboard( $(this).attr('data-field') );
    $('.field_list_block .field_list .copy').removeClass('copied');
    $(this).addClass('copied');
    $('.field_list_block .field_list .copy').html( $(this).attr('data-copy') );
    $(this).html( $(this).attr('data-copied') );
  });

  $(document).on('click', '.field_list_block .description .hide_fields', function(){
    if( $(this).hasClass('show_fields') ){
      $(this).removeClass('show_fields');
      $('.field_list_block .field_list').slideDown();
    }
    else {
      $('.field_list_block .field_list').slideUp();
      $(this).addClass('show_fields');
    }
  });

  $(document).on('focus', '.field_list_block .description .search_field input', function(){
    $(this).parent().addClass('active');
  });

  $(document).on('focusout', '.field_list_block .description .search_field input', function(){
    $(this).parent().removeClass('active');
  });

  $(document).on('keyup', '.field_list_block .description .search_field input', function(){
    $('.field_list_block .field_list .field_name').show();
    $('.field_list_block .field_list .copy_block').show();
    var search = $(this).val();
    if( search ){
      $.each($('.field_list_block .field_list .field_name'), function(i){
        if($(this).html().toLowerCase().indexOf(search.toLowerCase()) === -1){
          $('.field_list_block .field_list .copy_block').eq(i).hide();
          $(this).hide();
        }
      })
    }
  });

  $('.content_import_page .custom_fields_block').sortable({
    handle: ".move",
    revert:false,
    axis: "y"
  });

});


function addCustomFieldsAgain() {
  $('.add_to_fieldlist').show();
  $('.custom_fields_added').hide();
}

function addFieldsLabel() {

  $.each($('.pre_defined select'), function(i){
    $.each($(this).find('option'), function(i){
      if($(this).val().indexOf('{pre_saved}') !== -1){
        $(this).attr('class', 'pre_saved');
        $(this).parent().trigger("chosen:updated");
      }
    });
  });

  var savedCustomFields = [];
  $.each($('.field_settings .setting_new_field input'), function(i){
    if( $(this).val() ){
      savedCustomFields[i] = $(this).val();
    }
  });

  $.each($('.content_import_page select'), function(i){
    $.each($(this).find('option'), function(i){
      if( $.inArray( $(this).val(), savedCustomFields ) != -1 ){
        $(this).attr('class', 'custom_field');
        $(this).parent().trigger("chosen:updated");
      }
    });
  });
}


function addHelpButtons() {
  $('.content_import_page .categories_import_method .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/categories-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .remove_images .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/images-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .file_url .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/virtual-products-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .has_accessories .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/accessories-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .remove_pack_products .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/pack-products-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .remove_attachments .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/attachments-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .remove_customization .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/customization-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .remove_features .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/features-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .has_discount .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/specific-prices-import.html">Need Help?</a></div>'
  );

  $('.content_import_page .import_type .col-lg-9').append(
    '<div class="tutorial"><a class="need_help" target="_blank" href="http://faq.myprestamodules.com/product-catalog-csv-excel-import/automatic-import.html">Need Help?</a></div>'
  );
}

function addAttribute( object, load_settings ){
  if( !load_settings && load_settings != 0 ){
    load_settings = false;
  }


  $.ajax({
    type: "POST",
    url: "index.php",
    dataType: 'json',
    data: {
      ajax	: true,
      token: $('input[name=products_import_token]').val(),
      controller: 'AdminProductsimport',
      action: 'addAttribute',
      id_shop:$('input[name=id_shop]').val(),
      id_lang:$('input[name=id_lang]').val(),
      key_settings:$('#key_settings').val(),
      load_settings:load_settings
    },
    success: function(json) {
      if( json.attribute ){
        if( object.hasClass('add_attribute') ){
          object = object.parents('#fieldset_3_3');
          object.find('.single_attribute').last().after(json.attribute);
        }
        else{
          object = object.parents('#fieldset_3_3');
          if( object.find('.single_attribute').length > 0 ){
            object.find('.single_attribute').remove();
          }
          object.find('.combinations_import_type').after(json.attribute);
        }
        object.find('.chosen').chosen();
        object.find('.label-tooltip').tooltip();

        if( object.find('.single_attribute').length > 4 ){
          $('.delete_attribute').show();
          $('.delete_attribute').first().hide();
        }
        if( object.find('#combinations_import_type').val() == 'separated_field_value' ){
          object.find('.single_delimiter').show();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addCategories(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addCategories=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_0 .form-group-categories .col-lg-9').replaceWith(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addCombinations(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addCombinations=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_3_3').last().after(json['page']);
        $.each($('.panel.combinations'), function(i){
          var obj = $(this);

          var val = obj.find('#supplier_method_combination').val();

          if(val == 'supplier_name_method'){
            obj.find('.supplier_id_combination').hide();
            obj.find('.existing_supplier_combination').hide();
          }
          else if( val == 'supplier_ids_method' ){
            obj.find('.supplier_name_combination').hide();
            obj.find('.existing_supplier_combination').hide();
          }
          else{
            obj.find('.supplier_name_combination').hide();
            obj.find('.supplier_id_combination').hide();
          }
        });
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addAttachments(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addAttachments=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_14_14').last().after(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addCustomization(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addCustomization=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_13_13').last().after(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}
function addImages(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addImages=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_6_6').last().after(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addFeatures(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addFeatures=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_7_7').last().after(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addDiscount(){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addDiscount=true&ajax=true&key_settings='+$('#key_settings').val()+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if (json['page']) {
        $('.content_import_page #fieldset_5_5').last().after(json['page']);
        addFieldsLabel();
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreImagesCombination(obj){
  var hidden_count_images = parseInt(obj.find('.count_img').html()) + 1;

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreImagesCombination=true&ajax=true&hidden_count_images='+hidden_count_images+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          obj.after(json['page']);
          $('.chosen').chosen();
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}
function moreCategory(){
  var hidden_count_category = parseInt($('.one_category_block').last().find('.count_cat').html()) + 1;

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreCategory=true&ajax=true&hidden_count_category='+hidden_count_category+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          var block = hidden_count_category-1;
          $('.one_category_block_'+block).after(json['page']);

          if($('.simpleimportproduct select[name=category_method]').val() == 'category_tree_method' ){
            $('.more_subcategory').hide()
          }
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreSubcategory(category){
  var hidden_count_subcategory = parseInt($('.hidden_count_subcategory_'+category).val()) + 1;
  $('.hidden_count_subcategory_'+category).val(hidden_count_subcategory);
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreSubcategory=true&ajax=true&hidden_count_subcategory='+hidden_count_subcategory+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          var block = parseInt(hidden_count_subcategory)-1;
          $('.one_category_block_'+category+' .one_subcategory').last().after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreAttachments(afterBlock)
{
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreAttachments=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreCustomization(afterBlock)
{
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreCustomization=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}


function moreFeatures(afterBlock){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreFeatures=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreImages(afterBlock){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreImages=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}
function moreDiscount(afterBlock, key){
 $.ajax({
   url: '../modules/simpleimportproduct/send.php',
   type: 'post',
   data: 'moreDiscount=true&ajax=true&key='+key+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
   dataType: 'json',
   beforeSend: function(){
     $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
   },
   complete: function(){
     $(".progres_bar_ex").remove();
   },
   success: function(json) {
     $('.alert-danger, .alert-success').remove();
     if (json['error']) {
       $(document).scrollTop(0);
       $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
     }
     else {
       if (json['page']) {
         afterBlock.after(json['page']);
         changeDiscount();
         addFieldsLabel();
       }
     }
   }
 });
}

function addQuantityCondition( afterBlock ) {
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addQuantityCondition=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addFieldCondition()
{
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addFieldCondition=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          $('.field_settings_block').last().after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function addPriceCondition( afterBlock ) {
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'addPriceCondition=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function moreSuppliersCombination(afterBlock){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreSuppliersCombination=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);

          var val =  afterBlock.parents('#fieldset_3_3').find('#supplier_method_combination').val();

          if(val == 'supplier_name_method'){
            afterBlock.next().find('.supplier_id_combination').hide();
            afterBlock.next().find('.existing_supplier_combination').hide();
          }
          else if( val == 'supplier_ids_method' ){
            afterBlock.next().find('.supplier_name_combination').hide();
            afterBlock.next().find('.existing_supplier_combination').hide();
          }
          else{
            afterBlock.next().find('.supplier_name_combination').hide();
            afterBlock.next().find('.supplier_id_combination').hide();
          }
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}





function moreSuppliers(afterBlock, key){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreSuppliers=true&ajax=true&key_settings='+key+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          // if(afterBlock){
          //   afterBlock.after(json['page']);
          // }
          // else{
          //   $('.content_import_page #fieldset_12_12').last().after(json['page']);
          // }

          if( $('select[name=supplier_method]').val() == 'supplier_name_method' ){
            $('.simpleimportproduct .supplier_id_method').hide();
            $('.simpleimportproduct .existing_supplier_method').hide();
          }
          else if( $('select[name=supplier_method]').val() == 'supplier_ids_method' ){
            $('.simpleimportproduct .supplier_name_method').hide();
            $('.simpleimportproduct .existing_supplier_method').hide();
          }
          else{
            $('.simpleimportproduct .supplier_name_method').hide();
            $('.simpleimportproduct .supplier_id_method').hide();
          }
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}


function moreCombination(afterBlock){
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'moreCombination=true&ajax=true&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_shop='+$('input[name=id_shop]').val()+'&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    complete: function(){
      $(".progres_bar_ex").remove();
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $('#bootstrap_products_import').append('<div class="alert alert-danger">' + json['error'] + '</div>');
      }
      else {
        if (json['page']) {
          afterBlock.after(json['page']);
          $.each($('.panel.combinations'), function(i){
            var obj = $(this);
            var val = obj.find('#supplier_method_combination').val();
            if(val == 'supplier_name_method'){
              obj.find('.supplier_id_combination').hide();
              obj.find('.existing_supplier_combination').hide();
            }
            else if(val == 'supplier_ids_method'){
              obj.find('.supplier_name_combination').hide();
              obj.find('.existing_supplier_combination').hide();
            }
            else {
              obj.find('.supplier_id_combination').hide();
              obj.find('.supplier_name_combination').hide();
            }
          });
          addFieldsLabel();
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function copyToClipboard( text ) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(text).select();
  document.execCommand("copy");
  $temp.remove();
}


function saveConfig(){
  var active = $('input[name="save_config"]:checked').val();
  if(active == '1'){
    $('.content_import_page #fieldset_18_18').show();
  }
  else{
    $('.content_import_page #fieldset_18_18').hide();
  }
}
function hasCombination(){
  var active = $('input[name="has_combination"]:checked').val();
  if(active == '1'){
    $('.content_import_page #fieldset_3_3').show();
  }
  else{
    $('.content_import_page #fieldset_3_3').hide();
  }
}
function hasDiscount(){
  var active = $('input[name="has_discount"]:checked').val();
  var active_tab = $('.import_tabs .import_tab.active').attr('data-tab');
  if(active == '1' && active_tab == 'prices'){
    $('.content_import_page #fieldset_5_5').show();
  }
  else{
    $('.content_import_page #fieldset_5_5').hide();
  }
}
function hasFeatures(){
  var active = $('input[name="has_featured"]:checked').val();
  if(active == '1'){
    $('.content_import_page #fieldset_7_7').show();
  }
  else{
    $('.content_import_page #fieldset_7_7').hide();
  }
}

function hasAccessories(){
  var active = $('input[name="has_accessories"]:checked').val();
  var active_tab = $('.import_tabs .import_tab.active').attr('data-tab');
  if(active == '1' && active_tab == 'associations'){
    $('.content_import_page #fieldset_9_9').show();
  }
  else{
    $('.content_import_page #fieldset_9_9').hide();
  }
}

function hasPackProducts(){
  var active = $('input[name="has_pack"]:checked').val();
  if(active == '1'){
    $('.content_import_page #fieldset_11_11').show();
  }
  else{
    $('.content_import_page #fieldset_11_11').hide();
  }
}

function stepTwoImportFromUrl()
{
  $.ajax({
    type: "POST",
    url: "index.php",
    dataType: 'json',
    data: {
      ajax	: true,
      token: $('input[name=products_import_token]').val(),
      controller: 'AdminProductsimport',
      action: 'loadFile',
      id_shop:$('input[name=id_shop]').val(),
      id_lang:$('select[name=id_lang]').val(),
      id_shop_group:$('input[name=id_shop_group]').val(),
      format_file:$('.format_file').val(),
      delimiter_val:$('.delimiter_val').val(),
      import_type_val:$('#import_type_val').val(),
      parser_import_val:$('.parser_import_val').val(),
      use_headers:$('input[name=use_headers]:checked').val(),
      disable_hooks:$('input[name=disable_hooks]:checked').val(),
      search_index:$('input[name=search_index]:checked').val(),
      products_range:$('input[name=products_range]:checked').val(),
      from_range:$('input[name=from_range]').val(),
      to_range:$('input[name=to_range]').val(),
      force_ids:$('input[name=force_ids]:checked').val(),
      iteration:$('#iteration').val(),
      file_url:$('input[name=file_import_url]').val(),
      feed_source:$('select[name=feed_source]').val(),
      file_import_ftp_server:$('input[name=file_import_ftp_server]').val(),
      file_import_ftp_user:$('input[name=file_import_ftp_user]').val(),
      file_import_ftp_password:$('input[name=file_import_ftp_password]').val(),
      file_import_ftp_file_path:$('input[name=file_import_ftp_file_path]').val(),
      import_settings_name:$('input[name=import_settings_name]').val(),
      setting_id:$('input[name=setting_id]').val(),
    },
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if( !json ){
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
      if (json['error']) {
        $('.progres_bar_ex').remove();
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">' +  json['error'] + '</div>');
        $(document).scrollTop(0);
      }
      else if(json['page']){
        location.href = $('#location_href').val()+'&module_tab=step_2';
      }
      else{
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function stepTwoImport(){

  var xlsxData = new FormData();
  xlsxData.append('file', $('input[name=file_import]')[0].files[0]);
  xlsxData.append('stepTwo', true);
  xlsxData.append('ajax', true);
  xlsxData.append('id_lang', $('.id_lang').val());
  xlsxData.append('id_shop', $('input[name="id_shop"]').val());
  xlsxData.append('id_shop_group', $('input[name="id_shop_group"]').val());
  xlsxData.append('format_file', $('.format_file').val());
  xlsxData.append('delimiter_val', $('.delimiter_val').val());
  xlsxData.append('import_type_val', $('#import_type_val').val());
  xlsxData.append('parser_import_val', $('.parser_import_val').val());
  xlsxData.append('use_headers', $('input[name=use_headers]:checked').val());
  xlsxData.append('disable_hooks', $('input[name=disable_hooks]:checked').val());
  xlsxData.append('search_index', $('input[name=search_index]:checked').val());
  xlsxData.append('products_range', $('input[name=products_range]:checked').val());
  xlsxData.append('from_range', $('input[name=from_range]').val());
  xlsxData.append('to_range', $('input[name=to_range]').val());
  xlsxData.append('force_ids', $('input[name=force_ids]:checked').val());
  xlsxData.append('iteration', $('#iteration').val());
  xlsxData.append('feed_source', $('select[name=feed_source]').val());
  xlsxData.append('token', $('input[name=products_import_token]').val());
  xlsxData.append('import_settings_name', $('input[name=import_settings_name]').val());
  xlsxData.append('setting_id', $('input[name=setting_id]').val());
  xlsxData.append('controller', 'AdminProductsimport');
  xlsxData.append('action', 'send');

    $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: xlsxData,
    dataType: 'json',
    processData: false,
    contentType: false,
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if( !json ){
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
      }
      if (json['error']) {
        $('.progres_bar_ex').remove();
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">' +  json['error'] + '</div>');
        $(document).scrollTop(0);
      }
      else if(json['page']){
        location.href = $('#location_href').val()+'&module_tab=step_2';
      }
      else{
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
      }

    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/simpleimportproduct/error/error.log" target="_blank">error.log</a> file or contact us!</div>');
      }
    }
  });
}

function saveSettings(){
  $('input[name="settings_save"]').css('border-color', '#ccc');
  var id_shop = $('input[name="id_shop"]').val();
  var settings_save = $('input[name="settings_save"]').val();
  var key_settings = $('input[name="key_settings"]').val();

  var data = '';
  if(!settings_save){
    $('input[name="settings_save"]').css('border-color', 'red');
    return false;
  }
  $.each($('.simpleimportproduct #fieldset_0 .form-group select'), function( key, value){
    if(value.name !== 'category' && $(this).parents('.price_settings_block').length == 0 && $(this).parents('.quantity_settings_block').length == 0 && $(this).parents('.field_settings_block').length == 0 ){
      data += '&field['+value.name +']=' + encodeURIComponent( value.value );
    }
  });

  data += '&field[remove_categories]='+ encodeURIComponent($("input[name='remove_categories']:checked").val());
  data += '&field[remove_suppliers]='+ encodeURIComponent($("input[name='remove_suppliers']:checked").val());
  data += '&field[disable_zero_products]='+ encodeURIComponent($("input[name='disable_zero_products']:checked").val());

  $.each($('.simpleimportproduct #fieldset_0 .from_categories input').serializeArray(), function( key, value){
    data += '&import_from_categories['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .from_suppliers input').serializeArray(), function( key, value){
    data += '&import_from_suppliers['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .from_brands input').serializeArray(), function( key, value){
    data += '&import_from_brands['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .field_settings_block'), function( i ){
    $.each($(this).find('.form-group-field-settings select'), function( key, value){
      data += '&field_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-field-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&field_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_0 .price_settings_block'), function( i ){
    $.each($(this).find('.form-group-price-settings select'), function( key, value){
      data += '&price_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-price-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&price_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_0 .quantity_settings_block'), function( i ){
    $.each($(this).find('.form-group-quantity-settings select'), function( key, value){
      data += '&quantity_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-quantity-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&quantity_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_1_1 .form-group select').serializeArray(), function( key, value){
    if(value.name !== 'category'){
      data += '&field['+value.name +']=' + encodeURIComponent( value.value );
    }
  });

  $.each($('.simpleimportproduct #fieldset_0 .form-group .one_category_block'), function(i){
    $.each($(this).find('.one_subcategory'), function(j){
      data += '&field_category['+i+'][]='+ encodeURIComponent( $(this).find('#category').val() );
    });
  });

  data += MpmImportCategoryLinking.addToRequestData();

  // $.each($('.simpleimportproduct #fieldset_3_3 .form-group .one_image_block'), function(i){
  //     data += '&field_image_combination[]='+ $(this).find('#images').val();
  // });


  $.each($('.simpleimportproduct #fieldset_3_3'), function(i){
    var singleIndex = 0;
    var singleIndexTmp = 0;

    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').val() != undefined  || $(this).hasClass('manually_attribute_name') ){
        if( $(this).hasClass('single_attribute') && !$(this).hasClass('manually_attribute_name') ){
          data += '&field_combinations['+i+']['+$(this).find('select').attr('name')+']['+singleIndex+']='+ encodeURIComponent( $(this).find('select').val() );
          singleIndexTmp++;
          if( singleIndexTmp == 5 ){
            singleIndexTmp = 0;
            singleIndex++;
          }
        }
        else if( $(this).hasClass('manually_attribute_name') ){
          data += '&field_combinations['+i+']['+$(this).find('input').attr('name')+']['+singleIndex+']='+ encodeURIComponent( $(this).find('input').val() );
        }
        else if( $(this).hasClass('full_combination_suppliers') ){
          $.each($(this).find('.full_combination_supplier_item'), function(j){
            $.each($(this).find('.form-group-sup'), function(){
              data += '&field_combinations['+i+'][suppliers]['+j+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
            })
          })
        }
        else if( $(this).hasClass('images_combination') ){
          $.each($(this).find('.one_image_block '), function(k){
            data += '&field_combinations['+i+'][images]['+k+']='+ encodeURIComponent( $(this).find('select').val() );
          })
        }
        else{
          data += '&field_combinations['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
      }
      if( $(this).find('input[name=remove_combinations]').length > 0 ){
        data += '&field_combinations['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });

  });


  if($('input[name="has_discount"]:checked').val() == true){
    $.each($('.simpleimportproduct #fieldset_5_5'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_discount['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input').length > 0 ){
          data += '&field_discount['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
      });
    });
  }
    $.each($('.simpleimportproduct #fieldset_7_7'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_featured['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input[type=radio]').length > 0 ){
          if( $(this).find('input').attr('name') == undefined ){
            return;
          }
          data += '&field_featured['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
        if( $(this).find('input[type=text]').length > 0 ){
          if( $(this).find('input').attr('name') == undefined ){
            return;
          }
          data += '&field_featured['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input').val() );
        }
      });
    });

  $.each($('.simpleimportproduct #fieldset_13_13'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_customization['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_customization['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_14_14'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_attachments['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_attachments['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });


  $.each($('.simpleimportproduct #fieldset_12_12'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_suppliers['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_suppliers['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_6_6'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_images['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_images['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });


  if($('input[name="has_accessories"]:checked').val() == true){
    $.each($('.simpleimportproduct #fieldset_9_9'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_accessories['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input').length > 0 ){
          if( $(this).find('input').attr('name') == undefined ){
            return;
          }
          data += '&field_accessories['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
      });
    });
  }

    $.each($('.simpleimportproduct #fieldset_11_11'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_pack_products['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input').length > 0 ){
          if( $(this).find('input').attr('name') == undefined ){
            return;
          }
          data += '&field_pack_products['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
      });
    });

  data += '&id_shop='+id_shop;
  data += '&name_save='+ encodeURIComponent( settings_save );
  data += '&key_settings='+key_settings;
  data += '&id_shop_group='+$('input[name="id_shop_group"]').val();

  if( $('textarea[name=notification_emails]').length > 0 ){
    data += '&notification_emails='+$('textarea[name="notification_emails"]').val();
  }

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'save=true&ajax=true'+data+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {

      $('.alert-danger, .alert-success').remove();
      if( !json ){
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
      if (json['error']) {
        if( json['field_key'] || json['field_key'] == 0 ){
          $('.import_tab[data-tab=additional_settings]').trigger('click');
          $('.progres_bar_ex').remove();
          $(document).scrollTop(0);
          importError.show($('.field_settings .setting_new_field input')[json['field_key']], importError.ERROR_TYPE_ERROR, json['error']);
        }
        else {
          $(document).scrollTop(0);
          $('.progres_bar_ex').remove();
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">' +  json['error'] + '</div>');
        }
      }
      else {
        if (json['success']) {
          $('.panel-heading-gomakoil').before('<div class="alert alert-success">' +  json['success'] + '</div>');
          $('.progres_bar_ex').remove();
          $(document).scrollTop(0);
           location.href = $('#location_href').val()+'&module_tab=step_2&save='+json['count_settings'];
        }
        else{
          $('.alert-danger, .alert-success').remove();
          $(".progres_bar_ex").remove();
          $(document).scrollTop(0);
          $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}
function removeSettings(key){

  var id_shop = $('input[name="id_shop"]').val();
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'remove=true&ajax=true&key='+key+'&id_shop='+id_shop+'&id_shop_group='+$('input[name="id_shop_group"]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
    },
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['error']) {
        $(document).scrollTop(0);
        $(".progres_bar_ex").remove();
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">' +  json['error'] + '</div>');
      }
      else {
        if (json['success']) {
          $('.panel-heading-gomakoil').before('<div class="alert alert-success">' +  json['success'] + '</div>');
          $('.delete_config[settings='+key+']').parent().remove();
          $(".progres_bar_ex").remove();
          // location.href = $('#location_href').val()+'&step=2';
        }
      }
    },
    error: function(e){
      $('.alert-danger, .alert-success').remove();
      $(".progres_bar_ex").remove();
      $(document).scrollTop(0);
      if( e.responseText.indexOf('AdminProductsimport') > 0 ){
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during import process!</div>');
      }
      else {
        $('.panel-heading-gomakoil').before('<div class="alert alert-danger">Some error occurred please contact us!</div>');
      }
    }
  });
}

function importImages() {
  var id_shop = $('input[name="id_shop"]').val();
  if( stopImport ){
    return false;
  }
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'copyImages=true&ajax=true&id_shop='+id_shop+'&id_shop_group='+$('input[name="id_shop_group"]').val(),
    dataType: 'json',
    success: function(json) {
      if( json['success'] ){
        if ( json['need_copy'] ) {
          importImages();
        }
      }
      else {
        importImages();
      }
    },
    error: function(e){
      importImages();
    }
  });
}

var refreshIntervalId = false;
var errorsCount = 0;
var stopImport = 0;
var startImportRunning = 0;
var startCopyImages = 0;

function importProducts(limit){

  var id_shop = $('input[name="id_shop"]').val();
  var data = '';

  if( limit == 0 ){
    $(document).scrollTop(0);
    stopImport = 0;
    startImportRunning = 1;
    errorsCount = 0;
    startCopyImages = 0;
    // refreshIntervalId = setInterval(function(){ returnAddProducts(id_shop); }, 3000);
    returnAddProducts(id_shop);
  }

  $.each($('.simpleimportproduct #fieldset_0 .form-group select'), function( key, value){
    if(value.name !== 'category' && $(this).parents('.price_settings_block').length == 0 && $(this).parents('.quantity_settings_block').length == 0 && $(this).parents('.field_settings_block').length == 0 ){
      data += '&field['+value.name +']=' + encodeURIComponent( value.value );
    }
  });

  data += '&field[remove_categories]='+ encodeURIComponent($("input[name='remove_categories']:checked").val());
  data += '&field[remove_suppliers]='+ encodeURIComponent($("input[name='remove_suppliers']:checked").val());
  data += '&field[disable_zero_products]='+ encodeURIComponent($("input[name='disable_zero_products']:checked").val());

  $.each($('.simpleimportproduct #fieldset_0 .from_categories input').serializeArray(), function( key, value){
    data += '&import_from_categories['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .from_suppliers input').serializeArray(), function( key, value){
    data += '&import_from_suppliers['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .from_brands input').serializeArray(), function( key, value){
    data += '&import_from_brands['+key+']=' + encodeURIComponent( value.value );
  });

  $.each($('.simpleimportproduct #fieldset_0 .field_settings_block'), function( i ){
    $.each($(this).find('.form-group-field-settings select'), function( key, value){
      data += '&field_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-field-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&field_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_0 .price_settings_block'), function( i ){
    $.each($(this).find('.form-group-price-settings select'), function( key, value){
      data += '&price_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-price-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&price_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_0 .quantity_settings_block'), function( i ){
    $.each($(this).find('.form-group-quantity-settings select'), function( key, value){
      data += '&quantity_settings['+i+']['+value.name+']=' + encodeURIComponent( value.value );
    });

    $.each($(this).find('.form-group-quantity-settings input'), function( key, value){
      if( $(this).attr('name') ){
        data += '&quantity_settings['+i+']['+$(this).attr('name')+']=' + encodeURIComponent( $(this).val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_1_1 .form-group select').serializeArray(), function( key, value){
    if(value.name !== 'category'){
      data += '&field['+value.name +']=' + encodeURIComponent( value.value );
    }
  });
  $.each($('.simpleimportproduct #fieldset_0 .form-group .one_category_block'), function(i){
    $.each($(this).find('.one_subcategory'), function(j){
      data += '&field_category['+i+'][]='+ encodeURIComponent( $(this).find('#category').val() );
    });
  });

  data += MpmImportCategoryLinking.addToRequestData();

  $.each($('.simpleimportproduct #fieldset_3_3'), function(i){
    var singleIndex = 0;
    var singleIndexTmp = 0;

    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').val() != undefined  || $(this).hasClass('manually_attribute_name') ){
        if( $(this).hasClass('single_attribute') && !$(this).hasClass('manually_attribute_name') ){
          data += '&field_combinations['+i+']['+$(this).find('select').attr('name')+']['+singleIndex+']='+ encodeURIComponent( $(this).find('select').val() );
          singleIndexTmp++;
          if( singleIndexTmp == 5 ){
            singleIndexTmp = 0;
            singleIndex++;
          }
        }
        else if( $(this).hasClass('manually_attribute_name') ){
          data += '&field_combinations['+i+']['+$(this).find('input').attr('name')+']['+singleIndex+']='+ encodeURIComponent( $(this).find('input').val() );
        }
        else if( $(this).hasClass('full_combination_suppliers') ){
          $.each($(this).find('.full_combination_supplier_item'), function(j){
            $.each($(this).find('.form-group-sup'), function(){
              data += '&field_combinations['+i+'][suppliers]['+j+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
            })
          })
        }
        else if( $(this).hasClass('images_combination') ){
          $.each($(this).find('.one_image_block '), function(k){
            data += '&field_combinations['+i+'][images]['+k+']='+ encodeURIComponent( $(this).find('select').val() );
          })
        }
        else{
          data += '&field_combinations['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
      }

      if( $(this).find('input[name=remove_combinations]').length > 0 ){
        data += '&field_combinations['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  if($('input[name="has_discount"]:checked').val() == true){
    $.each($('.simpleimportproduct #fieldset_5_5'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_discount['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input').length > 0 ){
          data += '&field_discount['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
      });
    });
  }
  $.each($('.simpleimportproduct #fieldset_7_7'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_featured['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input[type=radio]').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_featured['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
      if( $(this).find('input[type=text]').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_featured['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input').val() );
      }
    });
  });
  $.each($('.simpleimportproduct #fieldset_12_12'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_suppliers['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_suppliers['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });
  $.each($('.simpleimportproduct #fieldset_13_13'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_customization['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_customization['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_14_14'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_attachments['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_attachments['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  if($('input[name="has_accessories"]:checked').val() == true){
    $.each($('.simpleimportproduct #fieldset_9_9'), function(i){
      $.each($(this).find('.form-group'), function(j){
        if( $(this).find('select').length > 0 ){
          data += '&field_accessories['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
        }
        if( $(this).find('input').length > 0 ){
          data += '&field_accessories['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
        }
      });
    });
  }

  $.each($('.simpleimportproduct #fieldset_6_6'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_images['+i+']['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_images['+i+']['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  $.each($('.simpleimportproduct #fieldset_11_11'), function(i){
    $.each($(this).find('.form-group'), function(j){
      if( $(this).find('select').length > 0 ){
        data += '&field_pack_products['+$(this).find('select').attr('name')+']='+ encodeURIComponent( $(this).find('select').val() );
      }
      if( $(this).find('input').length > 0 ){
        if( $(this).find('input').attr('name') == undefined ){
          return;
        }
        data += '&field_pack_products['+$(this).find('input').attr('name')+']='+ encodeURIComponent( $(this).find('input:checked').val() );
      }
    });
  });

  data += '&id_shop='+id_shop;
  data += '&limit='+limit;
  data += '&id_shop_group='+$('input[name="id_shop_group"]').val();

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'import=true&ajax=true'+data+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    beforeSend: function(){
      if( $(".import_progress").is(':hidden') ){
        $(".import_progress").show();
        $("#configuration_form").hide();
        $(".productTabs").hide();
      }
    },
    complete: function(){

    },
    success: function(json) {

      if( stopImport ){
        returnAddProducts($('input[name=id_shop]').val(),0,2);
        return true;
      }
      if( !json ){
        if( limit == 0 ){
          returnAddProducts(id_shop,1,1);
          return true;
        }
        if( limit != 0 && errorsCount < 10 ){
          importProducts(limit);
          errorsCount++;
        }
        else {
          returnAddProducts(id_shop,1,1);
        }
      }
      if( limit == 0 ){
        // processImages();
        startImportRunning = 0;
      }
      if( json['limit'] ){
        importProducts(json['limit']);
      }

      if( json['error'] ){
        returnAddProducts(id_shop,json['error'],1);
      }
    },
    error: function(e){
      // if( e.status != 200){
        if( stopImport ){
          returnAddProducts($('input[name=id_shop]').val(),0,2);
          return true;
        }
        if( limit != 0 && errorsCount < 10 ){
          importProducts(limit);
          errorsCount++;
        }
        else {
          returnAddProducts(id_shop,1,1);
        }
      // }
    }
  });
}

function generateThumbnails() {
  var id_shop = $('input[name="id_shop"]').val();
  if( stopImport ){
    return false;
  }
  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'generateThumbnails=true&ajax=true&id_shop='+id_shop+'&id_shop_group='+$('input[name="id_shop_group"]').val(),
    dataType: 'json',
    success: function(json) {
      if( json['success'] ){
        if ( json['generate'] ) {
          generateThumbnails();
        }
      }
      else {
        generateThumbnails();
      }
    },
    error: function(e){
      generateThumbnails();
    }
  });
}

function processImages() {

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'processImages=true&ajax=true&id_shop='+$('input[name="id_shop"]').val()+'&id_shop_group='+$('input[name="id_shop_group"]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {

      // importImages();


      // generateThumbnails();
    }
  });
}


function returnAddProducts(id_shop, error, finish){
  if( !error ){
    error = 0;
  }
  else {
    stopImport = 1;
  }
  if( !finish ){
    finish = 0;
    if( stopImport ){
      return true;
    }
  }

  $.ajax({
    url: '../modules/simpleimportproduct/send.php',
    type: 'post',
    data: 'returnCount=true&ajax=true&start_import_running='+startImportRunning+'&id_shop='+id_shop+'&error='+error+'&finish='+finish+'&id_shop_group='+$('input[name="id_shop_group"]').val()+'&token='+$('input[name=products_import_token]').val()+'&controller=AdminProductsimport&action=send&id_lang='+$('input[name=id_lang]').val(),
    dataType: 'json',
    success: function(json) {
      if( !startCopyImages && json['need_copy_image'] ){
        processImages();
        startCopyImages = 1;
      }
      if (json['progress']) {
        $('.import_progress').html(json['progress'])
      }
      if( !json ){
        refreshIntervalId = setTimeout(function () {
          returnAddProducts(id_shop, error, finish);
        },3000);
      }
      if( finish ){
        clearTimeout(refreshIntervalId);
        return true;
      }
      if( json['import_finished'] ){
        stopImport = 1;
      }
      if( json['import_running'] || startImportRunning ){
        refreshIntervalId = setTimeout(function () {
          returnAddProducts(id_shop, error, finish);
        },3000);
      }
    },
    error: function(e){
      refreshIntervalId = setTimeout(function () {
        returnAddProducts(id_shop, error, finish);
      },3000);
    }
  });
}


function changeDiscount() {
  var type = $('#combinations_import_type').val();

  if(type == 'separated_field_value'){
    $("#specific_prices_for option").each(function() {
      if($(this).val() != 0){
        $(this).remove();
      }
    });
  }
  else{
     var field = $(".simpleimportproduct #fieldset_3_3").length;
    $(".simpleimportproduct #fieldset_5_5").each(function() {
      var option = $(this).find("#specific_prices_for option").length;
      var obj = $(this).find("#specific_prices_for");

      if(field >= option){
        var add = (field+1) - option;
        for (var i = option; i < (option+add); i++) {
          $(obj).append($('<option>', {
            value: i,
            text: $('#label_combinations').val()+' '+i
          }));
        }
      }
      else{
        var remove = option - (field+1);

        for (var i = (option-remove); i < (option); i++) {
          $(this).find("#specific_prices_for option[value='"+i+"']").remove()
        }
      }
    })
  }
}

var MpmImportCategoryLinking = {
    toggle_switch: "input[name='show_category_linking_block']",
    container: "#mpm_sip_category_linking_container",
    linked_items_container: "#mpm_sip_category_linking_block_main_content",
    add_btn: "#mpm_sip_clb_add",
    delete_btn: ".mpm-sip-clb-delete",
    linked_item_row: ".mpm-sip-clb-row",
    file_categories_select: ".mpm-sip-clb-file-category",
    shop_categories_select: ".mpm-sip-clb-shop-category",
    show: function () {
        $(this.container).slideDown();
    },
    hide: function () {
        $(this.container).slideUp();
    },
    toggleVisibility: function () {
        if (this.isActive() == 1) {
            this.show();
        } else {
            this.hide();
        }
    },
    isActive: function () {
        return $(this.toggle_switch + ":checked").val();
    },
    init: function () {
        this.toggleVisibility();
        $(this.linked_item_row).find("select").chosen();
    },
    addNew: function () {
        $.ajax({
            type: "POST",
            url: "index.php",
            dataType: "json",
            data: {
                ajax: true,
                token: $("input[name=products_import_token]").val(),
                controller: "AdminProductsimport",
                action: "getNewCategoryLinking",
                last_id: MpmImportCategoryLinking.getNumberOfItems()
            },
            success: function(response) {
                if (response["status"] == "success") {
                    $(MpmImportCategoryLinking.linked_item_row).last().after(response["tpl"]);
                } else {
                    alert("Can not retrieve new category linking template!");
                }
            },
            error: function() {
              alert("AJAX request has failed!");
            }
        });
    },
    remove: function (clicked_delete_btn) {
        if (this.getNumberOfItems() == 1) {
            $("label[for='show_category_linking_block_off']").click();
            this.hide();
            $(this.file_categories_select).val("");
        } else {
            clicked_delete_btn.parents(this.linked_item_row).remove();
        }
    },
    getNumberOfItems: function () {
        return $(this.linked_item_row).length;
    },
    addToRequestData: function () {
        var data = '&category_linking_active=' + MpmImportCategoryLinking.isActive();

        if (MpmImportCategoryLinking.isActive() == true) {
            var category_linking = {};
            $(MpmImportCategoryLinking.linked_item_row).each(function () {


                var file_cat = $(this).find(MpmImportCategoryLinking.file_categories_select).val();
                file_cat = encodeURIComponent(file_cat);

                var shop_cat_input_name = "mpm_sip_shop_category_" + $(this).data("row-number");
                var shop_cat_id = $(this).find(".tree input[name='"+shop_cat_input_name+"']:checked").val();
                var shop_cat_name = $(this).find(".tree input[name='"+shop_cat_input_name+"']:checked").siblings("label").text();
                var shop_cat_parent = $(this).find(".tree input[name='"+shop_cat_input_name+"']:checked").closest(".tree").siblings(".tree-folder-name").find("input").val();
                var is_valid_values = file_cat.length > 0 && shop_cat_id > 0 && shop_cat_name.length > 0;

                if (is_valid_values) {
                    category_linking[file_cat] = {id: shop_cat_id, name: shop_cat_name, parent: shop_cat_parent};
                }
            });

            data += '&category_linking=' + JSON.stringify(category_linking);
        } else {
            data += '&category_linking=[]';
        }

        return data;
    }
}