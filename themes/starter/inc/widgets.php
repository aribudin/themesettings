<?php
/**
 * Custom Widgets
 *
 * @package starter 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Recent Posts Widget with Thumbnails
 */
class starter_Recent_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'starter_recent_posts',
            esc_html__('starter : Recent Posts', 'nametheme'),
            [
                'description' => esc_html__('Display recent posts with thumbnails.', 'nametheme'),
                'customize_selective_refresh' => true,
            ]
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'nametheme');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : true;
        
        $query = new WP_Query([
            'post_type'           => 'post',
            'posts_per_page'      => $number,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ]);
        
        if (!$query->have_posts()) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="widget-recent-posts-list">';
        
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <li>
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                <?php endif; ?>
                <div class="post-content">
                    <h4 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <?php if ($show_date) : ?>
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                    <?php endif; ?>
                </div>
            </li>
            <?php
        }
        
        echo '</ul>';
        
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'nametheme');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'nametheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts:', 'nametheme'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>">
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php esc_html_e('Display post date?', 'nametheme'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }
}

/**
 * About Widget
 */
class starter_About_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'starter_about',
            esc_html__('starter : About', 'nametheme'),
            [
                'description' => esc_html__('Display about information with image.', 'nametheme'),
                'customize_selective_refresh' => true,
            ]
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title'], $instance, $this->id_base) : '';
        $image = !empty($instance['image']) ? $instance['image'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        if ($image) {
            echo '<div class="about-image"><img src="' . esc_url($image) . '" alt=""></div>';
        }
        
        if ($description) {
            echo '<div class="about-description">' . wp_kses_post($description) . '</div>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $image = !empty($instance['image']) ? $instance['image'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'nametheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php esc_html_e('Image URL:', 'nametheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="url" value="<?php echo esc_url($image); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php esc_html_e('Description:', 'nametheme'); ?></label>
            <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['image'] = esc_url_raw($new_instance['image']);
        $instance['description'] = wp_kses_post($new_instance['description']);
        return $instance;
    }
}

/**
 * Social Links Widget
 */
class starter_Social_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'starter_social',
            esc_html__('starter : Social Links', 'nametheme'),
            [
                'description' => esc_html__('Display social media links.', 'nametheme'),
                'customize_selective_refresh' => true,
            ]
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title'], $instance, $this->id_base) : '';
        
        $social_links = starter_get_social_links();
        
        if (empty($social_links)) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<div class="social-links">';
        foreach ($social_links as $link) {
            printf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s</a>',
                esc_url($link['url']),
                esc_attr($link['label']),
                starter_get_icon($link['icon'], 20)
            );
        }
        echo '</div>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Follow Us', 'nametheme');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'nametheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p class="description">
            <?php esc_html_e('Social links are configured in Theme Settings.', 'nametheme'); ?>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}

/**
 * Register widgets
 */
function starter_register_widgets() {
    register_widget('starter_Recent_Posts_Widget');
    register_widget('starter_About_Widget');
    register_widget('starter_Social_Widget');
}
add_action('widgets_init', 'starter_register_widgets');
