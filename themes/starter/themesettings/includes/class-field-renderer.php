<?php
/**
 * Field Renderer Class
 * 
 * Renders all field types in the admin interface
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Field_Renderer {
    
    /**
     * Render field based on type
     */
    public static function render($field, $value = null) {
        $type = isset($field['type']) ? $field['type'] : 'text';
        $method = 'render_' . $type;
        
        // Get default value
        if ($value === null) {
            $value = isset($field['default']) ? $field['default'] : '';
        }
        
        // Field wrapper
        $wrapper_class = 'nts-field nts-field--' . $type;
        if (isset($field['class'])) {
            $wrapper_class .= ' ' . $field['class'];
        }
        
        echo '<div class="' . esc_attr($wrapper_class) . '">';
        
        // Label
        if (isset($field['label']) && !empty($field['label'])) {
            echo '<label class="nts-field__label" for="' . esc_attr($field['id']) . '">';
            echo esc_html($field['label']);
            if (isset($field['required']) && $field['required']) {
                echo '<span class="nts-required">*</span>';
            }
            echo '</label>';
        }
        
        // Field content
        echo '<div class="nts-field__content">';
        
        if (method_exists(__CLASS__, $method)) {
            self::$method($field, $value);
        } else {
            self::render_text($field, $value);
        }
        
        // Description
        if (isset($field['desc']) && !empty($field['desc'])) {
            echo '<p class="nts-field__desc">' . esc_html($field['desc']) . '</p>';
        }
        
        echo '</div>'; // .nts-field__content
        echo '</div>'; // .nts-field
    }
    
    /**
     * Get field name attribute
     */
    public static function get_name($field) {
        return 'ts_options[' . esc_attr($field['id']) . ']';
    }
    
    /**
     * Render text field
     */
    public static function render_text($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        ?>
        <input 
            type="text" 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            value="<?php echo esc_attr($value); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            class="nts-input nts-input--text"
        >
        <?php
    }
    
    /**
     * Render textarea field
     */
    public static function render_textarea($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        $rows = isset($field['rows']) ? $field['rows'] : 4;
        ?>
        <textarea 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            rows="<?php echo esc_attr($rows); ?>"
            class="nts-input nts-input--textarea"
        ><?php echo esc_textarea($value); ?></textarea>
        <?php
    }
    
    /**
     * Render WYSIWYG editor
     */
    public static function render_wysiwyg($field, $value) {
        $settings = [
            'textarea_name' => self::get_name($field),
            'textarea_rows' => isset($field['rows']) ? $field['rows'] : 8,
            'media_buttons' => isset($field['media_buttons']) ? $field['media_buttons'] : true,
            'teeny' => isset($field['teeny']) ? $field['teeny'] : false,
            'quicktags' => true,
        ];
        
        wp_editor($value, $field['id'], $settings);
    }
    
    /**
     * Render number field
     */
    public static function render_number($field, $value) {
        $min = isset($field['min']) ? $field['min'] : '';
        $max = isset($field['max']) ? $field['max'] : '';
        $step = isset($field['step']) ? $field['step'] : 1;
        ?>
        <input 
            type="number" 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            value="<?php echo esc_attr($value); ?>"
            min="<?php echo esc_attr($min); ?>"
            max="<?php echo esc_attr($max); ?>"
            step="<?php echo esc_attr($step); ?>"
            class="nts-input nts-input--number"
        >
        <?php
        if ($min !== '' || $max !== '') {
            echo '<span class="nts-input__hint">';
            if ($min !== '' && $max !== '') {
                printf(__('Range: %s - %s', 'nametheme'), $min, $max);
            } elseif ($min !== '') {
                printf(__('Min: %s', 'nametheme'), $min);
            } else {
                printf(__('Max: %s', 'nametheme'), $max);
            }
            echo '</span>';
        }
    }
    
    /**
     * Render URL field
     */
    public static function render_url($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : 'https://';
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-link-45deg"></i></span>
            <input 
                type="url" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_url($value); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>"
                class="nts-input nts-input--url"
            >
        </div>
        <?php
    }
    
    /**
     * Render email field
     */
    public static function render_email($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : 'email@example.com';
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-envelope"></i></span>
            <input 
                type="email" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>"
                class="nts-input nts-input--email"
            >
        </div>
        <?php
    }
    
    /**
     * Render tel field
     */
    public static function render_tel($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '+1 (123) 456-7890';
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-telephone"></i></span>
            <input 
                type="tel" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>"
                class="nts-input nts-input--tel"
            >
        </div>
        <?php
    }
    
    /**
     * Render switch/toggle field
     */
    public static function render_switch($field, $value) {
        $checked = !empty($value) ? 'checked' : '';
        ?>
        <!-- Hidden input -->
        <input type="hidden" name="<?php echo self::get_name($field); ?>" value="0">
        <label class="nts-switch">
            <input 
                type="checkbox" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="1"
                <?php echo $checked; ?>
            >
            <span class="nts-switch__slider"></span>
            <?php if (isset($field['switch_label'])): ?>
                <span class="nts-switch__text"><?php echo esc_html($field['switch_label']); ?></span>
            <?php endif; ?>
        </label>
        <?php
    }
    
    /**
     * Render checkbox field
     */
    public static function render_checkbox($field, $value) {
        $checked = !empty($value) ? 'checked' : '';
        ?>
        <!-- Hidden input -->
        <input type="hidden" name="<?php echo self::get_name($field); ?>" value="0">
        <label class="nts-checkbox">
            <input 
                type="checkbox" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="1"
                <?php echo $checked; ?>
            >
            <span class="nts-checkbox__mark"></span>
            <?php if (isset($field['checkbox_label'])): ?>
                <span class="nts-checkbox__text"><?php echo esc_html($field['checkbox_label']); ?></span>
            <?php endif; ?>
        </label>
        <?php
    }
    
    /**
     * Render checkbox group field
     */
    public static function render_checkbox_group($field, $value) {
        if (!is_array($value)) {
            $value = [];
        }
        $options = isset($field['options']) ? $field['options'] : [];
        ?>
        <div class="nts-checkbox-group">
            <?php foreach ($options as $key => $label): ?>
                <label class="nts-checkbox">
                    <input 
                        type="checkbox" 
                        name="<?php echo self::get_name($field); ?>[]"
                        value="<?php echo esc_attr($key); ?>"
                        <?php checked(in_array($key, $value)); ?>
                    >
                    <span class="nts-checkbox__mark"></span>
                    <span class="nts-checkbox__text"><?php echo esc_html($label); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    /**
     * Render radio field
     */
    public static function render_radio($field, $value) {
        $options = isset($field['options']) ? $field['options'] : [];
        ?>
        <div class="nts-radio-group">
            <?php foreach ($options as $key => $label): ?>
                <label class="nts-radio">
                    <input 
                        type="radio" 
                        name="<?php echo self::get_name($field); ?>"
                        value="<?php echo esc_attr($key); ?>"
                        <?php checked($value, $key); ?>
                    >
                    <span class="nts-radio__mark"></span>
                    <span class="nts-radio__text"><?php echo esc_html($label); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }
    
    /**
     * Render select field
     */
    public static function render_select($field, $value) {
        $options = isset($field['options']) ? $field['options'] : [];
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : __('Select an option', 'nametheme');
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            class="nts-select"
        >
            <option value=""><?php echo esc_html($placeholder); ?></option>
            <?php foreach ($options as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    /**
     * Render multiselect field
     */
    public static function render_multiselect($field, $value) {
        if (!is_array($value)) {
            $value = [];
        }
        $options = isset($field['options']) ? $field['options'] : [];
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>[]"
            class="nts-select nts-select--multi"
            multiple
        >
            <?php foreach ($options as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected(in_array($key, $value)); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="nts-field__hint"><?php _e('Hold Ctrl/Cmd to select multiple', 'nametheme'); ?></p>
        <?php
    }
    
    /**
     * Render color picker field
     */
    public static function render_color($field, $value) {
        $default = isset($field['default']) ? $field['default'] : '#000000';
        $alpha = isset($field['alpha']) ? $field['alpha'] : false;
        ?>
        <div class="nts-color-picker">
            <input 
                type="text" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="nts-color-input"
                data-default-color="<?php echo esc_attr($default); ?>"
                data-alpha="<?php echo $alpha ? 'true' : 'false'; ?>"
            >
        </div>
        <?php
    }
    
    /**
     * Render media/image field
     */
    public static function render_media($field, $value) {
        $image_url = '';
        $image_id = 0;
        
        if (is_array($value)) {
            $image_id = isset($value['id']) ? $value['id'] : 0;
            $image_url = isset($value['url']) ? $value['url'] : '';
        } elseif (is_numeric($value)) {
            $image_id = $value;
            $image_url = wp_get_attachment_url($value);
        }
        
        $preview_class = $image_url ? 'nts-media__preview--has-image' : '';
        $allowed_types = isset($field['allowed_types']) ? implode(',', $field['allowed_types']) : 'image';
        ?>
        <div class="nts-media" data-allowed-types="<?php echo esc_attr($allowed_types); ?>">
            <div class="nts-media__preview <?php echo esc_attr($preview_class); ?>">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                <?php endif; ?>
            </div>
            <div class="nts-media__actions">
                <button type="button" class="nts-btn nts-btn--secondary nts-media__upload">
                    <i class="bi bi-upload"></i>
                    <?php _e('Upload', 'nametheme'); ?>
                </button>
                <button type="button" class="nts-btn nts-btn--danger nts-media__remove" 
                        style="<?php echo !$image_url ? 'display:none;' : ''; ?>">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <input type="hidden" name="<?php echo self::get_name($field); ?>[id]" 
                   value="<?php echo esc_attr($image_id); ?>" class="nts-media__id">
            <input type="hidden" name="<?php echo self::get_name($field); ?>[url]" 
                   value="<?php echo esc_url($image_url); ?>" class="nts-media__url">
        </div>
        <?php
    }
    
    /**
     * Render range/slider field
     */
    public static function render_range($field, $value) {
        $min = isset($field['min']) ? $field['min'] : 0;
        $max = isset($field['max']) ? $field['max'] : 100;
        $step = isset($field['step']) ? $field['step'] : 1;
        $unit = isset($field['unit']) ? $field['unit'] : '';
        ?>
        <div class="nts-range-wrapper">
            <input 
                type="range" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                min="<?php echo esc_attr($min); ?>"
                max="<?php echo esc_attr($max); ?>"
                step="<?php echo esc_attr($step); ?>"
                class="nts-range"
            >
            <div class="nts-range__info">
                <span class="nts-range__min"><?php echo esc_html($min . $unit); ?></span>
                <span class="nts-range__value"><?php echo esc_html($value . $unit); ?></span>
                <span class="nts-range__max"><?php echo esc_html($max . $unit); ?></span>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render typography field
     */
    public static function render_typography($field, $value) {
        // Popular Google Fonts list
        $fonts = [
            'system' => 'System Default',

            'Abril Fatface' => 'Abril Fatface',
            'Alegreya' => 'Alegreya',
            'Alegreya Sans' => 'Alegreya Sans',
            'Alfa Slab One' => 'Alfa Slab One',
            'Amatic SC' => 'Amatic SC',
            'Anonymous Pro' => 'Anonymous Pro',
            'Anton' => 'Anton',
            'Archivo' => 'Archivo',
            'Archivo Black' => 'Archivo Black',
            'Archivo Narrow' => 'Archivo Narrow',
            'Arial' => 'Arial',
            'Arvo' => 'Arvo',
            'Asap' => 'Asap',
            'Assistant' => 'Assistant',

            'Barlow' => 'Barlow',
            'Barlow Condensed' => 'Barlow Condensed',
            'Bangers' => 'Bangers',
            'Be Vietnam Pro' => 'Be Vietnam Pro',
            'Bebas Neue' => 'Bebas Neue',
            'Bitter' => 'Bitter',
            'Black Ops One' => 'Black Ops One',

            'Cabin' => 'Cabin',
            'Cairo' => 'Cairo',
            'Cardo' => 'Cardo',
            'Catamaran' => 'Catamaran',
            'Chakra Petch' => 'Chakra Petch',
            'Chivo' => 'Chivo',
            'Cinzel' => 'Cinzel',
            'Comfortaa' => 'Comfortaa',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Crimson Text' => 'Crimson Text',
            'Coustard' => 'Coustard',

            'Dancing Script' => 'Dancing Script',
            'Didact Gothic' => 'Didact Gothic',
            'DM Mono' => 'DM Mono',
            'DM Sans' => 'DM Sans',
            'DM Serif Display' => 'DM Serif Display',
            'DM Serif Text' => 'DM Serif Text',
            'Domine' => 'Domine',

            'EB Garamond' => 'EB Garamond',
            'Encode Sans' => 'Encode Sans',
            'Epilogue' => 'Epilogue',
            'Exo 2' => 'Exo 2',

            'Figtree' => 'Figtree',
            'Fira Code' => 'Fira Code',
            'Fredoka' => 'Fredoka',
            'Francois One' => 'Francois One',

            'General Sans' => 'General Sans',
            'Gentium Plus' => 'Gentium Plus',
            'Georgia' => 'Georgia',
            'Gloria Hallelujah' => 'Gloria Hallelujah',
            'Glegoo' => 'Glegoo',

            'Handlee' => 'Handlee',
            'Hammersmith One' => 'Hammersmith One',
            'Heebo' => 'Heebo',
            'Hind' => 'Hind',

            'IBM Plex Mono' => 'IBM Plex Mono',
            'IBM Plex Sans' => 'IBM Plex Sans',
            'Inconsolata' => 'Inconsolata',
            'Indie Flower' => 'Indie Flower',
            'Inter' => 'Inter',
            'Istok Web' => 'Istok Web',

            'JetBrains Mono' => 'JetBrains Mono',
            'Josefin Sans' => 'Josefin Sans',
            'Jost' => 'Jost',

            'Kanit' => 'Kanit',
            'Karla' => 'Karla',
            'Kreon' => 'Kreon',
            'Kumbh Sans' => 'Kumbh Sans',

            'Lato' => 'Lato',
            'Lexend' => 'Lexend',
            'Libre Baskerville' => 'Libre Baskerville',
            'Literata' => 'Literata',
            'Lobster' => 'Lobster',
            'Lora' => 'Lora',
            'Luckiest Guy' => 'Luckiest Guy',

            'M PLUS 1p' => 'M PLUS 1p',
            'Mada' => 'Mada',
            'Manrope' => 'Manrope',
            'Maitree' => 'Maitree',
            'Merriweather' => 'Merriweather',
            'Monoton' => 'Monoton',
            'Montserrat' => 'Montserrat',
            'Mulish' => 'Mulish',

            'Nanum Gothic' => 'Nanum Gothic',
            'Neuton' => 'Neuton',
            'Noto Sans' => 'Noto Sans',
            'Noto Sans Display' => 'Noto Sans Display',
            'Noto Sans JP' => 'Noto Sans JP',
            'Noto Sans KR' => 'Noto Sans KR',
            'Noto Sans SC' => 'Noto Sans SC',
            'Noto Sans TC' => 'Noto Sans TC',
            'Noto Serif' => 'Noto Serif',
            'Nunito' => 'Nunito',

            'Old Standard TT' => 'Old Standard TT',
            'Onest' => 'Onest',
            'Open Sans' => 'Open Sans',
            'Orbitron' => 'Orbitron',
            'Oswald' => 'Oswald',
            'Outfit' => 'Outfit',
            'Overpass' => 'Overpass',
            'Oxygen' => 'Oxygen',

            'Pacifico' => 'Pacifico',
            'Patrick Hand' => 'Patrick Hand',
            'Paytone One' => 'Paytone One',
            'Play' => 'Play',
            'Playfair Display' => 'Playfair Display',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'Poiret One' => 'Poiret One',
            'Poppins' => 'Poppins',
            'Prompt' => 'Prompt',
            'Public Sans' => 'Public Sans',
            'PT Serif' => 'PT Serif',

            'Questrial' => 'Questrial',
            'Quicksand' => 'Quicksand',

            'Raleway' => 'Raleway',
            'Red Hat Display' => 'Red Hat Display',
            'Red Hat Text' => 'Red Hat Text',
            'Righteous' => 'Righteous',
            'Roboto' => 'Roboto',
            'Roboto Mono' => 'Roboto Mono',
            'Roboto Slab' => 'Roboto Slab',
            'Rubik' => 'Rubik',
            'Russo One' => 'Russo One',

            'Sen' => 'Sen',
            'Shadows Into Light' => 'Shadows Into Light',
            'Signika' => 'Signika',
            'Slabo 27px' => 'Slabo 27px',
            'Source Code Pro' => 'Source Code Pro',
            'Source Sans 3' => 'Source Sans 3',
            'Source Serif 4' => 'Source Serif 4',
            'Space Mono' => 'Space Mono',
            'Spectral' => 'Spectral',
            'Spline Sans' => 'Spline Sans',
            'Sora' => 'Sora',

            'Tajawal' => 'Tajawal',
            'Teko' => 'Teko',
            'Times New Roman' => 'Times New Roman',
            'Titillium Web' => 'Titillium Web',

            'Ubuntu' => 'Ubuntu',
            'Ubuntu Mono' => 'Ubuntu Mono',
            'Unbounded' => 'Unbounded',
            'Urbanist' => 'Urbanist',

            'Varela Round' => 'Varela Round',
            'Volkhov' => 'Volkhov',
            'Vollkorn' => 'Vollkorn',

            'Work Sans' => 'Work Sans',

            'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
            'Yantramanav' => 'Yantramanav',
            'Zilla Slab' => 'Zilla Slab',
        ];
        
        // Allow custom fonts
        if (isset($field['fonts']) && is_array($field['fonts'])) {
            $fonts = array_merge($fonts, $field['fonts']);
        }
        ?>
        <div class="nts-typography">
            <select 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                class="nts-select nts-typography__select"
            >
                <option value=""><?php _e('Select Font', 'nametheme'); ?></option>
                <?php foreach ($fonts as $key => $label): ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="nts-typography__preview" style="font-family: <?php echo esc_attr($value); ?>">
                The quick brown fox jumps over the lazy dog
            </div>
        </div>
        <?php
    }
    
    /**
     * Render page select field
     */
    public static function render_page_select($field, $value) {
        $pages = get_pages(['post_status' => 'publish']);
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            class="nts-select"
        >
            <option value=""><?php _e('Select Page', 'nametheme'); ?></option>
            <?php foreach ($pages as $page): ?>
                <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($value, $page->ID); ?>>
                    <?php echo esc_html($page->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    /**
     * Render post select field
     */
    public static function render_post_select($field, $value) {
        $post_type = isset($field['post_type']) ? $field['post_type'] : 'post';
        $posts = get_posts([
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            class="nts-select"
        >
            <option value=""><?php _e('Select Post', 'nametheme'); ?></option>
            <?php foreach ($posts as $post): ?>
                <option value="<?php echo esc_attr($post->ID); ?>" <?php selected($value, $post->ID); ?>>
                    <?php echo esc_html($post->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    /**
     * Render category select field
     */
    public static function render_category_select($field, $value) {
        $taxonomy = isset($field['taxonomy']) ? $field['taxonomy'] : 'category';
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            class="nts-select"
        >
            <option value=""><?php _e('Select Category', 'nametheme'); ?></option>
            <?php foreach ($terms as $term): ?>
                <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($value, $term->term_id); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    /**
     * Render menu select field
     */
    public static function render_menu_select($field, $value) {
        $menus = wp_get_nav_menus();
        ?>
        <select 
            id="<?php echo esc_attr($field['id']); ?>"
            name="<?php echo self::get_name($field); ?>"
            class="nts-select"
        >
            <option value=""><?php _e('Select Menu', 'nametheme'); ?></option>
            <?php foreach ($menus as $menu): ?>
                <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($value, $menu->term_id); ?>>
                    <?php echo esc_html($menu->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Render password field
     */
    public static function render_password($field, $value) {
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        ?>
        <div class="nts-password-field">
            <input type="password"
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                class="nts-input nts-password-input"
                value="<?php echo esc_attr($value); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>">
            <button type="button" class="nts-password-toggle" aria-label="<?php _e('Toggle password visibility', 'nametheme'); ?>">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php
    }

    /**
     * Render code editor field
     */
    public static function render_code($field, $value) {
        $language = isset($field['language']) ? $field['language'] : 'css';
        $rows = isset($field['rows']) ? $field['rows'] : 10;
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        ?>
        <div class="nts-code-field" data-language="<?php echo esc_attr($language); ?>">
            <div class="nts-code-field__header">
                <span class="nts-code-field__language"><?php echo esc_html(strtoupper($language)); ?></span>
                <button type="button" class="nts-code-field__copy" title="<?php _e('Copy to clipboard', 'nametheme'); ?>">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>
            <textarea
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                class="nts-code-input"
                rows="<?php echo esc_attr($rows); ?>"
                placeholder="<?php echo esc_attr($placeholder); ?>"
                spellcheck="false"><?php echo esc_textarea($value); ?></textarea>
        </div>
        <?php
    }
    
    /**
     * Render shortcode field
     */
    public static function render_shortcode($field, $value) {
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-code-square"></i></span>
            <input 
                type="text" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                placeholder="[shortcode]"
                class="nts-input nts-input--shortcode"
            >
        </div>
        <?php
    }
    
    /**
     * Render date field
     */
    public static function render_date($field, $value) {
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-calendar3"></i></span>
            <input 
                type="text" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="nts-input nts-datepicker"
                placeholder="YYYY-MM-DD"
            >
        </div>
        <?php
    }
    
    /**
     * Render time field
     */
    public static function render_time($field, $value) {
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-clock"></i></span>
            <input 
                type="time" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="nts-input nts-input--time"
            >
        </div>
        <?php
    }
    
    /**
     * Render datetime field
     */
    public static function render_datetime($field, $value) {
        ?>
        <div class="nts-input-group">
            <span class="nts-input-group__icon"><i class="bi bi-calendar-event"></i></span>
            <input 
                type="datetime-local" 
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="nts-input nts-input--datetime"
            >
        </div>
        <?php
    }
    
    /**
     * Render icon select field
     */
    public static function render_icon($field, $value) {
        // Bootstrap Icons (commonly used)
        $icons = [
            'bi-house' => 'House',
            'bi-person' => 'Person',
            'bi-envelope' => 'Envelope',
            'bi-telephone' => 'Telephone',
            'bi-geo-alt' => 'Location',
            'bi-clock' => 'Clock',
            'bi-calendar' => 'Calendar',
            'bi-star' => 'Star',
            'bi-heart' => 'Heart',
            'bi-chat' => 'Chat',
            'bi-gear' => 'Gear',
            'bi-search' => 'Search',
            'bi-cart' => 'Cart',
            'bi-bag' => 'Bag',
            'bi-bookmark' => 'Bookmark',
            'bi-lightning' => 'Lightning',
            'bi-shield' => 'Shield',
            'bi-award' => 'Award',
            'bi-trophy' => 'Trophy',
            'bi-rocket' => 'Rocket',
            'bi-graph-up' => 'Graph Up',
            'bi-pie-chart' => 'Pie Chart',
            'bi-bullseye' => 'Bullseye',
            'bi-puzzle' => 'Puzzle',
            'bi-layers' => 'Layers',
            'bi-cpu' => 'CPU',
            'bi-code-slash' => 'Code',
            'bi-database' => 'Database',
            'bi-cloud' => 'Cloud',
            'bi-globe' => 'Globe',
            'bi-facebook' => 'Facebook',
            'bi-twitter-x' => 'X (Twitter)',
            'bi-instagram' => 'Instagram',
            'bi-linkedin' => 'LinkedIn',
            'bi-youtube' => 'YouTube',
            'bi-tiktok' => 'TikTok',
            'bi-whatsapp' => 'WhatsApp',
            'bi-telegram' => 'Telegram',
        ];
        
        if (isset($field['icons']) && is_array($field['icons'])) {
            $icons = $field['icons'];
        }
        ?>
        <div class="nts-icon-select">
            <!-- Preview -->
            <div class="nts-icon-select__preview">
                <?php if ($value): ?>
                    <i class="bi <?php echo esc_attr($value); ?>"></i>
                <?php else: ?>
                    <i class="bi bi-plus"></i> <?php _e('Select Icon', 'nametheme'); ?>
                <?php endif; ?>
            </div>

            <!-- Dropdown -->
            <div class="nts-icon-select__dropdown">
                <!-- Custom input -->
                <div class="nts-icon-select__custom">
                    <input type="text"
                        class="nts-input nts-icon-select__custom-input"
                        placeholder="bi-facebook"
                        value="<?php echo esc_attr($value); ?>">
                    <small class="nts-help">
                        <?php _e('Enter Bootstrap Icon class (e.g. bi-facebook)', 'nametheme'); ?>
                    </small>
                </div>

                <!-- Icon list -->
                <div class="nts-icon-select__list">
                    <?php foreach ($icons as $icon => $label): ?>
                        <div class="nts-icon-select__item <?php echo $value === $icon ? 'nts-icon-select__item--active' : ''; ?>" 
                            data-value="<?php echo esc_attr($icon); ?>"
                            title="<?php echo esc_attr($label); ?>">
                            <i class="bi <?php echo esc_attr($icon); ?>"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Hidden value -->
            <input type="hidden"
                id="<?php echo esc_attr($field['id']); ?>"
                name="<?php echo self::get_name($field); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="nts-icon-select__input">
        </div>
        <?php
    }
}