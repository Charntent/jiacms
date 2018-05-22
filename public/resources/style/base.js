var current_url = $("script").last().attr("src");
current_url = current_url.replace(/style\/base\.js/ig, "");

$(function(){
	initframe = function(){
		if($.browser.msie){ 
			bheight = document.compatMode == "CSS1Compat"? document.documentElement.clientHeight : document.body.clientHeight; 
			bwidth = document.compatMode == "CSS1Compat"? document.documentElement.clientWidth : document.body.clientWidth; 
		}else{ 
			bheight = self.innerHeight; 
			bwidth = self.innerWidth; 
		}
		$(".frame .right iframe").width(bwidth-230);
		$(".frame .right iframe").height(bheight-45);
	
		$("#subiframe").height(bheight+50);
		
		$(".subiframe").height(bheight-125);
		/*$(".frame .right,.frame .right iframe").width(bwidth-189);
		$(".frame .left,.frame .right,.frame .right iframe").height(bheight-45);
		$(".frame .left>ul").height(bheight-32);
		$(".frame").height(bheight).width(bwidth);
		*/
		$(".frame .left,.frame .right").height(bheight-25);
		$(".frame .left>ul").height(bheight-85);
	}
	initframe();
	$(window).resize(function(){
		initframe();
	});
	
	
	initsubiframe = function(){
		try{
			//var sub_h = $(".subiframe iframe").contents().find("body").height();
			//if(sub_h<500) sub_h=500;
			/*$(".subiframe iframe").width(bwidth-50).height(sub_h);
			$("#pane_panel_warning").height(bheight-35);*/
			var sub_h = $(".subiframe iframe").contents().find("body").height();
			if(sub_h<500) sub_h = 500;
			var subiframews  = $('.subiframe').width();
			$("#subiframe").width(subiframews-60).height(sub_h+50);
		}catch(e){
			return false;
		}
	}
	if($("#subiframe").length>0){
		$("#subiframe").load(function(){
			initsubiframe();
			setInterval(initsubiframe,200);
		})
		setInterval(initsubiframe,200);
	};
	
	$("a[target=main]").click(function(){
		$(".loading").show();
	});
	$(".subframenav li").click(function(){
		if(!$(this).hasClass('current')) {
			$(".loading").show();
		}
	});
	$("#main,#subiframe").load(function(){
		$(".loading").fadeOut('slow');
	})
	
	$("#togglemenu").click(function(){
		$(".left").toggle();
		if($(".left").css('display') == 'none'){
		   // $(".frame .right,.frame .right iframe").width(bwidth);
			$(this).html("展开菜单");
		}else{
			//$(".frame .right,.frame .right iframe").width(bwidth-230);
			$(this).html("隐藏菜单");
		}
	});

})


/*$(function () {
    var elm = $('.subframenav');
	
	if($(elm).offset()!=null){
		var startPos = $(elm).offset().top; 
		$.event.add(window, "scroll", function() { 
			var p = $(window).scrollTop(); 
			$(elm).css('position',((p) > startPos) ? 'fixed' : 'static'); 
			$(elm).css('top',((p) > startPos) ? '0px' : ''); 
		}); 
	}
});
*/

//左侧导航鼠标滚动
mtop = 0;
var allowscroll = false;
function handle(delta) {
	if(allowscroll){
		//是否到顶部
		var l = $(".frame .left ul li").last();
		p = l.position();
		var h = $(".frame .left").height();
		if(p.top<h && delta<0) return;
		//是否到底部
		var o = $(".frame .left ul li").first();
		mtop = mtop+delta*5;
		if(mtop>0) mtop = 0;
		
		o.css("margin-top",mtop);
	}
}
function wheel(event){
	var delta = 0;
	if (!event) event = window.event;
	if (event.wheelDelta) {
		delta = event.wheelDelta/120;
	if (window.opera) delta = delta;
	} else if (event.detail) {
		delta = -event.detail/3;
	}
	if (delta)
		handle(delta);
}
if (window.addEventListener)
	window.addEventListener('DOMMouseScroll', wheel, false);
window.onmousewheel = document.onmousewheel = wheel;
$(function(){
	$(".frame .left").hover(function(){
		allowscroll = true;
	},function(){
		allowscroll = false;
	})
})

