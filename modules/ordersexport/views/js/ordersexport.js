$(document).ready(function(){
  $(document).on("click", ".mpm-oe-edit-field-name-btn", function (e) {
    showEditFieldNameForm(e, this);
  });

  $(document).on("click", ".mpm-oe-close-field-edit", function (e) {
    closeEditFieldNameForm(e, this);
  });

  $(document).on("click", ".mpm-oe-save-field-name", function (e) {
    saveEditFieldNameForm(e, this);
  });

  $(document).on('click', '#filter_fields .add-extra-field', function(){
    addCustomField();
  });
  
  $(document).on('click', '.alert-danger-export li a', function(){
    var tab = $(this).attr('data-tab');
    var field = $(this).attr('data-field');

    activeErrorTab(tab, field);
  });

  if( $('#orders_add_task').length > 0 ){
    $('#orders_add_task').prependTo('#schedule_tasks div[data-tab-id=schedule_tasks]');
  }

  if( $('.schedule_tab').length > 0 ){
    $('form#configuration_form .tab-pane').removeClass('active');
    $('form#configuration_form .nav-tabs li').removeClass('active');
    $('form#configuration_form #schedule_tasks').addClass('active');
    $('form#configuration_form .nav-tabs li a[href=#schedule_tasks]').parent().addClass('active');
  }

  $(document).on('click', '.welcome_page .start', function(){
    $('form#configuration_form .tab-pane').removeClass('active');
    $('form#configuration_form .nav-tabs li').removeClass('active');
    $('form#configuration_form #export').addClass('active');
    $('form#configuration_form .nav-tabs li a[href=#export]').parent().addClass('active');
  });

  $(document).on('click', '.tabs_list .export ', function(){
    if(!$(this).hasClass('active')){
      $(this).addClass('active');
      $('.form_ordersexport').addClass('active');
      $('.tab_content_ordersexport').removeClass('active');
      $('.tabs_list .support').removeClass('active');
    }
  });

  $(document).on('click', '.tabs_list .support ', function(){
    if(!$(this).hasClass('active')){
      $(this).addClass('active');
      $('.tab_content_ordersexport').addClass('active');
      $('.form_ordersexport').removeClass('active');
      $('.tabs_list .export').removeClass('active');
    }
  });

  $(document).on('change', "input[name='format_file']", function(){
    var checked = $("input[name='format_file']:checked").val();
    if(checked == 'csv'){
      $('.block_csv_settings').removeClass('hide_block');
      $('.block_csv_settings').addClass('show_block');
    }
    else{
      $('.block_csv_settings').addClass('hide_block');
      $('.block_csv_settings').removeClass('show_block');
    }
  });

  $(document).on('change', "select[name='feed_target']", function(){
    if($("select[name='feed_target']").val() == 'ftp'){
      $('.attach-file-to-mail').hide();
      $('.ftp_target').show();
      $('.file_description').hide();
    }
    else{
      $('.ftp_target').hide();
        $('.attach-file-to-mail').show();
      if( $('input[name=specific_export_file]:checked').val() == '1' ){
        $('.file_description').show();
      }
    }
  });

  if( $("select[name='feed_target']").val() == 'ftp' ){
    $('.ftp_target').show();
    $('.file_description').hide();
    $('.attach-file-to-mail').hide();
  } else {
    $('.attach-file-to-mail').show();
  }

  $('.selected_fields').sortable({
    revert:false,
    axis: "y"
  });

  $(document).on('keyup', 'input[name=file_name]', function(){
    if( $(this).val() ){
      $('#export .file_description').show();
      /*$('#export  .href_export_file').attr('href', $('#export .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());
      $('#export .href_export_file').html($('#export .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());*/
      /*if($("select[name='feed_target']").val() == 'ftp'){
        $('#export .file_description').hide();
      }*/
    }
    else {
      $('#export .file_description').hide();
    }
  });


  $(document).on('change', 'input[name=specific_export_file]', function(){
    if( $('input[name=specific_export_file]:checked').val() == '1' ){
      $('#export .form_group_name_file').show();
      if( $('input[name=file_name]').val() ){
        $('#export .file_description').show();
      }
      /*if($("select[name='feed_target']").val() == 'ftp'){
        $('#export .file_description').hide();
      }*/
    }
    else {
      $('#export  .form_group_name_file').hide();
      $('#export  .file_description').hide();
    }

  });

  if( $('input[name=specific_export_file]:checked').val() == '1' ){
    $('#export  .form_group_name_file').show();
    $('#export  .file_description').show();
    /*$('#export  .href_export_file').attr('href', $('#export  .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());
    $('#export  .href_export_file').html($('#export  .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());*/
    /*if($("select[name='feed_target']").val() == 'ftp'){
      $('#export .file_description').hide();
    }*/
  }

  $(document).on('change', 'input[name=format_file]', function(){
    if($('input[name=format_file]:checked').val() !== 'xlsx'){
      $('li[data-value=cover_image]').hide();
      $('li[data-value=combinations_value]').hide();
    }
    else{
      $('li[data-value=cover_image]').show();
      $('li[data-value=combinations_value]').show();
    }
    /*$('#export  .href_export_file').attr('href', $('#export  .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());
    $('#export  .href_export_file').html($('#export  .href_export_file').attr('data-file-url') + $('input[name=file_name]').val() + '.' + $('input[name=format_file]:checked').val());*/
  });

  $(document).on('change', 'input[name=separate]', function(){
    if($('input[name=separate]:checked').val() == '0'){

      $('li[data-value=combinations_value]').hide();
    }
    else{
      $('li[data-value=combinations_value]').show();
    }
  });

  $(document).on('change', '.customers_checkbox', function(){
    customersChecked($(this).val());
  });
  $(document).on('click', '.next_button_ordersexport', function(){
    $('#filter').parents('.form-wrapper').find('.nav-tabs').find('.active').next().find('a').click();
  });
  $(document).on('change', '.selection_all', function(){
    $(this).parents('.form-group').next().find('input[type="checkbox"]').prop('checked', this.checked)
  });
  $(document).on('click', '.ordersExport', function(){
    exportOrders(0);
  });
  $(document).on('click', '.saveOrdersExport', function(){
    saveSettingsOrders();
  });
  $(document).on('click', '.delete_config', function(){
    removeSettingsOrders($(this).attr('settings'), $(this));
  });
  $(document).on('change', '.date_period', function(){
    if( $(this).val() == 'period' ){
      $('#filter_orders .date_from').fadeIn();
      $('#filter_orders .date_to').fadeIn();
      $('#filter_orders .date_period_form').removeClass('date_period_form_style');
    }
    else{
      $('#filter_orders .date_from').fadeOut();
      $('#filter_orders .date_to').fadeOut();
      $('#filter_orders .date_period_form').addClass('date_period_form_style');
    }
  });

  if( $('#filter_orders .date_period').val() == 'period' ){
    $('#filter_orders .date_from').fadeIn();
    $('#filter_orders .date_to').fadeIn();
    $('#filter_orders .date_period_form').removeClass('date_period_form_style');
  }


    $(document).on('change', '#automatic_export input[name=automatic]', function(){
        if( $(this).val() == '1'){
            $('#fieldset_1_1').fadeOut();
            $('#automatic_export .auto_description').fadeIn();
            $(".attach-file-to-mail").fadeIn();
            $(".export_not_exported").fadeIn();
            $(".auto_notif").fadeIn();
            $(".email-on-no-orders").fadeIn();
            $('.order-status-autoexport-filter-block').fadeOut();
            $("#append_id_order_to_file_name_desc").hide();

            $('#order_status_changed_autoexport_off').attr('checked', true);
            $('#order_status_changed_autoexport_on').attr('checked', false);
        } else if($('#automatic_export input[name=order_status_changed_autoexport]:checked').val() == '0'){
            $('#fieldset_1_1').fadeIn();
            $('#automatic_export .auto_description').fadeOut();
            $(".attach-file-to-mail").fadeOut();
            $(".export_not_exported").fadeOut();
            $(".auto_notif").fadeOut();
            $(".email-on-no-orders").fadeOut();
            $('.order-status-autoexport-filter-block').fadeOut();
            $("#append_id_order_to_file_name_desc").hide();
        }
  });

  if($('#automatic_export input[name=automatic]:checked').val() == '1'){
      $('#fieldset_1_1').fadeOut();
      $('#automatic_export .auto_description').fadeIn();
      $(".attach-file-to-mail").fadeIn();
      $(".export_not_exported").fadeIn();
      $(".auto_notif").fadeIn();
      $(".email-on-no-orders").fadeIn();
      $('.order-status-autoexport-filter-block').fadeOut();
      $("#append_id_order_to_file_name_desc").hide();

      $('#order_status_changed_autoexport_off').attr('checked', true);
      $('#order_status_changed_autoexport_on').attr('checked', false);
  } else if($('#automatic_export input[name=order_status_changed_autoexport]:checked').val() == '0'){
      $('#fieldset_1_1').fadeIn();
      $('#automatic_export .auto_description').fadeOut();
      $(".attach-file-to-mail").fadeOut();
      $(".export_not_exported").fadeOut();
      $(".auto_notif").fadeOut();
      $(".email-on-no-orders").fadeOut();
      $('.order-status-autoexport-filter-block').fadeOut();
      $("#append_id_order_to_file_name_desc").hide();
  }

  $(document).on('change', '#automatic_export input[name=order_status_changed_autoexport]', function(){
    if( $(this).val() == '1'){
        $('.order-status-autoexport-filter-block').fadeIn();
        $("#append_id_order_to_file_name_desc").show();
        $(".attach-file-to-mail").fadeIn();
        $(".auto_notif").fadeIn();
        $(".email-on-no-orders").fadeIn();
        $(".export_not_exported").fadeOut();
        $('#automatic_export .auto_description').fadeOut();
        $('#automatic_off').attr('checked', true);
        $('#automatic_on').attr('checked', false);
    } else if($('#automatic_export input[name=automatic]:checked').val() == '0'){
        $('#fieldset_1_1').fadeIn();
        $('#automatic_export .auto_description').fadeOut();
        $(".attach-file-to-mail").fadeOut();
        $(".export_not_exported").fadeOut();
        $(".auto_notif").fadeOut();
        $(".email-on-no-orders").fadeOut();
        $('.order-status-autoexport-filter-block').fadeOut();
        $("#append_id_order_to_file_name_desc").hide();
    } else {
        $("#append_id_order_to_file_name_desc").hide();
    }
  });

  if($('#automatic_export input[name=order_status_changed_autoexport]:checked').val() == '1' ){
      $('.order-status-autoexport-filter-block').fadeIn();
      $("#append_id_order_to_file_name_desc").show();
      $(".attach-file-to-mail").fadeIn();
      $(".auto_notif").fadeIn();
      $(".email-on-no-orders").fadeIn();
      $(".export_not_exported").fadeOut();
      $('#automatic_export .auto_description').fadeOut();
      $('#automatic_off').attr('checked', true);
      $('#automatic_on').attr('checked', false);
  } else if($('#automatic_export input[name=automatic]:checked').val() == '0'){
      $('#fieldset_1_1').fadeIn();
      $('#automatic_export .auto_description').fadeOut();
      $(".attach-file-to-mail").fadeOut();
      $(".export_not_exported").fadeOut();
      $(".auto_notif").fadeOut();
      $(".email-on-no-orders").fadeOut();
      $('.order-status-autoexport-filter-block').fadeOut();
      $("#append_id_order_to_file_name_desc").hide();
  } else {
      $("#append_id_order_to_file_name_desc").hide();
  }


  $(document).on('click', '.block_base_fields li', function(e){
    if(e.ctrlKey) {
      $(this).addClass('checked');
    }
    else{
      $('.block_base_fields li').removeClass('checked');
      $(this).addClass('checked');
    }
  });

  $(document).on('click', '.selected_fields li', function(e){
    if(e.ctrlKey) {
      $(this).addClass('checked');
    }
    else{
      $('.selected_fields li').removeClass('checked');
      $(this).addClass('checked');
    }
  });

  $(document).on('click', '.info_items .info_block .content .subscribe .error, .info_items .info_block .content .subscribe .success', function(){
    $(this).removeClass('error');
    $(this).removeClass('success');
    $(this).val('');
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
        token: $('input[name=orders_export_token]').val(),
        controller: 'AdminOrdersExport',
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
        token: $('input[name=orders_export_token]').val(),
        controller: 'AdminOrdersExport',
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




  $(document).on('click', '#filter_fields .add_base_filds_all', function(e){
    var base_tab = $('#configuration_form .nav-tabs li.active a').attr('href');
    var tab = $(base_tab+' .list-group-item.active').attr('data-tab');
    var page = $(this).attr('data-page');
    $('#'+page+' .field_list_'+tab+'  .block_base_fields li').each(function(e) {
      var el = $(this).clone().removeClass('checked').append('<i class="icon-arrows icon-arrows-select-fields"></i>');
      $('#'+page+'  .block_selected_fields .selected_fields').append(el[0]);
      $(this).remove();
    });
  });


  $(document).on('click', '#filter_fields .add_base_filds', function(e){
    var base_tab = $('#configuration_form .nav-tabs li.active a').attr('href');
    var tab = $(base_tab+' .list-group-item.active').attr('data-tab');
    var page = $(this).attr('data-page');


    $('#'+page+'  .field_list_'+tab+'  .block_base_fields li.checked').each(function(e) {
      var el = $(this).clone().removeClass('checked').append('<i class="icon-arrows icon-arrows-select-fields"></i>');
      $('#'+page+'  .block_selected_fields .selected_fields').append(el[0]);
      $(this).remove();
    });
  });

  $(document).on('click', '#filter_fields .remove_base_filds_all', function(e){
    var page = $(this).attr('data-page');
    
    //Close open for editing fields
    $(".selected_fields .mpm-oe-active-field-edit").each(function() {
        $(this).find(".mpm-oe-close-field-edit").trigger("click");
    });
    
    $('#'+page+'  .selected_fields li').each(function(e) {
      if(!$(this).hasClass('disable_fields')) {
        var tab =  $(this).attr('data-tab');
        var el = $(this).clone().removeClass('checked');
        $('#'+page+' .field_list_'+tab+' .block_base_fields').append(el[0]);
        $(this).remove();
      }
    });
    $('#'+page+' .block_base_fields li .icon-arrows-select-fields').remove();
  });


  $(document).on('click', '#filter_fields .remove_base_filds', function(e){
    var page = $(this).attr('data-page');
  
    $("#"+page+" .selected_fields .mpm-oe-active-field-edit.checked .mpm-oe-close-field-edit").trigger("click");
    
    $('#'+page+'  .selected_fields li.checked').each(function(e) {
      if(!$(this).hasClass('disable_fields')){
        var tab =  $(this).attr('data-tab');
        var el = $(this).clone().removeClass('checked');
        
        $('#'+page+' .field_list_'+tab+' .block_base_fields').append(el[0]);
        $(this).remove();
      }
    });
    $('#'+page+' .block_base_fields li .icon-arrows-select-fields').remove();
  });

  $(document).on('keyup', '#filter_fields .search_base_fields', function(){
    var self = $(this);
    var base_tab = $('#configuration_form .nav-tabs li.active a').attr('href');
    var tab = $(base_tab+' .list-group-item.active').attr('data-tab');
    var page = $(this).attr('data-page');
    $('#'+page+'  .field_list_'+tab+' .block_base_fields li').each(function(){
      if( $(this).text().toLowerCase().indexOf(self.val().toLowerCase()) >= 0 ){
        $(this).show();
      }
      else{
        $(this).hide();
      }
    });
  });

  $(document).on('keyup', '#filter_fields .search_selected_fields', function(){
    var self = $(this);
    var page = $(this).attr('data-page');
    $('#'+page+'  .selected_fields li').each(function(){
      if( $(this).text().toLowerCase().indexOf(self.val().toLowerCase()) >= 0 ){
        $(this).show();
      }
      else{
        $(this).hide();
      }
    });
  });

  $(document).on('click', "#filter_fields .fields_list .list-group-item", function(){
    if(!$(this).hasClass('active')){
      var tab = $(this).attr('data-tab');
      $('#filter_fields .block_all_fields .field_list_base').removeClass('active');
      $('#filter_fields .block_all_fields .field_list_'+tab).addClass('active');
      $("#filter_fields .fields_list .list-group-item").removeClass('active');
      $(this).addClass('active');
    }
  });


});

function saveSettingsOrders(){
    if(!$('input[name="format_file"]:checked').val()){
        $('.alert-danger, .alert-success').remove();
        $('#bootstrap_orders').before('<div class="alert alert-danger">Select format file for export</div>');
        
        return false;
    }
    
    $.ajax({
      url: '../modules/ordersexport/send.php',
      type: 'post',
      data: getExportData(1, 0),
      dataType: 'json',
      beforeSend: function(){
        $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
      },
      success: function(json) {
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        if (json['error']) {
          $(document).scrollTop(0);
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">' + json['error'] + '</div>');
        }
        else {
          if (json['success']) {
            $('#bootstrap_orders_export').before('<div class="alert alert-success">' + json['success'] + '</div>');
            location.href = $("input[name=base_settings]").val()+'&settings='+$("input[name=save_setting]").val()
          }

          if( json['error_list'] ){
            $(".progres_bar_ex").remove();
            $('.alert-danger, .alert-success').remove();
            $(document).scrollTop(0);
            var error_list = json['error_list'];
            var msg = '';
            $.each( error_list, function( key, value ) {
              if(key == 0){
                activeErrorTab(value.tab, value.field);
              }
              msg = msg+'<li><a class="error_tab" data-tab="'+value.tab+'" data-field="'+value.field+'">'+value.msg+'</a></li>';
            });
            $('#bootstrap_orders_export').before('<div class="alert alert-danger"><ul class="alert-danger-export">' + msg + '</ul></div>');
          }


        }
      }
    });
}

function removeSettingsOrders(key, obj){
    var id_shop=$("input[name=id_shop]").val();
    $.ajax({
      url: '../modules/ordersexport/send.php',
      type: 'post',
      data: 'removeSettings=true&ajax=true&key='+key+'&id_shop='+id_shop+'&token='+$('input[name=export_token]').val()+'&controller=AdminOrdersExport&action=send',
      dataType: 'json',
      beforeSend: function(){
        $("body").append('<div class="progres_bar_ex"><div class="loading"><div></div></div></div>');
      },
      success: function(json) {
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        if (json['error']) {
          $(document).scrollTop(0);
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">' + json['error'] + '</div>');
        }
        else {
          if (json['success']) {
            location.href = $("input[name=base_settings]").val();
            obj.parent().remove();
          }
        }
      }
    });
 }
var refreshIntervalId = false;

function exportOrders(pageLimit)
{
  if(!$('input[name="format_file"]:checked').val()){
      $('.alert-danger, .alert-success').remove();
      $('#bootstrap_orders').before('<div class="alert alert-danger">Select format file for export</div>');
      
      return false;
  }
  
  if ( pageLimit == 0 ){
    refreshIntervalId = setInterval(function(){ returnExportedOrders($("input[name=id_shop]").val()); }, 3000);
  }
    
    $.ajax({
      url: '../modules/ordersexport/send.php',
      type: 'post',
      data: getExportData(0, pageLimit),
      dataType: 'json',
      beforeSend: function(){
        if( $('.progres_bar_ex').length < 1 ){
          $("body").append('<div class="progres_bar_ex"><div class="loading_block"><div class="loading"></div><div class="exporting_notification"></div></div></div>');
        }
      },
      success: function(json) {
        if( !json ){
          clearInterval(refreshIntervalId);
          $('.alert-danger, .alert-success').remove();
          $(".progres_bar_ex").remove();
          $(document).scrollTop(0);
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/ordersexport/error.log" target="_blank">error.log</a> file or contact us!</div>');
        }
        if (json['error']) {
          $(".progres_bar_ex").remove();
          $('.alert-danger, .alert-success').remove();
          clearInterval(refreshIntervalId);
          $(document).scrollTop(0);
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">' + json['error'] + '</div>');
        }
        else {
          if (json['success']) {
            $(document).scrollTop(0);
            $(".progres_bar_ex").remove();
            $('.alert-danger, .alert-success').remove();
            clearInterval(refreshIntervalId);
            $("input.customer_check:checkbox").removeAttr("checked");
            $('#bootstrap_orders_export').before('<div class="alert alert-success">' + json['success'] + '</div>');

            if( json['export_file'] ){
              location.href = json['export_file'];
            }
          }

          if( json['error_list'] ){
            clearInterval(refreshIntervalId);
            $(".progres_bar_ex").remove();
            $('.alert-danger, .alert-success').remove();
            $(document).scrollTop(0);

            var error_list = json['error_list'];
            var msg = '';
            $.each( error_list, function( key, value ) {
              if(key == 0){
                activeErrorTab(value.tab, value.field);
              }
              msg = msg+'<li><a class="error_tab" data-tab="'+value.tab+'" data-field="'+value.field+'">'+value.msg+'</a></li>';
            });
            $('#bootstrap_orders_export').before('<div class="alert alert-danger"><ul class="alert-danger-export">' + msg + '</ul></div>');
          }


          if( json['page_limit'] ){
            exportOrders(json['page_limit']);
          }
        }
      },
      error: function(e){
        clearInterval(refreshIntervalId);
        $('.alert-danger, .alert-success').remove();
        $(".progres_bar_ex").remove();
        $(document).scrollTop(0);
        if( e.responseText.indexOf('AdminOrdersExport') > 0 ){
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">You was logged out from your admin panel, you must be logged during export process!</div>');
        }
        else {
          $('#bootstrap_orders_export').before('<div class="alert alert-danger">Some error occurred please check <a href="../modules/ordersexport/error.log" target="_blank">error.log</a> file or contact us!</div>');
        }
      }
    });
}

function getExportData(is_for_saving, pageLimit)
{
    var export_data = {
        save: is_for_saving ? 0 : 1,
        saveSettings: is_for_saving ? 1 : 0,
        ajax: 1,
        save_setting: $("input[name=save_setting]").val(),
        automatic: $("input[name=automatic]:checked").val(),
        attach_file_to_mail: $("input[name=attach_file_to_mail]:checked").val(),
        order_status_changed_autoexport: $("input[name=order_status_changed_autoexport]:checked").val(),
        not_exported: $("input[name=not_exported]:checked").val(),
        email_on_no_orders: $("input[name=email_on_no_orders]:checked").val(),
        notification_emails: $("textarea[name=notification_emails]").val(),
        format: $("input[name=format_file]:checked").val(),
        separate: $("input[name=separate]:checked").val(),
        separator_decimal_points: $("select[name=separator_decimal_points]").val(),
        orderway: $("input[name=orderway]:checked").val(),
        display_headers: $("input[name=display_headers]:checked").val(),
        isset_invoice: $("select[name=isset_invoice]").val(),
        date_format: $("select[name=date_format]").val(),
        feed_target: $("select[name=feed_target]").val(),
        ftp_transfer_protocol: $("select[name=ftp_transfer_protocol]").val(),
        ftp_port: $("input[name=ftp_port]").val(),
        ftp_server: $("input[name=ftp_server]").val(),
        ftp_user: $("input[name=ftp_user]").val(),
        ftp_password: $("input[name=ftp_password]").val(),
        ftp_folder_path: $("input[name=ftp_folder_path]").val(),
        sort: $("select[name=sort]").val(),
        round_value: $("select[name=round_value]").val(),
        delimiter_val: $("select[name=delimiter_val]").val(),
        seperatop_val: $("select[name=seperatop_val]").val(),
        specific_export_file: $("input[name=specific_export_file]:checked").val(),
        file_name: $("input[name=file_name]").val(),
        time_from: $("input[name=date_from]").val(),
        time_to: $("input[name=date_to]").val(),
        date_period: $("select[name=date_period]").val(),
        date_type: $("select[name=date_type]").val(),
        shop_id: $("select[name=shop_id]").val(),
        id_shop: $("input[name=id_shop]").val(),
        id_lang: $("input[name=id_lang]").val(),
        page_limit: pageLimit ? pageLimit : 0,
        token: $("input[name=export_token]").val(),
        controller: 'AdminOrdersExport',
        action: 'send',
        field_export: [],
        extra_fields: {},
        group_list: [],
        customers_list: [],
        status_list: [],
        order_status_autoexport_filter: [],
        payment_list: [],
        carrier_list: [],
        supplier_list: [],
        manufacturer_list: [],
    };
    
    $.each($('.selected_fields li'), function(i){
        if( $(this).attr('data-value') == 'cover_image' && ( $('input[name=format_file]:checked').val() !== 'xlsx') ){
            return;
        }
        
        if( $(this).attr('data-value') == 'combinations_value' && ( $("input[name=separate]:checked").val() == '0' ) ){
            return;
        }
        
        var export_field = $(this).attr('data-name') + "-" + $(this).attr('data-value');
        export_data["field_export"].push(export_field);
    });
    
    $.each($('.groupBox'), function(){
        if (!$(this).prop('checked')){
            return;
        }
        
        export_data["group_list"].push($(this).val());
    });
    
    $.each($('.customers_checkbox'), function(){
        if (!$(this).prop('checked')){
            return;
        }
        
        export_data["customers_list"].push($(this).val());
    });
    
    $.each($('.statusOrders'), function(){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["status_list"].push($(this).val());
    });
    
    $.each($('.order-status-autoexport-filter-field'), function(i){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["order_status_autoexport_filter"].push($(this).val());
    });
    
    $.each($('.paymentOrders'), function(){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["payment_list"].push($(this).val());
    });
    
    $.each($('.carrierOrders'), function(){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["carrier_list"].push($(this).val());
    });
    
    $.each($('.supplierOrders'), function(){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["supplier_list"].push($(this).val());
    });
    
    $.each($('.manufacturerOrders'), function(){
        if(!$(this).prop('checked')){
            return;
        }
        
        export_data["manufacturer_list"].push($(this).val());
    });
    
    $(".mpm-oe-extra-field").each(function() {
        var id = $(this).attr('data-value');
        
        export_data["extra_fields"][id] = {
            id: $(this).attr('data-value'),
            name: $(this).attr('data-name'),
            value: $(this).find('.mpm-oe-edit-field-default-val').val()
        }
    });
    
    return export_data;
}

function returnExportedOrders(id_shop){
  $.ajax({
    url: '../modules/ordersexport/send.php',
    type: 'post',
    data: 'returnCount=true&ajax=true&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=export_token]').val()+'&controller=AdminOrdersExport&action=send',
    dataType: 'json',
    success: function(json) {
      if (json['export_notification']) {
        $('.exporting_notification').html(json['export_notification'])
      }
    }
  });
}

function searchCheckboxtable(obj, val){
  $.ajax({
    url: '../modules/ordersexport/send.php',
    type: 'post',
    data: 'ajax=true&search=' + val + '&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=export_token]').val()+'&controller=AdminOrdersExport&action=send',
    dataType: 'json',
    success: function(json) {
      $('.alert-danger, .alert-success').remove();
      if (json['customers']) {
        $(obj).replaceWith(json['customers']);
      }
    }
  });
}

