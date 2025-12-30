<?php get_header(); ?>
<?php include("header-nav.php"); ?>

<div id="mbxdh">
	<div>
		<a href="#"><?php the_title();?></a>				
	</div>
</div>
<div id="container">
	<div id="blog">

	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
	<section class="whole_article" id="article-<?php the_ID(); ?>">
		<article id="entry">
			<h2 id="article-title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>
			
            <div class="post-meta">
                发布时间：<?php the_modified_time('Y年n月j日')?>

                <?php _e('by'); ?> <?php the_author_posts_link(); ?>
                <?php comments_popup_link('快抢沙发 &#187;', '沙发被抢 &#187;', '% 评论 &#187;'); ?> <?php edit_post_link('Edit', ' &#124; ', ''); ?>
                 <?php if(function_exists('the_views')) { the_views(); } ?>
			</div>

            <div id="article-content">
				<?php the_content(); ?>
			</div>
		</article>
		<div id="otherdata">
			<?php link_pages('<p><strong>Pages:</strong>', '</p>', 'number'); ?>





			<div class="comments-template">
        		<?php comments_template('', true); ?>
    		</div>  

        </div>

        <?php endwhile; ?>

		
			<?php else : ?>
		<section class="whole_article">
    		<h2><?php _e("Not Found"); ?></h2>
		</section>

        <?php endif; ?>

	</section>
	

	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>