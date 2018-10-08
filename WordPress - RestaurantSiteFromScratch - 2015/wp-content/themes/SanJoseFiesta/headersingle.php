<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link href='https://fonts.googleapis.com/css?family=Kameron|Alfa+Slab+One|Khand|Fira+Mono|Mrs+Saint+Delafield|Bowlby+One|Amatic+SC|Crete+Round|Peddana' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo( 'template_directory' ).'/style.css'; ?>" />
<?php wp_head(); ?>

<script type="text/javascript">




jQuery(document).ready(function( $ ) {
	
	$('.subMenu').smint({
    	'scrollSpeed' : 1200
    });

	
});

</script>

</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">

<div id="container">

<div class="wrap ">
	<div class="section sTop">
		<div class="top0">
	<div class="socialtags">
		<ul>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>twitter.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>facebook.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>youtube.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>instagram.png"/></li>
		</ul>
	</div>
	</div>

	<div class="subMenu" >
	 	<div class="inner">
	 	<a href="<?php echo get_home_url(); ?>#sTop" class="subNavBtn">SOCIAL</a>
	 	   
	 		
			<a href="<?php echo get_home_url(); ?>#s2" class="subNavBtn">MENU</a>
			<a href="<?php echo get_home_url(); ?>#s3" class="subNavBtn">ABOUT US</a> 
			<!--<a href="#s4" class="subNavBtn">EVENTS</a>-->
			<a href="<?php echo get_home_url(); ?>#s5" class="subNavBtn">CONTACT/MAP</a>
			<a href="<?php echo get_home_url(); ?>#s6" class="subNavBtn">Events</a>
			
		</div>
	</div>