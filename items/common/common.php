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

function CheckIfSaveBtnClicked()
{
	if (isset($_POST['przycisk1']))
	{
		if ($_POST['przycisk1'] == "zapis1"  ) 
		{
			WriteToFile();
		}
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
		printPageView();
	}
	else
	{
		printPasswordForm();
	}
}

function printPasswordForm()
{
	echo "<form method=post>
			<input type='password' name='pass'></br>
			<input type='submit' value='Zaloguj'>
		</form>";
}

function printPageView()
{
	//echo "page view</br>";
	echo "<form name='formularz1' method='post' action=''>
		<table>
			<tr>
				<td colspan=2>
					<b><h2>"
						.ReadFolderName()
					."<h2></b>
				</td>
			</tr>
			<tr>
				<td style='vertical-align: top'>
					<input type='submit' name='przycisk1' value='zapis1'><br/>
				</td>
				<td>
					<select name='select1'>
						<option>text</option>
						<option>folder</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<textarea rows='1' cols='69' name='pole1'></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2 style='vertical-align: top'>";
					echo ReadFoldersName();
				echo"</td>
			</tr>
		</table>
	</form>";
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
			if(file_exists($filename))
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

function WriteToFile()
{
	$public = "public_html";
	$pwd = getcwd();
	$twoPathParts = explode($public, $pwd);
	$publicPath = "$twoPathParts[0]$public";
	$itemsPath = "";
	
	$textItemPath = "$publicPath/items/templates/text/";
	$folderItemPath = "$publicPath/items/templates/folder/";
	
	$postType = $_POST["select1"];
	if ($postType == 'text')
	{
		$directory = $textItemPath;
	}
	if ($postType == 'folder')
	{
		$directory = $folderItemPath;
	}
	
	echoVar("textItemPath", $textItemPath);
	echoVar("folderItemPath", $folderItemPath);
	
	$postTextArea = $_POST["pole1"];
	
	if ($postTextArea != "")
	{
		$num = intval(LastFileNumber())+1;	
		if ($num < 10)
		{
			$num = "0$num";
		}//echo $num;
		
		mkdir("$num", 0777);
		
		copy_directory($directory, "$num");
		
		$filename = "$num/nazwa.txt";
		if(file_exists($filename)){
			$file = fopen($filename,"a+");
			fwrite($file, $postTextArea);
			fclose($file);
		}
		
		header("Refresh:0");
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
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir);
}

?>



