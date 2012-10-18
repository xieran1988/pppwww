<?php
   require_once("sql.php");
   require_once("fun.php");
   function form_list(){
      echo "<form class=\"well\" method=\"get\" action=\"".$_SERVER["SCRIPT_NAME"]."\">";
      echo "<input name=\"t\" type=\"hidden\" value=\"findusr\"/>";
			echo "<label>时间</label>";
      echo "<input name=\"s\" type=\"text\" value=\"".date("Y-m-d")."\"/> -> ";
      echo "<input name=\"e\" type=\"text\" value=\"".date("Y-m-d")."\"/>";
      echo "<br><button id=\"input_sub\" type=\"submit\">查询</button>";
      echo "</form>";
   }
   function search_list(){
	echo "<a href='".$_SERVER["SCRIPT_NAME"]."'>返回</a>";
      $sql_select = "select user_info.name,user_info.addr,org.name as oname,speed.name as sname, bill.* ";
      $sql_select=$sql_select."from bill, user_info, org,speed ";
      $sql_select=$sql_select."where bill.uid=user_info.uid and bill.orgid = org.Id and bill.speed_id = speed.Id";
      $sql_select = $sql_select." and opt_time>='".$_GET["s"]."' and opt_time<='".$_GET["e"]."'";
	  
       $dataset = yjwt_mysql_select($sql_select);
       echo "<table class=\"table table-condensed\">";
			echo "<tr>";
	   echo "<td><b>代码</td>";
	   echo "<td><b>交易时间</b></td>";
       
       echo "<td><b>用户ID</b></td>";
       echo "<td><b>类型</b></td>";
       echo "<td><b>金额</b></td>";
       
       echo "<td><b>带宽</b></td>";
       echo "<td><b>姓名</b></td>";
       echo "<td><b>地址</b></td>";
       echo "<td><b>项目</b></td>";
	   echo "<td><b>时长</b></td>";
	   echo "<td><b>交易前到期时间</b></td>";
       echo "<td><b>交易后到期时间</b></td>";
       echo "<td><b>备注</b></td>";
       echo "</tr>";
	  while($row = mysql_fetch_array($dataset)){
       echo "<tr>";
	   echo "<td>".$row["Id"]."</td>";
	   $optd = date("Y-m-d h", strtotime($row["opt_time"]));
	   echo "<td>".$optd."</td>";
       
       echo "<td>".$row["uid"]."</td>";
	   echo "<td>".opt_type($row["opt_type"])."</td>";       
       echo "<td>".$row["money"]."</td>";

       echo "<td>".$row["sname"]."</td>";
       echo "<td>".$row["name"]."</td>";
       echo "<td>".$row["addr"]."</td>";
       echo "<td>".$row["oname"]."</td>";
	   $time_len = "";
	   if($row["months"] && $row["months"] != "") $time_len = $time_len.$row["months"]."月";
	   if($row["days"] && $row["days"] != "") $time_len = $time_len.$row["days"]."天";
	   echo "<td>".$time_len."</td>";
	   echo "<td>".date("Y-m-d h", strtotime($row["old_disable_time"]))."</td>";
       echo "<td>".date("Y-m-d h", strtotime($row["new_disable_time"]))."</td>";
       echo "<td>".$row["note"]."</td>";
       echo "</tr>";
	  }
      echo "</table>";
   }
   
		if ($_GET['t'] == 'qrybis') {
					 if(!$_GET["s"] || !$_GET["e"]){
							form_list();
					 }
					 if($_GET["s"] &&  $_GET["e"]){
							search_list();
					 }
	}
?>

