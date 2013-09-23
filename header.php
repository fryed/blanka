<?php
//the core class
global $C;
?>
<!DOCTYPE html>
<!--[if IE 6]><html id="ie6" class="msie"><![endif]-->
<!--[if IE 7]><html id="ie7" class="msie"><![endif]-->
<!--[if IE 8]><html id="ie8" class="msie"><![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8) ]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	
<title><?php echo $C->pageMetaTitle(); ?></title>
	
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<base href="<?php echo $C->sitePath(); ?>/"/>

<link rel="shortcut icon" href="<?php echo $C->themePath(); ?>/images/favicon.png"/>

<link rel="stylesheet" href="<?php echo $C->themePath(); ?>/functions/min/?g=css" type="text/css"/>

<script type="text/javascript">
	window.themePath = "<?php echo $C->themePath(); ?>";
</script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $C->themePath(); ?>/functions/min/?g=js"></script>

<!--GOOGLE ANALYTICS-->

<!--END-->

<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo $C->themePath(); ?>/js/respond.min.js"></script>
<script type="text/javascript" src="<?php echo $C->themePath(); ?>/js/selectivizr.min.js"></script>
<![endif]-->

<?php wp_head(); ?>

</head>

<body class="<?php echo $C->pageTemplate(); ?>">
	
<nav class="mainNav">
	<?php echo $C->siteMenu(2); ?>
</nav>
