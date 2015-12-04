<?php

function connectDB()
{
	try
	{
		$pdo = new PDO('mysql:host='.DB_ADDRESS.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
	}
	catch (Exception $e)
	{
		exit('Unable to connect at the database : ' . $e->getMessage());
	}

	return $pdo;
}
