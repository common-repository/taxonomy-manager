<?php
  /*
   Plugin Name: Taxonomy Manager
   Plugin URI: http://pranav.me/plugins/taxonomy-manager
   Description: Taxonomy Manager -> Add, Edit, Delete & Manage taxonomies for posts, pages, links and custom post types with a few clicks of mouse. Makes adding taxonomies a 100 times easier. Display taxonomy tag clouds and more using widgets and display tags and other info at the bottom of posts.
   Version: 1.0.1
   Author: Pranav Rastogi
   Author URI: http://www.pranav.me
   */
  /*  Copyright 2010  Pranav Rastogi  (email : i@pranav.me)
   
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as
   published by the Free Software Foundation.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   */

  TaxonomyManagerInit::install_init();
abstract  class TaxonomyManagerInit
  {
      static function install_init()
      {
          register_activation_hook(__FILE__, 'TaxonomyManagerInit::install_database');
      } //static function install_init()
      static function from_database()
      {
          global $wpdb;
          $sql_query = "SELECT * FROM taxonomy_man;";
          $array = $wpdb->get_results($sql_query, ARRAY_A);
          //$array = (array) $array;
          return $array;
      } //static function from_database()
      //static static function TaxonomyManagerInit::from_database()
      static function install_database()
      {
          //if(Table_Exists('TaxonomyManager') != true)
          //{
          global $wpdb;
          $sql = "CREATE TABLE IF NOT EXISTS `taxonomy_man` (
`id` TINYINT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 150 ) NOT NULL ,
`type` VARCHAR( 150 ) NOT NULL ,
`hierarchical` VARCHAR( 150 ) NOT NULL ,
`label` VARCHAR( 150 ) NOT NULL ,
`query_var` VARCHAR( 150 ) NOT NULL ,
`rewrite` VARCHAR( 150 ) NOT NULL
)";
          $wpdb->query($wpdb->prepare($sql));
          $wpdb->flush();
          $wpdb->show_errors();
          $wpdb->print_error();
          //}
      } //static function install_database()
  } //class TaxonomyManagerInit
  require_once(dirname(__FILE__) . '/register_widget.php');
  require_once(dirname(__FILE__) . '/new_taxonomy.php');
  require_once(dirname(__FILE__) . '/get_taxonomies.php');
  // create custom plugin settings menu
  TaxonomyManager::init();
  abstract class TaxonomyManager
  {
      static function init()
      {
          add_action('admin_menu', array('TaxonomyManager', 'create_menu'));
          add_action('init', array('TaxonomyManager', 'register_taxonomies'), 10);
          add_action('admin_menu', array('TaxonomyManager', 'taxonomy_meta_boxes_for_page'));
          add_action('add_tag_form_pre', array('TaxonomyManager', 'message_change'), 10, 1);
          add_action('admin_menu', array('TaxonomyManager', 'taxonomy_meta_boxes_for_links'));
		  
      } //static function init()
      static function create_menu()
      {
          //create new top-level menu
          add_menu_page('Taxonomy Manager -> Show/Edit Taxonomies', 'Taxonomies', 'administrator', 'show-taxonomies', array('TMTaxonomiesShow', 'get_taxonomies'));
          add_submenu_page('show-taxonomies', 'Taxonomy Manager -> Create New Taxonomy', 'Add Taxonomy', 'administrator', 'new-taxonomy', array('AddTaxonomy', 'new_taxonomy'));
      } //static function create_menu()
      static function register_taxonomies()
      {
          $taxonomies = TaxonomyManagerInit::from_database();
          if (TaxonomyManagerInit::from_database()) {
              foreach ($taxonomies as $key => $taxo) {
                  $name = (string)$taxo['name'];
                  $name = preg_replace('/[^a-zA-Z0-9s]/', '', $name);
                  $type = (string)$taxo['type'];
                  if (explode("-", $type)) {
                      $type = explode("-", $type);
                  } //if (explode("-", $type))
                  $hierarchical = (string)$taxo['hierarchical'];
                  $label = (string)$taxo['label'];
                  $query_var = (string)$taxo['query_var'];
                  $rewrite = (string)$taxo['rewrite'];
                  //if(!isset($size) || $size == 1) {
                  if ($hierarchical == '0') {
                      if ($query_var == '1' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => true, "rewrite" => true));
                      } //if ($query_var == '1' && $rewrite == '1')
                      if ($query_var == '0' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => false, "rewrite" => true));
                      } //if ($query_var == '0' && $rewrite == '1')
                      if ($query_var == '1' && $rewrite == '0') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => true, "rewrite" => false));
                      } //if ($query_var == '1' && $rewrite == '0')
                      if ($query_var != '1' && $query_var != '0' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var == '1' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => true, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var == '1' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var == '0' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => false, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var == '0' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var != '1' && $query_var != '0' && $rewrite == '0') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => false));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite == '0')
                      if ($query_var != '1' && $query_var != '0' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => false, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => true));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite == '1')
                  } //if ($hierarchical == '0')
                  //top-most if for checking inside foreach if $heir = 0
                  if ($hierarchical == '1') {
                      if ($query_var == '1' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => true, "rewrite" => true));
                      } //if ($query_var == '1' && $rewrite == '1')
                      if ($query_var == '0' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => false, "rewrite" => true));
                      } //if ($query_var == '0' && $rewrite == '1')
                      if ($query_var == '1' && $rewrite == '0') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => true, "rewrite" => false));
                      } //if ($query_var == '1' && $rewrite == '0')
                      if ($query_var != '1' && $query_var != '0' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var == '1' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => true, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var == '1' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var == '0' && $rewrite != '0' && $rewrite != '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => false, "rewrite" => array('slug' => $rewrite)));
                      } //if ($query_var == '0' && $rewrite != '0' && $rewrite != '1')
                      if ($query_var != '1' && $query_var != '0' && $rewrite == '0') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => false));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite == '0')
                      if ($query_var != '1' && $query_var != '0' && $rewrite == '1') {
                          register_taxonomy($name, $type, array("hierarchical" => true, "label" => __($label, 'series'), "query_var" => $query_var, "rewrite" => true));
                      } //if ($query_var != '1' && $query_var != '0' && $rewrite == '1')
                  } //if ($hierarchical == '1')
                  //top-most if for checking and registering taxonomy inside foreach if $heir = 1
              } //foreach ($taxonomies as $key => $taxo)
              //foreach
          } //if (TaxonomyManagerInit::from_database())
          //if there is any set of results
      } //static function register_taxonomies()
      //end func()
      static function taxonomy_meta_boxes_for_page()
      {
          foreach (get_object_taxonomies('page') as $tax_name) {
              if (!is_taxonomy_hierarchical($tax_name)) {
                  $tax = get_taxonomy($tax_name);
                  add_meta_box("tagsdiv-{$tax_name}", $tax->label, array('TaxonomyManager', 'post_tags_meta_box'), 'page', 'side', 'core');
              } //if (!is_taxonomy_hierarchical($tax_name))
          } //foreach (get_object_taxonomies('page') as $tax_name)
      } //static function taxonomy_meta_boxes_for_page()
      static function taxonomy_meta_boxes_for_links()
      {
          foreach (get_object_taxonomies('link') as $tax_name) {
              if (!is_taxonomy_hierarchical($tax_name)) {
                  $tax = get_taxonomy($tax_name);
                  add_meta_box("tagsdiv-{$tax_name}", $tax->label, array('TaxonomyManager', 'post_tags_meta_box'), 'link', 'side', 'core');
              } //if (!is_taxonomy_hierarchical($tax_name))
          } //foreach (get_object_taxonomies('link') as $tax_name)
      } //static function taxonomy_meta_boxes_for_links()
      static function message_change()
      {
          $data = TaxonomyManagerInit::from_database();
          foreach ($data as $key => $taxono) {
              if ($taxono['name'] == $_GET['taxonomy']) {
                  echo '<div id="message" class="updated">You are managing: ';
                  echo $taxono['label'] . " added by Taxonomy Manager.";
                  echo '</div><br/>';
              } //if ($taxono['name'] == $_GET['taxonomy'])
          } //foreach ($data as $key => $taxono)
      } //static function message_change()
  } //abstract class TaxonomyManager
  
add_action('init', 'get_post_types_all');
			
 function get_post_types_all()
      {
          $args = array('public' => true);
          // or objects
          $output = 'names';
          $post_types = get_post_types($args, $output);
          return $post_types;
      } // function get_post_types_all()
?>