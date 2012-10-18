<?php
function dro_list($sql_select, $default_vaule, $text_name, $vaule_name){
	$dataset = yjwt_mysql_select($sql_select);
	
	if($dataset) echo "<option value=\"-1\">--请选择--</option>";
	while($row = mysql_fetch_array($dataset)){
		if($row[$vaule_name] == $default_vaule)
			echo "<option value=\"".$default_vaule."\" selected=\"selected\">".$row[$text_name]."</option>";
		else echo "<option value=\"".$row[$vaule_name]."\">".$row[$text_name]."</option>";
	}
}
/*
<select name="">
  <option value="1" selected="selected">gg</option>
  <option value="55">55</option>
</select>
*/
?>

