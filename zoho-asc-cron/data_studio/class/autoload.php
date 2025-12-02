<?php
// Autoload to include classes on runtime
function rs_autoload_all_classes($className)
{
	$className=strtolower($className);
	$fileName=$className.".class.php";
	$filePath=__DIR__."/".$fileName;

	if(file_exists($filePath))
	{
		require_once $filePath;
	}
}

spl_autoload_register('rs_autoload_all_classes');
?>