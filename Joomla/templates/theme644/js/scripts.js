/* jQuery carusel */
!function(a,b,c,d){function e(b,c){this.settings=null,this.options=a.extend({},e.Defaults,c),this.$element=a(b),this.drag=a.extend({},m),this.state=a.extend({},n),this.e=a.extend({},o),this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._invalidated={},this._pipe=[],a.each(e.Plugins,a.proxy(function(a,b){this._plugins[a[0].toLowerCase()+a.slice(1)]=new b(this)},this)),a.each(e.Pipe,a.proxy(function(b,c){this._pipe.push({filter:c.filter,run:a.proxy(c.run,this)})},this)),this.setup(),this.initialize()}function f(a){if(a.touches!==d)return{x:a.touches[0].pageX,y:a.touches[0].pageY};if(a.touches===d){if(a.pageX!==d)return{x:a.pageX,y:a.pageY};if(a.pageX===d)return{x:a.clientX,y:a.clientY}}}function g(a){var b,d,e=c.createElement("div"),f=a;for(b in f)if(d=f[b],"undefined"!=typeof e.style[d])return e=null,[d,b];return[!1]}function h(){return g(["transition","WebkitTransition","MozTransition","OTransition"])[1]}function i(){return g(["transform","WebkitTransform","MozTransform","OTransform","msTransform"])[0]}function j(){return g(["perspective","webkitPerspective","MozPerspective","OPerspective","MsPerspective"])[0]}function k(){return"ontouchstart"in b||!!navigator.msMaxTouchPoints}function l(){return b.navigator.msPointerEnabled}var m,n,o;m={start:0,startX:0,startY:0,current:0,currentX:0,currentY:0,offsetX:0,offsetY:0,distance:null,startTime:0,endTime:0,updatedX:0,targetEl:null},n={isTouch:!1,isScrolling:!1,isSwiping:!1,direction:!1,inMotion:!1},o={_onDragStart:null,_onDragMove:null,_onDragEnd:null,_transitionEnd:null,_resizer:null,_responsiveCall:null,_goToLoop:null,_checkVisibile:null},e.Defaults={items:3,loop:!1,center:!1,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:b,responsiveClass:!1,fallbackEasing:"swing",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",themeClass:"owl-theme",baseClass:"owl-carousel",itemClass:"owl-item",centerClass:"center",activeClass:"active"},e.Width={Default:"default",Inner:"inner",Outer:"outer"},e.Plugins={},e.Pipe=[{filter:["width","items","settings"],run:function(a){a.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){var a=this._clones,b=this.$stage.children(".cloned");(b.length!==a.length||!this.settings.loop&&a.length>0)&&(this.$stage.children(".cloned").remove(),this._clones=[])}},{filter:["items","settings"],run:function(){var a,b,c=this._clones,d=this._items,e=this.settings.loop?c.length-Math.max(2*this.settings.items,4):0;for(a=0,b=Math.abs(e/2);b>a;a++)e>0?(this.$stage.children().eq(d.length+c.length-1).remove(),c.pop(),this.$stage.children().eq(0).remove(),c.pop()):(c.push(c.length/2),this.$stage.append(d[c[c.length-1]].clone().addClass("cloned")),c.push(d.length-1-(c.length-1)/2),this.$stage.prepend(d[c[c.length-1]].clone().addClass("cloned")))}},{filter:["width","items","settings"],run:function(){var a,b,c,d=this.settings.rtl?1:-1,e=(this.width()/this.settings.items).toFixed(3),f=0;for(this._coordinates=[],b=0,c=this._clones.length+this._items.length;c>b;b++)a=this._mergers[this.relative(b)],a=this.settings.mergeFit&&Math.min(a,this.settings.items)||a,f+=(this.settings.autoWidth?this._items[this.relative(b)].width()+this.settings.margin:e*a)*d,this._coordinates.push(f)}},{filter:["width","items","settings"],run:function(){var b,c,d=(this.width()/this.settings.items).toFixed(3),e={width:Math.abs(this._coordinates[this._coordinates.length-1])+2*this.settings.stagePadding,"padding-left":this.settings.stagePadding||"","padding-right":this.settings.stagePadding||""};if(this.$stage.css(e),e={width:this.settings.autoWidth?"auto":d-this.settings.margin},e[this.settings.rtl?"margin-left":"margin-right"]=this.settings.margin,!this.settings.autoWidth&&a.grep(this._mergers,function(a){return a>1}).length>0)for(b=0,c=this._coordinates.length;c>b;b++)e.width=Math.abs(this._coordinates[b])-Math.abs(this._coordinates[b-1]||0)-this.settings.margin,this.$stage.children().eq(b).css(e);else this.$stage.children().css(e)}},{filter:["width","items","settings"],run:function(a){a.current&&this.reset(this.$stage.children().index(a.current))}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var a,b,c,d,e=this.settings.rtl?1:-1,f=2*this.settings.stagePadding,g=this.coordinates(this.current())+f,h=g+this.width()*e,i=[];for(c=0,d=this._coordinates.length;d>c;c++)a=this._coordinates[c-1]||0,b=Math.abs(this._coordinates[c])+f*e,(this.op(a,"<=",g)&&this.op(a,">",h)||this.op(b,"<",g)&&this.op(b,">",h))&&i.push(c);this.$stage.children("."+this.settings.activeClass).removeClass(this.settings.activeClass),this.$stage.children(":eq("+i.join("), :eq(")+")").addClass(this.settings.activeClass),this.settings.center&&(this.$stage.children("."+this.settings.centerClass).removeClass(this.settings.centerClass),this.$stage.children().eq(this.current()).addClass(this.settings.centerClass))}}],e.prototype.initialize=function(){if(this.trigger("initialize"),this.$element.addClass(this.settings.baseClass).addClass(this.settings.themeClass).toggleClass("owl-rtl",this.settings.rtl),this.browserSupport(),this.settings.autoWidth&&this.state.imagesLoaded!==!0){var b,c,e;if(b=this.$element.find("img"),c=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:d,e=this.$element.children(c).width(),b.length&&0>=e)return this.preloadAutoWidthImages(b),!1}this.$element.addClass("owl-loading"),this.$stage=a("<"+this.settings.stageElement+' class="owl-stage"/>').wrap('<div class="owl-stage-outer">'),this.$element.append(this.$stage.parent()),this.replace(this.$element.children().not(this.$stage.parent())),this._width=this.$element.width(),this.refresh(),this.$element.removeClass("owl-loading").addClass("owl-loaded"),this.eventsCall(),this.internalEvents(),this.addTriggerableEvents(),this.trigger("initialized")},e.prototype.setup=function(){var b=this.viewport(),c=this.options.responsive,d=-1,e=null;c?(a.each(c,function(a){b>=a&&a>d&&(d=Number(a))}),e=a.extend({},this.options,c[d]),delete e.responsive,e.responsiveClass&&this.$element.attr("class",function(a,b){return b.replace(/\b owl-responsive-\S+/g,"")}).addClass("owl-responsive-"+d)):e=a.extend({},this.options),(null===this.settings||this._breakpoint!==d)&&(this.trigger("change",{property:{name:"settings",value:e}}),this._breakpoint=d,this.settings=e,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}}))},e.prototype.optionsLogic=function(){this.$element.toggleClass("owl-center",this.settings.center),this.settings.loop&&this._items.length<this.settings.items&&(this.settings.loop=!1),this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},e.prototype.prepare=function(b){var c=this.trigger("prepare",{content:b});return c.data||(c.data=a("<"+this.settings.itemElement+"/>").addClass(this.settings.itemClass).append(b)),this.trigger("prepared",{content:c.data}),c.data},e.prototype.update=function(){for(var b=0,c=this._pipe.length,d=a.proxy(function(a){return this[a]},this._invalidated),e={};c>b;)(this._invalidated.all||a.grep(this._pipe[b].filter,d).length>0)&&this._pipe[b].run(e),b++;this._invalidated={}},e.prototype.width=function(a){switch(a=a||e.Width.Default){case e.Width.Inner:case e.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},e.prototype.refresh=function(){if(0===this._items.length)return!1;(new Date).getTime();this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$stage.addClass("owl-refresh"),this.update(),this.$stage.removeClass("owl-refresh"),this.state.orientation=b.orientation,this.watchVisibility(),this.trigger("refreshed")},e.prototype.eventsCall=function(){this.e._onDragStart=a.proxy(function(a){this.onDragStart(a)},this),this.e._onDragMove=a.proxy(function(a){this.onDragMove(a)},this),this.e._onDragEnd=a.proxy(function(a){this.onDragEnd(a)},this),this.e._onResize=a.proxy(function(a){this.onResize(a)},this),this.e._transitionEnd=a.proxy(function(a){this.transitionEnd(a)},this),this.e._preventClick=a.proxy(function(a){this.preventClick(a)},this)},e.prototype.onThrottledResize=function(){b.clearTimeout(this.resizeTimer),this.resizeTimer=b.setTimeout(this.e._onResize,this.settings.responsiveRefreshRate)},e.prototype.onResize=function(){return this._items.length?this._width===this.$element.width()?!1:this.trigger("resize").isDefaultPrevented()?!1:(this._width=this.$element.width(),this.invalidate("width"),this.refresh(),void this.trigger("resized")):!1},e.prototype.eventsRouter=function(a){var b=a.type;"mousedown"===b||"touchstart"===b?this.onDragStart(a):"mousemove"===b||"touchmove"===b?this.onDragMove(a):"mouseup"===b||"touchend"===b?this.onDragEnd(a):"touchcancel"===b&&this.onDragEnd(a)},e.prototype.internalEvents=function(){var c=(k(),l());this.settings.mouseDrag?(this.$stage.on("mousedown",a.proxy(function(a){this.eventsRouter(a)},this)),this.$stage.on("dragstart",function(){return!1}),this.$stage.get(0).onselectstart=function(){return!1}):this.$element.addClass("owl-text-select-on"),this.settings.touchDrag&&!c&&this.$stage.on("touchstart touchcancel",a.proxy(function(a){this.eventsRouter(a)},this)),this.transitionEndVendor&&this.on(this.$stage.get(0),this.transitionEndVendor,this.e._transitionEnd,!1),this.settings.responsive!==!1&&this.on(b,"resize",a.proxy(this.onThrottledResize,this))},e.prototype.onDragStart=function(d){var e,g,h,i;if(e=d.originalEvent||d||b.event,3===e.which||this.state.isTouch)return!1;if("mousedown"===e.type&&this.$stage.addClass("owl-grab"),this.trigger("drag"),this.drag.startTime=(new Date).getTime(),this.speed(0),this.state.isTouch=!0,this.state.isScrolling=!1,this.state.isSwiping=!1,this.drag.distance=0,g=f(e).x,h=f(e).y,this.drag.offsetX=this.$stage.position().left,this.drag.offsetY=this.$stage.position().top,this.settings.rtl&&(this.drag.offsetX=this.$stage.position().left+this.$stage.width()-this.width()+this.settings.margin),this.state.inMotion&&this.support3d)i=this.getTransformProperty(),this.drag.offsetX=i,this.animate(i),this.state.inMotion=!0;else if(this.state.inMotion&&!this.support3d)return this.state.inMotion=!1,!1;this.drag.startX=g-this.drag.offsetX,this.drag.startY=h-this.drag.offsetY,this.drag.start=g-this.drag.startX,this.drag.targetEl=e.target||e.srcElement,this.drag.updatedX=this.drag.start,("IMG"===this.drag.targetEl.tagName||"A"===this.drag.targetEl.tagName)&&(this.drag.targetEl.draggable=!1),a(c).on("mousemove.owl.dragEvents mouseup.owl.dragEvents touchmove.owl.dragEvents touchend.owl.dragEvents",a.proxy(function(a){this.eventsRouter(a)},this))},e.prototype.onDragMove=function(a){var c,e,g,h,i,j;this.state.isTouch&&(this.state.isScrolling||(c=a.originalEvent||a||b.event,e=f(c).x,g=f(c).y,this.drag.currentX=e-this.drag.startX,this.drag.currentY=g-this.drag.startY,this.drag.distance=this.drag.currentX-this.drag.offsetX,this.drag.distance<0?this.state.direction=this.settings.rtl?"right":"left":this.drag.distance>0&&(this.state.direction=this.settings.rtl?"left":"right"),this.settings.loop?this.op(this.drag.currentX,">",this.coordinates(this.minimum()))&&"right"===this.state.direction?this.drag.currentX-=(this.settings.center&&this.coordinates(0))-this.coordinates(this._items.length):this.op(this.drag.currentX,"<",this.coordinates(this.maximum()))&&"left"===this.state.direction&&(this.drag.currentX+=(this.settings.center&&this.coordinates(0))-this.coordinates(this._items.length)):(h=this.coordinates(this.settings.rtl?this.maximum():this.minimum()),i=this.coordinates(this.settings.rtl?this.minimum():this.maximum()),j=this.settings.pullDrag?this.drag.distance/5:0,this.drag.currentX=Math.max(Math.min(this.drag.currentX,h+j),i+j)),(this.drag.distance>8||this.drag.distance<-8)&&(c.preventDefault!==d?c.preventDefault():c.returnValue=!1,this.state.isSwiping=!0),this.drag.updatedX=this.drag.currentX,(this.drag.currentY>16||this.drag.currentY<-16)&&this.state.isSwiping===!1&&(this.state.isScrolling=!0,this.drag.updatedX=this.drag.start),this.animate(this.drag.updatedX)))},e.prototype.onDragEnd=function(b){var d,e,f;if(this.state.isTouch){if("mouseup"===b.type&&this.$stage.removeClass("owl-grab"),this.trigger("dragged"),this.drag.targetEl.removeAttribute("draggable"),this.state.isTouch=!1,this.state.isScrolling=!1,this.state.isSwiping=!1,0===this.drag.distance&&this.state.inMotion!==!0)return this.state.inMotion=!1,!1;this.drag.endTime=(new Date).getTime(),d=this.drag.endTime-this.drag.startTime,e=Math.abs(this.drag.distance),(e>3||d>300)&&this.removeClick(this.drag.targetEl),f=this.closest(this.drag.updatedX),this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(f),this.invalidate("position"),this.update(),this.settings.pullDrag||this.drag.updatedX!==this.coordinates(f)||this.transitionEnd(),this.drag.distance=0,a(c).off(".owl.dragEvents")}},e.prototype.removeClick=function(c){this.drag.targetEl=c,a(c).on("click.preventClick",this.e._preventClick),b.setTimeout(function(){a(c).off("click.preventClick")},300)},e.prototype.preventClick=function(b){b.preventDefault?b.preventDefault():b.returnValue=!1,b.stopPropagation&&b.stopPropagation(),a(b.target).off("click.preventClick")},e.prototype.getTransformProperty=function(){var a,c;return a=b.getComputedStyle(this.$stage.get(0),null).getPropertyValue(this.vendorName+"transform"),a=a.replace(/matrix(3d)?\(|\)/g,"").split(","),c=16===a.length,c!==!0?a[4]:a[12]},e.prototype.closest=function(b){var c=-1,d=30,e=this.width(),f=this.coordinates();return this.settings.freeDrag||a.each(f,a.proxy(function(a,g){return b>g-d&&g+d>b?c=a:this.op(b,"<",g)&&this.op(b,">",f[a+1]||g-e)&&(c="left"===this.state.direction?a+1:a),-1===c},this)),this.settings.loop||(this.op(b,">",f[this.minimum()])?c=b=this.minimum():this.op(b,"<",f[this.maximum()])&&(c=b=this.maximum())),c},e.prototype.animate=function(b){this.trigger("translate"),this.state.inMotion=this.speed()>0,this.support3d?this.$stage.css({transform:"translate3d("+b+"px,0px, 0px)",transition:this.speed()/1e3+"s"}):this.state.isTouch?this.$stage.css({left:b+"px"}):this.$stage.animate({left:b},this.speed()/1e3,this.settings.fallbackEasing,a.proxy(function(){this.state.inMotion&&this.transitionEnd()},this))},e.prototype.current=function(a){if(a===d)return this._current;if(0===this._items.length)return d;if(a=this.normalize(a),this._current!==a){var b=this.trigger("change",{property:{name:"position",value:a}});b.data!==d&&(a=this.normalize(b.data)),this._current=a,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},e.prototype.invalidate=function(a){this._invalidated[a]=!0},e.prototype.reset=function(a){a=this.normalize(a),a!==d&&(this._speed=0,this._current=a,this.suppress(["translate","translated"]),this.animate(this.coordinates(a)),this.release(["translate","translated"]))},e.prototype.normalize=function(b,c){var e=c?this._items.length:this._items.length+this._clones.length;return!a.isNumeric(b)||1>e?d:b=this._clones.length?(b%e+e)%e:Math.max(this.minimum(c),Math.min(this.maximum(c),b))},e.prototype.relative=function(a){return a=this.normalize(a),a-=this._clones.length/2,this.normalize(a,!0)},e.prototype.maximum=function(a){var b,c,d,e=0,f=this.settings;if(a)return this._items.length-1;if(!f.loop&&f.center)b=this._items.length-1;else if(f.loop||f.center)if(f.loop||f.center)b=this._items.length+f.items;else{if(!f.autoWidth&&!f.merge)throw"Can not detect maximum absolute position.";for(revert=f.rtl?1:-1,c=this.$stage.width()-this.$element.width();(d=this.coordinates(e))&&!(d*revert>=c);)b=++e}else b=this._items.length-f.items;return b},e.prototype.minimum=function(a){return a?0:this._clones.length/2},e.prototype.items=function(a){return a===d?this._items.slice():(a=this.normalize(a,!0),this._items[a])},e.prototype.mergers=function(a){return a===d?this._mergers.slice():(a=this.normalize(a,!0),this._mergers[a])},e.prototype.clones=function(b){var c=this._clones.length/2,e=c+this._items.length,f=function(a){return a%2===0?e+a/2:c-(a+1)/2};return b===d?a.map(this._clones,function(a,b){return f(b)}):a.map(this._clones,function(a,c){return a===b?f(c):null})},e.prototype.speed=function(a){return a!==d&&(this._speed=a),this._speed},e.prototype.coordinates=function(b){var c=null;return b===d?a.map(this._coordinates,a.proxy(function(a,b){return this.coordinates(b)},this)):(this.settings.center?(c=this._coordinates[b],c+=(this.width()-c+(this._coordinates[b-1]||0))/2*(this.settings.rtl?-1:1)):c=this._coordinates[b-1]||0,c)},e.prototype.duration=function(a,b,c){return Math.min(Math.max(Math.abs(b-a),1),6)*Math.abs(c||this.settings.smartSpeed)},e.prototype.to=function(c,d){if(this.settings.loop){var e=c-this.relative(this.current()),f=this.current(),g=this.current(),h=this.current()+e,i=0>g-h?!0:!1,j=this._clones.length+this._items.length;h<this.settings.items&&i===!1?(f=g+this._items.length,this.reset(f)):h>=j-this.settings.items&&i===!0&&(f=g-this._items.length,this.reset(f)),b.clearTimeout(this.e._goToLoop),this.e._goToLoop=b.setTimeout(a.proxy(function(){this.speed(this.duration(this.current(),f+e,d)),this.current(f+e),this.update()},this),30)}else this.speed(this.duration(this.current(),c,d)),this.current(c),this.update()},e.prototype.next=function(a){a=a||!1,this.to(this.relative(this.current())+1,a)},e.prototype.prev=function(a){a=a||!1,this.to(this.relative(this.current())-1,a)},e.prototype.transitionEnd=function(a){return a!==d&&(a.stopPropagation(),(a.target||a.srcElement||a.originalTarget)!==this.$stage.get(0))?!1:(this.state.inMotion=!1,void this.trigger("translated"))},e.prototype.viewport=function(){var d;if(this.options.responsiveBaseElement!==b)d=a(this.options.responsiveBaseElement).width();else if(b.innerWidth)d=b.innerWidth;else{if(!c.documentElement||!c.documentElement.clientWidth)throw"Can not detect viewport width.";d=c.documentElement.clientWidth}return d},e.prototype.replace=function(b){this.$stage.empty(),this._items=[],b&&(b=b instanceof jQuery?b:a(b)),this.settings.nestedItemSelector&&(b=b.find("."+this.settings.nestedItemSelector)),b.filter(function(){return 1===this.nodeType}).each(a.proxy(function(a,b){b=this.prepare(b),this.$stage.append(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)},this)),this.reset(a.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},e.prototype.add=function(a,b){b=b===d?this._items.length:this.normalize(b,!0),this.trigger("add",{content:a,position:b}),0===this._items.length||b===this._items.length?(this.$stage.append(a),this._items.push(a),this._mergers.push(1*a.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)):(this._items[b].before(a),this._items.splice(b,0,a),this._mergers.splice(b,0,1*a.find("[data-merge]").andSelf("[data-merge]").attr("data-merge")||1)),this.invalidate("items"),this.trigger("added",{content:a,position:b})},e.prototype.remove=function(a){a=this.normalize(a,!0),a!==d&&(this.trigger("remove",{content:this._items[a],position:a}),this._items[a].remove(),this._items.splice(a,1),this._mergers.splice(a,1),this.invalidate("items"),this.trigger("removed",{content:null,position:a}))},e.prototype.addTriggerableEvents=function(){var b=a.proxy(function(b,c){return a.proxy(function(a){a.relatedTarget!==this&&(this.suppress([c]),b.apply(this,[].slice.call(arguments,1)),this.release([c]))},this)},this);a.each({next:this.next,prev:this.prev,to:this.to,destroy:this.destroy,refresh:this.refresh,replace:this.replace,add:this.add,remove:this.remove},a.proxy(function(a,c){this.$element.on(a+".owl.carousel",b(c,a+".owl.carousel"))},this))},e.prototype.watchVisibility=function(){function c(a){return a.offsetWidth>0&&a.offsetHeight>0}function d(){c(this.$element.get(0))&&(this.$element.removeClass("owl-hidden"),this.refresh(),b.clearInterval(this.e._checkVisibile))}c(this.$element.get(0))||(this.$element.addClass("owl-hidden"),b.clearInterval(this.e._checkVisibile),this.e._checkVisibile=b.setInterval(a.proxy(d,this),500))},e.prototype.preloadAutoWidthImages=function(b){var c,d,e,f;c=0,d=this,b.each(function(g,h){e=a(h),f=new Image,f.onload=function(){c++,e.attr("src",f.src),e.css("opacity",1),c>=b.length&&(d.state.imagesLoaded=!0,d.initialize())},f.src=e.attr("src")||e.attr("data-src")||e.attr("data-src-retina")})},e.prototype.destroy=function(){this.$element.hasClass(this.settings.themeClass)&&this.$element.removeClass(this.settings.themeClass),this.settings.responsive!==!1&&a(b).off("resize.owl.carousel"),this.transitionEndVendor&&this.off(this.$stage.get(0),this.transitionEndVendor,this.e._transitionEnd);for(var d in this._plugins)this._plugins[d].destroy();(this.settings.mouseDrag||this.settings.touchDrag)&&(this.$stage.off("mousedown touchstart touchcancel"),a(c).off(".owl.dragEvents"),this.$stage.get(0).onselectstart=function(){},this.$stage.off("dragstart",function(){return!1})),this.$element.off(".owl"),this.$stage.children(".cloned").remove(),this.e=null,this.$element.removeData("owlCarousel"),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.unwrap()},e.prototype.op=function(a,b,c){var d=this.settings.rtl;switch(b){case"<":return d?a>c:c>a;case">":return d?c>a:a>c;case">=":return d?c>=a:a>=c;case"<=":return d?a>=c:c>=a}},e.prototype.on=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d):a.attachEvent&&a.attachEvent("on"+b,c)},e.prototype.off=function(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d):a.detachEvent&&a.detachEvent("on"+b,c)},e.prototype.trigger=function(b,c,d){var e={item:{count:this._items.length,index:this.current()}},f=a.camelCase(a.grep(["on",b,d],function(a){return a}).join("-").toLowerCase()),g=a.Event([b,"owl",d||"carousel"].join(".").toLowerCase(),a.extend({relatedTarget:this},e,c));return this._supress[b]||(a.each(this._plugins,function(a,b){b.onTrigger&&b.onTrigger(g)}),this.$element.trigger(g),this.settings&&"function"==typeof this.settings[f]&&this.settings[f].apply(this,g)),g},e.prototype.suppress=function(b){a.each(b,a.proxy(function(a,b){this._supress[b]=!0},this))},e.prototype.release=function(b){a.each(b,a.proxy(function(a,b){delete this._supress[b]},this))},e.prototype.browserSupport=function(){if(this.support3d=j(),this.support3d){this.transformVendor=i();var a=["transitionend","webkitTransitionEnd","transitionend","oTransitionEnd"];this.transitionEndVendor=a[h()],this.vendorName=this.transformVendor.replace(/Transform/i,""),this.vendorName=""!==this.vendorName?"-"+this.vendorName.toLowerCase()+"-":""}this.state.orientation=b.orientation},a.fn.owlCarousel=function(b){return this.each(function(){a(this).data("owlCarousel")||a(this).data("owlCarousel",new e(this,b))})},a.fn.owlCarousel.Constructor=e}(window.Zepto||window.jQuery,window,document),function(a,b){var c=function(b){this._core=b,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel":a.proxy(function(b){if(b.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(b.property&&"position"==b.property.name||"initialized"==b.type))for(var c=this._core.settings,d=c.center&&Math.ceil(c.items/2)||c.items,e=c.center&&-1*d||0,f=(b.property&&b.property.value||this._core.current())+e,g=this._core.clones().length,h=a.proxy(function(a,b){this.load(b)},this);e++<d;)this.load(g/2+this._core.relative(f)),g&&a.each(this._core.clones(this._core.relative(f++)),h)},this)},this._core.options=a.extend({},c.Defaults,this._core.options),this._core.$element.on(this._handlers)};c.Defaults={lazyLoad:!1},c.prototype.load=function(c){var d=this._core.$stage.children().eq(c),e=d&&d.find(".owl-lazy");!e||a.inArray(d.get(0),this._loaded)>-1||(e.each(a.proxy(function(c,d){var e,f=a(d),g=b.devicePixelRatio>1&&f.attr("data-src-retina")||f.attr("data-src");this._core.trigger("load",{element:f,url:g},"lazy"),f.is("img")?f.one("load.owl.lazy",a.proxy(function(){f.css("opacity",1),this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("src",g):(e=new Image,e.onload=a.proxy(function(){f.css({"background-image":"url("+g+")",opacity:"1"}),this._core.trigger("loaded",{element:f,url:g},"lazy")},this),e.src=g)},this)),this._loaded.push(d.get(0)))},c.prototype.destroy=function(){var a,b;for(a in this.handlers)this._core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=c}(window.Zepto||window.jQuery,window,document),function(a){var b=function(c){this._core=c,this._handlers={"initialized.owl.carousel":a.proxy(function(){this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":a.proxy(function(a){this._core.settings.autoHeight&&"position"==a.property.name&&this.update()},this),"loaded.owl.lazy":a.proxy(function(a){this._core.settings.autoHeight&&a.element.closest("."+this._core.settings.itemClass)===this._core.$stage.children().eq(this._core.current())&&this.update()},this)},this._core.options=a.extend({},b.Defaults,this._core.options),this._core.$element.on(this._handlers)};b.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},b.prototype.update=function(){this._core.$stage.parent().height(this._core.$stage.children().eq(this._core.current()).height()).addClass(this._core.settings.autoHeightClass)},b.prototype.destroy=function(){var a,b;for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoHeight=b}(window.Zepto||window.jQuery,window,document),function(a,b,c){var d=function(b){this._core=b,this._videos={},this._playing=null,this._fullscreen=!1,this._handlers={"resize.owl.carousel":a.proxy(function(a){this._core.settings.video&&!this.isInFullScreen()&&a.preventDefault()},this),"refresh.owl.carousel changed.owl.carousel":a.proxy(function(){this._playing&&this.stop()},this),"prepared.owl.carousel":a.proxy(function(b){var c=a(b.content).find(".owl-video");c.length&&(c.css("display","none"),this.fetch(c,a(b.content)))},this)},this._core.options=a.extend({},d.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",a.proxy(function(a){this.play(a)},this))};d.Defaults={video:!1,videoHeight:!1,videoWidth:!1},d.prototype.fetch=function(a,b){var c=a.attr("data-vimeo-id")?"vimeo":"youtube",d=a.attr("data-vimeo-id")||a.attr("data-youtube-id"),e=a.attr("data-width")||this._core.settings.videoWidth,f=a.attr("data-height")||this._core.settings.videoHeight,g=a.attr("href");if(!g)throw new Error("Missing video URL.");if(d=g.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),d[3].indexOf("youtu")>-1)c="youtube";else{if(!(d[3].indexOf("vimeo")>-1))throw new Error("Video URL not supported.");c="vimeo"}d=d[6],this._videos[g]={type:c,id:d,width:e,height:f},b.attr("data-video",g),this.thumbnail(a,this._videos[g])},d.prototype.thumbnail=function(b,c){var d,e,f,g=c.width&&c.height?'style="width:'+c.width+"px;height:"+c.height+'px;"':"",h=b.find("img"),i="src",j="",k=this._core.settings,l=function(a){e='<div class="owl-video-play-icon"></div>',d=k.lazyLoad?'<div class="owl-video-tn '+j+'" '+i+'="'+a+'"></div>':'<div class="owl-video-tn" style="opacity:1;background-image:url('+a+')"></div>',b.after(d),b.after(e)};return b.wrap('<div class="owl-video-wrapper"'+g+"></div>"),this._core.settings.lazyLoad&&(i="data-src",j="owl-lazy"),h.length?(l(h.attr(i)),h.remove(),!1):void("youtube"===c.type?(f="http://img.youtube.com/vi/"+c.id+"/hqdefault.jpg",l(f)):"vimeo"===c.type&&a.ajax({type:"GET",url:"http://vimeo.com/api/v2/video/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a[0].thumbnail_large,l(f)}}))},d.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null},d.prototype.play=function(b){this._core.trigger("play",null,"video"),this._playing&&this.stop();var c,d,e=a(b.target||b.srcElement),f=e.closest("."+this._core.settings.itemClass),g=this._videos[f.attr("data-video")],h=g.width||"100%",i=g.height||this._core.$stage.height();"youtube"===g.type?c='<iframe width="'+h+'" height="'+i+'" src="http://www.youtube.com/embed/'+g.id+"?autoplay=1&v="+g.id+'" frameborder="0" allowfullscreen></iframe>':"vimeo"===g.type&&(c='<iframe src="http://player.vimeo.com/video/'+g.id+'?autoplay=1" width="'+h+'" height="'+i+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'),f.addClass("owl-video-playing"),this._playing=f,d=a('<div style="height:'+i+"px; width:"+h+'px" class="owl-video-frame">'+c+"</div>"),e.after(d)},d.prototype.isInFullScreen=function(){var d=c.fullscreenElement||c.mozFullScreenElement||c.webkitFullscreenElement;return d&&a(d).parent().hasClass("owl-video-frame")&&(this._core.speed(0),this._fullscreen=!0),d&&this._fullscreen&&this._playing?!1:this._fullscreen?(this._fullscreen=!1,!1):this._playing&&this._core.state.orientation!==b.orientation?(this._core.state.orientation=b.orientation,!1):!0},d.prototype.destroy=function(){var a,b;this._core.$element.off("click.owl.video");for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Video=d}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this.core=b,this.core.options=a.extend({},e.Defaults,this.core.options),this.swapping=!0,this.previous=d,this.next=d,this.handlers={"change.owl.carousel":a.proxy(function(a){"position"==a.property.name&&(this.previous=this.core.current(),this.next=a.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":a.proxy(function(a){this.swapping="translated"==a.type},this),"translate.owl.carousel":a.proxy(function(){this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};e.Defaults={animateOut:!1,animateIn:!1},e.prototype.swap=function(){if(1===this.core.settings.items&&this.core.support3d){this.core.speed(0);var b,c=a.proxy(this.clear,this),d=this.core.$stage.children().eq(this.previous),e=this.core.$stage.children().eq(this.next),f=this.core.settings.animateIn,g=this.core.settings.animateOut;this.core.current()!==this.previous&&(g&&(b=this.core.coordinates(this.previous)-this.core.coordinates(this.next),d.css({left:b+"px"}).addClass("animated owl-animated-out").addClass(g).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",c)),f&&e.addClass("animated owl-animated-in").addClass(f).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",c))}},e.prototype.clear=function(b){a(b.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.transitionEnd()},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Animate=e}(window.Zepto||window.jQuery,window,document),function(a,b,c){var d=function(b){this.core=b,this.core.options=a.extend({},d.Defaults,this.core.options),this.handlers={"translated.owl.carousel refreshed.owl.carousel":a.proxy(function(){this.autoplay()
},this),"play.owl.autoplay":a.proxy(function(a,b,c){this.play(b,c)},this),"stop.owl.autoplay":a.proxy(function(){this.stop()},this),"mouseover.owl.autoplay":a.proxy(function(){this.core.settings.autoplayHoverPause&&this.pause()},this),"mouseleave.owl.autoplay":a.proxy(function(){this.core.settings.autoplayHoverPause&&this.autoplay()},this)},this.core.$element.on(this.handlers)};d.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},d.prototype.autoplay=function(){this.core.settings.autoplay&&!this.core.state.videoPlay?(b.clearInterval(this.interval),this.interval=b.setInterval(a.proxy(function(){this.play()},this),this.core.settings.autoplayTimeout)):b.clearInterval(this.interval)},d.prototype.play=function(){return c.hidden===!0||this.core.state.isTouch||this.core.state.isScrolling||this.core.state.isSwiping||this.core.state.inMotion?void 0:this.core.settings.autoplay===!1?void b.clearInterval(this.interval):void this.core.next(this.core.settings.autoplaySpeed)},d.prototype.stop=function(){b.clearInterval(this.interval)},d.prototype.pause=function(){b.clearInterval(this.interval)},d.prototype.destroy=function(){var a,c;b.clearInterval(this.interval);for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},a.fn.owlCarousel.Constructor.Plugins.autoplay=d}(window.Zepto||window.jQuery,window,document),function(a){"use strict";var b=function(c){this._core=c,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":a.proxy(function(b){this._core.settings.dotsData&&this._templates.push(a(b.content).find("[data-dot]").andSelf("[data-dot]").attr("data-dot"))},this),"add.owl.carousel":a.proxy(function(b){this._core.settings.dotsData&&this._templates.splice(b.position,0,a(b.content).find("[data-dot]").andSelf("[data-dot]").attr("data-dot"))},this),"remove.owl.carousel prepared.owl.carousel":a.proxy(function(a){this._core.settings.dotsData&&this._templates.splice(a.position,1)},this),"change.owl.carousel":a.proxy(function(a){if("position"==a.property.name&&!this._core.state.revert&&!this._core.settings.loop&&this._core.settings.navRewind){var b=this._core.current(),c=this._core.maximum(),d=this._core.minimum();a.data=a.property.value>c?b>=c?d:c:a.property.value<d?c:a.property.value}},this),"changed.owl.carousel":a.proxy(function(a){"position"==a.property.name&&this.draw()},this),"refreshed.owl.carousel":a.proxy(function(){this._initialized||(this.initialize(),this._initialized=!0),this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation")},this)},this._core.options=a.extend({},b.Defaults,this._core.options),this.$element.on(this._handlers)};b.Defaults={nav:!1,navRewind:!0,navText:["prev","next"],navSpeed:!1,navElement:"div",navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotData:!1,dotsSpeed:!1,dotsContainer:!1,controlsClass:"owl-controls"},b.prototype.initialize=function(){var b,c,d=this._core.settings;d.dotsData||(this._templates=[a("<div>").addClass(d.dotClass).append(a("<span>")).prop("outerHTML")]),d.navContainer&&d.dotsContainer||(this._controls.$container=a("<div>").addClass(d.controlsClass).appendTo(this.$element)),this._controls.$indicators=d.dotsContainer?a(d.dotsContainer):a("<div>").hide().addClass(d.dotsClass).appendTo(this._controls.$container),this._controls.$indicators.on("click","div",a.proxy(function(b){var c=a(b.target).parent().is(this._controls.$indicators)?a(b.target).index():a(b.target).parent().index();b.preventDefault(),this.to(c,d.dotsSpeed)},this)),b=d.navContainer?a(d.navContainer):a("<div>").addClass(d.navContainerClass).prependTo(this._controls.$container),this._controls.$next=a("<"+d.navElement+">"),this._controls.$previous=this._controls.$next.clone(),this._controls.$previous.addClass(d.navClass[0]).html(d.navText[0]).hide().prependTo(b).on("click",a.proxy(function(){this.prev(d.navSpeed)},this)),this._controls.$next.addClass(d.navClass[1]).html(d.navText[1]).hide().appendTo(b).on("click",a.proxy(function(){this.next(d.navSpeed)},this));for(c in this._overrides)this._core[c]=a.proxy(this[c],this)},b.prototype.destroy=function(){var a,b,c,d;for(a in this._handlers)this.$element.off(a,this._handlers[a]);for(b in this._controls)this._controls[b].remove();for(d in this.overides)this._core[d]=this._overrides[d];for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},b.prototype.update=function(){var a,b,c,d=this._core.settings,e=this._core.clones().length/2,f=e+this._core.items().length,g=d.center||d.autoWidth||d.dotData?1:d.dotsEach||d.items;if("page"!==d.slideBy&&(d.slideBy=Math.min(d.slideBy,d.items)),d.dots||"page"==d.slideBy)for(this._pages=[],a=e,b=0,c=0;f>a;a++)(b>=g||0===b)&&(this._pages.push({start:a-e,end:a-e+g-1}),b=0,++c),b+=this._core.mergers(this._core.relative(a))},b.prototype.draw=function(){var b,c,d="",e=this._core.settings,f=(this._core.$stage.children(),this._core.relative(this._core.current()));if(!e.nav||e.loop||e.navRewind||(this._controls.$previous.toggleClass("disabled",0>=f),this._controls.$next.toggleClass("disabled",f>=this._core.maximum())),this._controls.$previous.toggle(e.nav),this._controls.$next.toggle(e.nav),e.dots){if(b=this._pages.length-this._controls.$indicators.children().length,e.dotData&&0!==b){for(c=0;c<this._controls.$indicators.children().length;c++)d+=this._templates[this._core.relative(c)];this._controls.$indicators.html(d)}else b>0?(d=new Array(b+1).join(this._templates[0]),this._controls.$indicators.append(d)):0>b&&this._controls.$indicators.children().slice(b).remove();this._controls.$indicators.find(".active").removeClass("active"),this._controls.$indicators.children().eq(a.inArray(this.current(),this._pages)).addClass("active")}this._controls.$indicators.toggle(e.dots)},b.prototype.onTrigger=function(b){var c=this._core.settings;b.page={index:a.inArray(this.current(),this._pages),count:this._pages.length,size:c&&(c.center||c.autoWidth||c.dotData?1:c.dotsEach||c.items)}},b.prototype.current=function(){var b=this._core.relative(this._core.current());return a.grep(this._pages,function(a){return a.start<=b&&a.end>=b}).pop()},b.prototype.getPosition=function(b){var c,d,e=this._core.settings;return"page"==e.slideBy?(c=a.inArray(this.current(),this._pages),d=this._pages.length,b?++c:--c,c=this._pages[(c%d+d)%d].start):(c=this._core.relative(this._core.current()),d=this._core.items().length,b?c+=e.slideBy:c-=e.slideBy),c},b.prototype.next=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!0),b)},b.prototype.prev=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!1),b)},b.prototype.to=function(b,c,d){var e;d?a.proxy(this._overrides.to,this._core)(b,c):(e=this._pages.length,a.proxy(this._overrides.to,this._core)(this._pages[(b%e+e)%e].start,c))},a.fn.owlCarousel.Constructor.Plugins.Navigation=b}(window.Zepto||window.jQuery,window,document),function(a,b){"use strict";var c=function(d){this._core=d,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":a.proxy(function(){"URLHash"==this._core.settings.startPosition&&a(b).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":a.proxy(function(b){var c=a(b.content).find("[data-hash]").andSelf("[data-hash]").attr("data-hash");this._hashes[c]=b.content},this)},this._core.options=a.extend({},c.Defaults,this._core.options),this.$element.on(this._handlers),a(b).on("hashchange.owl.navigation",a.proxy(function(){var a=b.location.hash.substring(1),c=this._core.$stage.children(),d=this._hashes[a]&&c.index(this._hashes[a])||0;return a?void this._core.to(d,!1,!0):!1},this))};c.Defaults={URLhashListener:!1},c.prototype.destroy=function(){var c,d;a(b).off("hashchange.owl.navigation");for(c in this._handlers)this._core.$element.off(c,this._handlers[c]);for(d in Object.getOwnPropertyNames(this))"function"!=typeof this[d]&&(this[d]=null)},a.fn.owlCarousel.Constructor.Plugins.Hash=c}(window.Zepto||window.jQuery,window,document);
/* jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/ */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('d.f["V"]=d.f["y"];d.M(d.f,{B:"C",y:9(e,t,n,r,i){6 d.f[d.f.B](e,t,n,r,i)},14:9(e,t,n,r,i){6 r*(t/=i)*t+n},C:9(e,t,n,r,i){6-r*(t/=i)*(t-2)+n},13:9(e,t,n,r,i){a((t/=i/2)<1)6 r/2*t*t+n;6-r/2*(--t*(t-2)-1)+n},12:9(e,t,n,r,i){6 r*(t/=i)*t*t+n},Q:9(e,t,n,r,i){6 r*((t=t/i-1)*t*t+1)+n},O:9(e,t,n,r,i){a((t/=i/2)<1)6 r/2*t*t*t+n;6 r/2*((t-=2)*t*t+2)+n},P:9(e,t,n,r,i){6 r*(t/=i)*t*t*t+n},L:9(e,t,n,r,i){6-r*((t=t/i-1)*t*t*t-1)+n},S:9(e,t,n,r,i){a((t/=i/2)<1)6 r/2*t*t*t*t+n;6-r/2*((t-=2)*t*t*t-2)+n},F:9(e,t,n,r,i){6 r*(t/=i)*t*t*t*t+n},J:9(e,t,n,r,i){6 r*((t=t/i-1)*t*t*t*t+1)+n},K:9(e,t,n,r,i){a((t/=i/2)<1)6 r/2*t*t*t*t*t+n;6 r/2*((t-=2)*t*t*t*t+2)+n},N:9(e,t,n,r,i){6-r*8.A(t/i*(8.c/2))+r+n},R:9(e,t,n,r,i){6 r*8.l(t/i*(8.c/2))+n},X:9(e,t,n,r,i){6-r/2*(8.A(8.c*t/i)-1)+n},11:9(e,t,n,r,i){6 t==0?n:r*8.g(2,10*(t/i-1))+n},15:9(e,t,n,r,i){6 t==i?n+r:r*(-8.g(2,-10*t/i)+1)+n},16:9(e,t,n,r,i){a(t==0)6 n;a(t==i)6 n+r;a((t/=i/2)<1)6 r/2*8.g(2,10*(t-1))+n;6 r/2*(-8.g(2,-10*--t)+2)+n},E:9(e,t,n,r,i){6-r*(8.p(1-(t/=i)*t)-1)+n},G:9(e,t,n,r,i){6 r*8.p(1-(t=t/i-1)*t)+n},H:9(e,t,n,r,i){a((t/=i/2)<1)6-r/2*(8.p(1-t*t)-1)+n;6 r/2*(8.p(1-(t-=2)*t)+1)+n},I:9(e,t,n,r,i){b s=1.j;b o=0;b u=r;a(t==0)6 n;a((t/=i)==1)6 n+r;a(!o)o=i*.3;a(u<8.v(r)){u=r;b s=o/4}k b s=o/(2*8.c)*8.w(r/u);6-(u*8.g(2,10*(t-=1))*8.l((t*i-s)*2*8.c/o))+n},T:9(e,t,n,r,i){b s=1.j;b o=0;b u=r;a(t==0)6 n;a((t/=i)==1)6 n+r;a(!o)o=i*.3;a(u<8.v(r)){u=r;b s=o/4}k b s=o/(2*8.c)*8.w(r/u);6 u*8.g(2,-10*t)*8.l((t*i-s)*2*8.c/o)+r+n},U:9(e,t,n,r,i){b s=1.j;b o=0;b u=r;a(t==0)6 n;a((t/=i/2)==2)6 n+r;a(!o)o=i*.3*1.5;a(u<8.v(r)){u=r;b s=o/4}k b s=o/(2*8.c)*8.w(r/u);a(t<1)6-.5*u*8.g(2,10*(t-=1))*8.l((t*i-s)*2*8.c/o)+n;6 u*8.g(2,-10*(t-=1))*8.l((t*i-s)*2*8.c/o)*.5+r+n},W:9(e,t,n,r,i,s){a(s==x)s=1.j;6 r*(t/=i)*t*((s+1)*t-s)+n},Y:9(e,t,n,r,i,s){a(s==x)s=1.j;6 r*((t=t/i-1)*t*((s+1)*t+s)+1)+n},Z:9(e,t,n,r,i,s){a(s==x)s=1.j;a((t/=i/2)<1)6 r/2*t*t*(((s*=1.D)+1)*t-s)+n;6 r/2*((t-=2)*t*(((s*=1.D)+1)*t+s)+2)+n},z:9(e,t,n,r,i){6 r-d.f.q(e,i-t,0,r,i)+n},q:9(e,t,n,r,i){a((t/=i)<1/2.h){6 r*7.m*t*t+n}k a(t<2/2.h){6 r*(7.m*(t-=1.5/2.h)*t+.h)+n}k a(t<2.5/2.h){6 r*(7.m*(t-=2.17/2.h)*t+.18)+n}k{6 r*(7.m*(t-=2.19/2.h)*t+.1a)+n}},1b:9(e,t,n,r,i){a(t<i/2)6 d.f.z(e,t*2,0,r,i)*.5+n;6 d.f.q(e,t*2-i,0,r,i)*.5+r*.5+n}})',62,74,'||||||return||Math|function|if|var|PI|jQuery||easing|pow|75||70158|else|sin|5625|||sqrt|easeOutBounce|||||abs|asin|undefined|swing|easeInBounce|cos|def|easeOutQuad|525|easeInCirc|easeInQuint|easeOutCirc|easeInOutCirc|easeInElastic|easeOutQuint|easeInOutQuint|easeOutQuart|extend|easeInSine|easeInOutCubic|easeInQuart|easeOutCubic|easeOutSine|easeInOutQuart|easeOutElastic|easeInOutElastic|jswing|easeInBack|easeInOutSine|easeOutBack|easeInOutBack||easeInExpo|easeInCubic|easeInOutQuad|easeInQuad|easeOutExpo|easeInOutExpo|25|9375|625|984375|easeInOutBounce'.split('|'),0,{}))
/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 */
;(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
/*!
 * https://github.com/es-shims/es5-shim
 */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}(';(15(a,b){V(1F 2F==="15"&&2F.4q){2F(b)}1s V(1F 3o==="1d"){41.3o=b()}1s{a.40=b()}})(13,15(){15 2i(){}V(!1w.17.1x){1w.17.1x=15 1x(b){W c=13;V(1F c!="15"){1h 18 1k("1w.17.1x 2k 2U 3P "+c)}W d=u.1f(1a,1);W e=15(){V(13 39 h){W a=c.1j(13,d.2X(u.1f(1a)));V(1G(a)===a){14 a}14 13}1s{14 c.1j(b,d.2X(u.1f(1a)))}};W f=1l.3i(0,c.X-d.X);W g=[];1v(W i=0;i<f;i++){g.1D("$"+i)}W h=1w("3j","14 15("+g.2Q(",")+"){14 3j.1j(13,1a)}")(e);V(c.17){2i.17=c.17;h.17=18 2i;2i.17=1E}14 h}}W p=1w.17.1f;W q=19.17;W r=1G.17;W u=q.1r;W v=p.1x(r.1y);W w=p.1x(r.3y);W y;W A;W B;W C;W E;V(E=w(r,"3n")){y=p.1x(r.3n);A=p.1x(r.4Y);B=p.1x(r.4U);C=p.1x(r.4y)}V([1,2].1Q(0).X!=2){W F=19.17.1Q;W G=19.17.1D;W H=19.17.1M;V(15(){15 2K(l){W a=[];1p(l--){a.1M(l)}14 a}W b=[],2H;b.1Q.1x(b,0,0).1j(1E,2K(20));b.1Q.1x(b,0,0).1j(1E,2K(26));2H=b.X;b.1Q(5,0,"3I");V(2H+1==b.X){14 1L}}()){19.17.1Q=15(a,b){V(!1a.X){14[]}1s{14 F.1j(13,[a===1q 0?0:a,b===1q 0?13.X-a:b].2X(u.1f(1a,2)))}}}1s{19.17.1Q=15(a,b){W c,1B=u.1f(1a,2),3s=1B.X;V(!1a.X){14[]}V(a===1q 0){a=0}V(b===1q 0){b=13.X-a}V(3s>0){V(b<=0){V(a==13.X){G.1j(13,1B);14[]}V(a==0){H.1j(13,1B);14[]}}c=u.1f(13,a,a+b);1B.1D.1j(1B,u.1f(13,a+b,13.X));1B.1M.1j(1B,u.1f(13,0,a));1B.1M(0,13.X);F.1j(13,1B);14 c}14 F.1f(13,a,b)}}}V([].1M(0)!=1){W H=19.17.1M;19.17.1M=15(){H.1j(13,1a);14 13.X}}V(!19.2E){19.2E=15 2E(a){14 v(a)=="[1d 19]"}}W I=1G("a");W J=I[0]!="a"||!(0 1n I);W K=15 3V(d){W e=1L;V(d){d.1f("4s",15(a,b,c){V(1F c!=="1d"){e=27}})}14!!d&&e};V(!19.17.2v||!K(19.17.2v)){19.17.2v=15 2v(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,1C=1a[1],i=-1,X=1b.X>>>0;V(v(a)!="[1d 1w]"){1h 18 1k}1p(++i<X){V(i 1n 1b){a.1f(1C,1b[i],i,b)}}}}V(!19.17.2m||!K(19.17.2m)){19.17.2m=15 2m(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0,1o=19(X),1C=1a[1];V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}1v(W i=0;i<X;i++){V(i 1n 1b)1o[i]=a.1f(1C,1b[i],i,b)}14 1o}}V(!19.17.2n||!K(19.17.2n)){19.17.2n=15 2n(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0,1o=[],1u,1C=1a[1];V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}1v(W i=0;i<X;i++){V(i 1n 1b){1u=1b[i];V(a.1f(1C,1u,i,b)){1o.1D(1u)}}}14 1o}}V(!19.17.2t||!K(19.17.2t)){19.17.2t=15 2t(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0,1C=1a[1];V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}1v(W i=0;i<X;i++){V(i 1n 1b&&!a.1f(1C,1b[i],i,b)){14 27}}14 1L}}V(!19.17.2u||!K(19.17.2u)){19.17.2u=15 2u(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0,1C=1a[1];V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}1v(W i=0;i<X;i++){V(i 1n 1b&&a.1f(1C,1b[i],i,b)){14 1L}}14 27}}V(!19.17.2d){19.17.2d=15 2d(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0;V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}V(!X&&1a.X==1){1h 18 1k("2d 2e 2x 2y 2f 2A 2B 1u")}W i=0;W c;V(1a.X>=2){c=1a[1]}1s{2R{V(i 1n 1b){c=1b[i++];2S}V(++i>=X){1h 18 1k("2d 2e 2x 2y 2f 2A 2B 1u")}}1p(1L)}1v(;i<X;i++){V(i 1n 1b){c=a.1f(1q 0,c,1b[i],i,b)}}14 c}}V(!19.17.1Z){19.17.1Z=15 1Z(a){W b=U(13),1b=J&&v(13)=="[1d 1g]"?13.1i(""):b,X=1b.X>>>0;V(v(a)!="[1d 1w]"){1h 18 1k(a+" 1K 1N a 15")}V(!X&&1a.X==1){1h 18 1k("1Z 2e 2x 2y 2f 2A 2B 1u")}W c,i=X-1;V(1a.X>=2){c=1a[1]}1s{2R{V(i 1n 1b){c=1b[i--];2S}V(--i<0){1h 18 1k("1Z 2e 2x 2y 2f 2A 2B 1u")}}1p(1L)}V(i<0){14 c}2R{V(i 1n 13){c=a.1f(1q 0,c,1b[i],i,b)}}1p(i--);14 c}}V(!19.17.1S||[0,1].1S(1,2)!=-1){19.17.1S=15 1S(a){W b=J&&v(13)=="[1d 1g]"?13.1i(""):U(13),X=b.X>>>0;V(!X){14-1}W i=0;V(1a.X>1){i=2T(1a[1])}i=i>=0?i:1l.3i(0,X+i);1v(;i<X;i++){V(i 1n b&&b[i]===a){14 i}}14-1}}V(!19.17.2l||[0,1].2l(0,-3)!=-1){19.17.2l=15 2l(a){W b=J&&v(13)=="[1d 1g]"?13.1i(""):U(13),X=b.X>>>0;V(!X){14-1}W i=X-1;V(1a.X>1){i=1l.4v(i,2T(1a[1]))}i=i>=0?i:X-1l.2V(i);1v(;i>=0;i--){V(i 1n b&&a===b[i]){14 i}}14-1}}V(!1G.2W){W L=1L,3p=15(){}.35("17"),33=["1y","4f","1V","3y","4u","35","2b"],37=33.X;1v(W N 1n{1y:1E}){L=27}1G.2W=15 c(a){W b=v(a)==="[1d 1w]",3c=a!==1E&&1F a==="1d";V(!3c&&!b){1h 18 1k("1G.2W 2k 2U a 3f-1d")}W c=[],3g=3p&&b;1v(W d 1n a){V(!(3g&&d==="17")&&w(a,d)){c.1D(d)}}V(L){W e=a.2b,3k=e&&e.17===a;1v(W i=0;i<37;i++){W f=33[i];V(!(3k&&f==="2b")&&w(a,f)){c.1D(f)}}}14 c}}W O=-4c,2M="-4g";V(!1c.17.1J||18 1c(O).1J().1S(2M)===-1){1c.17.1J=15 1J(){W a,X,1u,1A,1z;V(!3E(13)){1h 18 3F("1c.17.1J 2k 2U 3f-4V 1u.")}1A=13.4W();1z=13.4X();1A+=1l.1H(1z/12);1z=(1z%12+12)%12;a=[1z+1,13.3K(),13.3L(),13.3M(),13.3N()];1A=(1A<0?"-":1A>3z?"+":"")+("3U"+1l.2V(1A)).1r(0<=1A&&1A<=3z?-4:-6);X=a.X;1p(X--){1u=a[X];V(1u<10){a[X]="0"+1u}}14 1A+"-"+a.1r(0,2).2Q("-")+"T"+a.1r(2).2Q(":")+"."+("3w"+13.4t()).1r(-3)+"Z"}}W P=27;4w{P=1c.17.1Y&&18 1c(2L).1Y()===1E&&18 1c(O).1Y().1S(2M)!==-1&&1c.17.1Y.1f({1J:15(){14 1L}})}4T(e){}V(!P){1c.17.1Y=15 1Y(a){W o=1G(13),2N=3u(o),2g;V(1F 2N==="2P"&&!3E(2N)){14 1E}2g=o.1J;V(1F 2g!="15"){1h 18 1k("1J 3G 1K 1N 3H")}14 2g.1f(o)}}V(!1c.1X||"1c.1X 1K 3J"){1c=15(d){15 1c(Y,M,D,h,m,s,a){W b=1a.X;V(13 39 d){W c=b==1&&1g(Y)===Y?18 d(1c.1X(Y)):b>=7?18 d(Y,M,D,h,m,s,a):b>=6?18 d(Y,M,D,h,m,s):b>=5?18 d(Y,M,D,h,m):b>=4?18 d(Y,M,D,h):b>=3?18 d(Y,M,D):b>=2?18 d(Y,M):b>=1?18 d(Y):18 d;c.2b=1c;14 c}14 d.1j(13,1a)}W e=18 1W("^"+"(\\\\d{4}|[+-]\\\\d{6})"+"(?:-(\\\\d{2})"+"(?:-(\\\\d{2})"+"(?:"+"T(\\\\d{2})"+":(\\\\d{2})"+"(?:"+":(\\\\d{2})"+"(?:(\\\\.\\\\d{1,}))?"+")?"+"("+"Z|"+"(?:"+"([-+])"+"(\\\\d{2})"+":(\\\\d{2})"+")"+")?)?)?)?"+"$");W f=[0,31,3W,3X,3Y,42,43,44,45,46,49,4a,3d];15 2s(a,b){W t=b>1?1:0;14 f[b]+1l.1H((a-4i+t)/4)-1l.1H((a-4n+t)/4o)+1l.1H((a-4p+t)/4r)+3d*(a-3b)}15 3a(t){14 1m(18 d(3b,0,1,0,0,0,t))}1v(W g 1n d){1c[g]=d[g]}1c.2c=d.2c;1c.36=d.36;1c.17=d.17;1c.17.2b=1c;1c.1X=15 1X(a){W b=e.32(a);V(b){W c=1m(b[1]),1z=1m(b[2]||1)-1,2r=1m(b[3]||1)-1,34=1m(b[4]||0),2q=1m(b[5]||0),2p=1m(b[6]||0),2o=1l.1H(1m(b[7]||0)*30),38=54(b[4]&&!b[8]),2D=b[9]==="-"?1:-1,2Z=1m(b[10]||0),2Y=1m(b[11]||0),1o;V(34<(2q>0||2p>0||2o>0?24:25)&&2q<2a&&2p<2a&&2o<30&&1z>-1&&1z<12&&2Z<24&&2Y<2a&&2r>-1&&2r<2s(c,1z+1)-2s(c,1z)){1o=((2s(c,1z)+2r)*24+34+2Z*2D)*2a;1o=((1o+2q+2Y*2D)*2a+2p)*30+2o;V(38){1o=3a(1o)}V(-3e<=1o&&1o<=3e){14 1o}}14 2L}14 d.1X.1j(13,1a)};14 1c}(1c)}V(!1c.2c){1c.2c=15 2c(){14(18 1c).3O()}}V(!1m.17.1P||3Q-5.1P(3)!=="0.3w"||.9.1P(0)==="0"||1.3R.1P(2)!=="1.25"||3S.1P(0)!=="3T"){(15(){W b,29,1I,i;b=3h;29=6;1I=[0,0,0,0,0,0];15 1O(n,c){W i=-1;1p(++i<29){c+=n*1I[i];1I[i]=c%b;c=1l.1H(c/b)}}15 2h(n){W i=29,c=0;1p(--i>=0){c+=1I[i];1I[i]=1l.1H(c/n);c=c%n*b}}15 1y(){W i=29;W s="";1p(--i>=0){V(s!==""||i===0||1I[i]!==0){W t=1g(1I[i]);V(s===""){s=t}1s{s+="3Z".1r(0,7-t.X)+t}}}14 s}15 1R(x,n,a){14 n===0?a:n%2===1?1R(x,n-1,a*x):1R(x*x,n/2,a)}15 3l(x){W n=0;1p(x>=3m){n+=12;x/=3m}1p(x>=2){n+=1;x/=2}14 n}1m.17.1P=15(a){W f,x,s,m,e,z,j,k;f=1m(a);f=f!==f?0:1l.1H(f);V(f<0||f>20){1h 18 3F("1m.1P 2k 2f 47 2P 2e 48")}x=1m(13);V(x!==x){14"2L"}V(x<=-3q||x>=3q){14 1g(x)}s="";V(x<0){s="-";x=-x}m="0";V(x>1e-21){e=3l(x*1R(2,3r,1))-3r;z=e<0?x*1R(2,-e,1):x/1R(2,e,1);z*=4b;e=52-e;V(e>0){1O(0,z);j=f;1p(j>=7){1O(3h,0);j-=7}1O(1R(10,j,1),0);j=e-1;1p(j>=23){2h(1<<23);j-=23}2h(1<<j);1O(1,1);2h(2);m=1y()}1s{1O(0,z);1O(1<<-e,0);m=1y()+"0.4d".1r(2,2+f)}}V(f>0){k=m.X;V(k<=f){m=s+"0.4e".1r(0,f-k+2)+m}1s{m=s+m.1r(0,k-f)+"."+m.1r(k-f)}}1s{m=s+m}14 m}})()}W Q=1g.17.1i;V("3t".1i(/(?:3t)*/).X!==2||".".1i(/(.?)(.?)/).X!==4||"4h".1i(/(s)*/)[1]==="t"||"".1i(/.?/).X||".".1i(/()()/).X>1){(15(){W e=/()??/.32("")[1]===1q 0;1g.17.1i=15(a,b){W c=13;V(a===1q 0&&b===0)14[];V(1G.17.1y.1f(a)!=="[1d 1W]"){14 Q.1j(13,1a)}W d=[],2O=(a.4j?"i":"")+(a.4k?"m":"")+(a.4l?"x":"")+(a.4m?"y":""),1U=0,a=18 1W(a.3v,2O+"g"),2J,1t,1T,2I;c+="";V(!e){2J=18 1W("^"+a.3v+"$(?!\\\\s)",2O)}b=b===1q 0?-1>>>0:b>>>0;1p(1t=a.32(c)){1T=1t.2z+1t[0].X;V(1T>1U){d.1D(c.1r(1U,1t.2z));V(!e&&1t.X>1){1t[0].2G(2J,15(){1v(W i=1;i<1a.X-2;i++){V(1a[i]===1q 0){1t[i]=1q 0}}})}V(1t.X>1&&1t.2z<c.X){19.17.1D.1j(d,1t.1r(1))}2I=1t[0].X;1U=1T;V(d.X>=b){2S}}V(a.1T===1t.2z){a.1T++}}V(1U===c.X){V(2I||!a.3x("")){d.1D("")}}1s{d.1D(c.1r(1U))}14 d.X>b?d.1r(0,b):d}})()}1s V("0".1i(1q 0,0).X){1g.17.1i=15(a,b){V(a===1q 0&&b===0)14[];14 Q.1j(13,1a)}}V("".2w&&"4x".2w(-1)!=="b"){W R=1g.17.2w;1g.17.2w=15(a,b){14 R.1f(13,a<0?(a=13.X+a)<0?0:a:a,b)}}W S="	\\n\\f\\r \\4z\\4A\\4B\\4C\\4D\\4E\\4F"+"\\4G\\4H\\4I\\4J\\4K\\4L\\4M\\4N\\4O\\4P\\4Q"+"\\4R\\4S";V(!1g.17.28||S.28()){S="["+S+"]";W T=18 1W("^"+S+S+"*"),3A=18 1W(S+S+"*$");1g.17.28=15 28(){V(13===1q 0||13===1E){1h 18 1k("3B\'t 3C "+13+" 3D 1d")}14 1g(13).2G(T,"").2G(3A,"")}}V(2j(S+"4Z")!==8||2j(S+"50")!==22){2j=15(c){W d=/^0[51]/;14 15 53(a,b){a=1g(a).28();V(!+b){b=d.3x(a)?16:10}14 c(a,b)}}(2j)}15 2T(n){n=+n;V(n!==n){n=0}1s V(n!==0&&n!==1/0&&n!==-(1/0)){n=(n>0||-1)*1l.1H(1l.2V(n))}14 n}15 2C(a){W b=1F a;14 a===1E||b==="55"||b==="56"||b==="2P"||b==="57"}15 3u(a){W b,1V,1y;V(2C(a)){14 a}1V=a.1V;V(1F 1V==="15"){b=1V.1f(a);V(2C(b)){14 b}}1y=a.1y;V(1F 1y==="15"){b=1y.1f(a);V(2C(b)){14 b}}1h 18 1k}W U=15(o){V(o==1E){1h 18 1k("3B\'t 3C "+o+" 3D 1d")}14 1G(o)}});',62,318,'|||||||||||||||||||||||||||||||||||||||||||||||||||||||||if|var|length||||||this|return|function||prototype|new|Array|arguments|self|Date|object||call|String|throw|split|apply|TypeError|Math|Number|in|result|while|void|slice|else|match|value|for|Function|bind|toString|month|year|args|thisp|push|null|typeof|Object|floor|data|toISOString|is|true|unshift|not|multiply|toFixed|splice|pow|indexOf|lastIndex|lastLastIndex|valueOf|RegExp|parse|toJSON|reduceRight||||||||false|trim|size|60|constructor|now|reduce|of|with|toISO|divide|Empty|parseInt|called|lastIndexOf|map|filter|millisecond|second|minute|day|dayFromMonth|every|some|forEach|substr|empty|array|index|no|initial|isPrimitive|signOffset|isArray|define|replace|lengthBefore|lastLength|separator2|makeArray|NaN|negativeYearString|tv|flags|number|join|do|break|toInteger|on|abs|keys|concat|minuteOffset|hourOffset|1e3||exec|dontEnums|hour|propertyIsEnumerable|UTC|dontEnumsLength|isLocalTime|instanceof|toUTC|1970|isObject|365|864e13|non|skipProto|1e7|max|binder|skipConstructor|log|4096|__defineGetter__|exports|hasProtoEnumBug|1e21|69|addElementsCount|ab|toPrimitive|source|000|test|hasOwnProperty|9999|trimEndRegexp|can|convert|to|isFinite|RangeError|property|callable|XXX|buggy|getUTCDate|getUTCHours|getUTCMinutes|getUTCSeconds|getTime|incompatible|8e|255|0xde0b6b3a7640080|1000000000000000128|00000|properlyBoxed|59|90|120|0000000|returnExports|module|151|181|212|243|273|invalid|decimals|304|334|4503599627370496|621987552e5|00000000000000000000|0000000000000000000|toLocaleString|000001|tesst|1969|ignoreCase|multiline|extended|sticky|1901|100|1601|amd|400|foo|getUTCMilliseconds|isPrototypeOf|min|try|0b|__lookupSetter__|xa0|u1680|u180e|u2000|u2001|u2002|u2003|u2004|u2005|u2006|u2007|u2008|u2009|u200a|u202f|u205f|u3000|u2028|u2029|ufeff|catch|__lookupGetter__|finite|getUTCFullYear|getUTCMonth|__defineSetter__|08|0x16|xX||parseIntES5|Boolean|undefined|boolean|string'.split('|'),0,{}))
