<?php
	require_once("../configs/project_root.php");
	require_once(ROOT_PATH."php/functions/lib_files.php");
	require_once(ROOT_PATH."php/functions/lib_movies.php");

	$paths = array("C:\Users\jonathan.zaehringer@cpnv.ch\Desktop\FILM_copy");
    $result = array();
    getFilms($result, $paths);

	recoverInfoMovies($result);
?>
