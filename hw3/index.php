<?php
	require_once("include.inc.php");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (strlen($_SESSION["password"]))
		{
			if (strlen($_POST["title"]) && strlen($_POST["url"]))
			{
				$connect->query("INSERT INTO bookmarks (`owner`, `title`, `desc`, `url`) VALUES ('" . $_SESSION["id"] . "', '" . $_POST["title"] . "', '" . $_POST["desc"] . "', '" . urlencode($_POST["url"]) . "')");
				
				if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
				{
					echo md5($connect->insert_id . "delete");
					exit(0);
				}
			}
		}
		else
		{
			$bookmarks = json_decode(stripslashes($_SESSION["bookmarks"]), true);
			$added_bookmark = array(
				"id" => GetCookieBookmarksId($bookmarks),
				"title" => urlencode($_POST["title"]),
				"desc" => urlencode($_POST["desc"]),
				"url" => urlencode($_POST["url"])
			);
			$bookmarks[] = $added_bookmark;
			$_SESSION["bookmarks"] = json_encode($bookmarks, true);
			
			if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
			{
				echo md5($added_bookmark["id"] . "delete");
				exit(0);
			}
		}
		
		Header("Location: .");
		exit(0);
	}
	else
	{
		if (strlen($_SESSION["password"]))
		{
			if (strlen($_GET["query"]))
			{
				if (strtolower($_GET["range"]) == "title")
					$filter = " AND (`title` LIKE '%" . $_GET["query"] . "%')";
				else if (strtolower($_GET["range"]) == "desc")
					$filter = " AND (`desc` LIKE '%" . $_GET["query"] . "%')";
				else if (strtolower($_GET["range"]) == "url")
					$filter = " AND (`url` LIKE '%" . $_GET["query"] . "%')";
				else
					$filter = " AND (`title` LIKE '%" . $_GET["query"] . "%' OR `desc` LIKE '%" . $_GET["query"] . "%' OR `url` LIKE '%" . $_GET["query"] . "%')";
			}
			else if (isset($_GET["query"]))
			{
				Header("Location: .");
				exit(0);
			}

			$result = $connect->query("SELECT * FROM bookmarks WHERE owner=" . $_SESSION["id"] . $filter);
			while($row = $result->fetch_array())
				$bookmarks[] = $row;
		}
		else
		{
			$bookmarks = json_decode(stripslashes($_SESSION["bookmarks"]), true);
			if (strlen($_GET["query"]))
			{
				$bookmarks = SearchCookieBookmarks($bookmarks, $_GET["query"], $_GET["range"]);
			}
			else if (isset($_GET["query"]))
			{
				Header("Location: .");
				exit(0);
			}
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>F74022167 - Homework 3 - 首頁</title>
		
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
					<?php
						if (!strlen($_SESSION["password"]))
						{
							echo <<< END
								<div class="col-md-12">
									<div class="col-md-8 col-md-offset-2">
										<div class="alert alert-info" role="alert">
											請立即登入 / 註冊，以存取您的書籤並使用完整的功能！<br>
											目前操作將儲存於本機Cookie中，無法線上存取喔～
										</div>
									</div>
								</div>
END;
						}
					?>
					<ul class="nav nav-tabs" data-tabs="tabs">
						<li <?php echo ((strlen($_GET["query"])) ? "" : "class=\"active\""); ?> ><a href="#add" data-toggle="tab">新增書籤</a></li>
						<li <?php echo ((strlen($_GET["query"])) ? "class=\"active\"" : ""); ?> ><a href="#search" data-toggle="tab">書籤搜尋</a></li>
					</ul>
					<div class="tab-content">
						<div class=	"tab-pane fade <?php echo ((strlen($_GET["query"])) ? "" : "in active"); ?>" id="add">
							<br>
							<form class="form-inline" method="post" role="form">
								<div class="form-group">
									<label style="color: white; width: 80px;">名稱</label>
									<input class="form-control" name="title" placeholder="請輸入名稱" type="text">
								</div>
								<div class="form-group">
									<label style="color: white; width: 80px;">敘述</label>
									<input class="form-control" name="desc" placeholder="請輸入描述" type="text">
								</div>
								<div class="form-group">
									<label style="color: white; width: 80px;">網址</label>
									<input class="form-control" name="url" placeholder="請輸入網址" type="text">
								</div>
								&nbsp;&nbsp;<button class="btn btn-primary" type="submit">新增</button>
							</form>
							<br>
						</div>
							
						<div class="tab-pane fade <?php echo ((strlen($_GET["query"])) ? "in active" : ""); ?>" id="search">
							<br>
							<form class="form-inline" method="get" role="form">
								<div class="form-group">
									<label style="color: white; width: 80px;">關鍵字</label>
									<input class="form-control" name="query" placeholder="請輸入關鍵字" type="text" value="<?php echo $_GET["query"]; ?>">
								</div>
								<div class="form-group">
									<label style="color: white; width: 80px;">搜尋範圍</label>
									<select class="form-control" name="range">
										<option value="all">全部</option>
										<option value="title">名稱</option>
										<option value="desc">敘述</option>
										<option value="url">網址</option>
									</select>
								</div>
								&nbsp;&nbsp;<button class="btn btn-primary" type="submit">搜尋</button>
								<?php echo ((strlen($_GET["query"]))) ? "&nbsp;&nbsp;<button class=\"btn btn-primary\" onclick=\"location=location.href.split('?')[0];\" type=\"button\">清除</button>" : ""; ?>
							</form>
							<br>
						</div>
					</div>
					<br>
					<table class="table table-striped">
						<tr>
							<th style="text-align: center; width: 200px;">名稱</th>
							<th style="text-align: center; width: 480px;">敘述</th>
							<th style="text-align: center; width: 160px;">操作</th>
						</tr>
						
						
						<?php
							for ($i = 0; $i < count($bookmarks); $i++)
							{
								echo "<tr>";
								echo "<td class=\"vert-align\" style=\"text-align: center; width: 200px;\"><a href=\"" . urldecode($bookmarks[$i]["url"]) . "\" style=\"color: blue;\" target=\"_blank\">" . urldecode($bookmarks[$i]["title"]) . "</a></td>";
								echo "<td class=\"vert-align\" style=\"width: 480px;\">" . urldecode($bookmarks[$i]["desc"]) . "</td>";
								echo "<td class=\"vert-align\" style=\"text-align: center; width: 80px;\">";
								if (strlen($_SESSION["password"]))
									echo "<a href=\"modify.php?id=" . md5($bookmarks[$i]["id"] . "modify") . "\"><button class=\"btn btn-sm btn-success\">修改</button></a>&nbsp;&nbsp;";
								echo "<a href=\"delete.php?id=" . md5($bookmarks[$i]["id"] . "delete") . "&ref=" . urlencode($_SERVER["REQUEST_URI"]) . "\"><button class=\"btn btn-sm btn-danger\">移除</button></a>" . "</td>";
								echo "</tr>";
							}
							
							if (!count($bookmarks))
							{
								echo "<tr>";
								echo "<td colspan=\"3\" style=\"text-align: center\">" . ((strlen($_GET["query"])) ? "查無書籤" : "尚未新增任何書籤") . "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>