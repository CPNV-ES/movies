<?php
require_once(ROOT_PATH."php/configs/configs.php");

/* Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getTokenChecking($db){
    $query  = 'SELECT tokens.`path` FROM tokens';

    $req = $db->prepare($query);

    if (!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if ($req->rowCount() >= 1){
        $result = $req->fetchAll();
        return $result;
    }

    return "";
}

/* Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function setToken($db, $path, $step){
	$query  = 'INSERT INTO tokens ';
	$query .= '(`Path`, `Step`) VALUE (?, ?)';

    $req = $db->prepare($query);
	$req->bindParam(1, $path);
	$req->bindParam(2, $step);

    if (!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

	if ($req->rowCount() == 1){
        $result = $db->lastInsertId();
        return $result;
    }

    return false;
}

/* Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function removeToken($db, $id_token){
	$query  = 'DELETE FROM tokens ';
	$query .= 'WHERE tokens.`idTokens` = ?';

    $req = $db->prepare($query);
	$req->bindParam(1, $id_token);

    if (!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

	if ($req->rowCount() == 1){
        return true;
    }

    return false;
}

/* Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function updateToken($db, $id_token, $step){
	$query  = 'UPDATE tokens SET tokens.`Step` = ? ';
	$query .= 'WHERE tokens.`idTokens` = ?';

    $req = $db->prepare($query);
	$req->bindParam(1, $step);
	$req->bindParam(2, $id_token);

    if (!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

	if ($req->rowCount() == 1){
        return true;
    }

    return false;
}

/* Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getStatus($db){
	$query  = 'SELECT tokens.`Step` FROM tokens ';
	$query .= 'LIMIT 1';

    $req = $db->prepare($query);

    if (!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

	if ($req->rowCount() == 1){
        $result = $req->fetch(PDO::FETCH_LAZY);
        return $result['Step'];
    }

    return false;
}
