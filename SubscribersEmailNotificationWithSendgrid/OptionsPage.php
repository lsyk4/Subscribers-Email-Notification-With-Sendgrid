<?php
  // create custom plugin settings menu
  add_action('admin_menu', 'SubscribersEmailNotification_plugin_create_menu');

  function SubscribersEmailNotification_plugin_create_menu() {

    //create new top-level menu
    add_submenu_page('edit.php?post_type=sen_subscriber', __('Subscription Settings','menu-subscriptors'), __('Subscription Settings','menu-subscriptors'), 'manage_options', 'subscriptionsettings', 'SubscribersEmailNotification_plugin_settings_page');

    //call register settings function
    add_action( 'admin_init', 'register_SubscribersEmailNotification_plugin_settings' );
  }

  function register_SubscribersEmailNotification_plugin_settings() {
    //register our settings
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_SGAPIKEY' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_EmailTemplate' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_FromName' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_From' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_Subject' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_LastPostSent' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_LastPostSentDate' );
    register_setting( 'SubscribersEmailNotification-plugin-settings-group', 'SubscribersEmailNotification_EmailImage' );
  }

  function SubscribersEmailNotification_plugin_settings_page() {
    ?>
      <div class="wrap">
        <h1>Subscribers Email Notification</h1>
        <h4>ShortCode: [SubscribersEmailNotificationForm]</h4>
        <form method="post" action="options.php">
          <?php settings_fields( 'SubscribersEmailNotification-plugin-settings-group' ); ?>
          <?php do_settings_sections( 'SubscribersEmailNotification-plugin-settings-group' ); ?>
          <table class="form-table">
          <tr valign="top">
          <th scope="row">SendGrid API Key</th>
          <td>
            <input style="width:100%;" type="password" name="SubscribersEmailNotification_SGAPIKEY" value="<?php echo esc_attr( get_option('SubscribersEmailNotification_SGAPIKEY') ); ?>" />
            <br>
            <label style="color:#666; font-size:10px">Example: SG.xxxxxxxxxxxxxxxxxxxxxx.x_xxxxxxxxxxxxxxxxxxxxxxxxx_xxxxxxxxxxx_xx</label>
          </td>
          </tr>
          <tr valign="top">
          <th scope="row">From Name:</th>
          <td><input style="width:100%;" type="text" placeholder="My name or my business name" name="SubscribersEmailNotification_FromName" value="<?php echo esc_attr( get_option('SubscribersEmailNotification_FromName') ); ?>" /></td>
          </tr>
          <tr valign="top">
          <th scope="row">From:</th>
          <td><input style="width:100%;" type="text" placeholder="From Email" name="SubscribersEmailNotification_From" value="<?php echo esc_attr( get_option('SubscribersEmailNotification_From') ); ?>" /></td>
          </tr>
          <tr valign="top">
          <th scope="row">Subject:</th>
          <td><input style="width:100%;" type="text" placeholder="My great subject" name="SubscribersEmailNotification_Subject" value="<?php echo esc_attr( get_option('SubscribersEmailNotification_Subject') ); ?>" /></td>
          </tr>
          <tr valign="top">
          <th scope="row">Email Logo URL:</th>
          <td><input style="width:100%;" type="text" placeholder="Accessible url to your logo image" name="SubscribersEmailNotification_EmailImage" value="<?php echo esc_attr( get_option('SubscribersEmailNotification_EmailImage') ); ?>" /></td>
          </tr>
          <tr valign="top">
          <th scope="row">Email Body</th>
          <td><?php wp_editor(get_option('SubscribersEmailNotification_EmailTemplate'), 'SubscribersEmailNotification_EmailTemplate', ["tinymce", "wpautop" => false]); ?></td>
          </tr>
          <tr valign="top">
          <th>Last Post Sent:</th>
          <td>
            <ul>
              <li>ID: <?php echo esc_attr(get_option('SubscribersEmailNotification_LastPostSent')); ?></li>
              <li>Title: <?php echo get_the_title(get_option('SubscribersEmailNotification_LastPostSent')); ?></li>
              <li>Date: <?php echo esc_attr(get_option('SubscribersEmailNotification_LastPostSentDate')); ?></li>
            </ul>
          </td>
          </tr>
          </table>

          <?php submit_button(); ?>

        </form>
      </div>
    <?php
  }
?>
