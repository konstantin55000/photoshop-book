<?php
//категории в которых должен отображаться фильтр
 wp_reset_query();
 
 $wp_query->set('posts_per_page', 12);
 $wp_query->query($wp_query->query_vars);

//категории разделов уроки фотошоп
//$arrNeedCats = array(1, 17 , 90 , 98 , 88 , 93 , 96 , 95 , 94);
//категории разделов уроки фотошоп Тестовый сервер
//89, 'работа-с-фото')  97, 'базовый-раздел-не-трогать'
$arrNeedCats = array(1, 17 , 90 , 98 , 88 , 89, 97, 93 , 96 , 94,  'business',  'design-branding' );
$postId = get_the_ID();

//echo"***"; the_category_ID();
//текущие категории поста 
$showLessons = in_category( $arrNeedCats , $postId);
 
//показыват фильтр уроков.
//if page slug != blog
$path = parse_url($url, PHP_URL_PATH);
$pathFragments = explode('/', $path);
$pageslug = end($pathFragments);
//var_dump($pageslug);
if ($pageslug != 'blog'){
    include( locate_template( 'inc/templates/filter-category.php', false, false ) ); 
}
get_template_part('inc/templates/posts', 'category');

 
 
