jQuery(function ($) {
    if (typeof agc_classroom_vc_addons != 'undefined' && agc_classroom_vc_addons.data.sliders_owl) {
        $.each(agc_classroom_vc_addons.data.sliders_owl, function (i, slider) {
            $('#' + slider.id).owlCarousel(slider.options);
        });
    }
});