<?php
abstract class AddTaxonomy {
  //if form's been submitted
  static function Submit()
  {
      if (isset($_POST['submit']) && is_user_logged_in() && is_admin()) {
          $nonce2 = $_REQUEST['wpnonce'];
          if (!wp_verify_nonce($nonce2, 'Glad to see you!'))
              die('Security check');
          global $current_user;
          get_currentuserinfo();
          if ($current_user->user_level == 10) {
              //check taxonomy name - outer
              if (isset($_POST['tm_new_name']) && $_POST['tm_new_name'] != '') {
                  $tm_name = htmlentities($_POST['tm_new_name'], ENT_QUOTES);
                  //check taxonomy type - outer
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
                      //check if taxonomy label has been specified
                      if (isset($_POST['tm_new_label']) && $_POST['tm_new_label'] != "") {
                          $tm_label = $_POST['tm_new_label'];
                      } //if (isset($_POST['tm_new_label']) && $_POST['tm_new_label'] != "")
                      //if not then default to taxonomy name.
                      else {
                          $tm_label = $tm_name;
                      } //else
                      //check if var_query has been specified or not - outer
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
                              //check heir
                              if ($_POST['tm_new_heir'] && $_POST['tm_new_heir'] != '') {
                                  if ($_POST['tm_new_heir'] == "yes") {
                                      $tm_heir = '1';
                                  } //if ($_POST['tm_new_heir'] == "yes")
                                  else {
                                      $tm_heir = '0';
                                  } //else
								  $tm_label =  htmlentities($tm_label, ENT_QUOTES);
								  $tm_query_name = htmlentities($tm_query_name, ENT_QUOTES);
								  $tm_rewrite_slug = htmlentities($tm_rewrite_slug, ENT_QUOTES);
                                  $sql = "INSERT INTO taxonomy_man (name, type, hierarchical, label, query_var, rewrite) VALUES ('" . $tm_name . "', '" . $tm_type . "', '" . $tm_heir . "', '" . $tm_label . "', '" . $tm_query_name . "', '" . $tm_rewrite_slug . "')";
                                  global $wpdb;
                                  $query = $wpdb->query($wpdb->prepare($sql));
                                  if (!$query) {
                                      $wpdb->show_errors();
                                  } //if (!$query)
                                  else {
                                      $wpurl = get_bloginfo('wpurl');
                                      echo "Taxonomy {$tm_name} added! Go to <a href='" . $wpurl . "/wp-admin/admin.php?page=show-taxonomies'>Taxonomies</a> see the list of taxonomies.";
                                  } //else
                              } //if ($_POST['tm_new_heir'] && $_POST['tm_new_heir'] != '')
                          } //if ($_POST['tm_new_rewrite'])
                      } //if (isset($_POST['tm_new_query']))
                  } //if (isset($_POST['tm_new_type']) && $_POST['tm_new_type'] != '')
                  //check taxonomy type
                  else {
                      echo "taxonomy type not specified";
                  } //else
              } //if (isset($_POST['tm_new_name']) && $_POST['tm_new_name'] != '')
              //check taxonomy name
              else {
                  "taxonomy name not specified";
              } //else
          } //if ($current_user->user_level == 10)
          //check if user has access
      } //if (isset($_POST['submit']) && is_user_logged_in() && is_admin())
      //end checking for submit
  } //static function AddTaxonomy::Submit()
  //end AddTaxonomy::Submit()
  // create custom plugin settings menu
static function new_taxonomy()
  {
      $nonce = wp_create_nonce('Glad to see you!');
      $wpurl = get_bloginfo('wpurl');
      AddTaxonomy::Submit();
?>
<div class="wrap">
<h2>Taxonomy Manager -> Create New Taxonomy</h2>

<form method="post" action="<?php
      echo $wpurl
?>/wp-admin/admin.php?page=new-taxonomy&amp;wpnonce=<?php
      echo $nonce
?>">
    <table class="form-table">
  
           <tr valign="top">
        <th scope="row">Taxonomy Name</th>
        <td>
 <input type="text" name="tm_new_name" value="">
 </td>
 </tr>
         <tr valign="top">
        <th scope="row">Taxonomy For</th>
        <td>
 <input type="radio" name="tm_new_type" value="post">Post<br />
 <input type="radio" name="tm_new_type" value="page">Page<br />
 <input type="radio" name="tm_new_type" value="link">Links<br />
 <input type="radio" name="tm_new_type" value="type_post">Post Types<?php
      $types = get_post_types_all();
      foreach ($types as $key => $type) {
          if ($type != 'attachment' && $type != 'page')
              echo '&nbsp;&nbsp;<input type="checkbox" value="' . $type . '" name="post_types[]">&nbsp;&nbsp;' . ucfirst($type);
      } //foreach ($types as $key => $type)
?>
 </td>
 </tr>
 
          <tr valign="top">
        <th scope="row">Taxonomy Label</th>
        <td>
 <input type="text" name="tm_new_label" value="">
 </td>
 </tr>
 
          <tr valign="top">
        <th scope="row">Enable URL Querying</th>
        <td>
 <input type="radio" name="tm_new_query" value="yes">Yes. <br />

 Enter Query variable (if not entered, will be the same as the taxonomy name) <input type="text" name="tm_query_name" value="" /><br />

 <input type="radio" name="tm_new_query" value="no">No<br />
 </td>
 </tr>
 
           <tr valign="top">
        <th scope="row">Enable URL-Rewriting</th>
        <td>
 <input type="radio" name="tm_new_rewrite" value="yes">Yes.<br /> Enter Rewrite Slug(if not entered, will be the same as the taxonomy name) <input type="text" name="tm_rewrite_name" value=""><br />
 <input type="radio" name="tm_new_rewrite" value="no">No<br />
 </td>
 </tr>
       <tr valign="top">
        <th scope="row">Heirarchical</th>
        <td>
 <input type="radio" name="tm_new_heir" value="yes">Yes<br />
 <input type="radio" name="tm_new_heir" value="no">No<br />
 </td>
 </tr>
 
 </table>
     <p class="submit">
    <input type="submit" name="submit" class="button-primary" value="<?php
      _e('Add Taxonomy')
?>" />
    </p>
</form>
</div>
<?php
  } //static function tm_new_taxonomy()
  }
?>