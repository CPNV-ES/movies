<?php
	require_once("../configs/project_root.php");
	require_once(ROOT_PATH."php/functions/lib_db_connect.php");
	require_once(ROOT_PATH."php/functions/lib_db_token.php");
	require_once(ROOT_PATH."php/configs/configs.php");
	require_once(ROOT_PATH."php/functions/lib_files.php");
	require_once(ROOT_PATH."php/functions/lib_movies.php");

	if(!isset($_POST['path']) || empty($_POST['path'])){
		$error = "Path for scanning is empty !";
		return false;
	}

	$paths = array($_POST['path']);

	$db = connectDB();

	if( ($paths_checking = getTokenChecking($db)) === false){
		$error = "Internal server error";
		return false;
	}
	elseif(!empty($paths_checking)){
		foreach($paths_checking as $row){
			$return = strpos($paths[0], $row["path"], 0);

			if($return !== false){
				$error = "This path is being processed !";
				return false;
			}
		}
	}

	$id_token = setToken($db, $paths[0], "Getting all files in source");
	//echo $id_token;

	$result = array();
	if(getFilms($result, $paths) === false){
		removeToken($db, $id_token);
		return false;
	}

	updateToken($db, $id_token, "Getting information for all movies find in the source");
	recoverInfoMovies($result);

	removeToken($db, $id_token);
?>
