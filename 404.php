<?php get_header(); ?>
<?php include("header-nav.php"); ?>

<main id="main" role="main">
<div id="container">
	<section id="blog">
		<div class="error-404">
			<h1>404</h1>
			<h2>页面未找到</h2>
			<p>抱歉，您访问的页面不存在或已被移动。</p>
			<p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 1rem;">
				提示：如果链接中含有日期，请尝试删除日期并在网址最后添加 .html
			</p>
			<a href="<?php echo home_url(); ?>">← 返回首页</a>
		</div>
	</section>
	<?php get_sidebar(); ?>
</div>
</main>

<?php get_footer(); ?>
    		
			

<!-- 
                            
--> 
