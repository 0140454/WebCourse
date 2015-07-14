<?php
	require_once("include.inc.php");
	
	if (!strlen($_SESSION["password"]))
	{
		Header("Location: .");
		exit(0);
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (strlen($_POST["name"]) && strlen($_POST["email"]))
		{
			if (strlen($_POST["new_password"]))
				$connect->query("UPDATE hw3_users SET `real_name`='" . $_POST["name"] . "', `email`='" . $_POST["email"] . "', `password`=md5('" . $_POST["new_password"] . "') WHERE id=" . $_SESSION["id"]);
			else
				$connect->query("UPDATE hw3_users SET `real_name`='" . $_POST["name"] . "', `email`='" . $_POST["email"] . "' WHERE id=" . $_SESSION["id"]);
			
			$result = $connect->query("SELECT * FROM hw3_users WHERE id=" . $_SESSION["id"]);
			$row = $result->fetch_array();
			$_SESSION = $row;
			
			AlertRedirect("個人資訊修改成功！", "profile.php");
		}
		else
		{
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["name"])) ? "" : "未輸入真實姓名");
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["email"])) ? "" : "未輸入Email");
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - 個人資訊修改</title>
		
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
						<?php
							if (strlen($_SESSION["password"]))
								echo "<li><a href=\"profile.php\">" . $_SESSION["real_name"] . "</a></li>";
						?>
						<li><?php echo (strlen($_SESSION["password"])) ? "<a href=\"logout.php\">登出</a>" : "<a href=\"login.php\">登入</a>"; ?></li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- 主要內容 --> 
		<div class="container">
			<div class="row clearfix top-buffer">
				<div class="col-md-12" style="text-align: center">
					<h1 style="color: white;">維護個人資訊</h1>
					<div class="col-md-4 col-md-offset-4" style="text-align: left">
						<?php if (strlen($error_msg)) echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error_msg . "</div>"; ?>
						<form class="form-horizontal" method="post" role="form">
							<table>
								<div class="form-group">
									<label style="color: white;">姓名</label>
									<input class="form-control" name="name" placeholder="真實姓名" type="text" value="<?php echo $_SESSION["real_name"]; ?>">
								</div>
								<div class="form-group">
									<label style="color: white;">Email</label>
									<input class="form-control" name="email" placeholder="可連絡上的Email" type="email" value="<?php echo $_SESSION["email"]; ?>">
								</div>
								<div class="form-group">
									<label style="color: white;">新密碼</label>
									<input class="form-control" name="new_password" placeholder="若不變更密碼請留空" type="password">
								</div>
								<button class="btn btn-primary pull-right" type="submit">修改</button>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
