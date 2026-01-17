<?php
/**
 * YLW3 Theme Functions
 * 
 * @package YLW3
 * @since 3.2
 */

// 安全检查：禁止直接访问此文件
if (!defined('ABSPATH')) {
    exit;
}

// 定义主题常量
define('YLW3_VERSION', '3.2');
define('YLW3_DIR', get_template_directory());
define('YLW3_URI', get_template_directory_uri());

//去掉wp_head()的多余代码
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action( 'wp_head', 'wp_resource_hints', 2 );
// REST API 保持开放（多个插件依赖）
add_filter('rest_jsonp_enabled', '__return_false');

// 禁用 pingback/trackback（解决 cron 慢的问题）
add_action('init', function() {
    remove_action('do_pings', 'do_all_pings');
    wp_clear_scheduled_hook('do_pings');
}, 1);
add_filter('pings_open', '__return_false');

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
//add_filter('wp_list_bookmarks','rbt_friend_links'); // 已注释：函数未定义

// 支持缩略图，使用 WordPress 默认尺寸
add_theme_support( 'post-thumbnails' );
function catch_first_image() {
    global $post;

    // 优先使用特色图的默认缩略尺寸，避免首页加载原图
    if ( $post && has_post_thumbnail( $post->ID ) ) {
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        if ( $thumb && isset( $thumb[0] ) ) {
            return $thumb[0];
        }
    }

    // 回退到正文里的第一张图片，并尝试用附件 ID 下采样为 medium
    if ( isset( $post->post_content ) && preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post->post_content, $matches ) ) {
        $src = $matches[1];
        $attachment_id = attachment_url_to_postid( $src );

        if ( $attachment_id ) {
            $down = image_downsize( $attachment_id, 'medium' );
            if ( $down && isset( $down[0] ) ) {
                return $down[0];
            }
        }

        return $src;
    }

    // 兜底使用主题内的默认占位图
    return get_template_directory_uri() . '/img/default.png';
}
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//去掉Embed 功能
function disable_embeds_init() {
global $wp;
$wp->public_query_vars = array_diff( $wp->public_query_vars, array( 'embed', ) );
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
add_filter( 'embed_oembed_discover', '__return_false' );
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' ); }
add_action( 'init', 'disable_embeds_init', 9999 );
function disable_embeds_tiny_mce_plugin( $plugins ) { return array_diff( $plugins, array( 'wpembed' ) ); }
function disable_embeds_rewrites( $rules ) { foreach ( $rules as $rule => $rewrite ) { if ( false !== strpos( $rewrite, 'embed=true' ) ) { unset( $rules[ $rule ] ); } }
return $rules; }


//禁用wordpress自带emjoy表情
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
}





// 网页标题（WordPress 4.1+ 现代写法）
add_theme_support( 'title-tag' );

// 自定义标题分隔符和格式
function ylw_document_title_parts( $title ) {
    if ( is_home() || is_front_page() ) {
        $title['tagline'] = get_bloginfo( 'description' );
    }
    return $title;
}
add_filter( 'document_title_parts', 'ylw_document_title_parts' );

function ylw_document_title_separator( $sep ) {
    return '|';
}
add_filter( 'document_title_separator', 'ylw_document_title_separator' );

//添加自定义菜单
if(function_exists('register_nav_menus')){
    register_nav_menus( array(
	'main_menu' => '主体导航栏',
	'top_menu' => '最顶端菜单',
	'footer_menu' => '页脚菜单'
) );
}

// ========== 系列教程功能 ==========

/**
 * 注册系列教程自定义分类法
 */
function ylw_register_series_taxonomy() {
    $labels = array(
        'name'              => '系列教程',
        'singular_name'     => '系列',
        'search_items'      => '搜索系列',
        'all_items'         => '所有系列',
        'parent_item'       => '父系列',
        'parent_item_colon' => '父系列：',
        'edit_item'         => '编辑系列',
        'update_item'       => '更新系列',
        'add_new_item'      => '添加新系列',
        'new_item_name'     => '新系列名称',
        'menu_name'         => '系列教程',
    );

    $args = array(
        'hierarchical'      => true, // 支持三级层级
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'series'),
        'show_in_rest'      => true,
        'meta_box_cb'       => false, // 我们会创建自定义 Meta Box
    );

    register_taxonomy('post_series', array('post'), $args);
}
add_action('init', 'ylw_register_series_taxonomy');

/**
 * 系列教程 - 绑定分类（Term Meta）
 */
function ylw_series_category_meta_fields_add($taxonomy) {
    wp_nonce_field('ylw_series_category_meta', 'ylw_series_category_meta_nonce');
    $categories = get_categories(array('hide_empty' => false));
    ?>
    <div class="form-field term-group">
        <label for="ylw_series_cover_image">合集封面图（可选）</label>
        <div style="display:flex; gap:8px; align-items:center;">
            <input type="text" name="ylw_series_cover_image" id="ylw_series_cover_image" value="" placeholder="https://..." style="flex:1;">
            <button type="button" class="button ylw-media-select" data-target="ylw_series_cover_image">从媒体库选择图片</button>
        </div>
        <p class="description">填写图片 URL，用于分类合集展示。</p>
    </div>
    <div class="form-field term-group">
        <label for="ylw_series_categories">所属分类（可多选）</label>
        <div style="max-height: 220px; overflow-y: auto; border: 1px solid #ddd; padding: 8px; background: #fff;">
            <?php foreach ($categories as $cat) : ?>
                <label style="display:block; margin: 4px 0;">
                    <input type="checkbox" name="ylw_series_categories[]" value="<?php echo esc_attr($cat->term_id); ?>">
                    <?php echo esc_html($cat->name); ?>
                </label>
            <?php endforeach; ?>
        </div>
        <p class="description">不选择则该系列不会在分类页合集列表中显示。</p>
    </div>
    <?php
}
add_action('post_series_add_form_fields', 'ylw_series_category_meta_fields_add');

