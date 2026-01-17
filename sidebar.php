<aside id="sidebar" role="complementary">
    <ul>
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        <li id="search">
            <?php get_search_form(); ?>
        </li>
        
        <li id="calendar">
            <h2><?php esc_html_e('Calendar', 'ylw3'); ?></h2>
            <?php get_calendar(); ?>
        </li>
        
        <?php wp_list_pages(array('depth' => 3, 'title_li' => '<h2>' . esc_html__('Pages', 'ylw3') . '</h2>')); ?>
        
        <li>
            <h2><?php esc_html_e('Categories', 'ylw3'); ?></h2>
            <ul>
                <?php wp_list_categories(array('orderby' => 'name', 'show_count' => true, 'hierarchical' => false, 'title_li' => '')); ?>
            </ul>
        </li>
        
        <li>
            <h2><?php esc_html_e('Archives', 'ylw3'); ?></h2>
            <ul>
                <?php wp_get_archives(array('type' => 'monthly')); ?>
            </ul>
        </li>
        
        <li>
            <h2><?php esc_html_e('Meta', 'ylw3'); ?></h2>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </li>
    <?php endif; ?>
    </ul>
</aside>