//TouchSwipe-Jquery-Plugin
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(7(a){4(2p 26==="7"&&26.31&&26.31.1S){26(["4o"],a)}13{a(1S)}}(7(f){8 p="1T",o="2s",e="4s",x="4f",c="3r",z="4a",m="4j",s="3s",l="18",t="4g",A="1h",j="2N",b="2M",y="1E",D="4p",u="4z",i="3N",r=10,g="1i",k="4k",h="1a",q="4u",a="4w"3r 1U,v=1U.28.2T&&!1U.28.2J,d=1U.28.2J||1U.28.2T,B="4h";8 n={1q:1,2d:4q,2o:11,2D:20,2w:11,2P:3K,2y:3Q,2B:4e,18:11,1L:11,1K:11,1I:11,1G:11,1u:11,1R:11,1N:11,1B:11,2r:11,1h:11,2f:11,2e:11,1E:11,1y:1e,1z:14,1o:"3s",36:1e,2V:"3R, 3S, 3T, 3X, 42, a, .48"};f.1v.18=7(G){8 F=f(1d),E=F.1H(B);4(E&&2p G==="4i"){4(E[G]){6 E[G].37(1d,4l.4m.4n.19(2Q,1))}13{f.2h("4r "+G+" 2U 2i 2Z 2n 1S.18")}}13{4(!E&&(2p G==="4x"||!G)){6 w.37(1d,2Q)}}6 F};f.1v.18.34=n;f.1v.18.4B={4M:g,50:k,3t:h,3v:q};f.1v.18.3w={3x:p,3y:o,3z:e,3A:x,3B:c,3C:z};f.1v.18.3D={3E:m,3F:D,3G:u,3H:s};f.1v.18.1q={3I:1,3J:2,3L:3,3M:i};7 w(E){4(E&&(E.1o===1f&&(E.18!==1f||E.1u!==1f))){E.1o=m}4(E.2r!==1f&&E.1h===1f){E.1h=E.2r}4(!E){E={}}E=f.3O({},f.1v.18.34,E);6 1d.3P(7(){8 G=f(1d);8 F=G.1H(B);4(!F){F=3d C(1d,E);G.1H(B,F)}})}7 C(n,w){8 y=(a||d||!w.36),J=y?(d?(v?"51":"3U"):"3V"):"3W",23=y?(d?(v?"3Y":"3Z"):"40"):"41",U=y?(d?(v?"43":"44"):"46"):"47",S=y?11:"49",1Q=(d?(v?"4b":"4c"):"4d");8 16=0,17=11,15=0,1s=0,1r=0,G=1,1n=0,1m=0,M=11;8 9=f(n);8 Z="1i";8 W=0;8 12=11;8 T=0,1W=0,1X=0,21=0,N=0;8 1Y=11,27=11;4y{9.1w(J,22);9.1w(1Q,1F)}4C(4F){f.2h("4G 2i 4I "+J+","+1Q+" 2n 1S.18")}1d.4J=7(){9.1w(J,22);9.1w(1Q,1F);6 9};1d.4L=7(){2l();6 9};1d.4Q=7(){2l();9.1H(B,11);6 9};1d.4R=7(a,b){4(w[a]!==1f){4(b===1f){6 w[a]}13{w[a]=b}}13{f.2h("4T "+a+" 2U 2i 2Z 2n 1S.18.4Z")}6 11};7 22(b){4(2F()){6}4(f(b.1g).3u(w.2V,9).1D>0){6}8 c=b.1J?b.1J:b;8 d,2O=a?c.1k[0]:c;Z=g;4(a){W=c.1k.1D}13{b.1C()}16=0;17=11;1m=11;15=0;1s=0;1r=0;G=1;1n=0;12=2R();M=2S();R();4(!a||(W===w.1q||w.1q===i)||1M()){24(0,2O);T=1p();4(W==2){24(1,c.1k[1]);1s=1r=25(12[0].1i,12[1].1i)}4(w.1u||w.1B){d=O(c,Z)}}13{d=14}4(d===14){Z=q;O(c,Z);6 d}13{4(w.1E){27=32(f.33(7(){9.1b("1E",[c.1g]);4(w.1E){d=w.1E.19(9,c,c.1g)}},1d),w.2y)}1V(1e)}6 11}7 1Z(b){8 c=b.1J?b.1J:b;4(Z===h||Z===q||2j()){6}8 d,39=a?c.1k[0]:c;8 e=2k(39);1W=1p();4(a){W=c.1k.1D}4(w.1E){1P(27)}Z=k;4(W==2){4(1s==0){24(1,c.1k[1]);1s=1r=25(12[0].1i,12[1].1i)}13{2k(c.1k[1]);1r=25(12[0].1a,12[1].1a);1m=3k(12[0].1a,12[1].1a)}G=3l(1s,1r);1n=1c.29(1s-1r)}4((W===w.1q||w.1q===i)||!a||1M()){17=2G(e.1i,e.1a);2H(b,17);16=2I(e.1i,e.1a);15=2m();2K(17,16);4(w.1u||w.1B){d=O(c,Z)}4(!w.1y||w.1z){8 f=1e;4(w.1z){8 g=2L(1d);f=E(e.1a,g)}4(!w.1y&&f){Z=2a(k)}13{4(w.1z&&!f){Z=2a(h)}}4(Z==q||Z==h){O(c,Z)}}}13{Z=q;O(c,Z)}4(d===14){Z=q;O(c,Z)}}7 L(b){8 c=b.1J;4(a){4(c.1k.1D>0){F();6 1e}}4(2j()){W=21}1W=1p();15=2m();4(2b()||!2c()){Z=q;O(c,Z)}13{4(w.1y||(w.1y==14&&Z===k)){b.1C();Z=h;O(c,Z)}13{4(!w.1y&&2q()){Z=h;1A(c,Z,A)}13{4(Z===k){Z=q;O(c,Z)}}}}1V(14);6 11}7 1F(){W=0;1W=0;T=0;1s=0;1r=0;G=1;R();1V(14)}7 K(a){8 b=a.1J;4(w.1z){Z=2a(h);O(b,Z)}}7 2l(){9.1t(J,22);9.1t(1Q,1F);9.1t(23,1Z);9.1t(U,L);4(S){9.1t(S,K)}1V(14)}7 2a(a){8 b=a;8 c=2t();8 d=2c();8 e=2b();4(!c||e){b=q}13{4(d&&a==k&&(!w.1y||w.1z)){b=h}13{4(!d&&a==h&&w.1z){b=q}}}6 b}7 O(c,d){8 e=1f;4(I()||V()){e=1A(c,d,l)}13{4((P()||1M())&&e!==14){e=1A(c,d,t)}}4(2W()&&e!==14){e=1A(c,d,j)}13{4(2X()&&e!==14){e=1A(c,d,b)}13{4(2Y()&&e!==14){e=1A(c,d,A)}}}4(d===q){1F(c)}4(d===h){4(a){4(c.1k.1D==0){1F(c)}}13{1F(c)}}6 e}7 1A(a,c,d){8 e=1f;4(d==l){9.1b("1u",[c,17||11,16||0,15||0,W,12]);4(w.1u){e=w.1u.19(9,a,c,17||11,16||0,15||0,W,12);4(e===14){6 14}}4(c==h&&2u()){9.1b("18",[17,16,15,W,12]);4(w.18){e=w.18.19(9,a,17,16,15,W,12);4(e===14){6 14}}2v(17){1l p:9.1b("1L",[17,16,15,W,12]);4(w.1L){e=w.1L.19(9,a,17,16,15,W,12)}1j;1l o:9.1b("1K",[17,16,15,W,12]);4(w.1K){e=w.1K.19(9,a,17,16,15,W,12)}1j;1l e:9.1b("1I",[17,16,15,W,12]);4(w.1I){e=w.1I.19(9,a,17,16,15,W,12)}1j;1l x:9.1b("1G",[17,16,15,W,12]);4(w.1G){e=w.1G.19(9,a,17,16,15,W,12)}1j}}}4(d==t){9.1b("1B",[c,1m||11,1n||0,15||0,W,G,12]);4(w.1B){e=w.1B.19(9,a,c,1m||11,1n||0,15||0,W,G,12);4(e===14){6 14}}4(c==h&&2x()){2v(1m){1l c:9.1b("1R",[1m||11,1n||0,15||0,W,G,12]);4(w.1R){e=w.1R.19(9,a,1m||11,1n||0,15||0,W,G,12)}1j;1l z:9.1b("1N",[1m||11,1n||0,15||0,W,G,12]);4(w.1N){e=w.1N.19(9,a,1m||11,1n||0,15||0,W,G,12)}1j}}}4(d==A){4(c===q||c===h){1P(1Y);1P(27);4(Y()&&!H()){N=1p();1Y=32(f.33(7(){N=11;9.1b("1h",[a.1g]);4(w.1h){e=w.1h.19(9,a,a.1g)}},1d),w.2B)}13{N=11;9.1b("1h",[a.1g]);4(w.1h){e=w.1h.19(9,a,a.1g)}}}}13{4(d==j){4(c===q||c===h){1P(1Y);N=11;9.1b("2N",[a.1g]);4(w.2f){e=w.2f.19(9,a,a.1g)}}}13{4(d==b){4(c===q||c===h){1P(1Y);N=11;9.1b("2M",[a.1g]);4(w.2e){e=w.2e.19(9,a,a.1g)}}}}}6 e}7 2c(){8 a=1e;4(w.2d!==11){a=16>=w.2d}6 a}7 2b(){8 a=14;4(w.2o!==11&&17!==11){a=(2z(17)-16)>=w.2o}6 a}7 35(){4(w.2D!==11){6 1n>=w.2D}6 1e}7 2t(){8 a;4(w.2w){4(15>=w.2w){a=14}13{a=1e}}13{a=1e}6 a}7 2H(a,b){4(w.1o===m||1M()){a.1C()}13{8 c=w.1o===s;2v(b){1l p:4((w.1L&&c)||(!c&&w.1o!=D)){a.1C()}1j;1l o:4((w.1K&&c)||(!c&&w.1o!=D)){a.1C()}1j;1l e:4((w.1I&&c)||(!c&&w.1o!=u)){a.1C()}1j;1l x:4((w.1G&&c)||(!c&&w.1o!=u)){a.1C()}1j}}}7 2x(){8 a=2A();8 b=X();8 c=35();6 a&&b&&c}7 1M(){6!!(w.1B||w.1R||w.1N)}7 P(){6!!(2x()&&1M())}7 2u(){8 a=2t();8 b=2c();8 c=2A();8 d=X();8 e=2b();8 f=!e&&d&&c&&b&&a;6 f}7 V(){6!!(w.18||w.1u||w.1L||w.1K||w.1I||w.1G)}7 I(){6!!(2u()&&V())}7 2A(){6((W===w.1q||w.1q===i)||!a)}7 X(){6 12[0].1a.x!==0}7 2q(){6!!(w.1h)}7 Y(){6!!(w.2f)}7 38(){6!!(w.2e)}7 Q(){4(N==11){6 14}8 a=1p();6(Y()&&((a-N)<=w.2B))}7 H(){6 Q()}7 3a(){6((W===1||!a)&&(4t(16)||16<w.2d))}7 3b(){6((15>w.2y)&&(16<r))}7 2Y(){6!!(3a()&&2q())}7 2W(){6!!(Q()&&Y())}7 2X(){6!!(3b()&&38())}7 F(){1X=1p();21=4v.1k.1D+1}7 R(){1X=0;21=0}7 2j(){8 a=14;4(1X){8 b=1p()-1X;4(b<=w.2P){a=1e}}6 a}7 2F(){6!!(9.1H(B+"3c")===1e)}7 1V(a){4(a===1e){9.1w(23,1Z);9.1w(U,L);4(S){9.1w(S,K)}}13{9.1t(23,1Z,14);9.1t(U,L,14);4(S){9.1t(S,K,14)}}9.1H(B+"3c",a===1e)}7 24(a,b){8 c=b.1x!==1f?b.1x:0;12[a].1x=c;12[a].1i.x=12[a].1a.x=b.3e||b.3f;12[a].1i.y=12[a].1a.y=b.3g||b.3h;6 12[a]}7 2k(a){8 b=a.1x!==1f?a.1x:0;8 c=3i(b);c.1a.x=a.3e||a.3f;c.1a.y=a.3g||a.3h;6 c}7 3i(a){3j(8 b=0;b<12.1D;b++){4(12[b].1x==a){6 12[b]}}}7 2R(){8 a=[];3j(8 b=0;b<=5;b++){a.4D({1i:{x:0,y:0},1a:{x:0,y:0},1x:0})}6 a}7 2K(a,b){b=1c.4E(b,2z(a));M[a].2C=b}7 2z(a){4(M[a]){6 M[a].2C}6 1f}7 2S(){8 a={};a[p]=1O(p);a[o]=1O(o);a[e]=1O(e);a[x]=1O(x);6 a}7 1O(a){6{4H:a,2C:0}}7 2m(){6 1W-T}7 25(a,b){8 c=1c.29(a.x-b.x);8 d=1c.29(a.y-b.y);6 1c.2E(1c.3m(c*c+d*d))}7 3l(a,b){8 c=(b/a)*1;6 c.4K(2)}7 3k(){4(G<1){6 z}13{6 c}}7 2I(a,b){6 1c.2E(1c.3m(1c.3n(b.x-a.x,2)+1c.3n(b.y-a.y,2)))}7 3o(a,b){8 c=a.x-b.x;8 d=b.y-a.y;8 e=1c.4N(d,c);8 f=1c.2E(e*4O/1c.4P);4(f<0){f=3p-1c.29(f)}6 f}7 2G(a,b){8 c=3o(a,b);4((c<=45)&&(c>=0)){6 p}13{4((c<=3p)&&(c>=4S)){6 p}13{4((c>=3q)&&(c<=4U)){6 o}13{4((c>45)&&(c<3q)){6 x}13{6 e}}}}}7 1p(){8 a=3d 4V();6 a.4W()}7 2L(a){a=f(a);8 b=a.4X();8 c={1T:b.1T,2s:b.1T+a.4Y(),2g:b.2g,30:b.2g+a.4A()};6 c}7 E(a,b){6(a.x>b.1T&&a.x<b.2s&&a.y>b.2g&&a.y<b.30)}}}));',62,312,'||||if||return|function|var|bi||||||||||||||||||||||||||||||||||||||||||||||||||||||null|bj|else|false|ab|bh|aP|swipe|call|end|trigger|Math|this|true|undefined|target|tap|start|break|touches|case|aJ|aq|allowPageScroll|at|fingers|aZ|a1|unbind|swipeStatus|fn|bind|identifier|triggerOnTouchEnd|triggerOnTouchLeave|aF|pinchStatus|preventDefault|length|hold|a9|swipeDown|data|swipeUp|originalEvent|swipeRight|swipeLeft|aX|pinchOut|aw|clearTimeout|aD|pinchIn|jQuery|left|window|ao|a2|a5|bk|a3||ad|aN|ay|ai|au|define|af|navigator|abs|aC|ba|an|threshold|longTap|doubleTap|top|error|not|am|aH|aK|aM|on|cancelThreshold|typeof|a6|click|right|aA|aV|switch|maxTimeThreshold|a8|longTapThreshold|aT|aO|doubleTapThreshold|distance|pinchThreshold|round|aB|aL|al|aS|pointerEnabled|aI|aY|longtap|doubletap|bb|fingerReleaseThreshold|arguments|aj|aa|msPointerEnabled|does|excludedElements|aG|ap|ah|exist|bottom|amd|setTimeout|proxy|defaults|ae|fallbackToMouseEvents|apply|aU|bc|ax|a0|_intouch|new|pageX|clientX|pageY|clientY|ac|for|ar|a7|sqrt|pow|aE|360|135|in|auto|PHASE_END|closest|PHASE_CANCEL|directions|LEFT|RIGHT|UP|DOWN|IN|OUT|pageScroll|NONE|HORIZONTAL|VERTICAL|AUTO|ONE|TWO|250|THREE|ALL|all|extend|each|500|label|button|input|pointerdown|touchstart|mousedown|select|MSPointerMove|pointermove|touchmove|mousemove|textarea|MSPointerUp|pointerup||touchend|mouseup|noSwipe|mouseleave|out|MSPointerCancel|pointercancel|touchcancel|200|down|pinch|TouchSwipe|string|none|move|Array|prototype|slice|jquery|horizontal|75|Method|up|isNaN|cancel|event|ontouchstart|object|try|vertical|outerHeight|phases|catch|push|max|ak|events|direction|supported|enable|toFixed|disable|PHASE_START|atan2|180|PI|destroy|option|315|Option|225|Date|getTime|offset|outerWidth|options|PHASE_MOVE|MSPointerDown'.split('|'),0,{}))

