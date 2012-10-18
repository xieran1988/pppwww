<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>测小区带宽OA系统</title>
<link href="css/bootstrap.css" rel="stylesheet">
<script src=/jquery.js> </script>
<script src=js/bootstrap.js> </script>
<script>	
var qry = window.location.search.substr(1);
var params = qry.split('&');
var m;
for (var i in params) {
	var t = params[i];
	console.log(params);
	if (t.match(/^t=/)) {
		m = t;
	}
}
$('form').append('<input type=hidden name=t value=' + m.substr(2) + ' >');
$('form').attr('action', '?');
$('#right-pan button').addClass("btn");
//	$('#right-pan form').addClass("well");
$('#right-pan a').each(function (i) {
	console.log($(this));
	var h = $(this).attr('href');
	console.log(m);
	if (h) {
		h = '?' + m + '&' + h.substr(h.indexOf('?') + 1);
		console.log(h);
		$(this).attr('href', h);
	}
});
</script>
</head>

<body>
<?php
require_once("sql.php"); 
require_once("fun.php");
if(login_check()==0) return;
?>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
	<div class="container-fluid">
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		</a>
		<a class="brand" href="?index">小区宽带OA系统</a>

		<div class="nav-collapse">
		<ul id=topnav class="nav">
		</ul>
		</div><!--/.nav-collapse -->

	</div>
	</div>
</div>

<div class="container-fluid">
<div class="row-fluid">

				<div class="span3"> 
				<div class="well sidebar-nav">
				<ul class="nav nav-list">
					<li class=nav-header>业务办理</li>
					<li><a href=?t=getid>获取账号</a></li>
					<li><a href=?t=putbis>业务录入</a></li>
					<li><a href=?t=findusr>查找用户信息</a></li>
					<li class=nav-header>业务查询</li>
					<li><a href=?t=qrybis>业务查询</a></li>
					<li class=nav-header>系统管理</li>
					<li><a href=?t=manstaff>员工管理</a></li>
					<li><a href=?t=manprice>资费管理</a></li>
					<li><a href=?t=manband>带宽管理</a></li>
					<li><a href=?t=modpass>修改密码</a></li>
					<li class=nav-header>组织管理</li>
					<li><a href=?t=manorg>组织管理</a></li>
				</ul>
				</div>
				</div>

				<div id="right-pan" class=span7 >
				<?php require_once("business_management.php") ?>
				<?php require_once("business_list.php") ?>
				<?php require_once("system.php") ?>
				<?php require_once("org.php") ?>
				</div>

		</div>
		</div>
	
</body>
</html>

