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

<?php
function OnStart()
{
	$rootPath = GetRootPath();
	$commonPath = "$rootPath\items\common\common.php";
	include "$commonPath";

	$typeName = "folder";
	$lastFolderIndexPath = FindLastFolderIndexPath($typeName, $rootPath);
	$isUpToDate = IsIndexUpToDate($lastFolderIndexPath);

	EchoUpToDateStatement($isUpToDate);
	UpdateIndexIfNotUpToDate($isUpToDate, $lastFolderIndexPath);
}

function GetRootPath()
{
	$cwd = getcwd();
	$pubString = "public_html";
	$twoPathParts = explode($pubString, $cwd);
	$rootPath = "$twoPathParts[0]$pubString";
	return $rootPath;
}

?>