function customersChecked(id_customer){
  $.ajax({
    url: '../modules/ordersexport/send.php',
    type: 'post',
    data: 'add_customer=true&ajax=true&id_customer='+id_customer+'&id_shop='+$('input[name=id_shop]').val()+'&token='+$('input[name=export_token]').val()+'&controller=AdminOrdersExport&action=send',
    dataType: 'json'
  });
}


function activeErrorTab( tab, field ){
  if(tab){
    $('form#configuration_form .tab-pane').removeClass('active');
    $('form#configuration_form #'+tab).addClass('active');
    $('form#configuration_form .nav-tabs li').removeClass('active');
    $('form#configuration_form .nav-tabs li a[href=#'+tab+']').parent().addClass('active');
  }

  if(field){

    if( field == 'notification_emails' ){
      $('textarea[name='+field+']').focus();
    }
    else{
      $('input[name='+field+']').focus();
    }

  }
}

function showEditFieldNameForm(e, this_handler)
{
    e.preventDefault();
    var container = $(this_handler).siblings(".mpm-oe-edit-field-name-container");
    var field_name = $(this_handler).siblings(".mpm-oe-selected-field-name");
    var move_icon = $(this_handler).siblings(".icon-arrows-select-fields");
    
    $(this_handler).parents("li").addClass("mpm-oe-active-field-edit");
    container.css("display", "inline-block");
    field_name.hide();
    move_icon.hide();
    $(this_handler).hide();
}

