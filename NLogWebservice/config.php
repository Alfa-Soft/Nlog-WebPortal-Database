<?php

$g_DbHost = 'localhost';
$g_DbName = 'TBD';
$g_DbUser = 'TBD';
$g_DbPass = 'TBD';
$g_SrvcPass = "TBD";


$g_DbCmdCreateLogTable = "CREATE TABLE `%TABLENAME%` (  ".
"	`Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, ".
"	`DeviceReportedTime` DATETIME NULL DEFAULT NULL, ".
"	`FromHost` VARCHAR(60) NULL DEFAULT NULL, ".
"	`ProcessID` VARCHAR(60) NOT NULL DEFAULT '', ".
"	`SystemID` INT(11) NULL DEFAULT '0', ".
"	`SysLogTag` TEXT NULL, ".
"	`Message` TEXT NULL, ".
"	`InfoUnitID` INT(11) NULL DEFAULT '0', ".
"	`Facility` INT(6) NULL DEFAULT NULL, ".
"	`Priority` INT(11) NOT NULL DEFAULT '0', ".
"	`EventId` INT(11) NULL DEFAULT '0', ".
"	`EventCategory` INT(11) NULL DEFAULT NULL, ".
"	`EventlogType` TEXT NULL, ".
"	`EventSource` TEXT NULL, ".
"	`EventUser` VARCHAR(60) NULL DEFAULT NULL, ".
"	`Checksum` VARCHAR(60) NULL DEFAULT NULL, ".
"	PRIMARY KEY (`Id`) ".
") ".
"COLLATE='utf8_general_ci' ".
"ENGINE=InnoDB; ";



$g_DbCmdAddSourcesEntry = "INSERT INTO `logcon_sources` ".
"(Name, Description, SourceType, MsgParserList, MsgNormalize, MsgSkipUnparseable, ViewID, LogLineType, DiskFile, DBTableType, DBType, DBServer, DBName, DBUser, DBPassword, DBTableName, DBEnableRowCounting, DBRecordsPerQuery, defaultfilter, userid, groupid) " .
"VALUES " .
"(?,       '',       2,          '',            0,            0,                 'SYSLOG', NULL,       NULL,     'monitorware', 0,    ?,        ?,      ?,      ?,          ?,         0,                   100,                '',           NULL,    NULL)";
	
	

?>