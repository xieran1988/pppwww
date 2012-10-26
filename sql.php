<?php require_once("conn.php"); ?>
<?php
$main_con;
$sec_con;
function __yjwt_mysql_conn(){
	global $main_con;
	global $main_db_flag;
	global $main_db_ip;
	global $main_db_user;
	global $main_db_password;
	global $main_db_use;
	
	global $sec_con;
	global $sec_db_flag;
	global $sec_db_ip;
	global $sec_db_user;
	global $sec_db_password;
	global $sec_db_use;
	
	if($main_db_flag)
		$main_con = mysql_connect($main_db_ip, $main_db_user, $main_db_password);
	if($sec_db_flag)
		$sec_con = mysql_connect($sec_db_ip, $sec_db_user, $sec_db_password);
		
	if(!$main_con && !$sec_con)
		die('sql Could not connect: ' . mysql_error());
		
		
}

function __yjwt_mysql_close(){
	if($GLOBALS[main_db_flag] && $GLOBALS[main_con])
		mysql_close($GLOBALS[main_con]);
	if($GLOBALS[sec_db_flag] && $GLOBALS[sec_con])
		mysql_close($GLOBALS[sec_con]);
}

function yjwt_mysql_do($sql_comm){
	__yjwt_mysql_conn();
	
	if ($GLOBALS[main_db_flag] && $GLOBALS[main_con]){
		mysql_select_db($GLOBALS[main_db_use], $GLOBALS[main_con]);
		mysql_query("SET NAMES utf8");
		$result = mysql_query($sql_comm);
		if(!$result) {
			echo "main sql error:".mysql_error()."<br/>";
			echo "query:$sql_comm<br/>";
		}
	}
	
	if ($GLOBALS[sec_db_flag] && $GLOBALS[sec_con]){
		mysql_select_db($GLOBALS[sec_db_use], $GLOBALS[sec_con]);
		mysql_query("SET NAMES utf8");
		$result = mysql_query($sql_comm);
		if(!$result) {
			#echo "sec sql error:".mysql_error()."<br/>";
		}
	}
	
	__yjwt_mysql_close();
	
	
	if ($GLOBALS[main_db_flag] && !$GLOBALS[main_con]) exec("echo $sql_comm \n >> err_mail.sql");
	if ($GLOBALS[sec_db_flag] && !$GLOBALS[sec_con]) exec("echo $sql_comm \n >> err_sec.sql");
	
	return $result;
}

function yjwt_mysql_select($sql_comm){
	__yjwt_mysql_conn();
	if ($GLOBALS[main_db_flag] && $GLOBALS[main_con]){
		mysql_select_db($GLOBALS[main_db_use], $GLOBALS[main_con]);
		mysql_query("SET NAMES utf8");
	}
	else if ($GLOBALS[sec_db_flag] && $GLOBALS[sec_con]){
		mysql_select_db($GLOBALS[sec_db_use], $GLOBALS[sec_con]);
		mysql_query("SET NAMES utf8");
	}
	else return 0;
	
	$result = mysql_query($sql_comm);
	__yjwt_mysql_close();
	return $result;
}

///////////////////////////////////////
/*
__yjwt_mysql_conn();
//$main_con = mysql_connect($main_db_ip, $main_db_user, $main_db_password);
if (!$main_con && $main_db_flag)
	die('main sql Could not connect: ' . mysql_error());
if (!$main_con && $sec_db_flag)
	die('sec sql Could not connect: ' . mysql_error());
echo "xxx function database succ!!";
__yjwt_mysql_close();
*/
///////////////////////////////////////////////

?>
