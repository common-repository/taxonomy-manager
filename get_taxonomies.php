<?php

abstract Class TMTaxonomiesShow {
  //generates taxonomy code

  static function get_taxonomies()
  {
      if (!isset($_GET['tid']) || $_GET['tid'] == '') {
          $nonce3 = wp_create_nonce('Glad to see you!');
          $wpurl = get_bloginfo('wpurl');
          TMTaxonomiesShow::Delete();
?>
<div class="wrap"> 
  <h2>Taxonomy Manager -> List of Taxonomies</h2>
  
    <form method="post" action="<?php
          echo $wpurl
?>/wp-admin/admin.php?page=show-taxonomies&amp;wpnonce=<?php
          echo $nonce3;
?>">
    <div class="wprp-info">
      <div class="wprp-buttons">
          selected: <input type="button" onclick="jQuery('#delete-confirm').slideToggle('slow');" class="button-secondary delete" name="delete-expand" value="delete"> <small>(* will be removed permanently)</small>
        </div><br />
      <span>Total Taxonomies:<?php
          echo sizeof(TaxonomyManagerInit::from_database());
?></span><br /><br />
    </div>
      
    <div id="delete-confirm" style="display:none; background:#fff; border:1px dotted #ccc; border-bottom:none;" class="wprp-archive">
      <strong style="padding:10px;">Once deleted, It will be Permanently removed from taxonomy manager.      <input type="submit" class="button-secondary delete" name="deleteit" value="Confirm Delete"> </strong>
    </div>
      <table cellspacing="0" class="widefat post fixed">
    <thead>
      <tr>
        <th class="check-column" scope="col"><input type="checkbox"></th>
                <th scope="col">Taxonomy Name</th>
        <th style="width: 80px;" scope="col"># Name</th>
                        <th style="width: 80px;" scope="col"># Type</th>
                                <th style="width: 80px;" scope="col"># Label</th>
      </tr>
    </thead>
        <tfoot>
      <tr>
        <th class="check-column" scope="col"><input type="checkbox"></th>
                <th scope="col">Taxonomy Name</th>
                <th style="width: 80px;" scope="col"># Name</th>
                        <th style="width: 80px;" scope="col"># Type</th>
                                <th style="width: 80px;" scope="col"># Label</th>
      </tr>
    </tfoot><?php
          $wpurl = get_bloginfo('wpurl');
          if (TaxonomyManagerInit::from_database()) {
              $taxonomies = TaxonomyManagerInit::from_database();
              foreach ($taxonomies as $key => $taxo) {
                  echo '<tbody>
              <tr class="alt">
              <th class="check-column" scope="row"><input type="checkbox" value="' . $taxo['id'] . '" name="taxonomyid[]"></th>
        <td><a title="Edit This Taxonomy" href="' . $wpurl . '/wp-admin/admin.php?page=show-taxonomies&amp;tid=' . $taxo['id'] . '&amp;edit=true">Taxonomy -> ' . $taxo['name'] . '</a></td>
        <td>' . ucfirst($taxo['name']) . '</td>';
                  echo '<td>';
                  if (explode("-", $taxo['type'])) {
                      $taxo_types = explode("-", $taxo['type']);
                      foreach ($taxo_types as $key => $taxo_type)
                          echo ucfirst($taxo_type) . "<br />";
                  } //if (explode("-", $taxo['type']))
                  else {
                      echo ucfirst($taxo['type']);
                  } //else
                  echo '</td><td> ' . ucfirst($taxo['label']) . '</td>

        </tr>
                          
    </tbody>';
              } //foreach ($taxonomies as $key => $taxo)
          } //if (TaxonomyManagerInit::from_database())
?>
  </table>
        </form>
    </div><?php
      } //if (!isset($_GET["s_0"]) || $_GET["s_1"] == "s_2")
      elseif (isset($_GET['tid']) && TaxonomyManagerInit::from_database() && $_GET['edit'] == "true" && $_GET['tid'] == abs(intval($_GET['tid']))) {
          TMTaxonomiesShow::edit_taxonomy($_GET['tid']);
      } //elseif (isset($_GET['tid']) && TaxonomyManagerInit::from_database() && $_GET['edit'] == "true" && $_GET['tid'] == abs(intval($_GET['tid'])))
      else {
          echo "What're you doing!? o_O Taxonomy does not exist.";
      } //else
  } //static function get_taxonomies()
static function edit_taxonomy($tid)
  {
      $nonce2 = wp_create_nonce('Hi, MJ!');
      TMTaxonomiesShow::Save($tid);
?>
<div class="wrap">
<h2>Taxonomy Manager -> Edit Taxonomy</h2><?php
      $wpurl = get_bloginfo('wpurl');
?>
<form method="post" action="<?php
      echo $wpurl
?>/wp-admin/admin.php?page=show-taxonomies&amp;tid=<?php
      echo $tid;
?>&amp;edit=true&amp;wpnonce=<?php
      echo $nonce2
?>">
    <table class="form-table">
  
           <tr valign="top">
        <th scope="row">Taxonomy Name</th>
        <td>
 <input type="text" name="tm_new_name" value="<?php
      echo TMTaxonomiesShow::name_get($tid);
?>">
 </td>
 </tr>
         <tr valign="top">
        <th scope="row">Taxonomy Type</th>
        <td>
 <input type="radio" name="tm_new_type" value="post"<?php
      if (TMTaxonomiesShow::type_get($tid) == "post") {
          echo ' checked';
      } //if (TMTaxonomiesShow::type_get($tid) == "post")
?>>Post<br />
 <input type="radio" name="tm_new_type" value="page"<?php
      if (TMTaxonomiesShow::type_get($tid) == "page") {
          echo ' checked';
      } //if (TMTaxonomiesShow::type_get($tid) == "page")
?>>Page<br />
<input type="radio" name="tm_new_type" value="link"<?php
      if (TMTaxonomiesShow::type_get($tid) == "link") {
          echo ' checked';
      } //if (TMTaxonomiesShow::type_get($tid) == "link")
?>>Links<br />
<input type="radio" name="tm_new_type" value="type_post"<?php
      if (explode("-", TMTaxonomiesShow::type_get($tid))) {
          echo ' checked';
      } //if (explode("-", TMTaxonomiesShow::type_get($tid)))
?>>Post Types<?php
      $types = get_post_types_all();
	  if($types != "") {
      $counter = 0;
      foreach ($types as $key => $type) {
          if ($type != 'attachment' && $type != 'page')
              if (explode("-", TMTaxonomiesShow::type_get($tid))) {
                  $taxo_types = explode("-", TMTaxonomiesShow::type_get($tid));
                  $count = sizeOf($taxo_types);
                  if ($taxo_types[$counter] == $type)
                      echo '&nbsp;&nbsp;<input type="checkbox" value="' . $type . '" name="post_types[]" checked>&nbsp;&nbsp;' . $type;
                  else
                      echo '&nbsp;&nbsp;<input type="checkbox" value="' . $type . '" name="post_types[]">&nbsp;&nbsp;' . $type;
                  if ($counter < $count) {
                      $counter = $counter + 1;
                  } //if ($counter < $count)
              } //if (explode("-", TMTaxonomiesShow::type_get($tid)))
              else {
                  echo '&nbsp;&nbsp;<input type="checkbox" value="' . $type . '" name="post_types[]">&nbsp;&nbsp;' . $type;
              } //else
      } //foreach ($types as $key => $type)
	  }
?>
 </td>
 </tr>
 
          <tr valign="top">
        <th scope="row">Taxonomy Label</th>
        <td>
 <input type="text" name="tm_new_label" value="<?php
      echo TMTaxonomiesShow::label_get($tid);
?>">
 </td>
 </tr>
 
          <tr valign="top">
        <th scope="row">Enable URL Querying</th>
        <td>
 <input type="radio" name="tm_new_query" value="yes"<?php
      if (TMTaxonomiesShow::query_get($tid) != "0" || TMTaxonomiesShow::query_get($tid) == "1") {
          echo " checked";
      } //if (TMTaxonomiesShow::query_get($tid) != "0" || TMTaxonomiesShow::query_get($tid) == "1")
?>>Yes. <br />

 Enter Query variable (if not entered, will be the same as the taxonomy name) <input type="text" name="tm_query_name" value="<?php
      if (TMTaxonomiesShow::query_get($tid) != "" && TMTaxonomiesShow::query_get($tid) != "1" && TMTaxonomiesShow::query_get($tid) != "0") {
          echo TMTaxonomiesShow::query_get($tid);
      } //if (TMTaxonomiesShow::query_get($tid) != "" && TMTaxonomiesShow::query_get($tid) != "1" && TMTaxonomiesShow::query_get($tid) != "0")
?>" /><br />

 <input type="radio" name="tm_new_query" value="no"<?php
      if (TMTaxonomiesShow::query_get($tid) != "" && TMTaxonomiesShow::query_get($tid) == "0") {
          echo " checked";
      } //if (TMTaxonomiesShow::query_get($tid) != "" && TMTaxonomiesShow::query_get($tid) == "0")
?>>No<br />
 </td>
 </tr>
 
           <tr valign="top">
        <th scope="row">Enable URL-Rewriting</th>
        <td>
 <input type="radio" name="tm_new_rewrite" value="yes"<?php
      if (TMTaxonomiesShow::rewrite_get($tid) != "0" || TMTaxonomiesShow::rewrite_get($tid) == "1") {
          echo " checked";
      } //if (TMTaxonomiesShow::rewrite_get($tid) != "0" || TMTaxonomiesShow::rewrite_get($tid) == "1")
?>>Yes.<br /> Enter Rewrite Slug(if not entered, will be the same as the taxonomy name) <input type="text" name="tm_rewrite_name" value="<?php
      if (TMTaxonomiesShow::rewrite_get($tid) != "" && TMTaxonomiesShow::rewrite_get($tid) != "1" && TMTaxonomiesShow::rewrite_get($tid) != "0") {
          echo TMTaxonomiesShow::rewrite_get($tid);
      } //if (TMTaxonomiesShow::rewrite_get($tid) != "" && TMTaxonomiesShow::rewrite_get($tid) != "1" && TMTaxonomiesShow::rewrite_get($tid) != "0")
?>"><br />
 <input type="radio" name="tm_new_rewrite" value="no"<?php
      if (TMTaxonomiesShow::rewrite_get($tid) != "" && TMTaxonomiesShow::rewrite_get($tid) == "0") {
          echo " checked";
      } //if (TMTaxonomiesShow::rewrite_get($tid) != "" && TMTaxonomiesShow::rewrite_get($tid) == "0")
?>>No<br />
 </td>
 </tr>
       <tr valign="top">
        <th scope="row">Heirarchical</th>
        <td>
 <input type="radio" name="tm_new_heir" value="yes"<?php
      if (TMTaxonomiesShow::heir_get($tid) == '1') {
          echo " checked";
      } //if (TMTaxonomiesShow::heir_get($tid) == '1')
?>>Yes<br />
 <input type="radio" name="tm_new_heir" value="no"<?php
      if (TMTaxonomiesShow::heir_get($tid) == '0') {
          echo " checked";
      } //if (TMTaxonomiesShow::heir_get($tid) == '0')
?>>No<br />
 </td>
 </tr>
 
 </table>
     <p class="submit">
    <input type="submit" name="submit" class="button-primary" value="<?php
      _e('Save')
?>" />
    </p>
</form>
<?php
  } //static function edit_taxonomy($tid)

  static function name_get($id)
  {
      global $wpdb;
      $sql = "SELECT name FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['name'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::name_get($id)
  static function type_get($id)
  {
      global $wpdb;
      $sql = "SELECT type FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['type'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::type_get($id)
  static function label_get($id)
  {
      global $wpdb;
      $sql = "SELECT label FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['label'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::label_get($id)
  static function query_get($id)
  {
      global $wpdb;
      $sql = "SELECT query_var FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['query_var'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::query_get($id)
  static function rewrite_get($id)
  {
      global $wpdb;
      $sql = "SELECT rewrite FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['rewrite'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::rewrite_get($id)
  static function heir_get($id)
  {
      global $wpdb;
      $sql = "SELECT hierarchical FROM taxonomy_man WHERE id = " . $id;
      $run = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
      return $run[0]['hierarchical'];
      if (!$run) {
          echo mysql_error();
      } //if (!$run)
  } //static function TMTaxonomiesShow::heir_get($id)
  static function Save($tid)
  {
      if (isset($_POST['submit']) && is_user_logged_in() && is_admin()) {
          $nonce2 = $_REQUEST['wpnonce'];
          if (!wp_verify_nonce($nonce2, 'Hi, MJ!'))
              die('Security check');
          global $current_user;
          get_currentuserinfo();
          if ($current_user->user_level == 10) {
              global $wpdb;
              if (isset($_POST['tm_new_query'])) {
                  // if yes, set true, else set false.
                  if ($_POST['tm_new_query'] == "yes") {
                      $tm_query = true;
                  } //if ($_POST['tm_new_query'] == "yes")
                  else {
                      $tm_query = false;
                  } //else
                  //if query name is specified and query_var is true
                  if ($tm_query == true) {
                      if ($_POST['tm_query_name'] != "") {
                          $tm_query_name = $_POST['tm_query_name'];
                      } //if ($_POST['tm_query_name'] != "")
                      else {
                          $tm_query_name = '1';
                      } //else
                  } //if ($tm_query == true)
                  else {
                      $tm_query_name = '0';
                  } //else
              } //if (isset($_POST['tm_new_query']))
              //if rewrite is wanted
              if ($_POST['tm_new_rewrite']) {
                  //check if rewrite is yes or no.
                  if ($_POST['tm_new_rewrite'] == "yes") {
                      $tm_rewrite = true;
                  } //if ($_POST['tm_new_rewrite'] == "yes")
                  else {
                      $tm_rewrite = false;
                  } //else
                  //check if user has specified rewrite slug.
                  if ($tm_rewrite == true) {
                      if ($_POST['tm_rewrite_name'] != "") {
                          $tm_rewrite_slug = $_POST['tm_rewrite_name'];
                      } //if ($_POST['tm_rewrite_name'] != "")
                      else {
                          $tm_rewrite_slug = '1';
                      } //else
                  } //if ($tm_rewrite == true)
                  else {
                      $tm_rewrite_slug = '0';
                  } //else
              } //if ($_POST['tm_new_rewrite'])
              //check heir
              if ($_POST['tm_new_heir'] && $_POST['tm_new_heir'] != '') {
                  if ($_POST['tm_new_heir'] == "yes") {
                      $tm_heir = '1';
                  } //if ($_POST['tm_new_heir'] == "yes")
                  else {
                      $tm_heir = '0';
                  } //else
              } //if ($_POST['tm_new_heir'] && $_POST['tm_new_heir'] != '')
              if (isset($_POST['tm_new_type']) && $_POST['tm_new_type'] != '') {
                  $tm_type = $_POST['tm_new_type'];
                  if ($tm_type == "type_post") {
                      $types = $_POST['post_types'];
                      if (is_array($types)) {
                          foreach ($types as $typ) {
                              $type .= $typ . " ";
                          } //foreach ($types as $typ)
                          $type = trim($type);
                          $type = str_replace(" ", "-", $type);
                          $tm_type = $type;
                      } //if (is_array($types))
                      else {
                          $tm_type = $types;
                      } //else
                  } //if ($tm_type == "type_post")
              } //if (isset($_POST['tm_new_type']) && $_POST['tm_new_type'] != '')
			  
			  $tm_name = $_POST['tm_new_name'];
			  $tm_name = htmlentities($tm_name, ENT_QUOTES);
			  								  $tm_label =  htmlentities($tm_label, ENT_QUOTES);
								  $tm_query_name = htmlentities($tm_query_name, ENT_QUOTES);
								  $tm_rewrite_slug = htmlentities($tm_rewrite_slug, ENT_QUOTES);
								  if(isset($_POST['tm_new_name']) && isset($_POST['tm_new_rewrite']) && isset($_POST['tm_new_type']) && isset($_POST['tm_new_heir']) && isset($_POST['tm_new_query']))
              $wpdb->update('taxonomy_man', array('name' => $tm_name, 'type' => $tm_type, 'hierarchical' => $tm_heir, 'label' => $_POST['tm_new_label'], 'query_var' => $tm_query_name, 'rewrite' => $tm_rewrite_slug), array('id' => $tid), array('%s', '%s', '%s', '%s', '%s', '%s'), array('%d'));
              $wpdb->print_error();
              $wpdb->flush();
              echo "Success!";
          } //if ($current_user->user_level == 10)
      } //if (isset($_POST['submit']) && is_user_logged_in() && is_admin())
  } //static function TMTaxonomiesShow::Save($tid)
  static function Delete()
  {
      if (isset($_POST['deleteit']) && !empty($_POST['taxonomyid']) && !empty($_POST['deleteit'])) {
          if (is_user_logged_in() && is_admin()) {
              $nonce3 = $_REQUEST['wpnonce'];
              if (!wp_verify_nonce($nonce3, 'Glad to see you!'))
                  die('Security check');
              global $current_user;
              get_currentuserinfo();
              if ($current_user->user_level == 10) {
                  // $ids = $_POST['taxonomyid'];
                  //echo $ids;
                  foreach ($_POST['taxonomyid'] as $id) {
                      global $wpdb;
                      $wpdb->query($wpdb->prepare("DELETE FROM taxonomy_man WHERE id = " . $id));
                  } //foreach ($_POST['taxonomyid'] as $id)
                  echo "Taxonomies deleted";
              } //if ($current_user->user_level == 10)
          } //if (is_user_logged_in() && is_admin())
      } //if (isset($_POST['deleteit']) && !empty($_POST['taxonomyid']) && !empty($_POST['deleteit']))
  } //static function TMTaxonomiesShow::Delete()
  }
?>