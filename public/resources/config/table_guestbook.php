<?php return array (
  'listfield' => 
  array (
    0 => 'subject',
    1 => 'content',
    2 => 'status',
    3 => 'addtime',
  ),
  'editfield' => 
  array (
    0 => 'subject',
    1 => 'content',
    2 => 'username',
    3 => 'tel',
    4 => 'qq',
    5 => 'status',
    6 => 'addtime',
  ),
  'searchfield' => 
  array (
    0 => 'subject',
    1 => 'username',
    2 => 'status',
  ),
  'func' => 
  array (
    0 => 'allowview',
    1 => 'allowedit',
    2 => 'allowdel',
  ),
  'field' => 
  array (
    'subject' => 
    array (
      'name' => '留言标题',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'content' => 
    array (
      'name' => '留言内容',
      'type' => 'textarea',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => 'if($action==\'view\')
$content = nl2br($content);',
    ),
    'username' => 
    array (
      'name' => '联系人',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'tel' => 
    array (
      'name' => '电话',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'qq' => 
    array (
      'name' => '联系邮箱',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'status' => 
    array (
      'name' => '审核通过',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => 'if($status==1)$status="通过";else $status="不通过";',
    ),
    'addtime' => 
    array (
      'name' => '留言时间',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '$addtime = strtotime($addtime);',
      'outputexec' => '$addtime = date("Y-m-d H:i:s",$addtime);',
    ),
  ),
); ?>