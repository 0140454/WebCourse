<?php
	require_once("include.inc.php");
	
	if (strlen($_SESSION["password"]))
	{
		Header("Location: .");
		exit(0);
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (strlen($_POST["username"]) && strlen($_POST["password"]) && strlen($_POST["name"]) && strlen($_POST["email"]))
		{
			$result = $connect->query("SELECT * FROM hw3_users WHERE username='" . $_POST["username"] . "'");
			if ($result->num_rows)
			{
				$error_msg = $error_msg . ErrorMsg("指定帳號已存在");
			}
			else
			{
				$connect->query("INSERT INTO hw3_users (`username`, `password`, `real_name`, `email`) VALUES ('" . $_POST["username"] . "', md5('" . $_POST["password"] . "'), '" . $_POST["name"] . "', '" . $_POST["email"] . "')");
				AlertRedirect("帳號註冊成功！", "login.php");
			}
		}
		else
		{
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["name"])) ? "" : "未輸入真實姓名");
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["username"])) ? "" : "未輸入帳號");
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["password"])) ? "" : "未輸入密碼");
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["email"])) ? "" : "未輸入Email");
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - 使用者註冊</title>
		
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
						<li><a href="login.php">登入</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- 主要內容 --> 
		<div class="container">
			<div class="row clearfix top-buffer">
				<div class="col-md-12" style="text-align: center">
					<h1 style="color: white;">註冊帳戶</h1>
					<div class="col-md-4 col-md-offset-4" style="text-align: left">
						<?php if (strlen($error_msg)) echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error_msg . "</div>"; ?>
						<form class="form-horizontal" method="post" role="form">
							<table>
								<div class="form-group">
									<label style="color: white;">帳號</label>
									<input class="form-control" name="username" placeholder="帳戶名稱" type="text" value="<?php if (strpos($error_msg, "指定帳號已存在") === false) echo $_POST["username"]; ?>">
								</div>
								<div class="form-group">
									<label style="color: white;">密碼</label>
									<input class="form-control" name="password" placeholder="帳戶密碼" type="password">
								</div>
								<div class="form-group">
									<label style="color: white;">姓名</label>
									<input class="form-control" name="name" placeholder="真實姓名" type="text" value="<?php echo $_POST["name"]; ?>">
								</div>
								<div class="form-group">
									<label style="color: white;">Email</label>
									<input class="form-control" name="email" placeholder="可連絡上的Email" type="email" value="<?php echo $_POST["email"]; ?>">
								</div>
								<button class="btn btn-primary pull-right" type="submit">註冊</button>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
