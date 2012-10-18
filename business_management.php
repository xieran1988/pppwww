<?php
require_once("sql.php"); 
#phpinfo();
require_once("obj.php");
function guide_form(){
				echo "<a href='business_management.php?id=1'>获取帐号</a>.";
				echo "<a href='business_management.php?id=2'>业务录入</a>.";
				echo "<a href='business_management.php?id=3'>查找用户信息</a>";
				echo "<hr/>";
}

if ($_GET['t'] == 'getid') {
	$_GET['id'] = 1;
} else if ($_GET['t'] == 'putbis') {
	$_GET['id'] = 2;
} else if ($_GET['t'] == 'findusr') {
	$_GET['id'] = 3;
} else 
	return ;

if(!$_GET["id"] && !$_GET["opt"]){
				guide_form();
				return;
}
//////////////////////////////////////////////////////////////////////////
//申请帐户的导航图
if($_GET["id"] && $_GET["id"] == "1" && !$_GET["opt"]){
				get_org_map();
}
//业务办理前的帐户确定
if($_GET["id"] && $_GET["id"] == "2" && !$_GET["opt"]){
				uid_find_form();
}
//业务办理 帐户确定后 表单生成
if($_GET["opt"] && $_GET["opt"] == "2" && $_GET["s"]){
				uid_entry_form();
}
//业务办理
if($_GET["opt"] && $_GET["opt"] == "3" && $_GET["uid"]){
				uid_entry();
				#guide_form();
}
//申请帐户
if($_GET["opt"] && $_GET["opt"] == "1" && $_GET["speed_id"]){
				new_user_do();
}

?>

