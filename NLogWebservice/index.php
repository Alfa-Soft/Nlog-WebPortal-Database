<?php

try 
{
	require("config.php");
	require("utils.php");
	
	
	//Parse Arguments
	$token          = getQueryParam("Token");	
	$nlogProject    = getQueryParam("Project");
	$nlogAppName    = getQueryParam("AppName");
	$nlogException  = getQueryParam("Exception");
	$nlogLevel      = getQueryParam("Level");
	$nlogMessage    = getQueryParam("Message");
	$nlogStackTrace = getQueryParam("StackTrace");
	$nlogTimeStamp  = getQueryParam("TimeStamp");	
	$IPsender 		= netGetIpAddress();
	
	//Validate Input
	if ($token != $g_SrvcPass)
	    exitWithError("authentication error seckey: %s", $token);
				
	if( IsNullOrEmptyString($nlogMessage) )
		exitWithError("nlog message is empty");
	
	if(!checkDateTime($nlogTimeStamp))
		exitWithError("Invalid date format: " . $nlogTimeStamp);
	
	
	//Convert nLog to MonitorWare (reuse existing table fields)
	$mwReportTime = $nlogTimeStamp;
	$mwFromHost   = $IPsender;
	$mwProcessID  = $nlogAppName;
	$mwMessage    = $nlogMessage;
	$mwELSource   = $nlogStackTrace;
	$mwELType     = $nlogException;
	$mWSeverity   = parseSeverity($nlogLevel);
	$mwTableName  = parseTableName($nlogProject);

	//WriteLog( $mwReportTime . "_" . $mwFromHost . "_" . $mwProcessID . "_" . $mwMessage . "_" . $mwELSource . "_" . $mwELType  . "_" . $mWSeverity . "_" .  $mwTableName );
	
	//Connect database	
	$mysqli = new mysqli($g_DbHost, $g_DbUser, $g_DbPass, $g_DbName);
	if ($mysqli->connect_errno)
		exitWithError("Failed to connect to MySQL: " . $mysqli->connect_error);
	
		
	//Create project specific log table if not exits
	$r = appCheckLogTable($mysqli, $mwTableName);
	if($r->Error){
		$mysqli->Close();
		exitWithError($r->Msg);
	}
	
	
	//Configure LogAnalyzser if new project
	$r = appCheckLogAnalzerConfig($mysqli, $nlogProject, $mwTableName);
	if($r->Error){
		$mysqli->Close();
		exitWithError($r->Msg);
	}
		
	 //Add log entry
	$r = appAddLogEntry($mysqli, $mwTableName, $mwReportTime, $mwFromHost, $mwMessage, $mwELSource, $mwELType, $mwProcessID, $mWSeverity);
	if($r->Error){
		$mysqli->Close();
		exitWithError($r->Msg);
	}
	
	
	$mysqli->Close();
	$mysqli = null;  
	echo "ok";

} 
catch (Exception $e) 
{
	exitWithError($e->getMessage());
}	

?>



