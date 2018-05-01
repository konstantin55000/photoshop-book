<?php 

$class_archive = '';
$archive_layout = get_theme_mod( 'thim_archive_cate_display_layout' );
$layout_type   = !empty( $archive_layout ) ? 'grid' : '';
$layout_type = 'grid';
$show_description = get_theme_mod( 'thim_archive_cate_show_description' );
$show_desc   = !empty( $show_description ) ? $show_description : '';
$cat_desc = category_description();
$theme_options_data = get_theme_mods();
$blogPage = false; 
if (strpos($_SERVER['REQUEST_URI'], 'blog') !== false){ //if blogpage 
        $blogPage = true; 
} 
   
$columns  = !empty( $theme_options_data['thim_front_page_cate_columns_grid'] ) ? $theme_options_data['thim_front_page_cate_columns_grid'] : 4;
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

if ( have_posts() ) :   
    if (!empty($showLessons)) : ?>  
	   <ul id="maintabs" class="nav nav-tabs" role="tablist">
		   <li role="presentation" class="active">
			   <a href="#alllesons" aria-controls="alllesons" role="tab" data-filter="all_lessons" data-toggle="tab" aria-expanded="true">Все</a>
		   </li>
		   <li role="presentation" class="">
			   <a href="#textlesons" data-filter="text_lessons" aria-controls="textlesons" role="tab" data-toggle="tab" aria-expanded="false">Текстовые уроки</a>
		   </li>
		   <li role="presentation" class="">
			   <a href="#videolesons" aria-controls="videolesons" role="tab" data-toggle="tab" data-filter="video_lessons" aria-expanded="false">Видео уроки</a>
		   </li>
		   <li role="presentation" class="">
			   <a href="#kurslesons" aria-controls="kurslesons" data-filter="kurs_lessons" role="tab" data-toggle="tab" aria-expanded="false">Циклы уроков (Курсы)</a>
		   </li>
		  </ul> 
   <?php endif; ?>
 <div id="blog-archive" class="grid-<?php echo   $columns; ?> blog-content blog-switch-layout 
    <?php echo esc_attr( $class_archive ); ?>"> 
        <div class="thim-blog-top switch-layout-container <?php if( $show_desc && $cat_desc ) echo 'has_desc';?>"> <!-- testtt -->
            <div class="switch-layout">
                <a href="#" class="list switchToGrid  switch-active"><i class="fa fa-th-large"></i></a>
                <a href="#" class="grid switchToList"><i class="fa fa-list-ul"></i></a>
            </div>
            <div class="post-index"><?php echo esc_html( $index ); ?></div>
            <div class="lessonfilter">
 <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="lessonfilter">
<div>
     <?php if (!$blogPage) : ?> 
        <label>
        <!-- <input type="checkbox" name="lesson_difficulty"  selected value="any"  /> Все </label> -->
        <label>
        <label><input type="radio" name="ratio" value="ratio_new"  /> Новые </label>
        <label><input type="radio" name="ratio" value="ratio_mostview" /> Популярные </label>
        <label><input type="radio" name="ratio" value="ratio_mostcomment" /> Обсуждаемые </label>
  <?php endif; ?>
    <label> 
    <?php if (!empty($showLessons)) : ?>  
        <input type="checkbox" name="lesson_begginer" value="lesson_begginer"  /> Для Начинающих</label>
        <label>
        <input type="checkbox" name="lesson_middle" value="lesson_middle"  /> Средний </label>
        <label><input type="checkbox" name="lesson_hard" value="lesson_hard" /> Сложный </label>
    
    <?php endif; ?>
    <input type="hidden" id="curr_lesson" name="lessons" value="all_lessons">
    <input type="hidden" name="action" value="lessonfilter">
    <input type="hidden" id="curr_page" name="curr_page" value="1">
    <input type="hidden" name="current_category" value="<?php echo $wp_query->get_queried_object_id() ?>">
    <input type="hidden" id="mainpage" name="mainpage" value="<?php echo $isHomePage; ?>"> 
    <input type="hidden" id="blogpage" name="blogpage" value="<?php echo $blogPage; ?>"> 
              
</div>
<button id="lesson_submit" type="submit" style="opacity: 0; background: #eee;">Фильтр</button>
</form>
</div>
            
</div> 
 
<?php
 
else :
	get_template_part( 'content', 'none' );
endif;
   