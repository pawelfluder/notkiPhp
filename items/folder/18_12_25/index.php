<?php
	function GetRootPath()
	{
		$cwd = getcwd();
		$pubString = "public_html";
		$twoPathParts = explode($pubString, $cwd);
		$rootPath = "$twoPathParts[0]$pubString";
		return $rootPath;
	}
	
	function OnStart()
	{
		$rootPath = GetRootPath();
		$commonPath = "$rootPath\items\common\common.php";
		include "$commonPath";

		$typeName = "folder";
		$lastFolderPathForIndex = FindLastFolderPathForIndex($typeName, $rootPath);	
		$isUpToDate = IsIndexUpToDate($lastFolderPathForIndex);
		
		$lastFolderPathForCommon = FindLastFolderPathForCommon($typeName, $rootPath);
		include "$lastFolderPathForCommon";
		
		//View
		EngineFolder($isUpToDate, $lastFolderPathForIndex);
		EchoUpToDateStatement($isUpToDate);
	}
?>

<?php
	OnStart();
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
