<?php return array (
  'listfield' => 
  array (
    0 => 'username',
    1 => 'password',
    2 => 'email',
    3 => 'introducer',
    4 => 'registertime',
  ),
  'editfield' => 
  array (
    0 => 'username',
    1 => 'password',
    2 => 'email',
    3 => 'introducer',
    4 => 'registertime',
  ),
  'searchfield' => 
  array (
  ),
  'func' => 
  array (
    0 => 'allowview',
    1 => 'allowdel',
  ),
  'field' => 
  array (
    'username' => 
    array (
      'name' => '用户名',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'password' => 
    array (
      'name' => '密码',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'email' => 
    array (
      'name' => '邮箱',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'introducer' => 
    array (
      'name' => '介绍人',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'registertime' => 
    array (
      'name' => '注册时间',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '$registertime = strtotime($registertime);',
      'outputexec' => '$registertime = date("Y-m-d H:i:s",$registertime);',
    ),
  ),
); ?>