<?php
/*
 * THE CORE CLASS
 */
 
class core {
	
	/*
	 * @desc - inits the class
	 */
	public function __construct(){
		
	}
	
	/*
	* @desc 			- builds the main menu
	* @return (string) - the main menu
	*/
	public function siteMenu($depth=2,$classes=""){
			
		register_nav_menus(
			array("main-menu" => "Main Menu")
		);
		$mainNav = wp_nav_menu(
			array(
				"theme_location" => "main-menu",
				"echo" 			 => false,
				"depth"			 => $depth
			)
		);
		$replace = array("<div class=\"menu\"><ul>","</ul></div>");
		$mainNav = str_replace($replace,"",$mainNav);
		$mainNav = str_replace("current_page_item","active",$mainNav);
		$mainNav = "<ul class='$classes'>".$mainNav."</ul>";
		return $mainNav;
		
	}
	 
	/*
	 * @desc 			- works out the site path
	 * @return (string) - the site path
	 */
	public function sitePath(){
		
		return get_bloginfo("url");
		
	}
	
	/*
	 * @desc 			- grabs the site tags
	 * @return (array)  - the site tags
	 */
	public function siteTags(){
		
		$tags = get_tags();
		return $tags;
		
	}
	
	/*
	 * @desc 			- works out the sites theme path
	 * @return (string) - the site theme path
	 */
	public function themePath(){
		
		return get_bloginfo("template_directory");
		
	}
	
	/*
	 * @desc 			- get the page title
	 * @return (string) - the page title
	 */
	public function pageTitle($id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$title = get_the_title($id);
		return $title;
		
	}
	
	/*
	 * @desc 			- get the page meta title
	 * @return (string) - the page meta title
	 */
	public function pageMetaTitle($id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		if(is_404()){
			$title = "Page not found";
		}else{
			$title = get_the_title($id);
		}
		$title = $title." | ".get_bloginfo("name");
		return $title;
		
	}
	
	/*
	 * @desc 			- create a page body class
	 * @return (string) - the page body class
	 */
	public function pageClass($id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$cls = strtolower(str_replace(" ","-",get_the_title($id)));
		return $cls;
		
	}
	
	/*
	 * @desc 			- get the page content
	 * @return (string) - the page content
	 */
	public function pageContent($id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$content = get_post_field("post_content",$id);
		$content = apply_filters("the_content",$content);
		if($content != ""){
			return "<div class='editableContent'>".$content."</div>";
		}else{
			return "";
		}
		
	}
	
