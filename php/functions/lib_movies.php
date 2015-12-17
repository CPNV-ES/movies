<?php
/**
*    @Author       : Alain Pichonnat | alain.pichonnat@cpnv.ch
*    Date        : 17.12.2015
*    Description : go to research data movies in themoviedb(db web)and insert datas in the database.

*/
    require_once("../configs/project_root.php");
    require_once(ROOT_PATH."php/configs/configs.php");
    require_once(ROOT_PATH."php/functions/lib_db_connect.php");
    require_once(ROOT_PATH."tmdb/tmdb-api.php");
    require_once(ROOT_PATH."php/functions/lib_db_movies.php");

    /**
     *  recoverInfoMovies
     *
     *  function
     *
     * 	@param array        $var   Contains all movie found script jonathan with id table files
     *
     *  @return             no return data
     */
     function recoverInfoMovies($var)
     {
        $id_movie_tmbd = array();
        $key = KEY_TMDB;
        $lang = LANG_TMDB;
        $tmdb = new tmdb($key, $lang);


        $pdo = connectDB();
        foreach ($var as $data)
        {
            //research id movie in thMovieDB
            $id_movies_tmdb = Search_id_movie($data[FILES_TITLE], $tmdb);

            // if id movie is not found, next movie.
            if ($id_movies_tmdb === false continue;

            // research full info movie in TheMovieDB
            $Full_Info = Search_info_movie($id_movies_tmdb, $tmdb);

            // if info movie is not found, next movie.
            if($Full_Info === false)continue;

            //if movie exist in db, it not insert
            if (checkMovie($Full_Info, $pdo) === false)
            {
                // it insert information in db (Title, Year, tagline, Description, poster)
                $id_movie_db = inserMovies($Full_Info, $pdo);

                // if exist, insert data in db
                foreach ($Full_Info["genres"] as $row)
                {
                    if(($id_genres = getidGenre(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_genres = insertGenre(get_object_vars($row)["name"], $pdo);
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

                foreach ($Full_Info["production_companies"] as $row)
                {
                    if(($id_studios = getidstudios(get_object_vars($row)["name"], $pdo)) === false)
                    {
                        $id_studios = Insert_studios(get_object_vars($row)["name"], $pdo);
                    }
                    insert_studios_Movie($id_studios, $id_movie_db, $pdo);
                }

                // insert ten actor in db
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

            // update table files
            Update_files($id_movie_db, $data[FILES_ID], $pdo);
        }
        return;
    }

    /**
	 *  Search id movie in tmdb
	 *
	 * 	@param string       $var   Contains one movie
     *  @param object       object connection with tmdb
     *
	 * 	@return boolean     false  if not found beacause time out
     *  @return int         id movie found
	 */
    function Search_id_movie ($var, $tmdb)
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

    /**
	 *  Search information movie
	 *
	 * 	@param int          $id_tmdb   Contains id movie tmdb
     *  @param object       object connection with tmdb
     *
	 * 	@return boolean     false      if not found beacause time out
     *  @return array       Contains all information movie
	 */
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
