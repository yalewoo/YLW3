<body>
<header id="topheader">
	<hgroup>
		<h1><a href = "<?php bloginfo("url")?>"><?php bloginfo('name'); ?></a>
		</h1>
		<h2><?php bloginfo("description")?></h2>
	</hgroup>

	<div id="top_menu">
		<?php wp_nav_menu( array( 'theme_location' => 'top_menu')); ?>
		<?php get_search_form( true ); ?>
	</div>
	
	
</header>
<nav class="main_nav">
	<?php wp_nav_menu( array( 'theme_location' => 'main_menu' )); ?>
</nav>