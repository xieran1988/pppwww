<?php
header("content-type:text/html; charset=gb2312");
header("service; yjwt");
require_once("sql.php"); 
require_once("fun.php");
?>
<html>
<head>
<?php
function user_info(){
	
}
function fix_ip($fix){
	if($fix) return $fix;
	else return "系统地址池";
}
function fix_speed($fix){
	if($fix) return $fix." kbps";
	else return "未配置";
}
function fix_uptime($fix){
	if($fix) return $fix;
	else return "从未上线";
}
function from_search(){
	echo "<form class=\"form-horizontal\" method=\"post\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
	echo "关键字:<input name=\"key\" type=\"text\" />";
	echo " 在线<input type=\"checkbox\" name=\"online\" value=\"1\">";
	echo " 到期<input type=\"checkbox\" name=\"disable_time\" value=\"1\">";	
	echo " <input id=\"input_sub\" type=\"submit\" value=\"提交\" />";
	echo "<input name=\"opt\" type=\"hidden\" value=\"1\" />";
	echo "<form>";
}
function table_user(){
    $startCount = 0;
	$perNumber = 10;
	$num = 0;
	if($_REQUEST["page"]) $startCount = $_REQUEST["page"]*$perNumber;
	$sql_select = "select * from user_pppoe right join user_info on user_pppoe.id = user_info.uid";
	if($_REQUEST["opt"]){
		$sql_select =$sql_select." where";
		if($_REQUEST["online"]) $sql_select =$sql_select." online = 1";
		else $sql_select =$sql_select." (online is NULL or online !=1)";
		if($_REQUEST["disable_time"]) $sql_select =$sql_select." and  disable_time < CURRENT_DATE()";
		if($_REQUEST["key"]){
			$sql_select =$sql_select." and (username like '%".$_REQUEST["key"]."%'";
			$sql_select =$sql_select." or addr like '%".$_REQUEST["key"]."%'";
			$sql_select =$sql_select." or name like '%".$_REQUEST["key"]."%'";
			$sql_select =$sql_select." or idcar like '%".$_REQUEST["key"]."%'";
			if(stripos($_REQUEST["key"], ".")> 1) $sql_select =$sql_select." or last_ip like '%".$_REQUEST["key"]."%'";
			if(strlen($_REQUEST["key"]) == 23) $sql_select =$sql_select." or mac like '%".$_REQUEST["key"]."%'";
			$sql_select =$sql_select." or phone like '%".$_REQUEST["key"]."%')";
		}
	}
	if($_GET["uid"]) $sql_select = $sql_select." where uid='".$_GET["uid"]."'";
	else $sql_select = $sql_select." limit ".$startCount.",".$perNumber;
	//echo $sql_select;
	$dataset = yjwt_mysql_select($sql_select);
	echo "<table class=\"table table-condensed\">";
	if($dataset){
		echo "<tr>";
		echo "<td><b>序号</b></td>";
		echo "<td><b>UID</b></td>";
		echo "<td><b>帐户</b></td>";
		echo "<td><b>在线</b></td>";
		echo "<td><b>姓名</b></td>";
		echo "<td><b>地址</b></td>";
		echo "<td><b>电话</b></td>";
		echo "<td><b>证件号</b></td>";
		echo "<td><b>MAC</b></td>";
		echo "<td><b>服务器名称</b></td>";
		echo "<td><b>IP</b></td>";
		echo "<td><b>带宽</b></td>";
		echo "<td><b>到期时间</b></td>";
		echo "</tr>";
	}
	while($dataset && $row = mysql_fetch_array($dataset)){
		echo "<tr>";
		echo "<td>".$num."</td>";
		echo "<td><a href='".$_SERVER["SCRIPT_NAME"]."?uid=".$row["uid"]."'>".$row["uid"]."</a></td>";
		echo "<td>".$row["username"]."</td>";
		//user_online
		echo "<td>".user_online($row["online"])."</td>";
		echo "<td>".$row["name"]."</td>";
		echo "<td>".$row["addr"]."</td>";
		echo "<td>".$row["phone"]."</td>";
		echo "<td>".$row["idcar"]."</td>";
		echo "<td>".$row["mac"]."</td>";
		echo "<td>".$row["service_name"]."</td>";
		echo "<td>".$row["last_ip"]."</td>";
		echo "<td>".$row["down_speed"]."kbps</td>";
		echo "<td>".$row["disable_time"]."</td>";
		echo "</tr>";
		$num += 1;
		if($_GET["uid"]) break;
	}
	if($num==0) echo "<tr><td>未找到数据</td></tr>";
	echo "</table>";
	if($_REQUEST["page"]) echo "<a href='".$_SERVER["SCRIPT_NAME"]."?page=".($_REQUEST["page"]-1)."'>上一下</a> | ";
	if($num == $perNumber) echo "<a href='".$_SERVER["SCRIPT_NAME"]."?page=".($_REQUEST["page"]+1)."'>下一下</a>";
	if($_GET["uid"]){
		echo "<p>详细信息<p>";
		echo "<table class=\"table table-condensed\">";
		echo "<tr>";
		echo "<td><b>固定IP</b></td>";
		echo "<td><b>项目</b></td>";
		echo "<td><b>项目节构</b></td>";
		echo "<td><b>最后上线</b></td>";
		echo "<td><b>带宽名称</b></td>";
		echo "<td><b>内网带宽</b></td>";
		echo "<td><b>系统IP</b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".fix_ip($row["ip_address"])."</td>";
		echo "<td>".val_text("select name from org where Id=".$row["orgid"], "name")."</td>";
		echo "<td>".$row["orgmap"]."</td>";
		echo "<td>".fix_uptime($row["logout_time"])."</td>";
		echo "<td>".val_text("select name from speed where Id=".$row["speed_id"],"name")."</td>";
		echo "<td>".fix_speed($row["lan_speed"])."</td>";
		echo "<td>".fix_uptime($row["service_ip"])."</td>";
		echo "</tr>";
		echo "<table>";
		
		$sql_select = "select * from bill where uid=".$_GET["uid"];
		$dataset = yjwt_mysql_select($sql_select);
		if($dataset && $row = mysql_fetch_array($dataset)){
			echo "<header class=\"jumbotron subhead\" id=\"overview\">";

		
			
			echo "<p>交易信息</p>";
			echo "<table class=\"table table-condensed\">";
			echo "<tr>";
			echo "<td><b>序号</b></td>";
			echo "<td><b>时间</b></td>";
			echo "<td><b>类型</b></td>";
			echo "<td><b>交易前时间</b></td>";
			echo "<td><b>交易后时间</b></td>";
			echo "<td><b>时长</b></td>";
			echo "<td><b>金额</b></td>";
			echo "<td><b>操作员</b></td>";
			echo "<td><b>备注</b></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>".$row["Id"]."</td>";
			echo "<td>".$row["opt_time"]."</td>";
			echo "<td>".opt_type($row["opt_type"])."</td>";
			echo "<td>".$row["old_disable_time"]."</td>";
			echo "<td>".$row["new_disable_time"]."</td>";
			echo "<td>".$row["months"]."月".$row["days"]."天</td>";
			echo "<td>".$row["money"]."</td>";
			echo "<td>".val_text("select name from staff where Id=".$row["admin"],"name")."</td>";
			echo "<td>".$row["note"]."</td>";
			
			echo "</tr>";
			echo "<table>";
		}
		
	}
}

?>
<link href="css/bootstrap.css" rel="stylesheet"> 
<script src="js/bootstrap.js"></script>
</head>
<body>
<?php

from_search();
table_user();

?>
</body>
</html>