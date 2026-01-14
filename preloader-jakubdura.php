<?php
/**
 * Plugin Name: Custom Preloader Pro
 * Description: Advanced custom preloader with logo animation.
 * Version: 2.0
 * Author: Jakub Dura
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// Definiowanie stałych pluginu
define('PRELOADER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PRELOADER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PRELOADER_PLUGIN_SLUG', 'custom-preloader-pro');

// ===== INICJALIZACJA PLUGINU =====
add_action('plugins_loaded', 'preloader_initialize');
function preloader_initialize() {
    // Rejestrowanie opcji domyślnych
    add_option('preloader_enabled', 1);
    add_option('preloader_fade_duration', '0.6');
}

// ===== MENU ADMIN =====
add_action('admin_menu', 'preloader_add_admin_menu');
function preloader_add_admin_menu() {
    add_options_page(
        'Preloader Settings',
        'Preloader',
        'manage_options',
        PRELOADER_PLUGIN_SLUG,
        'preloader_admin_page'
    );
}

// ===== STRONA USTAWIEŃ ADMIN =====
function preloader_admin_page() {
    // Obsługa zapisywania ustawień
    if (isset($_POST['preloader_save'])) {
        check_admin_referer('preloader_nonce');
        
        update_option('preloader_enabled', isset($_POST['preloader_enabled']) ? 1 : 0);
        update_option('preloader_fade_duration', floatval($_POST['preloader_fade_duration']));
        
        echo '<div class="updated"><p><strong>Ustawienia zapisane!</strong></p></div>';
    }
    
    // Pobieranie bieżących wartości
    $enabled = get_option('preloader_enabled', 1);
    $fade = get_option('preloader_fade_duration', '0.6');
    ?>
    <div class="wrap">
        <h1>⚙️ Ustawienia Preloadera</h1>
        
        <form method="POST" style="background: white; padding: 20px; border-radius: 5px; max-width: 400px;">
            <?php wp_nonce_field('preloader_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="preloader_enabled">Włącz Preloader</label></th>
                    <td>
                        <input type="checkbox" id="preloader_enabled" name="preloader_enabled" value="1" <?php checked($enabled, 1); ?>>
                    </td>
                </tr>
                <tr>
                    <th><label for="preloader_fade_duration">Czas trwania (sekundy)</label></th>
                    <td>
                        <input type="number" id="preloader_fade_duration" name="preloader_fade_duration" value="<?php echo esc_attr($fade); ?>" min="0.1" max="10" step="0.1" style="width: 100px;">
                    </td>
                </tr>
            </table>
            
            <button type="submit" name="preloader_save" class="button button-primary">Zapisz</button>
        </form>
    </div>
    <?php
}

// ===== FRONTEND - HTML =====
add_action('wp_body_open', 'preloader_render_html');
function preloader_render_html() {
    if (!get_option('preloader_enabled', 1)) {
        return;
    }
    $logo_url = PRELOADER_PLUGIN_URL . 'assets/logo.png';
    ?>
    <div id="site-preloader">
        <img src="<?php echo esc_url($logo_url); ?>" alt="Loading" class="preloader-logo">
    </div>
    <?php
}

// ===== FRONTEND - CSS =====
add_action('wp_head', 'preloader_render_css');
function preloader_render_css() {
    if (!get_option('preloader_enabled', 1)) {
        return;
    }
    $fade = get_option('preloader_fade_duration', '10');
    ?>
    <style>
        #site-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0a0a0a;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            transition: opacity 0.5s ease;
        }
        
        .preloader-logo {
            width: 150px;
            height: 150px;
            object-fit: contain;
            animation: preloader-pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes preloader-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        .hide-loader {
            opacity: 0;
            pointer-events: none;
        }
    </style>
    <?php
}

// ===== FRONTEND - JS =====
add_action('wp_footer', 'preloader_render_js');
function preloader_render_js() {
    if (!get_option('preloader_enabled', 1)) {
        return;
    }
    $fade_ms = intval(get_option('preloader_fade_duration', '10') * 1000);
    ?>
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('site-preloader');
            if (loader) {
                // Czeka na ustawiony czas, potem zaczyna zanikać
                setTimeout(() => {
                    loader.classList.add('hide-loader');
                    // Po 0.5s fade usuwa element
                    setTimeout(() => {
                        if (loader && loader.parentNode) {
                            loader.remove();
                        }
                    }, 500);
                }, <?php echo $fade_ms; ?>);
            }
        });
    </script>
    <?php
}