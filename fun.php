<?php

function longer_cookie($name) {
	if ($_COOKIE[$name])
		setcookie($name, $_COOKIE[$name], time() + 1800);
}

longer_cookie('php_user');
longer_cookie('Id_user');

if ($_GET['t'] == 'exit') {
	echo "<script>";
	echo "alert('请重新登陆！');";
	echo "window.location.href = '?t=login';";
	echo "</script>";
}

if ($_GET['t'] == '') {
	echo "<script>";
	echo "window.location.href = '?t=getid';";
	echo "</script>";
}

login_check();

function dro_list($sql_select, $default_vaule, $text_name, $vaule_name){
	$dataset = yjwt_mysql_select($sql_select);
	
	if($dataset) echo "<option value=\"-1\">--请选择--</option>";
	while($row = mysql_fetch_array($dataset)){
		if($row[$vaule_name] == $default_vaule)
			echo "<option value=\"".$default_vaule."\" selected=\"selected\">".$row[$text_name]."</option>";
		else echo "<option value=\"".$row[$vaule_name]."\">".$row[$text_name]."</option>";
	}
}

function val_text($sql_select, $text_name){
	$dataset = yjwt_mysql_select($sql_select);
	if(!$dataset) return "ERROR";
	$row = mysql_fetch_array($dataset);
	if(!$row) return "NULL";
	return $row[$text_name];
}
function have_row($sql_select){
	$dataset = yjwt_mysql_select($sql_select);
	if(!$dataset) return 0;
	$row = mysql_fetch_array($dataset);
	if(!$row) return 0;
	return 1;
}
function dro_list_staff($v){
	if($v=="1") echo "<option value=\"1\" selected=\"selected\">职员</option>";
	else echo "<option value=\"1\" >职员</option>";
	
	if($v=="2") echo "<option value=\"2\" selected=\"selected\">经理</option>";
	else echo "<option value=\"2\" >经理</option>";
	
	if($v=="3") echo "<option value=\"3\" selected=\"selected\">管理员</option>";
	else echo "<option value=\"3\" >管理员</option>";
}

function dro_list_opt_type($v){
	if($v=="1") echo "<option value=\"-1\" selected=\"selected\">所有</option>";
	else echo "<option value=\"-1\" >所有</option>";
	
	if($v=="1") echo "<option value=\"1\" selected=\"selected\">开户</option>";
	else echo "<option value=\"1\" >开户</option>";
	
	if($v=="2") echo "<option value=\"2\" selected=\"selected\">续费</option>";
	else echo "<option value=\"2\" >续费</option>";
	
	if($v=="3") echo "<option value=\"3\" selected=\"selected\">变更</option>";
	else echo "<option value=\"3\" >变更</option>";
}

function staff_name($v){
	if($v=="1") return "职员";
	if($v=="2") return "经理";
	if($v=="3") return "管理员";
}

function login_check(){
	if($_COOKIE["Id_user"]==NULL && $_GET['t'] != 'login'){
	    echo "<script type=\"text/javascript\">";
		echo "alert('你还没有登陆');";
		echo "location.href ='?t=login';";
		echo "</script>";
		return 0;
	}
	//setcookie("php_user", $_COOKIE["php_user"], time()+1800);//半小时,这个语句必需要html这前
	//setcookie("Id_user", $_COOKIE["Id_user"], time()+1800);
	return 1;
}

function rule_ok($ru) {
	if($_COOKIE["php_user"]==$ru || $_COOKIE["php_user"]==3){
		return 1;
	}
	return 0;
}

function rule_check() {
	$arr = array('manorg', 'manstaff', 'manprice', 'manband');
	if (in_array($_GET[t], $arr) && !rule_ok(2)) {
		echo "<div class=\"alert alert-error\">";
		echo "警告：非工作人员请勿进入!";
		echo "</div>";
		return 0;
	}
	return 1;
}
function opt_type($opt_v){
	if($opt_v == 1) return "开户";
	if($opt_v == 2) return "续费";
	if($opt_v == 3) return "变更";
	return "非法";
}

function user_online($opt_v){
	if($opt_v == 0) return "不在线";
	if($opt_v == 1) return "在线";
	if($opt_v == 2) return "不在线";
	return "非法";
}

function form_field_header($desc) {
   echo '<div class="control-group">';
   echo '<label class="control-label" >';
	echo $desc;
	echo '</label>';
  echo '<div class="controls">';
}

function form_field_tail() {
   echo '</div> </div>';
}

function form_field($desc, $input) {
	form_field_header($desc);
  echo $input;
	form_field_tail();
}

function btn_edit_del($edit, $del) {
	echo "<a href='$edit'><button class='btn-mini btn-primary'>编辑</button></a>";
	echo " <a href='$del'><button class='btn-mini btn-danger btn-del'>删除</button></a>";
}

function jmp($url) {
}

