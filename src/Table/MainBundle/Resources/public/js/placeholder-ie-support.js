jQuery.browser={};(function(){jQuery.browser.msie=false;
jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)\./)){
jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
jQuery(document).ready(function() {
    /* Placeholder for IE */
    if(jQuery.browser.msie) { // Условие для вызова только в IE
        jQuery("form").find("input[type='text']").each(function() {
            var tp = jQuery(this).attr("placeholder");
            jQuery(this).attr('value',tp).css('color','#ccc');
        }).focusin(function() {
            var val = jQuery(this).attr('placeholder');
            if(jQuery(this).val() == val) {
                jQuery(this).attr('value','').css('color','#303030');
            }
        }).focusout(function() {
            var val = jQuery(this).attr('placeholder');
            if(jQuery(this).val() == "") {
                jQuery(this).attr('value', val).css('color','#ccc');
            }
        });

        /* Protected send form */
        jQuery("form").submit(function() {
            jQuery(this).find("input[type='text']").each(function() {
                var val = jQuery(this).attr('placeholder');
                if(jQuery(this).val() == val) {
                    jQuery(this).attr('value','');
                }
            })
        });
    }
});