<?

if ($_GET[t] != 'backupusr') 
	return ;

$backup_root = "/tmp/backup/usr";
chdir($backup_root);
$pwd = getcwd();

if ($_GET['do'] == 'b') {
	$date = date('YmdGis');
	$root = "$date";
	umask(0);
	mkdir($root);
	$fp = fopen("$root/time", "w+");
	fwrite($fp, date('Y-m-d G:i:s'));
	fclose($fp);
	$r = yjwt_mysql_do("select * from user_pppoe into outfile '$pwd/$root/file.txt' fields enclosed by '\"' terminated by ',' ;");
	if ($r == 1) {
		$tips = "<div class='alert alert-success'>备份成功！</div>";
	} else {
		$tips = "<div class='alert alert-error'>备份失败！</div>";
	}
}

if ($_GET['do'] == 'r') {
#	$r = yjwt_mysql_do("select * from user_pppoe into outfile '$pwd/$root/file.txt' fields enclosed by '\"' terminated by ',' ;");
	$tips = "<div class='alert alert-success'>恢复成功！</div>";
}

if ($_GET['do'] == 'd') {
	if (is_dir($_GET[path])) {
		system("rm -rf $_GET[path]");
		$tips = "<div class='alert alert-success'>删除成功！</div>";
	}
}

echo "<form class=form>";
echo "<input class='input' placeholder='请填写备注'></input> ";
echo "<a href='?do=b' class='btn btn-primary'>备份</a>";
echo "</form>";
echo "$tips";
echo "<table class='table table-condesend'>";
echo "<tr>";
	echo "<td><b>备份时间</b></td>";
	echo "<td><b>备注</b></td>";
	echo "<td><b>操作</b></td>";
echo "</tr>";

foreach (scandir(".") as $dir) {
	if ($dir == "." || $dir == "..") 
		continue;
	$time = file_get_contents("$dir/time");
	$note = file_get_contents("$dir/note");
	if (!$note)
		$note = '无';
	echo "<tr>";
	echo "<td>$time</td>";
	echo "<td>$note</td>";
	echo "<td>";
	echo "<a class='btn btn-mini btn-success' open='/tmp/$dir/file.txt' >下载</a> ";
	echo "<a class='btn btn-mini btn-primary' href='?do=r&path=$dir'>恢复</a> ";
	echo "<a class='btn-del btn btn-mini btn-danger' href='?do=d&path=$dir'>删除</a> ";
	echo "</td>";
	echo "</tr>";
}

echo "</table>";

?>
