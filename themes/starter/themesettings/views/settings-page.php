<?php
/**
 * Settings Page View
 * 
 * Main admin settings page template
 * 
 * @package TS
 * @subpackage ThemeSettings
 */

if (!defined('ABSPATH')) {
    exit;
}

$config = TS_Settings_Config::get_fields();
$options = get_option('ts_options', []);
$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : array_key_first($config);
?>
<!-- Edit data-theme="dark" for dark mode skin -->
<div data-theme="<?php echo defined('THEME_SETTINGS_SKIN') ? esc_attr(THEME_SETTINGS_SKIN) : 'light'; ?>" class="nts-wrapper">
    <!-- Header -->
    <header class="nts-header">
        <div class="nts-header__content">
            <div class="nts-header__brand">
                <img src="<?php echo THEME_SETTINGS_URL; ?>assets/images/logo.png" class="logo-admin" style="width: 36px;" alt="logo">
                <div>
                    <h1><?php echo THEME_SETTINGS_BRAND; ?></h1>
                </div>
            </div>
            <div class="nts-header__actions">
                <button type="button" class="nts-btn nts-btn--ghost" id="nts-import-btn">
                    <i class="bi bi-upload"></i>
                    <?php _e('Import', 'nametheme'); ?>
                </button>
                <button type="button" class="nts-btn nts-btn--ghost" id="nts-export-btn">
                    <i class="bi bi-download"></i>
                    <?php _e('Export', 'nametheme'); ?>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="nts-main">
        <!-- Sidebar Navigation -->
        <nav class="nts-sidebar">
            <ul class="nts-nav">
                <?php foreach ($config as $tab_id => $tab): ?>
                    <li class="nts-nav__item">
                        <a href="?page=<?php echo THEME_SETTINGS_BRAND_URL; ?>-settings&tab=<?php echo esc_attr($tab_id); ?>" 
                           class="nts-nav__link <?php echo $current_tab === $tab_id ? 'nts-nav__link--active' : ''; ?>">
                            <?php if (isset($tab['icon'])): ?>
                                <i class="bi <?php echo esc_attr($tab['icon']); ?>"></i>
                            <?php endif; ?>
                            <span><?php echo esc_html($tab['title']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Content Area -->
        <main class="nts-content">
            <form method="post" action="options.php" class="nts-form" id="nts-settings-form">
                <?php 
                settings_fields('ts_settings_group');
                
                // Current tab content
                if (isset($config[$current_tab])):
                    $tab = $config[$current_tab];
                ?>
                    <div class="nts-tab" data-tab="<?php echo esc_attr($current_tab); ?>">
                        <!-- Tab Header -->
                        <div class="nts-tab__header">
                            <h2 class="nts-tab__title"><?php echo esc_html($tab['title']); ?></h2>
                            <?php if (isset($tab['desc'])): ?>
                                <p class="nts-tab__desc"><?php echo esc_html($tab['desc']); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Sections -->
                        <?php if (isset($tab['sections'])): ?>
                            <?php foreach ($tab['sections'] as $section_id => $section): ?>
                                <div class="nts-section" id="section-<?php echo esc_attr($section_id); ?>">
                                    <div class="nts-section__header">
                                        <h3 class="nts-section__title"><?php echo esc_html($section['title']); ?></h3>
                                        <?php if (isset($section['desc'])): ?>
                                            <p class="nts-section__desc"><?php echo esc_html($section['desc']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="nts-section__content">
                                        <?php if (isset($section['fields'])): ?>
                                            <?php foreach ($section['fields'] as $field_id => $field): 
                                                $field['id'] = $field_id;
                                                $value = isset($options[$field_id]) ? $options[$field_id] : (isset($field['default']) ? $field['default'] : '');
                                            ?>
                                                <?php TS_Field_Renderer::render($field, $value); ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <span class="nts-save-status"></span>

                <!-- Save Button -->
                <div class="nts-form__footer">
                    <button type="submit" class="nts-btn nts-btn--primary nts-btn--lg">
                        <i class="bi bi-check-lg"></i>
                        <?php _e('Save Changes', 'nametheme'); ?>
                    </button>
                    <button type="button" class="nts-btn nts-btn--danger-ghost nts-btn--lg" id="nts-reset-btn">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <?php _e('Reset', 'nametheme'); ?>
                    </button>
                </div>
            </form>

            <!-- Purchase Pro Version for remove this credit -->
            <div class="copyright-text">
                Options by <a href="https://themesettings.com" target="_blank">ThemeSettings.com</a> | <?php echo THEME_SETTINGS_AUTHOR; ?> <span class="nts-header__version">Version <?php echo THEME_SETTINGS_VERSION; ?></span>
            </div>
        </main>
    </div>
    <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') : ?>
    <script>
        window.NTS_SETTINGS_SAVED = true;
    </script>
    <?php endif; ?>

    <!-- Import Modal -->
    <div class="nts-modal" id="nts-import-modal">
        <div class="nts-modal__overlay"></div>
        <div class="nts-modal__content">
            <div class="nts-modal__header">
                <h3><?php _e('Import Settings', 'nametheme'); ?></h3>
                <button type="button" class="nts-modal__close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="nts-modal__body">
                <!-- Upload File JSON -->
                <div class="nts-file-upload-wrapper" style="margin-bottom: 15px;">
                    <input type="file" id="nts-json-file-input" accept=".json" style="display: none;">
                    <button type="button" class="nts-btn nts-btn--secondary" id="nts-upload-json-btn">
                        <i class="bi bi-upload"></i> <?php _e('Upload JSON File', 'nametheme'); ?>
                    </button>
                    <span id="nts-file-name" style="margin-left: 10px; color: #666;"></span>
                </div>
                <p><?php _e('Or paste your exported settings JSON below:', 'nametheme'); ?></p>
                <textarea id="nts-import-data" rows="10" placeholder='{"option_key": "value"}'></textarea>
            </div>
            <div class="nts-modal__footer">
                <button type="button" class="nts-btn nts-btn--ghost nts-modal__cancel">
                    <?php _e('Cancel', 'nametheme'); ?>
                </button>
                <button type="button" class="nts-btn nts-btn--primary" id="nts-import-confirm">
                    <?php _e('Import', 'nametheme'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="nts-notifications" id="nts-notifications"></div>
</div>