function ylw_series_category_meta_fields_edit($term) {
    wp_nonce_field('ylw_series_category_meta', 'ylw_series_category_meta_nonce');
    $categories = get_categories(array('hide_empty' => false));
    $selected = get_term_meta($term->term_id, 'ylw_series_categories', true);
    $selected = is_array($selected) ? $selected : array();
    $cover_image = get_term_meta($term->term_id, 'ylw_series_cover_image', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="ylw_series_cover_image">合集封面图（可选）</label></th>
        <td>
            <div style="display:flex; gap:8px; align-items:center;">
                <input type="text" name="ylw_series_cover_image" id="ylw_series_cover_image" value="<?php echo esc_attr($cover_image); ?>" placeholder="https://..." style="flex:1;">
                <button type="button" class="button ylw-media-select" data-target="ylw_series_cover_image">从媒体库选择图片</button>
            </div>
            <p class="description">填写图片 URL，用于分类合集展示。</p>
        </td>
    </tr>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="ylw_series_categories">所属分类（可多选）</label></th>
        <td>
            <div style="max-height: 220px; overflow-y: auto; border: 1px solid #ddd; padding: 8px; background: #fff;">
                <?php foreach ($categories as $cat) : ?>
                    <label style="display:block; margin: 4px 0;">
                        <input type="checkbox" name="ylw_series_categories[]" value="<?php echo esc_attr($cat->term_id); ?>" <?php checked(in_array($cat->term_id, $selected, true)); ?>>
                        <?php echo esc_html($cat->name); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="description">不选择则该系列不会在分类页合集列表中显示。</p>
        </td>
    </tr>
    <?php
}
add_action('post_series_edit_form_fields', 'ylw_series_category_meta_fields_edit');

function ylw_save_series_category_meta($term_id) {
    if (!isset($_POST['ylw_series_category_meta_nonce']) || !wp_verify_nonce($_POST['ylw_series_category_meta_nonce'], 'ylw_series_category_meta')) {
        return;
    }
    if (!current_user_can('manage_categories')) {
        return;
    }
    if (isset($_POST['ylw_series_cover_image'])) {
        update_term_meta($term_id, 'ylw_series_cover_image', esc_url_raw($_POST['ylw_series_cover_image']));
    }
    $cat_ids = isset($_POST['ylw_series_categories']) ? array_map('intval', (array) $_POST['ylw_series_categories']) : array();
    update_term_meta($term_id, 'ylw_series_categories', $cat_ids);
}
add_action('created_post_series', 'ylw_save_series_category_meta');
add_action('edited_post_series', 'ylw_save_series_category_meta');

/**
 * 系列封面图 - 媒体库选择
 */
function ylw_series_admin_media_enqueue($hook) {
    if (empty($_GET['taxonomy']) || $_GET['taxonomy'] !== 'post_series') {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_script('jquery-core', "
        jQuery(function($){
            $(document).on('click', '.ylw-media-select', function(e){
                e.preventDefault();
                var targetId = $(this).data('target');
                var input = $('#' + targetId);
                var frame = wp.media({
                    title: '选择封面图',
                    button: { text: '使用此图片' },
                    multiple: false
                });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    if (attachment && attachment.url) {
                        input.val(attachment.url).trigger('change');
                    }
                });
                frame.open();
            });
        });
    ");
}
add_action('admin_enqueue_scripts', 'ylw_series_admin_media_enqueue');

/**
 * 获取指定分类下的系列合集
 */
function ylw_get_series_terms_by_category($category_id = 0, $extra_args = array()) {
    $args = array(
        'taxonomy' => 'post_series',
        'hide_empty' => false,
    );

    if ($category_id) {
        $args['meta_query'] = array(
            array(
                'key' => 'ylw_series_categories',
                'value' => 'i:' . intval($category_id) . ';',
                'compare' => 'LIKE',
            ),
        );
    }

    if (!empty($extra_args) && is_array($extra_args)) {
        $args = array_merge($args, $extra_args);
    }

    return get_terms($args);
}

/**
 * 渲染分类页的系列合集列表
 */
function ylw_render_series_by_category($category_id = 0) {
    if (!$category_id) {
        $category_id = is_category() ? get_queried_object_id() : 0;
    }
    if (!$category_id) {
        return;
    }

    $series_terms = ylw_get_series_terms_by_category($category_id);
    if (empty($series_terms) || is_wp_error($series_terms)) {
        return;
    }
    ?>
    <section class="series-by-category">
        <div class="series-by-category-header">📚 该分类合集</div>
        <ul class="series-by-category-list">
            <?php foreach ($series_terms as $term) : ?>
                <?php $cover_image = get_term_meta($term->term_id, 'ylw_series_cover_image', true); ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="series-by-category-card">
                        <span class="series-card-media">
                            <?php if (!empty($cover_image)) : ?>
                                <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($term->name); ?>">
                            <?php else : ?>
                                <span class="series-card-placeholder">📘</span>
                            <?php endif; ?>
                        </span>
                        <span class="series-card-content">
                            <span class="series-card-title"><?php echo esc_html($term->name); ?></span>
                            <?php if (!empty($term->description)) : ?>
                                <span class="series-card-desc"><?php echo esc_html(wp_trim_words($term->description, 18)); ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
}

/**
 * 添加系列教程管理菜单
 */
function ylw_add_series_admin_menu() {
    // 系列管理页面（包含批量添加和高级排序）
    add_submenu_page(
        'edit.php',
        '系列教程管理',
        '📚 系列管理',
        'manage_categories',
        'ylw-series-manager',
        'ylw_series_manager_page'
    );
}
add_action('admin_menu', 'ylw_add_series_admin_menu');

/**
 * 系列文章排序管理页面
 */
function ylw_series_order_page() {
    // 获取所有系列
    $series_list = get_terms(array(
        'taxonomy' => 'post_series',
        'hide_empty' => false,
    ));
    
    // 获取选中的系列
    $selected_series = isset($_GET['series_id']) ? intval($_GET['series_id']) : '';
    $series_order_nonce = wp_create_nonce('ylw_series_order');
    
    ?>
    <div class="wrap">
        <h1>📚 系列文章排序管理</h1>
        <p>通过拖拽调整文章顺序和层级关系（支持三级结构）</p>
        
        <form method="get" style="margin: 20px 0;">
            <input type="hidden" name="taxonomy" value="post_series">
            <input type="hidden" name="page" value="ylw-series-order">
            
            <label for="series_id"><strong>选择系列：</strong></label>
            <select name="series_id" id="series_id" onchange="this.form.submit()">
                <option value="">-- 请选择系列 --</option>
                <?php foreach ($series_list as $series) : ?>
                    <option value="<?php echo $series->term_id; ?>" <?php selected($selected_series, $series->term_id); ?>>
                        <?php echo esc_html($series->name); ?> (<?php echo $series->count; ?> 篇)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <?php if ($selected_series) : 
            $posts = get_posts(array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_series',
                        'field' => 'term_id',
                        'terms' => $selected_series,
                    ),
                ),
                'orderby' => 'meta_value_num date',
                'meta_key' => 'series_order',
                'order' => 'ASC',
            ));
            
            if (!empty($posts)) :
                $hierarchical = ylw_build_hierarchical_posts($posts);
        ?>
            <div id="series-order-container">
                <div class="series-order-notice">
                    <p><strong>操作说明：</strong></p>
                    <ul>
                        <li>🖱️ 拖拽文章可调整顺序</li>
                        <li>➡️ 向右拖动可设为子章节</li>
                        <li>⬅️ 向左拖动可提升层级</li>
                        <li>💾 调整后点击"保存排序"按钮</li>
                    </ul>
                </div>
                
                <ol class="series-sortable" id="series-sortable-list" data-level="0">
                    <?php ylw_render_sortable_list($hierarchical, 0); ?>
                </ol>
                
                <button type="button" class="button button-primary button-large" id="save-series-order" style="margin-top: 20px;">
                    💾 保存排序
                </button>
                <span id="save-message" style="margin-left: 15px; color: #46b450;"></span>
            </div>
            
            <style>
            .series-order-notice {
                background: #fff;
                border-left: 4px solid #2271b1;
                padding: 15px;
                margin: 20px 0;
            }
            .series-order-notice ul {
                margin: 10px 0 0 20px;
            }
            .series-sortable {
                list-style: none;
                padding: 0;
                margin: 20px 0;
            }
            .series-sortable li {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 12px 15px;
                margin: 8px 0;
                cursor: move;
                position: relative;
                transition: all 0.2s;
            }
            .series-sortable li:hover {
                border-color: #2271b1;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .series-sortable li.ui-sortable-helper {
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                transform: rotate(2deg);
            }
            .series-sortable li.ui-sortable-placeholder {
                background: #f0f6fc;
                border: 2px dashed #2271b1;
                visibility: visible !important;
            }
            .series-sortable .level-0 {
                background: #fff;
            }
            .series-sortable .level-1 {
                margin-left: 40px;
                background: #f9f9f9;
            }
            .series-sortable .level-2 {
                margin-left: 80px;
                background: #f5f5f5;
            }
            .series-item-title {
                font-weight: 600;
                color: #2271b1;
            }
            .series-item-meta {
                font-size: 12px;
                color: #666;
                margin-top: 5px;
            }
            .series-item-actions {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
            }
            .series-item-actions button {
                margin-left: 5px;
                padding: 2px 8px;
                font-size: 11px;
            }
            .level-indicator {
                display: inline-block;
                padding: 2px 8px;
                background: #e0e0e0;
                border-radius: 3px;
                font-size: 11px;
                margin-right: 8px;
            }
            .level-0 .level-indicator {
                background: #2271b1;
                color: #fff;
            }
            .level-1 .level-indicator {
                background: #72aee6;
                color: #fff;
            }
            .level-2 .level-indicator {
                background: #c3e6ff;
                color: #333;
            }
            </style>
            
            <script>
            jQuery(document).ready(function($) {
                // 初始化拖拽排序
                function initSortable() {
                    $('.series-sortable').sortable({
                        connectWith: '.series-sortable',
                        placeholder: 'ui-sortable-placeholder',
                        tolerance: 'pointer',
                        cursor: 'move',
                        opacity: 0.8,
                        handle: '.series-item-title',
                        start: function(e, ui) {
                            ui.placeholder.height(ui.item.height());
                        },
                        update: function(e, ui) {
                            updateLevelClasses();
                        }
                    });
                }
                
                initSortable();
                
                // 更新层级样式
                function updateLevelClasses() {
                    $('.series-sortable').each(function() {
                        var level = $(this).data('level');
                        $(this).children('li').each(function() {
                            $(this).removeClass('level-0 level-1 level-2').addClass('level-' + level);
                            $(this).find('.level-indicator').text('级别 ' + level);
                        });
                    });
                }
                
                // 升级/降级按钮
                $(document).on('click', '.make-child', function() {
                    var $item = $(this).closest('li');
                    var $prev = $item.prev('li');
                    
                    if ($prev.length) {
                        var $children = $prev.find('> ol');
                        if (!$children.length) {
                            $children = $('<ol class="series-sortable" data-level="' + (parseInt($prev.closest('ol').data('level')) + 1) + '"></ol>');
                            $prev.append($children);
                            initSortable();
                        }
                        $children.append($item);
                        updateLevelClasses();
                    }
                });
                
                $(document).on('click', '.make-parent', function() {
                    var $item = $(this).closest('li');
                    var $parentOl = $item.closest('ol');
                    var $parentLi = $parentOl.closest('li');
                    
                    if ($parentLi.length) {
                        $parentLi.after($item);
                        if ($parentOl.children('li').length === 0) {
                            $parentOl.remove();
                        }
                        updateLevelClasses();
                    }
                });
                
                // 保存排序
                $('#save-series-order').click(function() {
                    var button = $(this);
                    button.prop('disabled', true).text('保存中...');
                    
                    var data = collectHierarchy($('#series-sortable-list'));
                    
                    $.post(ajaxurl, {
                        action: 'ylw_save_series_order',
                        series_id: <?php echo $selected_series; ?>,
                        data: JSON.stringify(data),
                        nonce: '<?php echo esc_js($series_order_nonce); ?>'
                    }, function(response) {
                        if (response.success) {
                            $('#save-message').text('✅ 保存成功！').fadeIn().delay(2000).fadeOut();
                        } else {
                            $('#save-message').text('❌ 保存失败：' + response.data).fadeIn();
                        }
                        button.prop('disabled', false).text('💾 保存排序');
                    });
                });
                
                // 收集层级数据
                function collectHierarchy($list, parentId = 0) {
                    var items = [];
                    var order = 1;
                    
                    $list.children('li').each(function() {
                        var $li = $(this);
                        var postId = $li.data('post-id');
                        var $children = $li.find('> ol');
                        
                        items.push({
                            post_id: postId,
                            parent_id: parentId,
                            order: order
                        });
                        
                        if ($children.length) {
                            items = items.concat(collectHierarchy($children, postId));
                        }
                        
                        order++;
                    });
                    
                    return items;
                }
            });
            </script>
        <?php 
            else :
                echo '<p>该系列暂无文章。</p>';
            endif;
        endif;
        ?>
    </div>
    <?php
}

