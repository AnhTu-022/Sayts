<?php

class Connection
{
	private static $connection;

	public static function getConnection()
	{
		return Connection::$connection;
	}
	
	public static function setConnection($con)
	{
		Connection::$connection = $con;
	}
}

?>
