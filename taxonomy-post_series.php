<?php 
/**
 * 系列教程归档页模板
 * Template Name: Series Archive
 */
get_header(); 
include("header-nav.php"); 

$term = get_queried_object();
?>

<div id="mbxdh">
	<div>
		系列教程 &raquo; <?php echo esc_html($term->name); ?>
	</div>
</div>

<main id="main" role="main">
<div id="container">
	<section class="whole_article">
		<article class="series-archive">
			<header class="series-archive-header">
				<h1 class="series-archive-title">
					<span class="series-icon">📚</span>
					<?php echo esc_html($term->name); ?>
				</h1>
				
				<?php if ($term->description) : ?>
					<div class="series-archive-description">
						<?php echo wpautop(wp_kses_post($term->description)); ?>
					</div>
				<?php endif; ?>
				
				<div class="series-archive-meta">
					<span class="meta-ico">共 <?php echo $term->count; ?> 篇文章</span>
				</div>
			</header>
			
			<div class="series-archive-content">
				<?php 
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_series',
							'field' => 'term_id',
							'terms' => $term->term_id,
						),
					),
					'orderby' => 'meta_value_num date',
					'meta_key' => 'series_order',
					'order' => 'ASC',
				);
				
				$series_posts = get_posts($args);
				
				if (!empty($series_posts)) :
					// 构建层级结构
					$hierarchical = ylw_build_hierarchical_posts($series_posts);
				?>
					<ol class="series-archive-list">
						<?php ylw_render_archive_list($hierarchical, 0); ?>
					</ol>
				<?php else : ?>
					<p>该系列暂无文章。</p>
				<?php endif; ?>
			</div>
		</article>
	</section>
</div>
</main>

<?php get_footer(); ?>
