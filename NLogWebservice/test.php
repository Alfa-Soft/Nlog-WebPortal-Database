<?php 



$date = "2020-12-29 21:14:40";

if (DateTime::createFromFormat('Y-m-d H:i:s', $date) !== FALSE) 
{
  echo"date ok";
}


    $timestamp = strtotime($date);
    $date_formated = date('Y-m-d H:i:s', $timestamp);

	echo $date_formated;
	

?>