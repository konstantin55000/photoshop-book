<?php

$classes            = array();
 

$theme_options_data = get_theme_mods();
  
$columns  = !empty( $theme_options_data['thim_front_page_cate_columns_grid'] ) ? $theme_options_data['thim_front_page_cate_columns_grid'] : 4;
$show_author        = !empty( $theme_options_data['thim_show_author'] ) && $theme_options_data['thim_show_author'] == '1';
$show_date          = !empty( $theme_options_data['thim_show_date'] ) && $theme_options_data['thim_show_date'] == '1';
$show_comment       = !empty( $theme_options_data['thim_show_comment'] ) && $theme_options_data['thim_show_comment'] == '1';
$classes[]          = 'blog-grid-' . $columns;
switch ($columns) {
    case '2':
        $arr_size = array('570', '300');
        break;
    case '3':
        $arr_size = array('370', '220'); 
    default:
        $arr_size = array('270', '138');
}
 //var_dump('1 columns ', $columns);
//var_dump($arr_size);

//var_dump('*** 2 hm ****', $theme_options_data['thim_front_page_cate_columns_grid']);
?>

 
<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="content-inner">
    
		<?php
        //var_dump('get ph video', get_field('photoshop_video') );
        //Функция вывода иконки урока текст или видео        
        if ( get_field('photoshop_video') ) {         
            echo "<a href='#' title='Уроки в формате видео'><div class='icon-vodoio-inline'><i class='fa fa-video-camera'></i></div></a>";
        }
        if ( get_field('photoshop_tekst') ) {         
            echo "<a href='#' title='Уроки в текстовом формате'><div class='icon-vodoio-inline'><i class='fa fa-file-text-o'></i></div></a>";
        }
        
		do_action( 'thim_entry_top', $arr_size ); ?>
		<div class="entry-content">
			<?php
			if ( has_post_format( 'link' ) && thim_meta( 'thim_url' ) && thim_meta( 'thim_text' ) ) {
				$url  = thim_meta( 'thim_url' );
				$text = thim_meta( 'thim_text' );
				if ( $url && $text ) { ?>
					<header class="entry-header">
					    <div class="entry-contain">
						<h3 class="entry-title">
							<a class="link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $text ); ?></a>
						</h3>
                        </div>
					</header>

					<?php
				}
				?>
				<div class="entry-summary">
					<?php
					the_excerpt();
					?>
				</div><!-- .entry-summary -->
				<div class="readmore">
					<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html__( 'Read More', 'eduma' ); ?></a>
				</div>
			<?php } elseif ( has_post_format( 'quote' ) && thim_meta( 'thim_quote' ) && thim_meta( 'thim_author_url' ) ) {
				$quote      = thim_meta( 'thim_quote' );
				$author     = thim_meta( 'thim_author' );
				$author_url = thim_meta( 'thim_author_url' );
				if ( $author_url ) {
					$author = ' <a href=' . esc_url( $author_url ) . '>' . $author . '</a>';
				}
				if ( $quote && $author ) {
					?>
					<header class="entry-header">
						<div class="box-header box-quote">
							<blockquote><?php echo esc_html( $quote ); ?><cite><?php echo esc_html( $author ); ?></cite>
							</blockquote>
						</div>
					</header>
					<?php
				}
			} elseif ( has_post_format( 'audio' ) ) {
				?>
				<?php
                $show_author = false;
				if ( $show_author ) {
					?>
					<div class="author">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?>
						<?php printf( '<span class="vcard author author_name"><a href="%1$s">%2$s</a></span>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_html( get_the_author() )
						); ?>
					</div>
					<?php
				}
				?>
				<header class="entry-header">
					<div class="entry-contain">
					 
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php thim_entry_meta(); ?>
					</div>
				</header>
				<div class="entry-grid-meta">
					<?php
					if ( $show_date ) {
						?>
						<div class="date">
							<i class="fa fa-calendar"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
						</div>
						<?php
					}
					?>
					<?php
					if ( $show_comment ) {
						$comments = wp_count_comments( get_the_ID() );
						?>
						<div class="comments"><i class="fa fa-comment"></i><?php echo $comments->total_comments; ?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="entry-summary">
					<?php
					the_excerpt();
					?>
				</div><!-- .entry-summary -->
				<div class="readmore">
					<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html__( 'Read More', 'eduma' ); ?></a>
				</div>
				<?php
			} else {
				?>
				<?php
                $show_author = false;
				if ( $show_author ) {
					?>
					<div class="author">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?>
						<?php printf( '<span class="vcard author author_name"><a href="%1$s">%2$s</a></span>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_html( get_the_author() )
						); ?>
					</div>
					<?php
				}
				?>

				<header class="entry-header">
					<div class="entry-contain">
					 
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php thim_entry_meta(); ?>
					</div>
				</header>
				<div class="entry-grid-meta">
					<?php
                    $show_date = true;
                    $show_comment = true;
					if ( $show_date ) {
						?>
						<div class="date"> 
							<i class="fa fa-calendar"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
						</div>
						<?php
					}
					?>
					<span class="comments22"><i class="fa fa-eye"></i><span class="views-placeholder"><?php echo getPostViews(get_the_ID()); ?></span>
                    </span>
					<?php
					if ( $show_comment ) {
						$comments = wp_count_comments( get_the_ID() );
						?>
						<div class="comments"><i class="fa fa-comment"></i><?php echo $comments->total_comments; ?>
						</div>
						<?php
					}
					?>
					
				</div>
				<!-- .entry-header -->
				<div class="entry-summary">
					<?php
					the_excerpt();
					?>
				</div><!-- .entry-summary -->
				<div class="readmore">
					<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html__( 'Read More', 'eduma' ); ?></a>
				</div>
			<?php }; ?>
		</div>
	</div>
</article>

 
<!-- #post-## -->

