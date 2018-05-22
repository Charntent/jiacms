/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	var lang = $("input[name=lang]").val();
	config.image_previewText=' '; 
	config.filebrowserUploadUrl = "upload?lang="+lang+"&_="+Math.random();
	config.filebrowserImageUploadUrl ="upload?lang="+lang+"&_="+Math.random();
    config.filebrowserFlashUploadUrl = "upload?lang="+lang+"&_="+Math.random();
    config.allowedContent = true;
	config.height = '250px';
	config.uiColor = '#F7F7F7';
	config.DefaultLanguage = "zh-cn" ;

	config.toolbar = [
	    ['Source'],
		  //左对齐             居中对齐          右对齐          两端对齐
         ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Styles','Format','Font','FontSize','TextColor','BGColor'],
		['TextColor','MathJax','BGColor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		['Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
　　  //文本颜色     背景颜色
	   ['TextColor','BGColor'],
　　　　　　　　　//全屏           显示区块
	   ['Maximize', 'ShowBlocks','-']
		
	];
	CKEDITOR.config.contentsCss = [];
};
