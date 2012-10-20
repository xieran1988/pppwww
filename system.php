<?php 
require_once("sql.php"); 
require_once("obj.php");
if(login_check()==0) return;
if(rule_check(2) == 0) return ;
function user_password_form(){
				echo "<form method=\"post\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";

				echo "<table>";
				echo "<tr>";
				echo "<td>UID:</td><td>".$_REQUEST["Id_user"]."</td>";
				echo "</tr><tr>";
				echo "<td>原口令:</td><td><input name=\"old_pass\" type=\"password\" /></td>";
				echo "</tr><tr>";
				echo "<td>新口令:</td><td><input name=\"new_pass1\" type=\"password\" /></td>";
				echo "</tr><tr>";
				echo "<td>新口令:</td><td><input name=\"new_pass2\" type=\"password\" /></td>";
				echo "</tr><tr>";
				echo "<td>操作:</td><td><button id=\"input_sub\" type=\"submit\">提交</button></td>";
				echo "</tr>";
				echo "</table>";
				echo "<input name=\"opt\" type=\"hidden\" value=\"7\" />";
				echo "</form>";
}
function user_password_do(){
				$oldp = $_REQUEST["old_pass"];
				$newp1 = $_REQUEST["new_pass1"];
				$newp2 = $_REQUEST["new_pass2"];
				//防sql注入
				$oldp = str_replace("'","''", $oldp);
				$oldp = str_replace("\"","\"\"", $oldp);
				$newp1 = str_replace("'","''", $newp1);
				$newp1 = str_replace("\"","\"\"", $newp1);
				if($newp1 != $newp2){
								echo "<script type=\"text/javascript\"> alert('新口令不匹配'); </script>";
								user_password_form();
								return;
				}
				$user_id = $_REQUEST["Id_user"];
				$sql_select = "select * from staff where Id='".$user_id."' and password='".$oldp."'";
				//echo $sql_select;
				if(have_row($sql_select)){
								//setcookie("php_user", $row["flag"], time()-1800);//半小时,这个语句必需要html这前
								//setcookie("Id_user", $row["Id"], time()-1800);
								$sql_update = "update staff set password='".$newp2."' where Id=".$_REQUEST["Id_user"];
								yjwt_mysql_do($sql_update);
								echo "<script type=\"text/javascript\">";
								echo "alert('你的密码修改成功');";
								echo "location.href ='$optt';";
								echo "</script>";
				}else{
								echo "<script type=\"text/javascript\"> alert('原口令不匹配'); </script>";
								user_password_form();
				}
}

?>
<?php 
if(login_check()==0) return;
if(rule_check(2) == 0) return ;

$optt = "t=$_GET[t]";
if ($_GET['t'] == 'manstaff') {
	$_GET['id'] = '1';
} else if ($_GET['t'] == 'manprice') {
	$_GET['id'] = '2';
} else if ($_GET['t'] == 'manband') {
	$_GET['id'] = '3';
} else if ($_GET['t'] == 'modpass') {
	$_GET['id'] = '4';
} else 
	return ;


if(!$_GET["id"]){
				echo "<a href='system.php?id=4'>修改我的密码</a><hr/><a href='system.php?id=1'>员工</a>.<a href='system.php?id=2'>资费</a>.<a href='system.php?id=3'>带宽</a>";
}else{
				echo "<a href=?><button>返回</button></a>";
}
if($_GET["id"] && $_GET["id"] == "4") {
				user_password_form();
				return;
}
if($_REQUEST["opt"] && $_REQUEST["opt"]=="7") {
				user_password_do();
				return;
}

