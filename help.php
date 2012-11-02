
<?
	if (!$_GET[p])
		header('Location: ?p=1&t=1');
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title> 帮助文档 </title>
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
			.netmon-table {
				width: 500px ;
			}
			.graph-form data {
				display:none;
			}
			.graph {
				display:none;
				width: 600px;
				height: 400px;
			}
			#qrybis_form span {
				
			}
			.search select {
				width: 110px;
				margin: 0px;
			}
			.large {
				width: 300px;
			}
			.search input {
				margin: 0px;
			}
			li .tab {
				margin-left: 10px;
			}
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">

  </head>

  <body>

 	<div class="container-fluid">

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
		    <div class="container-fluid">
          <a class="brand" href="#">帮助文档</a>
						<ul class="nav pull-right">
						</ul>
				</div>
      </div>
    </div>

      <div class="row-fluid">
        <div class="span3">
					<div class="well sidebar-nav">
						<ul class="nav nav-list">
						<?
							$fp = fopen("man/b.txt", "r");
							$arr = array();
							while (!feof($fp)) {
								$arr[] = (int)fgets($fp);
							}
							fclose($fp);
							$fp = fopen("man/a.txt", "r");
							fread($fp, 3);
							$n = 0;
							while (!feof($fp)) {
								$l = fgets($fp);
								$h = "href='?t=$n&p=$arr[$n]' act='?t=$n'";
								if ($l[0] == '=') {
									?><li class=nav-header><a <?=$h?>><?= substr($l, 1) ?></a></li><?
								} else if ($l[0] == ' ') {
									?><li><a <?=$h?>>&nbsp; &nbsp; <?= $l ?></a></li><?
								} else {
									?><li><a <?=$h?>><?= $l ?></a></li><?
								}
								$n++;
							}
						?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->

        <div id=right-pan class="span9">
				<? require_once("help2.php") ?>
        </div><!--/span-->

      </div><!--/row-->

			<hr>
			<footer>
				<p>&copy; 盈捷万通 2012</p>
			</footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.time.js"></script>
    <script src="js/jquery.ocupload-min.js"></script>
    <script src="postfix.js"></script>
  </body>

</html>
