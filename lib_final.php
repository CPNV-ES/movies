<?php
    include_once("tmdb/tmdb-api.php");

    $var = array();
    $var[] = array('1', "avenger");
    $var[] = array('2', "iron man");
    $var[] = array('3', "les 4 fantastiques");
    $var[] = array('4', "lord of war");

    define("FILES_ID", 0);
    define("FILES_TITLE", 1);

    //$var[i][FILE_ID];
    //$var[i][FILE_TITLE];
    function connect_tmdb()//fonction ok
    {
        $id_movie_tmbd = array();
        $key = "3258bf1b52c98a7b40e373ad5a43521e";
    	$lang = "fr";
    	$tmdb = new tmdb($key, $lang);
        return $tmdb;
    }

    function connectdb()//fonction ok
    {
    	$bdd = "movies";
    	$host = "localhost";
    	$user = "root";
    	$mdp = "";

    	try
    	{
    		$pdo = new PDO('mysql:host='.$host.';dbname='.$bdd, $user, $mdp);
    	}
    	catch (Exception $e)
    	{
    		exit('Erreur : ' . $e->getMessage());
    	}
    	return($pdo);
    }

    // =============================================================================
    //
    // =============================================================================
    function Word($var)
    {
        $pdo = connectdb();
        foreach ($var as $data)
        {
            //verifier si le film n'est pas deja
            if(verif_movies($data[FILES_TITLE], $pdo)===true)
            {
                echo "yolo";
                continue;
            }

            $id_movies_tmdb = Search_id_movie($data[FILES_TITLE]);//faire un continue en cas de non trouver id

            if ($id_movies_tmdb === false)
            {// Si l'id du film n'est pas trouvé, je passe donc au film suivant
                //log("[Non Trouvé] Movie is not found in tmbd, ".$data["FILES_TITLE"]);
                continue;
            }
            $Full_Info = Search_info_movie($id_movies_tmdb);
            $id_movie_db = Insert_Movie($Full_Info, $pdo);
            Update_files($id_movie_db, $data[FILES_ID], $pdo);

            foreach ($Full_Info["genres"] as $row)
            {
                if(($id_genres = getidGenre(get_object_vars($row)["name"], $pdo)) === false)
                {
                    $id_genres = Insert_Genre(get_object_vars($row)["name"], $pdo);
                }
                insert_genres_Movie($id_genres, $id_movie_db, $pdo);
            }

            foreach ($Full_Info["production_countries"] as $row)
            {
                if(($id_countrie = getidCountries(get_object_vars($row)["name"], $pdo)) === false)
                {
                    $id_countrie = Insert_Countries(get_object_vars($row)["name"], $pdo);
                }
                insert_countrie_movie($id_countrie, $id_movie_db, $pdo);

            }
            //var_dump($id_movie_db);
            //var_dump($Full_Info);

        }
        return;
    }
    // =============================================================================
    // =============================================================================

    function verif_movies($movie, $pdo)
    {
        $req = $pdo->prepare('SELECT * FROM `files` WHERE fileTitle = ?');
        $req->bindParam(1, $movie);
        $req->execute();
        $result = $req->fetchAll();

        if (($req->rowCount() > 0 )&&($result[0]["fkMovie"]))
        {
            echo "true ";
            return true;
        }
        return false;
    }



    function Search_id_movie ($var) //fonction ok
    {
        $tmdb = connect_tmdb();
        $var = str_replace(" ", "+", $var);
        $idmovie = $tmdb->searchMovie($var);

        if(empty($idmovie)) return false;
        //var_dump($idmovie[0]->GetID());
        return $idmovie[0]->GetID();
    }


    function Search_info_movie($id_tmdb) //fonction ok
    {
        $tmdb = connect_tmdb();

        if ($id_tmdb == null)return "Not Find Info";

        $data = $tmdb->getMovie($id_tmdb);

        $Full_Info = $data->GetJSON();

        return(get_object_vars(json_decode($Full_Info)));
    }

    function Insert_Movie($movie, $pdo)//fonction ok
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

    function Insert_Genre($genre, $pdo)//fonction ok
    {
        $req = $pdo->prepare('INSERT INTO `genres` (Name) VALUES (?)');
        $req->bindParam(1, $genre);
        $req->execute();
        return($pdo->lastInsertId());
    }

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

    function getidCountries($countrie, $pdo)//fonction à tester
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

    function insert_Countries($countrie, $pdo)//fonction à tester
    {
        $req = $pdo->prepare('INSERT INTO `countries` (Name) VALUES (?)');
        $req->bindParam(1, $countrie);
        $req->execute();
        return($pdo->lastInsertId());
        echo "countrie";
    }

    function insert_countrie_movie($id_countrie, $id_movie_db, $pdo)//fonction à tester
    {
        print_r("id movie :".$id_movie_db." - ");
        print_r("id countrie :".$id_countrie."<br> ");
        $req = $pdo->prepare('INSERT INTO `countries_movies` (fkCountries, fkMovies) VALUES (?, ?)');
        $req->bindParam(1, $id_countrie);
        $req->bindParam(2, $id_movie_db);
        $req->execute();
        return;

    }



    Word($var);
?>