//操作 添加
if($_GET["opt"] && ($_GET["opt"] == "1" || $_GET["opt"] == "10") && $_GET["id"]){
				//
				echo "<form class=\"form-horizontal\" id=\"form1\" method=\"get\" action=\"system.php\">";
				echo "<table >";
				if($_GET["id"] == "1"){
								$staff_name;
								$staff_pho;
								$staff_addr;
								$staff_note;
								$staff_flag;
								if($_GET["opt"] == "10"){
												$sql_select = "select * from staff where id=".$_GET["item"];
												$dataset = yjwt_mysql_select($sql_select);
												while($row = mysql_fetch_array($dataset)){
																$staff_name = $row["name"];
																$staff_pho = $row["pho"];
																$staff_addr = $row["addr"];
																$staff_flag = $row["flag"];
																$staff_note = $row["note"];
																break;
												}
								}
								echo "<tr><td>姓名：</td><td><input name=\"name\" type=\"text\" style=\"width:200px;\" value=\"".$staff_name."\" /></td></tr>";
								echo "<tr><td>电话：</td><td><input name=\"pho\" type=\"text\" style=\"width:200px;\" value=\"".$staff_pho."\" /></td></tr>";
								echo "<tr><td>住址：</td><td><input name=\"addr\" type=\"text\" style=\"width:200px;\" value=\"".$staff_addr."\" /></td></tr>";

								echo "<tr><td>职类：</td><td><select name=\"flag\">";
								dro_list_staff($staff_flag);
								echo "</select></td></tr>";

								echo "<tr><td>备注</td><td><textarea  name=\"note\" style=\"width:200px; height:150px;\" >".$staff_note."</textarea></td></tr>";

				}else if($_GET["id"] == "2"){

								$tariff_group;
								$tariff_name;
								$tariff_speed;
								$tariff_months;
								$tariff_days;
								$tariff_money;
								$tariff_installation_fee;
								$tariff_note;
								if($_GET["opt"] == "10"){
												$sql_select = "select * from tariff where id=".$_GET["item"];
												$dataset = yjwt_mysql_select($sql_select);	
												while($row = mysql_fetch_array($dataset)){

																$tariff_group = $row["group"];
																$tariff_name = $row["name"];
																$tariff_speed = $row["speed"];
																$tariff_months = $row["months"];
																$tariff_days = $row["days"];
																$tariff_money = $row["money"];
																$tariff_installation_fee = $row["installation_fee"];
																$tariff_note = $row["note"];
																break;

												}
								}
								echo "<tr><td>资费识别码：</td><td><input name=\"group\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_group."\" /></td></tr>";
								echo "<tr><td>资费名称：</td><td><input name=\"name\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_name."\" /></td></tr>";

								echo "<tr><td>带宽：</td><td><select name=\"speed\">";
								dro_list("select * from speed", $tariff_speed, "name", "Id" );
								echo "</select></td></tr>";

								echo "<tr><td>资费时长：</td><td><input name=\"months\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_months."\" />月<input name=\"days\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_days."\" />天</td></tr>";
								echo "<tr><td>资费金额：</td><td><input name=\"money\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_money."\" /></td></tr>";
								echo "<tr><td>初装费：</td><td><input name=\"installation_fee\" type=\"text\" style=\"width:200px;\" value=\"".$tariff_installation_fee."\" /></td></tr>";
								echo "<tr><td>备注</td><td><textarea  name=\"note\" style=\"width:200px; height:150px;\" >".$tariff_note."</textarea></td></tr>";
				}
				else if($_GET["id"] == "3"){
								$speed_name;
								$speed_up_speed;
								$speed_down_speed;
								$speed_lan_speed_tx;
								$speed_lan_speed_rx;
								$speed_lan_note;
								if($_GET["opt"] == "10"){
												$sql_select = "select * from speed where id=".$_GET["item"];
												$dataset = yjwt_mysql_select($sql_select);
												while($row = mysql_fetch_array($dataset)){
																$speed_name = $row["name"];
																$speed_up_speed = $row["up_speed"];
																$speed_down_speed = $row["down_speed"];
																$speed_lan_speed_tx = $row["lan_speed_tx"];
																$speed_lan_speed_rx = $row["lan_speed_rx"];
																$speed_lan_note = $row["note"];
																break;

												}
								}
								echo "<tr><td>带宽识别名称：</td><td><input name=\"name\" type=\"text\" style=\"width:200px;\" value=\"".$speed_name."\" /></td></tr>";
								echo "<tr><td>带宽上行：</td><td><input name=\"up_speed\" type=\"text\" style=\"width:200px;\" value=\"".$speed_up_speed."\" /></td></tr>";
								echo "<tr><td>带宽下行：</td><td><input name=\"down_speed\" type=\"text\" style=\"width:200px;\" value=\"".$speed_down_speed."\" /></td></tr>";
								echo "<tr><td>网内上行：</td><td><input name=\"lan_speed_tx\" type=\"text\" style=\"width:200px;\" value=\"".$speed_lan_speed_tx."\" /></td></tr>";
								echo "<tr><td>网内下行：</td><td><input name=\"lan_speed_rx\" type=\"text\" style=\"width:200px;\" value=\"".$speed_lan_speed_rx."\" /></td></tr>";
								echo "<tr><td>备注</td><td><textarea  name=\"note\" style=\"width:200px; height:150px;\" >".$speed_lan_note."</textarea></td></tr>";
				}
				echo "<tr><td>操作</td><td><input id=\"input_sub\" type=\"submit\" value=\"提交\" /></td></tr>";
				if($_GET["opt"] == "1")
								echo "<input name=\"opt\" type=\"hidden\" value=\"4\" />";
				else if($_GET["opt"] == "10"){
								echo "<input name=\"opt\" type=\"hidden\" value=\"14\" />";
								echo "<input name=\"item\" type=\"hidden\" value=\"".$_GET["item"]."\" />";
				}
				echo "<input type=\"hidden\" name=\"id\" value=\"".$_GET["id"]."\" />";
				echo "</form>";
				echo "</table>";
}
/*添加*/
if($_GET["id"] && $_GET["opt"] && $_GET["opt"] == "4"){
				$insert_sql;
				if($_GET["id"] == "1") 
								$insert_sql = "INSERT INTO staff(name, pho, addr, note, password ,flag) VALUES ('".$_GET["name"]."','".$_GET["pho"]."','".$_GET["addr"]."','".$_GET["note"]."', '123456',".$_GET["flag"].")";
				if($_GET["id"] == "2"){
								$insert_sql = "INSERT INTO `tariff`(`group`, `name`, `speed`, `months`, `days`, `money`, `installation_fee`, `note`) ";
								$insert_sql = $insert_sql."VALUES ('".$_GET["group"]."','".$_GET["name"]."',".$_GET["speed"].",".$_GET["months"].",".$_GET["days"].",".$_GET["money"].",".$_GET["installation_fee"].",'".$_GET["note"]."')";
				}
				if($_GET["id"] == "3"){
								$insert_sql = "INSERT INTO speed(name, up_speed, down_speed, lan_speed_tx, lan_speed_rx, note )";
								$insert_sql = $insert_sql."VALUES ('".$_GET["name"]."',".$_GET["up_speed"].",".$_GET["down_speed"].",".$_GET["lan_speed_tx"].",".$_GET["lan_speed_rx"].",'".$_GET["note"]."')";
				}
				yjwt_mysql_do($insert_sql);
				jmp("?t=$_GET[t]");
}
/*删除*/
if($_GET["id"] && $_GET["opt"] && $_GET["opt"] == "11"){
				$delect_sql;
				if($_GET["id"] == "1"){
								$delect_sql = "DELETE FROM `staff` where Id=".$_GET["item"];
								yjwt_mysql_do($delect_sql);
				}
				if($_GET["id"] == "2"){
								$delect_sql = "DELETE FROM `tariff` where Id=".$_GET["item"];
								if(have_row() == 0) echo "<script type=\"text/javascript\"> alert('资费中正使用本带宽模板不能删除'); </script>";
								else yjwt_mysql_do($delect_sql);
				}
				if($_GET["id"] == "3"){
								$delect_sql = "DELETE FROM `speed` where Id=".$_GET["item"];
								yjwt_mysql_do($delect_sql);
				}

}
/*更新*/
if($_GET["id"] && $_GET["opt"] && $_GET["opt"] == "14"){
				$update_sql;
				if($_GET["id"] == "1"){
								$update_sql="UPDATE staff SET name='".$_GET["name"]."', pho='".$_GET["pho"]."',addr='".$_GET["addr"]."', note='".$_GET["note"]."', flag=".$_GET["flag"];
								$update_sql = $update_sql." where Id=".$_GET["item"];
				}

				if($_GET["id"] == "2"){
								$update_sql="UPDATE `tariff` SET `group`='".$_GET["group"]."', `name`='".$_GET["name"]."', `speed`=".$_GET["speed"].", `months`=".$_GET["months"].", `days`=".$_GET["days"].", `money`=".$_GET["money"].", `installation_fee`=".$_GET["installation_fee"].", `note`='".$_GET["note"]."'";
								$update_sql = $update_sql." where Id=".$_GET["item"];
				}

				if($_GET["id"] == "3"){
								$update_sql="UPDATE speed SET name='".$_GET["name"]."', up_speed=".$_GET["up_speed"].", down_speed=".$_GET["down_speed"].", lan_speed_tx=".$_GET["lan_speed_tx"].", lan_speed_rx=".$_GET["lan_speed_rx"].", note='".$_GET["note"]."' ";
								$update_sql = $update_sql." where Id=".$_GET["item"];
				}
				//echo $update_sql;
				yjwt_mysql_do($update_sql);
}



