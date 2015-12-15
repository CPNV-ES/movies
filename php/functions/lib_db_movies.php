<?php
    require_once("../configs/config.php");


    /**
     * check if movies exist in data base
     *
     * @param array     $FullInfo Contains info of movie
     * @param ObjectPdo $pdo      Contains object connection data base
     *
     * @return boulean return true if found in db or return false if not found
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
     * insert movies in db
     *
     * @param array     $movie    Contains full info of movie
     * @param ObjectPdo $pdo      Contains object connection data base
     *
     * @return int      last insert id of movie
    */
    function inserMovies($movie, $pdo)//fonction ok
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
    * gives the genre id
     *
     * @param string    $genre    Contains one genre
     * @param ObjectPdo $pdo      Contains object connection data base
     *
     * @return mixed    false if id not found, idGenre if found
    */
    function getidGenre($genre, $pdo)//fonction ok
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
     * insert the genre in data base
     *
     * @param string    $genre    Contains one genre
     * @param ObjectPdo $pdo      Contains object connection data base
     *
     * @return int    last insert id of genre
    */
    function insertGenre($genre, $pdo)//fonction ok
    {
        $req = $pdo->prepare('INSERT INTO `genres` (Name) VALUES (?)');
        $req->bindParam(1, $genre);
        $req->execute();
        return($pdo->lastInsertId());
    }

    /**
     * insert idGenre and idMovie
     *
     * @param int    $id_genres    Contains id genre of last insert
     * @param int    $id_movie_db  Contains id movie of last insert
     * @param ObjectPdo $pdo      Contains object connection data base
     *
     * @return 
    */
    function insert_genres_Movie($id_genres, $id_movie_db, $pdo)//fonction ok
    {
        $req = $pdo->prepare('INSERT INTO `genres_movies` (fkGenres, fkMovies) VALUES (?, ?)');
        $req->bindParam(1, $id_genres);
        $req->bindParam(2, $id_movie_db);
        $req->execute();
        return;
    }

    function Update_files($id_movie, $id_files, $pdo)// fonction ok
    {
        $req = $pdo->prepare('UPDATE `files` SET fkMovie = ? WHERE idFiles = ?');
        $req->bindParam(1, $id_movie);
        $req->bindParam(2, $id_files);
        $req->execute();

        return;
    }

    function getidCountries($countrie, $pdo)//fonction ok
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

    function insert_Countries($countrie, $pdo)//fonction ok
    {
        $req = $pdo->prepare('INSERT INTO `countries` (Name) VALUES (?)');
        $req->bindParam(1, $countrie);
        $req->execute();
        return($pdo->lastInsertId());
        echo "countrie";
    }

    function insert_countrie_movie($id_countrie, $id_movie_db, $pdo)//fonction ok
    {
        $req = $pdo->prepare('INSERT INTO `countries_movies` (fkCountries, fkMovies) VALUES (?, ?)');
        $req->bindParam(1, $id_countrie);
        $req->bindParam(2, $id_movie_db);
        $req->execute();
        return;
    }

    function getrollebyid($role, $pdo)
    {
        $req = $pdo->prepare('SELECT * FROM `types_role` WHERE type = ?');
        $req->bindParam(1, $role);
        $req->execute();
        $result = $req->fetchAll();
        return $result[0]["idTypes_role"];
    }



    function getidPeople($people, $pdo)
    {
        $data = explode(" ", $people);
        $req = $pdo->prepare('SELECT * FROM `people` WHERE FirstName = ? AND LastName = ?');
        $req->bindParam(1, $data[0]);
        $lastname = $data[1].@$data[2];
        $req->bindParam(2, $lastname);
        $req->execute();
        $result = $req->fetchAll();
        if ($req->rowCount() > 0)
        {
            return $result[0]["idPeople"];
        }
        return false;
    }

    function insert_people($people, $pdo)
    {
        $data = explode(" ", $people);
        $req = $pdo->prepare('INSERT INTO `people` (FirstName, LastName) VALUES (?, ?)');
        $req->bindParam(1, $data[0]);
        $lastname = $data[1].@$data[2];
        $req->bindParam(2, $lastname);
        $req->execute();
        return($pdo->lastInsertId());
    }

    function insert_people_role_movies($idPeople, $id_role, $id_movie_db, $pdo)
    {
        $req = $pdo->prepare('INSERT INTO `people_movies` (fkPeople, fkTypes_Role, fkMovies) VALUES (?, ?, ?)');
        $req->bindParam(1, $idPeople);
        $req->bindParam(2, $id_role);
        $req->bindParam(3, $id_movie_db);
        $req->execute();
        return;
    }

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

    function Insert_studios($studios, $pdo)
    {
        $req = $pdo->prepare('INSERT INTO `studios` (Name) VALUES (?)');
        $req->bindParam(1, $studios);
        $req->execute();
        return($pdo->lastInsertId());
    }

    function insert_studios_Movie($idStudios, $id_movie_db, $pdo)
    {
        $req = $pdo->prepare('INSERT INTO `studios_movies` (fkStudios, fkMovies) VALUES (?, ?)');
        $req->bindParam(1, $idStudios);
        $req->bindParam(2, $id_movie_db);
        $req->execute();
        return;
    }

?>
