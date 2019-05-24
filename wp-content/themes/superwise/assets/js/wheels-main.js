jQuery(function ($) {

    "use strict";

//===============================================
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
     * ScrollUp
     */
    if (wheels.data.useScrollToTop) {
        $.scrollUp({
            scrollText: wheels.data.scrollToTopText
        });
    }


})();
//===============================================
/**
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

})();
/**
 * Boxed Row
 */

(function () {


    function boxedRow() {


        var $mainWrap = $('.wh-main-wrap');

        if ($mainWrap.length) {
            var boxedWrapWidth = $mainWrap.width();
            var $boxedFullwidthRow = $('.boxed-fullwidth');

            var diff = $mainWrap.width() - $boxedFullwidthRow.width();

            var currentMarginLeft = parseInt($boxedFullwidthRow.css('margin-left'));
            var currentMarginRight = parseInt($boxedFullwidthRow.css('margin-right'));


            $boxedFullwidthRow.css({
                marginLeft: currentMarginLeft - (diff / 2),
                marginRight: currentMarginRight - (diff / 2)
            });
        }
    }

    boxedRow();
    $(window).resize(boxedRow);

})();//===============================================
/**
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

    var settings = {
        topSpacing: stickyMenuTopOffset,
        zIndex: 99999,
        //getWidthFrom: '.cbp-container',
        getWidthFrom: 'body',
        responsiveWidth: true
    };

    var $header = $('.sticky-bar');
    $header.wrap('<div class="sticky-bar-bg"></div>');
    $('.sticky-bar-bg').sticky(settings);


})();
//===============================================
/**
 * Scroll to Element
 */
(function () {

    $('header a[href^="#"], .wh-header a[href^="#"], .wh-header a[href^="/#"]').on('click', function (e) {

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
            if ($('.wh-header.is_stuck').length) {
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
//===============================================
/**
 * Quick Sidebar
 */
(function () {

    var bodyClass = 'wh-quick-sidebar-shown';
    var $quickSidebarPanel = $('.wh-quick-sidebar');
    var panelPosition = $quickSidebarPanel.data('position');

    if (panelPosition === 'left') {
            bodyClass += '-left';
        }


        $('.wh-quick-sidebar-toggler-wrapper').on('click', '.wh-quick-sidebar-toggler', function (e) {
            e.preventDefault();
            e.stopPropagation();

            $('body').toggleClass(bodyClass);
    });

    $quickSidebarPanel.on('click', '.wh-close', function (e) {
        e.preventDefault();

        $('body').removeClass(bodyClass);
    });

    $quickSidebarPanel.on('click', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.' + bodyClass, function (e) {
        $(this).removeClass(bodyClass);
    });

})();
    //===============================================
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

    // handle close icon for mobile and desktop
    $('.wh-quick-search').on('click', '> span', function (e) {
        e.preventDefault();
        $('body').removeClass('wh-quick-search-shown');
    });

})();
//===============================================
/**
 * Mobile Menu
 */
(function () {

    var $mobileMenu = $('#wh-mobile-menu');

    // Header Toggle
    $mobileMenu.find('.respmenu-header .respmenu-open').on('click', function() {
        $mobileMenu.find('.respmenu').slideToggle(200);
    });

    // Submenu Toggle
    $mobileMenu.find('.respmenu-submenu-toggle').on('click', function() {
        $(this).siblings('.sub-menu').slideToggle(200);
    });

})();


//===============================================
/**
 * Preloader
 */
(function () {

    var $preloader = $('.wh-preloader');
    var showPreloader = false;
    var spinner = 'spinner4';
    var preloaderBgColor = '#304ffe';
    if (wheels.data.preloaderSpinner) {
        showPreloader = true;
        spinner = 'spinner' + wheels.data.preloaderSpinner;
    }
    if (wheels.data.preloaderBgColor) {
        preloaderBgColor = wheels.data.preloaderBgColor;
    }

    if (showPreloader) {

        var settings = {
            timeToHide:600, //Time in milliseconds for fakeLoader disappear
            zIndex:"9999999",//Default zIndex
            spinner:spinner,//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
            bgColor:preloaderBgColor, //Hex, RGB or RGBA colors
            //imagePath:"yourPath/customizedImage.gif" //If you want can you insert your custom image
        };

        $preloader.fakeLoader(settings);
    }

})();
//===============================================
/**
 * Preloader
 */
(function () {

	var i = 0;
	var $list = $('.tl-recent-tweets ul li');

	$list.hide();

	function changeList() {
	    
	    $list.eq(i).fadeIn(1000).delay(5000).fadeOut(300, function() {
	        i++;
	        if(i % $list.length == 0) {
	            i = 0;
	        }
	        changeList();
	    });
	}
	changeList();

})();
});