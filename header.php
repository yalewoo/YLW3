<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
		<meta name="renderer" content="webkit">
		<meta name="theme-color" content="#2563eb" media="(prefers-color-scheme: light)">
		<meta name="theme-color" content="#1e293b" media="(prefers-color-scheme: dark)">
		
		<!-- 性能优化：预连接百度统计 -->
		<link rel="preconnect" href="https://hm.baidu.com" crossorigin>
		<link rel="dns-prefetch" href="https://hm.baidu.com">
		
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />


		
		<?php wp_head(); ?>

<!--[if lte IE 8]><script>document.write("<p style=\"color:red;font-size:40px;\">你正在使用 Internet Explorer 的过期版本（IE6、IE7、IE8）<br/>请<a href=\"#\" style=\"color:blue;\">升级您的浏览器</a>获得更好的浏览体验。</p>");</script><![endif]-->
	
	</head>