<?php
	function FetchHWStatus()
	{
		$dir = "../..";
		$handle  = opendir($dir);
		while (($filename = readdir($handle)) !== false)
		{
			$path = $dir . "/" . $filename;
			if (is_dir($path) && $filename != ".." && $filename != ".")
			{
				$student[] = $filename;
				
				$found = 0;
				if (count(glob($path . "/[hH][wW]1*")) > 0)
				{
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[hH][wW]1*/[iI][nN][dD][eE][xX].[hH][tT][mM][lL]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
					
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[hH][wW]1*/[iI][nN][dD][eE][xX].[hH][tT][mM]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
					
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[hH][wW]1*/[iI][nN][dD][eE][xX].[hH][tT][mM][lL]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
					
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[iI][nN][dD][eE][xX].[pP][hH][pP]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
					
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[iI][nN][dD][eE][xX].[hH][tT][mM][lL]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
					
					if (!$found)
					{
						$path_array = glob($path . "/[hH][wW]1*/[iI][nN][dD][eE][xX].[hH][tT][mM]");
						if (count($path_array) > 0)
						{
							$index[] = $path_array[0];
							$found = 1;
						}
					}
				}

				if (!$found)
					$index[] = "";
			}
		}
		closedir($handle);
			
		return array($student, $index);
	}
?>