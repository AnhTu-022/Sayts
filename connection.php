<?php

class Connection
{
	private $connection;

	public static function getConnection()
	{
		return $connection;
	}
	
	public static function setConnection($con)
	{
		$connection = $con;
	}
}

?>
