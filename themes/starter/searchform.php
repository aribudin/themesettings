<?php
/**
 * Custom search form
 *
 * @package starter 
 * @since 1.0.0
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text"><?php esc_html_e('Search for:', 'nametheme'); ?></label>
    <input type="search" class="search-field" placeholder="<?php esc_attr_e('Search...', 'nametheme'); ?>" value="<?php echo get_search_query(); ?>" name="s">
    <button type="submit" class="search-submit">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>
        <span class="screen-reader-text"><?php esc_html_e('Search', 'nametheme'); ?></span>
    </button>
</form>
