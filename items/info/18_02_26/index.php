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
		$typeOfIndex = "info/";
		
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
			echo "Index is up to date</br>";
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
	<?php ApplyOnBtnClickScript() ?>
	<?php CheckPasswordAndPrintPageView() ?>
</body>
</html>

<?php

function printPageView()
{
	//echo "printPageView()</br>";
	echo "
		<table>
			<tr>
				<b><h2>"
					.ReadFolderName()
				."<h2></b>
			</tr>
			<tr>				
				<td colspan='2' style='vertical-align: top'>
					<form name='formularz1' method='post' action=''>
						<input type='submit' name='przycisk1' value='zapis1'><br/>
						<textarea rows='1' cols='69' name='pole1'></textarea>
					</form>
				</td>
			</tr>
			<tr>
				<td valign='top' width='160px'>
					Dostałem numer w/na: </br>
					Data: </br>
					Imię </br>
					Nazwisko </br>
					Wiek </br>
					Numer telefonu </br>
					Facebook </br>
					Fb id </br>przed
				</td>
				<td valign='top'>";
					ReadFromFile();
			echo"</td>
		</tr>
		</table>";
	
}

function GetTextToSave()
{
	$text = $_POST["pole1"];	
	return $text;
}

function ApplyOnBtnClickScript()
{
	$filePath = "lista.txt";
	$filePath2 = "../06/lista.txt";
	if (isset($_POST['przycisk1']))
	{
		if ($_POST['przycisk1'] == "zapis1"  ) 
		{
			$textToSave = GetTextToSave();
			SaveContent($filePath, $textToSave);
		}
	}
}

function SaveContent($filePath, $textToSave)
{
	$upDnSetting = GetDownOrUpSetting();
	
	if($upDnSetting == "Up")
	{
		$textToSave2 = "$upDnSetting\n\n\n\n$textToSave\n";
		$fileContent = file_get_contents($filePath);	
		$fileContent = str_chop_lines($fileContent);
		$textToSave2 .= $fileContent;
		$textToSave2 .= file_put_contents($filePath, $textToSave2);
		echo "SaveContent: in up if</br>";
	}
	else if($upDnSetting == "Down")
	{
		$file = fopen($filePath,"a+");			
		fwrite($file, "$textToSave\n");
		fclose($file);
		echo "SaveContent: in down if</br>";
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

function ReadFolderName()
{
	$filename = "nazwa.txt";
	if(file_exists($filename))
	{
		$file = fopen($filename, "r");
		$size = filesize($filename);
		if ($size > 0)
		{
			$text = fread($file, $size);
		}
		else
		{
			$text = "";
		}
		echo $text;
		fclose($file);
	}
}

function GetDownOrUpSetting()
{
	$filename = "lista.txt";
	if(file_exists($filename))
	{
		if ($file = fopen($filename, "r")) {
			if (($line = fgetss($file)) !== false)
			{
				$upDownLine = $line;
			}
		}
	}
	//echoVar("upDownLine", $upDownLine);
	
	$upString = "Up";
	$downString = "Down";
	
	//Problem: pierwsze dwa znaki było to
	//pytajniki, późnij już nie, więc tekst
	//się przesuwa
	
	//$threeToFive = substr($upDownLine,3,2);
	//echo "threeToFive: $threeToFive</br>";
	
	//$threeToSeven = substr($upDownLine,3,4);
	//echo "threeToSeven: $threeToSeven</br>";
		
	if(strpos($upDownLine, $upString) !== false)
	{
		$upDownSetting = $upString;
	}
	else if(strpos($upDownLine, $downString) !== false)
	{
		$upDownSetting = $downString;
	}
	else
	{
		$upDownSetting = $upString;
	}
	//echoVar("upDnSetting", $upDnSetting);
	
	return $upDownSetting;
}

function ReadFromFile()
{
	//echo "ReadFromFile()";
	$filename = "lista.txt";
	if(file_exists($filename))
	{
		$i = 0;
		if ($file = fopen($filename, "r")) {
			while (($line = fgetss($file)) !== false) {
				$i = $i +1;
				
				if ($i >= 5)
				{	
					$firstTwoChars= substr($line,0,2);
					$firstSevenChars= substr($line,0,7);
					$firstEightChars= substr($line,0,8);
					
					if (strpos($line, "\t") !== false) {
						$line = str_replace("\t", "&nbsp &nbsp &nbsp &nbsp", $line);
					}
									
					if($firstSevenChars == "http://")
					{
						echo "<a href='$line'>$line</a><br>";
					}
					else if($firstEightChars == "https://")
					{						
						echo "<a href='$line'>$line</a><br>";
					}
					else if($firstTwoChars == "//")
					{						
						echo "<font color='DarkSeaGreen'>".$line."</font><br>";
					}
					else if($firstTwoChars == "r/")
					{						
						echo "<font color='red'>".substr($line,2)."</font><br>";
					}
					else if($firstTwoChars == "g/")
					{						
						echo "<font color='green'>".substr($line,2)."</font><br>";
					}
					else if($firstTwoChars == "b/")
					{						
						echo "<font color='blue'>".substr($line,2)."</font><br>";
					}
					else
						{
							echo $line."<br>";
						}
					}
				}
				fclose($file);
			}
		else 
		{
			// error opening the file.
		}
	}
}

function str_chop_lines($str, $lines = 4) 
{
    return implode("\n", array_slice(explode("\n", $str), $lines));
}

?>