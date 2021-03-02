<?php 

	require("config.php");
    require("utils.php");
	
	
	//check config	
	$configErr = false;
	if( IsNullOrEmptyString($g_DbHost) ) {
		echo "Parameter $g_DbHost is empty. Check file config.php";
		$configErr = true;
	}
	
	if( IsNullOrEmptyString($g_DbUser) ) {
		echo "Parameter $g_DbUser is empty. Check file config.php";
		$configErr = true;
	}
	
	if( IsNullOrEmptyString($g_DbPass) ) {
		echo "Parameter $g_DbPass is empty. Check file config.php";
		$configErr = true;
	}
	
	if( IsNullOrEmptyString($g_DbName) ) {
		echo "Parameter $g_DbName is empty. Check file config.php";
		$configErr = true;
	}
	
	if($configErr)
		return;
	
	
	
	
	
	//check database access
	$DbConErr = false;
	$mysqli = new mysqli($g_DbHost, $g_DbUser, $g_DbPass, $g_DbName);
	$DbConErr = false;
	if ($mysqli->connect_errno){
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
		$DbConErr = true;
	}
	
	if($DbConErr)
		return;
	

	
	//check default log table
	$queryCheckTable  = "SHOW TABLES LIKE 'logs'";
	$resultCheckTable = $mysqli->query($queryCheckTable);

	if(!$resultCheckTable || $resultCheckTable->num_rows == 0)
	{
		$queryAddTable  = str_replace("%TABLENAME%", "logs", $g_DbCmdCreateLogTable);		
	    $resultAddTable = $mysqli->query($queryAddTable);
		
		if (!$resultAddTable)
		{
			echo sprintf("Failed to add new table: %s (%s)", $mwTableName, $mysqli->error);
			return;
		}
		else
		{
			echo "Ok. Database prepared"; 
		}
	}
	else
	{
		echo "Ok. Database already prepared"; 
	}
	
?>