<?php
   //by dongzh
   //update out or not in redis
   header("Content-type: text/html; charset=utf-8");
   $redis = new Redis();
   if($redis->connect('10.86.10.134', 6379))
   {
   } else {
       echo "connect to redis fail!</br>";
   }
   $result = $redis->smembers('yewuoutset');
   //var_dump($result);
   //var_dump($_POST);
   foreach ($result as $key=>$value) {
       $domainname = $value;
       if (strpos($domainname,'etcp.cn') === false) {
           continue;
       }
       $newkey=str_replace(".","_",$value);
       $changecheck="change".$newkey;
       $yewucheck="yewu".$newkey;
       $ikey = "yewuout:".$value;
       $isout = $redis->hget($ikey,"isout");

       if ($_POST[$changecheck] == "chg"){
           $redis->hset($ikey,"isout","1");
       } else {
           $redis->hset($ikey,"isout","0");
       }
       $redis->hset($ikey,"yewuling",$_POST[$yewucheck]);
   }
   echo "提交成功</br>";
   echo "</br>";
   $redis->close();
   //quit
   $originurl=$_SERVER["HTTP_REFERER"];
   echo "<a href=".$originurl.">立即返回</a></br>";
?>
