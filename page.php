<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package thim
 */
  
//get_template_part('content', 'page'); check this for any pages
get_template_part('index'); 

// If comments are open or we have at least one comment, load up the comment template
if (comments_open() || get_comments_number()) :
    comments_template();
endif;
 

 