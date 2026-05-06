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
            'category' => 'Seguridad'
        ],
        [
            'slug' => 'classic-widgets',
            'file' => 'classic-widgets/classic-widgets.php',                    
            'name' => 'Classic Widgets',      
            'description' => 'Widgets clasicos de Wordpress',             
            'category' => 'Contenido'
        ],
        [
            'slug' => 'complianz-gdpr',
            'file' => 'complianz-gdpr/complianz-gdpr.php',                    
            'name' => 'Complianz | GDPR/CCPA Cookie Consent',      
            'description' => 'Complianz Privacy Suite for GDPR, CaCPA, DSVGO, AVG with a conditional cookie warning and customized cookie policy.',             
            'category' => 'Cookies'
        ],
        [
            'slug' => 'complianz-terms-conditions',
            'file' => 'complianz-terms-conditions/complianz-terms-conditions.php',                    
            'name' => 'Complianz - Terms and Conditions',      
            'description' => 'Plugin from Complianz to generate Terms & Conditions for your website.',             
            'category' => 'Terminos y Condiciones'
        ],
        [
            'slug' => 'header-footer-elementor',
            'file' => 'header-footer-elementor/header-footer-elementor.php',                    
            'name' => 'Ultimate Addons for Elementor (UAE)',      
            'description' => 'Ultimate Addons is a powerful plugin allows you to create custom headers and footers with Elementor and display them in selected locations. You can also create custom Elementor blocks and place them anywhere on your website using a shortcode.',             
            'category' => 'Edicion'
        ],
        [
            'slug' => 'machete',
            'file' => 'machete/machete.php',                    
            'name' => 'Machete',      
            'description' => 'Machete is a lean and simple suite of tools that makes WordPress development easier: cookie bar, tracking codes, custom code editor, header cleanup, post and page cloner',             
            'category' => 'General'
        ],
        [
            'slug' => 'polylang',
            'file' => 'polylang/polylang.php',                    
            'name' => 'Polylang',      
            'description' => 'Adds multilingual capability to WordPress.',             
            'category' => 'Idiomas'
        ],
        [
            'slug' => 'wpforms-lite',
            'file' => 'wpforms-lite/wpforms-lite.php',                    
            'name' => 'WPForms Lite',      
            'description' => 'Beginner friendly WordPress contact form plugin. Use our Drag & Drop form builder to create your WordPress forms.',             
            'category' => 'Contenido'
        ],
        [
            'slug' => 'wordpress-seo',
            'file' => 'wordpress-seo/wp-seo.php',                    
            'name' => 'Yoast SEO',      
            'description' => 'The first true all-in-one SEO solution for WordPress, including on-page content analysis, XML sitemaps and much more.',             
            'category' => 'Seo'
        ],                          
    ];
}
?>