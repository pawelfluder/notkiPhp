<?php
	$public = "public_html";
	$pwd = getcwd();
	$twoPathParts = explode($public, $pwd);
	$publicPath = "$twoPathParts[0]$public";
	
	$mostCommonFilePath = "$publicPath/items/common/index.php";
	include "$mostCommonFilePath";
	//echoVar("mostCommonFilePath", $mostCommonFilePath);
	
	CheckIfIndexIsUpToDate();
	
	$protectedFilePath = "$publicPath/items/protected.php";
	include "$protectedFilePath";
	//echoVar("protectedFilePath", $protectedFilePath);
	
	$protectedFilePath = "$publicPath/items/protected.php";
	include "$protectedFilePath";
	//echoVar("protectedFilePath", $protectedFilePath);
	
	function CheckIfIndexIsUpToDate()
	{
		$typeOfIndex = "folder/";
		
		$public = "public_html";
		$pwd = getcwd();
		$twoPathParts = explode($public, $pwd);
		$folderPath = "$twoPathParts[0]$public/items/$typeOfIndex";
		
		$lastFolderPath = FindLastFolder($folderPath);
		$lastFolderIndexPath = $lastFolderPath."/index.php";
		$index = "index.php";
		$IsUpToDate = identical($lastFolderIndexPath, $index);
		
		//echoVar("folderPath", $folderPath);
		//echoVar("lastFolderIndexPath", $lastFolderIndexPath);
		//echoVar("pwd/gg.php", "$pwd/gg.php");
		//echoVar("IsUpToDate", $IsUpToDate);			
		if ($IsUpToDate)
		{
			echo "Index was up to date</br>";
		}
		else
		{
			echo "Index was updated, because was not up to date</br>";
			copy($lastFolderIndexPath, "$pwd/index.php");
		}	
	}
	
	function FindLastFolder($pathToSearch)
	{
		//echo "FindLastFolder()</br>";
		//echo "$pathToSearch</br>";
		if ($handle = opendir($pathToSearch))
		{
			// doputy mamy kolejne pliki lub foldery
			while (false !== ($fileOrFolderName = readdir($handle))) 
			{
				$path = $pathToSearch.$fileOrFolderName;
				if (is_dir($path))
				{
					if (substr($path, -1) != "." 
						&& substr($path, -2) != "..")
					{
						$pathList[] = $path;
					}			
				}
			}
			
			sort($pathList);
			/*for ($i = 0; $i < count($pathList); $i++)
			{
				echo "pathList[$i] = $pathList[$i]</br>";
			}*/
		}
		
		$lastFolderPath = end($pathList);
		//echoVar("lastFolderPath", $lastFolderPath);
		
		return $lastFolderPath;
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

<html>
<head>
	<title>
	<?php ReadFolderName()?>
	</title>
</head>

<body style="background-color: rgb(225,225,225)">
	<?php CheckIfSaveBtnClicked() ?>
	<?php CheckPasswordAndPrintPageView() ?>
</body>
</html>

<?php
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
				<b><h2>"
					.ReadFolderName()
				."<h2></b>
			</tr>
			<tr>
				<td style='vertical-align: top'>
					<input type='submit' name='przycisk1' value='zapis1'><br/>
					<textarea rows='1' cols='69' name='pole1'></textarea>
				</td>
			</tr>
			<tr>
				<td style='vertical-align: top'>";
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
		$len1 = $i;//echo "długosc: $len1</br>";
		closedir($handle);
		
		sort($name);
		for ($i = 0; $i < $len1; $i++){
			echo $name[$i];
		}
		return $name[$len1-1];
	}
}

function WriteToFile()
{
	//echo "WriteToFile()</br>";
	$public = "public_html";
	$pwd = getcwd();
	$twoPathParts = explode($public, $pwd);
	$src = "$twoPathParts[0]$public/items/templates/text/";
	echo "src: $src</br>";

	$num = intval(LastFileNumber())+1;	
	if ($num < 10)
	{
		$num = "0$num";
	}//
	echo $num;
	
	mkdir("$num", 0777);
	copy_directory($src, "$num");
	
	$post = $_POST["pole1"];
	$filename = "$num/nazwa.txt";
	if(file_exists($filename)){
		$file = fopen($filename,"a+");
		fwrite($file, $post);
		fclose($file);
	}
	header("Refresh:0");
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

