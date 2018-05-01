<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package thim
 */
if ( !is_active_sidebar( 'sidebar' ) ) {
	return;
}
$theme_options_data = get_theme_mods();
$sticky_sidebar     = !empty( $theme_options_data['thim_sticky_sidebar'] ) ? ' sticky-sidebar' : '';
$cls_sidebar = '';
if ( get_theme_mod( 'thim_header_style', 'header_v1' ) == 'header_v4' ) {
    $cls_sidebar = ' sidebar_' . get_theme_mod( 'thim_header_style' );
}
?>

<div id="sidebar" class="widget-area col-sm-3<?php echo esc_attr( $sticky_sidebar ); ?><?php echo $cls_sidebar;?>" role="complementary">
	<?php 
    
    $category = get_the_category(); 
    $category_parent_id = $category[0]->category_parent;
    
    //если главная блог страница подключаем Блог сайдбар
     if (strpos($_SERVER['REQUEST_URI'], 'blog') !== false){ //if blogpage 
         do_action('get_blog_sidebar');  
     }
     elseif ( $category_parent_id != 0 ) { 
          //Проверка если одиночный блог пост в Блоге, подключаем блог сайдбар 
        if ($category_parent_id == 16){
            do_action('get_blog_sidebar');  
        } 
          elseif( !dynamic_sidebar( 'sidebar' ) ){
         //подключаем обычный сайдбар по умолчанию 
           dynamic_sidebar( 'sidebar' );  
         }  
   } 
    else {
         //подключаем обычный сайдбар по умолчанию
         if ( !dynamic_sidebar( 'sidebar' ) ){
           dynamic_sidebar( 'sidebar' );  
         } 
          
   }  
    // end sidebar widget area ?>
</div><!-- #secondary -->
