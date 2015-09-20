<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
include('lib.php');


if(($user = isLogin()) == false){
  header('location:index.php');
  // exit;
}


//取出自己发的和粉主推过来的信息

$r = connredis();

//取出自己发的和粉主推过来的信息
$r->ltrim('recivepost:'.$user['userid'],0,49);
/*$newpost = $r->sort('recivepost:'.$user['userid'],array('sort'=>'desc','get'=>'post:postid:*:content'));
*/

$star = $r->smembers('following:'.$user['userid']);
$star[] = $user['userid'];

$lastpull = $r->get('lastpull:userid:'.$user['userid']);
if (!$lastpull) {
    $lastpull = 0;
}

//拉取最新数据

$latest = array();

foreach ($star as $key => $value) {
    $latest = array_merge($latest,$r->zrangebyscore('starpost:userid:'.$value,$lastpull+1,1<<32-1));
}

//更新$latest
if ($latest) {
    $r->set('lastpull:userid:'.$user['userid'],end($latest));    
}



sort($latest,SORT_NUMERIC);

foreach ($latest as $key => $value) {
    $r->lpush('recivepost:'.$user['userid'],$value);
}

//只要1000条
$r->ltrim('recivepost:'.$user['userid'],0,999);


$newpost = $r->sort('recivepost:'.$user['userid'],array('sort'=>'desc'));



//计算个数
$myfans = $r->sCard('follower:'.$user['userid']);
$mystar = $r->sCard('following:'.$user['userid']);


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
<div id="postform">
<form method="POST" action="post.php">
<?php echo $user['username'] ?>, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="status"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $myfans; ?> 粉丝<br>
<?php echo $mystar; ?> 关注<br>
</div>
</div>
  
<?php foreach($newpost as $postid) {

$p = $r->hmget('post:postid:'.$postid,array('userid','username','time','content'));

?>
<div class="post">
<a class="username" href="profile.php?u=<?php echo $p['username']?>"><?php echo $p['username']?></a><?php echo $p['content']; ?><br>
<i>><?php echo $p['time']?> 分钟前 通过 web发布</i>
</div>
<?php }?>


<div id="footer">redis版本的仿微博项目 <a href="http://redis.io">Redis key-value database</a></div>
</div>
</body>
</html>
