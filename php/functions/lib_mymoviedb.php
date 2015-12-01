<?php 
function connect()
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

function insert_movie($pdo, $movie)
{
	//var_dump($movie);
	$req = $pdo->prepare('INSERT INTO `movies` (Title, Year, Length, Description, Poster) VALUE (?, ?, ?, ?, ?)');
	$req->bindParam(1, $movie["Original_Title"]);
	$req->bindParam(2, $movie["release_date"]);
	$req->bindParam(3, $movie["runtime"]);
	$req->bindParam(4, $movie["tagline"]);
	$req->bindParam(5, $movie["Poster"]);
	$req->execute();

	return($pdo->lastInsertId());
}

function  update_files($id_movie)
{

}

function insertion_Genre($Genre)
{
	$pdo = Connect();
	foreach ($Genre as $val)
	{

		$sql_search = "SELECT `genres` FROM 'name' WHERE `genres`.'name'=".$val;

	}
}








//========================================================================================================================================
//========================================================================================================================================
//========================================================================================================================================


function SearchMovie($title)
{

	include_once("tmdb/tmdb-api.php");

	$key = "3258bf1b52c98a7b40e373ad5a43521e";
	$lang = "fr";
	$tmdb = new tmdb($key, $lang);
	$title = str_replace(" ", "+", $title);
	$movie = $tmdb->searchMovie($title);

	$i=0;
	foreach ($movie as $e)
	{
		//echo $e->getTitle() ."<br>";
		//echo $e->getID() ."<br>";
		$tabid[$i] = $e->GetID();

		//echo "<hr>";
		$i++;
	}
	if (!empty($tabid[0]))
	{
		$FullMovie = $tmdb->getMovie($tabid[0]);
		//echo $FullMovie->getTitle()." <br>";
		//echo $FullMovie->getPoster(). "<br>";
		$FullInfo = $FullMovie->getJSON();

		return(get_object_vars(json_decode($FullInfo)));
	}
	return false;

}



function AllParse($Info)
{

	//var_dump($Info);
	//print_r($Info);
	$NbCase = count($Info["genres"]);

	for ($i=0; $i < $NbCase; $i++)
	{
		$Genres[$i] = ($Info["genres"][$i]);
		$Genres[$i] = get_object_vars($Genres[$i]);
		$Genres[$i] = $Genres[$i]["name"];
	}
//=========================================================================
	$TabInfo["Genres"] = @$Genres;
//=========================================================================
	$TabInfo["Original_Title"] = $Info["original_title"];
//=========================================================================
	$TabInfo["release_date"] = $Info["release_date"];
//=========================================================================
	$TabInfo["Synopsie"] = $Info["overview"];
//=========================================================================
	$TabInfo["Poster"] = "http://image.tmdb.org/t/p/w500".($Info['poster_path']);
//=========================================================================
	$TabInfo["Sortie"] = $Info["release_date"];
//=========================================================================
	$TabInfo["runtime"] = $Info["runtime"];
//=========================================================================
	$TabInfo["tagline"] = $Info["tagline"];
//=========================================================================
	$TabInfo["Title"] = $Info["title"];
//=========================================================================
	$trailers = get_object_vars($Info["trailers"]);
	@$trailers = $trailers["youtube"][0];
	@$trailers = get_object_vars($trailers);
	$trailers = "https://www.youtube.com/watch?v=".$trailers['source'];

	$TabInfo["Video"] = $trailers;
//=========================================================================

	$cast = get_object_vars($Info["casts"]);


	//print_r($cast["cast"]);
	$NbCastCast = count($cast["cast"]);
	for($i=0; $i < $NbCastCast; $i++)
	{
		$Casts[$i] = get_object_vars($cast["cast"][$i]);
		$Casts[$i] = $Casts[$i]["name"];
	}
	//print_r($Casts);
	if (isset($Casts))
	{
		$TabInfo["Actors"] = $Casts;
	}
	else
	{
		$TabInfo["Actors"] = " ";
	}
//=========================================================================



	$id_movie = insert_movie(connect(), $TabInfo);
	//update_files($id_movie, $idfiles);

	return($TabInfo);

}







?>
