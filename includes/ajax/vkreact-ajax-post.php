<?php
/**
 * Vikinger Reactions POST AJAX
 * 
 * @since 1.0.0
 */

/**
 * Create a user reaction for a post
 */
function vkreact_create_post_user_reaction_ajax() {
  $result = vkreact_create_post_user_reaction($_POST['args']);

  header('Content-Type: application/json');
  echo json_encode($result);

  wp_die();
}

add_action('wp_ajax_vkreact_create_post_user_reaction_ajax', 'vkreact_create_post_user_reaction_ajax');
add_action('wp_ajax_nopriv_vkreact_create_post_user_reaction_ajax', 'vkreact_create_post_user_reaction_ajax');

/**
 * Delete a user reaction for a post
 */
function vkreact_delete_post_user_reaction_ajax() {
  $result = vkreact_delete_post_user_reaction($_POST['args']);

  header('Content-Type: application/json');
  echo json_encode($result);

  wp_die();
}

add_action('wp_ajax_vkreact_delete_post_user_reaction_ajax', 'vkreact_delete_post_user_reaction_ajax');
add_action('wp_ajax_nopriv_vkreact_delete_post_user_reaction_ajax', 'vkreact_delete_post_user_reaction_ajax');

?>