<?php 
/*
 * ADD CUSTOM META BOXES FUNCTION
 */
 
/*
 * USAGE
 * $metaBoxes = array(
 * 		"title" 	=> "My Meta Boxes",
 * 		"type"		=> "page",
 * 		"template"  => "page",
 * 		"fields"	=> array("name","title")
 * );
 */

 //add meta box hook
function addMetaBoxes(){
	global $metaBoxes;
	if(isset($metaBoxes["template"])){
		$C = new core();
		if($C->pageTemplate() != $metaBoxes["template"]){
			return false;
		}
	}
	if(!isset($metaBoxes["title"])){
		$metaBoxes["title"] = "Custom Meta";
	}
	if(!isset($meta["type"])){
		add_meta_box("custom_meta",$metaBoxes["title"],"addCustomMeta","page","normal","high"); 
		add_meta_box("custom_meta",$metaBoxes["title"],"addCustomMeta","post","normal","high"); 
	}else{
		if($meta["type"] == "page"){
			add_meta_box("custom_meta",$metaBoxes["title"],"addCustomMeta","page","normal","high"); 
		}elseif(($meta["type"] == "post")){
			add_meta_box("custom_meta",$metaBoxes["title"],"addCustomMeta","post","normal","high"); 
		}
	}
}

//add the actual boxes
function addCustomMeta(){
	global $post;
	global $metaBoxes;
	$html = "<div class='customMeta'>";
	foreach($metaBoxes["fields"] as $m){
		$meta[$m] = get_post_meta($post->ID,"custom_".$m,true);
		$label = ucfirst($m);
		$html .= "<div class='custRow'><label>$label:</label><input type='text' value='".$meta[$m]."' name='custom_$m' placeholder='$m' $required/></div>";
	}
	$html .= "</div><br style='clear:both;'/>";
	$css = "
		<style type='text/css'>
			.customMeta .custRow {
				width:100%;
				float:left;
				margin:10px 0;
			}
			.customMeta label {
				float:left;
				width:10%;
			}
			.customMeta input,
			.customMeta textarea {
				float:left;
				width:80%;
			}
		</style>
	";
	echo $css.$html;
}

//save the meta boxes
function saveMetaBoxes($id){
	foreach($_POST as $key => $val){
		if(strstr($key,"custom_")){
			$val = addslashes(esc_html(stripslashes($val)));
			update_post_meta($id,$key,$val);
		}
	}
}

?>