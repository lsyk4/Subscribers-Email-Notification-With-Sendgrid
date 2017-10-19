<?php
  add_action( 'add_meta_boxes', 'add_sen_subscriber_metaboxes' );
  function add_sen_subscriber_metaboxes() {
  	add_meta_box('sen_subscriber_name', 'Name', 'sen_subscriber_name', 'sen_subscriber');
    add_meta_box('sen_subscriber_lastname', 'Last Name', 'sen_subscriber_lastname', 'sen_subscriber');
  }

  //Metaboxes
  function sen_subscriber_name() {
  	global $post;
  	$sen_subscriber_name = get_post_meta($post->ID, 'sen_subscriber_name', true);
  	echo '<input type="text" name="sen_subscriber_name" value="' . esc_attr($sen_subscriber_name)  . '" class="widefat" />';
  }
  function sen_subscriber_lastname() {
  	global $post;
  	$sen_subscriber_lastname = get_post_meta($post->ID, 'sen_subscriber_lastname', true);
  	echo '<input type="text" name="sen_subscriber_lastname" value="' . esc_attr($sen_subscriber_lastname)  . '" class="widefat" />';
  }

  // Save the Metabox Data
  function sen_subscriber_save_sen_subscriber_meta($post_id, $post) {
    $sen_subscriber_meta = array();
  	if ( !current_user_can( 'edit_post', $post->ID ))
  		return $post->ID;
  	if(isset($_POST["sen_subscriber_name"]) && $_POST["sen_subscriber_lastname"]){
      $sen_subscriber_meta['sen_subscriber_name'] = sanitize_text_field($_POST['sen_subscriber_name']);
      $sen_subscriber_meta['sen_subscriber_lastname'] = sanitize_text_field($_POST['sen_subscriber_lastname']);
    	foreach ($sen_subscriber_meta as $key => $value) {
    		if( $post->post_type === 'revision' ) return;
    		$value = implode(',', (array)$value);
    		if(get_post_meta($post->ID, $key, FALSE)) {
    			update_post_meta($post->ID, $key, $value);
    		} else {
    			add_post_meta($post->ID, $key, $value);
    		}
    		if(!$value) delete_post_meta($post->ID, $key);
    	}
    }
  }
  add_action('save_post', 'sen_subscriber_save_sen_subscriber_meta', 1, 2);
?>
