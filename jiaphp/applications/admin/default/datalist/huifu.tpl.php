<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>无标题文档</title>
</head>

<body>
<p>给{$r.subject}解答，最后一条为准</p>

{sql select * FROM guestbook where pid='$id' order by id desc}
<p>{$index}、{$content}</p>
{/sql}

<form method="post" class="system">
<input type="hidden" value="{$id}" name="data[pid]">
<textarea name="data[content]" style="width:680px; height:80px;" class="iptext"></textarea>
<input type="hidden" name="action" value="huifu">
<input type="hidden" name="do" value="1">
<input type="submit" name="fsda" value="提交">
</form>
</body>
</html>