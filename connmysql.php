<?php 

include('./lib.php');

$r = connredis();

$sql = 'insert into post(postid,userid,username,time,content) values ';
$i = 0;

while ($r->llen('global:store') && $i++<100) {
    $postid = $r->rpop('global:store');
    $post = $r->hmget('post:postid:'.$postid,array('userid','username','time','content'));
    $sql .= "($postid," . $post['userid'] . ",'" . $post['username'] . "'," . $post['time'] . ",'" . $post['content'] . "'),";

}


if ($i == 0) {
    echo 'no job';
    exit;
}

$sql = substr($sql,0,-1);

echo $sql;

//链接数据库

$conn = mysql_connect('127.0.0.1','root','123456');
var_dump($conn);
mysql_query('use test',$conn);
mysql_query('set names utf8',$conn);

$res = mysql_query($sql,$conn);


var_dump($res);

echo 'ok';





 ?>
