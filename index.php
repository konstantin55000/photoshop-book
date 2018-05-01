<?php 
$class_archive = '';
$archive_layout = get_theme_mod( 'thim_front_page_cate_display_layout' );
$layout_type   = !empty( $archive_layout ) ?  $archive_layout : 'grid';
//$layout_type = 'grid';  /*check this for list*/




if (!isset($args))
    $args = array();

$args['posts_per_page'] =  12;
$args['post_type'] = 'post';

$link =  $_SERVER['REQUEST_URI'];
$link_array = explode('/',$link);
$page = end($link_array);  
//var_dump('currpage', $page, $link);

if (strpos($_SERVER['REQUEST_URI'], 'blog') !== false){ //if blogpage
    $blogPage = true;
    $args['cat'] = 16; 
} else {
     $args['category__not_in'] = array( 16 );
}

//$args['orderby']='date'; 
//$args['order'] = 'DESC';

if ( $layout_type == 'grid' ) {
	$class_archive = ' blog-switch-layout blog-grid';
	global $post, $wp_query;

	if ( is_category() ) {
		$total = get_queried_object();
		$total = $total->count;
	} elseif ( !empty( $_REQUEST['s'] ) ) {
		$total = $wp_query->found_posts;
	} else {
		$total = wp_count_posts( 'post' );
		$total = $total->publish;
	}

	if ( $total == 0 ) {
		echo '<p class="message message-error">' . esc_html__( 'There are no available posts!', 'eduma' ) . '</p>';
		return;
	} elseif ( $total == 1 ) {
		$index = esc_html__( 'Showing only one result', 'eduma' );
	} else {
		$courses_per_page = absint( get_option( 'posts_per_page' ) );
		$paged            = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		$from = 1 + ( $paged - 1 ) * $courses_per_page;
		$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

		if ( $from == $to ) {
			$index = sprintf(
				esc_html__( 'Showing last post of %s results', 'eduma' ),
				$total
			);
		} else {
			$index = sprintf(
				esc_html__( 'Showing %s-%s of %s results', 'eduma' ),
				$from,
				$to,
				$total
			);
		}
	}
}

//пагинация  постов
//wp_reset_postdata();
 
//выбираем только уроки на главной странице 
if (!$blogPage){
       $meta_query_args[] = array(
        'key' => 'type_informacii_posta',
        'value' => array('Текстовый урок', 'Видео урок','Цикл уроков'),
        'compare' => 'IN'
    );
    $args['meta_query'] = $meta_query_args; 
}

      
$wp_query = new WP_Query( $args );
 
//если есть посты подключаем фильтр

if ($wp_query->have_posts() ) :
    if (!$blogPage) {
       
        $isHomePage = true;
        $showLessons = true; 
        //  подключаем шаблон фильтр
        include( locate_template( 'inc/templates/filter-category.php', false, false ) ); 
    }

 if ($blogPage):
    //Показываем архив враппер для блог пейдж. Для всех остальных он открывается в filter-category.php
//var_dump('*** blogPage', $blogPage);
?>
 <div id="blog-archive" class="grid-<?php echo   $columns; ?> blog-content blog-switch-layout 
    <?php echo esc_attr( $class_archive ); ?>"> 
        <div class="thim-blog-top switch-layout-container <?php if( $show_desc && $cat_desc ) echo 'has_desc';?>"> <!-- testtt -->
            <div class="switch-layout">
                <a href="#" class="list switchToGrid  switch-active"><i class="fa fa-th-large"></i></a>
                <a href="#" class="grid switchToList"><i class="fa fa-list-ul"></i></a>
            </div>
            <div class="post-index"><?php echo esc_html( $index ); ?></div>
     </div>
<?php
endif; 
//подключаем нужный шаблон (грид или контент)
?>
  <div class="row"> 
    <?php 
      
      if ( $layout_type == 'grid' ):  
            /* Start the Loop */
            while ($wp_query->have_posts() ) : the_post(); 
                get_template_part( 'content-grid' );
            endwhile;
            ?>
       </div> 
    <?php else: ?>
        <div class="row">
            <?php
            /* Start the Loop */
            while ( $wp_query->have_posts()  ) : the_post();
                get_template_part( 'content' );
            endwhile;
            ?>
        </div>
    <?php endif; ?>
        
    
</div>
	<?php
	thim_paging_nav();
else :
	get_template_part( 'content', 'none' );
endif;
