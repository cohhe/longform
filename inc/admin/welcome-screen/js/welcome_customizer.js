jQuery(document).ready(function() {
    var longform_aboutpage = longformWelcomeScreenCustomizerObject.aboutpage;
    var longform_nr_actions_required = longformWelcomeScreenCustomizerObject.nr_actions_required;

    /* Number of required actions */
    if ((typeof longform_aboutpage !== 'undefined') && (typeof longform_nr_actions_required !== 'undefined') && (longform_nr_actions_required != '0')) {
        jQuery('#accordion-section-themes .accordion-section-title').append('<a href="' + longform_aboutpage + '"><span class="welcome-screen-actions-count">' + longform_nr_actions_required + '</span></a>');
    }

    /* Upsell in Customizer (Link to Welcome page) */
    if ( !jQuery( ".longform-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('<li class="accordion-section longform-upsells">');
    }
    if (typeof longform_aboutpage !== 'undefined') {
        jQuery('.longform-upsells').append('<a style="width: 80%; margin: 5px auto 5px auto; display: block; text-align: center;" href="' + longform_aboutpage + '" class="button" target="_blank">{themeinfo}</a>'.replace('{themeinfo}', longformWelcomeScreenCustomizerObject.themeinfo));
    }
    if ( !jQuery( ".longform-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('</li>');
    }
});