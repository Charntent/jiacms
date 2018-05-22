(function(){

	isIE = !-[1,];
	isIE6 = isIE && !window.XMLHttpRequest;

	var js=document.getElementsByTagName('script');
	jspath=js[js.length-1].src.substring(0,js[js.length-1].src.lastIndexOf("/")+1);

	init = function(c){

		if(typeof this == "object"){
			c.content = $(this).html();
		}else if(typeof c == "string"){
			tmp = c;
			c = {};
			c.content = tmp;
		};
		
		if(typeof c.fix == "undefined") c.fix = true;
		
		isiframe = (c.content.match(/^http:\/\/.+/) || c.content.match(/^\?.+/) || c.content. match(/^\/.+/) || c.content.match(/^[a-z]+\.[a-z]+.+/))!=null;

		config = {
			id: c.id,
			title: typeof c.title == "undefined" ? '\u63D0\u793A' : c.title,
			content: c.content,
			isiframe: typeof c.isiframe == "undefined" ? isiframe : c.isiframe,
			width: c.width,
			height: c.height,
			fix: c.fix,
			top: c.top,
			right: c.right,
			bottom: c.bottom,
			left: c.left,
			scroll: c.scroll,
			lock: c.lock,
			drag: c.drag,
			time: c.time,
			_close: c.close,
			closefn: c.closefn
		}

		return show(config);
	}

	$.doc = function(){
		dd = document.documentElement;
		db = document.body;
		dom = dd || db;
		return {
			width:  Math.max(dom.clientWidth, dom.scrollWidth),
			height: Math.max(dom.clientHeight, dom.scrollHeight),
			left: Math.max(dd.scrollLeft, db.scrollLeft),
			top: Math.max(dd.scrollTop, db.scrollTop)
		};
	};

	positionfix = function(fix,elem){
		isIE6 && $('body').css('backgroundAttachment') !== 'fixed' && $('html').css({
			backgroundImage: 'url(about:blank)',
			backgroundAttachment: 'fixed'
		});
		if(fix==true){
			if(isIE6){
				var left = parseInt(elem.style.left) - document.documentElement.scrollLeft,
					top = parseInt(elem.style.top) - document.documentElement.scrollTop;
				elem.style.position = 'absolute';
				elem.style.removeExpression('left');
				elem.style.removeExpression('top');
				elem.style.setExpression('left', 'eval((document.documentElement).scrollLeft + ' + left + ') + "px"');
				elem.style.setExpression('top', 'eval((document.documentElement).scrollTop + ' + top + ') + "px"');
			}else{
				elem.style.position = 'fixed';
			}
		}else{
			if(isIE6){
				elem.style.position = 'absolute';
				elem.style.removeExpression('left');
				elem.style.removeExpression('top');
			}else{
				elem.style.position = 'absolute';	
			}
		}
	}

	clsSelect = window.getSelection ?
		function(){
			try{
				window.getSelection().removeAllRanges()
			}catch(_){};
		} :
		function(){
			try{
				document.selection.empty();
			}catch(_){};
	};

	$.fn.drag = function(on,fix){
		on = $(on);
		$elem = this;

		var down = function(event){
			var doc = $.doc();
			isDown = true;

			cache = function(){
				return {
					x: event.clientX,
					y: event.clientY,
					left: parseInt($elem[0].style.left),
					top: parseInt($elem[0].style.top),
					width: $elem[0].offsetWidth,
					height: $elem[0].offsetHeight,
					docLeft: doc.left,
					docTop: doc.top
				}
			}();
			document.body.setCapture && $elem[0].setCapture();
			$(document).bind('mousemove', move).bind('mouseup', up);
			regular  = setInterval(clsSelect, 200);
		}
		on.bind('mousedown', down);
		
		var move = function(event){
			if (isDown === false) return;
	
			$move = $elem;
			var doc = $.doc();
			var x = event.clientX,
				y = event.clientY,
				l = cache.left - cache.x + x - cache.docLeft + doc.left,
				t = cache.top - cache.y + y - cache.docTop + doc.top;
			
			limit = $elem.limit();
			if (l > limit.maxX) l = limit.maxX;
			if (l < limit.minX) l = limit.minX;
			if (t > limit.maxY) t = limit.maxY;
			if (t < limit.minY) t = limit.minY;
			
			$move.css({
				'left': l + 'px',
				'top': t + 'px'
			});
			
			positionfix(fix,$("#div_dialog")[0]);
		};
		
		var up = function(){
			isDown = false;
			$(document).unbind('mousemove', move).unbind('mouseup', up);
			document.body.releaseCapture && $elem[0].releaseCapture();
			clearInterval(regular);
		};
	}

	$.fn.limit = function(){
		var dom = document.documentElement || document.body;
		var doc = $.doc();
		var minX, minY, maxX, maxY, centerX, centerY;
		var winWidth = dom.clientWidth,
			winHeight = dom.clientHeight,
			docLeft = doc.left,
			docTop = doc.top,
			boxWidth = this[0].offsetWidth,
			boxHeight = this[0].offsetHeight;
		if (!isIE6) {
			minX = 0;
			maxX = winWidth - boxWidth;
			centerX = maxX / 2;
			minY = 0;
			maxY = winHeight - boxHeight;
			var hc =  winHeight * 0.382 - boxHeight / 2;
			centerY = (boxHeight < 4 * winHeight / 7) ?  hc : maxY / 2;
		} else {
			minX = docLeft;
			maxX = winWidth + minX - boxWidth;
			centerX = maxX / 2;
			minY = docTop;
			maxY = winHeight + minY - boxHeight;
			var hc = winHeight * 0.382 - boxHeight / 2 + minY;
			centerY =  (boxHeight < 4 * winHeight / 7) ? hc : (maxY + minY) / 2;
		};
		
		if(config.left) centerX = minX + parseInt(config.left);
		if(config.right) centerX = maxX - parseInt(config.right);
		if(config.bottom) centerY = maxY - parseInt(config.bottom);
		if(config.top) centerY = minY + parseInt(config.top);
		
		if (centerX < 0) centerX = 0;
		if (centerY < 0) centerY = 0;
		return {minX: minX, minY: minY, maxX: maxX, maxY: maxY, centerX: centerX, centerY: centerY};
	};

	show = function(config){

		if(typeof lastid == "undefined" || !lastid) lastid = config.id ? config.id : null; else return false;

		$("#div_dialog").remove();
		$("html").css("overflow","");

		var dom = document.documentElement || document.body;
		var mheight = dom.clientHeight;
		
		if(!config.width) config.width = 200;
		if(config.height){
			config.height = config.height.toString();
			if(config.height.lastIndexOf('%') === config.height.length - 1){
				config.height = (parseInt(mheight * config.height.split('%')[0] / 100)-80)+"px";
			}
		}
		
		template(config);

		if(config.title == null){
			$("#div_dialogtop").remove();
		}

		if(config._close == false){
			$("#div_dialogclose").remove();
		}

		if(config.isiframe==true){
			if(config.height){
				$("#div_dialog_iframe").css({height:config.height});
			}else{
				$("#div_dialog_iframe").load(function(){
					var ifm = $("#div_dialog_iframe")[0];
					try{
						var bHeight = ifm.contentWindow.document.body.scrollHeight;
						var dHeight = ifm.contentWindow.document.documentElement.scrollHeight;
						var height = Math.max(bHeight, dHeight);
					}catch (ex){}
					if(height){
						if(height>mheight-80){
							$("#div_dialog_iframe").css({height:(mheight-80)+"px"});
							$("html").css("overflow","hidden");
						}else{
							$("#div_dialog_iframe").css({height:height+"px"});
						}
					}
				})
			}
			
			$("#div_dialog_iframe").attr('src',config.content).load(function(){
				$("#div_ifloading").remove();
				$("#div_dialog_iframe").show();
				$(this).focus();
				limit = $("#div_dialog").limit();
				$("#div_dialog").css({top:limit.centerY+"px",left:limit.centerX+"px"});
				positionfix(config.fix,$("#div_dialog")[0]);
				config.time && setTimeout(function(){$.dialog.close();},config.time);
			});
			
		}else{
			if(config.height){
				if(parseInt(config.height)<$("#div_dialogcontent").height()) $("html").css("overflow","hidden");
				$("#div_dialogcontent").css({height:config.height,overflow:'auto'});
				limit = $("#div_dialog").limit();
				$("#div_dialog").css({top:limit.centerY+"px",left:limit.centerX+"px"});
			}else{
				var obj = $("#div_dialogcontent");
				var contentheight = mheight-70;
				obj.css("max-height",contentheight);
				if(isIE6){
					obj[0].style.removeExpression('height');
					obj[0].style.setExpression('height', 'eval(this.scrollHeight>'+contentheight+'?'+contentheight+':this.scrollHeight) + "px"');
				}
			}
			positionfix(config.fix,$("#div_dialog")[0]);
			config.time && setTimeout(function(){$.dialog.close();},config.time);
		}

		if(typeof config.drag == "undefined" || config.drag==true){
			$("#div_dialog").drag("#div_dialogtop",config.fix);
			$("#div_dialogtop").css("cursor","move");
		}

		if(!config.scroll){
			$("html").css("overflow","hidden");
		}

		if(config.lock){
			lock();
			$(window).bind('resize',lock);
		}

	}
	
	lock = function(){
		$("html").css("overflow","hidden");
		$("#div_lock").remove();
		$("body").append("<div id='div_lock' style='index-z:99998;background-color:#000;left:0px;top:0px;position:absolute;filter:alpha(opacity=30);opacity:0.3;'></div>");
		if(isIE6) $("#div_lock").append('<iframe src="about:blank" style="width:100%;height:100%;position:absolute;top:0;left:0;z-index:-1;filter:alpha(opacity=0)"></iframe>');
		var doc = $.doc();
		$("#div_lock").css({width:doc.width,height:doc.height}).bind('mousedown',function(){this.oncontextmenu = function(){return false;}});
	}

	template = function(config){
		var template = '<div id="div_dialog" style="position:fixed;_position:absolute;z-index:99999;">\
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="div_dialogtable">\
				  <tr>\
					<td class="div_border div_left_top"></td>\
					<td class="div_border div_top"></td>\
					<td class="div_border div_right_top"></td>\
				  </tr>\
				  <tr>\
					<td class="div_border div_left"></td>\
					<td class="div__center">\
						<div id="div_dialogtop"><div id="div_dialogtitle"></div><div id="div_dialogclose"><a href="javascript:void(0);" onclick="$.dialog.close()">×</a></div></div>\
						<div id="div_dialogcontent"></div>\
					</td>\
					<td class="div_border div_right"></td>\
				  </tr>\
				  <tr>\
					<td class="div_border div_left_bottom"></td>\
					<td class="div_border div_bottom"></td>\
					<td class="div_border div_right_bottom"></td>\
				  </tr>\
				</table>\
			</div>';
		if(config.isiframe==true){
			addon = isIE6?"scrolling=\"yes\"":"";
			content =  '<div id="div_ifloading">loading...</div><iframe src="about:blank" frameborder="0" width="100%" id="div_dialog_iframe" style="overflow:visible;display:none" '+addon+'></iframe>';
		}else{
			content = config.content;
		}
		$("body").append(template);
		$("#div_dialogtitle").html(config.title);
		$("#div_dialogcontent").html(content);
		$("#div_dialog").css({width:config.width});
		limit = $("#div_dialog").limit();
		$("#div_dialog").css({top:limit.centerY+"px",left:limit.centerX+"px"});
		return true;
	}

	addstyle = function(){
		var style = "<style type='text/css'>#div_dialog{font:12px 'Microsoft Yahei',Arial;}.div_dialogtable{border:1px solid #999}.div_left_top,.div_right_top,.div_left_bottom,.div_right_bottom{width:8px;height:8px;}.div_border{background:#000;filter:alpha(opacity=30);opacity:0.3;}#div_dialogtop{line-height:22px;height:22px;padding:4px;border-bottom:solid 1px #CCC;background-color:#FFF;}#div_dialogtitle{float:left;font-size:12px;font-weight:bold;padding-left:3px;}#div_dialogclose{float:right;height:22px;width:40px;font-size:30px;text-align:center;}#div_dialogclose a{color:gray;text-decoration:none;}#div_dialogclose a:hover{color:red;}#div_dialogcontent{padding:10px;background-color:#FFF;overflow:auto;}#div_ifloading{color:#666;padding:50px;text-align:center;}</style>";
		$("head").append(style);
		return true;
	}

	$(function(){
		// 添加css
		addstyle();
	})

	if(isIE6) document.execCommand('BackgroundImageCache', false, true);// IE CSS背景图片缓存

	jQuery.fn.dialog=init;$.dialog=$().dialog;

	jQuery.fn.dialog.close = function(){
		lastid = null;
		if(config._close == false) return;
		$(window).unbind('resize');
		$("#div_lock").remove();
		if(config.isiframe==true) $("#div_dialog_iframe").remove();
		$("#div_dialog").remove();
		$("html").css("overflow","auto");
		fn = config.closefn;
		fn && fn();
	}

})();