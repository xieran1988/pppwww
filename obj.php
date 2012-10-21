<?php
require_once("fun.php");
function get_tc_list($orgmap, $speed){
	$list_num = 0;
	while($pos = strrpos($orgmap, ".")){
		if($pos > 0){
			$node_id = substr($orgmap, $pos + 1);
			$orgmap = substr($orgmap, 0, $pos);
		
			$sql_select = "select * from org where Id=".$node_id;
			$pro = val_text($sql_select, "pro");
			if($pro != "NULL" && $pro != "ERROR"){
				$sql_select = "select * from `tariff` where `group`='$pro' and `speed`=$speed";
				echo $sql_select;
				$dataset = yjwt_mysql_select($sql_select);
				while($dataset && $row = mysql_fetch_array($dataset)){
					$list_num += 1;
					$opt_name = $row["name"]."{￥".$row["money"]."元".$row["months"]."月".$row["days"]."天 开户费:".$row["installation_fee"]."元|".$row["note"]."}";
					$opt_value = $row["Id"];
					echo "<option value=\"$opt_value\">$opt_name</option>";
				}
				break;
			}			
		}
		else break;
	}
	echo "<option value=\"-2\">手工录入</option>";
	return $list_num;
}
function business_form(){
    echo "<a href='business_management.php?id=1'>获取帐号</a>.";
	echo "<a href='business_management.php?id=2'>业务录入</a>.";
	echo "<a href='business_management.php?id=3'>查找用户信息</a>";
	echo "<hr/>";
}

/*
function href() {
	$r = 'href=?';
	$res = $_GET;
	$args = func_get_args();
	for ($i = 0; $i < count($args); $i += 2) {
		$k = $args[$i];
		$v = $args[$i+1];
		$res[$k] = $v;
	}
	foreach ($res as $k => $v) {
		$r = "$r$k=$v&";
	}
	return $r;
}
*/

function org_note_print($node_id, $script_opt){
	$last_node = $node_id;
	$sql_select = "select * from org where father_node=".$node_id;
	$dataset = yjwt_mysql_select($sql_select);
	while($row = mysql_fetch_array($dataset)){	
		$last_node = $row["Id"];
		echo "<a href=" . $script_opt . $row["Id"]."&orgmap=".$_GET["orgmap"].".".$row["Id"].">";
		echo "<button> ". $row["name"] . "</button>" ;
		echo "</a>";
		//_SERVER["SCRIPT_NAME"]
	}
	if($last_node == $node_id) return $node_id;
	else return "-1";
}
function org_note_head($node_id, $script_opt){
	$sql_select = "select * from org where Id=".$node_id;
	$dataset = yjwt_mysql_select($sql_select);
#	echo "<a href='".$_SERVER["SCRIPT_NAME"]."'><button class=btn>返回</button></a>";
	while($row = mysql_fetch_array($dataset)){
		echo "<p>$row[name] </p>";
		$last_map = substr($_GET["orgmap"], 0, strrpos($_GET["orgmap"], "."));
		echo "<a href='".$_SERVER["SCRIPT_NAME"].$script_opt.$row["father_node"]."&orgmap=".$last_map."'>
			<button class=btn>返回上一级</button>
			</a><hr/>";
		return;
	}
}

function new_user_form(){
	$lefte_str = date('Ymd');
	echo "<form class=well id=\"form_new_user\" method=\"get\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
	echo "帐户带宽:";
	echo "<select name=\"speed_id\">";
	dro_list("select * from speed", "-1", "name", "Id");
	echo "</select>";
	echo "<br/>帐号前缀:<input name=\"lefte\" type=\"text\" value=\"$lefte_str\" />";
	echo "<input name=\"opt\" type=\"hidden\" value=\"1\" />";
	echo "<input name=\"node\" type=\"hidden\" value=\"".$_GET["node"]."\" />";
	echo "<input name=\"orgmap\" type=\"hidden\" value=\"".$_GET["orgmap"]."\" />";
	/*echo "<input type=\"hidden\" name=\"id\" value=\"".$_GET["id"]."\" />";*/
	echo "<br/><button id=\"input_sub\" type=\"submit\" >提交</button>";
	echo "</form>";
}

