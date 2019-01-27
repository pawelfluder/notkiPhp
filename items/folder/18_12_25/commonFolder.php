<?php	

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

function PrintPasswordForm()
{
	echo "<form method=post>
			<input type='password' name='pass'></br>
			<input type='submit' value='Zaloguj'>
		</form>";
}

function PrintPageView()
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
?>