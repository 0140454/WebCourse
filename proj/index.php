<?php
	require_once("include.inc.php");
?>

<html>
    <head>
		<meta charset="utf-8">
		<title>漢字五子棋</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/simple-sidebar.css" rel="stylesheet">
		<link href="css/bootstrap-dialog.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
	</head>
    <body style="text-align:center;" onload="Restart();">
		<div id="wrapper">
			<div id="sidebar-wrapper">
				<ul class="sidebar-nav">
					<li class="navbar"></li>
					<li><a href="." style="height: 65px; font-size: 18px; line-height: 65px; color: white;">漢字五子棋</a></li>
					<li><a href="#" onclick="SetupDictionary();">選擇辭庫</a></li>
					<li><a href="#" onclick="SetupPlayerName();">玩家名稱設定</a></li>
					<li><a href="#" onclick="SetupSize();">棋盤大小設定</a></li>
					<li><a href="#" onclick="SetupTimeout();">時間設定</a></li>
					<?php
						if (IsLoggedIn()) {
							echo "<li><a href=\"#\" id=\"mode\" onclick=\"ChangeMode();\">AI模式</a></li>";
						}
					?>
					<li><a href="#" onclick="ShowRank();">排行榜</a></li>
					<li><a href="#" onclick="Restart();">重新遊戲</a></li>
					<?php
						if (IsLoggedIn()) {
							echo "<li><a href=\"manager\">辭庫管理</a></li>";
							echo "<li><a href=\"#\" onclick=\"ShowModifyPage();\">修改密碼</a></li>";
							echo "<li><a href=\"logout.php\">登出</a></li>";
						} else {
							echo <<< END
								<li><a href="#" onclick="ShowLoginPage();">登入</a></li>
								<li><a href="#" onclick="ShowRegisterPage();">註冊</a></li>
END;
						}
					?>
				</ul>
			</div>
		</div>
		<div id="page-content-wrapper">
			<!-- 導航欄 -->
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container-fluid">
					<div class="navbar-header">
						<a href="#menu-toggle" class="navbar-brand" id="menu-toggle"><span class="glyphicon glyphicon-list"></span></a>
					</div>
					<div class="collapse navbar-collapse">
						<div class="nav navbar-nav pull-right">
							<span class="navbar-text" id="cur-mode" style="color: yellow;"></span>
							<span class="navbar-text" id="timeout" style="color: yellow;"></span>
						</div>
					</div>
			</nav>
			<!-- 內容 -->
			<div class="container top-buffer">
				<div class="row">
					<canvas></canvas>
					<canvas></canvas>
				</div>
			</div>
		</div>
		
		<script src="js/script.js"></script>
		<script>
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		</script>
    </body>
</html>