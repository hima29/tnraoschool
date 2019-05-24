jQuery(function ($) {

    "use strict";

/**
 * Init Plugins
 */
(function () {

    /**
     * Superfish Menu
     */
    $('.sf-menu ul').superfish();

    $('.cbp-row:not(.wpb_layerslider_element)').fitVids();


    /**
     * Respmenu
     */
    if (wheels.data.respmenu) {
        $('#' + wheels.data.respmenu.id + ' ul').first().respmenu(wheels.data.respmenu.options);
    }

    /**
     * ScrollUp
     */
    if (wheels.data.useScrollToTop) {
        $.scrollUp({
            scrollText: wheels.data.scrollToTopText
        });
    }

})();/**
 * Embellishments
 */
(function () {

    $('.wh-has-embellishment').each(function () {

        var $this = $(this);

        var classes = $this.attr('class').split(' ');
        var matchedClasses = [];

        $.each(classes, function (i, className) {

            var matches = /^wh-embellishment-type\-(.+)/.exec(className);
            if (matches !== null) {
                matchedClasses.push(matches[1]);
            }
        });

        $.each(matchedClasses, function (i, className) {

            if (className.search('top') !== -1) {
                $this.prepend('<div class="wh-embellishment-' + className + '"/>');
            } else if (className.search('bottom') !== -1) {
                $this.append('<div class="wh-embellishment-' + className + '"/>');
            }
        });

    });

})();
/**
 * VC Accordion
 */
(function () {

    var classOpen = 'iconsmind-minus';
    var classClose = 'iconsmind-plus';

    $('.wpb_accordion_header').on('click', function () {

        var $this = $(this);

        $this.find('.ui-icon').addClass(classOpen);
        $this.find('.ui-icon').removeClass(classClose);

        $this.parent().siblings().find('.wpb_accordion_header .ui-icon').removeClass(classOpen).addClass(classClose);

    });
    /**
     * Replace Accordion icon class
     */

    setTimeout(function () {


        $('.wpb_accordion_header').each(function () {

            var $this = $(this);

            if ($this.hasClass('ui-state-active')) {
                $this.find('.ui-icon').addClass(classOpen);
            } else {
                $this.find('.ui-icon').addClass(classClose);
            }


        });
    }, 500);

})();/**
 * Sticky
 */
(function () {

    /**
     * Sticky Menu
     */
    var stickyMenuTopOffset = 0;
    if (wheels.data.isAdminBarShowing) {
        stickyMenuTopOffset = $('#wpadminbar').height();
    }

    var getWidthFrom = 'body';
    // if boxed
    if ($('.wh-main-wrap').length) {
        getWidthFrom = '.wh-main-wrap';
    }

    $('.wh-sticky-header-enabled').sticky({
        topSpacing: stickyMenuTopOffset,
        className: 'wh-sticky-header',
        getWidthFrom: getWidthFrom,
        center: true,
        responsiveWidth: true
    });

    /**
     * Sticky Project Info
     */
    var $projectInfo = $('.wh-sticky  .vc_column-inner');

    var $prevNextLinks = $('.prev-next-item');

    var projectInfoBottomSpacing = 0;

    if ($prevNextLinks.length) {
        var prevNextTop = $prevNextLinks.offset().top;
        var bodyHeight = $('body').height();

        projectInfoBottomSpacing = bodyHeight - prevNextTop;
    }

    $projectInfo.sticky({
        topSpacing: stickyMenuTopOffset + 100,
        className: 'wh-sticky-sidebar',
        getWidthFrom: '.wh-sticky',
        responsiveWidth: true,
        bottomSpacing: projectInfoBottomSpacing
    });


})();/**
 * Scroll to Element
 */
(function () {

    $('header a[href^="#"], .wh-top-bar a[href^="#"], .wh-top-bar a[href^="/#"]').on('click', function (e) {

        var positionTop;
        var $this = $(this);
        var $mainMenuWrapper = $('.wh-main-menu-bar-wrapper');
        var stickyHeaderHeight = $mainMenuWrapper.height();


        var target = $this.attr('href');
        target = target.replace('/', '');
        var $target = $(target);

        if ($target.length) {
            e.preventDefault();

            // if sticky menu is visible
            if ($('.wh-sticky-header').length) {
                positionTop = $target.offset().top - stickyHeaderHeight;
            } else {
                positionTop = $target.offset().top - wheels.data.initialWaypointScrollCompensation || 120;
            }

            $('body, html').animate({ // html needs to be there for Firefox
                scrollTop: positionTop
            }, 1000);
        }
    });


})();
/**
 * Quick Sidebar
 */
(function () {

    $('.wh-quick-sidebar-toggler-wrapper').on('click', '.wh-quick-sidebar-toggler', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if ($('body').hasClass("wh-quick-sidebar-shown")) {
            $('body').removeClass("wh-quick-sidebar-shown");
        } else {
            $('body').addClass("wh-quick-sidebar-shown");
        }
    });

    $('.wh-quick-sidebar').on('click', '.wh-close', function (e) {
        e.preventDefault();

        $('body').removeClass("wh-quick-sidebar-shown");
    });

    $('.wh-quick-sidebar').on('click', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.wh-quick-sidebar-shown', function (e) {
        $(this).removeClass("wh-quick-sidebar-shown");
    });


})();
/**
 * Quick Search
 */
(function () {

    // desktop mode
    $('.wh-search-toggler').on('click', function (e) {
        e.preventDefault();

        $('body').addClass('wh-quick-search-shown');

        //if ($.browser.msie === false) {
        $('.wh-quick-search > .form-control').focus();
        //}
    });

    // mobile mode
    //$('.c-layout-header').on('click', '.c-brand .c-search-toggler', function (e) {
    //    e.preventDefault();
    //
    //    $('body').addClass('c-layout-quick-search-shown');
    //
    //    if (App.isIE() === false) {
    //        $('.c-quick-search > .form-control').focus();
    //    }
    //});

    // handle close icon for mobile and desktop
    $('.wh-quick-search').on('click', '> span', function (e) {
        e.preventDefault();
        $('body').removeClass('wh-quick-search-shown');
    });

})();

});