<?php

namespace Bonnier\WP\FocalPoint;

use WP_Post;

class FocalPoint
{
    const FIELD = 'focal_point';
    private static $instance;
    
    private $pluginUrl;
    
    private function __construct()
    {
        $this->pluginUrl = plugin_dir_url(__DIR__);
        add_action('admin_enqueue_scripts', [$this, 'registerScripts']);
        add_action('wp_admin_set_focal_point', [$this, 'registerFocalPoint']);
        add_filter('attachment_fields_to_edit', [$this, 'addFields'], 11, 2);
        add_filter('attachment_fields_to_save', [$this, 'saveFields'], 11, 2);
    }
    
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    public function addFields($form_fields, WP_Post $post = null)
    {
        if(!$post) {
            return $form_fields;
        }
        if (preg_match( "/image/", $post->post_mime_type)) {
            $meta = get_post_meta( $post->ID, '_' . static::FIELD, true );
            
            $form_fields[static::FIELD] = [
                'label' => 'Focal point',
                'input' => 'text',
                'value' => $meta
            ];
        }
        
        return $form_fields;
    }
    
    public function saveFields($post, $attachment)
    {
        if(isset($attachment[static::FIELD])) {
            if(!preg_match('/[0,1]\.[0-9]{1,2}\,[0,1]\.[0-9]{1,2}/', $attachment[static::FIELD])) {
                $post['errors'][static::FIELD]['errors'][] = 'Invalid focal point format';
            } else {
                update_post_meta($post['ID'], '_' . static::FIELD, $attachment[static::FIELD]);
            }
        }
        
        return $post;
    }
    
    public function registerScripts($hook)
    {
        if ('post.php' != $hook) {
            return;
        }
        
        wp_register_script('focal_point_script', $this->pluginUrl . 'assets/js/focal_point.js');
        wp_localize_script('focal_point_script', 'assets', [
            'crosshair' => $this->pluginUrl . 'assets/img/crosshair.png',
        ]);
        wp_enqueue_script('focal_point_script');
    }
    
    public function registerFocalPoint()
    {
    
    }
}