$(function(){
	$(".frame .left li a").hover(
		function(){
			$(this).parent().addClass("hover");	
		},
		function(){
			$(this).parent().removeClass("hover");	
		}
	)
	$(".frame .left li a").click(
		function(){
			//获取current的id
			
			var cid = $(this).parent().parent().find('.current').data('cid');
			var crid = $(this).parent().data('cid');
			if(cid!=crid){
				$(this).parent().parent().find('.current').find('ul').hide();
			}
			
			
			$(this).parent().addClass("current").siblings().removeClass('current');	
			
			$(this).parent().find('ul').slideToggle();	
			
			var sons = $(this).parent().data('sons');
			if(sons!='' && sons!=undefined){
				$(this).parent().find('ul li').hide();
				var sons_ar = sons.toString().split(',');
				for(var i in sons_ar){
				    $("#wf_cat_"+sons_ar[i]).toggle();	
				}
			}
			//判断一下
			
		}
	)	
	
	  $(".fanxuan").click(function(){
		
	     var ids = $(".idars"); 
	     $.each(ids,function(index,value){
		       $(this).prop("checked",!$(this).prop("checked"));
	     });
     });
	 
	 //切换
	 $("#article_nav li").click(function(){
		 $("#article_nav li").removeClass('active');
		 $(this).addClass('active');
		 var id = $(this).data('id');
		 switch(id){
		    
			case 'common':
			    $(".content").addClass('hide');
				$(".seo").addClass('hide');
				$(".common").removeClass('hide').fadeIn();   
			break;	
			case 'content':
			    $(".common").addClass('hide');
				$(".seo").addClass('hide');
				$(".content").removeClass('hide').fadeIn();   
			break;	
			case 'seo':
			    $(".content").addClass('hide');
				$(".common").addClass('hide');
				$(".seo").removeClass('hide').fadeIn();   
			break;	 
		 }
		 
	 });
	 
})

$(function(){
	$(".form input[type='text'],.form input[type='password'],.form textarea").addClass("iptext").focus(
		function(){
			$(this).addClass("iphover");
		}
	).blur(
		function(){
			$(this).removeClass("iphover");
		}
	)
})

$(function(){
	$(".subframenav li").click(function(){
		var last = $(this).find('a').data('last');
	    if(last!=1)
		$(this).addClass("current").siblings().removeClass("current");
	})
})
$(function(){
	$(".subframenav li a").click(function(){
		$(".subframenav li a").removeClass("btnGreen");
		$(this).addClass("btnGreen");
		
	})
	
	//
	$(".wlcms_dev .idars").click(function(){
	    var v = $(this).prop('checked');
		var ids = $(this).data('ids').toString();
		
		var ids_ar = ids.split(',');
		
		for(var i in ids_ar){
			$("#cat_"+ids_ar[i]).prop('checked',v);
		}
		
	});
	
})

function message(msg,time){
	if(msg!=''){
		setTimeout(function(){
			$(".message").html(msg).fadeTo("slow",0.7,function(){
				setTimeout(function(){
					$(".message").fadeOut("fast");
				},time)	
			});
		},200)
	}
}

function upload_show_thumb(obj){
	$(obj).parent().find(".upspanimg").remove();
	$(obj).parent().find("img").remove();
	var val = $(obj).val();
	var vals = val.split(',');
	$.each(vals,function(i,r){
		if(r.indexOf('.jpg')!=-1 || r.indexOf('.gif')!=-1 || r.indexOf('.png')!=-1 ){
			$(obj).after("<span class='upspanimg'><img src='../"+r+"' rel='"+r+"' height='100px' onclick='upload_file_remove(this)' onMouseOver=\"this.style.borderColor='red'\" onMouseOut=\"this.style.borderColor='#CCC'\" /><i class='dele glyphicon glyphicon-remove-circle' onclick=upload_file_remove($(this).parent().find('img'))></i></span>");	
		}else{
			$(obj).after(" <img src='"+current_url+"images/fileico.gif' rel='"+r+"' height='40px' onclick='upload_file_remove(this)' onMouseOver=\"this.style.borderColor='red'\" onMouseOut=\"this.style.borderColor='#CCC'\" />");	
		}
	})
}

