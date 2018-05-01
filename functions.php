<?php
define( 'WP_DEBUG', true ); 
define( 'SAVEQUERIES', true );
function replace_jquery() { 
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', false, '1.11.3');
		wp_enqueue_script('jquery'); 
}
add_action('init', 'replace_jquery');


function thim_child_enqueue_styles() {
	if ( is_multisite() ) {
		wp_enqueue_style( 'thim-child-style', get_stylesheet_uri() );
	} else {
		wp_enqueue_style( 'thim-parent-style', get_template_directory_uri() . '/style.css' );
	}
   
}

add_action( 'wp_enqueue_scripts', 'thim_child_enqueue_styles', 1000 );

function include_phbstyles(){ 
    wp_enqueue_style( 'theme-child-style', get_stylesheet_directory_uri() . '/style.css' ); 
    wp_enqueue_style( 'photoshop-book-style', get_stylesheet_directory_uri() . '/photoshop-book-theme.css' );
}

add_action( 'include_phbook_styles', 'include_phbstyles', 1000 );


function index_php_shortcode() {
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

}
add_shortcode( 'index_php_output', 'index_php_shortcode');
// функция дополнительного фильтра в разделе записи
function true_taxonomy_filter() {
	global $typenow; // тип поста
	if( $typenow == 'post' ){ // для каких типов постов отображать
		$taxes = array('platform', 'game'); // таксономии через запятую
		foreach ($taxes as $tax) {
			$current_tax = isset( $_GET[$tax] ) ? $_GET[$tax] : '';
			$tax_obj = get_taxonomy($tax);
			$tax_name = mb_strtolower($tax_obj->labels->name);
			// функция mb_strtolower переводит в нижний регистр
			// она может не работать на некоторых хостингах, если что, убирайте её отсюда
			$terms = get_terms($tax);
			if(count($terms) > 0) {
				echo "<select name='$tax' id='$tax' class='postform'>";
				echo "<option value=''>Все $tax_name</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}


//add_action( 'restrict_manage_posts', 'true_taxonomy_filter' );

// плагин счетчика количества просмотров
add_action( 'after_setup_theme', function() {
    add_theme_support( 'pageviews' );
});

// подключаем ajax обработчик формы
function include_formscript() {
 	wp_enqueue_script( 'formscript', get_stylesheet_directory_uri() . '/js/main.js',  array( 'jquery' ) );
     //wp_localize_script( 'formscript', 'my_ajax_object',
           // array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

/***
    Счетчик просмотров постов без плагинов :)
**/    
//регcитрируем группу полей acf
   
//add_action('acf/init', 'my_acf_add_local_field_groups');


 

// инкремент счетчика для поста
function postViews($postID, $setviews = false) {
    $count_key = 'zviews_count';
    $count = get_post_meta($postID, $count_key); 
    $count = $count[0]; 
    
    if(!isset($count)){  
        $count = 0;  
        add_post_meta($postID, $count_key,  $count); 
    } else {
         if ($setviews){
             $count = intval($count) + 1; 
             update_post_meta($postID, $count_key,  $count);
         } 
    } 
//    var_dump('### post id', $postID);
//    var_dump('###postviews 1', $count );
    return $count; 
}

//wrapper инкрементируем счетчик для поста
//возвращает обновленный postviews
function setPostViews($postID){
    return postViews($postID, true);
}
//возвращает колво просмотров поста
function getPostViews($postID){
     return postViews($postID);
}
 

add_action( 'wp_enqueue_scripts', 'include_formscript' );

function lesson_filter_function(){


    $confirmFiltering = false;
    //выполнить все (AND) условия фильтра

      $confirmFiltering = true;
      $meta_query_args = array( 
      );
      $args = array(
           
      );
    
     $args['category__not_in'] = array( 16 );
     if (isset($_POST['blogpage']) ) {
        if ( $_POST['blogpage'] == true ) {
             $args['cat'] = 16; 
        }
     }
    
   if ( isset($_POST['mainpage']) ) {
    if ( $_POST['mainpage'] == true ) {
        $meta_query_args[] = array(
        'key' => 'type_informacii_posta',
        'value' => array('Текстовый урок', 'Видео урок','Цикл уроков'),
        'compare' => 'IN'
    );
    $args['meta_query'] = $meta_query_args;
       
    }
   }
    //checkbox compare
    if (isset($_POST['lesson_begginer']) || isset($_POST['lesson_middle']) || isset($_POST['lesson_hard']))
    {
               //добавляем аргументы в мета квери
                $meta_query_args['relation'] = 'AND';  

                $tmpArr = array();

                if(isset($_POST['lesson_begginer'])){
                    $tmpArr[] =  'Для начинающих'  ;
                }

                if(isset($_POST['lesson_middle']) ){
                     $tmpArr[] = 'Средний' ;
                }

                if(isset($_POST['lesson_hard']) ){
                    $tmpArr[] =  'Сложный' ;
                }

                $meta_query_args[] =  array (                      
                            'key' => 'slojnost_acf',
                            'value' => $tmpArr,
                            'compare' => 'IN'
                );
        $args['meta_query'] = $meta_query_args;
      }


 	// прменяем фильтр, только если есть по чему фильтровать.
     
	if(isset($_POST['lessons']))
    {
         $confirmFiltering = true;
        // Фильтр по значению поля тип урока
        $meta_query_args['relation'] = 'AND';  

        // Все уроки по умолчанию
        if( $_POST['lessons'] == 'text_lessons' )
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Текстовый урок'
		);

        if( $_POST['lessons'] == 'video_lessons')
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Видео урок'
		);

	   //Цикл уроков
	   if($_POST['lessons'] == 'kurs_lessons' )
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Цикл уроков'
		);
        
        $args['meta_query'] = $meta_query_args;

    }

        //var_dump($args['meta_query']);
        if (!confirmFiltering)
           exit();
 
          //Сортировка по просмотрам и колву комментариев
        if ( isset($_POST['ratio']) ){
                if ($_POST['ratio'] == 'ratio_new'){
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                }
                if ($_POST['ratio'] == 'ratio_mostcomment'){
                        $args['orderby'] = 'comment_count';
                        $args['order'] = 'DESC';
                }
                if ($_POST['ratio'] == 'ratio_mostview'){ 
                        $args['meta_key'] = 'zviews_count';
                        $args['orderby'] =  'meta_value_num';
                        $args['order'] = 'DESC';
                     
              } 
        }
    
        //Создаем запрос  с нашими аргументами
 
         if (isset($_POST['current_category']) && $_POST['current_category'] != 0){
             $args['cat'] = $_POST['current_category']; 
         } 
        
        if (isset($_POST['curr_page']) ){
            $paged = $_POST['curr_page'];
        }
        else {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }
         
//        if( function_exists('register_nav_menus')) {
//          register_nav_menus( array('main_nav' => 'Main Navigation Menu' ) );
//        }
         $args['posts_per_page'] =   12;
         $args['paged'] = $paged;
         $isJson = true;
    
        
        //unset($args['cat']); //@todo: check this
     
       //this must be dynamic $paged
     
         
        $wp_query =  new WP_Query( $args );
        $posts = $wp_query->posts;
       // var_dump('*********************', $args); exit;
        
        $GLOBALS['wp_query']->max_num_pages = ceil($wp_query->found_posts / $args['posts_per_page']);
         
        $navLinks = k_paging_nav(isJson, $paged);
         
        
        foreach ($posts as $index => $post){
            
            //добавляем нужные поля для вывода в js

            $id = $post->ID;
            $post->post_type_video = get_field('photoshop_video', $id);
            $post->post_type_text = get_field('photoshop_tekst', $id);
            $post->postviews =  getPostViews($id);
//            var_dump('** post id', $id);
//            var_dump('***postviews2', $post->postviews );
            $post->comments_count = get_comments_number($id);
            $post->cat = get_the_category($id);
            $first_cat = $post->cat[0];
            $category_slug = $first_cat->slug;
            $postname = $post->post_name;
            $dt  = (explode(" ", $post->post_date));
            $post->formatted_date = $dt[0];
            $post->posthref = "http://".$_SERVER['HTTP_HOST']."/$category_slug/$postname"; 
            //"http://".$_SERVER['HTTP_HOST']."/category/$category_slug/$postname";

            $post->post_thumbnail =  get_the_post_thumbnail_url( $id, 'full');
            $post->type_informacii_posta  = get_post_meta( $id, 'type_informacii_posta' );
            $post->slojnost_acf  = get_post_meta( $id, 'slojnost_acf' );
        }
    
        $posts[]['nav_links'] = $navLinks; 
     
       // var_dump('*********',  $navLinks); exit;
        wp_send_json($posts);


	exit();

}


add_action('wp_ajax_lessonfilter', 'lesson_filter_function');
add_action('wp_ajax_nopriv_lessonfilter', 'lesson_filter_function');

register_sidebar( array(
    'name'         => __( 'Custom Blog Sidebar' ),
    'id'           => 'cpt-sidebar',
    'description'  => __( 'Blog sidebar' ),
    'before_title' => '<h1 class="widget-title">',
    'after_title'  => '</h1>' ,
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>' 
    ) );


 add_action('get_blog_sidebar','change_cpt_sidebar');

function change_cpt_sidebar($sidebar) { 
        unregister_sidebar( 'sidebar' );
        ?>
<!--       <div id="blog-sidebar" class="widget-area col-sm-3 sticky-sidebar" role="complementary" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">-->
            <?php
        dynamic_sidebar( 'cpt-sidebar' );
       // the_widget( 'PPS-blog' );
        ?>
<!--    </div>-->
     <?php
}
 
 

/* Переопределяем функцию thim_paging_nav для использования в фильтре */
function k_paging_nav($json = false, $paged = 1) {
         
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
         
		//$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    
		$pagenum_link = html_entity_decode( get_pagenum_link() );
        
		$query_args = array();
		$url_parts  = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
   
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
//        var_dump('pagenun link', $pagenum_link);
//        exit;
		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
        
        $big = 999999999; // уникальное число для замены
 
	 
		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'total'     => $GLOBALS['wp_query']->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 2,
            'end_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => esc_html__( '<', 'eduma' ),
			'next_text' => esc_html__( '>', 'eduma' ),
			'type'      => 'list'
		) );
         
        if ($json == true){
            
            return  ent2ncr( $links );
        }
		if ( $links ) :
			?>
			<div class="pagination loop-pagination">
				<?php echo ent2ncr( $links ); ?>
			</div>
			<!-- .pagination -->
			<?php
		endif;
	}
/**
 * Enable ACF 5 early access
 * Requires at least version ACF 4.4.12 to work
 */
define('ACF_EARLY_ACCESS', '5');

//add_action( 'after_setup_theme', function() {
//    add_theme_support( 'pageviews' );
//});
