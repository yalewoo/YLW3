<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

function bootstrapwp_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
			break;
		case 'trackback' :
	  // 用不同于其它评论的方式显示 trackbacks 。
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'bootstrapwp' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'bootstrapwp' ), '<span class="edit-link">', '</span>' ); ?>
		</p>
	<?php
		break;
		default :
		// 开始正常的评论
		global $post;
	 ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-one">
			<div class="comment-author-avatar">
  			<?php // 显示评论作者头像 
  			  echo get_avatar( $comment, 64 ); 
  			?>
			</div>
			<?php // 未审核的评论显示一行提示文字
			  if ( '0' == $comment->comment_approved ) : ?>
  			<p class="comment-awaiting-moderation">
  			  Your comment is awaiting moderation.
  			</p>
			<?php endif; ?>
			<div class="comment-body">
				<div class="comment-author-name">
  				<?php // 显示评论作者名称
  				    printf( '%1$s %2$s',
  						get_comment_author_link(),
  						// 如果当前文章的作者也是这个评论的作者，那么会出现一个标签提示。
  						( $comment->user_id === $post->post_author ) ? '<span class="comment-author-is-bloger"> ' .'作者' . '</span>' : ''
  					);
  				?>
  				</div>
  				
  				<?php // 显示评论内容
				  comment_text(); 
				?>
				
				<div class="comment-meta">

    				<?php // 显示评论的发布时间
    				    printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
    						esc_url( get_comment_link( $comment->comment_ID ) ),
    						get_comment_time( 'c' ),
    					  // 翻译: 1: 日期, 2: 时间
    						sprintf( __( '%1$s %2$s', 'fenikso' ), get_comment_date(), get_comment_time() )
    					);
    				?>


				
				<?php // 显示评论的编辑链接 
				  edit_comment_link( __( 'Edit', 'bootstrapwp' ), '<span class="edit-link">', '</span>' ); 
				?>
				
				<span class="reply">
					<?php // 显示评论的回复链接 
					  comment_reply_link( array_merge( $args, array( 
					    'reply_text' =>  '回复', 
					    'depth'      =>  $depth, 
					    'max_depth'  =>  $args['max_depth'] ) ) ); 
					?>
				</span>
				</div>
			</div>
		</div>
	<?php
		break;
	endswitch; // end comment_type check
}
?>


<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
		文章《<?php	echo get_the_title();?>》共有<?php echo get_comments_number(); ?>条评论：
		</h3>

		<?php twentyfifteen_comment_nav(); ?>

		<ol class="comment-list">
			<?php wp_list_comments( array(
				'callback'     =>  'bootstrapwp_comment',
			) );  ?>
		</ol><!-- .comment-list -->

		<?php twentyfifteen_comment_nav(); ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfifteen' ); ?></p>
	<?php endif; ?>

	<?php 
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
        'author' => '<div class="comment-name-email-url"><p class="comment-form-author">' . ( $req ? '<span class="required">*</span>' : '' ) .
            '<label for="author">姓名</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />  </p>',
        'email'  => '<p class="comment-form-email">' . ( $req ? '<span class="required">*</span>' : '' ) .
            '<label for="email">邮箱</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /> </p>',
        'url'    => '<p class="comment-form-url">' .
            '<label for="url">网址</label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"' . $aria_req . '  /></p></div>'. spam_protection_math() .'<div class="comment-right"><div class="comment-submit-button">',
    );
	 
	$comments_args = array(
		'fields' =>  $fields,
		'title_reply'=>'我要评论',
		'label_submit' => '发表评论',
		'comment_notes_before' => '',
  		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>'
	);
	 
	comment_form($comments_args);
	//comment_form();
	
	?>

</div><!-- .comments-area -->

<?php wp_enqueue_script( 'comment-reply' );?>
