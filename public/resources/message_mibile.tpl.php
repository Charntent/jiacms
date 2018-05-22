<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
  <title>WLCMS提示-{title}</title>
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="themesmobile/68ecshopcom_mobile/css/loginxin.css">
  <link rel="stylesheet" href="themesmobile/68ecshopcom_mobile/css/public.css">
  <style>
  .nl-login-title { font-size: 18px; height:50px;line-height: 50px; color: #454c5b;background: #ffffff; }
.nl-login-title span{width:30%;margin:auto; display: block;}
.nl-login-title .h-left{float:left;width:15%; height:50px;}
.nl-login-title .h-left a{ display:block; width:40px; height:50px; margin:auto; background: url(../images/category/arrow_left.png) no-repeat 15px center; background-size: auto 16px; }

  </style>
  </head>

<body>
    <header class="header_03">
      <div class="nl-login-title">
        <div class="h-left">
          <a class="sb-back" href="javascript:history.back(-1)" title="返回"></a>
        </div>
        <span style="text-align:center">系统提示</span>
      </div>
    </header>
    <div class="tishimain">{$msg}</div>
        <div class="tishi">
           {if $redirect}
            <a href="$redirect"><span>返回上一页</span></a>
           {else}
            <a href="javascript:history.back()"><span>返回上一页</span></a>
           {/if}
          </div>
    



</body></html>