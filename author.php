<?php get_header(); ?>
<?php include("header-nav.php"); ?>

<style type="text/css">
	
</style>


<main id="main" role="main">
<div id="container">

	
	<?php
		$curauth = get_queried_object();
		if (!$curauth instanceof WP_User) {
			$author_name = get_query_var('author_name');
			$author_id = get_query_var('author');
			if (!empty($author_name)) {
				$curauth = get_user_by('slug', sanitize_title_for_query($author_name));
			} elseif (!empty($author_id)) {
				$curauth = get_user_by('id', absint($author_id));
			}
		}
		if (!$curauth instanceof WP_User) {
			$curauth = new WP_User(0);
		}
	?>
	<div id="authordiv">
		<h3>作者档案</h3>
		<div class="author_da">

			<?php if(!empty($curauth->touxiang)){ ?><div class="avatar"><img src="<?php echo esc_url($curauth->touxiang); ?>" alt="" /></div><?php } ?>
			<?php if(!empty($curauth->display_name)){ ?><p><b>昵称：</b><?php echo esc_html($curauth->display_name); ?></p><?php } ?>
			<?php if(!empty($curauth->job)){ ?><p><b>职业：</b><?php echo esc_html($curauth->job); ?></p><?php } ?>
			<?php if(!empty($curauth->addres)){ ?><p><b>所在地：</b><?php echo esc_html($curauth->addres); ?></p><?php } ?>
			<?php if(!empty($curauth->user_url)){ ?><p><b>主页：</b> <a href="<?php echo esc_url($curauth->user_url); ?>" rel="noopener noreferrer" target="_blank"><?php echo esc_html($curauth->user_url); ?></a></p><?php } ?>
			
			<?php if(!empty($curauth->qq)){ ?><p><b>QQ：</b><?php echo esc_html($curauth->qq); ?></p><?php } ?>
			<?php if(!empty($curauth->description)){ ?><p><b>个人简介：</b><?php echo esc_html($curauth->description); ?></p><?php } ?>
		</div>
	</div>

	<section id="blog">

		<h3 style="margin-bottom: 50px;">该作者的所有文章：</h3>


		<?php include("post-article.php"); ?>
		
	</section>

	<?php get_sidebar(); ?>
</div>
</main>
<?php get_footer(); ?>