function include(scriptUrl) {
    document.write('<script src="' + path + '/' + scriptUrl + '"></script>');
}

var isIE = (navigator.appVersion.indexOf("MSIE") != -1 || !!(navigator.userAgent.match(/Trident/))) ? true : false;


/* Fancybox
 ========================================================*/
;
(function ($) {
    var o = $('a[data-fancybox="fancybox"]');
    var vm = $('a[data-fancybox="fancybox2"]');
    if (o.length || vm.length) {
        include('js/jquery.fancybox.pack.js');
        include('js/jquery.fancybox-thumbs.js');
        include('js/jquery.pep.js');

        $(document).ready(function () {
            o.fancybox({
                padding: 0,
                margin: 0,
                loop: true,
                openSpeed: 500,
                closeSpeed: 500,
                nextSpeed: 500,
                prevSpeed: 500,
                wrapCSS : 'photo-class',
                afterLoad: function () {
                    $('.fancybox-inner').click(function () {
                        if (click == true) {
                            $('body').toggleClass('fancybox-full');
                        }
                    })
                },
                beforeShow: function () {
                    $('body').addClass('fancybox-lock');
                },
                afterClose: function () {
                    $('body').removeClass('fancybox-lock');
                },
                tpl: {
                    image: '<div class="fancybox-image" style="background-image: url(\'{href}\');"/>',
                    iframe: '<span class="iframe-before"/><iframe id="fancybox-frame{rnd}" width="60%" height="60%" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0"' + ($.browser.msie ? ' allowtransparency="true"' : '') + '/>'
                },
                helpers: {
                    title: null,
                    thumbs: {
                        height: 50,
                        width: 80
                    },
                    overlay: {
                        css: {
                            'background': '#191919'
                        }
                    }
                }
            });
        });
    }
})(jQuery);



