<?php
//Template name: Contact page
get_header();
?>

<h1><?php echo $C->pageTitle(); ?></h1>
<?php if($C->isPageMeta("custom_subtitle")): ?>
<h2><?php echo $C->pageMeta("custom_subtitle"); ?></h2>
<?php endif; ?>
		
<?php echo $C->pageContent(); ?>

<?php $C->pageForm(1); ?>

<?php get_footer(); ?>