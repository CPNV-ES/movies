<?php
/*
    Autor       : Alain Pichonnat
    mail        : alain.pichonnat@cpnv.ch
    Date        : 09.12.2015
    Description : go to research datas movies in themoviedb(db web)and insert datas in the database.

*/
    include_once("tmdb/tmdb-api.php");

    //data test
    $var = array();
    $var[] = array('1', "lucy");
    $var[] = array('2', "iron man");
    $var[] = array('3', "les 4 fantastiques");
    $var[] = array('4', "lord of war");

    define("FILES_ID", 0);
    define("FILES_TITLE", 1);

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

            // Recherche de l'id du film dans theMovieDB
            $id_movies_tmdb = Search_id_movie($data[FILES_TITLE]);

            // Si l'id du film n'est pas trouvé dans theMovieDb alor on continue
            if ($id_movies_tmdb === false)
            {
                //log("[Not Found] Movie is not found in tmbd, ".$data["FILES_TITLE"]);
                continue;
            }
            // on va rechercher toutes les informations dans TheMovieDb
            $Full_Info = Search_info_movie($id_movies_tmdb);


            if (verif_movie($Full_Info, $pdo) === false)
            {
                // On insert les information du films (Title, Year, tagline, Description, poster)
                $id_movie_db = Insert_Movie($Full_Info, $pdo);

                // la boucle suivant insert si il n'existe pas dans la bdd, le genre et le lie avec l'id du film
                foreach ($Full_Info["genres"] as $row)
                {
                    if(($id_genres = getidGenre(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_genres = Insert_Genre(get_object_vars($row)["name"], $pdo);
                    }
                    insert_genres_Movie($id_genres, $id_movie_db, $pdo);
                }
                // meme boucle que ci-dessus mais pour les countries
                foreach ($Full_Info["production_countries"] as $row)
                {
                    if(($id_countrie = getidCountries(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_countrie = Insert_Countries(get_object_vars($row)["name"], $pdo);
                    }
                    insert_countrie_movie($id_countrie, $id_movie_db, $pdo);

                }
                foreach ($Full_Info["production_companies"] as $row)
                {
                    if(($id_studios = getidstudios(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_studios = Insert_studios(get_object_vars($row)["name"], $pdo);
                    }
                    insert_studios_Movie($id_studios, $id_movie_db, $pdo);
                }

                // ci dessous je vais inserer les acteurs du film, mais pas tous, je prend que les 10 principaux
                $id_role = getrollebyid("Acteur", $pdo);
                for ($i=0; $i < 10; $i++)
                {
                    if(($idPeople = getidPeople(get_object_vars(get_object_vars($Full_Info["casts"])["cast"][$i])["name"], $pdo)) === false)
                    {
                        $idPeople = insert_people(get_object_vars(get_object_vars($Full_Info["casts"])["cast"][$i])["name"], $pdo);
                    }
                    insert_people_role_movies($idPeople, $id_role, $id_movie_db, $pdo);
                }

                // insert crew, director, writer and producer.
                foreach(get_object_vars($Full_Info["casts"])["crew"] as $data)
                {
                    $data = get_object_vars($data);

                    switch ($data["job"])
                    {
                        case 'Producer':
                        case 'Director':
                        case 'Writer':
                            if ($id_crew = getidPeople($data["name"], $pdo) === false)
                            {
                                $id_crew = insert_people($data["name"], $pdo);
                            }

                            $id_role = getrollebyid($data["job"], $pdo);

                            insert_people_role_movies($id_crew, $id_role, $id_movie_db, $pdo);
                        break;
                    }
                }


            }

            // On update dans la table files le fkmovie pour correspondre à ce que jonathan à inseré
            if (isset($data[FILES_ID]) && isset($id_movie_db))
            {
                Update_files($id_movie_db, $data[FILES_ID], $pdo);
            }

            //var_dump($id_movie_db);
            //var_dump($Full_Info);
            print_r($Full_Info);
        }
        return;
    }
    // =============================================================================
    // =============================================================================

    // verif_movies:
    // Utilisation : Va vérifier si le film existe déja dans la base de donnée
    // Attribu:
    //      - $FullInfo -> tout les info du film
    //      - $pdo   -> Objet de connexion à la bdd
    // Sortie:
    //      - true   -> si le film et déja dans la bdd
    //      - False  -> si le film n'y est pas
    function verif_movie($FullInfo, $pdo)
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

    // Search_id_movie:
    // Utilisation : Va rechercher les id des filme dans themoviedb
    // Attribu:
    //      - $var   -> contient les titre de film qui sont contenu dans votre hdd
    // Sortie:
    //      - false  -> si aucun film n'a été trouvé.
    //      - id     -> si le film à été trouvé
    function Search_id_movie ($var) //fonction ok
    {
        $tmdb = connect_tmdb();
        $var = str_replace(" ", "+", $var);
        $idmovie = $tmdb->searchMovie($var);

        if(empty($idmovie)) return false;
        //ici je mais la valu 0 pour ne prendre que le première id car themoviedb peut en trouver plusieur
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

    Word($var);
?>