/**
 * 渲染可排序列表
 */
function ylw_render_sortable_list($hierarchical, $level = 0) {
    foreach ($hierarchical as $item) {
        $post = $item['post'];
        $order = get_post_meta($post->ID, 'series_order', true);
        ?>
        <li data-post-id="<?php echo $post->ID; ?>" class="level-<?php echo $level; ?>">
            <span class="level-indicator">级别 <?php echo $level; ?></span>
            <span class="series-item-title"><?php echo esc_html($post->post_title); ?></span>
            <span class="series-item-meta">
                排序: <?php echo $order ? $order : '-'; ?> | 
                ID: <?php echo $post->ID; ?>
            </span>
            <span class="series-item-actions">
                <?php if ($level < 2) : ?>
                    <button type="button" class="button button-small make-child">→ 设为子级</button>
                <?php endif; ?>
                <?php if ($level > 0) : ?>
                    <button type="button" class="button button-small make-parent">← 提升层级</button>
                <?php endif; ?>
            </span>
            
            <?php if (!empty($item['children'])) : ?>
                <ol class="series-sortable" data-level="<?php echo $level + 1; ?>">
                    <?php ylw_render_sortable_list($item['children'], $level + 1); ?>
                </ol>
            <?php endif; ?>
        </li>
        <?php
    }
}

/**
 * AJAX 保存系列排序
 */
function ylw_ajax_save_series_order() {
    check_ajax_referer('ylw_series_order', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('权限不足');
    }

    $series_id = isset($_POST['series_id']) ? absint($_POST['series_id']) : 0;
    $raw_data = isset($_POST['data']) ? wp_unslash($_POST['data']) : '';
    $data = json_decode($raw_data, true);
    
    if (!$series_id || !is_array($data)) {
        wp_send_json_error('数据无效');
    }
    
    foreach ($data as $item) {
        $post_id = intval($item['post_id']);
        $parent_id = intval($item['parent_id']);
        $order = intval($item['order']);
        
        // 更新排序
        update_post_meta($post_id, 'series_order', $order);
        
        // 更新父文章
        if ($parent_id) {
            update_post_meta($post_id, 'series_parent_post', $parent_id);
        } else {
            delete_post_meta($post_id, 'series_parent_post');
        }
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_ylw_save_series_order', 'ylw_ajax_save_series_order');

/**
 * 添加系列教程 Meta Box
 */
function ylw_add_series_meta_box() {
    add_meta_box(
        'ylw_series_meta_box',
        '📚 系列教程',
        'ylw_series_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'ylw_add_series_meta_box');

/**
 * 系列教程 Meta Box 回调函数
 */
function ylw_series_meta_box_callback($post) {
    wp_nonce_field('ylw_series_nonce_action', 'ylw_series_nonce');
    
    $current_series = wp_get_post_terms($post->ID, 'post_series', array('fields' => 'ids'));
    $selected_series = !empty($current_series) ? $current_series[0] : '';
    $series_order = get_post_meta($post->ID, 'series_order', true);
    $parent_post = get_post_meta($post->ID, 'series_parent_post', true);
    $series_posts_nonce = wp_create_nonce('ylw_series_posts');
    
    $all_series = get_terms(array(
        'taxonomy' => 'post_series',
        'hide_empty' => false,
    ));
    ?>
    <div style="margin-bottom: 15px;">
        <label for="ylw_post_series" style="display: block; margin-bottom: 5px; font-weight: 600;">
            所属系列：
        </label>
        <select name="ylw_post_series" id="ylw_post_series" style="width: 100%;" onchange="ylwLoadSeriesPosts(this.value, <?php echo $post->ID; ?>)">
            <option value="">-- 不属于任何系列 --</option>
            <?php foreach ($all_series as $series) : ?>
                <option value="<?php echo esc_attr($series->term_id); ?>" <?php selected($selected_series, $series->term_id); ?>>
                    <?php echo esc_html($series->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div style="margin-bottom: 15px;" id="ylw-parent-post-wrapper">
        <label for="ylw_parent_post" style="display: block; margin-bottom: 5px; font-weight: 600;">
            父章节：
        </label>
        <select name="ylw_parent_post" id="ylw_parent_post" style="width: 100%;">
            <option value="">-- 顶级章节 --</option>
            <?php
            if ($selected_series) :
                $series_posts = get_posts(array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'post__not_in' => array($post->ID),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'post_series',
                            'field' => 'term_id',
                            'terms' => $selected_series,
                        ),
                    ),
                    'orderby' => 'meta_value_num date',
                    'meta_key' => 'series_order',
                    'order' => 'ASC',
                ));
                
                ylw_render_hierarchical_options($series_posts, 0, '', $parent_post);
            endif;
            ?>
        </select>
        <p class="description" style="margin-top: 5px; font-size: 12px; color: #666;">
            💡 选择父章节可创建三级结构（章 → 节 → 小节）
        </p>
    </div>
    
    <div>
        <label for="ylw_series_order" style="display: block; margin-bottom: 5px; font-weight: 600;">
            排序值：
        </label>
        <input type="number" name="ylw_series_order" id="ylw_series_order" 
               value="<?php echo esc_attr($series_order); ?>" 
               min="1" step="1" style="width: 100%;" 
               placeholder="例如：1, 2, 3..."/>
        <p class="description" style="margin-top: 5px; font-size: 12px; color: #666;">
            💡 同级内排序，数字越小越靠前
        </p>
    </div>
    
    <script>
    function ylwLoadSeriesPosts(seriesId, currentPostId) {
        if (!seriesId) {
            jQuery('#ylw_parent_post').html('<option value="">-- 顶级章节 --</option>');
            return;
        }
        
        jQuery.post(ajaxurl, {
            action: 'ylw_get_series_posts',
            series_id: seriesId,
            current_post_id: currentPostId,
            nonce: '<?php echo esc_js($series_posts_nonce); ?>'
        }, function(response) {
            if (response.success) {
                jQuery('#ylw_parent_post').html(response.data.options);
            }
        });
    }
    </script>
    <?php
}

/**
 * 保存系列教程 Meta 数据
 */
function ylw_save_series_meta($post_id) {
    // 验证 nonce
    if (!isset($_POST['ylw_series_nonce']) || !wp_verify_nonce($_POST['ylw_series_nonce'], 'ylw_series_nonce_action')) {
        return;
    }
    
    // 检查自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 检查权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // 保存系列选择
    if (isset($_POST['ylw_post_series'])) {
        $series_id = intval($_POST['ylw_post_series']);
        if ($series_id > 0) {
            wp_set_post_terms($post_id, array($series_id), 'post_series');
        } else {
            wp_set_post_terms($post_id, array(), 'post_series');
        }
    }
    
    // 保存父文章
    if (isset($_POST['ylw_parent_post'])) {
        $parent_id = intval($_POST['ylw_parent_post']);
        if ($parent_id > 0) {
            update_post_meta($post_id, 'series_parent_post', $parent_id);
        } else {
            delete_post_meta($post_id, 'series_parent_post');
        }
    }
    
    // 保存章节顺序
    if (isset($_POST['ylw_series_order'])) {
        $order = intval($_POST['ylw_series_order']);
        if ($order > 0) {
            update_post_meta($post_id, 'series_order', $order);
        } else {
            delete_post_meta($post_id, 'series_order');
        }
    }
}
add_action('save_post', 'ylw_save_series_meta');

/**
 * AJAX 获取系列文章列表
 */
function ylw_ajax_get_series_posts() {
    check_ajax_referer('ylw_series_posts', 'nonce');

    $series_id = isset($_POST['series_id']) ? absint($_POST['series_id']) : 0;
    $current_post_id = isset($_POST['current_post_id']) ? absint($_POST['current_post_id']) : 0;

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('权限不足');
    }
    
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post__not_in' => array($current_post_id),
        'tax_query' => array(
            array(
                'taxonomy' => 'post_series',
                'field' => 'term_id',
                'terms' => $series_id,
            ),
        ),
        'orderby' => 'meta_value_num date',
        'meta_key' => 'series_order',
        'order' => 'ASC',
    ));
    
    $options = '<option value="">-- 顶级章节 --</option>';
    if (!empty($posts)) {
        $options .= ylw_render_hierarchical_options($posts, 0, '', '', true);
    }
    
    wp_send_json_success(array('options' => $options));
}
add_action('wp_ajax_ylw_get_series_posts', 'ylw_ajax_get_series_posts');

