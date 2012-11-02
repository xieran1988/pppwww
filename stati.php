<meta charset="utf-8">
<?php
require_once("sql.php");
require_once("fun.php");

bill_statistics_top($style_id1,$style_id2);


$sql_select = "select * from org where father_node = 0";
$dataset = yjwt_mysql_select($sql_select);

while($dataset && $row = mysql_fetch_array($dataset)){	
	$orgid = $row["Id"];
	bill_statistics("一级", $orgid, $style_id1);
	
	$sql_select = "select * from org where father_node = $orgid";
	$dataset2 = yjwt_mysql_select($sql_select);
	while($dataset2 && $row2 = mysql_fetch_array($dataset2)){
		$orgid2 = $row2["Id"];	
		bill_statistics("二级", $orgid2, $style_id3);
	}
}

bill_statistics("总计", -1, $style_id1);
bill_statistics_bottom();

?>

