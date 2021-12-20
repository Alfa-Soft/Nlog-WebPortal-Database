<?php
//include($gl_root_path . 'include/functions_db.php');

function RemoveEventLogItems( $tableName, $ids )
{
	$query = "Delete from " . $tableName . " where id in(" . implode(',', $ids) . ")";
	$result = DB_Query($query);
	DB_FreeQuery($result);	
}

?>