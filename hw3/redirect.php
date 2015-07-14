<?php
	require_once("include.inc.php");
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="refresh" content="<?php echo "5; url=" . $_POST["url"]; ?>">
		<title>F74022167 - Homework 3 - 提示訊息</title>
		
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
				</div>
			</div>
		</nav>
		<!-- 內容 -->
		<div class="container">
			<div class="row clearfix top-buffer">
				<div class="col-md-12" style="text-align: center">
					<div class="post-box">
						<h2><?php echo $_POST["msg"]; ?></h2>
						<a href="<?php echo $_POST["url"]; ?>" style="color: blue; font-size: small;">將於5秒後重新導向，若無自動導向請點擊此處繼續...</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>