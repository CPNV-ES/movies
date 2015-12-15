<?php
require_once("/php/configs/config.php");

/* Function connectDB
	Retourne l'object PDO connecter à la base de donnée
	Param :
		- Pris selon les configurations !
	Return : En succes = object PDO, Echéc = False	*/
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
