<?php
require_once("sql.php"); 
require_once("fun.php");
?>
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

function from_search() {
	$str_key = $_REQUEST["key"];
	$req_online = $_REQUEST["online"];
	if($req_online) $b_online = "checked=true";
	else $b_online = "";
	$req_disable = $_REQUEST["disable_time"];
	if($req_disable) $b_disable = "checked=true";
	else $b_disable = "";
	$req_opt = $_REQUEST["opt"];
	$req_page = $_REQUEST["page"];
	
	#var_dump($_REQUEST);
	echo "<form class='form well search' method=\"post\" >";
	echo "<input type=text class='input large' ";
	echo "	placeholder='用户名,身份证号,IP,MAC,地址,小区名称 ...' name=key value='$str_key'/>";
	online_select($req_online);
	disable_select($req_disable);
	/*
	echo " <select name=org >";
		dro_list("select * from org", $_POST[org], "name", "Id");
	echo "</select>";
	*/
	echo " <button class='btn btn-primary' type=submit >查找</button>";
	echo "<input name=\"opt\" type=\"hidden\" value=\"1\" />";
	echo "</form>";
}

function table_user() {
  $startCount = 0;
	$perNumber = 10;
	$num = 0;
	$str_key = $_REQUEST["key"];
	$req_online = $_REQUEST["online"];
	$req_disable = $_REQUEST["disable_time"];
	$req_opt = $_REQUEST["opt"];
	$req_page = $_REQUEST["page"];

	if ($_REQUEST["page"]) $startCount = $_REQUEST["page"]*$perNumber;
	$sql_select = "select *, user_info.name as info_name ".
								"from user_pppoe inner join user_info ".
								"on user_pppoe.id = user_info.uid ";
	$where = array();
	if ($_POST[org] != '-1' && $_POST[org] != '') 
		$where[] = "orgid = '$_POST[org]'";
	if ($_REQUEST["opt"]) {
		$where = online_disable($where, $req_online, $req_disable);
		if ($str_key) {
			$a = array("user_info.name", "idcar", "username", "addr", "phone");
			if (stripos($str_key, ".") > 1) $a[] = "last_ip";
			if (strlen($str_key == 23)) $a[] = "mac";
			$where[] = "(" . join(" or ", (array_map(
				function($k) use($str_key) { return "$k like '%$str_key%'";}, $a))) . ")";
		}
	}
	if ($str_key) {
		$dataset = yjwt_mysql_select("select Id from org where name = '$str_key'");
		$row = mysql_fetch_array($dataset);
		if ($row[Id]) {
			$where[] = "orgid = $row[Id]";
		}
	}
	if ($_GET["uid"]) 
		$where[] = "uid='$_GET[uid]'";
	if (count($where)) {
		$sql_select .= " where " . join(" and ", $where);
	}
	$user_count;
	if (!$_GET[uid]){
		$user_count = val_text(str_replace("*","count(*) as number",$sql_select), "number");
		$sql_select = $sql_select." order by user_pppoe.Id desc limit ".$startCount.",".$perNumber;
	}
	#echo $sql_select;
	$dataset = yjwt_mysql_select($sql_select);
	echo "<table class=\"table table-condensed\">";
	if($dataset){
		echo "<tr>";
		//echo "<td><b>序号</b></td>";
		echo "<td><b>UID</b></td>";
		echo "<td><b>帐户</b></td>";
		echo "<td><b>在线</b></td>";
		echo "<td><b>姓名</b></td>";
		#echo "<td><b>地址</b></td>";
		#echo "<td><b>电话</b></td>";
		#echo "<td><b>证件号</b></td>";
		echo "<td><b>IP</b></td>";
		echo "<td><b>带宽</b></td>";
		echo "<td><b>到期时间</b></td>";
		#echo "<td><b>MAC</b></td>";
		#echo "<td><b>服务器名称</b></td>";
		echo "</tr>";
	}
	while($dataset && $row = mysql_fetch_array($dataset)){
		echo "<tr>";
		//echo "<td>".$num."</td>";
		echo "<td><a href='".$_SERVER["SCRIPT_NAME"]."?uid=".$row["uid"]."'>".$row["uid"]."</a></td>";
		echo "<td>".$row["username"]."</td>";
		//user_online
		echo "<td>".user_online($row["online"])."</td>";
		$user_name = $row["info_name"];
		$user_addr = $row["addr"];
		$user_phone = $row["phone"];
		$str_note = $row["note"];
		$mac= $row["mac"];
		$server_name= $row["service_name"];
		$server_ip = $row["service_ip"];
		if(!$str_note) $str_note = "无备注信息";
		$id_car = $row["idcar"];
		$uinfo = "地址:$user_addr<br/>电话:$user_phone<br/>身份证:$id_car<br/>MAC:$mac<br/>sName:$server_name<br/>sIP:$server_ip<br/>备注:$str_note";
		echo "<td><div style='float:left;' rel=\"popover\" data-content=\"$uinfo\" data-original-title=\"联系方式\">$user_name</div></td>";
		#echo "<td>".$row["addr"]."</td>";
		#echo "<td>".$row["phone"]."</td>";
		#echo "<td>".$row["idcar"]."</td>";
		echo "<td>".$row["last_ip"]."</td>";
		echo "<td>".$row["down_speed"]."kbps</td>";
		echo "<td>".date("Ymd h",strtotime($row["disable_time"]))."</td>";
		#echo "<td>".$row["mac"]."</td>";
		#echo "<td>".$row["service_name"]."</td>";
		echo "</tr>";
		$num += 1;
		if($_GET["uid"]) break;
	}
	if($num==0) echo "<tr><td>未找到数据</td></tr>";
	echo "</table>";
	if($_REQUEST["page"]) echo "<a href='".$_SERVER["SCRIPT_NAME"]."?opt=$req_opt&key=$str_key&online=$req_online&disable_time=$req_disable&page=".($_REQUEST["page"]-1)."'>上一页</a> | ";
	if($num == $perNumber) echo "<a href='".$_SERVER["SCRIPT_NAME"]."?opt=$req_opt&key=$str_key&online=$req_online&disable_time=$req_disable&page=".($_REQUEST["page"]+1)."'>下一页</a>";
	if($user_count) echo " <font color=\"red\">{共$user_count}</font>";
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
		echo "</table>";
		
		$sql_select = "select * from bill where uid=".$_GET["uid"];
		$dataset = yjwt_mysql_select($sql_select);
		if($dataset && $row = mysql_fetch_array($dataset)){
			//echo "<header class=\"jumbotron subhead\" id=\"overview\">";
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
			echo "</table>";
		}
		
	}
}

if ($_GET['t'] != 'findusr')
	return ;
from_search();
table_user();

?>