function online_select($onl){
	echo " <select name='online'>";
	if($onl == "0") echo "<option value='0' selected='selected'>所有用户</option>";
	else echo "<option value='0'>所有用户</option>";
	
	if($onl == "1") echo "<option value='1' selected='selected'>在线用户</option>";
	else echo "<option value='1'>在线用户</option>";
	
	if($onl == "2") echo "<option value='2' selected='selected'>离线用户</option>";
	else echo "<option value='2'>离线用户</option>";
	
	if($onl == "3") echo "<option value='3' selected='selected'>未触发用户</option>";
	else echo "<option value='3'>未触发用户</option>";
	
	echo "</select>";
}

function disable_select($dis){
	echo " <select name='disable_time'>";
	if($dis == "0") echo "<option value='0' selected='selected'>所有用户</option>";
	else echo "<option value='0' >所有用户</option>";
	
	if($dis == "1") echo "<option value='1' selected='selected'>已到期用户</option>";
	else echo "<option value='1' >已到期用户</option>";
	if($dis == "2") echo "<option value='2' selected='selected'>未到期用户</option>";
	else echo "<option value='2'>未到期用户</option>";
	if($dis == "3") echo "<option value='3' selected='selected'>当月到期用户</option>";
	else echo "<option value='3'>当月到期用户</option>";
	if($dis == "4") echo "<option value='4' selected='selected'>下月到期用户</option>";
	else echo "<option value='4'>下月到期用户</option>";
	if($dis == "5") echo "<option value='5' selected='selected'>上月到期用户</option>";
	else echo "<option value='5'>上月到期用户</option>";
	echo "</select>";
}
function online_disable($r, $online, $dis){
	if($dis){
		if($dis==1) array_push($r, "disable_time < now()");
		else if($dis==2) array_push($r, "disable_time >= now()");
		else if($dis==3) array_push($r, 
			"disable_time > date_add(date_add(last_day(now()), interval -1 month), interval 1 day)", 
			"disable_time < date_add(last_day(now()), interval 1 day)"
		);
		else if($dis==4) array_push($r, 
			"disable_time >= date_add(last_day(now()), interval 1 day)",
			"disable_time < date_add(date_add(last_day(now()), interval 1 month), interval 1 day)"
		);
		else if($dis==5) array_push($r,
			"disable_time >= date_add(date_add(last_day(now()), interval -2 month),interval 1 day)",
			"disable_time < date_add(date_add(last_day(now()), interval -1 month),interval 1 day)"
		);
	}
	if($online){
		if($online == 1) array_push($r, "online=1");
		else if($online == 2) array_push($r, "online !=1");
		else array_push($r, "online is NULL");
	}
	return $r;
}
$style_id1="style='background-color:White;border-color:#E7E7FF;border-width:1px;border-style:None;font-size:12px;border-collapse:collapse;'";
$style_id2="style='color:#F7F7F7; background-color:#4A3C8C;'";
$style_id3="style='color:#4A3C8C; background-color:#F7F7F7;'";
$style_id4="style='color:#4A3C8C; background-color:#E7E7FF;'";


function bill_statistics_top($s1,$s2){
	echo "<table width='100%' cellspacing=0 cellpadding=3 border=1 $s1>";
	echo "<tr $s2>";
	
	echo "<td>项</td>";
	echo "<td>名称</td>";
	echo "<td>标志</td>";
	echo "<td>增长</td>";
	echo "<td>当天</td>";
	echo "<td>昨天</td>";
	echo "<td>当月</td>";
	echo "<td>同时</td>";
	echo "<td>当月开户</td>";
	echo "<td>当月续费</td>";
	echo "<td>上月开户</td>";
	echo "<td>上月续费</td>";
	
	echo "</tr>";
}
	
function bill_statistics($name, $orgid, $style){
	echo "<tr $style>";
	$orgname;
	
	$cur_day;
	$last_day;
	
	$cur_month;
	$_last_month;
	$last_month;
	
	$cur_month_new;
	$cur_month_old;
	
	$last_month_new;
	$last_month_old;
	
	echo "<td>$name</td>";
	echo "<td>$orgname</td>";
	echo "<td>标志</td>";
	echo "<td>增长</td>";
	echo "<td>$cur_day</td>";
	echo "<td>$last_day</td>";
	echo "<td>$cur_month</td>";
	echo "<td>$_last_month/$last_month</td>";
	echo "<td>$cur_month_new</td>";
	echo "<td>$cur_month_old</td>";
	echo "<td>$last_month_new</td>";
	echo "<td>$last_month_old</td>";
	
	echo "</tr>";
	
}
function bill_statistics_bottom(){
	echo "</table>";
}

/*
<select name='online'>
  <option value='1' selected='selected'>gg</option>
  <option value='55'>55</option>
</select>
//身份证
onkeypress='return event.keyCode>=48 && event.keyCode<=57 || event.keyCode==120'
//电话
onkeypress='return event.keyCode>=48 && event.keyCode<=57 || event.keyCode==45'
*/
?>
