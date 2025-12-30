<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php wp_title(' | ', true, 'right') ?>
		</title>
		
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="renderer" content="webkit">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />


		<?php wp_deregister_script( 'jquery' );wp_register_script( 'jquery', 'https://cdn.bootcdn.net/ajax/libs/jquery/1.8.2/jquery.min.js' , false, null, false );wp_enqueue_script( 'jquery' );?>
		
		<?php wp_head(); ?>

		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.lazyload.min.js" type="text/javascript"></script>
		<script type="text/javascript">
		    $(function() {
		        $("#secondary img").lazyload({
		            effect:"fadeIn"
		          });
		        });
		    $(function() {
		        $("img").lazyload({
		            effect:"fadeIn"
		          });
		        });
		</script>

<!--[if lte IE 8]><script>document.write("<p style=\"color:red;font-size:40px;\">你正在使用 Internet Explorer 的过期版本（IE6、IE7、IE8）<br/>请<a href=\"#\" style=\"color:blue;\">升级您的浏览器</a>获得更好的浏览体验。</p>");</script><![endif]-->
	
	</head>