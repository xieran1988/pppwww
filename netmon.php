
<? if ($_GET['t'] == 'netmon') { ?>
<form class=well>
从
<input name=datestart type=input class=datetime value="<?= date('Y-m-d') ?>">	
到
<input name=dateend type=input class=datetime value="<?= date('Y-m-d') ?>">	
<button type=submit>提交</button>
</form>
<? } ?>

<?
//	error_reporting(E_ALL);
	$pagenr = 10;
	$start = 0;
	if ($_GET['start'])
		$start = $_GET['start'];
	$end = $start + $pagenr;
	if ($_GET['datestart'] && $_GET['dateend']) {
		?> 
		<table class="table table-condensed netmon-table">
			<tr>
				<td>编号</td>
				<td>用户名</td>
				<td>日期</td>
				<td>记录</td>
			</tr>
		<?
		$prefix = "/var/www/netmondata";
		$fp = popen("cd $prefix && find -name url", "r");
		$n = 0;
		while ($fname = trim(fgets($fp))) {
			$a = explode('/', $fname);
			$b = "$a[1]-$a[2]-$a[3]";
			$c = strtotime($b);
			if ($c >= strtotime($datestart) && $c <= strtotime($dateend)) {
			}
			$urlfp = fopen("$prefix/$fname", "r");
			while ($l = fgets($urlfp)) {
				if ($n >= $start && $n < $end) {
					$la = preg_split("/\^-+\^/", $l);
					?>
					<tr>
					<td><?= $la[0] ?></td>
					<td><?= $la[1] ?></td>
					<td><?= $la[2] ?></td>
					</tr>
					<?
				}
				$n++;
			}
		}
		
		?></table><?
	}
?>
