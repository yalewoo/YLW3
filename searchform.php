<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">



<div>



	<input type="text" value="<?php echo esc_attr(get_search_query()); ?>" name="s" id="s" size="15" />



	<input type="submit" id="searchsubmit" value="Search" />



</div>



</form>