<?php
	require_once("include.inc.php");
	
	if (strlen($_SESSION["password"]))
	{
		Header("Location: .");
		exit(0);
	}
	
	if (strlen($_POST["username"]) && strlen($_POST["password"]))
	{
		$result = $connect->query("SELECT * FROM hw3_users WHERE username='" . $_POST["username"] . "' AND password=md5('" . $_POST["password"] . "')");
		$row = $result->fetch_array();
		if (!is_null($row))
		{
			$_SESSION = $row;
			Header("Location: .");
			exit(0);
		}
		else
		{
			$error_msg = ErrorMsg("帳號或密碼錯誤，請重新登入！");
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - 使用者登入</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- 導航欄 -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href=".">線上書籤</a>
				</div>

				<div class="collapse navbar-collapse">
					<form action="search.php" class="navbar-form navbar-left" role="search">
						<div class="input-group">
							<input class="form-control" name="query" placeholder="Meta Search" type="text">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">搜尋</button>
							</span>
						</div>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="register.php">註冊</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- 主要內容 --> 
		<div class="container">
			<div class="row clearfix top-buffer">
				<div class="col-md-12" style="text-align: center">
					<h1 style="color: white;">登入帳戶</h1>
					<div class="col-md-4 col-md-offset-4" style="text-align: left">
						<?php if (strlen($error_msg)) echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error_msg . "</div>"; ?>
						<form class="form-horizontal" method="post" role="form">
							<table>
								<div class="form-group">
									<label style="color: white;">帳號</label>
									<input class="form-control" name="username" placeholder="請輸入帳戶名稱" type="text">
								</div>
								<div class="form-group">
									<label style="color: white;">密碼</label>
									<input class="form-control" name="password" placeholder="請輸入帳戶密碼" type="password">
								</div>
								<button class="btn btn-primary pull-right" type="submit">登入</button>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>