'use strict';

jQuery(function ($) {

    "use strict";

    /**
     * Init Plugins
     */

    {
        (function () {
            var setPosition = function setPosition() {

                var bodyWidth = $body.outerWidth();
                var bodyPaddingLeft = parseInt($body.css('paddingLeft'));
                var bodyPaddingRight = parseInt($body.css('paddingRight'));

                menuItems.forEach(function (item) {

                    var $el = item.$el;
                    var $parent = item.$parent;

                    var depth = $el.data('depth') || 0;
                    var width = $el.data('width') || bodyWidth;
                    var position = $el.data('position') || 'center';
                    var margin = $el.data('margin') || 0;
                    var bgColor = $el.data('bgColor') || null;
                    var parentOffset = $parent.offset().left;

                    //if (depth === 0 || msm_mega_submenu.data.submenu_items_position_relative) {
                    //    $parent.css('position', 'relative');
                    //}

                    if (typeof width === 'string' && width.indexOf('%') !== -1) {
                        width = bodyWidth / 100 * parseFloat(width);
                    }

                    var style = {
                        width: width
                    };

                    /**
                     * Left offset from the body
                     * Set to null because it is not used in all cases
                     * @type {null}
                     */
                    var offsetLeft = null;

                    if (bgColor) {
                        style.backgroundColor = bgColor;
                    }

                    /**
                     * If in submenus
                     */
                    if (depth) {

                        // Calculate offset from the top level parent item
                        var $topLevelParent = $parent.parents('.msm-top-level-item');
                        var topLevelParentOffsetLeft = $topLevelParent.offset().left;

                        var $branchParent = $parent.parents('.sub-menu');
                        var branchParentTop = $branchParent.offset().top;
                        var branchParentWidth = $branchParent.width();
                        var branchParentMarginLeft = parseInt($branchParent.css('margin-left'));
                        var branchParentMarginRight = parseInt($branchParent.css('margin-left'));

                        var parentOffsetTop = $parent.offset().top;

                        // only allow left and right when in submenus
                        if (position === 'right') {

                            // if going beyond left edge
                            if (width > topLevelParentOffsetLeft) {
                                style.width = topLevelParentOffsetLeft + branchParentMarginLeft;
                            }
                            style.left = 'auto';
                            style.right = branchParentWidth;
                        } else {
                            // all others are set to left

                            // if going beyond right edge
                            if (width > bodyWidth - topLevelParentOffsetLeft - branchParentWidth) {
                                style.width = bodyWidth - topLevelParentOffsetLeft - branchParentWidth - branchParentMarginLeft;
                            }

                            style.left = branchParentWidth;
                        }
                        // for both cases
                        style.top = branchParentTop - parentOffsetTop;

                        /**
                         * Full width
                         */
                    } else if (width > bodyWidth || position === 'center_full') {

                        style.width = bodyWidth - bodyPaddingLeft - bodyPaddingRight;
                        offsetLeft = bodyPaddingLeft;

                        if (margin) {
                            style.width = bodyWidth - 2 * margin;
                            offsetLeft += margin;
                        }

                        /**
                         * Center
                         */
                    } else if (position === 'center') {

                        offsetLeft = (bodyWidth - width) / 2;

                        /**
                         * Left
                         */
                    } else if (position === 'left') {

                        style.left = 0;

                        // if going beyond right edge
                        if (parentOffset + width > bodyWidth) {
                            style.width = bodyWidth - parentOffset;
                        }

                        if (margin) {
                            style.width -= margin;
                        }

                        /**
                         * Left Edge
                         * Starts from left edge of the screen
                         */
                    } else if (position === 'left_edge') {

                        offsetLeft = 0;
                        if (margin) {
                            offsetLeft = margin;
                        }

                        /**
                         * Right
                         */
                    } else if (position === 'right') {
                        style.right = 0;
                        style.left = 'auto';

                        // if going beyond left edge
                        if (width > parentOffset) {
                            style.width = parentOffset + $parent.width();
                        }

                        if (margin) {
                            style.width -= margin;
                        }

                        /**
                         * Right Edge
                         * Ends on the right edge of the screen
                         */
                    } else if (position === 'right_edge') {
                        offsetLeft = bodyWidth - parentOffset - $parent.width();

                        if (margin) {
                            offsetLeft -= margin;
                        }

                        // if going beyond left edge
                        if (width > parentOffset) {
                            style.width = parentOffset + $parent.width();
                        }
                    }

                    $el.css(style);

                    if ('null' != offsetLeft) {
                        $el.offset({ left: offsetLeft });
                    }
                });
            };

            var $body = $('body');
            var $menuSubmenu = $('.msm-menu-item .msm-submenu, .msm-link .msm-submenu');
            var menuItems = [];
            var clickParents = [];

            setTimeout(function () {

                $menuSubmenu.each(function (i, el) {

                    var $el = $(el);
                    var $parent = $el.parent();

                    var $clickParent = $el.parents('.msm-click');

                    if ($body.outerWidth() < msm_mega_submenu.data.mobile_menu_trigger_click_bellow && !$clickParent.length) {
                        $clickParent = $el.parents('.msm-hover');
                        if ($clickParent.length) {
                            $clickParent.removeClass('msm-hover').addClass('msm-click');
                        }
                    }

                    if ($clickParent.length) {
                        clickParents.push($clickParent);

                        $clickParent.on('click', function (e) {
                            e.preventDefault();
                            var isOpen = false;
                            if ($clickParent.hasClass('open')) {
                                isOpen = true;
                            }
                            // close all
                            $.each(clickParents, function (i, clickParent) {
                                clickParent.removeClass('open');
                            });
                            if (!isOpen) {
                                $clickParent.addClass('open');
                            }
                        });
                        $el.on('click', function (e) {
                            e.stopPropagation();
                        });
                    }

                    menuItems.push({
                        $el: $el,
                        $parent: $parent
                    });
                });

                setPosition();
            }, 500);

            $(window).resize(setPosition);
        })();
    }
    //===============================================
    /**
     * Mobile Menu
     */
    (function () {

        var $mobileMenu = $('#msm-mobile-menu');
        $mobileMenu.prependTo('body');

        // Header Toggle
        $mobileMenu.find('.respmenu-header .respmenu-open').on('click', function () {
            $mobileMenu.find('.respmenu').slideToggle(200);
        });

        // Submenu Toggle
        $mobileMenu.find('.respmenu-submenu-toggle').on('click', function () {
            $(this).siblings('.sub-menu').slideToggle(200);
        });
    })();
});
//# sourceMappingURL=msm-main.js.map
