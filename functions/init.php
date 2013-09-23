<?php
/*
 * INIT THE NKH THEME FUNCTIONS
 */

//load up the required files
require_once("core.php");
require_once("validator.php");
require_once("custom_metaboxes.php");

//add featured image support
add_theme_support("post-thumbnails");
add_image_size("square_thumb",300,300,true);
add_image_size("feature_thumb",300,180,true);
add_image_size("full_web",940,99999);

//define the custom metaboxes
$metaBoxes = array(
	"title"		=> "Page details",
	"fields"	=> array("subtitle"),
);

//add custom meta box hooks
add_action("add_meta_boxes","addMetaBoxes");
add_action("save_post","saveMetaBoxes");

//seperate out single php into seperate templates based on cat name
add_filter("single_template","filterPostTemplate");
function filterPostTemplate($template){
	global $post;
	$cat		= get_the_category($post->ID);  
	$cat 		= $cat[0]->slug;
	$newSingle	= "single-$cat.php";
	$template 	= locate_template(array($newSingle,"single.php"));
	return $template;
}

//init the core class
$C = new core();

?>