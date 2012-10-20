<?php 

header('Content-Type: text/html; charset=utf-8');
require_once("sql.php");

function login_out() {
	setcookie("php_user", "", time()-1800);
	setcookie("Id_user", "", time()-1800);
}

function user_login(){
	$user_name = $_POST["name"];
	$user_password = $_POST["password"];
	//防sql注入
	$user_name = str_replace("'","''", $user_name);
	$user_name = str_replace("\"","\"\"", $user_name);
	$user_password = str_replace("'","''", $user_password);
	$user_password = str_replace("\"","\"\"", $user_password);
	
	if($user_name == "" || $user_password == "") {
		//echo "<script type=\"text/javascript\"> alert('用户名密码不能为空'); </script>";
		echo "<script> window.location.href = 'index.php?t=login' </script>";
	}
	$sql_select = "select * from staff where name='".$user_name."' and password='".$user_password."'";
	$dataset = yjwt_mysql_select($sql_select);
	//echo "$sql_select";
	if($dataset && $row = mysql_fetch_array($dataset)){
		setcookie("php_user", $row["flag"], time()+1800);//半小时,这个语句必需要html这前
		setcookie("Id_user", $row["Id"], time()+1800);
		echo "<script type=\"text/javascript\">";
		echo "alert('登陆成功.祝你工作愉快');";
		echo "window.location.href ='index.php?';";
		echo "</script>";
	}else{
		//echo "<script type=\"text/javascript\"> alert('登陆失败'); </script>";
		echo "<script> window.location.href = 'index.php?t=login' </script>";
	}
}

user_login();

?>

