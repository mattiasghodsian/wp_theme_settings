/**
 * Class Name: wp_theme_settings
 * GitHub URI: http://www.github.com/mattiasghodsian/wp_theme_settings
 * Description: A custom WordPress class for creating theme settings page (Design looks identical to WP About page)
 * Author: Mattias Ghodsian
 * Author URI: http://www.nexxoz.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
jQuery( document ).ready(function() {

  jQuery('.nav-rtab-wrapper a[href*="#"]:not([href="#"])').click(function() {
    jQuery('.nav-rtab-wrapper > a').removeClass('nav-tab-active');
    jQuery(this).addClass('nav-tab-active');
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      if (target.length) {
        jQuery('.nav-rtabs  .nav-rtab-holder').css("display", "none");
        jQuery(target).css("display", "block");
        jQuery(target.selector + ' > .wpts-nav-section-holder').css("display", "none");
        jQuery(target.selector + '_parent').css("display", "block");
        jQuery('.nav-rtab-form').attr("action", "options.php"+target.selector);
        jQuery('html, body').animate({scrollTop : 0},1);
      }
    }
  });

  jQuery('.wpts-nav-sections > li > a').click(function() {
    var target = jQuery(this).attr('href');
    if(target.indexOf('&') != -1){
      var target_array = target.split("&");
      var parent_target = target_array[0];
      var section_target = target_array[1].replace("section=", "#");
      jQuery('.nav-rtabs .nav-rtab-holder').css("display", "none");
      jQuery(parent_target).css("display", "block");
      jQuery(parent_target + ' > .wpts-nav-section-holder').css("display", "none");
      jQuery(section_target).css("display", "block");
      jQuery('.nav-rtab-form').attr("action", "options.php"+target);
      jQuery('html, body').animate({scrollTop : 0},1);
    }
  });

  if(window.location.hash.length) {
    var target = window.location.hash;
    if(target.indexOf('&') != -1){
      var target_array = target.split("&");
      var parent_target = target_array[0];
      var section_target = target_array[1].replace("section=", "#");
      jQuery('.nav-rtabs .nav-rtab-holder').css("display", "none");
      jQuery(parent_target).css("display", "block");
      jQuery(section_target).css("display", "block");
    }else{
      jQuery('.nav-rtabs .nav-rtab-holder').css("display", "none");
      jQuery(target).css("display", "block");
      jQuery(target + '_parent').css("display", "block");
      jQuery('.nav-rtab-wrapper > a').removeClass('nav-tab-active');
      jQuery('.nav-rtab-wrapper a[href="'+target+'"]').each(function(e){
        jQuery(this).addClass('nav-tab-active');
        jQuery('.nav-rtab-form').attr("action", "options.php"+target);
      });
    }
  }else{
    var target = jQuery('.nav-rtab-wrapper > a').first().attr('href');
    jQuery(target).css("display", "block");
    jQuery(target + '_parent').css("display", "block");
  }

  if ( jQuery( '.nav-tab-wrapper' ).length > 0 ) {
    jQuery( '#footer-thankyou' ).html('Thank you for creating with <a href="https://git.io/vi1Gr" target="_new">WPTS</a>');
  }

  jQuery( '.wpts_color_field' ).wpColorPicker();
  jQuery( '.wpts_fa_field' ).wptsFa();

  var formfield;
  jQuery('.wpts-file-field').click(function() {
    jQuery('html').addClass('Image');
    formfield = jQuery(this).prev().attr('id');
    tb_show('Upload File', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
  });

  window.original_send_to_editor = window.send_to_editor;

  window.send_to_editor = function(html){
    if (formfield) {
      re = /\ssrc=(?:(?:'([^']*)')|(?:"([^"]*)")|([^\s]*))/i,
      res = html.match(re),
      src = res[1]||res[2]||res[3];     
      jQuery('#'+formfield).val(src);
      jQuery( ".wpts-file-field-preview" ).before('#'+formfield).attr('src', src);
      tb_remove();
      jQuery('html').removeClass('Image');
    } else {
      window.original_send_to_editor(html);
    }
  };


  
});

(function( $ ){
  $.fn.wptsFa = function() {
    return this.each(function() { 
      var mainE = $(this);
      var mainName = $(mainE).attr('name');
      $(mainE).hide();

      $( '<div class="wptsFA-container" id="'+mainName+'"><div class="wptsFA-icon"></div><div class="wptsFA-button">Select icon</div><div class="wptsFA-icons"></div></div>').insertBefore(mainE);

      $.get( 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/master/src/icons.yml', function( data ) {
        var obj = jsyaml.load( data );
        jQuery.each( obj['icons'], function( i, val ) {
           $('#' + mainName + ' .wptsFA-icons').append('<i class="fa fa-'+val['id']+'"></i>');
        });
      });

      if ($(this).length > 0) {
        $('#' + mainName + ' .wptsFA-icon').html('<i class="fa ' + $(this).val() + '"></i>');
      }

      $('#' + mainName).click(function(){
        $(this).toggleClass('active');
      });

      $(document).on("click",'#' + mainName + ' .wptsFA-icons > i',function() {
        var id = $(this).attr('class').replace('fa','');
        $(mainE).val(id);
        $('#' + mainName + ' .wptsFA-icon').html('<i class="fa ' + id + '"></i>');
      });

    });
  }; 
})( jQuery );