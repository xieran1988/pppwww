
<? 
if ($_GET['t'] != 'netmon')
	return ;
?>

<form class=well method=get action="?">
<table>

<tr>
<td>
关键字:
</td><td>
<input name="key" type=\"text\"/>
</td>
</tr>
<tr>
<td>
时间:
</td>
<td>
<input name=datestart type=input class=datetime value="<?= date('Y-m-d') ?>">	
到
<input name=dateend type=input class=datetime value="<?= date('Y-m-d') ?>">	
</td>
</tr>
<tr>
<td>
类型:
</td>
<td>
<select name="type">
  <option value="1">QQ</option>
  <option value="55">URL、weibo、E-maill</option>
</select>
</td>
</tr>
<tr>
<td></td><td>
<button type=submit>提交</button>
</td></tr>
</table>
</form>

<?
//	error_reporting(E_ALL);
	$pagenr = 10;
	$start = 0;
	if ($_GET['start'])
		$start = $_GET['start'];
	$end = $start + $pagenr;
	if ($_GET['datestart'] && $_GET['dateend']) {
		if ($_GET[type] == '1')
			$fname = 'qq';
		else 
			$fname = 'url';
		?> 
		<table class="table table-condensed netmon-table">
			<tr>
				<td>编号</td>
				<td>IP</td>
				<td>日期</td>
		<? if ($fname == 'url') { ?>
				<td>记录</td>
		<? } else { ?>
				<td>QQ</td>
		<? } ?>
			</tr>
		<?
		$prefix = "/var/www/netmondata";
		$fp = popen("cd $prefix && find -name $fname", "r");
		$n = 0;
		while ($fname = trim(fgets($fp))) {
			$a = explode('/', $fname);
			$b = "$a[1]-$a[2]-$a[3]";
			$c = strtotime($b);
			if ($c >= strtotime($_GET[datestart]) && $c <= strtotime($_GET[dateend])) {
			}
			$urlfp = fopen("$prefix/$fname", "r");
			while ($l = fgets($urlfp)) {
				if ($n >= $start && $n < $end) {
					$la = preg_split("/\^-+\^/", $l);
					?>
					<tr>
					<td><?= $n ?></td>
					<td><?= $la[0] ?></td>
					<td><?= $la[1] ?></td>
					<td><?= $la[2] ?></td>
					</tr>
					<?
				}
				$n++;
			}
		}
	}
	?></table><?
?>
