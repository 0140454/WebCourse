<?php
	require_once("include.inc.php");
	
	if (!strlen($_SESSION["password"]))
	{
		Header("Location: .");
		exit(0);
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (strlen($_POST["title"]) && strlen($_POST["url"]))
		{
			$connect->query("UPDATE bookmarks SET `title`='" . $_POST["title"] . "', `desc`='" . $_POST["desc"] . "', `url`='" . $_POST["url"] . "' WHERE md5(concat(id, 'modify'))='" . $_GET["id"] . "'");

			Header("Location: .");
			exit(0);
		}
		else
		{
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["title"])) ? "" : "未輸入書籤名稱");
			$error_msg = $error_msg . ErrorMsg((strlen($_POST["url"])) ? "" : "未輸入書籤網址");
		}
	}
	
	$result = $connect->query("SELECT * FROM bookmarks WHERE md5(concat(id, 'modify'))='" . $_GET["id"] . "'");
	$row = $result->fetch_array();
	if (!is_null($row))
	{
		$title = $row["title"];
		$desc = $row["desc"];
		$url = $row["url"];
	}
	else
	{
		Header("Location: .");
		exit(0);
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - 書籤修改</title>
		
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
							<input class="form-control" name="query" placeholder="Meta Search" type="text" value="<?php echo $_GET["query"]; ?>">
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
					<div class="col-md-4 col-md-offset-4" style="text-align: left">
						<?php if (strlen($error_msg)) echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error_msg . "</div>"; ?>
						<?php
							echo <<< END
								<form method="post" role="form">
									<div class="form-group">
										<label style="color: white;">名稱</label>
										<input class="form-control" name="title" placeholder="請輸入名稱" type="text" value="$title">
									</div>
									<div class="form-group">
										<label style="color: white;">敘述</label>
										<input class="form-control" name="desc" placeholder="請輸入描述" type="text" value="$desc">
									</div>
									<div class="form-group">
										<label style="color: white;">網址</label>
										<input class="form-control" name="url" placeholder="請輸入網址" type="text" value="$url">
									</div>
									<button class="btn btn-primary pull-right" type="submit">修改</button>
								</form>
END;
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
