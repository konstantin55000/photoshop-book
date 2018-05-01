jQuery(function($){
     var urlTo =  "//" + window.location.hostname + '/wp-admin/admin-ajax.php';
   // console.log('urlTo', urlTo);
    var link = $('.thim-blog-top .switch-layout .switchToGrid'); 
    link.click();
//    var a = $('#maintabs li > a')[0] ;
//    var getAllLessons = $(a).attr('data-filter');
//    getTabContent(getAllLessons);
     
    //// $(document).on('keydown', function(e) { e.preventDefault(); });  
    localStorage.setItem('currAClass', '');
     $('#maintabs li > a').on('click', function(){
         var currLesson = $(this).attr('data-filter');
         getTabContent(currLesson);
         //return false;
         //getTabContent(currLesson);
     });
     //pagination
     $(document).on('click', '.loop-pagination li > a', function(e){
         
         //убираем подсветку с прошлой пункта меню страницы
         var activeLink = $('.page-numbers > li  .current'); 
         var targetLink = $(this);  // на какую страницу клацаем
         var currPage = $(this).attr('href');
         if (currPage.indexOf('?paged=') != -1){
             currPage = currPage.split("?paged=");
             currPage = currPage[currPage.length - 1]; 
             console.log('currPage now', currPage);
         } else {
             currPage = currPage.split("/");
             currPage = currPage[currPage.length - 2]; 
         
         }
          
         //get ajax page content for current tabs
         getPageContent(currPage);
         e.preventDefault();
         return false;
          
     });
    $('#lessonfilter input[type=radio]').change(function(val) {
        $('#lessonfilter').submit();
    });
    
    $('#lessonfilter input[type=checkbox]').change(function(val) {
         $('#lessonfilter').submit();
    });
    
    
//     $('#lesson_submit').on('click', function(e){
//         return false;
//         e.preventDefault();
//         e.stopPropagation();
//     })

	$('#lessonfilter').on('submit', function(e){
       
		submitFormFilter($(this));
         e.preventDefault();
         e.stopPropagation();
        return false;
	});


    function submitFormFilter(filter){
        $('#lesson_submit').css('opacity', '1');
		$.ajax({
			url: urlTo,
			data:filter.serialize(), // данные
			type:filter.attr('method'), // тип запроса
			beforeSend:function(xhr){
				filter.find('button').text('Загружаю...'); // изменяем текст кнопки
			},
			success:function(data, msg){
                console.log('nav links', data);
                //$('.pagination').append(data.nav_links[0]);
                if (typeof data == 'undefined'){
                    alert('data is ' + data);
                    return false;
                }
                $('#blog-archive > .row').empty();
                var currClass =  localStorage.getItem('currAClass');
                
				filter.find('button').text('Применить фильтр'); // возвращаеи текст кнопки 
				     
                    if (currClass != ''){ 
                      var found =  $('.switch-layout-container').find('a.'+currClass).addClass('switch-active');
                        
                    }
                if (typeof data.forEach != 'function')
                    return;
                data.forEach(function(row, key){
                     addItemHtml(row);
                    $('#lesson_submit').css('opacity', '0');
                });
                
                //close row
                 
                //console.log(bigStr);
                 
			}
		});

    }

    function addItemHtml(row){
      //console.log('row', row);
     if (typeof row.post_title == 'undefined'){
         return;
     }
      var catStr = ''; 
        if (row.nav_links){
            var links = $('.pagination'); 
            links.empty(); 
            links.html(row.nav_links); 
            return;
        }
        if (row.cat){
             var length = row.cat.length;
              row.cat.forEach(function(category, index){ 
              catStr+= '<a href="'+category.slug+'" rel="category tag">'+category.name+'</a>';
                  if (index != length -1)
                      catStr+=', ';
              });
        }
       
        var dt = row.formatted_date;
        var commentname = 'комментариев';
        if (row.comments_count == 1)
            commentname = 'комментарий'
        if (row.comments_count > 1 && row.comments_count < 5)
            commentname = 'комментария'
       if (row.comments_count > 5)
            commentname = 'комментариев' 
        var tmb = row.post_thumbnail;
        
        var imgStrg = '';
        var wrapperClass = 'entry-content';
        if (tmb === false) {
            wrapperClass = 'entry-header';
        } else {
            
           imgStrg = '<a  class="post-image" href="'+row.posthref+'">' + '<img width="270" height="138" src="' + row.post_thumbnail +'" alt="thumbnail" /></a>';
        }
        console.log('video', row.post_type_video);
        console.log('text',row.post_type_text);
       //иконка для фотошоп тип поста видео или текст.
       var  pType = "";
       if (row.post_type_video){
           pType = "<a href='#' title='Уроки в формате видео'><div class='icon-vodoio-inline'><i class='fa fa-video-camera'></i></div></a>";
        }
        if (row.post_type_text){
            pType = "<a href='#' title='Уроки в текстовом формате'><div class='icon-vodoio-inline'><i class='fa fa-file-text-o'></i></div></a>";
        }
     //console.log(catStr);
     var htmlStr = ['<article id="post-',row.ID,'" class="blog-grid-4 post-',row.ID,' post type-post status-publish format-standard has-post-thumbnail hentry   tag-wordpress pmpro-has-access">',
	'<div class="content-inner">',
     , pType,
		'<div class="post-formats-wrapper">',imgStrg,'</div>',
            '<div class="',wrapperClass,'">',
				'<header class="entry-header">',   
					'<div class="entry-contain">',
						'<h2 class="entry-title"><a href="',row.posthref,'" rel="bookmark">',row.post_title,'</a></h2>',
                   '<ul class="entry-meta">', 
				   '<li class="entry-category"><span>Разделы</span>',
                    catStr, 
                    ' </li>',
				 
				    '<li class="comment-total">',
						'<span>Сложность урока</span>',
						'<span class="value">',row.slojnost_acf,'</span>',
					'</li>',

				'<li class="comment-total">',
						'<span>Тип урока</span>',
						'<span class="value">',row.type_informacii_posta,'</span>',
					'</li>',
				 '<li class="comment-total">',
					'<span>Просмотров</span>',
					'<p><i class="fa fa-eye"></i> <span class="value">',row.postviews,'</span></p>',
					'</li>',
					'<li class="comment-total">',
						'<span>Оценка пользователями</span>',
						'<span></span>',
					'</li>',
					'<li class="comment-total">',
						'<span>Комментарии</span>',
						'<a href="',row.posthref,'/#comments"> ',row.comments_count,' ',commentname,'</a> </li>',

		'</ul>',
							'</div>',
				'</header>',
				'<div class="entry-grid-meta" >',
										'<div class="date">',
						'<i class="fa fa-calendar"></i>',row.formatted_date,						 	'</div>', 
                        '<span class="comments22"> <i class="fa fa-eye"></i>⁠',
						'<span class="views-placeholder">',row.postviews,'</span></span>',
						 '<div class="comments"><i class="fa fa-comment"> ',row.comments_count,'</i>',						'</div>',
										'</div>',
				'<div class="entry-summary">',
					'<p>  </p>',
				'</div>',
				'<div class="readmore">',
					'<a href="',row.posthref,'">Подробнее</a>',
				'</div>',
					'</div>',
	'</div>',
'</article>'].join(''); 
       // console.log('html', htmlStr);
         $('#blog-archive > .row').append($.parseHTML(htmlStr));
    }

    function getTabContent(currLesson) {
        if (typeof currLesson == 'undefined')
            return false;
        $("#curr_lesson").val(currLesson);
        submitFormFilter( $('#lessonfilter') );
    }
    
    function  getPageContent(currPage) {
        if (typeof currPage == 'undefined')
            return false;
        $("#curr_page").val(currPage);
        submitFormFilter( $('#lessonfilter') );
    }
    
    var thim_Blog_SwitchLayout = function () {
		var cookie_name = 'blog_layout',
			archive = $('#blog-archive'),
			switch_layout = $('.thim-blog-top > .switch-layout');
		if (archive.length > 0) {
			//Check grid-layout
			if (!jQuery.cookie(cookie_name) || jQuery.cookie(cookie_name) == 'grid-layout') {
				if (archive.hasClass('blog-list')) {
					archive.removeClass('blog-list').addClass('blog-grid');
				}
				switch_layout.find('> a.switch-active').removeClass('switch-active');
				switch_layout.find('> a.switchToGrid').addClass('switch-active');
                localStorage.setItem('currAClass', 'switchToGrid');
			} else {
				if (archive.hasClass('blog-grid')) {
					archive.removeClass('blog-grid').addClass('blog-list');
				}
				switch_layout.find('> a.switch-active').removeClass('switch-active');
				switch_layout.find('> a.switchToList').addClass('switch-active');
                localStorage.setItem('currAClass', 'switchToList');
			}

			$(document).on('click', '#blog-archive .switch-layout > a', function (event) {
				var elem = $(this),
					archive = $('#blog-archive');

				event.preventDefault();
				//if (!elem.hasClass('switch-active')) {
					var links = switch_layout.find('a').removeClass('switch-active');
                  var elems = document.querySelectorAll(".thim-blog-top.switch-layout-container .switch-active"); 
                    [].forEach.call(elems, function(el) {
                        el.classList.remove("switch-active");
                    });
                     
					elem.addClass('switch-active');
                    localStorage.setItem('currAClass', elem[0].classList[1]); 
                //localStorage.setItem('currAClass', 'switchToList');
                    console.log('2 links', switch_layout.find('>a'));
					if (elem.hasClass('switchToGrid')) {
						archive.fadeOut(300, function () {
							archive.removeClass('blog-list').addClass('blog-grid').fadeIn(300);
							jQuery.cookie(cookie_name, 'grid-layout', {expires: 3, path: '/'});
						});
					} else {
						archive.fadeOut(300, function () {
							archive.removeClass('blog-grid').addClass('blog-list').fadeIn(300);
							jQuery.cookie(cookie_name, 'list-layout', {expires: 3, path: '/'});
						});
					}
				//}
			});
		} 
	};
    
    thim_Blog_SwitchLayout();
});
