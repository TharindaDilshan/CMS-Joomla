
/* Stik Up menu script */
(function($) {
	$.fn.tmStickUp=function(options) {

		var getOptions = {
			correctionSelector: $('.correctionSelector')
		}
		$.extend(getOptions, options);

		var
			_this = $(this)
		,	_window = $(window)
		,	_document = $(document)
		,	thisOffsetTop = 0
		,	thisOuterHeight = 0
		,	thisMarginTop = 0
		,	thisPaddingTop = 0
		,	documentScroll = 0
		,	pseudoBlock
		,	lastScrollValue = 0
		,	scrollDir = ''
		,	tmpScrolled
		;

		init();
		function init() {
			thisOffsetTop = parseInt(_this.offset().top);
			thisMarginTop = parseInt(_this.css('margin-top'));
			thisOuterHeight = parseInt(_this.outerHeight(true));

			$('<div class="pseudoStickyBlock"></div>').insertAfter(_this);
			pseudoBlock = $('.pseudoStickyBlock');
			pseudoBlock.css({'position':'relative', 'display':'block'});
			addEventsFunction();
		}//end init

		function addEventsFunction() {
			_document.on('scroll', function() {
				tmpScrolled = $(this).scrollTop();
					if (tmpScrolled > lastScrollValue) {
						scrollDir = 'down';
					} else {
						scrollDir = 'up';
					}
				lastScrollValue = tmpScrolled;

				correctionValue = getOptions.correctionSelector.outerHeight(true);
				documentScroll = parseInt(_window.scrollTop());
				
				if (thisOffsetTop - correctionValue < documentScroll) {
					_this.css({top:correctionValue});
					_this.addClass('isStuck');
					_this.addClass('fadeInDown');
					pseudoBlock.css({'height':thisOuterHeight});
				} else if(thisOffsetTop - correctionValue > documentScroll) {
					_this.removeClass('isStuck');
					_this.removeClass('fadeInDown');
					_this.css({top:0});
					pseudoBlock.css({'height':0});
				}
			}).trigger('scroll');
		}
	}//end tmStickUp function
})(jQuery)
jQuery(document).ready(function() {
	if (jQuery('#icemegamenu').length > 0) {
		var stickMenu = true;
		var docWidth= jQuery('body').find('#navigation').width();
		
		//alert(windowsize);
		if (stickMenu && docWidth > 767) {
			jQuery('body').find('#navigation').wrapInner('<div class="stickUpTop"><div class="stickUpHolder">');
			jQuery('.stickUpTop').addClass('animated');
			jQuery('.stickUpTop').tmStickUp();
			var windowsizes = '100%';
			jQuery('.stickUpTop').css({'width':windowsizes});
		} 
	}
})

	//jQuery(window).resize(function() {
	//var windowsize = jQuery('body').find('#top').width();
	  //jQuery('.stickUpTop').css({'width':windowsize});
	  //alert(windowsize);
	//});
	jQuery(function($){
		///////////////////
		$('.icemegamenu li>a.iceMenuTitle').hover(function(){
			$(this).addClass('hover');
		},
		function(){
			$(this).removeClass('hover');
		});
		var $hide_block = false;
		$('.icemega_modulewrap input[type]').focus(function(){
			$hide_block = true;
		})
		$('.icemega_modulewrap input[type]').blur(function(){
			$hide_block = false;
		})
		if($('body').hasClass('desktop_mode') || ($('body').hasClass('mobile') && screen.width>767)){
			$('.icemegamenu li').not('.parent').find('>a.iceMenuTitle[href^="#"]').click(function(){
				var a=$(this);
				animate_body(a);
			})
			$('.icemegamenu li.parent>a.iceMenuTitle[href^="#"]').click(function(){
				var a=$(this);
				if(a.closest("li").hasClass("hover")){
					hide_submenu(a);
					animate_body(a);
				}
				else{
					$('.icemegamenu li.parent[class^="iceMenuLiLevel"]').not(a.closest("li").parents("li")).not(a.closest("li")).removeClass("hover");
					a.closest("li").addClass("hover").attr("data-hover","true");
					a.closest("li").find(">ul.icesubMenu").addClass("visible");
					return false
				}
			})
			$('.icemegamenu li.parent>a').not('[href]').click(function(){
				var a=$(this);
				if(a.closest("li").hasClass("hover")){
					if(!a.attr('href')){
						a.removeClass('hover');
						a.closest("li").attr('data-hover','false');
						hide_submenu(a, 0);
					}
				}
				else{
					$('.icemegamenu li.parent[class^="iceMenuLiLevel"]').not(a.closest("li").parents("li")).not(a.closest("li")).removeClass("hover");
					a.closest("li").addClass("hover").attr("data-hover","true");
					a.closest("li").find(">ul.icesubMenu").addClass("visible");
					return false;
				}
			})
			$('.icemegamenu li[class^="iceMenuLiLevel"]').not('.parent').find('>a.iceMenuTitle[href]').not('[href^="#"]').click(function(){
				var a=$(this);
				if(window.location.href.split("#")[0]==a.attr('href')) {
					animate_body(a);
					return false;
				}
			})
			$('.icemegamenu li.parent[class^="iceMenuLiLevel"]').find('>a.iceMenuTitle[href]').not('[href^="#"]').click(function(){
				var a=$(this);
				if(a.closest("li").hasClass("hover")){
					if(window.location.href.split("#")[0]==a.attr('href')){
						hide_submenu(a);
						animate_body(a);
						return false;
					}
				}
				else{
					$('.icemegamenu li.parent[class^="iceMenuLiLevel"]').not(a.closest("li").parents("li")).not(a.closest("li")).removeClass("hover");
					a.closest("li").addClass("hover").attr("data-hover","true");
					a.closest("li").find(">ul.icesubMenu").addClass("visible");
					return false;
				}
			})
			$('body').click(function(){
				$('.icemegamenu li.parent').each(function(){
					$(this).attr('data-hover','false');
					a = $(this).find('>a');
					hide_submenu(a, 0);
				})
			})
		}
		else{
			$('.icemegamenu li.parent[class^="iceMenuLiLevel"]').hover(function(){
				$('#icemegamenu li.parent[class^="iceMenuLiLevel"]').not($(this).parents('li')).not($(this)).removeClass('hover');
				$(this).addClass('hover').attr('data-hover','true');
				$(this).find('>ul.icesubMenu').addClass('visible');
			},
			function(){
				$(this).attr('data-hover','false');
				a = $(this).find('>a');
				hide_submenu(a);
			})
			$('.icemegamenu li.iceMenuLiLevel_1>a.iceMenuTitle[href^="#"]').click(function(){
				var a=$(this);
				animate_body(a);
			})
			$('.icemegamenu li[class^="iceMenuLiLevel"]>a.iceMenuTitle[href]').click(function(){
				var a=$(this);
				if(window.location.href.split("#")[0]==a.attr('href')) {
					animate_body(a);
					return false;
				}
				
			})
		}
		function hide_submenu(a, delay){
   			delay = typeof delay !== 'undefined' ? delay : 800;
			a.delay(delay).queue(function(b){
				if(a.closest("li").attr("data-hover")=="false" && $hide_block != true){
					a.closest("li").removeClass("hover").delay(250).queue(function(c){
						if(a.closest("li").attr("data-hover")=="false"){
							a.closest("li").find(">ul.icesubMenu").removeClass("visible")
						}
						c()
					})
				}
				b()
			});
		}
		function animate_body(a){
			if(window.location.href.split("#")[0]!=a.attr('href')){
				if($('a[name="'+a.attr("href").substring(1)+'"]').length){
					click_scroll=true;
					$('.icemegamenu li[class^="iceMenuLiLevel"]').removeClass("hover").find(">a.iceMenuTitle").removeClass("icemega_active").removeClass('hover');
					a.addClass("icemega_active");
					if(history.pushState){
						history.pushState(null,null,a.attr("href"))
					}
					$scrollEl.animate({
						scrollTop: $('a[name="'+a.attr("href").substring(1)+'"]').offset().top-$(".scroll-to-fixed-fixed").outerHeight()
					},400,function(){
						$(this).delay(200).queue(function(b){
							click_scroll=false;
							$(this).delay(1200).queue(function(c){
								if(typeof $.fn.lazy=="function"){
									$("img.lazy").lazy({
										bind:"event",effect:"fadeIn",effectTime:500
									})
								}
								c()
							});
							b()
						})
					})
				}
			}
			if(window.location.href.split("#")[0]==a.attr('href')){
				click_scroll=true;
				$('.icemegamenu li[class^="iceMenuLiLevel"]').removeClass("hover").find(">a.iceMenuTitle").removeClass("icemega_active").removeClass('hover');
				a.addClass("icemega_active");
				if(history.pushState){
					history.pushState(null,null,'#')
				}
				$scrollEl.animate({
					scrollTop: 0
				},400,function(){
					$(this).delay(200).queue(function(b){
						click_scroll=false;
						$(this).delay(1200).queue(function(c){
							if(typeof $.fn.lazy=="function"){
								$("img.lazy").lazy({
									bind:"event",effect:"fadeIn",effectTime:500
								})
							}
							c()
						});
						b()
					})
				})
			}
			return false;
		}
		function change_menu_item(){
			if(!click_scroll){
				var a=$(window).scrollTop(),
				d="";
				y = $(document).height();
				if(!init_hash||init_hash==""){
					for(var b=0,c=idArray.length;b<c;b++){
						if($('a[name="'+idArray[b].substring(1)+'"]').length){
							if(a+($(window).height()-$(".scroll-to-fixed-fixed").outerHeight())/2>=$('a[name="'+idArray[b].substring(1)+'"]').offset().top && y>a+($(window).height()-$(".scroll-to-fixed-fixed").outerHeight())/2-$('a[name="'+idArray[b].substring(1)+'"]').offset().top){
								d=idArray[b];
								y=a+($(window).height()-$(".scroll-to-fixed-fixed").outerHeight())/2-$('a[name="'+idArray[b].substring(1)+'"]').offset().top
							}
						}
					}
					lastScrollTop=a;
					if(window.location.hash!=d&&d!=""){
						if(history.pushState){
							history.pushState(null,null,d)
						}
						$('.icemegamenu li[class^="iceMenuLiLevel"]').removeClass("hover").find(">a.iceMenuTitle").removeClass("icemega_active").removeClass('hover');
						$("a.iceMenuTitle[href="+d+"]").addClass("icemega_active")
					}
					if(a == 0){
						if(history.pushState && window.location.hash){
							history.pushState(null,null,'#')
						}
						$('.icemegamenu li[class^="iceMenuLiLevel"]').removeClass("hover").find(">a.iceMenuTitle").removeClass("icemega_active").removeClass('hover');
						if(window.location.hash){
							$("a.iceMenuTitle[href=\""+window.location.href+"\"]").addClass("icemega_active")
						}
						else{
							$("a.iceMenuTitle[href=\""+window.location.href.split('#')[0]+"\"]").addClass("icemega_active")
						}
					}
				}
				else{
					if($("a.iceMenuTitle[href="+init_hash+"]").length){
						$('.icemegamenu li[class^="iceMenuLiLevel"]').removeClass("hover").find(">a.iceMenuTitle").removeClass("icemega_active").removeClass('hover');
						$("a.iceMenuTitle[href="+init_hash+"]").addClass("icemega_active");
					}
					if($('a[name="'+init_hash.substring(1)+'"]').length){
						click_scroll=true;
						$scrollEl.delay(100).queue(function(z){
							$scrollEl.animate({
								scrollTop: $('a[name="'+init_hash.substring(1)+'"]').offset().top-$(".scroll-to-fixed-fixed").outerHeight()
							},400,function(){
								$(this).delay(200).queue(function(e){
									click_scroll=false;
									init_hash=false;
									$(this).delay(1200).queue(function(f){
										if(typeof $.fn.lazy=="function"){
											$(".lazy_container img").lazy({
												bind:"event",effect:"fadeIn",effectTime:500
											})
										}
										f()
									});
									e()
								})
							})
							z()
						})
					}
				}
				return false
			}
		}
		if($('.icemegamenu li[class^="iceMenuLiLevel"]>a.iceMenuTitle[href^="#"]').length && $('.icemegamenu li[class^="iceMenuLiLevel"]>a.iceMenuTitle[href^="#"]').attr('href')!='#'){
			$(window).scroll($.throttle(500,function(){change_menu_item()}));
			$(window).load(function(){
				change_menu_item();
				if($(this).scrollTop()>24){
					$("body:first").addClass("scrolled")
				}
				else{
					$("body:first").removeClass("scrolled")
				}
			});
			var idArray=[],
			click_scroll=false,
			init_hash=window.location.hash,
			lastScrollTop=0;
			$('.icemegamenu li[class^="iceMenuLiLevel"]>a.iceMenuTitle[href^="#"]').each(function(a){
				idArray[a]=$(this).attr("href");
			});
		}
	});