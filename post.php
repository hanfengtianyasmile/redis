<?php

include('lib.php');

$content = $_POST['status'];

if(!$content){
  exit('请填写内容');
}

if(($user = isLogin()) == false){
  header('location:index.php');
  exit;
}

$r = connredis();

$postid = $r->incr('global:postid');



/*
$r->set('post:postid:'.$postid.':userid',$user['userid']);

$r->set('post:postid:'.$postid.':time',time());
$r->set('post:postid:'.$postid.':content',$content);
*/

$r->hmset('post:postid:'.$postid,array('userid'=>$user['userid'],'username'=>$user['username'],'time'=>time(),'content'=>$content));





//只要最新的20个

$r->zadd('starpost:userid:'.$user['userid'],$postid,$postid);
if ($r->zcard('starpost:userid'.$user['userid']) > 20) {
    $r->zremrangebyrank('starpost:userid'.$userid,0,0);
}

//把自己超过1000个的旧微博，放到mysql
$r->lpush('mypost:userid:'.$user['userid'],$postid);


if ($r->llen('mypost:userid:'.$user['userid']) > 10) {
    $r->rpoplpush('mypost:userid:'.$user['userid'],'global:store');
}

header('location:home.php');

exit();

?>
