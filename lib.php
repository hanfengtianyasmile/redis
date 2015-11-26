<?php

function connredis(){
  static $r = null;
  
  if($r !== null){
    return $r;
  }

  $r = new redis();

  $r->connect('localhost');
  
  return $r;

}

function isLogin(){

  if(!isset($_COOKIE['userid']) || !$_COOKIE['username']){
    return false;
  }  
  
  $r = connredis();
  $authsecret = $r->get('user:userid:'.$_COOKIE['userid'].':authsecret');
  
  if($authsecret != $_COOKIE['authsecret']){
    return false;
  }

  
  return array('userid'=>$_COOKIE['userid'],'username'=>$_COOKIE['username']);

}

function randsecret(){
  $str = 'qazwsxedcrfvtgbyhnmjuikolp23456789';
  return  substr(str_shuffle($str),0,16);
}













?>