/* Radial Progress Bar
 ========================================================*/
;
(function ($) {
    var o = $('.radial-progress');
    if (o.length) {
        include('js/progressbar.min.js');
        $(window).load(function () {
            if (isIE) {
                var width = o.width();
                if (o.find('svg').length) {
                    o.find('svg').height(width);
                } else {
                    setInterval(function () {
                        o.find('svg').height(width);
                    }, 300)
                }
            }
        });
    }
})(jQuery);


/* Progress Bars
 ========================================================*/
;
(function ($) {
    var o = $('[class*="progress"]');
    if (o.length) {
        function progressResize() {
            //$('.progress-vertical .text-inner').css('height', $('.progress-vertical').height());
            $('.progress-horizontal .text-inner').css('width', $('.progress-horizontal').width());
        }

        $(window).on('load', progressResize);
        $(window).resize($.throttle(200, progressResize));
    }
})(jQuery);

/* AutoSize
 ========================================================*/
;
(function ($) {
    var o = $('textarea');
    if (o.length) {
        include('js/autosize.min.js');
        $(window).load(function () {
            autosize(o);
        });
    }
})(jQuery);


/*  Modal Window
 ========================================================*/
;
(function ($) {
    var o = $('[href="#modal"]');
    if (o.length) {
        $(document).ready(function () {

            o.click(function (e) {
                $('#modal').modal({show: true, backdrop: false});
                e.preventDefault()
            });

            $('#modal button.modalClose').click(function (e) {
                $('#modal').modal('hide');
                e.preventDefault()
            });
            $('.modal-hide').click(function (e) {
                $('#modal').modal('hide')
            });
        });

    }
})(jQuery);