/**
 * 递归渲染层级选项
 */
function ylw_render_hierarchical_options($posts, $parent_id = 0, $prefix = '', $selected = '', $return_html = false, $depth = 0) {
    // 防止无限递归，最多3级
    if ($depth >= 3) {
        return $return_html ? '' : null;
    }
    
    $html = '';
    
    foreach ($posts as $post) {
        $post_parent = get_post_meta($post->ID, 'series_parent_post', true);
        $post_parent = $post_parent ? intval($post_parent) : 0;
        
        if ($post_parent == $parent_id) {
            $selected_attr = ($selected == $post->ID) ? 'selected' : '';
            $option = '<option value="' . esc_attr($post->ID) . '" ' . $selected_attr . '>';
            $option .= esc_html($prefix . $post->post_title);
            $option .= '</option>';
            
            if ($return_html) {
                $html .= $option;
            } else {
                echo $option;
            }
            
            // 递归渲染子章节
            if ($return_html) {
                $html .= ylw_render_hierarchical_options($posts, $post->ID, $prefix . '— ', $selected, true, $depth + 1);
            } else {
                ylw_render_hierarchical_options($posts, $post->ID, $prefix . '— ', $selected, false, $depth + 1);
            }
        }
    }
    
    if ($return_html) {
        return $html;
    }
}

/**
 * 获取文章所属系列的所有文章（按层级顺序）
 */
function ylw_get_series_posts($post_id) {
    error_log("开始获取文章 $post_id 的系列信息");
    
    $series = wp_get_post_terms($post_id, 'post_series');
    
    error_log("wp_get_post_terms 返回: " . print_r($series, true));
    
    if (empty($series)) {
        error_log("文章不属于任何系列");
        return array();
    }
    
    $series_term = $series[0];
    error_log("文章属于系列: " . $series_term->name . " (ID: " . $series_term->term_id . ")");
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'post_series',
                'field' => 'term_id',
                'terms' => $series_term->term_id,
            ),
        ),
        'orderby' => 'meta_value_num date',
        'meta_key' => 'series_order',
        'order' => 'ASC',
    );
    
    $all_posts = get_posts($args);
    
    error_log("查询到 " . count($all_posts) . " 篇文章");
    
    // 构建层级结构
    $hierarchical_posts = ylw_build_hierarchical_posts($all_posts);
    
    error_log("构建层级结构完成，层级数: " . count($hierarchical_posts));
    
    return array(
        'series' => $series_term,
        'posts' => $all_posts,
        'hierarchical' => $hierarchical_posts,
    );
}

/**
 * 构建文章层级结构
 */
function ylw_build_hierarchical_posts($posts, $parent_id = 0, &$flat_list = null, $depth = 0) {
    // 防止无限递归，最多3级
    if ($depth >= 3) {
        return array();
    }
    
    if ($flat_list === null) {
        $flat_list = array();
    }
    
    $result = array();
    
    foreach ($posts as $post) {
        $post_parent = get_post_meta($post->ID, 'series_parent_post', true);
        $post_parent = $post_parent ? intval($post_parent) : 0;
        
        if ($post_parent == $parent_id) {
            $flat_list[] = $post;
            
            $item = array(
                'post' => $post,
                'children' => ylw_build_hierarchical_posts($posts, $post->ID, $flat_list, $depth + 1),
            );
            
            $result[] = $item;
        }
    }
    
    return $result;
}

/**
 * 将层级结构扁平化为列表（用于导航）
 */
function ylw_flatten_hierarchical_posts($hierarchical, $level = 0, &$result = null, $depth = 0) {
    // 防止无限递归
    if ($depth >= 10) {
        return $result ? $result : array();
    }
    
    if ($result === null) {
        $result = array();
    }
    
    foreach ($hierarchical as $item) {
        $result[] = array(
            'post' => $item['post'],
            'level' => $level,
        );
        
        if (!empty($item['children'])) {
            ylw_flatten_hierarchical_posts($item['children'], $level + 1, $result, $depth + 1);
        }
    }
    
    return $result;
}

/**
 * 获取系列中的上一篇和下一篇（基于扁平化列表）
 */
function ylw_get_series_adjacent_posts($post_id) {
    $series_data = ylw_get_series_posts($post_id);
    
    if (empty($series_data['posts'])) {
        return array('prev' => null, 'next' => null);
    }
    
    $flat_posts = array();
    
    // 如果有层级结构，扁平化
    if (!empty($series_data['hierarchical'])) {
        $flat_posts = ylw_flatten_hierarchical_posts($series_data['hierarchical']);
    } else {
        // 否则直接使用文章列表
        foreach ($series_data['posts'] as $post) {
            $flat_posts[] = array('post' => $post, 'level' => 0);
        }
    }
    
    $current_index = -1;
    
    foreach ($flat_posts as $index => $item) {
        if ($item['post']->ID == $post_id) {
            $current_index = $index;
            break;
        }
    }
    
    $prev = ($current_index > 0) ? $flat_posts[$current_index - 1]['post'] : null;
    $next = ($current_index < count($flat_posts) - 1) ? $flat_posts[$current_index + 1]['post'] : null;
    
    return array('prev' => $prev, 'next' => $next);
}

