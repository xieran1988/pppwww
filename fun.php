<?php

if ($_GET['t'] == 'exit') {
	?><script> 
		alert('请重新登陆！'); 
		window.location.href = '?t=login';
	</script> <?
}

if ($_GET['t'] == '') {
	?><script> 
		window.location.href = '?t=getid';
	</script> <?
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
	return 1;
}

function rule_check($ru){
	if($_COOKIE["php_user"]==$ru || $_COOKIE["php_user"]==3){
		return 1;
	}
	echo "flag=".$_COOKIE["php_user"];
	echo "<script type=\"text/javascript\">";
	echo "alert('非工作内容请勿进入');";
	echo "</script>";
	return 0;
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
	echo "<a href='$del'><button class='btn-mini btn-danger btn-del'>删除</button></a>";
}

function jmp($url) {
	?> <script> window.location.href = "<?= $url ?>" </script> <?
}

?>