function closeEditFieldNameForm(e, this_handler)
{
    e.preventDefault();
    var container = $(this_handler).parents(".mpm-oe-edit-field-name-container");
    var field_name = container.siblings(".mpm-oe-selected-field-name");
    var edit_field_name_button = container.siblings(".mpm-oe-edit-field-name-btn");
    var move_icon = container.siblings(".icon-arrows-select-fields");
    
    $(this_handler).parents("li").removeClass("mpm-oe-active-field-edit");
    
    container.hide();
    field_name.show();
    edit_field_name_button.show();
    move_icon.show();
    
    $(this_handler).siblings(".form-group").find(".mpm-oe-edit-field-name").val($(this_handler).parents("li").attr("data-name"));
    $(this_handler).siblings(".form-group").find(".mpm-oe-edit-field-default-val").val($(this_handler).parents("li").attr("data-default-value"));
}

function saveEditFieldNameForm(e, this_handler)
{
    e.preventDefault();
    var container = $(this_handler).parents(".mpm-oe-edit-field-name-container");
    var new_field_name = $(this_handler).siblings(".form-group").children(".mpm-oe-edit-field-name").val();
    var new_field_val  = $(this_handler).siblings(".form-group").children(".mpm-oe-edit-field-default-val").val();
    
    container.siblings(".mpm-oe-selected-field-name").html(new_field_name);
    container.parent().attr("data-name", new_field_name);
    container.parent().attr("data-default-value", new_field_val);
    
    $(this_handler).parents("li").removeClass("mpm-oe-active-field-edit");
    $(this_handler).siblings(".mpm-oe-close-field-edit").trigger("click");
}