/*  IE9 Placeholders
 ========================================================*/
;
(function ($) {
    $.support.placeholder = ('placeholder' in document.createElement('input'));
    if (!$.support.placeholder) {
        $('input[placeholder], textarea[placeholder]').each(function (n) {
            $input = $(this);
            $(this)
                .attr('autocomplete', 'off')
                .addClass('ie_placeholder')
                .bind('keydown keyup click blur focus change paste cut', function (e) {
                    $(this).delay(10)
                        .queue(function (n) {
                            if ($(this).val() != '') {
                                $(this)
                                    .parent()
                                    .find('>.form_placeholder')
                                    .hide();
                            }
                            else {
                                $(this)
                                    .parent()
                                    .find('>.form_placeholder')
                                    .show();
                            }
                            n();
                        });
                })
            var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttribute('class', 'form_placeholder');
            var svgNS = svg.namespaceURI,
                text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', '0');
            text.setAttribute('y', parseInt($input.css('paddingTop')) + parseInt($input.css('borderTopWidth')) + parseInt($input.css('fontSize')) + 1);
            text.setAttribute('fill', $input.css('color'));
            text.textContent = $input.attr('placeholder');
            svg.appendChild(text);
            $input.parent()[0].appendChild(svg);
            $input.parent()
                .addClass('ie_placeholder_controls')
                .find('>.form_placeholder')
                .css({
                    paddingLeft: $(this).parent().find('>*[placeholder]').css('paddingLeft'),
                    borderTopWidth: 0,
                    borderBottomWidth: 0,
                    borderLeftWidth: $(this).parent().find('>*[placeholder]').css('borderLeftWidth'),
                    borderRightWidth: 0,
                    fontSize: $(this).parent().find('>*[placeholder]').css('fontSize'),
                    textAlign: $(this).parent().find('>*[placeholder]').css('textAlign')
                })
            if ($input.val() && $input.val() != '') {
                $input.parent().find('>.form_placeholder').hide();
            }
        })
    }
})(jQuery);


