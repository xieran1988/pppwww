<?php 
require_once("sql.php");
require_once("fun.php");
?>

<?php
if(login_check()==0) return;
if(rule_check(2) == 0) return ;

if ($_GET['t'] != 'manorg')
	return ;

$opt = $_GET["opt"];
$input_name = $_GET["input_name"];


function org_list(){
	$cur_node = $_GET["node"];
	if(!$cur_node) $cur_node = "0";
	get_title();
	echo "<a href=\"org.php?node=".$cur_node."&opt=3\"><button class=btn-primary>添加项目</button></a>";
	echo " <a href=\"?opt=6\"><button>对比收入</button></a>";

	if ($opt == '6') {
		$sql_cmd = "select * from org where father_node = ".$cur_node;
		$sql_result = yjwt_mysql_select($sql_cmd);
		echo "<div class=graph>";
		while($row = mysql_fetch_array($sql_result)) {
			echo "$row[name],";
		}
		echo "</div>";
	} else {
		$sql_cmd = "select * from org where father_node = ".$cur_node;
		$sql_result = yjwt_mysql_select($sql_cmd);
		echo "<div id=\"org_list\">";
		echo "<table class=\"table table-condensed\">";
		echo "<tr>";
		echo "<td>项目ID</td><td>项目名称</td><td>项目资费</td><td>项目备注</td><td>分离项目</td><td>查看项目</td>";
		echo "</tr>";
		while($row = mysql_fetch_array($sql_result))
		{
			echo "<tr>";
			org_rows($row, "Id", "name");
			echo "</tr>";
		}
		echo "</table>";
		break_org_list();
		echo "</div>";
	}
}
	
function org_rows($obj, $id, $name){
	
	
	$org_id = $obj["Id"];
	echo "<td>$org_id</td>";
	
	$org_name=$obj["name"];
	echo "<td>$org_name</td>";
	
	$org_tc = $obj["pro"];
	if($org_tc == "" || $org_tc == "-1") $org_tc = "Inheritance";
	echo "<td>$org_tc</td>";
	
	$org_note = $obj["note"];
	$fn = $obj["father_node"];
	echo "<td>$org_note</td>";
	echo "<td><a href='?node=$org_id&father_node=$fn&opt=25'>分离</a></td>";
	$cur_map = "".$_GET["orgmap"].".".$org_id;
	//$last_map = substr($_GET["orgmap"], 0, strrpos($_GET["orgmap"], "."));
	//$cur_map = $last_map.".".$org_id;
	echo "<td><a href='?node=$org_id&father_node=$fn&orgmap=$cur_map'>查看</a></td>";
}
function get_title(){
	$id_node = $_GET["node"];
	if(!$id_node) $id_node = "0";
	
	$sql_txt = "select * from org where Id = ".$id_node;
	$sql_vaule = yjwt_mysql_select($sql_txt);
	while($sql_row = mysql_fetch_array($sql_vaule))
	{
		if(!$sql_row["name"] || $sql_row["name"] == "") $sql_row["name"] = "NULL";
		echo "<div class=well>";
		#echo "[<a href=\"?node=".$sql_row["Id"]."\">".$sql_row["name"]."</a>]";
		echo "<h3>".$sql_row["name"]."</h3>";
		echo $sql_row["note"]."";
		echo "</div>";
		$last_map = substr($_GET["orgmap"], 0, strrpos($_GET["orgmap"], "."));
		echo "<a href=\"?node=".$sql_row["father_node"]."&orgmap=$last_map\"><button>上一级项目</button></a> ";
		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=1\"><button class='btn-danger btn-del'>删除项目</button></a> ";
		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=4\"><button>修改项目</button></a> ";

		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=26\"><button>分离子项目</button></a> ";
		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=27\"><button>聚合项目</button></a> ";
		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=28\"><button>分离本项目用户</button></a> ";
		echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=29\"><button>聚合用户</button></a> ";
		echo "<a href=\"?\"><button>返回</button></a> ";
		break;
		//org_rows($row, "Id", "name", "20px");
	}
}