function get_org_map(){
	$note_flag = "node";
	$script_opt = "?t=" . $_GET['t'] . "&id=1&node=";
	if(!$_GET[$note_flag]){
		org_note_head("0", $script_opt);
		if(org_note_print("0", $script_opt) != "-1") new_user_form();
	}else{
		org_note_head($_GET[$note_flag], $script_opt);
		if(org_note_print($_GET[$note_flag], $script_opt) != "-1") new_user_form();
	}
}
function new_user_do(){
    //$nextWeek = time() + (7 * 24 * 60 * 60); // 7 days; 24 hours; 60 mins; 60secs
	//echo 'Now:       '. date('Y-m-d') ."\n";
	if($_GET["lefte"]) $new_username = $_GET["lefte"]."-";
	else $new_username = date('Ymd')."-";
	$new_password = rand(10000000,99999999);
	//$_GET["node"];
	//$_GET["speed_id"]
	$up_speed = val_text("select * from speed where Id=".$_GET["speed_id"],"up_speed");
	$down_speed = val_text("select * from speed where Id=".$_GET["speed_id"],"down_speed");
	$lan_speed = val_text("select * from speed where Id=".$_GET["speed_id"],"lan_speed_rx");
	$speed_id = $_GET["speed_id"];
	$disable_time= date('y-m-d h:m:s', time() + (1 * 24 * 60 * 60));
	$create_time = date('Y-m-d h:m:s');
	$worker_name = "admin";

	$sql_pppoe = "INSERT INTO `user_pppoe`(`username`, `password`, `up_speed`, `down_speed`, `lan_speed`,`speed_id`, `disable_time`, `creat_time`) ";
	$sql_pppoe = $sql_pppoe . "VALUES('".$new_username."', '".$new_password."', ".$up_speed.",".$down_speed.",".$lan_speed.",".$speed_id.",'".$disable_time."','".$create_time."')";
    yjwt_mysql_do($sql_pppoe);
    $new_user_id = val_text("select Id from user_pppoe where username='".$new_username."' and password='".$new_password."' and creat_time='".$create_time."'","Id");
    $new_username = $new_username.$new_user_id;
    $sql_pppoe = "UPDATE `user_pppoe` SET username='".$new_username."' WHERE Id =".$new_user_id;
    yjwt_mysql_do($sql_pppoe);
	$sql_info = "INSERT INTO `user_info`(`uid`,`orgid`,`orgmap`) ";
	$sql_info = $sql_info."VALUES(".$new_user_id.",".$_GET["node"].",'".$_GET["orgmap"]."')";
    yjwt_mysql_do($sql_info);
    //echo $sql_info;

	echo '<div class="alert alert-success">';
	echo '账号分配成功！';
	echo '</div>';

	echo '<div class=well>';
	echo "<p>账号：".$new_username."<p/>";
	echo "<p>密码：".$new_password." bps<p/>";
	echo "<p>上行带宽：".$up_speed." bps<p/>";
	echo "<p>下行带宽：".$down_speed." bps<p/>";
	echo "<p>网内带宽：".$lan_speed."【只当有网内带宽时有效】<p/>";
	echo "<p>创建时间：".$create_time."<p/>";
	echo "<p>初始到期时间：".$disable_time."<p/>";
	echo "<p>带宽识别码：".$_GET["speed_id"]."<p/>";
	echo "<p>组织识别码：".$_GET["node"]."<p/>";
	echo "<p>组织拓扑：".$_GET["orgmap"]."<p/>";
	echo "<p>操作员：".$worker_name."<p/>";
	echo '</div>';
}
function uid_find_form(){
    echo "<form class=well id=\"form_new_user\" method=\"get\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
    echo "<label>帐号：（请输入完整的帐号）</label>";
		echo "<input class=input name=\"s\" type=\"text\"/><br/>";
    echo "<label>操作：</label>";
    echo "<button id=\"input_sub\" type=\"submit\">提交</button>";
    echo "<input name=\"opt\" type=\"hidden\" value=\"2\" />";
    echo "</form>";
}

