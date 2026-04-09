<?php
/**
 * Plugin Name:       Manager Plugins
 * Plugin URI:        https://example.com
 * Description:       Instala, activa, desactiva y desinstala plugins recomendados con un solo clic.
 * Version:           0.0.1
 * Author:            Alvaro Garcia Gonzalez
 * License:           MIT license
 * Text Domain:       plugin-manager
 */
require_once 'includes/funcionesPlugin.php';
require_once 'includes/paginaPrincipal.php';

// ─── LISTA DE PLUGINS RECOMENDADOS ───────────────────────────────────────────
function getPlugins()
{
    return [
        [
            'slug' => 'elementor',
            'file' => 'elementor/elementor.php',
            'name' => 'Elementor',
            'description' => 'Un plugin para mejorar la edicion de temas en wordpress.',
            'category' => 'Edicion'
        ],
        [
            'slug' => 'classic-editor',
            'file' => 'classic-editor/classic-editor.php',
            'name' => 'Classic Editor',
            'description' => 'Restaura el editor clásico de WordPress.',
            'category' => 'Contenido'
        ],
        [
            'slug' => 'really-simple-ssl',
            'file' => 'really-simple-ssl/really-simple-ssl.php',                    
            'name' => 'Really Simple SSL',      
            'description' => 'Migra tu sitio a HTTPS de forma sencilla.',             
            'category' => 'Seguridad'],
                                  
    ];
}
?>