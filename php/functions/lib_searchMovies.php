<?php
require_once(ROOT_PATH."php/configs/configs.php");

/* Function getMovies
	Return all movies when the string match on the title
	Param :
		- db : connector PDO of the db
		- search_string : string for matching on title, if it's homited, the function get all movies
	Return : Success = array of movies found, Echec = False	*/
function getMovies($db, $search_string = null){
	$query  = 'SELECT movies.`idMovies`, movies.`Title`, files.`idFiles` FROM movies ';
	$query .= 'INNER JOIN files ON files.`fkMovies` = movies.`idMovies` ';

	if($search_string !== null){
    	$query .= 'WHERE movies.`Title` LIKE ? ';
	}

	$query .= 'GROUP BY movies.`idMovies`';

	$req = $db->prepare($query);
	if($search_string !== null){
		$search_string = '%'.$search_string.'%';
		$req->bindParam(1, $search_string);
	}

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

/* Function getGenres
	Return all Genres of a movies
	Param :
		- db : connector PDO of the db
		- id : id of Movies in DB
	Return : Success = array of Genres, Echec = False	*/
function getGenres($db, $id, $filter = false){
	$query  = 'SELECT genres.`Name` FROM movies ';
	$query .= 'INNER JOIN genres_movies ON genres_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN genres ON genres.`idGenres` = genres_movies.`fkGenres` ';
	$query .= 'WHERE movies.`idMovies` = ? ';
	if($filter !== false){
		$query .= 'AND genres.`Name` LIKE ?';
	}

    $req = $db->prepare($query);
    $req->bindParam(1, $id);

	if($filter !== false){
		$filter = '%'.$filter.'%';
		$req->bindParam(2, $filter);
	}

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
function getCountries($db, $id, $filter = false){
	$query  = 'SELECT countries.`Name` FROM movies ';
	$query .= 'INNER JOIN countries_movies ON countries_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN countries ON countries.`idCountries` = countries_movies.`fkCountries` ';
	$query .= 'WHERE movies.`idMovies` = ? ';
	if($filter !== false){
		$query .= 'AND countries.`Name` LIKE ?';
	}

    $req = $db->prepare($query);
    $req->bindParam(1, $id);

	if($filter !== false){
		$filter = '%'.$filter.'%';
		$req->bindParam(2, $filter);
	}

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
function getPeople($db, $id, $type, $filter = false){
	$query  = 'SELECT concat(people.`FirstName`, " ", people.`LastName`) AS FullName FROM movies ';
	$query .= 'INNER JOIN people_movies ON people_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN types_role ON types_role.`idTypes_role` = people_movies.`fkTypes_Role` ';
	$query .= 'INNER JOIN people ON people.`idPeople` = people_movies.`fkPeople` ';
	$query .= 'WHERE movies.`idMovies` = ? ';
	$query .= 'AND types_role.`type` LIKE ? ';
	if($filter !== false){
		$query .= 'GROUP BY people.`idPeople` ';
		$query .= 'HAVING  fullName LIKE ?';
	}

    $req = $db->prepare($query);
    $req->bindParam(1, $id);
	$req->bindParam(2, $type);

	if($filter !== false){
		$req->bindParam(3, $filter);
	}

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
function getStudios($db, $id, $filter = false){
	$query  = 'SELECT studios.`idStudios`, studios.`Name` FROM movies ';
	$query .= 'INNER JOIN studios_movies ON studios_movies.`fkMovies` = movies.`idMovies` ';
	$query .= 'INNER JOIN studios ON studios.`idStudios` = studios_movies.`fkStudios` ';
	$query .= 'WHERE movies.`idMovies` = ?';
	if($filter !== false){
		$query .= 'AND countries.`Name` LIKE ?';
	}

    $req = $db->prepare($query);
    $req->bindParam(1, $id);

	if($filter !== false){
		$filter = '%'.$filter.'%';
		$req->bindParam(2, $filter);
	}

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
		);
	*/
function getInfoMovies($db, $attr = array(), $filter = array()){
	//recure filter array
	$secur = array(	'genres' => false, 'studios'=> false,
					'countries' => false, 'actor' => false,
					'writer' => false, 'producer' => false, 'director' => false);
	$filter = array_merge($secur, $filter);

	$query  = 'SELECT * FROM movies ';
	$query .= 'INNER JOIN files ON files.`fkMovies` = movies.`idMovies` ';

	$first = true;
	foreach($attr as $key => $value){
		$sign = $value[1];
		if($first){
			$first = false;
			$query .= "WHERE movies.$key $sign '${value[0]}' ";
		}
		else{
			$query .= "AND movies.$key $sign '${value[0]}' ";
		}
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
	else{
		return false;
	}

	for($i = 0, $size = count($result); $i < $size; $i++){
		$id = $result[$i]["idMovies"];

		if(($result[$i]["genres"] = getGenres($db, $id, $filter['genres'])) === false && $filter['genres'] !== false){
			unset($result[$i]);
			continue;
		}

		if(($result[$i]["countries"] = getCountries($db, $id, $filter['countries'])) === false && $filter['countries'] !== false){
			unset($result[$i]);
			continue;
		}

		if(($result[$i]["writer"] = getPeople($db, $id, DB_WRITER_TYPE, $filter['writer'])) === false && $filter['writer'] !== false){
			unset($result[$i]);
			continue;
		}

		if(($result[$i]["director"] = getPeople($db, $id, DB_DIRECTOR_TYPE, $filter['director'])) === false && $filter['director'] !== false){
			unset($result[$i]);
			continue;
		}
		if(($result[$i]["actor"] = getPeople($db, $id, DB_ACTOR_TYPE, $filter['actor'])) === false && $filter['actor'] !== false){
			unset($result[$i]);
			continue;
		}

		if(($result[$i]["producer"] = getPeople($db, $id, DB_PRODUCER_TYPE, $filter['producer'])) === false && $filter['producer'] !== false){
			unset($result[$i]);
			continue;
		}

		if(($result[$i]["studios"] = getStudios($db, $id, $filter['studios'])) === false && $filter['studios'] !== false){
			unset($result[$i]);
			continue;
		}
	}

	return $result;
}
