<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
		<meta name="renderer" content="webkit">
		<meta name="theme-color" content="#2563eb" media="(prefers-color-scheme: light)">
		<meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />


		<?php wp_deregister_script( 'jquery' );wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js' , false, '3.7.1', false );wp_enqueue_script( 'jquery' );?>
		
		<?php wp_head(); ?>

<!--[if lte IE 8]><script>document.write("<p style=\"color:red;font-size:40px;\">你正在使用 Internet Explorer 的过期版本（IE6、IE7、IE8）<br/>请<a href=\"#\" style=\"color:blue;\">升级您的浏览器</a>获得更好的浏览体验。</p>");</script><![endif]-->
	
	</head>