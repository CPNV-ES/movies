<?php

/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getAllFilm($db){
	$query  = 'SELECT movies.`idMovies`, movies.`Title` FROM movies';

    $req = $db->prepare($query);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
        return $result;
    }
    return false;
}

/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getFilmByTitle($db, $search_string){
	$query  = 'SELECT movies.`idMovies`, movies.`Title` FROM movies ';
    $query .= 'WHERE movies.`Title` LIKE ?';

    $req = $db->prepare($query);
	$search_string = '%'.$search_string.'%';
    $req->bindParam(1, $search_string);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
		var_dump($result);
        return $result;
    }

    return false;
}

/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getGenres($db, $id){
	$query  = 'SELECT genres.`Name` FROM movies ';
    $query .= 'INNER JOIN genres_movies ON genres_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN genres ON genres.`idGenres` = genres_movies.`fkGenres` ';
	$query .= 'WHERE movies.`idMovies` = ?';

    $req = $db->prepare($query);
    $req->bindParam(1, $id);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
        return $result;
    }

    return false;
}

/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getCountries($db, $id){
	$query  = 'SELECT countries.`Name` FROM movies ';
    $query .= 'INNER JOIN countries_movies ON countries_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN countries ON countries.`idCountries` = countries_movies.`fkCountries` ';
	$query .= 'WHERE movies.`idMovies` = ?';

    $req = $db->prepare($query);
    $req->bindParam(1, $id);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
        return $result;
    }

    return false;
}

/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getPeople($db, $id, $type){
	$query  = 'SELECT people.`FirstName`,people.`LastName` FROM movies ';
    $query .= 'INNER JOIN people_movies ON people_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN types_role ON types_role.`idTypes_role` = people_movies.`fkTypes_Role` ';
	$query .= 'INNER JOIN people ON people.`idPeople` = people_movies.`fkPeople` ';
	$query .= 'WHERE movies.`idMovies` = ? ';
	$query .= 'AND types_role.`type` LIKE ?';

    $req = $db->prepare($query);
    $req->bindParam(1, $id);
	$req->bindParam(2, $type);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
        return $result;
    }

    return false;
}


/* //TODO UDPATE Function getSourceId
	Recupére l'id en base de donnée de la source demandé
	Param :
		- db : lien vers la base de donnée (PDO object)
		- path : chemin source demandé
	Return : En succes = l'id de la source, Echéc = False	*/
function getInfoFilm($db, $attr = array()){
	$query  = 'SELECT * FROM movies ';
	$first = true;
	foreach($attr as $key => $value){
		$sign = $value[1];
		if($first){
			$first = false;
			$query .= "WHERE movies.$key $sign ${value[0]} ";
		}
		$query .= "AND movies.$key $sign ${value[0]} ";
	}

    $req = $db->prepare($query);

    if(!$req->execute()){
        $error = $req->errorCode();
        $error = "Erreur est survenu lors de l'execution de la requête ('$error')";
		//echo $error;
        return false;
    }

    if($req->rowCount() >= 1){
        $result = $req->fetchAll();
    }

	for($i = 0, $size = count($result); $i < $size; $i++){
		$id = $result[$i]["idMovies"];

		$result[$i]["genres"] = getGenres($db, $id);
		$result[$i]["countries"] = getCountries($db, $id);
		$result[$i]["writer"] = getPeople($db, $id, DB_WRITER_TYPE);
		$result[$i]["director"] = getPeople($db, $id, DB_DIRECTOR_TYPE);
		$result[$i]["actor"] = getPeople($db, $id, DB_ACTOR_TYPE);
		$result[$i]["producer"] = getPeople($db, $id, DB_PRODUCER_TYPE);
	}
	var_dump($result);

    return false;
}
