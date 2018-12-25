<?php
//tutaj są tylko funkcje, które są koniecznie do index update
function echoVar($name, $var)
{
	$fileType = gettype ($var);
	if ($fileType == "string")
	{
		echo "$name: \"$var\"</br>";
	}
	if ($fileType == "boolean")
	{
		if ($var)
		{
			echo "$name: \"true\"</br>";
		}
		else
		{
			echo "$name: \"false\"</br>";
		}	
	}
}

function IsCurrentDirectoryPointer($path)
{
	return $path == ".";
}

function IsPreviousDirectoryPointer($path)
{
	return $path == "..";
}

function IsHiddenFileOrFolder($path)
{
	return substr($path, 0, 1) == ".";
}

function FindAlphabeticallyLastFolder($folderPath)
{
	$pathList = (array) null;

	if ($handle = opendir($folderPath))
	{
		while (false !== ($current = readdir($handle))) 
		{
			if (IsCurrentDirectoryPointer($current) == false &&
				IsPreviousDirectoryPointer($current) == false &&
				IsHiddenFileOrFolder($current) == false)
			{
				$path = "$folderPath\\$current";
				if (is_dir($path))
				{
					$pathList[] = $path;
				}
			}
		}	
	}
	
	$length = sizeOf($pathList);
	if ($length > 0)
	{
		sort($pathList);
		$alphabeticallyLastFolder = end($pathList);
		return $alphabeticallyLastFolder;
	}
	else
	{
		return null;
	}
}

function FindLastFolderIndexPath($typeName, $rootPath)
{
	$folderPath = "$rootPath\items\\$typeName";
	
	$lastFolderPath = FindAlphabeticallyLastFolder($folderPath);

	$index = "index.php";
	$lastFolderIndexPath = "$lastFolderPath\\$index";

	return $lastFolderIndexPath;
}

function UpdateIndexIfNotUpToDate($isUpToDate, $lastFolderIndexPath)
{
	if ($isUpToDate == false)
	{
		$cwd = getcwd();
		$indexPhpString = "index.php";
		copy($lastFolderIndexPath, "$cwd\\$indexPhpString");
	}
}

function EchoUpToDateStatement($isUpToDate)
{
	if ($isUpToDate == true)
	{
		echo "Index was up to date</br>";
	}
	else
	{
		echo "Index was updated, because was not up to date</br>";
	}
}

function IsIndexUpToDate($lastFolderIndexPath)
{
	$index = "index.php";
	$isUpToDate = identical($lastFolderIndexPath, $index);
	return $isUpToDate;		
}

function identical($fileOne, $fileTwo)
{
	if (filetype($fileOne) !== filetype($fileTwo)) return false;
	if (filesize($fileOne) !== filesize($fileTwo)) return false;
 
	if (! $fp1 = fopen($fileOne, 'rb')) return false;
 
	if (! $fp2 = fopen($fileTwo, 'rb'))
	{
		fclose($fp1);
		return false;
	}
 
	$same = true;
 
	while (! feof($fp1) and ! feof($fp2))
		if (fread($fp1, 4096) !== fread($fp2, 4096))
		{
			$same = false;
			break;
		}
 
	if (feof($fp1) !== feof($fp2)) $same = false;
 
	fclose($fp1);
	fclose($fp2);
 
	return $same;
}

?>



