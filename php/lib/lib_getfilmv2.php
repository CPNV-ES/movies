<?php
require_once("./getfilmv2_config.php");
require_once("./lib_db_file.php");
require_once("./lib_mymoviedb.php");

function getInfoOfFilm($name, &$title, &$type){
	$matches = array();

	preg_match('/^(\[.*\])?[_\.\s]?(([a-zA-Z0-9éèàô&]{1,})([_\.\s]([A-Z]?([a-zéèàô&]{1,}|[I]{1,})?|(?!(19|20|21)[0-9]{2})[0-9]{1,}|\%\![0-9]{1,}))*)([_\.\s]((\(?([0-9]){4}\)?|[A-Z]{4,}|(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})[\s-]*(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})?).*))?\.(avi|mp4|mov|mpg|mpa|wma)$/', $name, $matches);

	if(isset($matches[2])){
		$title = $matches[2];
		$title = str_replace(array('.', '-','_'), ' ', $title);

		$type = $matches[18];
		return true;
	}

	return false;
}
/* Function getDir
	Permet de recuperer le pointeur sur le dossier voulu
	Param :
		- link : Conteneur du pointeur du fichier (Rempli � l'execution)
		- path : Chaine de caract�re contenant le chemin vers le dossier
	Return : En succes = True, Ech�c = False	*/
function getDir(&$link, $path){
	if($link !== null)
		return true;

	$link = opendir($path);
	if($link === false){
		$link == NULL;
		if(VERBOSE)
			printf("Unable to open %s\n", $path);
		return false;
	}

	return true;
}

function listFile(&$result, $path){
	if(!getDir($link, $path)){
		return false;
	}

	while (($entry = readdir($link)) !== false)
	{
		if($entry == "." || $entry == "..")
			continue;

		if(is_dir($path . '/' . $entry)){
			listFile($result, $path . '/' . $entry);
		}
		else{
			if(is_file($path .'/' . $entry)){
				if(preg_match('/.*\.avi$/', $entry)){
					$result[] = array($path . '/', $entry);
				}
			}
			continue;
		}
    }
}

function getFilms (&$return, $paths = NULL){
	$error = "";
	$db = connect();
	if(!isset($paths)){
		if(defined("PATHS")){
			$paths = unserialize(PATHS);
		}

		return false;
	}

	foreach($paths as $path){
		$path = str_replace('\\', '/', $path . '/');

		$result = NULL;
		if(!listFile($result, $path)){
			$error .= "Erreur sur l'ouverture du répértoire ('$path')\n";
		}



		if(($source_id = getSourceId($db, $path)) === false){
			$source_id = insertSource($db, $path);
		}

		$return = array();
		foreach($result as $row)
		{
			if(!getInfoOfFilm($row[RESULT_NAME], $title, $file_type)){
				$error = "Name is invalide" .$row[RESULT_NAME];
				continue;
			}
			if(($file_type_id = getTypeId($db, $file_type)) === false){
				//PARANOIA use default type id : 1
				$file_type_id = 1;
			}

			$row[RESULT_PATH_CLEAR] = str_replace($path, "", $row[RESULT_PATH]);

			if(empty($row[RESULT_PATH_CLEAR])){
				$row[RESULT_PATH_CLEAR] = ".";
			}
			if(($file_id = getFile($db, $source_id, $row[RESULT_PATH_CLEAR], $row[RESULT_NAME], $file_type_id)) === false){
				$file_id = insertFile($db, $source_id, $row[RESULT_PATH_CLEAR], $row[RESULT_NAME], $file_type_id);
			}
			if($title !== false){
				$return[] = array($file_id, $title);
			}
			else{
				$error .= "Titre non trouvé pour le fichier ('${row[RESULT_PATH]}${row[RESULT_NAME]}')";
			}
		}
	}
}
