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

?>