/**
 * 侧边栏显示系列导航（简洁版）
 */
function ylw_display_series_navigation_sidebar($post_id) {
    $series_data = ylw_get_series_posts($post_id);
    
    // 如果文章不属于任何系列，不显示
    if (empty($series_data['posts'])) {
        return;
    }
    
    $series = $series_data['series'];
    $hierarchical = $series_data['hierarchical'];
    $all_posts = $series_data['posts'];
    $series_url = get_term_link($series);
    
    ?>
    <nav class="series-sidebar">
        <div class="series-sidebar-title">
            <strong><?php echo '📚 合集：' . esc_html($series->name); ?></strong>
        </div>
        <ol class="series-sidebar-list">
            <?php 
            // 如果有层级结构，显示层级
            if (!empty($hierarchical)) {
                ylw_render_series_sidebar_list($hierarchical, $post_id, 0);
            } else {
                // 否则显示扁平列表
                foreach ($all_posts as $series_post) {
                    $is_current = ($series_post->ID == $post_id);
                    ?>
                    <li class="<?php echo $is_current ? 'current' : ''; ?>">
                        <?php if ($is_current) : ?>
                            <span><?php echo esc_html($series_post->post_title); ?></span>
                        <?php else : ?>
                            <a href="<?php echo esc_url(get_permalink($series_post->ID)); ?>">
                                <?php echo esc_html($series_post->post_title); ?>
                            </a>
                        <?php endif; ?>
                    </li>
                    <?php
                }
            }
            ?>
        </ol>
    </nav>
    <?php
}

/**
 * 递归渲染侧边栏系列文章列表
 */
function ylw_render_series_sidebar_list($hierarchical, $current_post_id, $level = 0, $depth = 0) {
    // 防止无限递归
    if ($depth >= 10) {
        return;
    }
    
    foreach ($hierarchical as $item) {
        $series_post = $item['post'];
        $is_current = ($series_post->ID == $current_post_id);
        
        ?>
        <li class="<?php echo $is_current ? 'current' : ''; ?> level-<?php echo $level; ?>" style="padding-left: <?php echo $level * 15; ?>px;">
            <?php if ($is_current) : ?>
                <span><?php echo esc_html($series_post->post_title); ?></span>
            <?php else : ?>
                <a href="<?php echo esc_url(get_permalink($series_post->ID)); ?>">
                    <?php echo esc_html($series_post->post_title); ?>
                </a>
            <?php endif; ?>
        </li>
        <?php
        
        // 递归渲染子章节
        if (!empty($item['children'])) {
            ylw_render_series_sidebar_list($item['children'], $current_post_id, $level + 1, $depth + 1);
        }
    }
}

/**
 * 递归渲染归档页文章列表（支持层级）
 */
function ylw_render_archive_list($hierarchical, $level = 0, &$counter = null, $depth = 0) {
    // 防止无限递归
    if ($depth >= 10) {
        return;
    }
    
    if ($counter === null) {
        $counter = 1;
    }
    
    foreach ($hierarchical as $item) {
        $series_post = $item['post'];
        $views = function_exists('pvc_get_post_views') ? intval(pvc_get_post_views($series_post->ID)) : 0;
        $indent_style = $level > 0 ? 'margin-left: ' . ($level * 30) . 'px; border-left: 3px solid #667eea;' : '';
        
        ?>
        <li class="series-archive-item level-<?php echo $level; ?>" style="<?php echo $indent_style; ?>">
            <div class="series-item-header">
                <h2 class="series-item-title">
                    <?php if ($level == 0) : ?>
                        <span class="series-item-number"><?php echo $counter; ?>.</span>
                    <?php else : ?>
                        <span class="series-item-bullet">└</span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_permalink($series_post->ID)); ?>"><?php echo esc_html($series_post->post_title); ?></a>
                </h2>
            </div>
            
            <div class="series-item-meta">
                <span class="meta-time meta-ico"><?php echo get_the_date('Y-m-d', $series_post->ID); ?></span>
                <?php if ($views > 0) : ?>
                    <span class="meta-view meta-ico"><?php echo $views; ?> 次浏览</span>
                <?php endif; ?>
                <span class="meta-comment meta-ico">
                    <?php 
                    $comments_count = wp_count_comments($series_post->ID);
                    echo $comments_count->approved . ' 条评论';
                    ?>
                </span>
            </div>
            
            <?php if ($series_post->post_excerpt) : ?>
                <div class="series-item-excerpt">
                    <?php echo esc_html(wp_trim_words($series_post->post_excerpt, 30)); ?>
                </div>
            <?php endif; ?>
        </li>
        <?php
        
        if ($level == 0) {
            $counter++;
        }
        
        // 递归渲染子章节
        if (!empty($item['children'])) {
            ylw_render_archive_list($item['children'], $level + 1, $counter, $depth + 1);
        }
    }
}

/**
 * 系列管理页面内容
 */