	/*
	 * @desc 			- get the page excerpt
	 * @return (string) - the page excerpt
	 */
	public function pageExcerpt($id=false,$truncate=60){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}else{
			$post = get_post($id);
		}
		$excerpt = $post->post_excerpt;
		if($excerpt == ""){
			$excerpt = strip_tags($this->truncate($this->pageContent($id),$truncate));
		}
		return $excerpt;
		
	}
	
	/*
	 * @desc 			- get the page slug
	 * @return (string) - the page slug
	 */
	public function pageLink($id=false){
		
		$slug = array();
		if(!$id){
			global $post;
		}else{
			$post = get_page($id);
		}
		$link = $post->guid;
		$pageTrail = $this->getPageTrail($post);
		foreach($pageTrail as $trail){
			$slug[] = $trail->post_name;
		}
		$slug = implode("/",array_reverse($slug));
		if(substr($slug,-1) != "/"){
			$slug = $slug."/";
		}
		return $slug;
		
	}
	
	/*
	 * @desc 			- get the post slug
	 * @return (string) - the post slug
	 */
	public function postLink($id=false){
		
		$slug = array();
		if(!$id){
			global $post;
		}else{
			$post = get_post($id);
		}
		$link = $post->guid;
		$cat 	 = get_the_category($post->ID);
		$cat 	 = $cat[0]->slug;
		$slug[]  = $cat; 
		$slug[] = $post->post_name;
		$slug = implode("/",$slug);
		if(substr($slug,-1) != "/"){
			$slug = $slug."/";
		}
		return $slug;
		
	}
	
	/*
	 * @desc 			- get the page template
	 * @return (string) - the page template
	 */
	public function pageTemplate(){
		
		$template = explode("/",get_page_template());
		$template = str_replace(".php","",$template[count($template)-1]);
		return $template;
		
	}
	
	/*
	 * @desc 					 - check the page template
	 * @param $template (string) - the page template to check against 
	 * @return (bool)			 - whether the template matches
	 */
	public function pageTemplateIs($template){
		
		$match = false;
		if($this->pageTemplate() == $template){
			$match = true;
		}
		return $match;
		
	}
	
	/*
	 * @desc 				- check the page catrgory
	 * @param $cat (string) - the page template to check against 
	 * @return (bool)		- whether the template matches
	 */
	public function pageCategoryIs($cat){
		
		$match = false;
		$curCat = get_the_category();
		foreach($curCat as $c){
			if($c->name == $cat){
				$match = true;
			}
		}
		return $match;
		
	}
	
	/*
	 * @desc 				- grab some post meta
	 * @param $key (string) - the meta key to fetch
	 * @param $id (int) 	- the id of the page meta to fetch
	 * @return (string)		- the meta value
	 */
	public function pageMeta($key,$id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$meta = get_post_meta($id,$key,true);
		return $meta;
		
	}
	
	/*
	 * @desc 				- check is a post has meta set
	 * @param $key (string) - the meta key to check
	 * @param $id (int) 	- the id of the page meta to check
	 * @return (bool)		- whether the meta value exists
	 */
	public function isPageMeta($key,$id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$meta = get_post_meta($id,$key,true);
		
		if($meta == ""){
			return false;
		}else{
			return true;
		}
		
	}
	
	/*
	 * @desc 					- grab a pages magic fields group
	 * @param $name (string) 	- the name of the group
	 * @param $id (int) 		- the id of the page group to get
	 * @return (array)			- the group
	 */
	public function pageGroup($name,$id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		if(function_exists("get_group")){
			$group = get_group($name,$id);
			return $group;
		}else{
			return array();
		}
	
		
	}
	 
	/*
	 * @desc 				 - get the page feature image
	 * @param $type (string) - the type of image to return ie thumb
	 * @param $link (bool) 	 - whether to wrap the image in a link
	 * @param $id (int) 	 - the id of the page feature to fetch
	 * @return (string) 	 - the page feature image
	 */
	public function pageFeatureImage($type="thumb",$link=false,$id=false,$raw=false,$class=""){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$image = false;
		if(has_post_thumbnail($id)){
			$imgId 		= get_post_thumbnail_id($id);
			$imgInfo 	= wp_get_attachment_image_src($imgId,$type);
			$imgAlt		= get_post_meta($imgId,"_wp_attachment_image_alt",true);
			$imgTitle	= get_the_title($imgId);
			$image		= "<img src='{$imgInfo[0]}' class='$class' width='{$imgInfo[1]}' height='{$imgInfo[2]}' alt='{$imgAlt}'/>";
			if($link){
				$bigInfo = wp_get_attachment_image_src($imgId,"full");
				$image 	 = "<a class='lightBox' title='{$imgTitle}' href='{$bigInfo[0]}'>$image</a>";
			}
			if($raw){
				$src 	= wp_get_attachment_image_src($imgId,$type);
				$image  = $src[0];
			}
		}
		return $image;
		
	}
	
	/*
	 * @desc 			 - get the page images
	 * @param $id (int)  - the id of the page images to fetch
	 * @return (array) 	 - list of pages images
	 */
	public function pageImages($id=false){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$images = array();
		$imgs = get_post_meta($id,"image");
		if(has_post_thumbnail($id)){
			$fid = get_post_thumbnail_id($id);
			array_unshift($imgs,$fid);
		}
		foreach($imgs as $i){
			$img = array(
				"full_src"  => wp_get_attachment_image_src($i,"full"),
				"thumb_src" => wp_get_attachment_image_src($i,"square_thumb"),
				"caption"	=> get_post($i)->post_excerpt,
				"title"		=> get_post($i)->post_title
			);
			$images[] = $img;
		}
		return $images;
		
	}
	
	/*
	 * @desc 			   		- get a single image by id
	 * @param $id (int)    		- the id of the image to fetch
	 * @param $type (string)  	- the size of image to fetch
	 * @return (string)    		- the image
	 */
	public function getImage($id,$type="thumb",$raw=false){
		
		$img = wp_get_attachment_image_src($id,$type);
		if(!$raw){
			$alt 	= get_post($id)->post_excerpt;
			$img = "<img src='{$img[0]}' width='{$img[1]}' height='{$img[2]}' alt='$alt'/>";
		}
		return $img;
		
	}
	
	/*
	 * @desc 			   		- get the page gallery
	 * @param $id (int)    		- the id of page images to fetch
	 * @return (array)    		- array of images
	 */
	public function pageGallery($id=false){
			
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$meta 	= get_post_meta($id,"gallery_image");
		$group 	= get_group("Gallery",$id);
		$gallery = array();
		foreach($meta as $key => $img){
			$imgPost = get_post($img);
			$gallery[$key]["thumb"] = $this->getImage($img,"full_web",true);
			$gallery[$key]["full"]  = $this->getImage($img,"full",true);
			$gallery[$key]["title"] = $imgPost->post_title;
		}
		$reordered = array();
		foreach($group[1]["gallery_image"] as $groupImage){
			foreach($gallery as $galleryImage){
				if($groupImage["o"] == $galleryImage["full"][0]){
					$reordered[] = $galleryImage;
				}
			}
		}
		$gallery = $reordered;
		return $gallery;
		
	}
	
	/*
	 * @desc 				- get children of a page
	 * @param $id (int) 	- the id of the parent
	 * @param $start (int) 	- the start index
	 * @param $max (int) 	- the max number to fetch
	 * @return (array) 		- an array of child pages
	 */
	public function pageChildren($id=false,$start=0,$max=999){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		$children = array();
		$pages = get_pages(array(
			"numberposts" 	=> $max,
			"offset"		=> $start,
			"child_of" 		=> $id,
			"sort_column" 	=> "menu_order"
		));
		$i = 1;
		foreach($pages as $page){
			if($page->post_parent == $id){
				if($i >= $start && $i <= $max){
					$page->thumb = $this->pageFeatureImage("thumb",false,$page->ID);
					$page->meta  = get_post_meta($page->ID);
					$children[]  = $page;
				}
				$i++;
			}
		}
		return $children;
		
	}
	
	/*
	 * @desc 				- get children of a page
	 * @param $id (int) 	- the id of the parent
	 * @param $start (int) 	- the start index
	 * @param $max (int) 	- the max number to fetch
	 * @return (array) 		- an array of child pages
	 */
	public function pageSiblings($id=false,$start=0,$max=999){
		
		if(!$id){
			global $post;
			$id 	= $post->ID;
		}else{
			$post = get_page($id);
		}
		$parent   = $post->post_parent;
		$siblings = array();
		if($parent != 0){
			$pages = get_pages(array(
				"numberposts" 	=> $max,
				"offset"		=> $start,
				"parent" 		=> $parent,
				"child_of"		=> $parent,
				"sort_column" 	=> "menu_order"
			));
			$i = 1;
			foreach($pages as $page){
				if($i >= $start && $i <= $max){
					$page->thumb = $this->pageFeatureImage("thumb",false,$page->ID);
					$page->meta  = get_post_meta($page->ID);
					$siblings[]  = $page;
				}
				$i++;
			}
		}
		return $siblings;
		
	}
	
	/*
	 * @desc 				- get related posts - ie posts in same cat
	 * @param $id (int) 	- the id of the page
	 * @param $start (int) 	- the start index
	 * @param $max (int) 	- the max number to fetch
	 * @return (array) 		- an array of related pages
	 */
	public function pageRelated($cat=false,$start=0,$max=999){
		
		if(!$cat){
			$catInfo = get_the_category();
			$catId	 = $catInfo[0]->term_id;
		}else{
			$catInfo = get_category_by_slug($cat);
			$catId	 = $catInfo->term_id;
		}
		if(!empty($catInfo)){
			$related = array();
			$posts = get_posts(array(
				"numberposts" 	=> $max,
				"offset"		=> $start,
				"category"		=> $catId
			));
			foreach($posts as $post){
				$post->thumb = $this->pageFeatureImage("thumb",false,$post->ID);
				$post->meta  = get_post_meta($post->ID);
				$related[]  = $post;
				$i++;
			}
		}
		return $related;
		
	}
	
	/*
	 * @desc 						- get the posts from a cat
	 * @param $catName (string) 	- the id of the parent
	 * @param $start (int) 			- the start index
	 * @param $max (int) 			- the max number to fetch
	 * @return (array) 				- an array of posts
	 */
	public function pagePosts($catName,$start=0,$max=999,$truncate=60,$onlySticky=false){
		
		if(isset($_GET["paging"])){
			$start = $_GET["paging"];
		}
		$list	 		= array();
		$catInfo 		= get_category_by_slug($catName);
		$catId	 		= $catInfo->term_id;
		$postNo  		= count(get_posts(array("numberposts"=>-1,"category"=>$catId)));
		$stickyPosts 	= get_option("sticky_posts");
		$sticky	 = get_posts(array(
			"post__in" 		=> $stickyPosts,
			"numberposts" 	=> $max,
			"category"		=> $catId,
			"post_status"	=> "publish"
		));
		$newMax = $max-count($sticky);
		if($newMax > 0 && !$onlySticky && !empty($stickyPosts)){
			$normal = get_posts(array(
				"post__not_in" 	=> $stickyPosts,
				"numberposts" 	=> $newMax,
				"offset"		=> $start,
				"category"		=> $catId,
				"post_status"	=> "publish"
			));
		}else{
			$normal = array();
		}
		$list["posts"] = array();
		$allPosts = array_merge($sticky,$normal);
		foreach($allPosts as $post){
			$post->thumb = $this->pageFeatureImage("thumb",false,$post->ID);
			$post->meta  = get_post_meta($post->ID);
			$post->tags	 = wp_get_post_tags($post->ID);
			if($post->post_excerpt == ""){
				$post->post_excerpt = strip_tags($this->truncate($post->post_content,$truncate));
			}
			$post->post_created = date("D jS M Y",strtotime($post->post_date));
			$list["posts"][] = $post;
		}
		$next = $start+$max;
		$prev = $start-$max;
		if($prev < 0){
			$prev = false;
		}else{
			$prev = "&paging=$prev";
		}
		if($next >= $postNo){
			$next = false;
		}else{
			$next = "&paging=$next";
		}
		$list["paging"] = array(
			"next"  => $next,
			"prev"	=> $prev
		);
		return $list;
		
	}

	
	/*
	 * @desc 					- grab a posts tags
	 * @param $id (int)			- the id of the post
	 * @return (array/string) 	- the tags
	 */
	function pageTags($id=false,$string=false,$divider=" "){
		
		if(!$id){
			global $post;
			$id = $post->ID;
		}
		
		$tags = wp_get_post_tags($id);
		if(!$string){
			return $tags;
		}

		$string = "";
		foreach($tags as $tag){
			$string .= $tag->slug.$divider;
		}
		return $string;
		
	}
	
	/*
	 * @desc 				- grab a pages top most parent
	 * @param $id (int)		- the id of the page parent
	 * @return (string) 	- the section
	 */
	function getTopParent($id=false){
		
		if(!$id){
			global $post;
			$parent = $post;
		}else{
			$parent = get_page($id);
		}
		
		if($parent->post_type == "post"){
			
			$cat = get_the_category($post->ID);
			$cat = $cat[0]->slug;
			$parent->cust_section = $cat;
			return $parent;
			
		}else{
	
			if($parent->post_parent != 0){
				return $this->getTopParent($parent->post_parent);
			}else{
				$parent->cust_section = $parent->post_name;
				return $parent;
			}
			
		}
		
	}
	
	/*
	 * @desc 				- grab all a pages parents
	 * @param $id (int)		- the id of the page parent
	 * @return (array) 		- an array of all the parents
	 */
	function getPageTrail($page,$trail=array()){
			
		$trail[] = $page;
		if($page->post_parent == 0){
			return $trail;
		}else{
			$parent = $this->getParent($page->ID);
			return $this->getPageTrail($parent,$trail);
		}
		
	}
	
	/*
	 * @desc 				- grab a pages parent
	 * @param $id (int)		- the id of the page parent
	 * @return (object) 	- the pages parent
	 */
	function getParent($id){
		
		$page = get_page($id);
		if($page->post_parent != 0){
			return get_page($page->post_parent);
		}else{
			return $page;
		}
		
	}
	
	/*
	 * @desc 				- echo out a gravity form
	 * @param $id (int)		- the id of the gravity form
	 * @echo (string) 		- the form
	 */
	public function pageForm($id,$class="form-horizontal"){
		
		global $formClass;
		$formClass = $class;
		add_filter("gform_form_tag","form_tag",10,2);
		add_filter("gform_field_content","subsection_field",10,5);
		add_filter("gform_submit_button","form_submit_button",10,2);
		function subsection_field($content,$field,$value,$lead_id,$form_id){
			if(strstr($content,"gsection_title")){
				$label 		= GFCommon::get_label($field);
				$newContent = "<h3>$label</h3>";
			}else{
				
				$label 		 = GFCommon::get_label($field);
				$placeholder = str_replace(":","",$label);
				$desc  		 = rgget("description",$field);
					
				$valiFailed  = rgget("failed_validation",$field);
				$valiMessage = $field["validation_message"];
				$isRequired	 = strstr($content,"gfield_required");
				$isPhone	 = strstr(strtolower($label),"phone");
				$isEmail	 = strstr(strtolower($label),"email");
				
				$input 		 = GFCommon::get_field_input($field,$value,0,$form_id);
				$input 		 = str_replace("ginput_container","",$input);
				$input 		 = str_replace("<input","<input placeholder='$placeholder' ",$input);
				$input 		 = str_replace("<textarea","<textarea placeholder='$placeholder' ",$input);
				
				if($isRequired){
					$input = str_replace("<input","<input required='required' ",$input); 
					$input = str_replace("<select","<select required='required' ",$input); 
					$input = str_replace("<textarea","<textarea required='required' ",$input); 
				}
				
				if($isPhone){
					//$input = str_replace("type='text'","type='number'",$input); 
				}
				
				if($isEmail){
					$input = str_replace("type='text'","type='email'",$input); 
				}
				
				$newContent = "
					<label>$label</label>
					<small class='validationMessage'>$valiMessage</small>
					<div class='controls'>
						$input
						<small><em>$desc</em></small>
					</div>	
				";
				
			}
			return $newContent;	
		}
		function form_tag($form_tag,$form){
			global $formClass;
			$form_tag = str_replace("<form","<form class='$formClass' ",$form_tag);
			return $form_tag;
		}
		function form_submit_button($button,$form){
			$button = "
				<div class='control-group'>
					 <label class='control-label'></label>
					 <div class='controls'>
					 	<input class='button' type='submit' value='Submit' id='gform_submit_button_{$form["id"]}'/>
					 </div>
				</div>
			";
			return $button;
		}
		echo "<div id='custForm'>";
		gravity_form($id,false,true,false,"",false);
		echo "</div>";
		
	} 
	
	/*
	 * @desc 					- truncate a string
	 * @param $string (string)	- the string to truncate
	 * @param $limit (int)		- the number of words to truncate to
	 * @return (string) 		- the page excerpt
	 */
	public function truncate($string,$limit){
		
		if(str_word_count($string,0) > $limit){
			$words 	= str_word_count($string,2);
			$pos 	= array_keys($words);
			$string = substr($string,0,$pos[$limit])."...";
		}
		return $string;
		
	}
	
	/*
	 * @desc 					- send a post value to the page
	 * @param $name (string)	- the name of the post field
	 * @return (string) 		- the field value
	 */
	public function postValue($name){
		
		$val = "";
		if(isset($_POST["$name"])){
			$val = $_POST["$name"];
		}
		return $val;
		
	}
	
	/*
	 * @desc					- gets a group image with thumb options
	 * @param $group (string)	- group field name
	 * @param $key (int)		- the key of the field in the array
	 * @param $id (int)			- id of the page
	 * @param $type (string)	- type of image to fetch
	 * @return (string)			- the image html
	 */
	public function getGroupImage($group,$key,$id,$type="feature_thumb"){
		
		//get the meta
		$meta 	= get_post_meta($id,$group);
		if(isset($meta[$key-1])){
			$id = $meta[$key-1];
			$img = $this->getImage($id,$type);
			return $img;
		}else{
			return false;
		}
		
	}
	
	/*
	 * @desc 				- check a text string for links and if found turn into anchor tags
	 * @param (string) 		- the string to process
	 * @return (string) 	- the string with links
	 */
	public function addLinks($text){
		$regEx = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		//if there is a link in the text
		if(preg_match($regEx,$text,$url)){
			//add the links into the text
			return preg_replace($regEx,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a>",$text);
		//else just return the text
		}else{
			return $text;
		}
	}
	
	/*
	 * @desc  			- check if running in live environment
	 * @return (bool) 	- live or not
	 */
	public function isLive(){
		$server = $_SERVER["SERVER_NAME"];
		if($server == "4logic.com.au" || $server == "www.4logic.com.au"){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	 * @desc  			- check if running in staging environment
	 * @return (bool) 	- staging or not
	 */
	public function isStaging(){
		$server = $_SERVER["SERVER_NAME"];
		if($server == "4logic.blackboxdesign.com.au"){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	 * @desc  					- prints out an object or array
	 * @param (array or object) - the array or object to debug 
	 */
	public function debug($debug){
		
		echo "<pre style='color:#fff; background:#000;'>";
		print_r($debug);
		echo "</pre>";
		exit;
		
	}
	
}
 
?>