function break_org(){
	if(!$_GET["node"]) return;
	$sql_update = "UPDATE `org` SET father_node='-2' where Id=".$_GET["node"];
	//echo $sql_update;
	yjwt_mysql_do($sql_update);
	echo "<script type=\"text/javascript\">";
	$page = $_SERVER["SCRIPT_NAME"]."?t=manorg&node=".$_GET["up_node"];
	echo "location.href='$page';";
	echo "</script>";
}
function break_chi_org(){
	if(!$_GET["node"]) return;
	$sql_update = "UPDATE `org` SET father_node='-2' where father_node=".$_GET["node"];
	yjwt_mysql_do($sql_update);
	echo "<script type=\"text/javascript\">";
	$page = $_SERVER["SCRIPT_NAME"]."?t=manorg&node=".$_GET["node"];
	echo "location.href='$page';";
	echo "</script>";
}

function join_org(){
	if(!$_GET["node"]) return;
	$sql_select = "select * from user_info where orgid =".$_GET["node"];
	if(have_row($sql_select)) {
		echo "<script type=\"text/javascript\">";
		echo "alert('本项目存在用户不能聚合');";
		echo "</script>";
	}else{
		$fa_node = $_GET["node"];
		$sql_update = "UPDATE `org` SET father_node='$fa_node' where father_node=-2";
		yjwt_mysql_do($sql_update);
	}
	echo "<script type=\"text/javascript\">";
	$page = $_SERVER["SCRIPT_NAME"]."?t=manorg&node=".$_GET["node"];
	echo "location.href='$page';";
	echo "</script>";
}
function break_user_info(){
	if(!$_GET["node"]) return;
	$sql_update = "UPDATE `user_info` SET orgid='-2' where orgid=".$_GET["node"];
	yjwt_mysql_do($sql_update);
	echo "<script type=\"text/javascript\">";
	$page = $_SERVER["SCRIPT_NAME"]."?t=manorg&node=".$_GET["node"];
	echo "location.href='$page';";
	echo "</script>";
}
function join_user_info(){
	if(!$_GET["node"]) return;
	$sql_select = "select * from `org` where father_node =".$_GET["node"];
	
	if(have_row($sql_select)) {
		echo "<script type=\"text/javascript\">";
		echo "alert('本项目存在子项目不能聚合用户');";
		echo "</script>";
	}else{
		$orgid = $_GET["node"];
		$orgmap = $_GET["orgmap"];
		$sql_update = "UPDATE `user_info` SET orgid='$orgid', orgmap='$orgmap' where orgid=-2";
		yjwt_mysql_do($sql_update);
	}
	echo "<script type=\"text/javascript\">";
	$page = $_SERVER["SCRIPT_NAME"]."?t=manorg&node=".$_GET["node"];
	echo "location.href='$page';";
	echo "</script>";
}
function break_org_list(){
	$sql_select = "select * from `org` where father_node=-2";
	$user_result = yjwt_mysql_select($sql_select);
	$flag = 1;
	while($user_result && $row = mysql_fetch_array($user_result)){
		if($flag) echo "分离状态:[";
		echo $row["name"].".";
		if($flag) $flag = 0;
	}
	if(!$flag) echo "]</div>";
}

function have_user($orgid){
	$user_sql = "select * from user_info where orgid=".$orgid." limit 1";
	$user_result = yjwt_mysql_select($user_sql);
	while($rowddd = mysql_fetch_array($user_result))
	{
		return 1;
	}
	return 0;
}



