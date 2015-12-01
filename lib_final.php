<?php
    include_once("tmdb/tmdb-api.php");

    $var = array();
    $var[] = array('1', "avenger");
    $var[] = array('2', "iron man");
    $var[] = array('3', "les 4 fantastiques");
    $var[] = array('4', "moi moche et mechant");

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
    // =============================================================================
    function Word($var)
    {
        foreach ($var as $data)
        {
            $id_movies_tmdb = Search_id_movie($data[FILES_TITLE]);//faire un continue en cas de non trouver id
            $Full_Info = Search_info_movie($id_movies_tmdb);
            $id_movie_db = Insert_Movie($Full_Info);
            Update_files($id_movie_db, $data[FILES_ID]);

            foreach ($Full_Info["genres"] as $row)
            {
                if(($id_genres = getidGenre(get_object_vars($row)["name"])) === false)
                {
                    $id_genres = Insert_Genre(get_object_vars($row)["name"]);
                }

                //insert_genres_Movie($id_genres, $id_movie_db);
            }

            //var_dump($id_movie_db);
            //var_dump($Full_Info);
        }

        return;
    }
    // =============================================================================
    // =============================================================================


    function Search_id_movie ($var) //fonction ok
    {
        $tmdb = connect_tmdb();
        $var = str_replace(" ", "+", $var);
        $idmovie = $tmdb->searchMovie($var);

        if(empty($idmovie)) return $idmovie = null;
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

    function Insert_Movie($movie)//fonction ok
    {
        $pdo = connectdb();
        $req = $pdo->prepare('INSERT INTO `movies` (Title, Year, Length, Description, Poster) VALUE (?, ?, ?, ?, ?)');

        $req->bindParam(1, $movie["original_title"]);
        $req->bindParam(2, $movie["release_date"]);
        $req->bindParam(3, $movie["runtime"]);
        $req->bindParam(4, $movie["tagline"]);
        $req->bindParam(5, $movie["poster_path"]);
        $req->execute();

        return($pdo->lastInsertId());
    }

    function getidGenre($genre)//fonction ok
    {
        $pdo = connectdb();
        $req = $pdo->prepare('SELECT * FROM `genres` WHERE Name = ?');
        $req->bindParam(1, $genre);
        $req->execute();
        $result = $req->fetchAll();

        if ($req->rowCount() > 0)
        {
            return $resut["idGenres"];
        }
        return false;
    }




    function Insert_Genre($movie)//fonction non ok
    {
        $pdo = connectdb();
        $id_genre_movie = array();

        foreach ($movie["genres"] as $datas)
        {


            $req = $pdo->prepare('INSERT INTO `genres` (Name) VALUE (?)');
            $data = get_object_vars($data);
            $req->bindParam(1, $data["name"]);
            $req->execute();
            $id_genre_movie[] = $pdo->lastInsertId();
        }
        return $id_genre_movie;
    }

    function Update_files($id_movie, $id_files)// fonction ok
    {
        $pdo = connectdb();
        $req = $pdo->prepare('UPDATE `files` SET fkMovie = ? WHERE idFiles = ?');
        $req->bindParam(1, $id_movie);
        $req->bindParam(2, $id_files);
        $req->execute();

        return;
    }



    Word($var);
?>
