// JavaScript Document// JavaScript Document
//首先创建Json数组
var dialogCon=new Object();
dialogCon={background:'#000',title:'温馨提示', opacity: 0.6,lock: true,drag:false,width:500};
/*
复制对象
*/
function CopyObject(newObject,oldObject){
	for(var n in oldObject){
		  newObject[n] = oldObject[n];
	}
}

//交集
function CombineOveried(o,n){

   for(var p in n)if(n.hasOwnProperty(p))o[p]=n[p];

};

function dialog(parmObj){
	var newconf=new Object();
	CopyObject(newconf,dialogCon);
	if(typeof parmObj != "undefined"){
		CombineOveried(newconf,parmObj);
		if(newconf.CloseAll){
			var list = art.dialog.list;
			for (var i in list) {
				list[i].close();
			};
		}
		
		if(parmObj.status) newconf.time=1.5;
	}
	newconf.content=(newconf.content)?newconf.content:'请求出错稍后再试';
	newconf.title=(newconf.title)?newconf.title:'温馨提示';

	art.dialog(newconf);
}

