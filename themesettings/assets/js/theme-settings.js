/**
 * Theme Settings - Admin JavaScript
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

(function($) {
    'use strict';

    // Main Theme Settings object
    const NTS = {
        
        /**
         * Initialize
         */
        init: function() {
            this.initColorPickers();
            this.initMediaUploaders();
            this.initRangeSliders();
            this.initIconSelects();
            this.initTypography();
            this.initDatepickers();
            this.initModals();
            this.initFormHandling();
            this.initPasswordFields();
            this.initCodeFields();
            this.initTinyMCEDarkMode();
        },

        /**
         * Initialize WordPress Color Pickers
         */
        initColorPickers: function() {
            $('.nts-color-input').each(function() {
                const $input = $(this);
                const defaultColor = $input.data('default-color') || '';
                const alpha = $input.data('alpha') === true;
                
                $input.wpColorPicker({
                    defaultColor: defaultColor,
                    change: function(event, ui) {
                        // Trigger change for gradient preview update
                        $(this).trigger('colorchange', ui.color.toString());
                    }
                });
            });
        },

        /**
         * Initialize Media Uploaders
         */
        initMediaUploaders: function() {
            $(document).on('click', '.nts-media__upload, .nts-file__upload', function(e) {
                e.preventDefault();
                
                const $container = $(this).closest('.nts-media, .nts-file');
                const allowedTypes = $container.data('allowed-types') || 'image';
                
                const frame = wp.media({
                    title: TSSettings.mediaTitle,
                    button: { text: TSSettings.mediaButton },
                    multiple: false,
                    library: {
                        type: allowedTypes
                    }
                });
                
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    
                    $container.find('.nts-media__id, .nts-file__id').val(attachment.id);
                    $container.find('.nts-media__url, .nts-file__url').val(attachment.url);
                    
                    // Update preview
                    if ($container.hasClass('nts-media')) {
                        const $preview = $container.find('.nts-media__preview');
                        $preview.addClass('nts-media__preview--has-image');
                        $preview.html('<img src="' + attachment.url + '" alt="">');
                        $container.find('.nts-media__remove').show();
                    } else {
                        const $preview = $container.find('.nts-file__preview');
                        $preview.show();
                        $preview.find('.nts-file__name').text(attachment.filename);
                        $preview.find('.nts-file__link').attr('href', attachment.url);
                        $container.find('.nts-file__remove').show();
                    }
                });
                
                frame.open();
            });
            
            // Remove media
            $(document).on('click', '.nts-media__remove, .nts-file__remove', function(e) {
                e.preventDefault();
                
                const $container = $(this).closest('.nts-media, .nts-file');
                
                $container.find('.nts-media__id, .nts-file__id').val('');
                $container.find('.nts-media__url, .nts-file__url').val('');
                
                if ($container.hasClass('nts-media')) {
                    $container.find('.nts-media__preview')
                        .removeClass('nts-media__preview--has-image')
                        .html('');
                } else {
                    $container.find('.nts-file__preview').hide();
                }
                
                $(this).hide();
            });
        },

        /**
         * Initialize Range Sliders
         */
        initRangeSliders: function() {
            $(document).on('input', '.nts-range', function() {
                const $range = $(this);
                const value = $range.val();
                const $wrapper = $range.closest('.nts-range-wrapper, .nts-gradient-picker__row');
                
                $wrapper.find('.nts-range__value').text(value + ($range.data('unit') || ''));
            });
        },

        /**
         * Initialize Icon Selects
         */
        initIconSelects: function() {

            /* ===============================
            * Toggle dropdown
            * =============================== */
            $(document).on('click', '.nts-icon-select__preview', function(e) {
                e.stopPropagation();

                const $select = $(this).closest('.nts-icon-select');

                // Close others
                $('.nts-icon-select').not($select).removeClass('is-open');

                $select.toggleClass('is-open');
            });

            /* ===============================
            * Select icon from list
            * =============================== */
            $(document).on('click', '.nts-icon-select__item', function() {
                const $item = $(this);
                const $select = $item.closest('.nts-icon-select');
                const value = $item.data('value');

                // Active state
                $select.find('.nts-icon-select__item')
                    .removeClass('nts-icon-select__item--active');
                $item.addClass('nts-icon-select__item--active');

                // Update hidden input
                $select.find('.nts-icon-select__input').val(value);

                // Update preview
                $select.find('.nts-icon-select__preview')
                    .html('<i class="bi ' + value + '"></i>');

                // Sync custom input (jika ada)
                $select.find('.nts-icon-select__custom-input').val(value);

                $select.removeClass('is-open');
            });

            /* ===============================
            * Custom icon input (bi-facebook, etc)
            * =============================== */
            $(document).on('input', '.nts-icon-select__custom-input', function() {
                const value = $(this).val().trim();
                const $select = $(this).closest('.nts-icon-select');

                if (!value) {
                    $select.find('.nts-icon-select__preview')
                        .html('<i class="bi bi-plus"></i>');
                    $select.find('.nts-icon-select__input').val('');
                    return;
                }

                // Update hidden value
                $select.find('.nts-icon-select__input').val(value);

                // Update preview
                $select.find('.nts-icon-select__preview')
                    .html('<i class="bi ' + value + '"></i>');

                // Remove active state from preset icons
                $select.find('.nts-icon-select__item')
                    .removeClass('nts-icon-select__item--active');
            });

            /* ===============================
            * Search icons (existing feature)
            * =============================== */
            $(document).on('input', '.nts-icon-select__search', function() {
                const search = $(this).val().toLowerCase();
                const $list = $(this).siblings('.nts-icon-select__list');

                $list.find('.nts-icon-select__item').each(function() {
                    const label = ($(this).data('label') || '').toLowerCase();
                    const value = ($(this).data('value') || '').toLowerCase();

                    if (label.includes(search) || value.includes(search)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            /* ===============================
            * Close on outside click
            * =============================== */
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.nts-icon-select').length) {
                    $('.nts-icon-select').removeClass('is-open');
                }
            });
        },

        /**
         * Update gradient preview
         */
        updateGradientPreview: function($picker) {
            const type = $picker.find('.nts-gradient-type').val() || 'linear';
            const angle = $picker.find('.nts-gradient-angle input').val() || 90;
            const color1 = $picker.find('.nts-gradient-color').eq(0).val() || '#000000';
            const color2 = $picker.find('.nts-gradient-color').eq(1).val() || '#ffffff';
            
            let gradient;
            if (type === 'radial') {
                gradient = 'radial-gradient(circle, ' + color1 + ', ' + color2 + ')';
            } else {
                gradient = 'linear-gradient(' + angle + 'deg, ' + color1 + ', ' + color2 + ')';
            }
            
            $picker.find('.nts-gradient-picker__preview').css('background', gradient);
        },

        /**
         * Initialize Typography Selects
         */
        initTypography: function() {
            $(document).on('change', '.nts-typography__select', function() {
                const font = $(this).val();
                const $preview = $(this).siblings('.nts-typography__preview');
                
                if (font && font !== 'system') {
                    // Load font
                    WebFont.load({
                        google: {
                            families: [font]
                        }
                    });
                    
                    $preview.css('font-family', '"' + font + '", sans-serif');
                } else {
                    $preview.css('font-family', 'inherit');
                }
            });
        },

        /**
         * Initialize Datepickers
         */
        initDatepickers: function() {
            $('.nts-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        },

        /**
         * Initialize Modals
         */
        initModals: function() {
            // Import button
            $('#nts-import-btn').on('click', function() {
                $('#nts-import-modal').addClass('is-open');
            });
            
            // Export button
            $('#nts-export-btn').on('click', function() {
                NTS.exportSettings();
            });
            
            // Reset button
            $('#nts-reset-btn').on('click', function() {
                if (confirm('Are you sure you want to reset all settings? This cannot be undone.')) {
                    NTS.resetSettings();
                }
            });
            
            // Close modal
            $(document).on('click', '.nts-modal__close, .nts-modal__cancel, .nts-modal__overlay', function() {
                $(this).closest('.nts-modal').removeClass('is-open');
            });
            
            // Import confirm
            $('#nts-import-confirm').on('click', function() {
                const data = $('#nts-import-data').val();
                NTS.importSettings(data);
            });

            // Upload JSON file handler
            $('#nts-upload-json-btn').on('click', function() {
                $('#nts-json-file-input').click();
            });

            $('#nts-json-file-input').on('change', function(e) {
                const file = e.target.files[0];
                
                if (!file) return;
                
                // Validasi file JSON
                if (!file.name.endsWith('.json')) {
                    NTS.showNotification('Please select a valid JSON file', 'error');
                    return;
                }
                
                // Baca file
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    try {
                        const content = event.target.result;
                        // Validasi JSON
                        JSON.parse(content);
                        
                        // Tampilkan ke textarea
                        $('#nts-import-data').val(content);
                        
                        // Tampilkan nama file
                        $('#nts-file-name').text('✓ ' + file.name);
                        
                        NTS.showNotification('File loaded successfully. Review and click Import to continue.', 'success');
                    } catch (error) {
                        NTS.showNotification('Invalid JSON file format', 'error');
                        $('#nts-file-name').text('');
                    }
                };
                
                reader.onerror = function() {
                    NTS.showNotification('Error reading file', 'error');
                };
                
                reader.readAsText(file);
            });
        },

        /**
         * Initialize Form Handling
         */
        initFormHandling: function() {
            const $form = $('#nts-settings-form');
            
            $form.on('submit', function() {
                $('.nts-save-status').text('Updating settings...').addClass('is-saving');
            });
            
            // Show saved message
            if (window.NTS_SETTINGS_SAVED) {
                setTimeout(function() {
                    NTS.showNotification('Settings saved successfully!', 'success');
                }, 300);
            }
        },

        /**
         * Export Settings
         */
        exportSettings: function() {
            const formData = new FormData(document.getElementById('nts-settings-form'));
            const settings = {};
            
            for (let [key, value] of formData.entries()) {
                // Parse the nested option format
                const match = key.match(/ts_options\[([^\]]+)\]/);
                if (match) {
                    settings[match[1]] = value;
                }
            }
            
            const json = JSON.stringify(settings, null, 2);
            
            // Create download
            const blob = new Blob([json], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'theme-settings-' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            NTS.showNotification('Settings exported successfully!', 'success');
        },

        /**
         * Import Settings
         */
        importSettings: function(json) {
            try {
                const settings = JSON.parse(json);
                
                // Send to server
                $.ajax({
                    url: TSSettings.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ts_import_settings',
                        nonce: TSSettings.nonce,
                        settings: JSON.stringify(settings)
                    },
                    success: function(response) {
                        if (response.success) {
                            NTS.showNotification('Settings imported successfully!', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            NTS.showNotification('Import failed: ' + response.data, 'error');
                        }
                    },
                    error: function() {
                        NTS.showNotification('Import failed. Please try again.', 'error');
                    }
                });
                
                $('#nts-import-modal').removeClass('is-open');
                
            } catch (e) {
                NTS.showNotification('Invalid JSON format', 'error');
            }
        },

        /**
         * Reset Settings
         */
        resetSettings: function() {
            $.ajax({
                url: TSSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ts_reset_settings',
                    nonce: TSSettings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        NTS.showNotification('Settings reset successfully!', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        NTS.showNotification('Reset failed', 'error');
                    }
                }
            });
        },

        /**
         * Initialize Password Fields
         */
        initPasswordFields: function() {
            $(document).on('click', '.nts-password-toggle', function() {
                const $button = $(this);
                const $input = $button.siblings('.nts-password-input');
                const $icon = $button.find('i');
                
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        },

        /**
         * Initialize Code Fields
         */
        initCodeFields: function() {
            // Copy to clipboard
            $(document).on('click', '.nts-code-field__copy', function() {
                const $button = $(this);
                const $codeField = $button.closest('.nts-code-field');
                const $textarea = $codeField.find('.nts-code-input');
                
                // Copy to clipboard
                $textarea.select();
                document.execCommand('copy');
                
                // Show feedback
                const $icon = $button.find('i');
                const originalClass = $icon.attr('class');
                $icon.removeClass('bi-clipboard').addClass('bi-check');
                
                setTimeout(function() {
                    $icon.attr('class', originalClass);
                }, 2000);
            });
            
            // Tab key support for indentation
            $(document).on('keydown', '.nts-code-input', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const textarea = this;
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const value = textarea.value;
                    
                    // Insert tab (2 spaces)
                    textarea.value = value.substring(0, start) + '  ' + value.substring(end);
                    textarea.selectionStart = textarea.selectionEnd = start + 2;
                }
            });
        },

        /**
         * Initialize TinyMCE Dark Mode
         */
        initTinyMCEDarkMode: function() {
            if (typeof TSSettings !== 'undefined' && TSSettings.themeSkin === 'dark') {
                // Wait for TinyMCE to initialize
                $(document).on('tinymce-editor-init', function(event, editor) {
                    const darkCSS = `
                        body.mce-content-body {
                            background: #22272e !important;
                            color: #e6edf3 !important;
                        }
                        body.mce-content-body p,
                        body.mce-content-body h1,
                        body.mce-content-body h2,
                        body.mce-content-body h3,
                        body.mce-content-body h4,
                        body.mce-content-body h5,
                        body.mce-content-body h6,
                        body.mce-content-body li,
                        body.mce-content-body span,
                        body.mce-content-body div,
                        body.mce-content-body blockquote,
                        body.mce-content-body code,
                        body.mce-content-body pre {
                            color: #e6edf3 !important;
                        }
                        body.mce-content-body a {
                            color: #539bf5 !important;
                        }
                        body.mce-content-body code {
                            background: #2d333b !important;
                            border-color: #444c56 !important;
                        }
                        body.mce-content-body blockquote {
                            border-left-color: #444c56 !important;
                        }
                        body.mce-content-body hr {
                            border-color: #444c56 !important;
                        }
                        body.mce-content-body table,
                        body.mce-content-body table td,
                        body.mce-content-body table th {
                            border-color: #444c56 !important;
                            color: #e6edf3 !important;
                        }
                    `;
                    
                    // Inject CSS into TinyMCE iframe
                    if (editor.iframeElement) {
                        const iframeDoc = editor.iframeElement.contentDocument || editor.iframeElement.contentWindow.document;
                        const style = iframeDoc.createElement('style');
                        style.type = 'text/css';
                        style.id = 'tinymce-dark-mode';
                        style.appendChild(iframeDoc.createTextNode(darkCSS));
                        iframeDoc.head.appendChild(style);
                    }
                });
            }
        },

        /**
         * Show Notification
         */
        showNotification: function(message, type) {
            type = type || 'success';
            
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-x-circle-fill',
                warning: 'bi-exclamation-circle-fill'
            };
            
            const $notification = $(`
                <div class="nts-notification nts-notification--${type}">
                    <i class="bi ${icons[type]} nts-notification__icon"></i>
                    <span class="nts-notification__message">${message}</span>
                </div>
            `);
            
            $('#nts-notifications').append($notification);
            
            setTimeout(function() {
                $notification.fadeOut(200, function() {
                    $(this).remove();
                });
            }, 4000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        NTS.init();
    });

})(jQuery);