function ylw_series_manager_page() {
    // 加载 jQuery UI sortable
    wp_enqueue_script('jquery-ui-sortable');
    $series_order_nonce = wp_create_nonce('ylw_series_order');
    
    // 处理批量添加
    if (isset($_POST['ylw_bulk_add_series']) && check_admin_referer('ylw_bulk_series_action', 'ylw_bulk_series_nonce')) {
        $series_id = intval($_POST['series_id']);
        $post_ids = isset($_POST['post_ids']) ? array_map('intval', $_POST['post_ids']) : array();
        
        foreach ($post_ids as $post_id) {
            wp_set_post_terms($post_id, array($series_id), 'post_series');
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>已成功将 ' . count($post_ids) . ' 篇文章添加到系列。</p></div>';
    }
    
    // 处理排序保存
    if (isset($_POST['ylw_save_series_order']) && check_admin_referer('ylw_series_order_action', 'ylw_series_order_nonce')) {
        $order_data = isset($_POST['series_order']) ? $_POST['series_order'] : array();
        
        foreach ($order_data as $post_id => $order) {
            update_post_meta(intval($post_id), 'series_order', intval($order));
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>排序已保存！</p></div>';
    }
    
    // 获取所有系列
    $all_series = get_terms(array(
        'taxonomy' => 'post_series',
        'hide_empty' => false,
        'orderby' => 'name',
    ));
    
    $selected_series = isset($_GET['series_id']) ? intval($_GET['series_id']) : '';
    
    ?>
    <div class="wrap ylw-series-manager">
        <h1>📚 系列教程管理</h1>
        
        <div class="ylw-series-tabs">
            <a href="#bulk-add" class="nav-tab nav-tab-active">批量添加</a>
            <a href="#sort-posts" class="nav-tab">排序管理</a>
        </div>
        
        <!-- 批量添加标签页 -->
        <div id="bulk-add" class="ylw-tab-content" style="display: block;">
            <div class="ylw-card">
                <h2>批量添加文章到系列</h2>
                <form method="post" action="<?php echo esc_url(admin_url('edit.php?page=ylw-series-manager')); ?>">
                    <?php wp_nonce_field('ylw_bulk_series_action', 'ylw_bulk_series_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="series_id">选择系列：</label></th>
                            <td>
                                <select name="series_id" id="series_id" class="regular-text" required>
                                    <option value="">-- 请选择系列 --</option>
                                    <?php 
                                    ylw_series_options_walker($all_series, 0, ''); 
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <h3>选择要添加的文章：</h3>
                    <div class="ylw-posts-grid">
                        <?php
                        $posts = get_posts(array(
                            'post_type' => 'post',
                            'posts_per_page' => 100,
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ));
                        
                        foreach ($posts as $post) :
                            $current_series = wp_get_post_terms($post->ID, 'post_series');
                            $has_series = !empty($current_series);
                        ?>
                            <label class="ylw-post-item <?php echo $has_series ? 'has-series' : ''; ?>">
                                <input type="checkbox" name="post_ids[]" value="<?php echo $post->ID; ?>">
                                <span class="post-title"><?php echo esc_html($post->post_title); ?></span>
                                <?php if ($has_series) : ?>
                                    <span class="current-series">(已在: <?php echo esc_html($current_series[0]->name); ?>)</span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <p class="submit">
                        <button type="submit" name="ylw_bulk_add_series" class="button button-primary button-large">
                            ✓ 批量添加到系列
                        </button>
                    </p>
                </form>
            </div>
        </div>
        
        <!-- 高级排序管理标签页 -->
        <div id="sort-posts" class="ylw-tab-content" style="display: none;">
            <div class="ylw-card">
                <h2>🎯 高级拖拽排序（支持层级调整）</h2>
                <p style="color: #666; margin-bottom: 20px;">拖拽调整文章顺序和层级关系，支持三级嵌套结构</p>
                
                <form method="get" action="<?php echo esc_url(admin_url('edit.php')); ?>" style="margin-bottom: 20px;">
                    <input type="hidden" name="page" value="ylw-series-manager">
                    <input type="hidden" name="tab" value="sort-posts">
                    <label for="sort_series_id"><strong>选择系列：</strong></label>
                    <select name="series_id" id="sort_series_id" class="regular-text" onchange="this.form.submit()">
                        <option value="">-- 请选择系列 --</option>
                        <?php 
                        foreach ($all_series as $series) : ?>
                            <option value="<?php echo $series->term_id; ?>" <?php selected($selected_series, $series->term_id); ?>>
                                <?php echo esc_html($series->name); ?> (<?php echo $series->count; ?> 篇)
                            </option>
                        <?php endforeach; 
                        ?>
                    </select>
                </form>
                
                <?php if ($selected_series) : 
                    $posts = get_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'post_series',
                                'field' => 'term_id',
                                'terms' => $selected_series,
                            ),
                        ),
                        'orderby' => 'meta_value_num date',
                        'meta_key' => 'series_order',
                        'order' => 'ASC',
                    ));
                    
                    if (!empty($posts)) :
                        $hierarchical = ylw_build_hierarchical_posts($posts);
                ?>
                    <div class="series-order-notice" style="background: #fff; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0;">
                        <p><strong>操作说明：</strong></p>
                        <ul style="margin: 10px 0 0 20px;">
                            <li>🖱️ 拖拽文章标题可调整顺序</li>
                            <li>➡️ 点击"设为子级"可设为上一篇的子章节</li>
                            <li>⬅️ 点击"提升层级"可提升到上一级</li>
                            <li>💾 调整后点击"保存排序"按钮</li>
                        </ul>
                    </div>
                    
                    <ol class="series-sortable-advanced" id="series-sortable-list" data-level="0" style="list-style: none; padding: 0; margin: 20px 0;">
                        <?php ylw_render_sortable_list($hierarchical, 0); ?>
                    </ol>
                    
                    <button type="button" class="button button-primary button-large" id="save-series-order-advanced" style="margin-top: 20px;">
                        💾 保存排序
                    </button>
                    <span id="save-message-advanced" style="margin-left: 15px; color: #46b450;"></span>
                    
                    <style>
                    .series-sortable-advanced {
                        list-style: none;
                        padding: 0;
                        margin: 20px 0;
                    }
                    .series-sortable-advanced li {
                        background: #fff;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        padding: 12px 15px;
                        margin: 8px 0;
                        cursor: move;
                        position: relative;
                        transition: all 0.2s;
                    }
                    .series-sortable-advanced li:hover {
                        border-color: #2271b1;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .series-sortable-advanced li.ui-sortable-helper {
                        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                        transform: rotate(2deg);
                    }
                    .series-sortable-advanced li.ui-sortable-placeholder {
                        background: #f0f6fc;
                        border: 2px dashed #2271b1;
                        visibility: visible !important;
                    }
                    .series-sortable-advanced .level-0 {
                        background: #fff;
                    }
                    .series-sortable-advanced .level-1 {
                        margin-left: 40px;
                        background: #f9f9f9;
                    }
                    .series-sortable-advanced .level-2 {
                        margin-left: 80px;
                        background: #f5f5f5;
                    }
                    .series-item-title {
                        font-weight: 600;
                        color: #2271b1;
                        cursor: move;
                    }
                    .series-item-meta {
                        font-size: 12px;
                        color: #666;
                        margin-top: 5px;
                        display: block;
                    }
                    .series-item-actions {
                        position: absolute;
                        right: 15px;
                        top: 50%;
                        transform: translateY(-50%);
                    }
                    .series-item-actions button {
                        margin-left: 5px;
                        padding: 2px 8px;
                        font-size: 11px;
                    }
                    .level-indicator {
                        display: inline-block;
                        padding: 2px 8px;
                        background: #e0e0e0;
                        border-radius: 3px;
                        font-size: 11px;
                        margin-right: 8px;
                    }
                    .level-0 .level-indicator {
                        background: #2271b1;
                        color: #fff;
                    }
                    .level-1 .level-indicator {
                        background: #72aee6;
                        color: #fff;
                    }
                    .level-2 .level-indicator {
                        background: #c3e6ff;
                        color: #333;
                    }
                    </style>
                    
                    <script>
                    jQuery(document).ready(function($) {
                        // 初始化拖拽排序
                        function initSortable() {
                            $('.series-sortable-advanced').sortable({
                                connectWith: '.series-sortable-advanced',
                                placeholder: 'ui-sortable-placeholder',
                                tolerance: 'pointer',
                                cursor: 'move',
                                opacity: 0.8,
                                handle: '.series-item-title',
                                start: function(e, ui) {
                                    ui.placeholder.height(ui.item.height());
                                },
                                update: function(e, ui) {
                                    updateLevelClasses();
                                }
                            });
                        }
                        
                        initSortable();
                        
                        // 更新层级样式
                        function updateLevelClasses() {
                            $('.series-sortable-advanced').each(function() {
                                var level = $(this).data('level');
                                $(this).children('li').each(function() {
                                    $(this).removeClass('level-0 level-1 level-2').addClass('level-' + level);
                                    $(this).find('.level-indicator').text('级别 ' + level);
                                });
                            });
                        }
                        
                        // 设为子级按钮
                        $(document).on('click', '.make-child', function() {
                            var $item = $(this).closest('li');
                            var $prev = $item.prev('li');
                            
                            if ($prev.length) {
                                var $children = $prev.find('> ol');
                                if (!$children.length) {
                                    $children = $('<ol class="series-sortable-advanced" data-level="' + (parseInt($prev.closest('ol').data('level')) + 1) + '"></ol>');
                                    $prev.append($children);
                                    initSortable();
                                }
                                $children.append($item);
                                updateLevelClasses();
                            }
                        });
                        
                        // 提升层级按钮
                        $(document).on('click', '.make-parent', function() {
                            var $item = $(this).closest('li');
                            var $parentOl = $item.closest('ol');
                            var $parentLi = $parentOl.closest('li');
                            
                            if ($parentLi.length) {
                                $parentLi.after($item);
                                if ($parentOl.children('li').length === 0) {
                                    $parentOl.remove();
                                }
                                updateLevelClasses();
                            }
                        });
                        
                        // 保存排序
                        $('#save-series-order-advanced').click(function() {
                            var button = $(this);
                            button.prop('disabled', true).text('保存中...');
                            
                            var data = collectHierarchy($('#series-sortable-list'));
                            
                            $.post(ajaxurl, {
                                action: 'ylw_save_series_order',
                                series_id: <?php echo $selected_series; ?>,
                                data: JSON.stringify(data),
                                nonce: '<?php echo esc_js($series_order_nonce); ?>'
                            }, function(response) {
                                if (response.success) {
                                    $('#save-message-advanced').text('✅ 保存成功！').fadeIn().delay(2000).fadeOut();
                                } else {
                                    $('#save-message-advanced').text('❌ 保存失败：' + response.data).fadeIn();
                                }
                                button.prop('disabled', false).text('💾 保存排序');
                            });
                        });
                        
                        // 收集层级数据
                        function collectHierarchy($list, parentId = 0) {
                            var items = [];
                            var order = 1;
                            
                            $list.children('li').each(function() {
                                var $li = $(this);
                                var postId = $li.data('post-id');
                                var $children = $li.find('> ol');
                                
                                items.push({
                                    post_id: postId,
                                    parent_id: parentId,
                                    order: order
                                });
                                
                                if ($children.length) {
                                    items = items.concat(collectHierarchy($children, postId));
                                }
                                
                                order++;
                            });
                            
                            return items;
                        }
                    });
                    </script>
                <?php 
                    else :
                        echo '<p>该系列暂无文章。</p>';
                    endif;
                else :
                    echo '<p class="description">请先选择一个系列。</p>';
                endif; 
                ?>
            </div>
        </div>
    </div>
    
    <style>
        .ylw-series-manager { max-width: 1200px; }
        .ylw-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            padding: 20px;
            margin-top: 20px;
        }
        .ylw-series-tabs {
            border-bottom: 1px solid #ccd0d4;
            margin: 20px 0 0;
            padding: 0;
        }
        .ylw-series-tabs .nav-tab {
            margin-bottom: -1px;
        }
        .ylw-posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 10px;
            max-height: 500px;
            overflow-y: auto;
            padding: 15px;
            border: 1px solid #ddd;
            background: #fafafa;
            border-radius: 4px;
        }
        .ylw-post-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .ylw-post-item:hover {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }
        .ylw-post-item.has-series {
            background: #f0f6fc;
            border-color: #c3dcf5;
        }
        .ylw-post-item input[type="checkbox"] {
            margin: 0;
        }
        .ylw-post-item .post-title {
            flex: 1;
            font-weight: 500;
        }
        .ylw-post-item .current-series {
            font-size: 11px;
            color: #2271b1;
        }
        .ylw-sortable-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .ylw-sortable-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            margin-bottom: 8px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: move;
            transition: all 0.2s;
        }
        .ylw-sortable-item:hover {
            background: #f0f6fc;
            border-color: #2271b1;
        }
        .ylw-sortable-item .dashicons {
            color: #999;
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
        .ylw-sortable-item .item-number {
            font-weight: 700;
            color: #2271b1;
            min-width: 30px;
            font-size: 16px;
        }
        .ylw-sortable-item .item-title {
            flex: 1;
            font-weight: 500;
        }
        .ylw-sortable-item .item-meta {
            color: #666;
            font-size: 12px;
        }
        .ylw-sortable-item.ui-sortable-helper {
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transform: scale(1.02);
        }
        .ylw-sortable-item.ui-sortable-placeholder {
            background: #e5f5ff;
            border: 2px dashed #2271b1;
            visibility: visible !important;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // 根据 URL 参数自动切换标签页
        var urlParams = new URLSearchParams(window.location.search);
        var activeTab = urlParams.get('tab');
        
        if (activeTab) {
            $('.nav-tab').removeClass('nav-tab-active');
            $('.ylw-tab-content').hide();
            
            $('a[href="#' + activeTab + '"]').addClass('nav-tab-active');
            $('#' + activeTab).show();
        }
        
        // 标签页切换
        $('.ylw-series-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.ylw-tab-content').hide();
            $(target).show();
        });
        
        // 拖拽排序
        if ($('#series-sortable').length) {
            $('#series-sortable').sortable({
                placeholder: 'ui-sortable-placeholder',
                helper: 'clone',
                update: function(event, ui) {
                    // 更新序号和隐藏字段
                    $('#series-sortable .ylw-sortable-item').each(function(index) {
                        var newOrder = index + 1;
                        $(this).find('.item-number').text(newOrder + '.');
                        $(this).find('.order-input').val(newOrder);
                    });
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * 递归输出系列下拉选项（支持层级）
 */
function ylw_series_options_walker($terms, $parent_id = 0, $prefix = '', $selected = '') {
    if (empty($terms)) {
        return;
    }
    
    foreach ($terms as $term) {
        // 检查是否有 parent 属性（系列层级支持）
        $term_parent = isset($term->parent) ? $term->parent : 0;
        
        if ($term_parent == $parent_id) {
            $selected_attr = ($selected == $term->term_id) ? 'selected' : '';
            echo '<option value="' . esc_attr($term->term_id) . '" ' . $selected_attr . '>';
            echo esc_html($prefix . $term->name) . ' (' . $term->count . ' 篇)';
            echo '</option>';
            
            // 递归输出子系列
            ylw_series_options_walker($terms, $term->term_id, $prefix . '— ', $selected);
        }
    }
}

//添加侧边栏
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'id'            => 'sidebar-1',
        'name'          => '边栏1',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));


//添加评论表情
function add_my_tips() {

		include(get_template_directory() . '/smiley.php');

}
add_filter('comment_form_before_fields', 'add_my_tips');
add_filter('comment_form_logged_in_after', 'add_my_tips');
//评论表情路径 
add_filter('smilies_src','custom_smilies_src',1,10); 
function custom_smilies_src ($img_src, $img, $siteurl){ 
return $img; 
} 
//修复smilies图片表情
include("ylw_smiley.php");
smilies_reset();

//评论添加验证码
function spam_protection_math(){
	$num1=rand(0,9);
	$num2=rand(0,9);
	return "<div class='comment_yzm'>验证码*： $num1 + $num2 = <input type='text' name='sum' class='math_textfield'  required='required' value='' size='25' tabindex='4'>"
	."<input type='hidden' name='num1' value='$num1'>"
	."<input type='hidden' name='num2' value='$num2'></div>";
}
/* 邮箱接收回复提醒 */
function add_checkbox() {
  echo '</div><div class="ylw_comment_notifyme"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="margin-left:20px;" /><label for="comment_mail_notify">有人回复时邮件通知我</label></div></div></div><div class="clear"></div>';
}
add_action('comment_form', 'add_checkbox', 20, 2);


function spam_protection_pre($commentdata){
	// 只对普通评论进行验证码检查，排除 trackback/pingback
	if(isset($commentdata['comment_type']) && $commentdata['comment_type'] != ''){
		return $commentdata;
	}
    $sum = isset($_POST['sum']) ? intval($_POST['sum']) : null;
    $num1 = isset($_POST['num1']) ? intval($_POST['num1']) : null;
    $num2 = isset($_POST['num2']) ? intval($_POST['num2']) : null;
	switch($sum){
    case $num1 + $num2:break;
	case null:wp_die('对不起: 请输入验证码.');break;
	default:wp_die('对不起: 验证码错误,请重试.');
	}
	return $commentdata;
}
add_filter('preprocess_comment','spam_protection_pre');



 
//边栏彩色标签
function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i','colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	$color = dechex(rand(0,16777215));
	$pattern = '/style=(\'|\”)(.*)(\'|\”)/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

//修改摘要字数
function new_excerpt_length($length) {
    return 120;
}
add_filter('excerpt_length', 'new_excerpt_length');
      
      
//修改摘要样式
function new_excerpt_more( $more ) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');




//添加自定义用户信息字段
add_filter('user_contactmethods','my_user_contactmethods');
function my_user_contactmethods($user_contactmethods ){
 $user_contactmethods ['weibo'] = '新浪微博';
 $user_contactmethods ['touxiang'] = '头像url';
 $user_contactmethods ['job'] = '职业';
 $user_contactmethods ['addres'] = '所在地';

 return $user_contactmethods ;
}


//点赞功能
add_action('wp_ajax_nopriv_specs_zan', 'specs_zan');
add_action('wp_ajax_specs_zan', 'specs_zan');
function specs_zan(){
    global $wpdb,$post;
    check_ajax_referer('specs_zan', 'nonce');
    $id = isset($_POST["um_id"]) ? absint($_POST["um_id"]) : 0;
    $action = isset($_POST["um_action"]) ? sanitize_text_field($_POST["um_action"]) : '';
    if ( $id && $action === 'ding'){
        $specs_raters = get_post_meta($id,'specs_zan',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('specs_zan_'.$id,$id,$expire,'/',$domain,false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'specs_zan', 1);
        } 
        else {
            update_post_meta($id, 'specs_zan', ($specs_raters + 1));
        }
        echo get_post_meta($id,'specs_zan',true);
    } 
    die;
}




/**
 * Random_Posts widget class
 *
 * Author: haoxian_zeng <http://cnzhx.net/>
 * Date: 2013.05.14, cnzhx2011 1.0
 */
//--------------- * 注册该微件
class WP_Widget_myRandom_Posts extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_my_random_posts', 'description' => __( '随机文章小工具' ) );
        parent::__construct('random-posts', __('随机文章'), $widget_ops);
        $this->alt_option_name = 'widget_my_random_posts';
    }

    function widget( $args, $instance ) {
        global $randomposts, $post;

        extract($args, EXTR_SKIP);
        $output = '';
        // 设置 widget 标题
        $title = apply_filters('widget_title', empty($instance['title']) ? __('随机文章') : $instance['title']);

        // 设置要获取的文章数目
        if ( ! $number = absint( $instance['number'] ) )
            $number = 5;

        // WP 数据库查询，使用 rand 参数来获取随机的排序，并取用前面的 $number 个文章
        $randomposts = get_posts( array( 'numberposts' => $number, 'orderby' => 'rand', 'post_status' => 'publish' ) );

        // 下面开始准备输出数据
        // 先输出一般的 widget 前缀
        $output .= $before_widget;
        // 输出标题
        if ( $title )
        $output .= $before_title . $title . $after_title;

        // random posts 列表开始
        $output .= '<ul id="randomposts">';
        if ( $randomposts ) {
            foreach ( (array) $randomposts as $post) {
                $output .= '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html($post->post_title) . '</a></li>';
            }
        }
        $output .= '</ul>';
        // 输出一般的 widget 后缀
        $output .= $after_widget;

        // 输出到页面
        echo $output;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint( $new_instance['number'] );

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_my_random_posts']) )
            delete_option('widget_my_random_posts');

        return $instance;
    }

    //
    // 在 WP 后台的 widget 内部显示两个参数, 1. 标题；2. 显示文章数目
    //
    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="cnzhx" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        <?php
    }
}

	// register WP_Widget_myRandom_Posts widget
	add_action( 'widgets_init', function() { return register_widget('WP_Widget_myRandom_Posts'); } );