function upload_file_remove(obj){
	
	if(!confirm("删除？")) return;
	imgsrc = $(obj).attr("rel");
	var input = $(obj).parent().parent().find("input");
	var ipval = input.val();
	ipval = ipval.replace(","+imgsrc,'');
	ipval = ipval.replace(imgsrc+',','');
	ipval = ipval.replace(imgsrc,'');
	input.val(ipval);
	
	
	$.ajax({
			type: "POST",
			url:'admin?action=deletefile&filename='+imgsrc+'&_='+Math.random(),
			dataType:'json',
			success: function (msg) {
				if(msg['error'] == 12 || msg['error'] == 11){
					alert("这是系统原图，不能删除！");
				}else{
				   $(obj).parent().remove();	
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert('服务器有问题！请您稍后再试！');
			}
		}); 	
}

// 文件上传
$(function(){
	$("input[_type=file]").each(function(){
		var name = $(this).attr("id");
		$(this).after(' <span class="uploadbtn"><input type="button" value="上传" id="spanButtonPlaceholder_'+name+'"></span><div id="divFileProgressContainer_'+name+'" ></div>');	
		var lang = $("input[name='lang']").val();
		if(lang == undefined || lang==''){
			lang = 'zh_cn';
		}
		var session_id = $("input[name='session_id']").val();
		
		var swfu;
		swfu = new SWFUpload({
			upload_url: "upload",
			post_params: {"lang":lang,'session_id':session_id},
			
			file_types: "*.*",
			file_size_limit : "20480",
	
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
	
			// Button Settings
			button_image_url : current_url+"style/uploadbtn.png",
			button_placeholder_id : "spanButtonPlaceholder_"+name,
			button_width: 120,
			button_height: 40,
			button_text : '',
			//button_text_style : '',
			button_text_top_padding: 0,
			button_text_left_padding: 5,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			
			// Flash Settings
			flash_url : current_url+"swfupload/swfupload.swf",
	
			custom_settings : {
				content_box : this,
				upload_target : "divFileProgressContainer_"+name
			},
			
			// Debug Settings
			debug: false
		});
	})

	// 加载图片预览
	$("input[_type=file]").each(function(){
		upload_show_thumb(this);
	})
	$("input[_type=file]").change(function(){
		upload_show_thumb(this);
	})
	
})

// 加载编辑器
$(function(){
	var cms_obj = document.getElementById('cms_editor');
	var use = ''
	if(cms_obj!=undefined){
	   use	 = cms_obj.getAttribute('use');
	}

	
	switch(use){
		case 'kindeditor':
		  var editor;
		  $("textarea[_type=minieditor]").each(function(){
				var name = $(this).attr("id");
				KindEditor.ready(function(K) {
					editor = KindEditor.create("#"+name, {
						allowFileManager : true,
						afterBlur: function(){this.sync(); },
						resizeType : 1,
					    allowPreviewEmoticons : false,
					    allowImageUpload : false,
						filterMode:false,
						urlType:'' ,
						items : [
							'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
							'insertunorderedlist', '|', 'emoticons', 'image', 'link']
						});
						
						
				
			    });
			})
			$("textarea[_type=editor]").each(function(){
				var name = $(this).attr("id");
				
				KindEditor.ready(function(K) {
					editor = KindEditor.create("#"+name, {
						  allowFileManager : true,
						  minWidth : '100%',
						  width : '100%',
						  minHeight:'420px',
						  filterMode:false,
						  afterBlur: function(){this.sync();},
						  urlType:'' 
						  
					});
				
			    });
			});
	    break; 
		
		case 'ckeditor':
		
		    $("textarea[_type=minieditor]").each(function(){
				var ckid = $(this).attr("id");
				CKEDITOR.replace(ckid,{
					 height:420,
					 width:'100%',
					 toolbar:[['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'], ['Bold','Italic'],      
					['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],     
					['Smiley'],       
					['Styles','Font','FontSize'],      
					['TextColor'],      
					['Undo','Redo']]
				});
			});
		      
			$("textarea[_type=editor]").each(function(){
				var ckid = $(this).attr("id");
				CKEDITOR.replace(ckid,{
				 height:420,
				 width:'1000px;min-width:1000px;',
			
				});

            });

		
		break;
		
		case 'wangeditor':
		   
		
		break;
		
		
		default:
			$("textarea[_type=minieditor]").each(function(){
				var name = $(this).attr("id");
				var editorOption = {
					toolbars:[['FullScreen', 'Source', 'Undo', 'Redo','Bold','JustifyLeft','JustifyCenter','JustifyRight','InsertImage','RemoveFormat', 'FontFamily', 'FontSize']],
					minFrameHeight:120,
					autoHeightEnabled:false,
					elementPathEnabled:false,
					wordCount:false
				}
				var editor_a = new baidu.editor.ui.Editor(editorOption);
				editor_a.render( name );
			})
			$("textarea[_type=editor]").each(function(){
				var name = $(this).attr("id");
				//var editor_a = new baidu.editor.ui.Editor();
				//editor_a.render( name );
				var editor = UE.getEditor(name);
			});
	    break;
	
	}
})

function delall(url,name){
	 var ids = $("input[name=id]:checked"); 
	
	 if(ids.length == 0) {
		window.top.message("您什么都还没有选择!");
		 return false;
	 }
	 if(layer != undefined) {
		 var anims = parseInt(Math.random()*10);
		 layer.confirm("您真的要"+name+"吗？", {
		  btn: ['确定','取消'] //按钮
		  ,title:'提示'
		  ,icon:2
		  ,shade: [0.6, '#fff']
		  ,shift:anims
		  ,anim:parseInt(Math.random()*10)
		}, function(){
		    var index = layer.load(0, {
			  shade: [0.1,'#fff'] //0.1透明度的白色背景
			});
			
			var str = "";
			 $.each(ids,function(index,value){
				  str = str +","+$(this).val();
			 });
			
			 $.post(url,{delids:str},
				function(data){
					window.top.message(data.msg,500);
					 setTimeout(function(){window.location.reload();},1000);
				},"json");
		});
	 }else{
	     if(confirm("您真的要"+name+"吗？")){
			 var str = "";
			 $.each(ids,function(index,value){
				  str = str +","+$(this).val();
			 });
			
			 $.post(url,{delids:str},
				function(data){
					window.top.message(data.msg,500);
					 setTimeout(function(){window.location.reload();},1000);
				},"json");
		 }	 
     }
}

function submitTo(url,action,key){
	if(action=='del'){
		if(layer != undefined) {
		 var anims = parseInt(Math.random()*10);
		 layer.confirm("您真的要删除吗？", {
		  btn: ['确定','取消'] //按钮
		  ,title:'提示'
		  ,icon:2
		  ,shade: [0.6, '#fff']
		  ,shift:anims
		  ,anim:parseInt(Math.random()*10)
		}, function(){
		    var index = layer.load(0, {
			  shade: [0.1,'#fff'] //0.1透明度的白色背景
			});
			$.post(url,{action:action,delids:key},
				function(data){
					window.top.message(data.msg,500);
					setTimeout(function(){window.location.reload();},1000);
			},"json");
		 });
	 }else{
		if(confirm("您真的要删除吗？")){
			 $.post(url,{action:action,delids:key},
				function(data){
					window.top.message(data.msg,500);
					 setTimeout(function(){window.location.reload();},1000);
				},"json");
		 }
	   }
	}
	
}

$(function(){
    
	$('.hp-del').on('click',function(){
	   var href = $(this).attr('href');
	   var anims = parseInt(Math.random()*10);
	  
	   layer.confirm('真的要删除吗？', {
		  btn: ['确定','取消'] //按钮
		  ,title:'提示'
		  ,icon:2
		  ,shade: [0.6, '#fff']
		  ,shift:anims
		  ,anim:parseInt(Math.random()*10)
		}, function(){
		    var index = layer.load(0, {
			  shade: [0.1,'#fff'] //0.1透明度的白色背景
			});
			
			$.post(href,{},function(data){
			   layer.close(index);
			   layer.msg(data.msg);
			   if(data.error == 0) {
				   setTimeout(function() { location.href =location.href;},1000);
			   }
			   	
			},'json');
			
		});	
	  
	   return false;
	});
		
})
