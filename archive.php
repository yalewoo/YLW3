<?php get_header(); ?>
<?php include("header-nav.php"); ?>

<div id="mbxdh">
	<div>
		<?php
			if( is_single() ){
			$categorys = get_the_category();
			$category = $categorys[0];
			echo( get_category_parents($category->term_id,true,' &raquo; ') );
			the_title();
			} 
			else 
			{
				echo "<a href=\"#\">";
				if ( is_page() ){
				the_title();
				} elseif ( is_category() ){
					global $wp_query;
					$cat_obj = $wp_query->get_queried_object();
					$thisCat = $cat_obj->term_id;
					$thisCat = get_category($thisCat);
					$parentCat = get_category($thisCat->parent);
					if ($thisCat->parent != 0){
						$cat_code = get_category_parents($parentCat, TRUE, ' &raquo; ');
						echo $cat_code;
					}
					echo single_cat_title('', false);
				} elseif ( is_tag() ){
				single_tag_title();
				} elseif ( is_day() ){
				the_time('Y年Fj日');
				} elseif ( is_month() ){
				the_time('Y年F');
				} elseif ( is_year() ){
				the_time('Y年');
				} elseif ( is_search() ){
				echo $s.' 的搜索结果';
				}
				echo "</a>";
			}
		?>
	</div>
</div>

<main id="main" role="main">
<div id="container">

	<div class="archive-main">
		<?php if (is_category()) : ?>
		<section id="series-collection">
			<?php ylw_render_series_by_category(); ?>
		</section>
		<?php endif; ?>

		<section id="blog">
			<h2 class="section-title">最新文章</h2>
			<?php include("post-article.php"); ?>
			
		</section>
	</div>

	<?php get_sidebar(); ?>
</div>
</main>
<?php get_footer(); ?>
