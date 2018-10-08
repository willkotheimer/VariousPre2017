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
<!--facebook javascript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div id="wrapper" class="hfeed">

<div id="container">

<div class="wrap ">
	<div class="section sTop">
		<div class="top0">
	<!--<div class="socialtags">
		<ul>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>twitter.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>facebook.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>youtube.png"/></li>
		<li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>instagram.png"/></li>
		</ul>
	</div>-->
	</div>

	<div class="subMenu" >
	 	<div class="inner">
	
  			<a href="#sTop" class="subNavBtn">HOME</a>
			<a href="#s2" class="subNavBtn">MENU</a>
			<a href="#s3" class="subNavBtn">ABOUT US</a> 
			<a href="#s5" class="subNavBtn">CONTACT/MAP</a>
			<a href="#s6" class="subNavBtn">EVENTS</a></div>
	</div>