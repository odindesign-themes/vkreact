<?php

class VKReact_PostComment_User_Reaction {
  private $wpdb;

  private $table;
  private $wp_postcomment_table;
  private $wp_users_table;
  private $vkreact_reaction_table;

  public function __construct() {
    $table_name = 'vkreact_postcomment_user_reaction';
    $wp_postcomment_table_name = 'comments';
    $wp_users_table_name = 'users';
    $vkreact_reaction_table_name = 'vkreact_reaction';

    global $wpdb;

    $this->wpdb = $wpdb;
    
    $this->table = $wpdb->prefix . $table_name;
    $this->wp_postcomment_table = $wpdb->prefix . $wp_postcomment_table_name;
    $this->wp_users_table = $wpdb->prefix . $wp_users_table_name;
    $this->vkreact_reaction_table = $wpdb->prefix . $vkreact_reaction_table_name;
  }

  public function createTable() {
    $sql = "CREATE TABLE IF NOT EXISTS $this->table (
      postcomment_id bigint(20) unsigned NOT NULL,
      user_id bigint(20) unsigned NOT NULL,
      reaction_id int NOT NULL,
      PRIMARY KEY (postcomment_id, user_id)
    ) {$this->wpdb->get_charset_collate()};";
    
    return $this->wpdb->query($sql);
  }

  public function dropTable() {
    $sql = "DROP TABLE IF EXISTS $this->table";
  
    return $this->wpdb->query($sql);
  }

  public function create($postcomment_user_reaction) {
    $format = ['%d', '%d', '%d'];

    // number of affected rows on successful replace (1 if only insert, 1 more for each delete if replace), false on error
    $result = $this->wpdb->replace($this->table, $postcomment_user_reaction, $format);

    if ($result) {
      return $this->wpdb->insert_id;
    }

    return false;
  }

  public function delete($postcomment_user) {
    $format = ['%d', '%d', '%d'];

    // number of affected rows on succesful delete, false on error
    $result = $this->wpdb->delete($this->table, $postcomment_user, $format);

    return $result;
  }

  public function getReactions($postcomment_id) {
    $sql = "SELECT id, name, image_url, COUNT(id) AS reaction_count FROM $this->table
              INNER JOIN $this->vkreact_reaction_table ON $this->table.reaction_id=$this->vkreact_reaction_table.id
              WHERE postcomment_id=%d
              GROUP BY $this->vkreact_reaction_table.id
              ORDER BY reaction_count ASC";

    return $this->wpdb->get_results(
      $this->wpdb->prepare($sql, [$postcomment_id])
    );
  }

  public function getUsersByPostCommentReaction($postcomment_id, $reaction_id) {
    $sql = "SELECT user_id FROM $this->table
              INNER JOIN $this->vkreact_reaction_table ON $this->table.reaction_id=$this->vkreact_reaction_table.id
              WHERE postcomment_id=%d AND reaction_id=%d";

    return $this->wpdb->get_results(
      $this->wpdb->prepare($sql, [$postcomment_id, $reaction_id])
    );
  }
}

?>