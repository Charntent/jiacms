// JavaScript Document
var use = document.getElementById('cms_editor').getAttribute('use');
var pb = document.getElementById('cms_editor').getAttribute('pb');

switch(use){
  
  case 'ueditor':
      
	  document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/ueditor/ueditor.config.js"></script>')
	  document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/ueditor/ueditor.all.js"></script>');
  break;
  
  case 'kindeditor':
      document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/kindeditor/kindeditor-all.js"></script>')
  break;	
  
  case 'ckeditor':
      document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/ckeditor/ckeditor.js"></script>')
  break;
  
   case 'wangeditor':
	  document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/wangeditor/js/lib/plupload/plupload.full.min.js"></script>');
document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/wangeditor/js/wangEditor.js"></script>');
 document.write('<script type="text/javascript" charset="utf-8" src="'+pb+'/wangeditor/js/app.js"></script>');
  document.write('<link rel="stylesheet" type="text/css" href="'+pb+'/wangeditor/css/wangEditor.min.css">');
 
  break;
  

}