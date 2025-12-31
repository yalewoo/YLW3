<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		<article <?php post_class(); ?> class="post" id="post-<?php the_ID(); ?>">
			
			<h2 class="post-title">

				<?php   $custom_fields = get_post_custom_keys(get_the_ID());
				if ($custom_fields && !in_array ('copyright', $custom_fields)) : ?>
				<span class = "title-meta-yuanchuang title-meta-ico"></span>
				<?php else: ?>
				<span class = "title-meta-zhuanzai title-meta-ico"></span>
				<?php endif; ?>




				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>

				<?php   //$custom_fields = get_post_custom_keys($post_id);
				if ($custom_fields && in_array ('recommend', $custom_fields)) : ?>
				<span class = "title-meta-recommend title-meta-ico"></span>
				<?php endif; ?>



				<?php $views = intval( get_post_meta( get_the_ID(), 'views', true ) );
				if ($views > 1000): ?>
				<span class = "title-meta-huo title-meta-ico"></span>
				<?php endif; ?>





				


			</h2>
			

			

				

			<div class="post-thumb">
			<img src="<?php echo catch_first_image() ?>" alt="<?php the_title(); ?>" loading="lazy" />
			</div>

			<div class="post-content">
				<?php the_excerpt(); ?>
				<div class="post-meta">
                    <?php //the_time('Y年n月j日')?>
                    <span class="meta-time meta-ico"><?php the_modified_time('Y-m-d'); ?></span>

                    
                    <span class="meta-author meta-ico"><?php the_author_posts_link(); ?> </span>
                    
                    
                    <span class="meta-view meta-ico"><?php if(function_exists('the_views')) { the_views(); } ?></span>
                    <span class="meta-comment meta-ico"><?php comments_popup_link('0', '1', '%'); ?></span>

                    

                    <?php edit_post_link('Edit', ' &#124; ', ''); ?>
				</div>
			</div>

			
			<!-- <a href="<?php the_permalink() ?>">阅读全文</a> -->
			
			
		</article>
	<?php endwhile; ?>

	<div class="page_navi">
		<?php par_pagenavi(3); ?>
	</div>

<?php else : ?>
	<div class="post">
		<h2><?php _e("Not Found"); ?></h2>
	</div>
    	
<?php endif; ?>