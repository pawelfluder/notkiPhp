<?php	

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

function CheckIfSaveBtnClicked($rootPath)
{
	if (isset($_POST['przycisk1']))
	{
		if ($_POST['przycisk1'] == "zapis1"  ) 
		{
			WriteToFile($rootPath);
		}
	}
}

function WriteToFile($rootPath)
{
	$textTypeName = "text";	
	$textItemPath = FindLastFolderPathForIndex($textTypeName, $rootPath);
	
	$folderTypeName = "folder";
	$folderItemPath = FindLastFolderPathForIndex($folderTypeName, $rootPath);
	
	$postType = $_POST["select1"];
	if ($postType == 'text')
	{
		$directory = $textItemPath;
	}
	if ($postType == 'folder')
	{
		$directory = $folderItemPath;
	}
	
	$postTextArea = $_POST["pole1"];
	
	if ($postTextArea != "")
	{
		$num = intval(LastFileNumber())+1;	
		if ($num < 10)
		{
			$num = "0$num";
		}//echo $num;
		
		copy_directory($directory, "$num");
		
		$filename = "$num/nazwa.txt";
		if(file_exists($filename))
		{
			$file = fopen($filename,"a+");
			fwrite($file, $postTextArea);
			fclose($file);
		}
		
		CreateNameFile($num, $postTextArea);
		if ($postType == 'text')
		{
			CreateListFile($num);
		}
		
		header("Refresh:0");
	}	
}

function CreateNameFile($num, $name)
{
	$filePath = "$num\\nazwa.txt";
	$myfile = fopen($filePath, "w");
	fwrite($myfile, $name);
	fclose($myfile);
}

function CreateListFile($num)
{
	$filePath = "$num\\lista.txt";
	$myfile = fopen($filePath, "w");
	fwrite($myfile, "\n\n\n\n");
	fclose($myfile);
}



?>