<?php

function form_list() {
	$s_time = date("Y-m-1");
	$e_time = date("Y-m-d");
	$str_key = $_REQUEST["key"];
	$opt_type = $_REQUEST["type"];
	if($_REQUEST["s"]) $s_time = $_REQUEST["s"];
	if($_REQUEST["e"]) $e_time = $_REQUEST["e"];

	echo "<form id=qrybis_form class='well search' method=\"get\" >";

	echo "<input name=\"t\" type=\"hidden\" value=\"findusr\"/>";
	echo "<table>";
	echo "<tr><td>关键字</td>";
	echo "<td><input name=\"key\" type=\"text\" value=\"$str_key\"/> [项目、UID、姓名、操作员...]</td></tr>";
	echo "<tr><td>时间</td>";		
	echo "<td><input name=\"s\" class=datetime type=\"text\" value=\"$s_time\"/> -> ";
	echo "<input name=\"e\" class=datetime type=\"text\" value=\"$e_time\"/>";
	echo "</tr>";
	echo "<tr><td>类型</td>";
	echo "<td><select name=\"type\">";
	dro_list_opt_type($opt_type);
	echo "</select></td>";
	echo "</tr>";
	echo "<tr><td><button id=\"input_sub\" type=\"submit\">查询</button></td></tr>";
	echo "</table>";
	echo "</form>";
}

function search_list(){
	form_list();
	$rows_num=0;
	$rows_id = 0;
	$startCount=0; 
	$perNumber = 8;
	$s_time=$_REQUEST["s"];
	$e_time=$_REQUEST["e"];
	$opt_type = $_REQUEST["type"];
	$str_key = $_REQUEST["key"]; 
	if($_REQUEST["page"]){
		$startCount = $_REQUEST["page"] * $perNumber;
		$rows_id = $_REQUEST["page"] * $perNumber;
	}
	//$_SERVER["REQUEST_URI"]
	//echo "<a href='".$_SERVER["SCRIPT_NAME"]."'>返回</a>";
	//
	#      $sql_select = "select user_info.name, user_info.addr, org.name as oname, speed.name as sname, bill.* ";
	#      $sql_select=$sql_select."from bill, user_info, org,speed ";
	#      $sql_select=$sql_select."where bill.uid=user_info.uid and bill.orgid = org.Id and bill.speed_id = speed.Id";


	echo "<table class=\"table table-condensed\">";
	echo "<tr>";
	echo "<td><b>NO.</td>";
	echo "<td><b>ID</td>";
	echo "<td><b>时间</b></td>";

	echo "<td><b>UID</b></td>";
	echo "<td><b>类型</b></td>";
	echo "<td><b>金额</b></td>";

	echo "<td><b>带宽</b></td>";
	echo "<td><b>姓名</b></td>";
	echo "<td><b>地址</b></td>";
	echo "<td><b>项目</b></td>";
	echo "<td><b>时长</b></td>";
	echo "<td><b>交易前到期时间</b></td>";
	echo "<td><b>交易后到期时间</b></td>";
	echo "<td><b>操作员</b></td>";
	echo "<td><b>备注</b></td>";
	echo "</tr>";

	$where = array();
	if ($opt_type != "-1") 
		$where[] = "opt_type = $opt_type";
	$where[] = "opt_time >= '$_GET[s]'";
	$where[] = "opt_time <= '$_GET[e]'";
	if ($str_key) 
		$where[] = "(u.uid = '$str_key' or org.name='$str_key' or u.name='$str_key' or u.addr like '%$str_key%')";
	$where = join(" and ", $where);
	$sql_select = 
		"select * from bill where $where " .
		"order by opt_time desc ".
		"limit $startCount, $perNumber"
		;

	$dataset = yjwt_mysql_select($sql_select);

	while ($dataset && $row = mysql_fetch_array($dataset)) {

		$cols = "u.name, u.addr, org.name as oname, b.*, sp.name as spname, s.name as stname ";
		$from = "bill as b inner join user_info as u on b.uid = u.uid ".
			"left join org on b.orgid = org.Id ".
			"left join staff as s on b.admin = s.Id " .
			"left join speed as sp on b.speed_id = sp.Id " .
			""
			;
		$where = "b.Id = $row[Id]";
		$sql_select = "select $cols from $from where $where ";
		$dataset2 = yjwt_mysql_select($sql_select);
		$row2 = mysql_fetch_array($dataset2);

		$rows_num += 1;
		$rows_id += 1;
		echo "<tr>";
		echo "<td>$rows_id</td>";
		echo "<td>".$row["Id"]."</td>";
		$optd = date("Y-m-d h", strtotime($row2["opt_time"]));
		echo "<td>".$optd."</td>";

		echo "<td>".$row2["uid"]."</td>";
		echo "<td>".opt_type($row2["opt_type"])."</td>";       
		echo "<td>".$row2["money"]."</td>";

		echo "<td>".$row2["spname"]."</td>";
		echo "<td>".$row2["name"]."</td>";
		echo "<td>".$row2["addr"]."</td>";
		echo "<td>".$row2["oname"]."</td>";
		$time_len = "";
		if($row2["months"] && $row2["months"] != "") $time_len = $time_len.$row2["months"]."月";
		if($row2["days"] && $row2["days"] != "") $time_len = $time_len.$row2["days"]."天";
		echo "<td>".$time_len."</td>";
		echo "<td>".date("Y-m-d h", strtotime($row2["old_disable_time"]))."</td>";
		echo "<td>".date("Y-m-d h", strtotime($row2["new_disable_time"]))."</td>";
		echo "<td>".$row2["stname"]."</td>";
		echo "<td>".$row2["note"]."</td>";
		echo "</tr>";
	}
	echo "</table>";
	if($_REQUEST["page"]) 
		echo "<a href='".$_SERVER["SCRIPT_NAME"]."?type=$opt_type&key=$str_key&s=$s_time&e=$e_time&page=".($_REQUEST["page"]-1)."'>上一页 | </a>";
	if ($rows_num >= $perNumber) 
		echo "<a href='".$_SERVER["SCRIPT_NAME"]."?type=$opt_type&key=$str_key&s=$s_time&e=$e_time&page=".($_REQUEST["page"]+1)."'>下一页</a>";

}


if ($_GET['t'] == 'qrybis') {
	if(!$_GET["s"] || !$_GET["e"]){
		form_list();
	}
	if($_GET["s"] &&  $_GET["e"]){
		search_list();
	}
}
?>

