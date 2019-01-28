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

function PrintPasswordForm()
{
	echo "<form method=post>
			<input type='password' name='pass'></br>
			<input type='submit' value='Zaloguj'>
		</form>";
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

function CheckPasswordAndPrintPageView()
{
	//echo "CheckPasswordAndPrintPageView()</br>";
	//start sesji
		session_start();
	//hasło sesji
		if(isset($_SESSION['session_pass']))
		{
			$sessionPass = $_SESSION['session_pass'];
		}
		else
		{
			$sessionPass = "";
		}
	//haslo wpisane do textbox'a
		if(isset($_POST['pass']))
		{
			$passTypedToTextBox = $_POST['pass'];
		}
		else
		{
			$passTypedToTextBox = "";
		}
	//wpisywanie haslo z textbox do sesji
		if ($passTypedToTextBox)
		{
			echoVar("passTypedToTextBox",$passTypedToTextBox);
			$_SESSION['session_pass'] = $passTypedToTextBox;
			header("Refresh:0");
		}
	//sprawdzenia hasła
		$hardcodedPassword = "66071805";
		$isPasswordCorrect = ($hardcodedPassword == $sessionPass);

	//wyswietlenie formularza z haslem lub strony
	if ($isPasswordCorrect == true)
	{
		PrintPageView();
	}
	else
	{
		PrintPasswordForm();
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

function FindLastFolderPathForIndex($typeName, $rootPath)
{
	$folderPath = "$rootPath\items\\$typeName";
	
	$lastFolderPath = FindAlphabeticallyLastFolder($folderPath);

	$lastFolderIndexPath = "$lastFolderPath";

	return $lastFolderIndexPath;
}

function FindLastFolderPathForCommon($typeName, $rootPath)
{
	$folderPath = "$rootPath\items\\$typeName"."Common";
	
	$lastFolderPath = FindAlphabeticallyLastFolder($folderPath);
	
	$lastFolderIndexPath = "$lastFolderPath\\$typeName"."Common.php";

	return $lastFolderIndexPath;
}

function FindLastFolderPathForTemplate($typeName, $rootPath)
{
	$templatePath = "$rootPath\items\\templates\\$typeName";

	return $templatePath;
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

function ReadFolderName()
{
	$filename = "nazwa.txt";
	if(file_exists($filename)){
		$file = fopen($filename, "r");
		$size = filesize($filename);
		if ($size > 0)
			$text = fread($file, $size);
		else
			$text = "";
		echo $text;
		fclose($file);
	}
}

function ReadFoldersName()
{
	$name = array();
	
	// jezeli potrafimy otworzyc odnosnik do bierzacego folderu
	if ($handle = opendir('.')) {
		
		$i = 0;
		// doputy mamy kolejne pliki lub foldery
		while (false !== ($nam0 = readdir($handle))) 
		{
			$filename = "$nam0/nazwa.txt";
			if(IsCurrentDirectoryPointer($nam0) == false &&
				IsCurrentDirectoryPointer($nam0) == false &&
				IsPreviousDirectoryPointer($nam0) == false &&
				IsHiddenFileOrFolder($nam0) == false &&
				file_exists($filename))
			{
				$file = fopen($filename, "r");
				$size = filesize($filename);
				$i++;
				if ($size > 0)
				{				
					$text = "$nam0 <a href='$nam0'>".fread($file, $size)."</a>";
					$name[$i] = "$text</br>";		
				}								
				else
				{
					$name[$i] = "$i</br>";
				}				
				
				fclose($file);
			}
			else
			{
				$i++;
			}
			
		}

		$len = sizeof($name);
		closedir($handle);
		
		sort($name);
		for ($i = 0; $i < $len; $i++){
			echo $name[$i];
		}
	}
}


	
function LastFileNumber()
{
	// jezeli potrafimy otworzyc odnosnik do bierzacego folderu
	if ($handle = opendir('.')) 
	{
		$i = 0;
		// doputy mamy kolejne pliki lub foldery
		while (false !== ($nam0 = readdir($handle))) 
		{
			if(is_dir($nam0))
			{
				$num[$i]=$nam0;//echo "$nam0</br>";
				$i++;
			}
		}
		closedir($handle);
		
		sort($num);
		$LastFileNumber = checkIfFolderNameIsANumber($num);//
		echo "$LastFileNumber</br>";
		
		return $LastFileNumber;
	}
}

function checkIfFolderNameIsANumber($num)
{
	//*
	for ($i=sizeof($num)-1;$i>0;$i--)
	{
		//echo "pętla: $i $num[$i]</br>";
		if(intval($num[$i]) != "0")
		{
			break;
			//echo "$i $num[$i] is_a_number</br>";
		}
		else
		{
			//echo "$i $num[$i] is_not_a_number</br>";
		}
	}	
	return $num[$i];
}

function copy_directory($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                copy_directory($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir);
}

?>



