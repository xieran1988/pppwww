<?php 
$to_ping = $_GET["ip"]; 
$count = 1; 
$psize = 65;
//exec("ping -c $count -s $psize $to_ping", $list); 
exec("ping $to_ping -n $count -w 1", $list);
//for ($i=0;$i < count($list);$i++) { 
//print "Y".$list[2]."X"; 
// print "<pre>"; 
//flush();
 if($list[2] == "请求超时。") print "<div style=\"background-color:#999999;height:1000px;width:1000px;color:#ffffff;\">".$_GET["name"]."</div>";
 else print "<div style=\"background-color:#00ff00;height:1000px;width:1000px;color:#ffffff;\">".$_GET["name"]."</div>";
// print "</pre>";
//}
?> 