/*  Checkbox replacement
========================================================*/
;
(function ($) {
    var o = $('input[type=checkbox]');
    if (o.length && !$('body').hasClass('.com_config')) {
        o.each(function (i) {
            if ($(this).parent().not('span.checkbox')) {
                if (!$(this).attr("id")) {
                    $(this).attr({id: 'checkbox' + i}).wrap('<span class="checkbox"/>').after('<label class="checkbox_inner" for="checkbox' + i + '" />')
                } else {
                    $(this).wrap('<span class="checkbox"/>').after('<label class="checkbox_inner" for="' + $(this).attr("id") + '" />')
                }
            }
        })
    }

})(jQuery);

/*  Radio Buttons replacement
========================================================*/
;
(function ($) {
    var o = $('input[type=radio]');
    if (o.length && !$('body').hasClass('.com_config')) {
        o.each(function () {
            if ($(this).parent().not('span.radio')) {
                if (!$(this).attr("id")) {
                    $(this).attr({id: 'radio' + i}).wrap('<span class="radio"/>').after('<label class="radio_inner" for="radio' + i + '" />')
                } else {
                    $(this).wrap('<span class="radio"/>').after('<label class="radio_inner" for="' + $(this).attr("id") + '" />')
                }
            }
        })
    }
})(jQuery);


