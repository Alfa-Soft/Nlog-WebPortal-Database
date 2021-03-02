<?php

	
class FuncResult {
    public $Error;
    public $Msg;
}

function getQueryParam($argName) 
{	
	if (isset($_POST[$argName]))
		return $_POST[$argName];
	
	if (isset($_GET[$argName]))
		return $_GET[$argName];
	
	return "";
}

function checkDateTime($date) 
{
    return (DateTime::createFromFormat('Y-m-d H:i:s', $date) !== FALSE);
}

function parseMessage($msg, $stackTrace, $exception)
{
	return $msg . "\r\n" . $exception . "\r\n" . $stackTrace;
}

function parseTableName($project)
{
	if(! isset($project)) 
		return "logs";
	else
	    return "logs_" . $project;
}

function parseSeverity($nlogLogLevel)
{	
/* 
   Syslog-nl                                                  NLog
   Emergency = 0      system is unusable                     ----
   Alert = 1          action must be taken immediately       ----
   Critical = 2       critical conditions                    Fatal 	Something bad happened; application is going down
   Error = 3          error conditions                       Error 	Something failed; application may or may not continue
   Warning = 4        warning conditions                     Warn 	Something unexpected; application will continue
   Notice = 5         normal but significant condition       Trace 	For trace debugging; begin method X, end method X
   Informational = 6  informational messages                 Info 	Normal behavior like mail sent, user updated profile etc.
   Debug = 7          debug-level messages                   Debug 	For debugging; executed query, user authenticated, session expired
*/

	if(!isset($nlogLogLevel))
		return 4;

	
	$loglevel = strtoupper($nlogLogLevel);
	
	if( $loglevel == "FATAL")   return 2;	
	if( $loglevel == "ERROR")   return 3;
	if( $loglevel == "WARN")    return 4;
	if( $loglevel == "TRACE")   return 5;
	if( $loglevel == "INFO")    return 6;
	if( $loglevel == "DEBUG" )  return 7;
	
		
	return 4;
	
}

function exitWithError($msg)	
{		
	//add entry to default table 'logs'
	global $g_DbHost, $g_DbUser, $g_DbPass, $g_DbName;
	$mysql = new mysqli($g_DbHost, $g_DbUser, $g_DbPass, $g_DbName);
	$query = sprintf("INSERT INTO logs (DeviceReportedTime, FromHost, Message, syslogtag, ProcessID, Priority) VALUES ('%s','localhost','%s','Webservice Error','LogAnalyzerWebservice',1)", date('Y-m-d H:i:s'), $msg); //without bindPara due safe parameters
	$mysql->query($query);
	
	
	//add file log	
	WriteLog($msg);
	error_log($msg);
	echo "error:" . $msg;
	exit;
}

//Create project specific log table if not exits
function appCheckLogTable($mysqli, $mwTableName)
{		
	global $g_DbCmdCreateLogTable;
	
	
	$r = new FuncResult();
	
	$queryCheckTable  = sprintf("SHOW TABLES LIKE '%s'", $mwTableName);
	$resultCheckTable = $mysqli->query($queryCheckTable);

	if(!$resultCheckTable || $resultCheckTable->num_rows == 0)
	{
		$queryAddTable  = str_replace("%TABLENAME%", $mwTableName, $g_DbCmdCreateLogTable);		
	    $resultAddTable = $mysqli->query($queryAddTable);
		
		if (!$resultAddTable)
		{
			$r->Msg   = sprintf("Failed to add new table: %s (%s)", $mwTableName, $mysqli->error);
			$r->Error = true;
		}
	}
	else
	{
		$r->Msg = sprintf("Table %s already exists", $mwTableName); 
	}
	
	return $r;
}

