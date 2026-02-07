<?php
/**
 * The sidebar template
 *
 * @package starter 
 * @since 1.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="sidebar" role="complementary">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>