//显示列表
if($_GET["id"] && (!$_GET["opt"] || $_GET["opt"] =="4" || $_GET["opt"] =="14" || $_GET["opt"] =="11") ){
				echo "<a href='?$optt&id=".$_GET["id"]."&opt=1'><button class=btn-primary>添加</button></a>";
				//列表

				$sql_select;
				if($_GET["id"] == "1") $sql_select = "select * from staff";
				/*if($_GET["id"] == "2") $sql_select = "select * from tariff";*/

				if($_GET["id"] == "2") $sql_select = "select tariff.*,speed.name as speed_name from tariff, speed where tariff.speed = speed.Id";
				if($_GET["id"] == "3") $sql_select = "select * from speed";
				$dataset = yjwt_mysql_select($sql_select);
				echo "<table class=\"table table-condensed\">";

				if($_GET["id"] == "1"){
								echo "<tr><td><b>编号</b></td><td><b>姓名</b></td><td><b>职类</b></td><td><b>电话</b></td><td><b>住址</b></td><td><b>备注</b></td><td><b>M.D</b></td></tr>";
								while($row = mysql_fetch_array($dataset)){
												echo "<tr>";
												echo "<td>".$row["Id"]."</td>";
												echo "<td>".$row["name"]."</td>";
												echo "<td>".staff_name($row["flag"])."</td>";
												echo "<td>".$row["pho"]."</td>";
												echo "<td>".$row["addr"]."</td>";
												echo "<td>".$row["note"]."</td>";
												echo "<td>";
												$url = "?id=1&item=".$row["Id"]."&opt=";
												btn_edit_del($url."10", $url."11");
												echo "</td>";
												echo "</tr>";
								}
				}
				if($_GET["id"] == "2"){
								echo "<tr><td><b>编号</b></td><td><b>资费识别码</b></td><td><b>资费名称</b></td><td><b>带宽</b></td><td><b>资费时长</b></td>";
								echo "<td><b>资费金额</b></td><td><b>初装费</></td><td><b>备注</b></td><td><b>M.D</b></td></tr>";
								while($row = mysql_fetch_array($dataset)){
												echo "<tr>";
												echo "<td>".$row["Id"]."</td>";
												echo "<td>".$row["group"]."</td>";
												echo "<td>".$row["name"]."</td>";
												echo "<td>".$row["speed_name"]."</td>";
												echo "<td>".$row["months"]."月".$row["days"]."天</td>";
												echo "<td>RMB".$row["money"]."元</td>";
												echo "<td>RMB".$row["installation_fee"]."元</td>";
												echo "<td>".$row["note"]."</td>";
												echo "<td>";
												$url = "?id=2&item=".$row["Id"]."&opt=";
												btn_edit_del($url."10", $url."11");
												echo "</td>";
												echo "</tr>";
								}
				}

				if($_GET["id"] == "3"){
								echo "<tr><td><b>编号</b></td><td><b>带宽名称</b></td><td><b>带宽上行</b></td><td><b>带宽下行</b</td><td><b>网内带宽上行</b></td>";
								echo "<td><b>网外带宽下行</b></td><td><b>备注</b></td><td><b>M.D</b></td></tr>";
								while($row = mysql_fetch_array($dataset)){
												echo "<tr>";
												echo "<td>".$row["Id"]."</td>";
												echo "<td>".$row["name"]."</td>";
												echo "<td>".$row["up_speed"]."kbps</td>";
												echo "<td>".$row["down_speed"]."kbps</td>";
												echo "<td>".$row["lan_speed_tx"]."kbps</td>";
												echo "<td>".$row["lan_speed_rx"]."kbps</td>";
												echo "<td>".$row["note"]."</td>";
												echo "<td>";
												$url = "?id=3&item=".$row["Id"]."&opt=";
												btn_edit_del($url."10", $url."11");
												echo "</td>";
												echo "</tr>";
								}
				}
				echo "</table>";
}
?>
