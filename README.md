# redis
用redis做数据存储的一个微博小项目，有发微博、关注与被关注、登录注册等常见功能，主要为了练习redis的使用


微博项目的key设计




数据结构：字符串





局相关的key:

表名  global

列名  操作  备注

Global:userid   incr    产生全局的userid

Global:postid   Incr    产生全局的postid


用户相关的key(表)

表名  user

Userid  Username    Password    Authsecret

3   Test3   1111111 123456

在redis中,变成以下几个key

Key前缀   user

User:Userid:*   User:userid:*Username   User:userid:*Password   User:userid:*:Authsecret

User:userid:3   User:userid:3:Test3 User:userid:3:1111111   User:userid:3:12222


微博相关的表设计

表名  post          

Postid  Userid  Username    Time               Content

4              2         Lisi    1370987654f        测试内容

微博在redis中,与表设计对应的key设计

Key前缀   post     

Post:Postid:*   Post:postid:*Userid Post:postid:*:Username  Post:postid:*:Time  Post:postid:*:Content

4   2   Lisi    1370987654f 测试内容







数据结构：集合







关注表: following


Following:$userid -->


粉丝表


Follower:$userid --->







数据结构：链表







拉取表

3   4   7           
         







