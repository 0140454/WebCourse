<?php
	function AlertRedirect($message, $url)
	{
		echo <<< END
			<form action="redirect.php" method="post" name="redirect">
				<input name="url" type="hidden" value="$url">
				<input name="msg" type="hidden" value="$message">
			</form>
			<script>
				document.redirect.submit();
			</script>
END;
		exit(0);
	}
	
	function ErrorMsg($message)
	{
		if (strlen($message))
			return "<span class=\"glyphicon glyphicon-exclamation-sign\"></span>" . $message . "<br>";
		else
			return "";
	}
	
	function GoogleMetaSearch($query, $page)
	{
		// Get original html
		$html = file_get_contents("https://www.google.com.tw/search?hl=zh-TW&ei=SmN3VJzXKYHOmwWszIHABA&oe=utf-8&start=" . ($page - 1) * 10 . "&q=" . $query);
		// Fetch data
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);
				
		$titles = $xpath->query("//html/body/table/tbody/tr/td/div/div/div/div/ol/li/h3");
		$urls = $xpath->query("//html/body/table/tbody/tr/td/div/div/div/div/ol/li/h3/a/@href");
		$contents = $xpath->query("//html/body/table/tbody/tr/td/div/div/div/div/ol/li/div/span");
		for ($i = 0, $k = 0; $i < $titles->length; $i++)
		{
			$content = "";
			
			if (preg_match("/^https?:\/\/([-\w]*\.)\S*$/", $urls->item($i)->childNodes->item(0)->nodeValue)) // Original URL
			{
				$url = $urls->item($i)->childNodes->item(0)->nodeValue;
			}
			else // Processed URL
			{
				$url = substr($urls->item($i)->childNodes->item(0)->nodeValue, 7);
				$url = substr($url, 0, strpos($url, "&"));
				
				if (preg_match("/^https?:\/\/([-\w]*\.)\S*$/", $url)) // Normal page
				{
					foreach ($contents->item($k)->childNodes as $node)
						$content = $content . $node->nodeValue;
					$k++;
				}
				else // Google page
				{
					$url = "https://www.google.com" . $urls->item($i)->childNodes->item(0)->nodeValue;
				}
			}
			
			$result[0] = $titles->item($i)->childNodes->item(0)->nodeValue;
			$result[1] = $url;
			$result[2] = $content;
			$results[] = $result;
		}

		return $results;
	}
	
	function YahooMetaSearch($query, $page)
	{
		// Get original html
		$html = file_get_contents("https://tw.search.yahoo.com/search?ei=utf-8&b=" . (($page - 1) * 10 + 1) . "&p=" . $query);
		// Fetch data
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);
				
		$titles = $xpath->query("//html/body/div/div/div/div/div/div/div/ol/li/div/div/h3/a[@id]");
		$urls = $xpath->query("//html/body/div/div/div/div/div/div/div/ol/li/div/div/h3/a[@id]/@href");
		$contents = $xpath->query("//html/body/div/div/div/div/div/div/div/ol/li/div/div[@class='abstr']");
		for ($i = 0; $i < $titles->length; $i++)
		{
			$result[0] = $titles->item($i)->nodeValue;
			$result[1] = $urls->item($i)->nodeValue;
			$result[2] = $contents->item($i)->nodeValue;
			$results[] = $result;
		}

		return $results;
	}
	
	function MetaSearch($query, $page)
	{
		$google_result = GoogleMetaSearch($query, $page);
		$yahoo_result = YahooMetaSearch($query, $page);
		
		for ($i = 0; $i < count($google_result); $i++)
		{
			$results[] = $google_result[$i];
			$existed_url[] = $google_result[$i][1];
		}
		
		for ($i = 0; $i < count($yahoo_result); $i++)
		{
			if (!in_array($yahoo_result[$i][1], $existed_url))
				$results[] = $yahoo_result[$i];
		}

		return $results;
	}
	
	function SearchCookieBookmarks($array, $keyword, $range)
	{
		for ($i = 0; $i < count($array); $i++)
		{
			if (strtolower($range) == "title" && strpos(urldecode($array[$i]["title"]), $keyword) !== false)
				$results[] = $array[$i];
			else if (strtolower($range) == "desc" && strpos(urldecode($array[$i]["desc"]), $keyword) !== false)
				$results[] = $array[$i];
			else if (strtolower($range) == "url" && strpos(urldecode($array[$i]["url"]), $keyword) !== false)
				$results[] = $array[$i];
			else if (strpos(urldecode($array[$i]["title"]), $keyword) !== false ||
				  strpos(urldecode($array[$i]["desc"]), $keyword) !== false ||
				  strpos(urldecode($array[$i]["url"]), $keyword) !== false)
				$results[] = $array[$i];
		}
		
		return $results;
	}
	
	function GetCookieBookmarksId($array)
	{
		return $array[count($array) - 1]["id"] + 1;
	}
?>