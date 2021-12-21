<?php
//include($gl_root_path . 'include/functions_db.php');

function RemoveEventLogItemsByIDs( $tableName, $ids )
{
	$query = "Delete from " . $tableName . " where id in(" . implode(',', $ids) . ")";
	$result = DB_Query($query);
	DB_FreeQuery($result);	
}

function RemoveEventLogItemsByDate( $tableName, $dateObjBegin, $dateObjEnd )
{
	$dStart = $dateObjBegin->format("y-m-d");
	$dEnd   = $dateObjEnd->format("y-m-d");
	
	$query = "Delete from " . $tableName . " where DeviceReportedTime >= '" . $dStart . "' and DeviceReportedTime <= '" . $dEnd . "'";
	$result = DB_Query($query);
	DB_FreeQuery($result);
}

?>