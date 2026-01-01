<?php get_header(); ?>
<?php include("header-nav.php"); ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/tiaozhuanyema.js"></script>

<style type="text/css">
	
</style>


<main id="main" role="main">
<div id="container">

	
	<?php
		if(isset($_GET['author_name'])) :
		$curauth = get_userdatabylogin($author_name);
		else :
		$curauth = get_userdata(intval($author));
		endif;
	?>
	<div id="authordiv">
		<h3>作者档案</h3>
		<div class="author_da">

			<?php if($curauth->touxiang){ ?><div class="avatar"><img src="<?php echo $curauth->touxiang; ?>" /></div><?php } ?>
			<?php if($curauth->display_name){ ?><p><b>昵称：</b><?php echo $curauth->display_name; ?></p><?php } ?>
			<?php if($curauth->job){ ?><p><b>职业：</b><?php echo $curauth->job; ?></p><?php } ?>
			<?php if($curauth->addres){ ?><p><b>所在地：</b><?php echo $curauth->addres; ?></p><?php } ?>
			<?php if($curauth->user_url){ ?><p><b>主页：</b> <a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></p><?php } ?>
			
			<?php if($curauth->qq){ ?><p><b>QQ：</b><?php echo $curauth->qq; ?></p><?php } ?>
			<?php if($curauth->description){ ?><p><b>个人简介：</b><?php echo $curauth->description; ?></p><?php } ?>
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