<?php
   //by dongzh
   //close connection after get data.
   header('Content-type: text/html; charset=utf-8');
   $redis = new Redis();
   if($redis->connect('10.86.10.134', 6379))
   {
   } else {
       echo "connect to redis fail!</br>";
   }
   $result = $redis->smembers('yewuoutset');
   ksort($result);
   //var_dump($result);
   //generate a html table
   $submitbutton = <<<SUBMITEOT
<input class="a-upload" type="submit" value="提交">
SUBMITEOT;

   $resetbutton = <<<RESETEOT
<input class="a-upload" type="reset" value="重置">
RESETEOT;


   $checkedbutton = <<<BOTTONEOT
Needout:
<input type="radio" name="change" value="chg">Yes
<input type="radio" name="change" value="nochg" checked="checked">No
BOTTONEOT;

   $checkednobutton1 = <<<BOTTONEOT
 ?
<input type="radio" name=
BOTTONEOT;

   $checkednobutton2 = <<<BOTTONEOT
 value="chg">Yes
<input type="radio" name=
BOTTONEOT;

   $checkednobutton3 = <<<BOTTONEOT
 value="nochg" checked="checked">No
BOTTONEOT;

   $checkedyesbutton1 = <<<BOTTONEOT
 ?
<input type="radio" name=
BOTTONEOT;

   $checkedyesbutton2 = <<<BOTTONEOT
 value="chg" checked="checked">Yes
<input type="radio" name=
BOTTONEOT;

   $checkedyesbutton3 = <<<BOTTONEOT
 value="nochg">No
BOTTONEOT;

   $tohostname = <<<TOEOT
<input type="text" size="30" maxlength="60" name="yewu" value=yewuline>
TOEOT;

   $tohostname1 = <<<TOEOT
<input type="text" size="30" maxlength="60" name=
TOEOT;

   $tohostname2 = <<<TOEOT
 value="
TOEOT;

   $tohostname3 = <<<TOEOT
">
TOEOT;


   $csstable = <<<HTMLEOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
#customers
  {
  font-family:"Trebuchet MS", Arial, Helvetica, sans-serif,"FangSong_GB2312";
  width:98%;
  border-collapse:collapse;
  }

#customers td, #customers th 
  {
  font-size:1em;
  border:1px solid #BA55D3;
  padding:3px 7px 2px 7px;
  }

#customers th 
  {
  font-size:1.1em;
  text-align:left;
  padding-top:5px;
  padding-bottom:4px;
  background-color:#9370DB;
  color:#ffffff;
  }

#customers tr.alt td 
  {
  color:#000000;
  background-color:#9932CC;
  }

tr.change:hover
{
background-color:#F5F5F5;
}


.a-upload {
    padding: 1px 15px;
    height: 30px;
    line-height: 30px;
    font-size: 17px;
    font-family: Tahoma,Helvetica,Arial,'宋体',sans-serif;
    position: relative;
    cursor: pointer;
    color: #888;
    background: #fafafa;
    border: 1px solid #ddd;
    border-radius: 2px;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    *zoom: 1
}

.a-upload  input {
    position: absolute;
    right: 0;
    top: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    cursor: pointer
}

.a-upload:hover {
    color: #444;
    background: #eee;
    border-color: #ccc;
    text-decoration: none
}

</style>
</head>
<form action="domainoutdatain.php" method="post">
<table id="customers"><tr><th>id</th><th>域名</th><th>ip 列表</th><th>是否访问公网</th><th>产品线说明</th></tr>
HTMLEOT;
   echo $csstable;
   echo $submitbutton;
   echo $resetbutton;

   $countfor = 1;
   $contentdesc = "";
   foreach ($result as $key=>$value) {
       $domainname = $value;
       if (strpos($domainname,'etcp.cn') === false) {
           continue;
       }
       $ikey = "yewuout:".$value;
       $isout = $redis->hget($ikey,"isout");
       $ipnum = $redis->hget($ikey,"ipnum");
       $yewuline = $redis->hget($ikey,"yewuling");
       //debug;
       $ips="";
       for($i=1;$i<=$ipnum;$i++) {
           $ipkey = "ip".(string)$i;
           $oneip = $redis->hget($ikey,$ipkey);
           $ips .= $oneip." ";
       }
       $changename="change".$domainname;
       $yewuname="yewu".$domainname;
       if ($isout == '0') {
           echo "<tr class='change'><td>".$countfor."</td><td width='20%'>".$domainname."</td><td width='35%'>".$ips."</td><td width='20%'>".$checkednobutton1.$changename.$checkednobutton2.$changename.$checkednobutton3."</td><td>".$tohostname1.$yewuname.$tohostname2.$yewuline.$tohostname3."</td><tr>";
       } else {
           echo "<tr class='change'><td>".$countfor."</td><td width='20%'>".$domainname."</td><td width='35%'>".$ips."</td><td width='20%'>".$checkedyesbutton1.$changename.$checkedyesbutton2.$changename.$checkedyesbutton3."</td><td>".$tohostname1.$yewuname.$tohostname2.$yewuline.$tohostname3."</td><tr>";
       }
       $countfor++;
   }

   echo "</table>";
   echo $submitbutton;
   echo $resetbutton;
   echo "</form></html>";
   $redis->close();
   //end table

?>
