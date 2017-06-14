jQuery(document).ready(function() {
	
	/* If there are required actions, add an icon with the number of required actions in the About longform page -> Actions required tab */
    var longform_nr_actions_required = longformWelcomeScreenObject.nr_actions_required;

    if ( (typeof longform_nr_actions_required !== 'undefined') && (longform_nr_actions_required != '0') ) {
        jQuery('li.welcome-screen-w-red-tab a').append('<span class="welcome-screen-actions-count">' + longform_nr_actions_required + '</span>');
    }

    /* Dismiss required actions */
    jQuery(".longform-dismiss-required-action").click(function(){

        var id= jQuery(this).attr('id');
        console.log(id);
        jQuery.ajax({
            type       : "GET",
            data       : { action: 'longform_dismiss_required_action',dismiss_id : id },
            dataType   : "html",
            url        : longformWelcomeScreenObject.ajaxurl,
            beforeSend : function(data,settings){
				jQuery('.welcome-screen-tab-pane#actions_required h1').append('<div id="temp_load" style="text-align:center"><img src="' + longformWelcomeScreenObject.template_directory + '/inc/admin/welcome-screen/img/ajax-loader.gif" /></div>');
            },
            success    : function(data){
				jQuery("#temp_load").remove(); /* Remove loading gif */
                jQuery('#'+ data).parent().remove(); /* Remove required action box */

                var longform_actions_count = jQuery('.welcome-screen-actions-count').text(); /* Decrease or remove the counter for required actions */
                if( typeof longform_actions_count !== 'undefined' ) {
                    if( longform_actions_count == '1' ) {
                        jQuery('.welcome-screen-actions-count').remove();
                        jQuery('.welcome-screen-tab-pane#actions_required').append('<p>' + longformWelcomeScreenObject.no_required_actions_text + '</p>');
                    }
                    else {
                        jQuery('.welcome-screen-actions-count').text(parseInt(longform_actions_count) - 1);
                    }
                }
            },
            error     : function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    });
	
	/* Tabs in welcome page */
	function longform_welcome_page_tabs(event) {
		jQuery(event).parent().addClass("active");
        jQuery(event).parent().siblings().removeClass("active");
        var tab = jQuery(event).attr("href");
        jQuery(".welcome-screen-tab-pane").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
	}
	
	var longform_actions_anchor = location.hash;
	
	if( (typeof longform_actions_anchor !== 'undefined') && (longform_actions_anchor != '') ) {
		longform_welcome_page_tabs('a[href="'+ longform_actions_anchor +'"]');
	}
	
    jQuery(".welcome-screen-nav-tabs a").click(function(event) {
        event.preventDefault();
		longform_welcome_page_tabs(this);
    });

});