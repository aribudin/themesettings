<?php
/**
 * The footer template
 *
 * @package starter 
 * @since 1.0.0
 */

?>
    </div><!-- #content -->

<footer id="colophon" class="footer">
    <?php
    $footer_info_show = ts_get_option('footer_info_show', true);
    
    if ($footer_info_show) :
    ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="footer-widgets-grid">
                    
                    <!-- Footer Info Column -->
                    <div class="footer-widget-area footer-info">
                        <?php
                        // Footer Logo
                        $footer_logo = ts_get_option('footer_logo');
                        if (!empty($footer_logo)) {
                            $logo_url = '';
                            if (is_array($footer_logo) && isset($footer_logo['url'])) {
                                $logo_url = $footer_logo['url'];
                            } elseif (is_numeric($footer_logo)) {
                                $logo_url = wp_get_attachment_url($footer_logo);
                            }
                            
                            if ($logo_url) {
                                echo '<div class="footer-logo">';
                                echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                                echo '</div>';
                            }
                        }
                        
                        // Footer Description
                        $footer_description = ts_get_option('footer_description', '');
                        if ($footer_description) {
                            echo '<p class="footer-description">' . esc_html($footer_description) . '</p>';
                        }
                        
                        // Social Media Links
                        $facebook = ts_get_option('social_facebook', '');
                        $twitter = ts_get_option('social_twitter', '');
                        $instagram = ts_get_option('social_instagram', '');
                        
                        if ($facebook || $twitter || $instagram) :
                        ?>
                            <div class="footer-social">
                                <?php if ($facebook) : ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($twitter) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener" aria-label="Twitter">
                                        <i class="bi bi-twitter-x"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($instagram) : ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Footer Navigation Column -->
                    <div class="footer-widget-area footer-navigation">
                        <?php
                        $footer_nav_title = ts_get_option('footer_nav_title', 'Quick Links');
                        if ($footer_nav_title) {
                            echo '<h3 class="widget-title">' . esc_html($footer_nav_title) . '</h3>';
                        }
                        
                        $footer_menu = ts_get_option('footer_menu');
                        if (!empty($footer_menu)) {
                            wp_nav_menu([
                                'menu'        => $footer_menu,
                                'container'   => false,
                                'menu_class'  => 'footer-menu',
                                'fallback_cb' => false,
                                'depth'       => 1,
                            ]);
                        }
                        ?>
                    </div>
                    
                    <!-- Footer Featured Posts Column -->
                    <div class="footer-widget-area footer-posts">
                        <?php
                        $footer_posts_title = ts_get_option('footer_posts_title', 'Latest News');
                        if ($footer_posts_title) {
                            echo '<h3 class="widget-title">' . esc_html($footer_posts_title) . '</h3>';
                        }
                        
                        $footer_posts_category = ts_get_option('footer_posts_category', '');
                        $footer_posts_max = ts_get_option('footer_posts_max', 3);
                        
                        $args = [
                            'post_type'      => 'post',
                            'posts_per_page' => $footer_posts_max,
                            'post_status'    => 'publish',
                        ];
                        
                        // category
                        if (!empty($footer_posts_category)) {
                            $args['cat'] = $footer_posts_category;
                        }
                        
                        $footer_posts = new WP_Query($args);
                        
                        if ($footer_posts->have_posts()) :
                            echo '<ul class="footer-posts-list">';
                            while ($footer_posts->have_posts()) : $footer_posts->the_post();
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                </li>
                                <?php
                            endwhile;
                            echo '</ul>';
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Footer Contact Column -->
                    <div class="footer-widget-area footer-contact">
                        <h3 class="widget-title"><?php esc_html_e('Contact Us', 'nametheme'); ?></h3>
                        
                        <?php
                        $contact_phone = ts_get_option('contact_phone', '');
                        $contact_email = ts_get_option('contact_email', '');
                        
                        if ($contact_phone || $contact_email) :
                        ?>
                            <ul class="footer-contact-list">
                                <?php if ($contact_phone) : ?>
                                    <li>
                                        <i class="bi bi-telephone"></i>
                                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $contact_phone)); ?>">
                                            <?php echo esc_html($contact_phone); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ($contact_email) : ?>
                                    <li>
                                        <i class="bi bi-envelope"></i>
                                        <a href="mailto:<?php echo esc_attr($contact_email); ?>">
                                            <?php echo esc_html($contact_email); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Footer Bottom / Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-copyright">
                <?php
                $copyright_text = ts_get_option('footer_copyright_text', '© {year} MyCompany. All rights reserved.');
                // Replace {year}
                $copyright_text = str_replace('{year}', date('Y'), $copyright_text);
                echo wp_kses_post($copyright_text);
                ?>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button type="button" id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'nametheme'); ?>">
    <i class="bi bi-arrow-up-short"></i>
</button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
