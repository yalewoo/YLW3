<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">跳至正文</a>
<header id="topheader">
	<hgroup>
		<h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a></h1>
		<h2><?php echo esc_html(get_bloginfo('description')); ?></h2>
	</hgroup>

	<div id="top_menu">
		<?php wp_nav_menu(array('theme_location' => 'top_menu')); ?>
		<?php get_search_form(true); ?>
	</div>
</header>

<nav class="main_nav">
	<?php wp_nav_menu(array('theme_location' => 'main_menu')); ?>
</nav>