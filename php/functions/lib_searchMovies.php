<?php

/* Function getAllFilm
	Return id and title of all film found in the db
	Param :
		- db : connector PDO of the db
	Return : Success = array of film, Echec = False	*/
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

/* Function getFilmByTitle
	Return all movies when the string match on the title
	Param :
		- db : connector PDO of the db
		- search_string : string for matching on title
	Return : Success = array of movies found, Echec = False	*/
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

/* Function getGenres
	Return all Genres of a movies
	Param :
		- db : connector PDO of the db
		- id : id of Movies in DB
	Return : Success = array of Genres, Echec = False	*/
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

/* Function getCountries
	return all countries of a movies
	Param :
		- db : connector PDO of the db
		- id : id of Movies in DB
	Return : Success = array of Genres, Echec = False	*/
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

/* Function getPeople
	return all people with precised role of a movies
	Param :
		- db : connector PDO of the db
		- id : id of Movies in DB
		- type : role of people
	Return : Success = array of People, Echec = False	*/
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

/* Function getStudios
	return all studios of a movie
	Param :
		- db : connector PDO of the db
		- id : id of Movies in DB
	Return : Success = array of Studios, Echec = False	*/
function getStudios($db, $id){
	$query  = 'SELECT people.`FirstName`,people.`LastName` FROM movies ';
    $query .= 'INNER JOIN studios_movies ON studios_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN studios ON studios.`idStudios` = studios_movies.`fkStudios` ';
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


/* Function getInfoFilm
	return all movies with all information. It's possible to precised an attribute of the movie
	Param :
		- db : connector PDO of the db
		- attr : array of attribute /!\Special syntax !!!  Look example /!\
	Return : Success = array of Movies, Echec = False

	Example of attributes
	- field is the field in database
	- value : is the value for the test ('%r%' or '1', ...)
	- sign : is the sign for the comparation for permit compart string with special test % or _ ( '=' or 'LIKE', ...)
	$attr = array(
			'field' => array(
				'value', 'sign'
			)
		);
	*/
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
		$result[$i]["studios"] = getStudios($db, $id);
	}

	return $result;
}
