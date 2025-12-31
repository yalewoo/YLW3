<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/" role="search">
	<div>
		<input type="text" value="<?php echo esc_attr(get_search_query()); ?>" name="s" id="s" placeholder="搜索..." aria-label="搜索" />
		<input type="submit" id="searchsubmit" value="搜索" />
	</div>
</form>
</form>