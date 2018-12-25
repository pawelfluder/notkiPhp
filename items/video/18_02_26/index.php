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
		$typeOfIndex = "video/";
		
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
	<?php CheckPasswordAndPrintPageView() ?>
</body>
</html>

<?php
function FindVideoFiles()
{
	$dir = '.';
	$files = scandir($dir,1);

	$i = 0;
	foreach($files as &$file)
	{
		if (substr($file,-4,4) == ".mp4")//|| substr($file,-5,5) == ".3gpp")
		{
			$videos[$i] = $file;
			$i++;//foreach($videos as &$v){echo "$v</br>";}
		}
	}
	$video = $videos[0];
	echo "<h2>$video</h2>";
	$context = "
	<video width='320' height='240' controls>
		<source src='$video' type='video/mp4'>
	</video>";
	echo $context;
}

function printPageView()
{
	FindVideoFiles();
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

?>