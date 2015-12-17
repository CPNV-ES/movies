<?php
	require_once("../configs/project_root.php");
	require_once(ROOT_PATH."php/configs/configs.php");
	require_once(ROOT_PATH."php/functions/lib_files.php");
	require_once(ROOT_PATH."php/functions/lib_movies.php");
	//$paths = array("C:/Users/alain.pichonnat@cpnv.ch/Desktop/FILM");

	$paths = array();
	//$paths[] = $_POST["path"];
	//$paths[] = $_GET["path"];

	$result = array();

	if(getFilms($result, $paths) === false){
		return false;
	}

	recoverInfoMovies($result);

?>
