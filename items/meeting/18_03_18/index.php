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

		$typeName = "meeting";
		$lastFolderPathForIndex = FindLastFolderPathForIndex($typeName, $rootPath);
		$lastFolderPathForIndex = "$lastFolderPathForIndex\index.php";
		$isUpToDate = IsIndexUpToDate($lastFolderPathForIndex);
		
		$lastFolderPathForCommon = FindLastFolderPathForCommon($typeName, $rootPath);
		include "$lastFolderPathForCommon";
		
		//View
		UpdateIndexIfNotUpToDate($isUpToDate, $lastFolderPathForIndex);
		EchoUpToDateStatement($isUpToDate);
		CheckIfSaveBtnClicked($rootPath);
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
	<?php CheckPasswordAndPrintPageView() ?>
</body>
</html>
