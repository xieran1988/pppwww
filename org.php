<?php 
require_once("sql.php");
require_once("fun.php");
?>

<?php
if(login_check()==0) return;
if(rule_check(2) == 0) return ;

if ($_GET['t'] != 'manorg')
	return ;

//echo "UID=".$_REQUEST["Id_user"];
//if(login_check()==0) return;

function org_rows($obj, $id, $name){
	$org_id = $obj["Id"];
	echo "<td>$org_id</td>";
	
	$org_name=$obj["name"];
	echo "<td>$org_name</td>";
	
	$org_note = $obj["note"];
	echo "<td>$org_note</td>";
	echo "<td><a href='?node=$org_id'>查看</a></td>";
	/*
	if(!$obj[$name] || $obj[$name] == "") $obj[$name] = "NULL";
	echo "<div ><a href=\"?node=".$obj[$id]."\">"."<button>".$obj[$name]. "</button>"."</a> | </div>";
	*/
}
function get_title($id_node){
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
								echo "<a href=\"?node=".$sql_row["father_node"]."\"><button>上一级</button></a> ";
								echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=1\"><button class='btn-danger btn-del'>删除本级</button></a> ";
								echo "<a href=\"?node=".$sql_row["Id"]."&up_node=".$sql_row["father_node"]."&opt=4\"><button>修改本级</button></a> ";
								echo "<a href=\"?\"><button>返回</button></a> .";
								break;
								//org_rows($row, "Id", "name", "20px");
				}
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

$cur_node = $_GET["node"];
$opt = $_GET["opt"];
$input_name = $_GET["input_name"];

if(!$cur_node) $cur_node = "0";

if($opt){
				if($opt == "3" || $opt == "4"){
								get_title($cur_node);

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
if(!$opt || ($opt != "3" && $opt != "1" && $opt != "4")){
				get_title($cur_node);
				echo "<a href=\"org.php?node=".$cur_node."&opt=3\"><button>添加项目</button></a>";

				$sql_cmd = "select * from org where father_node = ".$cur_node;
				$sql_result = yjwt_mysql_select($sql_cmd);
				echo "<div id=\"org_list\">";
				echo "<table class=\"table table-condensed\">";
				echo "<tr>";
				echo "<td>项目ID</td><td>项目名称</td><td>项目备注</td><td>查看项目</td>";
				echo "</tr>";
				while($row = mysql_fetch_array($sql_result))
				{
					echo "<tr>";
					org_rows($row, "Id", "name");
					echo "</tr>";
				}
				echo "</table>";
				echo "</div>";
				
}
?>
