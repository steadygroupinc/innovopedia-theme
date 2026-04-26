(function($) {
    'use strict';

    $(document).ready(function() {
        const $trigger = $('.hamburger-trigger');
        const $panel = $('.innovopedia-side-panel');
        const $overlay = $('.side-overlay');
        const $close = $('.side-close');

        function openMenu() {
            $panel.addClass('is-active');
            $overlay.addClass('is-active');
            $('body').css('overflow', 'hidden');
        }

        function closeMenu() {
            $panel.removeClass('is-active');
            $overlay.removeClass('is-active');
            $('body').css('overflow', '');
        }

        $trigger.on('click', openMenu);
        $close.on('click', closeMenu);
        $overlay.on('click', closeMenu);

        // Escape key to close
        $(document).on('keydown', function(e) {
            if (e.key === "Escape") closeMenu();
        });
    });

})(jQuery);
