<?php
//Template name: List children
get_header();
//grab children
$children = $C->pageChildren();
?>
	
<h1><?php echo $C->pageTitle(); ?></h1>
<?php if($C->isPageMeta("custom_subtitle")): ?>
<h2><?php echo $C->pageMeta("custom_subtitle"); ?></h2>
<?php endif; ?>

<?php echo $C->pageContent(); ?>
		
<ul>
	
<?php if(!empty($children)): ?>
	
	<?php foreach($children as $child): ?>
	<li>
		<a href="<?php echo $C->pageLink($child->ID); ?>" title="<?php echo $C->pageExcerpt($child->ID); ?>"><?php echo $C->pageFeatureImage("feature_thumb",false,$child->ID); ?></a>
		<h2><a href="<?php echo $C->pageLink($child->ID); ?>" title="<?php echo $C->pageExcerpt($child->ID); ?>"><?php echo $child->post_title; ?></a></h2>
		<p><?php echo $C->pageExcerpt($child->ID); ?> <a href="<?php echo $C->pageLink($child->ID); ?>" title="<?php echo $C->pageExcerpt($child->ID); ?>">Read more</a></p>
	</li>
	<?php endforeach; ?>

<?php else: ?>
	<li>Sorry, No Sub-pages found.</li>
<?php endif; ?>

</ul>

<?php get_footer(); ?>