function addCustomField()
{
    var id = parseInt(Math.random() * 10000);
    
    var extra_field = "<li data-tab='exportTabOrdersData'  class='mpm-oe-extra-field' data-name='Extra field' id='extra_field_" + id + "' data-value='extra_field_" + id + "'>";
    extra_field += "<span class='mpm-oe-selected-field-name'>Custom Extra field</span>";
    extra_field += "<i class='icon-pencil mpm-oe-edit-field-name-btn'></i>";
    extra_field += "<div class='form-inline mpm-oe-edit-field-name-container mpm-oe-edit-field-value-container'>";
    extra_field += "<div class='form-group'>";
    extra_field += "<input type='text' class='form-control mpm-oe-edit-field-name' placeholder='Custom field name' value='Custom Extra field' />";
    extra_field += "</div>";
    extra_field += "<div class='form-group'>";
    extra_field += "<input type='text' class='mpm-oe-edit-field-default-val' placeholder='Default field value'/>";
    extra_field += "</div>";
    extra_field += "<span class='mpm-oe-save-field-name'><i class='icon-check'></i></span>";
    extra_field += "<span class='mpm-oe-close-field-edit'><i class='icon-times'></i></span>";
    extra_field += "</div>";
    extra_field += "<i class='icon-arrows icon-arrows-select-fields'></i>";
    extra_field += "</li>";
    
    $(".selected_fields.ui-sortable").append(extra_field);
    
    $("#extra_field_" + id + " .mpm-oe-edit-field-name-btn").trigger("click");
}
