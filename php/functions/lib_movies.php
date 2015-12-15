<?php
/*
    Autor       : Alain Pichonnat
    mail        : alain.pichonnat@cpnv.ch
    Date        : 09.12.2015
    Description : go to research datas movies in themoviedb(db web)and insert datas in the database.

*/
    require_once("../../tmdb/tmdb-api.php");
    require_once("lib_db_connect.php");
    require_once("../configs/config.php");
    require_once("lib_db_movies.php");

    /*//data test
    $var = array();
    $var[] = array('1', "lucy");
    $var[] = array('2', "iron man");
    $var[] = array('3', "les 4 fantastiques");
    $var[] = array('4', "lord of war");
*/

    function connect_tmdb()//fonction ok
    {
        $id_movie_tmbd = array();
        $key = "3258bf1b52c98a7b40e373ad5a43521e";
    	$lang = "fr";
    	$tmdb = new tmdb($key, $lang);
        return $tmdb;
    }

    // =============================================================================
    //
    // =============================================================================
    function recoverInfoMovies($var)
    {
        $pdo = connectDB();
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
            //print_r($Full_Info);
        }
        return;
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
    //Word($var);
?>
