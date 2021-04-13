importError = new function()
{
  this.ERROR_TYPE_ERROR 	= "error";
  this.ERROR_TYPE_NOTICE 	= "notice";


  var errorBoxHtml = "<div id='importErrorBox'><div class='message'></div></div>";

  this.left = 0;
  this.top = 0;

  this.init = function() {
    if ( $("#importErrorBox").length > 0 ) {
      return;
    }

    $('body').append(errorBoxHtml);
    $('#importErrorBox').bind("click", function(){
      $(this).fadeOut();
    });
  };

  this.hide = function() {
    $("#importErrorBox").fadeOut();
  };

  this.setPosition = function(selector) {
    this.left = $(selector).offset().left + 5;
    this.top = $(selector).offset().top - $("#importErrorBox").height() - 10;
    if(($(window).width() - ($('#importErrorBox').width() + this.left)) < 10 ){
      this.left = this.left - $('#importErrorBox').width() - 5;

      $('#importErrorBox').addClass("right");
    }
    if ( this.left < 0 ) {
      this.left = 100;
    }

    if ( this.top < 0 ) {
      this.top = 100;
    }

    $("#importErrorBox").css("left", this.left + "px" );
    $("#importErrorBox").css("top", this.top + "px");
  };

  this.show = function(selector, errorType, message) {
    var self = this;
    $(".import_error").each(function(){
      $(this).removeClass("import_error");
    })
    $(".import_error_tiny").each(function(){
      $(this).removeClass("import_error_tiny");
    })
    if( message == 'empty' ){
      $(selector).addClass("import_error");
      return true;
    }
    if( message == 'empty_tiny' ){
      $(selector).addClass("import_error_tiny");
      return true;
    }
    this.init();

    $("#importErrorBox").removeClass("importError");
    $("#importErrorBox").removeClass("importNotice");

    if (errorType == self.ERROR_TYPE_NOTICE) {
      $("#importErrorBox").addClass("importNotice");
    } else {
      $("#importErrorBox").addClass("importError");
    }

    $("#importErrorBox .message").html(message);

    if ( $(selector).length == 0 ) {
      selector = "body";
    }

    $(selector).bind('keypress.importErrorHide', function(e){
      $(e.target).unbind('keypress.importErrorHide');
      $(e.target).unbind('click.importErrorHide');
      self.hide();
    });

    $(selector).bind('click.importErrorHide', function(e){
      $(e.target).unbind('keypress.importErrorHide');
      $(e.target).unbind('click.importErrorHide');
      self.hide();
    });

    this.setPosition(selector);
    $(selector).focus();
    $("#importErrorBox").fadeIn();
    this.setPosition(selector);
    setTimeout(self.hide, 5000);
  };
};