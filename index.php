<?php get_header(); ?>
<?php include("header-nav.php"); ?>



<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/tiaozhuanyema.js"></script>

<main id="main" role="main">
<div id="container">

	<section id="blog">
		
        <?php include("post-article.php"); ?>

	</section>

	<?php get_sidebar(); ?>
</div>
</main>
<?php get_footer(); ?>