//分页  
function par_pagenavi($range = 9){   
if ( is_singular() ) return;  
global $wp_query, $paged;  
$max_page = $wp_query->max_num_pages;  
if ( $max_page == 1 ) return;  
if ( empty( $paged ) ) $paged = 1;  
echo '<span>第' . $paged . '页（共' . $max_page . '页）</span> ';  
    global $paged, $wp_query;    
    if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}    
    if($max_page > 1){if(!$paged){$paged = 1;}    
    if($paged > 3){echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='跳转到首页'> 首页 </a>";}    
    echo "... " ;
    if($max_page > $range){    
        if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";    
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}    
    elseif($paged >= ($max_page - ceil(($range/2)))){    
        for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";    
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}    
    elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){    
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}    
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";    
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}    
    echo "... " ;
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend' title='跳转到最后一页'> 尾页</a>";}}   
    if($max_page>1){
	  echo '<span></span>
	        <label for="page_input" class="screen-reader-text">页码</label>
	        <input id="page_input" type="text" max="'.$max_page.'" name="page_num" value="" aria-label="页码" placeholder="输入页码" />
	        <a href="#" class="go_btn">跳转</a> ';
	  }

}  


/*
 * 评论列表的显示
 */
function twentyfifteen_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfifteen' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'twentyfifteen' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'twentyfifteen' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}

