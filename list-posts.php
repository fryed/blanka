<?php
//Template name: List posts
get_header();
//grab posts
$posts = $C->pagePosts($pagename,0,20);
?>

<h1><?php echo $C->pageTitle(); ?></h1>
<?php if($C->isPageMeta("custom_subtitle")): ?>
<h2><?php echo $C->pageMeta("custom_subtitle"); ?></h2>
<?php endif; ?>
		
<?php echo $C->pageContent(); ?>
		
<ul>
	
<?php if(!empty($posts["posts"])): ?>
	
	<?php foreach($posts["posts"] as $key => $post): ?>
	<li>
		<a href="<?php echo $C->postLink($post->ID); ?>" title="<?php echo $post->post_excerpt; ?>"><?php echo $C->pageFeatureImage("feature_thumb",false,$post->ID); ?></a>
		<h2><a href="<?php echo $C->postLink($post->ID); ?>" title="<?php echo $post->post_excerpt; ?>"><?php echo $post->post_title; ?></a> - <small><?php echo $post->post_created; ?></small></h2>
		<p><?php echo $post->post_excerpt; ?> <a href="<?php echo $C->postLink($post->ID); ?>" title="<?php echo $posts->post_excerpt; ?>">Read more</a></p>
	</li>
	<?php endforeach; ?>

	<?php if($posts["paging"]["prev"] || $posts["paging"]["next"]): ?>
	<?php wp_reset_postdata(); ?>
	<div class="pagination">
		<ul>
			<?php if($posts["paging"]["prev"]): ?>
			<li class="prev"><a href="<?php echo $C->pageLink(get_the_ID()).$posts["paging"]["prev"]; ?>">&laquo; Prev</a></li>
			<?php endif; ?>
			<?php if($posts["paging"]["next"]): ?>
			<li class="next"><a href="<?php echo $C->pageLink(get_the_ID()).$posts["paging"]["next"]; ?>">Next &raquo;</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<?php endif; ?>

<?php else: ?>
	<li>Sorry, No <?php echo $C->pageTitle(); ?> found.</li>
<?php endif; ?>

</ul>
		
<?php get_footer(); ?>