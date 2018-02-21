<?php
/**
 * Plugin Name: WP Focal Point
 * Version: 1.0.1
 * Plugin URI: https://github.com/BenjaminMedia/wp-focal-point
 * Description: This plugin allows setting a focal point on an image
 * Author: Bonnier
 * License: GPL v3
 */

if (!defined('ABSPATH')) {
    exit;
}

//require_once (__DIR__ . '/vendor/autoload.php');

spl_autoload_register(function ($className) {
    $namespace = 'Bonnier\\WP\\FocalPoint\\';
    if (str_contains($className, $namespace)) {
        $className = str_replace([$namespace, '\\'], [__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $className);
        
        $file = $className . '.php';
        
        if(file_exists($file)) {
            require_once($className . '.php');
        } else {
            throw new Exception(sprintf('\'%s\' does not exist!', $file));
        }
    }
});

function register_focal_point_plugin()
{
    return \Bonnier\WP\FocalPoint\FocalPoint::instance();
}

add_action('plugins_loaded', 'register_focal_point_plugin');
