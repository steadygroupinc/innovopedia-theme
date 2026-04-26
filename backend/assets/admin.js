/** Backend */
var FOXIZ_ADMIN_HELPERS = (function (Module, $) {
    'use strict';

    const ID_REGEX = /id=["']?(\d+)["']?/;
    const SLUG_REGEX = /slug=["']?([\w\-]+)["']?/;

    Module.init = function () {
        Module.initMegaMenuInfo();
        Module.templateEditLink();
        Module.avatarUploadHandler();
        Module.initColorPickers();
    };

    /**
     * Initialize Mega Menu Information
     */
    Module.initMegaMenuInfo = function () {
        $('.rb-menu-setting').each(function () {
            const $input = $(this);
            const $titleWrap = $input.closest('.menu-item').find('.item-title');

            if ($input.val()?.length && $input.val() !== '0') {
                Module.addMenuInfo($input, $titleWrap);
            }

            $input.on('change', function () {
                const value = $input.val();
                if (value?.length && value !== '0') {
                    Module.addMenuInfo($input, $titleWrap, true);
                } else {
                    Module.removeMenuInfo($titleWrap);
                }
            });
        });
    };

    /**
     * Add Mega Menu Info label
     * @param {jQuery} $input - The input element triggering the info
     * @param {jQuery} $titleWrap - Target title wrapper
     * @param {Boolean} animate - Optional animation flag
     */
    Module.addMenuInfo = function ($input, $titleWrap, animate = false) {

        if ($titleWrap.children('.rb-mega-info').length) return;

        const isCategory = $input.hasClass('mega-category-setting');
        const labelClass = isCategory ? 'label-category' : 'label-columns';
        const labelText = isCategory ? 'Mega Category' : 'Mega Columns';
        const $info = $(`<span class="rb-mega-info ${labelClass}">${labelText}</span>`);

        if (animate) {
            $info.hide().appendTo($titleWrap).fadeIn(250);
        } else {
            $titleWrap.append($info);
        }
    };

    /**
     * Remove Mega Menu Info label
     * @param {jQuery} $titleWrap - Target title wrapper
     */
    Module.removeMenuInfo = function ($titleWrap) {
        $titleWrap.find('.rb-mega-info').fadeOut(250, function () {
            $(this).remove();
        });
    };

    /**
     * Render the wrapper content based on the shortcode value
     * @param {jQuery} $wrapper
     * @param {string} shortcode
     */
    Module.renderWrapperContent = function ($wrapper, shortcode) {
        $wrapper.empty();

        const idMatch = ID_REGEX?.exec(shortcode);
        const slugMatch = SLUG_REGEX?.exec(shortcode);

        if (idMatch?.[1]) {
            const postId = idMatch[1];
            const adminBase = typeof ajaxurl !== 'undefined'
                ? ajaxurl.replace('admin-ajax.php', '')
                : `${window.location.origin}/wp-admin/`;

            const editUrl = `${adminBase}post.php?post=${postId}&action=elementor`;

            $wrapper.append(`
            <a href="${editUrl}" target="_blank" class="ruby-edit-template-btn">
                Edit Template
            </a>
        `);
        } else if (slugMatch?.[1]) {
            const slug = slugMatch[1];
            $wrapper.append(`
            <div class="ruby-edit-template-notice">
                Please change <code>slug="${slug}"</code> to <code>id="..."</code> in the shortcode to enable the Edit Template link.
            </div>
        `);
        }
    };

    /**
     * Throttle execution of a function
     * @param {Function} fn
     * @param {number} delay
     * @returns {Function}
     */
    const throttle = (fn, delay) => {
        let timeout = null;
        return (...args) => {
            if (timeout) return;
            timeout = setTimeout(() => {
                fn.apply(this, args);
                timeout = null;
            }, delay);
        };
    };

    /**
     * Initialize edit link rendering for each textarea
     */
    Module.templateEditLink = function () {

        const $wpBody = $('#wpbody');
        const $textareas = $wpBody.find('textarea.ruby-template-input');

        if (!$textareas.length) {
            return;
        }

        $textareas.each(function () {
            const $textarea = $(this);

            let $wrapper = $textarea.next('.ruby-edit-template-wrapper');
            if (!$wrapper.length) {
                $wrapper = $('<div class="ruby-edit-template-wrapper"></div>');
                $textarea.after($wrapper);
            }

            // Initial render
            Module.renderWrapperContent($wrapper, $textarea.val());

            // Watch changes with throttled handler
            const updateWrapper = throttle(() => {
                Module.renderWrapperContent($wrapper, $textarea.val());
            }, 150);

            $textarea.on('input change', updateWrapper);
        });
    };


    /**
     * Avatar upload button handler
     */
    Module.avatarUploadHandler = function () {
        const $uploadButton = $('#rb-upload-avatar');
        const $removeButton = $('#rb-remove-avatar');
        const $preview = $('#rb-avatar-preview');
        const $avatarIdInput = $('#rb-avatar-id');

        if (!$uploadButton.length || !$preview.length || !$removeButton.length || !$avatarIdInput.length) {
            return;
        }

        // Upload handler
        $uploadButton.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const avatarUploader = wp.media({
                title: 'Select or Upload Avatar',
                button: {text: 'Use This Media'},
                multiple: false
            });

            avatarUploader.on('select', function () {
                const attachment = avatarUploader.state().get('selection').first().toJSON();
                $preview.html(`<img src="${attachment.url}" width="100" height="100" />`);
                $avatarIdInput.val(attachment.id);
                $removeButton.show();
            });

            avatarUploader.open();
        });

        // Remove handler
        $removeButton.on('click', function (e) {
            e.preventDefault();
            $preview.empty();
            $avatarIdInput.val('');
            $removeButton.hide();
        });

        // Initial state: show/hide remove button based on image presence
        if ($preview.find('img').length) {
            $removeButton.show();
        } else {
            $removeButton.hide();
        }
    };

    /**
     * Initialize Color Pickers for menu settings
     */
    Module.initColorPickers = function () {
        // Check if wpColorPicker is available
        if (typeof $.fn.wpColorPicker !== 'function') {
            return;
        }

        // Initialize on page load for existing items
        Module.activateColorPickers();

        // Handle newly added menu items via WordPress AJAX
        $(document).on('menu-item-added', function () {
            setTimeout(function () {
                Module.activateColorPickers();
            }, 100);
        });
    };

    /**
     * Activate color pickers on elements that haven't been initialized
     */
    Module.activateColorPickers = function () {
        $('.rb-color-picker').each(function () {
            const $input = $(this);
            // Only initialize if not already wrapped by wp-picker-container
            if (!$input.closest('.wp-picker-container').length) {
                $input.wpColorPicker();
            }
        });
    };

    return Module;

}(FOXIZ_ADMIN_HELPERS || {}, jQuery));

jQuery(document).ready(() => {
    FOXIZ_ADMIN_HELPERS.init();
});
