<?php
/**
*    @Author     : Alain Pichonnat | alain.pichonnat@cpnv.ch
*    Date        : 17.12.2015
*    Description : function crud for th data base

*/
require_once(ROOT_PATH."php/configs/configs.php");

/**
 *  checkMovie
 *
 *  Use : check if movie exist in db
 *
 * 	@param array        $FullInfo   Contains all info movie
 *  @param object       $pdo        object connection with db
 *
 * 	@return boolean     false  if not found
 */
function checkMovie($FullInfo, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `movies` WHERE Title = ?');
    $req->bindParam(1, $FullInfo["original_title"]);
    $req->execute();
    $result = $req->fetchAll();

    if ($req->rowCount() > 0)
    {
        return true;
    }
    return false;
}

/**
 *  getIdMovie
 *
 *  Use : check if movie exist in db
 *
 * 	@param string       $title      Contains title movie
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id movie in db
 */
function getIdMovie($title, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `movies` WHERE Title = ?');
    $req->bindParam(1, $title);
    $req->execute();
    $result = $req->fetchAll();

    if ($req->rowCount() > 0)
    {
        return $result[0]["idMovies"];
    }
    return;
}

/**
 *  inserMovies
 *
 *  Use : insert data movie in db
 *
 * 	@param array        $movie      Contains title movie
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id movie in db
 */
function inserMovies($movie, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `movies` (Title, Year, Length, Description, Poster) VALUE (?, ?, ?, ?, ?)');
    $req->bindParam(1, $movie["original_title"]);
    $req->bindParam(2, $movie["release_date"]);
    $req->bindParam(3, $movie["runtime"]);
    $req->bindParam(4, $movie["tagline"]);
    $req->bindParam(5, $movie["poster_path"]);
    $req->execute();

    return($pdo->lastInsertId());
}

/**
 *  getidGenre
 *
 *  Use : get id genre
 *
 * 	@param string        $genre      Contains genre of movies
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id genre in db,
 *  @return boulean     false if not found
 */
function getidGenre($genre, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `genres` WHERE Name = ?');
    $req->bindParam(1, $genre);
    $req->execute();
    $result = $req->fetchAll();

    if ($req->rowCount() > 0)
    {
        return $result[0]["idGenres"];
    }
    return false;
}

/**
 *  insertGenre
 *
 *  Use : insert genre in db
 *
 * 	@param string        $genre      Contains genre of movies
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         last insert id of the genre
 */
function insertGenre($genre, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `genres` (Name) VALUES (?)');
    $req->bindParam(1, $genre);
    $req->execute();
    return($pdo->lastInsertId());
}

/**
 *  insert_genres_Movie
 *
 *  Use : link idgenre and idmovie in db
 *
 * 	@param int        $id_genres      Contains id genre
 * 	@param int        $id_movie_db    Contains id movie
 *  @param object     $pdo            Object connection with db
 *
 */
function insert_genres_Movie($id_genres, $id_movie_db, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `genres_movies` (fkGenres, fkMovies) VALUES (?, ?)');
    $req->bindParam(1, $id_genres);
    $req->bindParam(2, $id_movie_db);
    $req->execute();
    return;
}

/**
 *  Update_files
 *
 *  Use : update fkmovie in db for link the path and the movie
 *
 * 	@param int          $id_movie   Contains id movie
 * 	@param int          $id_files   Contains id files in db
 *  @param object       $pdo        object connection with db
 *
 */
function Update_files($id_movie, $id_files, $pdo)
{
    $req = $pdo->prepare('UPDATE `files` SET fkMovies = ? WHERE idFiles = ?');
    $req->bindParam(1, $id_movie);
    $req->bindParam(2, $id_files);
    $req->execute();
    return;
}

/**
 *  getidCountries
 *
 *  Use : get id countries
 *
 * 	@param string       $genre      Contains genre of movies
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id countrie in db,
 *  @return boulean     false if not found
 */
function getidCountries($countrie, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `countries` WHERE Name = ?');
    $req->bindParam(1, $countrie);
    $req->execute();
    $result = $req->fetchAll();

    if ($req->rowCount() > 0)
    {
        return $result[0]["idCountries"];
    }
    return false;

}

/**
 *  insert_Countries
 *
 *  Use : insert countries in db
 *
 * 	@param string       $countrie   Contains countrie of movie
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         last insert id of the genre
 */
function insert_Countries($countrie, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `countries` (Name) VALUES (?)');
    $req->bindParam(1, $countrie);
    $req->execute();
    return($pdo->lastInsertId());
}

