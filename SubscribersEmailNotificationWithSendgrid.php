<?php
/**
  * Plugin Name: Subscribers Email Notification With Sendgrid
  * Plugin URI: https://github.com/lsyk4/Subscribers-Email-Notification-With-Sendgrid
  * Description: Send a email notification when publish a new post using sendgrid
  * Version: 1.0.0
  * Author: Lsyk4
  * Author URI: http://lsyk4.xyz
  * License: GPL2
*/
//Install
  function create_post_type() {
    register_post_type( 'sen_subscriber',
      array(
        'capability_type' => 'post',
        'capabilities' => array(
        'create_posts' => 'do_not_allow',
        'delete_posts' => 'do_not_allow'
        ),
        'labels' => array(
          'name' => __( 'Subscribers' ),
          'singular_name' => __( 'sen subscriber' )
        ),
        'menu_icon'   => 'dashicons-email',
        'public' => true,
        'has_archive' => true,
      )
    );
  }
  add_action( 'init', 'create_post_type' );
  require_once("metaboxes.php");
//Install
require_once("emailtemplate.php");
require_once("functions.php");
require_once("OptionsPage.php");

function SubscribersEmailNotificationAssets($hook) {
    wp_enqueue_script("SubscribersEmailNotificationScript", "/wp-content/plugins/SubscribersEmailNotificationWithSendgrid/assets/js/script.js");
    wp_localize_script( 'SubscribersEmailNotificationScript', 'new_subscriber_params', array( 'new_subscriber_ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'SubscribersEmailNotificationScript' );
    wp_register_style("SubscribersEmailNotificationStyle", "/wp-content/plugins/SubscribersEmailNotificationWithSendgrid/assets/css/style.css");
    wp_enqueue_style ("SubscribersEmailNotificationStyle");
}



if(!is_admin()){
  add_action('wp_enqueue_scripts', 'SubscribersEmailNotificationAssets');

}
if(is_admin()){

}
?>