//评论者的链接新窗口打开
function comment_author_link_window() {
global $comment;
$url    = get_comment_author_url();
$author = get_comment_author();
if (empty( $url ) || 'http://' == $url )
 $return = esc_html($author);
 else
 $return = "<a href='" . esc_url($url) . "' target='_blank' rel='noopener noreferrer'>" . esc_html($author) . "</a>"; 
 return $return;
}
add_filter('get_comment_author_link', 'comment_author_link_window');



/*评论回复邮件通知*/
function comment_mail_notify($comment_id) {
  $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
    $comment_id = absint($comment_id);
    $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
    if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
        $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_mail_notify = %d WHERE comment_ID = %d", 1, $comment_id));
  $notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
  $spam_confirmed = $comment->comment_approved;
  if ($parent_id != '' && $spam_confirmed == '1' && $notify == '1') {
    //$wp_email = 'no-reply@yalewoo.com';
    $wp_email = 'yalewoo@163.com';
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的评论有了新回复';
    $message = '
    <div>
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》中评论：</p><p style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:15px; border-radius:5px;">'
       . trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您回复了：</p><p style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:15px; border-radius:5px;">'
       . trim($comment->comment_content) . '<br /></p>
            <p>您还可以<a href="' . esc_url(get_comment_link($parent_id)) . '" title="单击查看完整的回复内容" target="_blank" rel="noopener noreferrer">&nbsp;查看完整的回复內容</a>，欢迎再度光临<a href="https://www.yalewoo.com" target="_blank" rel="noopener noreferrer">雅乐网</a></p>
    </div>';
         $from = "From: \"" . get_option('blogname') . "评论提醒\" <$wp_email>";
         $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
         wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');
 




function autoblank($text) {
    $return = str_replace('<a', '<a target="_blank" rel="noopener noreferrer"', $text);
	return $return;
}
add_filter('the_content', 'autoblank');


// ========== MathJax 数学公式支持 ==========

// 添加文章编辑页面的 Meta Box
function ylw_mathjax_meta_box() {
    add_meta_box(
        'ylw_mathjax_meta_box',
        '数学公式',
        'ylw_mathjax_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'ylw_mathjax_meta_box');

// Meta Box 的内容（复选框）
function ylw_mathjax_meta_box_callback($post) {
    wp_nonce_field('ylw_mathjax_nonce_action', 'ylw_mathjax_nonce');
    $value = get_post_meta($post->ID, 'enableMathJax', true);
    ?>
    <label>
        <input type="checkbox" name="enableMathJax" value="1" <?php checked($value, '1'); ?> />
        启用数学公式（MathJax）
    </label>
    <p class="description">勾选后将加载 MathJax 渲染 LaTeX 公式</p>
    <?php
}

// 保存 Meta 数据
function ylw_save_mathjax_meta($post_id) {
    if (!isset($_POST['ylw_mathjax_nonce']) || !wp_verify_nonce($_POST['ylw_mathjax_nonce'], 'ylw_mathjax_nonce_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['enableMathJax'])) {
        update_post_meta($post_id, 'enableMathJax', '1');
    } else {
        delete_post_meta($post_id, 'enableMathJax');
    }
}
add_action('save_post', 'ylw_save_mathjax_meta');

// 前端加载 KaTeX
function ylw_load_katex() {
    if (!is_singular('post')) {
        return;
    }
    
    $enable = get_post_meta(get_the_ID(), 'enableMathJax', true);
    
    if ($enable === '1') {
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">';
        echo '<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>';
        echo '<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" onload="renderMathInElement(document.body,{delimiters:[{left:\'$$\',right:\'$$\',display:true},{left:\'\\\\(\',right:\'\\\\)\',display:false},{left:\'\\\\[\',right:\'\\\\]\',display:true}]});"></script>';
    }
}
add_action('wp_footer', 'ylw_load_katex');

// 正确加载主题样式和脚本
function ylw_enqueue_scripts() {
    // 加载主题样式
    wp_enqueue_style('ylw-style', get_stylesheet_uri(), array(), '3.0');
    
    // 根据不同页面条件加载 JS 文件
    // 归档页、首页、作者页加载跳转页码脚本
    if (is_archive() || is_home() || is_author()) {
        wp_enqueue_script('ylw-tiaozhuanyema', get_template_directory_uri() . '/js/tiaozhuanyema.js', array(), '1.0', true);
    }
    
    // 单篇文章页加载点赞和目录脚本
    if (is_single()) {
        wp_enqueue_script('ylw-dianzan', get_template_directory_uri() . '/js/dianzan.js', array(), '1.0', true);
        wp_localize_script('ylw-dianzan', 'YLW3_DIANZAN', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('specs_zan'),
        ));
        wp_enqueue_script('ylw-toc', get_template_directory_uri() . '/js/toc.js', array(), '1.0', true);
    }
    
    // 评论表单页面加载表情显示脚本
    if (is_singular() && comments_open()) {
        wp_enqueue_script('ylw-show-smilies', get_template_directory_uri() . '/js/show_smilies.js', array(), '1.0', true);
    }

    // 线程评论回复脚本
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'ylw_enqueue_scripts');


?>