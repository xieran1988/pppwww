<!DOCTYPE html>

<?php require_once("fun.php") ?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title> 小区宽带 OA 系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">小区宽带 OA 系统</a>
          <div class="nav-collapse collapse">
          </div><!--/.nav-collapse -->
     
						<ul class="nav pull-right">
						<? if ($_GET[t] != 'login') { ?>
							<li><a href=?t=exit>退出登陆</a></li>
						<? } ?>
						</ul>
        </div>
      </div>
    </div>
		
		<div class="container-fluid">

		<? if ($_GET['t'] == 'login') { ?>

			<form class=well method=post action="login.php"
					style="width: 300px; position: relative; top: auto; left: auto; margin: 0 auto; z-index: 1" >
				<div class="control-group">
					<label class="control-label" >用户名</label>
					<div class="controls">
						<input placeholder="请输入用户名..." name="name" type="text" value="<?= $_REQUEST["name"]?>" >
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" >密码</label>
					<div class="controls">
						<input placeholder="请输入密码..." name="password" type="password" value="<?= $_REQUEST["password"]?>" >
					</div>
				</div>
				<button type=submit class="btn btn-primary">登录</a>
			</form>

		<? } else { ?>

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
          </div><!--/.well -->
        </div><!--/span-->

        <div id=right-pan class="span9">
					<?php require_once("business_management.php") ?>
					<?php require_once("business_list.php") ?>
					<?php require_once("system.php") ?>
					<?php require_once("org.php") ?>
					<?php require_once("user_info.php") ?>
        </div><!--/span-->
      </div><!--/row-->

			<hr>
			<footer>
				<p>&copy; 盈捷万通 2012</p>
			</footer>

    </div><!--/.fluid-container-->

		<? } ?>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="postfix.js"></script>

  </body>
</html>
