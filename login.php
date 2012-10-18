<?php header("content-type:text/html; charset=utf-8"); 

require_once("sql.php");
function login_out(){
	setcookie("php_user", "", time()-1800);
	setcookie("Id_user", "", time()-1800);
}
function login_form(){
	//echo "<div class=\"container\">";
	  echo "<form class=\"well\" method=\"post\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
	  echo "<table ALIGN=\"CENTER\">";
	  echo "<tr>";
      echo "<td>职工:</td><td><input placeholder=\"请输入用户名...\" name=\"name\" type=\"text\" value=\"".$_REQUEST["name"]."\" /></td>";
	  echo "</tr><tr>";
      echo "<td>口令:</td><td><input placeholder=\"请输入密码...\" name=\"password\" type=\"password\" value=\"".$_REQUEST["password"]."\" /></td>";
	  echo "</tr><tr>";
      echo "<td>操作:</td><td><input class=\"btn-min\" id=\"input_sub\" type=\"submit\" value=\"提交\" /></td>";
	  "</tr>";
	  echo "</table>";
      echo "</form>";
	 // echo "</div>";
}
function user_login(){
	$user_name = $_REQUEST["name"];
	$user_password = $_REQUEST["password"];
	//防sql注入
	$user_name = str_replace("'","''", $user_name);
	$user_name = str_replace("\"","\"\"", $user_name);
	$user_password = str_replace("'","''", $user_password);
	$user_password = str_replace("\"","\"\"", $user_password);
	
	if($user_name == "" || $user_password == ""){
		echo "<script type=\"text/javascript\"> alert('用户名密码不能为空'); </script>";
		login_form();
		return;
	}
	$sql_select = "select * from staff where name='".$user_name."' and password='".$user_password."'";
	$dataset = yjwt_mysql_select($sql_select);
	//echo "$sql_select";
	if($dataset && $row = mysql_fetch_array($dataset)){
		setcookie("php_user", $row["flag"], time()+1800);//半小时,这个语句必需要html这前
		setcookie("Id_user", $row["Id"], time()+1800);
		echo "<script type=\"text/javascript\">";
		echo "alert('登陆成功.祝你工作愉快');";
		echo "location.href ='admin.php';";
		echo "</script>";
	}else{
		echo "<script type=\"text/javascript\"> alert('登陆失败'); </script>";
		login_form();
	}
}
//退出
login_out();
//登陆
if($_REQUEST["name"] && $_REQUEST["password"]){
	user_login();
}


?>

<HTML lang="en">
<HEAD>
 <TITLE>Login</TITLE>
 <link href="css/bootstrap.css" rel="stylesheet">
 <script src="js/bootstrap.js"></script>
 <script src="jquery.js"></script>
 <script src="postfix.js"></script>
 
</HEAD>
<BODY>

<?php

//表单
if(!$_REQUEST["name"]){
	login_form();
}
?>
</BODY>
</HTML>