//分离项目
if($opt =="25"){
	break_org();
	return;
}
//分离子项目
if($opt == "26"){
	break_chi_org();
	return;
}
//聚合项目
if($opt == "27"){
	join_org();
	return;
}
//分离用户
if($opt == "28"){
	break_user_info();
	return;
}
//聚合用户
if($opt == "28"){
	join_user_info();
	return;
}
$cur_node = $_GET["node"];
if(!$cur_node) $cur_node = "0";
if($opt){
	if($opt == "3" || $opt == "4"){
		get_title();

		?>
						<div>
						<?php
						$optv = 2;
		$chang_name;
		$chang_note;
		$show_note;
		$pro_group;
		/////////////////////////////////////////////////
		if($opt == "3"){
			//opt=3, 呈现插入操作表单
			echo "<p>添加项目</p>";
			$optv = 2;
			$show_note = $_GET["up_node"];
		}
		else {
			$sql_ctxt = "select * from org where Id = ".$cur_node;
			$sql_cvaule = yjwt_mysql_select($sql_ctxt);
			if($sql_crow = mysql_fetch_array($sql_cvaule)){
				$chang_name = $sql_crow["name"];
				$chang_note = $sql_crow["note"];
				$pro_group =  $sql_crow["pro"];
			}
			//opt=4, 呈现修改操作表单
			echo "<p>项目修改</p>";
			$optv = 5;
			$show_note = $cur_node;
		}
		?>
<form id="form1" method="get" action="?">
<table>
<tr>
<td valign="top">名称：</td><td><input name="input_name" type="text" style="width:200px;" value="<?php echo $chang_name; ?>" /></td>
</tr>

<tr>
<td valign="top">资费识别码：</td><td><select name="pro"><?php dro_list("select `group` from `tariff` group by `group`",$pro_group, "group","group"); ?></select></td>
</tr>

<tr>
<td valign="top">备注：</td><td><textarea  name="input_note" style="width:200px; height:150px;" ><?php echo $chang_note; ?></textarea></td>
</tr><tr>
<td valign="top">操作：</td><td><input id="input_sub" type="submit" value="提交" /></td>
</tr>
</table>
<input name="opt" type="hidden" value="<?php echo $optv; ?>" />
<input type="hidden" name="node" value="<?php echo $cur_node; ?>" />
<input type="hidden" name="up_node" value="<?php echo $show_note; ?>" />

</form>
</div>
<?php
	}
	else if($opt == "2"){
		//opt=2，插入操作
		$sql_insert = "INSERT INTO org(name, father_node, pro, note ) VALUES ('".$_GET["input_name"]."',".$_GET["node"].",'".$_GET["pro"]."','".$_GET["input_note"]."');";
		//echo $sql_insert;
		if(have_user($_GET["node"])){
			echo "<script type=\"text/javascript\"> alert('存在有效用户不能添加项目'); </script>";
		}else{
			yjwt_mysql_do($sql_insert);
		}
	}
	else if($opt == "1" || $opt == "5"){
		$sql_t;
		if($opt == "1"){
			//opt=1,删除操作
			$sql_t ="select father_node from org where  father_node =".$_GET["node"];
			$tmp_result = yjwt_mysql_select($sql_t);
			$tmp_rows = mysql_fetch_array($tmp_result);

			if($tmp_rows){
				echo "<script type=\"text/javascript\"> alert('存在子项目不允许删除'); </script>";
			}
			else if(have_user($_GET["node"])){
				echo "<script type=\"text/javascript\"> alert('存在有效用户不能删除'); </script>";
			}
			else{
				$sql_t = "DELETE FROM org where Id=".$_GET["node"];
				yjwt_mysql_do($sql_t);
			}

		}
		else if($opt == "5"){
			//opt=5,更新操作
			$sql_t = "UPDATE org SET name='".$_GET["input_name"]."',pro='".$_GET["pro"]."', note='".$_GET["input_note"]."' where Id=".$_GET["node"];
			yjwt_mysql_do($sql_t);
		}
?>
<script type="text/javascript">
location.href = "<?php echo "?t=manorg&node=".$_GET["up_node"]; ?>";
</script>
<?php
	} 
}
if($opt != "3" && $opt != "1" && $opt != "4") org_list();
?>
