<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" role="search">
	<div>
		<label for="s" class="screen-reader-text">搜索</label>
		<input type="text" value="<?php echo esc_attr(get_search_query()); ?>" name="s" id="s" placeholder="搜索..." aria-label="搜索" />
		<input type="submit" id="searchsubmit" value="搜索" />
	</div>
</form>