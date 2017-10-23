<?php
  function SubscribersEmailNotification_FormShortCode(){
    ?>
      <div class="sen-form-container">
        <form class="sen-subscribe-form" method="POST">
          <label>Subscribe to our posts!</label><br>
          <div class="inputscontainer">
            <?php wp_nonce_field("sen_new_subscriber_nonce", "sen_subscribe_form"); ?>
            <input type="email" name="email" class="email" placeholder="Email Address">
            <button type="submit" name="subscribe">Subscribe</button>
            <br>
          </div>
        </form>
        <label class="success-message"></label>
      </div>
    <?php
  }
  add_shortcode('SubscribersEmailNotificationForm', 'SubscribersEmailNotification_FormShortCode');

//Create new subscriber

  // creating Ajax call for WordPress
  add_action( 'wp_ajax_nopriv_sen_new_subscriber', 'sen_new_subscriber' );
  add_action( 'wp_ajax_sen_new_subscriber', 'sen_new_subscriber' );

  /* WP Insert Post Function
  ----- */

  function sen_new_subscriber()
  {

      $email = sanitize_email($_POST['email']);
      $randid = random_int(0, 99999999999999);
      // Create post object
      $new_subscriber = array(
          'post_type'     => 'sen_subscriber',
          'post_title'    => $email,
          'post_status'   => "publish",
          'post_name'     => $randid
      );

      // Insert the post into the database
      if(!wp_verify_nonce( $_POST['sen_subscribe_form'], 'sen_new_subscriber_nonce')){
        wp_die( 'Security check' );
      }else{
        wp_insert_post( $new_subscriber );
        exit();
      }
  };
///Create new subscriber


//
//
function sen_send_notification(){
  /*if ( false === get_transient( 'sen_notify_subscribers_lock' ) ) {
      // set for 15 minutes
      set_transient( 'sen_notify_subscribers_lock', 1, ( 30 ) );
  } else {
      // stop running if locked
      return;
  }*/
  $recent_posts = wp_get_recent_posts(['numberposts' => 1, 'post_type' => 'post', 'post_status' => 'publish', 'suppress_filters' => false] );
  $lastpostid = (string)$recent_posts[0]['ID'];
  if (get_option('SubscribersEmailNotification_LastPostSent', '0') === '' || get_option('SubscribersEmailNotification_LastPostSent', '0') === 0 ) {
    update_option("SubscribersEmailNotification_LastPostSent", $lastpostid);
  }elseif(get_option('SubscribersEmailNotification_LastPostSent') !== $lastpostid){
    $emailToArray = array();
    require_once 'sendgrid-php.php';
    require_once 'vendor/autoload.php';
    $wpb_all_query = new WP_Query(array('post_type'=>'sen_subscriber', 'post_status'=>'publish', 'posts_per_page'=>-1));
    if($wpb_all_query->have_posts()){
      while ( $wpb_all_query->have_posts() ){
        $wpb_all_query->the_post();
        $emailToArray[] = get_the_title();
      }
      foreach ($emailToArray as $emailto) {
        //Send email
          $from = new SendGrid\Email(get_option('SubscribersEmailNotification_FromName'), get_option('SubscribersEmailNotification_From'));
          $subject = "'".get_option('SubscribersEmailNotification_Subject')."'";
          $to = new SendGrid\Email("Subscriber", $emailto);
          $content = new SendGrid\Content("text/html", sen_email_to_send());
          $mail = new SendGrid\Mail($from, $subject, $to, $content);
          $apiKey = get_option('SubscribersEmailNotification_SGAPIKEY');
          $sg = new \SendGrid($apiKey);
          $response = $sg->client->mail()->send()->post($mail);
        ///Send email
      }
    }
    update_option("SubscribersEmailNotification_LastPostSent", $lastpostid);
    update_option("SubscribersEmailNotification_LastPostSentDate", date("m/d/Y g:i:s a"));
  }
}
///
///


//Schedule
  function my_add_fifteen_mins( $schedules ) {
      $schedules['fifteen_mins'] = array(
          'interval' => 900, //15 minutes
          'display' => __('Once each fifteen mins')
      );
      return $schedules;
  }
  add_filter( 'cron_schedules', 'my_add_fifteen_mins' );
///Schedule


//Send email notification

add_action('sen_notify_subscribers', 'sen_send_notification');

if (! wp_next_scheduled ( 'sen_notify_subscribers' )) {
     wp_schedule_event(time(), 'fifteen_mins', 'sen_notify_subscribers');
}
///

?>
