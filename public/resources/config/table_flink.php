<?php return array (
  'listfield' => 
  array (
    0 => 'sitename',
    1 => 'url',
  ),
  'editfield' => 
  array (
    0 => 'sitename',
    1 => 'url',
  ),
  'searchfield' => 
  array (
    0 => 'sitename',
  ),
  'func' => 
  array (
    0 => 'allowadd',
    1 => 'allowedit',
    2 => 'allowdel',
  ),
  'field' => 
  array (
    'sitename' => 
    array (
      'name' => '网站名称',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'url' => 
    array (
      'name' => '网址',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'pic' => 
    array (
      'name' => 'logo',
      'type' => 'file',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => 'if(empty($action))
$pic = "<img src=../$pic width=100 />";',
    ),
  ),
); ?>