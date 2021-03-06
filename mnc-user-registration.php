<?php
/*
Plugin Name: MNC New User Registration
Plugin URI: http://matiasmancini.com.ar/
Description: Override wp_new_user_notification().
Version: 1.0
Author: Matias Mancini
Author URI: http://matiasmancini.com.ar/
*/

if ( !function_exists('wp_new_user_notification') ) :
/**
 * Notify the blog admin of a new user, normally via email.
 *
 * @since 2.0
 *
 * @param int $user_id User ID
 * @param string $plaintext_pass Optional. The user's plaintext password
 */
function wp_new_user_notification($user_id, $plaintext_pass = '') {
  $user = new WP_User($user_id);

  $user_login = stripslashes($user->user_login);
  $user_email = stripslashes($user->user_email);

  // The blogname option is escaped with esc_html on the way into the database in sanitize_option
  // we want to reverse this for the plain text arena of emails.
  $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

  $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
  $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
  $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

  @wp_mail(get_option('mnc_site_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

  if ( empty($plaintext_pass) )
    return;

  $message  = sprintf(__('Username: %s'), $user_login) . "\r\n";
  $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
  $message .= wp_login_url() . "\r\n";

  wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);

}
endif;

?>
