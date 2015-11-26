<?php

include('lib.php');

if(isLogin() != false){
  header('location:home.php');
  exit;
}


$username = $_POST['username'];
$password = $_POST['password'];

if(!$username || !$password){
  exit('请输入完整');
}

$r = connredis();
$userid = $r->get('user:username:'.$username.':userid');



if(!$userid){
  exit('用户名不存在');
}

$realpass = $r->get('user:userid:'.$userid.':password');

if($password != $realpass){
  exit('密码不对');
}

//设置cookie
$authsecret = randsecret();
$r->set('user:userid:'.$userid.':authsecret',$authsecret);

setcookie('username',$username);
setcookie('userid',$userid);
setcookie('authsecret',$authsecret);



header('location:home.php');










?>
