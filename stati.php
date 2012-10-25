<meta charset="utf-8">
<?php
require_once("sql.php");
require_once("fun.php");

bill_statistics_top($style_id1,$style_id3);
bill_statistics("大区", 5, $style_id1);
bill_statistics("大区", 5, $style_id3);
bill_statistics_bottom();

?>

