<?php
/*
    Autor       : Alain Pichonnat
    mail        : alain.pichonnat@cpnv.ch
    Date        : 09.12.2015
    Description : go to research datas movies in themoviedb(db web)and insert datas in the database.

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
    function connect_tmdb()//fonction ok
    {
        try
        {
            $id_movie_tmbd = array();
            $key = "3258bf1b52c98a7b40e373ad5a43521e";
        	$lang = "fr";
        	$tmdb = new tmdb($key, $lang);
            return $tmdb;
            
        } catch(Exception $e)
        {
            print_r($e);
        }

    }

    // =============================================================================
    //
    // =============================================================================
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
            else
            {
                $id_movie_db = getIdMovie($Full_Info["original_title"], $pdo);
            }

            // On update dans la table files le fkmovie pour correspondre à ce que jonathan à inseré
            if (isset($data[FILES_ID])) 
            {
                //echo "id movie : ".$id_movie_db."<br>";
                //echo "id files : ".$data[FILES_ID]."<br>";

                Update_files($id_movie_db, $data[FILES_ID], $pdo);
            }
            

            //var_dump($id_movie_db);
            //var_dump($Full_Info);
            //print_r($Full_Info);
        }
        // if tabfilmnontrouver isnot null alors je rappele recoverInfoMovie
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
        //$tmdb = connect_tmdb();
        $var = str_replace(" ", "+", $var);
        while(true)
        {
            try
            {
                $idmovie = $tmdb->searchMovie($var);
                break;
            }
            catch(Exception $e)
            {
                //var_dump($e);
                sleep(10);

                //je cree un table avec le nom du film et son id dans la data base pour le rappele du script en fin d'execution 
                //return false;
                //gestion du time out
            }
        }

        if(empty($idmovie)) return false;
        //ici je mais la valu 0 pour ne prendre que le première id car themoviedb peut en trouver plusieur
        return $idmovie[0]->GetID();
    }

    function Search_info_movie($id_tmdb, $tmdb) //fonction ok
    {
        //$tmdb = connect_tmdb();

        if ($id_tmdb == null)return false;
        if(empty($id_tmdb)) return false;

        try
        {
            $data = $tmdb->getMovie($id_tmdb);
        }
        catch(Exception $e)
        {
            var_dump($e);
        }

        $Full_Info = $data->GetJSON();


        if (empty(get_object_vars(json_decode($Full_Info)))) 
        {
            return false;
        }
        return(get_object_vars(json_decode($Full_Info)));
    }
    //recoverInfoMovies($var);
?>
