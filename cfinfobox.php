<?php
/*
Plugin Name: Info Box
Plugin URI: www.romanbenedict.com/freebies
Description: This will take the custom fields on a post and display them neatly in the sidebar as a widget.
Author: Roman Benedict
Version: 0.5
Author URI: www.romanbenedict.com
License: GPL2
    
*/
///////////////////////////////////////////////////////////////////////////////////
//plugin function
function contents_cfinfobox()
{
//set up variables
$pageid = get_the_ID(); //get page ID
$objectfetch =  get_option("widget_cfinfobox");//object types from setting
$objectposttype=$objectfetch['posttype'];
$objectkeyword=$objectfetch['category'];
$objectelsetext=$objectfetch['elsetext'];
$ancestorarray=get_ancestors($pageid, $objectposttype);
	//foreach($ancestorarray as $ancestor){
	//echo $ancestor}
//do they match?)
if (in_array($objectkeyword, $ancestorarray)) 
{
    echo '<h3>'.get_the_title().'</h3>'; //display page title
	echo '<p>'.the_meta().'</p>';
}
else
{
echo $objectelsetext;
}
}
//the widget setup
function widget_cfinfobox($args) {
  extract($args);
 
  $options = get_option("widget_cfinfobox");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'More Information',
      ); 
  }      
 //the widget itself
  echo $before_widget;
    echo $before_title;
      echo $options['title'];
    echo $after_title;
 
    //the Widget Content
    contents_cfinfobox();
  echo $after_widget;
}
 //control function
function cfinfobox_control() 
{
  $blogtagline = get_bloginfo('description');
  $options = get_option("widget_cfinfobox");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'More Information',
	  'category' => 'None',
	  'posttype' => 'page',
	  'elsetext' => $blogtagline
      ); 
  }     
 
  if ($_POST['cfinfobox-Submit']) 
  {
    $options['title'] = htmlspecialchars($_POST['cfinfobox-WidgetTitle']);
	$options['category'] = htmlspecialchars($_POST['cfinfobox-WidgetCategory']);
	$options['posttype'] = htmlspecialchars($_POST['cfinfobox-WidgetPostType']);
	$options['elsetext'] = htmlspecialchars($_POST['cfinfobox-WidgetElseText']);
    update_option("widget_cfinfobox", $options);
  }
 
?>
  <p>
    <label for="cfinfobox-WidgetTitle">Widget Title: </label></br>
    <input type="text" id="cfinfobox-WidgetTitle" name="cfinfobox-WidgetTitle" value="<?php echo $options['title'];?>" /></br>
	<label for="cfinfobox-WidgetCategory">Display on Children Of: </label></br>
    <input type="text" id="cfinfobox-WidgetCategory" name="cfinfobox-WidgetCategory" value="<?php echo $options['category'];?>" /></br>
	<label for="cfinfobox-WidgetPostType">Type of Post: </label>
	<?php 
          $args=array(
                     'public'   => true,
                     '_builtin' => true
                     ); 
                $output = 'names';
                $operator = 'and';
				$currentsavedvalues=get_option("widget_cfinfobox");
                $post_types=get_post_types($args,$output,$operator); 

          echo '<select name="cfinfobox-WidgetPostType" id="cfinfobox-WidgetPostType">';
          foreach ($post_types  as $post_type ) {
          echo '<option value="'. $post_type.'"'.selected( $post_type, $currentsavedvalues['posttype']).'>'. $post_type. '</option>';
}echo '</select>';
?></br>
<label for="cfinfobox-WidgetElseText">Otherwise Display: </label></br>
    <input type="textarea" id="cfinfobox-WidgetElseText" name="cfinfobox-WidgetElseText" value="<?php echo $options['elsetext'];?>" /></br>
    <input type="hidden" id="cfinfobox-Submit" name="cfinfobox-Submit" value="1" />
  </p>
<?php
}
 //initiate plugin/widget
function cfinfobox_init()
{
  register_sidebar_widget(__('Info Box Fields'), 'widget_cfinfobox');
  register_widget_control(   'Info Box Fields', 'cfinfobox_control' );     
}
add_action("plugins_loaded", "cfinfobox_init");

//Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
    add_action( 'wp_enqueue_scripts', 'cfinfobox_stylesheet' );

    /**
     * Enqueue plugin style-file
     */
    function cfinfobox_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'cfinfobox-style', plugins_url('cfinfobox.css', __FILE__) );
        wp_enqueue_style( 'cfinfobox-style' );
		}
?>
