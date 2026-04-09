<?php
add_action('admin_menu', 'ManagerPlugins_Add_My_Admin_Link');

function ManagerPlugins_Add_My_Admin_Link() {
    add_menu_page(
        'Pagina principal',          // Título de la página
        'Plugins Manager',           // Texto que aparece en el menú
        'manage_options',            // Capacidad necesaria para ver el menú
        'manager_plugins',           // Slug único para la página
        'ManagerPlugins_Display_Page', // Función que mostrará el contenido
        'dashicons-admin-plugins',   // Icono que aparecerá en el menú
        6                            // Posición en el menú
    );
}

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script(
        'plugin-js',
        plugin_dir_url(__FILE__) . 'js/plugin.js',
        ['jquery', 'updates'], // 'updates' es clave
        '1.0',
        true
    );

    // Pasar nonce al JS
   wp_localize_script('plugin-js', 'plugin', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce'    => wp_create_nonce('activar_plugin_nonce'),
    ]);
});

add_action('wp_ajax_activar_plugin', function() {
    check_ajax_referer('activar_plugin_nonce', 'nonce');

    if (!current_user_can('activate_plugins')) {
        wp_send_json_error('Sin permisos');
    }

    $plugin = sanitize_text_field($_POST['plugin']); // 'really-simple-ssl/really-simple-ssl.php'
    $result = activate_plugin($plugin);

    if (is_wp_error($result)){
        wp_send_json_error($result->get_error_message());
    }

    wp_send_json_success('Plugin activado');
});