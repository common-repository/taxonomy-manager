<?php
  class Display_Widget extends WP_Widget
  {
      function Display_Widget()
      {
          /* Widget settings. */
          $widget_ops = array('classname' => 'Taxonomy', 'description' => 'A widget that displays a cloud/list from a taxonomy');
          /* Widget control settings. */
          $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'taxonomycloud');
          /* Create the widget. */
          $this->WP_Widget('taxonomycloud', 'Taxonomy Cloud', $widget_ops);
      } //function Display_Widget()
      function widget($args, $instance)
      {
          extract($args);
          /* User-selected settings. */
          $title = esc_attr($instance['title']);
          $name = esc_attr($instance['name']);
          $number = intval($instance['number']);
		   $format = esc_attr($instance['format']);
		   $separator = esc_attr($instance['separator']);
		   $orderby = esc_attr($instance['orderby']);
		   $order = esc_attr($instance['order']);
$exclude = $instance['exclude'];
$exclude = str_replace(" ", "", $exclude);
$include = $instance['include'];
$include = str_replace(" ", "", $include);
$link = esc_attr($instance['link']);
          /* Before widget (defined by themes). */
          echo $before_widget;
          /* Title of widget (before and after defined by themes). */
          if ($title) {
              echo $before_title . $title . $after_title;
          } //if ($title)
          /* Display name from widget settings. */
          if ($name) {
              wp_tag_cloud(array('taxonomy' => $name, 'number' => 15, 'format' => $format, 'separator' => $separator, 'orderby' => $orderby, 'order' => $order, 'exclude' => $exclude, 'include' => $include, 'link' => $link));
          } //if ($name)
          /* Show number */
          /* After widget (defined by themes). */
          echo $after_widget;
      } //function widget($args, $instance)
      function update($new_instance, $old_instance)
      {
          $instance = $old_instance;
          /* Strip tags (if needed) and update the widget settings. */
          $instance['title'] = esc_attr($new_instance['title']);
          $instance['name'] = esc_attr($new_instance['name']);
          $instance['number'] = intval($new_instance['number']);
		  $instance['format'] = esc_attr($instance['format']);
		  		   $instance['separator'] = esc_attr($instance['separator']);
		   $instance['orderby'] = esc_attr($instance['orderby']);
		   $instance['order'] = esc_attr($instance['order']);
$instance['exclude'] = $instance['exclude'];
$instance['include'] = $instance['include'];
$instance['link'] = esc_attr($instance['link']);
          return $instance;
      } //function update($new_instance, $old_instance)
      function form($instance)
      {
          /* Set up some default widget settings. */
          $defaults = array('title' => 'Taxonomy Cloud', 'name' => 'post_tag', 'number' => 0, 'format' => 'flat', 'separator' => '/n', 'orderby' => 'name', 'order' => 'ASC', 'exclude' => '', 'include' => '', 'link' => 'view');
          $instance = wp_parse_args((array)$instance, $defaults);
?>

    <p>
      <label for="<?php
          echo $this->get_field_id('title');
?>">Title:</label>
      <input id="<?php
          echo $this->get_field_id('title');
?>" name="<?php
          echo $this->get_field_name('title');
?>" value="<?php
          echo $instance['title'];
?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php
          echo $this->get_field_id('name');
?>">Taxonomy Name:</label>
      <select id="<?php
          echo $this->get_field_id('name');
?>" name="<?php
          echo $this->get_field_name('name');
?>">
<?php
          if (get_taxonomies()) {
              $taxonomies = get_taxonomies();
              foreach ($taxonomies as $key => $taxo) {
                  if ($instance['name'] == $taxo) {
                      echo '<option checked>' . $taxo;
                  } //if ($instance['name'] == $taxo['name'])
                  else {
                      echo '<option>' . $taxo;
                  } //else
              } //foreach ($taxonomies as $key => $taxo)
          } //if (TaxonomyManagerInit::from_database())
?>
</select>
      
    
    </p>
    
	    <p>
      <label for="<?php
          echo $this->get_field_id('format');
?>">Display:</label>
      <select id="<?php
          echo $this->get_field_id('format');
?>" name="<?php
          echo $this->get_field_name('format');
?>">
<?php
$formats = array('list', 'flat');
foreach ($formats as $format) {
                  if ($instance['format'] == $format) {
                      echo '<option checked>' . $format;
                  } //if ($instance['name'] == $taxo['name'])
                  else {
                      echo '<option>' . $format;
                  } //else
}
?>
</select>
      
    
    </p>
        <p>
<label for="<?php
          echo $this->get_field_id('number');
?>">Limit(entering 0 (zero) will display all):</label>
<input id="<?php
          echo $this->get_field_id('number');
?>" name="<?php
          echo $this->get_field_name('number');
?>" value="<?php
          echo $instance['number'];
?>" style="width:100%;" />
    </p>
	
	        <p>
<label for="<?php
          echo $this->get_field_id('separator');
?>">Separator:</label>
<input id="<?php
          echo $this->get_field_id('separator');
?>" name="<?php
          echo $this->get_field_name('separator');
?>" value="<?php
          echo $instance['separator'];
?>" style="width:100%;" />
    </p>
  
	    <p>
      <label for="<?php
          echo $this->get_field_id('orderby');
?>">Order By:</label>
      <select id="<?php
          echo $this->get_field_id('orderby');
?>" name="<?php
          echo $this->get_field_name('orderby');
?>">
<?php
$formats = array('name', 'count');
foreach ($formats as $format) {
                  if ($instance['orderby'] == $format) {
                      echo '<option checked>' . $format;
                  } //if ($instance['name'] == $taxo['name'])
                  else {
                      echo '<option>' . $format;
                  } //else
}
?>
</select>
      
    
    </p>
	
	    <p>
      <label for="<?php
          echo $this->get_field_id('order');
?>">Order (ASC = asceding, DESC = descending, RAND = random) :</label>
      <select id="<?php
          echo $this->get_field_id('order');
?>" name="<?php
          echo $this->get_field_name('order');
?>">
<?php
$formats = array('ASC', 'DESC', 'RAND');
foreach ($formats as $format) {
                  if ($instance['order'] == $format) {
                      echo '<option checked>' . $format;
                  } //if ($instance['name'] == $taxo['name'])
                  else {
                      echo '<option>' . $format;
                  } //else
}
?>
</select>
      
    
    </p>
	
		        <p>
<label for="<?php
          echo $this->get_field_id('exclude');
?>">Exclude (Comma separated) :</label>
<input id="<?php
          echo $this->get_field_id('exclude');
?>" name="<?php
          echo $this->get_field_name('exclude');
?>" value="<?php
          echo $instance['exclude'];
?>" style="width:100%;" />
    </p>
	
		        <p>
<label for="<?php
          echo $this->get_field_id('include');
?>">Include (Comma separated) :</label>
<input id="<?php
          echo $this->get_field_id('include');
?>" name="<?php
          echo $this->get_field_name('include');
?>" value="<?php
          echo $instance['include'];
?>" style="width:100%;" />
    </p>

		    <p>
      <label for="<?php
          echo $this->get_field_id('link');
?>">Link (Display normal viewing link, or link to edit) :</label>
      <select id="<?php
          echo $this->get_field_id('link');
?>" name="<?php 
          echo $this->get_field_name('link');
?>">
<?php
$formats = array('view', 'edit');
foreach ($formats as $format) {
                  if ($instance['link'] == $format) {
                      echo '<option checked>' . $format;
                  } //if ($instance['name'] == $taxo['name'])
                  else {
                      echo '<option>' . $format;
                  } //else
}
?>
</select>
      
    
    </p>
	
<input type="hidden" id="<?php
          echo $this->get_field_id('submit');
?>" name="<?php
          echo $this->get_field_name('submit');
?>" value="1" />    
<?php
      } //function form($instance)
  } //class Display_Widget extends WP_Widget
  /* Add our function to the widgets_init hook. */
  add_action('widgets_init', 'taxonomy_widget');
  /* Function that registers our widget. */
  function taxonomy_widget()
  {
      register_widget('Display_Widget');
  } //function taxonomy_widget()
?>