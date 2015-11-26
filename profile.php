
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include('lib.php');

if(($user=isLogin()) == false){
  header('location:index.php');
  exit;
}


$r = connredis();

$u = $_GET['u'];

$prouid = $r->get('user:username:'.$u.':userid');

if(!$prouid){
  exit('非法用户');
 
}

$isf = $r->sismember('following:'.$user['userid'],$prouid);

$isfstatus = $isf ? '0' : '1';
$isfword = $isf ? '取消关注': '关注ta';

?>



<html lang="it">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title>Retwis - Example Twitter clone based on the Redis Key-Value DB</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="page">
<div id="header">
<a href="/"><img style="border:none" src="logo.png" width="192" height="85" alt="Retwis"></a>
<div id="navbar">
<a href="index.php">主页</a>
| <a href="timeline.php">热点</a>
| <a href="logout.php">退出</a>
</div>
</div>
<h2 class="username">test</h2>
<a href="follow.php?uid=<?php echo $prouid?>&f=<?php echo $isfstatus; ?>" class="button"><?php echo $isfword?></a>

<div class="post">
<a class="username" href="profile.php?u=test">test</a> 
world<br>
<i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>

<div id="footer">redis版本的仿微博项目 <a href="http://redis.io">Redis key-value database</a></div>
</div>
</body>
</html>
