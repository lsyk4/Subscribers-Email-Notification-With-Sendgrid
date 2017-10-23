<?php
  function sen_email_to_send(){
    $sen__recent_posts = wp_get_recent_posts(['numberposts' => 1, 'post_type' => 'post', 'suppress_filters' => false]);

    $sen__emailtemplate = wpautop(get_option('SubscribersEmailNotification_EmailTemplate'));
    $sen__emaillogo = get_option('SubscribersEmailNotification_EmailImage');
    $sen__post_permalink = $sen__recent_posts[0]['guid'];
    $sen__post_title = $sen__recent_posts[0]['post_title'];
    $sen__post_excerpt = substr($sen__recent_posts[0]['post_content'], 0, 200)." ... ";
    $sen__email_message = '
      <!doctype html>
      <html>
        <head>
          <meta name="viewport" content="width=device-width">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Simple Transactional Email</title>
        </head>
        <body>
          <center><img src="'.$sen__emaillogo.'" width="200px"></center>
          <p>
            '.$sen__emailtemplate.'
          </p>
          <p><h3><b>'.strtoupper($sen__post_title).'</b></h3></p>
          <p>'.$sen__post_excerpt.'</p>
          <a href="'.$sen__post_permalink.'" target="_blank">
            View Post
          </a>
        </body>
      </html>
      ';
      return $sen__email_message;
  }
?>
