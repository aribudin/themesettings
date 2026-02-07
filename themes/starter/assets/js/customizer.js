/**
 * Starter Starter Theme - Customizer Preview
 *
 * @package starter 
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Site title
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.logo-text').text(to);
        });
    });

    // Site description
    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

})(jQuery);