/**
 *  insert_countrie_movie
 *
 *  Use : link id countries and id movies in db
 *
 * 	@param int        $id_countrie    Contains id countries
 * 	@param int        $id_movie_db    Contains id movie
 *  @param object     $pdo            Object connection with db
 *
 */
function insert_countrie_movie($id_countrie, $id_movie_db, $pdo)//fonction ok
{
    $req = $pdo->prepare('INSERT INTO `countries_movies` (fkCountries, fkMovies) VALUES (?, ?)');
    $req->bindParam(1, $id_countrie);
    $req->bindParam(2, $id_movie_db);
    $req->execute();
    return;
}

/**
 *  getrollebyid
 *
 *  Use: get id_type_rolle by name
 *
 * 	@param string       $role       Contains role
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id typeRole in db
 */
function getrollebyid($role, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `types_role` WHERE type = ?');
    $req->bindParam(1, $role);
    $req->execute();
    $result = $req->fetchAll();
    return $result[0]["idTypes_role"];
}

/**
 *  getidPeople
 *
 *  Use: get id people
 *
 * 	@param string       $people     Contains genre of movies
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id people in db,
 *  @return boulean     false if not found
 */
function getidPeople($people, $pdo)
{
    $data = explode(" ", $people);

    $req = $pdo->prepare('SELECT * FROM `people` WHERE FirstName = ? AND LastName = ?');
    $req->bindParam(1, $data[0]);
    $lastname = @$data[1].@$data[2];
    $req->bindParam(2, $lastname);
    $req->execute();
    $result = $req->fetchAll();
    if ($req->rowCount() > 0)
    {
        return $result[0]["idPeople"];
    }
    return false;
}

/**
 *  insert_people
 *
 *  Use : insert people in db
 *
 * 	@param string       $people     Contains name people
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         last insert id of the people
 */
function insert_people($people, $pdo)
{
    $data = explode(" ", $people);
    $req = $pdo->prepare('INSERT INTO `people` (FirstName, LastName) VALUES (?, ?)');
    $req->bindParam(1, $data[0]);
    $lastname = @$data[1].@$data[2];
    $req->bindParam(2, $lastname);
    $req->execute();
    return($pdo->lastInsertId());
}

/**
 *  insert_people_role_movies
 *
 *  Use : link id people and id movies and id role in db
 *
 * 	@param int        $idPeople       Contains id people
 * 	@param int        $id_role        Contains id role
 * 	@param int        $id_movie_db    Contains id movie
 *  @param object     $pdo            Object connection with db
 *
 */
function insert_people_role_movies($idPeople, $id_role, $id_movie_db, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `people_movies` (fkPeople, fkTypes_Role, fkMovies) VALUES (?, ?, ?)');
    $req->bindParam(1, $idPeople);
    $req->bindParam(2, $id_role);
    $req->bindParam(3, $id_movie_db);
    $req->execute();
    return;
}

/**
 *  getidstudios
 *
 *  Use: get id studios
 *
 * 	@param string       $studios    Contains name studio
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         id studio
 *  @return boulean     false if not found
 */
function getidstudios($studios, $pdo)
{
    $req = $pdo->prepare('SELECT * FROM `studios` WHERE Name = ?');
    $req->bindParam(1, $studios);
    $req->execute();
    $result = $req->fetchAll();

    if ($req->rowCount() > 0)
    {
        return $result[0]["idStudios"];
    }
    return false;

}

/**
 *  Insert_studios
 *
 *  Use : insert studios name in db
 *
 * 	@param string       $studios    Contains studio of movie
 *  @param object       $pdo        object connection with db
 *
 * 	@return int         last insert id of the studio
 */
function Insert_studios($studios, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `studios` (Name) VALUES (?)');
    $req->bindParam(1, $studios);
    $req->execute();
    return($pdo->lastInsertId());
}

/**
 *  insert_studios_Movie
 *
 *  Use : link id studio and id movies in db
 *
 * 	@param int        $idStudios      Contains id studio
 * 	@param int        $id_movie_db    Contains id movie
 *  @param object     $pdo            Object connection with db
 *
 */
function insert_studios_Movie($idStudios, $id_movie_db, $pdo)
{
    $req = $pdo->prepare('INSERT INTO `studios_movies` (fkStudios, fkMovies) VALUES (?, ?)');
    $req->bindParam(1, $idStudios);
    $req->bindParam(2, $id_movie_db);
    $req->execute();
    return;
}
 ?>
