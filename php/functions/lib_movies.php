<?php
/*
    Autor       : Alain Pichonnat
    mail        : alain.pichonnat@cpnv.ch
    Date        : 09.12.2015
    Description : go to research data movies in themoviedb(db web)and insert datas in the database.

*/
    require_once("../configs/project_root.php");
    require_once(ROOT_PATH."php/configs/configs.php");
    require_once(ROOT_PATH."php/functions/lib_db_connect.php");
    require_once(ROOT_PATH."tmdb/tmdb-api.php");
    require_once(ROOT_PATH."php/functions/lib_db_movies.php");

    //data test
   /* $var = array();
    $var[] = array('1', "lucy");
    $var[] = array('2', "iron man");
    $var[] = array('3', "les 4 fantastiques");
    $var[] = array('4', "Les évadés");
    $var[] = array('4', "Le parrain");
    $var[] = array('4', "Pulp Fiction");
    $var[] = array('4', "Fight Club");
    $var[] = array('4', "Matrix");
    $var[] = array('4', "Les sept samouraïs");
    $var[] = array('4', "American History X");
    $var[] = array('4', "Retour vers le futur");
    $var[] = array('4', "Alien - Le 8ème passager");
    $var[] = array('4', "Témoin à charge");

*/

    function recoverInfoMovies($var)
    {
        $id_movie_tmbd = array();
        $key = "3258bf1b52c98a7b40e373ad5a43521e";
        $lang = "fr";
        $tmdb = new tmdb($key, $lang);


        $pdo = connectDB();
        foreach ($var as $data)
        {

            // Recherche de l'id du film dans theMovieDB
            $id_movies_tmdb = Search_id_movie($data[FILES_TITLE], $tmdb);

            // Si l'id du film n'est pas trouvé dans theMovieDb alor on continue
            if ($id_movies_tmdb === false)
            {
                //log("[Not Found] Movie is not found in tmbd, ".$data["FILES_TITLE"]);
                continue;
            }
            // on va rechercher toutes les informations dans TheMovieDb
            $Full_Info = Search_info_movie($id_movies_tmdb, $tmdb);

            if($Full_Info === false)
            {
                continue;
            }

            if (checkMovie($Full_Info, $pdo) === false)
            {
                // On insert les information du films (Title, Year, tagline, Description, poster)
                $id_movie_db = inserMovies($Full_Info, $pdo);

                // la boucle suivant insert si il n'existe pas dans la bdd, le genre et le lie avec l'id du film
                foreach ($Full_Info["genres"] as $row)
                {
                    if(($id_genres = getidGenre(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_genres = insertGenre(get_object_vars($row)["name"], $pdo);
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
                $nbcast = 10;
                if (count(get_object_vars($Full_Info["casts"])["cast"])<10)
                {
                    $nbcast = count(get_object_vars($Full_Info["casts"])["cast"]);
                }

                $id_role = getrollebyid("Actor", $pdo);
                for ($i=0; $i < $nbcast; $i++)
                {
                    if(($idPeople = getidPeople(get_object_vars(get_object_vars($Full_Info["casts"])["cast"][$i])["name"], $pdo)) === false)
                    {
                        $idPeople = insert_people(get_object_vars(get_object_vars($Full_Info["casts"])["cast"][$i])["name"], $pdo);
                    }
                    insert_people_role_movies($idPeople, $id_role, $id_movie_db, $pdo);
                }

                // insert crew, director, writer and producer.
                foreach(get_object_vars($Full_Info["casts"])["crew"] as $crew)
                {
                    $crew = get_object_vars($crew);

                    switch ($crew["job"])
                    {
                        case 'Producer':
                        case 'Director':
                        case 'Writer':
                            if ($id_crew = getidPeople($crew["name"], $pdo) === false)
                            {
                                $id_crew = insert_people($crew["name"], $pdo);
                            }

                            $id_role = getrollebyid($crew["job"], $pdo);

                            insert_people_role_movies($id_crew, $id_role, $id_movie_db, $pdo);
                        break;
                    }
                }
            }
            else
            {
                $id_movie_db = getIdMovie($Full_Info["original_title"], $pdo);
            }

            // On update dans la table files le fkmovie pour correspondre à ce que jonathan à inseré

                Update_files($id_movie_db, $data[FILES_ID], $pdo);


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
    function Search_id_movie ($var, $tmdb) //fonction ok
    {
        $var = str_replace(" ", "+", $var);
        $count = 0;
        do
        {
            if ($count > 3)
            {
                return false;
            }

            if(isset($idmovie))
            {
                sleep(4);
            }

            $idmovie = $tmdb->searchMovie($var);
            $count++;
        }
        while (empty($idmovie));
        
        return $idmovie[0]->GetID();
    }

    function Search_info_movie($id_tmdb, $tmdb) //fonction ok
    {

        if ($id_tmdb == null)return false;
        if(empty($id_tmdb)) return false;

        $count = 0;
        do
        {
            if ($count > 6)
            {
                return false;
            }

            if(isset($data))
            {

                sleep(2);
            }

            $data = $tmdb->getMovie($id_tmdb);
            $count++;
        }
        while(empty($data));

        $Full_Info = $data->GetJSON();

        return(get_object_vars(json_decode($Full_Info)));
    }

?>