function uid_entry_form(){

    $sql_select = "select user_pppoe.*, user_info.* from user_pppoe, user_info where user_pppoe.Id = user_info.uid ";
    $sql_select = $sql_select." and user_pppoe.username = '".$_GET["s"]."'";
    $dataset = yjwt_mysql_select($sql_select);
	$opt_type = 1;
	if($row = mysql_fetch_array($dataset)){
		echo "<div class=well>";
		if($row["name"] && $row["name"] != "") $opt_type = 2;
		if($row["name"] && $row["name"] != "") echo "<p>姓名:".$row["name"]."</p>";
		if($row["phone"] && $row["phone"] != "") echo "<p> 电话:".$row["phone"]."</p>";
		if($row["addr"] && $row["addr"] != "") echo "<p>住址:".$row["addr"]."</p>";
		if($row["idcar"] && $row["idcar"] != "") echo "<p>身份证:".$row["idcar"]."</p>";
		echo "</div>";

		echo "<div class=well>";
        echo "<table class=table>";
        echo "<tr> <td>帐户识别码</td> <td>帐户</td><td>密码</td><td>在线状态</td><td>上下行带宽</td></tr>";
        
        echo "<tr>";
	    echo "<td>".$row["uid"]."</td>";
	    echo "<td>".$row["username"]."</td>";
	    echo "<td>".$row["password"]."</td>";

	    if($row["online"] == 1) echo "<td>"."在线"."</td>";
	    else  echo "<td>"."不在线"."</td>";
	    echo "<td>".$row["up_speed"]."kbps/".$row["down_speed"]."kbps"."</td>";
	    echo "</tr>";
	    echo "</table>";
	    echo "可用时间:".$row["creat_time"]." - ".$row["disable_time"]."<br/>";
	    if($row["lan_speed"] && $row["lan_speed"] != "") echo "内网带宽:".$row["lan_speed"]." kbps";
	    else echo "未配置内网带宽";
	    if($row["ip_address"] && $row["ip_address"] != "") echo "固定IP:".$row["ip_address"]." kbps";
	    else echo " 未配置固定IP";

			echo "</div>";
	    //////////////////////////////////////
	    echo "<form id=\"form_new_user\" class='well form-horizontal' method=\"get\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
			echo "<fieldset>";

				form_field("姓名", "<input class=input name=\"name\" type=\"text\" value=\"".$row["name"]."\" >");
				form_field("电话", "<input name=\"phone\" type=\"text\" value=\"".$row["phone"]."\" >");
				form_field("身份证号", "<input name=\"idcar\" type=\"text\" value=\"".$row["idcar"]."\" >");
        form_field("住址", "<input name=\"addr\" type=\"text\" value=\"".$row["addr"]."\" >");

		$list_num = 0;
		form_field_header("套餐");
    echo "<select id=tc_select name=\"tc\">";
		$list_num = get_tc_list($row["orgmap"], $row["speed_id"]);
	  echo "</select>";
		form_field_tail();

		echo "<div id='opt_menu' style='display:none'>";
			form_field("固定IP", "<input name=\"ip\" type=\"text\" value=\"".$row["ip_address"]."\" >");
			form_field("密码", "<input name=\"password\" type=\"text\" value=\"".$row["password"]."\" >");
			form_field_header("带宽");
				echo "<select name=\"speed_id\">";
				dro_list("select * from speed", $row["speed_id"], "name", "Id");
				echo "</select>";
			form_field_tail();
      form_field("时长", "<input name=\"months\" class=input-mini type=\"text\" value=\"0\"/>".
														 "月<input name=\"days\" class=input-mini type=\"text\" value=\"0\" >");
      form_field("金额", "<input name=\" money\" type=\"text\" value=\"0\"/>");;
    echo "</div>";

        form_field("备注", "<input name=\"note\" type=\"text\" value=\"业务\" >");
        form_field("", "<button id=\"input_sub\" type=\"submit\" >提交</button>");

        echo "<input name=\"opt\" type=\"hidden\" value=\"3\" />";
		echo "<input name=\"opt_type\" type=\"hidden\" value=\"".$opt_type."\" />";
        echo "<input name=\"uid\" type=\"hidden\" value=\"".$row["uid"]."\" />";
        echo "<input name=\"orgmap\" type=\"hidden\" value=\"".$row["orgmap"]."\" />";
        echo "<input name=\"orgid\" type=\"hidden\" value=\"".$row["orgid"]."\" />";

				echo "</fieldset>";
        echo "</form>";


	}else{
          echo "<script type=\"text/javascript\"> alert('输入的帐户不存在'); ";
          echo "location.href = '?t=putbis';";
          echo "</script>";
	}
}
function uid_entry(){
    $uid = $_GET["uid"];
    $sql_select="select * from speed where Id =".$_GET["speed_id"];
    $dataset = yjwt_mysql_select($sql_select);
    //admin
	$admin = $_REQUEST["php_user"];
	if($admin == NULL || $admin == "") $admin = "1";
	//opt_type
    $opt_type ="1";//1==开户，2=续费
	$opt_type = $_GET["opt_type"];
	
	if($_GET["money"] == "" || $_GET["money"] == "0") $opt_type = "3";
    $old_t = val_text("select * from user_pppoe where Id=".$uid,"disable_time");
    
    $set_opt = "password='".$_GET["password"]."'";
    if($row = mysql_fetch_array($dataset)){
       $set_opt = $set_opt.", `up_speed`=".$row["up_speed"];
       $set_opt = $set_opt.", `down_speed`=".$row["down_speed"];
       $set_opt = $set_opt.", `lan_speed`=".$row["lan_speed_rx"];
       $set_opt = $set_opt.", `speed_id`=".$_GET["speed_id"];
       
    }
       $sql_update = "update user_pppoe set `disable_time`=now() where `disable_time`<now() and where Id=".$uid;
       yjwt_mysql_do($sql_update);
       $disable_time = "DATE_ADD(`disable_time`, Interval ".$_GET["months"]." month)";
       $sql_update="update user_pppoe set ".$set_opt.", `disable_time`=".$disable_time." where Id=".$uid;
       yjwt_mysql_do($sql_update);

    if($_GET["days"] && $_GET["days"] !="" && $_GET["days"] !="0"){
       $disable_time = "DATE_ADD(`disable_time`, Interval ".$_GET["days"]." day)";
       $sql_update="update user_pppoe set ".$set_opt.", `disable_time`=".$disable_time." where Id=".$uid;
       yjwt_mysql_do($sql_update);
     }
     echo "<br/>";
    $set_opt = "name='".$_GET["name"]."'";
    $set_opt = $set_opt.", phone='".$_GET["phone"]."'";
    $set_opt = $set_opt.", addr='".$_GET["addr"]."'";
    $set_opt = $set_opt.", idcar='".$_GET["idcar"]."'";
    $sql_update = "update user_info set ".$set_opt." where uid=".$uid;
    yjwt_mysql_do($sql_update);
    $sql_update = "INSERT INTO `bill`(`opt_time`, `old_disable_time`,`new_disable_time`";
    $sql_update = $sql_update.",`months`";
    $sql_update = $sql_update.",`days`";
    $sql_update = $sql_update.",`admin`";
    $sql_update = $sql_update.",`opt_type`";
    $sql_update = $sql_update.",`orgid`";
    $sql_update = $sql_update.",`orgmap`";
    $sql_update = $sql_update.",`speed_id`";
    $sql_update = $sql_update.",`note`";
    $sql_update = $sql_update.",`money`";
    $sql_update = $sql_update.",`uid`";
    $sql_update = $sql_update.") VALUES('".date("Y-m-d H:i:s",time())."'";
    $sql_update = $sql_update.",'".$old_t."'";
    $new_t = val_text("select * from user_pppoe where Id=".$uid,"disable_time");
    $sql_update = $sql_update.",'".$new_t."'";
    $sql_update = $sql_update.",'".$_GET["months"]."'";
    $sql_update = $sql_update.",'".$_GET["days"]."'";
    $sql_update = $sql_update.",'".$admin."'";
    $sql_update = $sql_update.",'".$opt_type."'";
    $sql_update = $sql_update.",'".$_GET["orgid"]."'";
    $sql_update = $sql_update.",'".$_GET["orgmap"]."'";
    $sql_update = $sql_update.",'".$_GET["speed_id"]."'";
    $sql_update = $sql_update.",'".$_GET["note"]."'";
    $sql_update = $sql_update.",'".$_GET["money"]."'";
    $sql_update = $sql_update.",'".$uid."')";
    yjwt_mysql_do($sql_update);
	echo "<script type=\"text/javascript\"> alert('业务办理成功'); </script>";
	echo "<script type=\"text/javascript\"> window.location.href = '?t=putbis' </script>";
    
    //echo  $sql_update;
}


/*
<select name="">
  <option value="1" selected="selected">gg</option>
  <option value="55">55</option>
</select>
*/
?>

