<?php	

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
	$directory = FindLastFolderPathForTemplate("meeting", $rootPath);
	
	$postTextArea = $_POST["pole1"];
	
	if ($postTextArea != "")
	{
		$num = intval(LastFileNumber())+1;	
		if ($num < 10)
		{
			$num = "0$num";
		}//echo $num;
		
		copy_directory($directory, "$num");		
		CreateNameFile($num, $postTextArea);
		
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