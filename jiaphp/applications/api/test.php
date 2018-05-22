<?php

$app = array(

      'data'=>array(
		   'myinput'=>'d@qq.com'
	   ),
	  'option'=>array(
		 'myinput'=>array('email','活动名称不能为空!')
	  )
	  
);

//set_gpc('myinput','d');
if(Hopen::validate($app,array('myinput'=>'d@qq.com')) === true){
	dump(Hopen::$data);
}