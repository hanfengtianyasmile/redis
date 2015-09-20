<?php

include('lib.php');

if(isLogin() != false){
  header('location:home.php');
  exit;
}

$username = $_POST['username'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

if(!$username || !$password || !$password2){
  exit('请输入完整信息');
}

if($password !== $password2){
  exit('2次密码不一致');
}

$r = connredis();

if($r->get('user:username:'.$username.':userid')){
  exit('用户名已经被注册');
}

//获取userID
$userid = $r->incr('global:userid');

$r->set('user:userid:'.$userid.':username',$username);

$r->set('user:userid:'.$userid.':password',$password);


$r->set('user:username:'.$username.':userid',$userid);

//通过一个链表，维护50个表的最新的userid
$r->lpush('newuserlink',$userid);
$r->lrtim('newuserlink',0,49);



?>
