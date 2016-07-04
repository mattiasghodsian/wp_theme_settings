jQuery( document ).ready(function() {

  jQuery('.nav-rtab-wrapper a[href*="#"]:not([href="#"])').click(function() {
    jQuery('.nav-rtab-wrapper > a').removeClass('nav-tab-active');
    jQuery(this).addClass('nav-tab-active');
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      if (target.length) {
        jQuery('.nav-rtabs  .nav-rtab-holder').css("display", "none");
        jQuery(target).css("display", "block");
        jQuery('.nav-rtab-form').attr("action", "options.php"+target.selector);
      }
    }
  });

  if(window.location.hash.length) {
    var target = window.location.hash;
    jQuery('.nav-rtabs .nav-rtab-holder').css("display", "none");
    jQuery(target).css("display", "block");
    jQuery('.nav-rtab-wrapper > a').removeClass('nav-tab-active');
    jQuery('.nav-rtab-wrapper a[href="'+target+'"]').each(function(e){
      jQuery(this).addClass('nav-tab-active');
      jQuery('.nav-rtab-form').attr("action", "options.php"+target);
    });
  }

});