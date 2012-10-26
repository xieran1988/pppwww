<?
	header("Content-type: application/txt");
	header("Content-Disposition: attachment; filename=$_GET[name].txt");
	readfile("$_GET[path]");
?>
