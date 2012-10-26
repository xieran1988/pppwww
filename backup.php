<?

if ($_GET[t] != 'backupusr') 
	return ;

$backup_root = "backup/usr";
chdir($backup_root);
$pwd = getcwd();

#$sql_fields = "fields enclosed by '\"' terminated by ',' ";

if ($_GET['do'] == 'b') {
	$date = date('YmdGis');
	$root = "$date";
	umask(0);
	mkdir($root);
	$fp = fopen("$root/time", "w+");
	fwrite($fp, date('Y-m-d G:i:s'));
	fclose($fp);
	$note = $_POST[note];
	$fp = fopen("$root/note", "w+");
	fwrite($fp, $note);
	fclose($fp);
	$r = yjwt_mysql_do("select * from user_pppoe into outfile '$pwd/$root/file.txt' $sql_fields ;");
	if ($r == 1) {
		$tips = "<div fade=1 class='alert alert-success'>备份成功！</div>";
	} else {
		$tips = "<div fade=1 class='alert alert-error'>备份失败！</div>";
	}
}

if ($_GET['do'] == 'r') {
	$r = yjwt_mysql_do("delete from user_pppoe;");
	$r = yjwt_mysql_do("LOAD DATA LOCAL INFILE '$pwd/$_GET[path]/file.txt' into table user_pppoe $sql_fields ;");
	if ($r == 1) {
		$tips = "<div fade=1 class='alert alert-success'>恢复成功！</div>";
	} else {
		$tips = "<div fade=1 class='alert alert-error'>恢复失败！</div>";
	}
}

if ($_GET['do'] == 'd') {
	if (is_dir($_GET[path])) {
		system("rm -rf $_GET[path]");
		$tips = "<div fade=1 class='alert alert-success'>删除成功！</div>";
	}
}

if ($_POST['do'] == 'i') {
	$path = $_FILES[file][tmp_name];
	$r = yjwt_mysql_do("delete from user_pppoe;");
	$r = yjwt_mysql_do("LOAD DATA LOCAL INFILE '$path' into table user_pppoe $sql_fields ;");
	if ($r == 1) {
		$tips = "<div fade=1 class='alert alert-success'>导入成功！</div>";
	} else {
		$tips = "<div fade=1 class='alert alert-error'>导入失败！</div>";
	}
}

echo "<form class=form method=post >";
echo "<input name=note class='input' placeholder='请填写备注'></input> ";
echo "<a href='?do=b' class='btn btn-primary'>备份</a> ";
echo "<a upload-post='i' class='btn btn-danger'>导入</a>";
echo "</form>";
echo "$tips";

echo "<table class='table table-condesend'>";

echo "<tr>";
	echo "<td><b>备份时间</b></td>";
	echo "<td><b>备注</b></td>";
	echo "<td><b>操作</b></td>";
echo "</tr>";

$n = 0;
foreach (scandir(".", 1) as $dir) {
	if ($dir == "." || $dir == "..") 
		continue;
	$n++;
	$time = file_get_contents("$dir/time");
	$note = file_get_contents("$dir/note");
	if (!$note)
		$note = '无';
	echo "<tr>";
	echo "<td>$time</td>";
	echo "<td>$note</td>";
	echo "<td>";
	echo "<a class='btn btn-mini btn-success' clickopen='raw.php?path=$backup_root/$dir/file.txt&name=$dir' >下载</a> ";
	echo "<a class='btn btn-mini btn-primary' confirm='警告：将覆盖已有数据，确认恢复？'  href='?do=r&path=$dir'>恢复</a> ";
	echo "<a class='btn-del btn btn-mini btn-danger' confirm='确定删除？' href='?do=d&path=$dir'>删除</a> ";
	echo "</td>";
	echo "</tr>";
}

if ($n == 0) {
#	echo "<i>暂时没有备份数据</i>";
}

echo "</table>";

?>