//Configure LogAnalyzser if new project
function appCheckLogAnalzerConfig($mysqli, $ProjectName, $ProjectTableName)
{			
	global $g_DbCmdAddSourcesEntry, $g_DbHost, $g_DbName, $g_DbUser, $g_DbPass;

	$r = new FuncResult();
	

	//check entry exists in LogAnalyzser.Sources
	$stmt = $mysqli->prepare("select Id from logcon_sources where DBTableName = ?");
	if (!$stmt) 
	{
		$r->Msg   = sprintf("CheckLogAnalzerConfig. Prepare mysql query failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}
	
	
	$bnd = $stmt->bind_param('s', $ProjectTableName);
	if (!$bnd) 
	{
		$r->Msg   = sprintf("CheckLogAnalzerConfig. Binding parameters failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}	
	
	$stmt->execute();
	
	
	$rowCnt = 0;
    $stmt->bind_result($rowCnt);
    $stmt->fetch();
	$stmt->close();
	
	if($rowCnt > 0)
		return $r;
	
	
	//add entry to LogAnalyzser.Sources	
	$stmt = $mysqli->prepare($g_DbCmdAddSourcesEntry);
	if (!$stmt)
	{
		$r->Msg   = sprintf("AddLogAnalzerConfig. Prepare mysql query failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}

	$bnd = $stmt->bind_param('ssssss', $ProjectName, $g_DbHost, $g_DbName, $g_DbUser, $g_DbPass, $ProjectTableName);
	if (!$bnd) 
	{
		$r->Msg   = sprintf("AddLogAnalzerConfig. Binding parameters failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}		
	
	
	$stmt->execute();
	$stmt->close();
	
	
	
	//add first item
	$date = date('Y-m-d H:i:s');
	appAddLogEntry($mysqli, $ProjectTableName, $date, "LogAnalyzerWebservice", "Database created", "", "", "appCheckLogAnalzerConfig", 0);
	
	return $r;
}

function appAddLogEntry($mysqli, $mwTableName, $mwReportTime, $mwFromHost, $mwMessage, $mwELSource, $mwELType, $mwProcessID, $mWSeverity)
{	
	$r = new FuncResult();
	
	$stmt = $mysqli->prepare("INSERT INTO " . $mwTableName . " (DeviceReportedTime, FromHost, Message, EventSource, EventlogType, ProcessID, Priority) VALUES (?,?,?,?,?,?,?)");
	if(!$stmt)
	{
		$r->Msg   = sprintf("Prepare mysql query failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}
	
	
	$bnd = $stmt->bind_param('ssssssi', $mwReportTime, $mwFromHost, $mwMessage, $mwELSource, $mwELType, $mwProcessID, $mWSeverity);
	if(!$bnd) 
	{
		$r->Msg   = sprintf("appAddLogEntry::Binding parameters failed: (%s) %s", $mysqli->errno, $mysqli->error);
		$r->Error = true;
		return $r;
	}		
	
	$stmt->execute();
    $stmt->close();
	$stmt   = null;
	
	return $r;
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}

function netGetIpAddress() 
{    
	$clientIp = "";
	
	// check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && netValidateIP($_SERVER['HTTP_CLIENT_IP'])) {
        $clientIp = $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	{
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) 
			{
                if (netValidateIP($ip))
				{
                    $clientIp = $ip;
					break;
				}
            }
        } 
		else {
            if (netValidateIP($_SERVER['HTTP_X_FORWARDED_FOR']))
                $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
	
	//Some other header info
    else if (!empty($_SERVER['HTTP_X_FORWARDED']) && netValidateIP($_SERVER['HTTP_X_FORWARDED']))
        $clientIp =  $_SERVER['HTTP_X_FORWARDED'];
    else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && netValidateIP($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $clientIp =  $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && netValidateIP($_SERVER['HTTP_FORWARDED_FOR']))
        $clientIp =  $_SERVER['HTTP_FORWARDED_FOR'];
    else if (!empty($_SERVER['HTTP_FORWARDED']) && netValidateIP($_SERVER['HTTP_FORWARDED']))
        $clientIp =  $_SERVER['HTTP_FORWARDED'];

	// return unreliable ip since all else failed
	else    
		$clientIp =  $_SERVER['REMOTE_ADDR'];
	
	
	if($clientIp == "::1")
		$clientIp = "127.0.0.1";
	
	return $clientIp;
}


function netValidateIP($ip) 
{
    if (strtolower($ip) === 'unknown')
        return false;

    // generate ipv4 network address
    $ip = ip2long($ip);

    // if the ip is set and not equivalent to 255.255.255.255
    if ($ip !== false && $ip !== -1) {
        // make sure to get unsigned long representation of ip
        // due to discrepancies between 32 and 64 bit OSes and
        // signed numbers (ints default to signed in PHP)
        $ip = sprintf('%u', $ip);
        // do private network range checking
        if ($ip >= 0 && $ip <= 50331647) return false;
        if ($ip >= 167772160 && $ip <= 184549375) return false;
        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
        if ($ip >= 4294967040) return false;
    }
    return true;
}

function netValidateIP6($ip)
{
	if (!filter_var($ip, FILTER_netValidateIP, FILTER_FLAG_IPV6) === false) {
		return true;
	}
	else {
		return false;
	}
}



function WriteLog($msg)
{
	//$file = fopen('C:MyLog.txt', 'a');	
	//fwrite($file, $msg . "\r\n");
	//fclose($file);	
	//echo $msg;
}
?>