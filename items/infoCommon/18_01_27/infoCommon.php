<?php

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

function CheckIfSaveBtnClicked()
{
	$filePath = "lista.txt";
	if (isset($_POST['przycisk1']))
	{
		if ($_POST['przycisk1'] == "zapis1"  ) 
		{
			$textToSave = GetTextToSave();
			SaveContent($filePath, $textToSave);
		}
	}
}

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

function ReadFromFile(){
	$filename = "lista.txt";
	if(file_exists($filename))
	{
		$i = 0;
		if ($file = fopen($filename, "r")) {
			while(!feof($file))
			{
				$line = stream_get_line($file, 200, "\n");
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

function GetTextToSave()
{
	$text = $_POST["pole1"];	
	return $text;
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
	}
	else if($upDnSetting == "Down")
	{
		$file = fopen($filePath,"a+");			
		fwrite($file, "$textToSave\n");
		fclose($file);
	}
	//echoVar("upDnSetting", $upDnSetting);
}

?>