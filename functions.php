<?php
//去掉wp_head()的多余代码
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action( 'wp_head', 'wp_resource_hints', 2 );
add_filter( 'emoji_svg_url', '__return_false' );
add_filter('rest_enabled', '__return_false');
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





//网页标题
function ylw_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name');
	$site_description = get_bloginfo( 'description');
	if ( $site_description && ( is_home() ) )
		$title = "$title $sep $site_description";
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( '第 %s 页', max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'ylw_wp_title', 10, 2 );

//添加自定义菜单
if(function_exists('register_nav_menus')){
    register_nav_menus( array(
	'main_menu' => '主体导航栏',
	'top_menu' => '最顶端菜单',
	'footer_menu' => '页脚菜单'
) );
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
	$sum=$_POST['sum'];
	switch($sum){
	case $_POST['num1']+$_POST['num2']:break;
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

// 首页按修改时间排序（替代query_posts）
function ylw_modify_main_query($query) {
    if ($query->is_main_query() && !is_admin() && $query->is_home()) {
        $query->set('orderby', 'modified');
    }
}
add_action('pre_get_posts', 'ylw_modify_main_query');


//修改摘要字数
function new_excerpt_length($length) {
    return 120;
}
add_filter('excerpt_length', 'new_excerpt_length');
      
      
//支持缩略图
add_theme_support( "post-thumbnails" );
function catch_first_image() {
	global $post, $posts;
	$first_img = '';
	
	if ( has_post_thumbnail() ) {
		$post2 = get_the_post_thumbnail( $post->ID );
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post2, $matches);	
	}
	else
	{
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	}
		
	$first_img = $matches [1] [0];
	if(empty($first_img)){
		$first_img = get_template_directory_uri().'/img/default.png';
		return $first_img;
	}
	else
	{
		if (strpos($first_img, "7d9rd6.com1.z0.glb.clouddn.com"))
		{
			return $first_img.'-indexthumb';
		}
		else
		{
			return $first_img;
		}
	}
};
//增强默认编辑器
function Bing_editor_buttons($buttons){
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'backcolor';
	$buttons[] = 'underline';
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'cut';
	$buttons[] = 'copy';
	$buttons[] = 'paste';
	$buttons[] = 'cleanup';
	$buttons[] = 'wp_page';
	$buttons[] = 'newdocument';
	return $buttons;
}
add_filter("mce_buttons_3", "Bing_editor_buttons");


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
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
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
                $output .= '<li><a href="' . get_permalink() . '">' . $post->post_title . '</a></li>';
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


//wordpress禁用图片属性srcset和sizes   
add_filter( 'max_srcset_image_width', function() { return 1; } );  

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
	        <input id="page_input" type="text" max="'.$max_page.'" name="page_num" value="" />
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

//修改头像服务器
// function mytheme_get_avatar( $avatar ) {
// //$avatar = preg_replace( "/http:\/\/(www|\d).gravatar.com/","http://on63vd0xn.bkt.clouddn.com/",$avatar );
// $avatar = preg_replace( "/http:\/\/(www|\d).gravatar.com/","http://gravatar.duoshuo.com",$avatar );

// return $avatar;
// }
// add_filter( 'get_avatar', 'mytheme_get_avatar' );

//评论者的链接新窗口打开
function comment_author_link_window() {
global $comment;
$url    = get_comment_author_url();
$author = get_comment_author();
if (empty( $url ) || 'http://' == $url )
 $return = $author;
 else
 $return = "<a href='$url'  target='_blank'>$author</a>"; 
 return $return;
}
add_filter('get_comment_author_link', 'comment_author_link_window');



/*评论回复邮件通知*/
function comment_mail_notify($comment_id) {
  $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
  if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
    $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
    $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
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
      <p>您还可以<a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" title="单击查看完整的回复内容" target="_blank">&nbsp;查看完整的回复內容</a>，欢迎再度光临<a href="http://www.yalewoo.com">雅乐网</a></p>
    </div>';
         $from = "From: \"" . get_option('blogname') . "评论提醒\" <$wp_email>";
         $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
         wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');
 




function autoblank($text) {
	$return = str_replace('<a', '<a target="_blank"', $text);
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


?>