/*  ToTop
 ========================================================*/
;
(function ($) {
    var o = $("#back-top");
    if (isMobile !== 'true') {

        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        var $scrollEl = ($.browser.mozilla || $.browser.msie) ? $('html') : $('body');

        o.find('a').click(function () {
            $scrollEl.animate({scrollTop: 0}, 400);
            return false;
        });
    }
})(jQuery);

jQuery(function($) {

    $(document).ready(function () {
        $(':header').not('[class*=heading-style-]').not('.alert-heading').each(function () {
            $(this).addClass('heading-style-' + $(this).prop('tagName').replace(/^\D+/g, ''));
        });
        $('.buttonBar-right .btn[type="reset"] , .mod_tm_ajax_contact_form_btn[type="reset"]').addClass('cancel');
        // $('.btn').wrapInner('<span></span>');
        
        // Turn radios into btn-group
        $('.radio.btn-group label').addClass('btn');
        $(".btn-group label:not(.active)").click(function () {
            var a = $(this);
            var b = $('#' + a.attr('for'));
            if (!b.prop('checked')) {
                a.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
                if (b.val() == '') {
                    a.addClass('active btn-primary')
                } else if (b.val() == 0) {
                    a.addClass('active btn-danger')
                } else {
                    a.addClass('active btn-success')
                }
                b.prop('checked', true)
            }
        });
        $('fieldset#jform_published label[for*=jform_published]').each(function () {
            $(this).attr('title', $(this).text()).tooltip();
        })
        $('fieldset#jform_published label[for*=jform_published]').wrapInner('<span/>');
        $('fieldset#jform_published label[for=jform_published0]').prepend('<i class="fa fa-check"/> ');
        $('fieldset#jform_published label[for=jform_published1]').prepend('<i class="fa fa-times-circle"/> ');
        $('fieldset#jform_published label[for=jform_published2]').prepend('<i class="fa fa-trash"/> ');
        $(".btn-group input[checked]").each(function () {
            if ($(this).val() == '') {
                $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
            } else if ($(this).val() == 0) {
                $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
            } else {
                $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
            }
        });

        $('.accordion-body.collapse').on('shown', function (event) {
            $(this).parent('.accordion-group').find('.accordion-toggle').addClass('selected')
        })
        $('.accordion-body.collapse').on('hidden', function (event) {
            $(this).parent('.accordion-group').find('.accordion-toggle').removeClass('selected')
        })

        /*Pagination Active Button*/
        $('div.pagination ul li:not([class])').addClass('num');

        var iconTest = /icon-/i;
        var iconReplace = "fa fa-";

        function iconSet() {
            $('[class*="icon-"]').each(function () {
                iconClass = $(this).attr('class');
                var a = iconClass.replace(iconTest, iconReplace);
                $(this).addClass(a)
            })
        }

        iconSet()

        function setOffset() {
            $('.item__module, .item, .row-container, .categories-module, .archive-module, .tagspopular, .mod-login__aside, .form-vertical, .mod-search__aside, .contact_details_item, .contact_misc, .contact_form, .contact_map, h1, h2, h3, h4, h5, h6').each(function (i) {
                var a = ($(this).offset().top - $(window).scrollTop()) < $(window).height(), scrollUp = ($(this).offset().top - $(window).scrollTop()) > -$(this).height();
                if (a && scrollUp) {
                    $(this).addClass('visible-first');
                    //if ($('.radial-progress').length || $('[class*="progress"]').length) {
                    //    if ($(this).find('> .item_content [class*="progress"]').length != 0 && !$(this).hasClass('animated')) {
                    //        animateProgress($(this));
                    //    }
                    //}
                }
            })
        }

        setOffset()
        $(window).resize($.throttle(500, setOffset));
        $(window).scroll($.throttle(500, setOffset));

        var $scrollEl = ($.browser.mozilla || $.browser.msie) ? $('html') : $('body');

        // Calendar position fix
        if ($('#jform_profile_dob_img').length && $.browser.msie) {
            var h = $('#jform_profile_dob_img').offset().top - 202;
            $('head').append('<style> .calendar { top:' + h + 'px !important;}</style>')
        }

        if ($.browser.msie) {
            $('.lazy_container').each(function () {
                $(this).parent('a').not('.fancybox-thumb').attr({style: $(this).attr('style')}).parent('.img-intro__left, .img-intro__right').attr({style: $(this).attr('style')});
            })
        }
        $('select.kchecktask').bind('change', function (e) {
            ktarget = $(this).siblings('select[name=target]');
            if ($(this).val() == 'move') {
                ktarget.removeAttr('disabled').trigger("liszt:updated")
            } else {
                ktarget.attr('disabled', 'disabled').trigger("liszt:updated")
            }
        });

        ///* Animate Progress Bars
        // ========================================================*/
        //function animateProgress(el) {
        //    if (el.find('.radial-progress').length != 0 && el.find('.radial-progress').hasClass('draw')) {
        //        $.each(el.find('.radial-progress'), function () {
        //            var progress = $(this).data('progress');
        //            var circle = new ProgressBar.Circle('.radial-progress.draw', {
        //                color: '#000',
        //                trailColor: '#000',
        //                fill: '#000',
        //                strokeWidth: 10,
        //                trailWidth: 0,
        //                duration: 1500,
        //                text: {
        //                    value: '0'
        //                },
        //                step: function (state, bar) {
        //                    bar.setText((bar.value() * 100).toFixed(0));
        //                }
        //            });
        //            circle.animate(progress / 100, function () {
        //            });
        //            $(this).removeClass('draw');
        //        })
        //    } else {
        //        el.addClass('animated');
        //        el.find('.text').each(function () {
        //            var progress = $(this);
        //            var percentage = Math.ceil($(this).parents('[class*="progress"]').attr('data-progress'));
        //            $({countNum: 0}).animate({countNum: percentage}, {
        //                duration: 2000,
        //                easing: 'linear',
        //                step: function () {
        //                    progress.text(Math.ceil(this.countNum) + '%');
        //                }
        //            });
        //        });
        //    }
        //}
    });


});