<?php
//require_once("../configs/project_root.php");
require_once(ROOT_PATH."php/configs/configs.php");
require_once(ROOT_PATH."php/functions/lib_db_files.php");
require_once(ROOT_PATH."php/functions/lib_db_connect.php");

/* Function getInfoFilm
	Permet de recuperer les informations du film dans les variabls title & type
	Param :
		- name : nom du fichier
		- title : variable récupérant le titre du film
		- type : variable récupérant l'extension du fichier
	Return : En succes = True, Echéc = False	*/
function getInfoOfFilm($name, &$title, &$type){
	$matches = array();

	preg_match('/^(\[.*\])?[_\.\s]?(([a-zA-Z0-9éèàô&]{1,})([_\.\s]([A-Z]?([a-zéèàô&]{1,}|[I]{1,})?|(?!(19|20|21)[0-9]{2})[0-9]{1,}|\%\![0-9]{1,}))*)([_\.\s]((\(?([0-9]){4}\)?|[A-Z]{4,}|(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})[\s-]*(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})?).*))?\.(avi|mp4|mov|mpg|mpa|wma)$/', $name, $matches);

	if(isset($matches[2])){
		//Get name of film
		$title = $matches[2];
		$title = str_replace(array('.', '-','_'), ' ', $title);

		//Get extension of file
		$type = $matches[18];
		return true;
	}

	return false;
}

/* Function getDir
	Permet de recuperer le pointeur sur le dossier voulu
	Param :
		- link : Conteneur du pointeur du fichier (Rempli à l'execution)
		- path : Chaine de caractère contenant le chemin vers le dossier
	Return : En succes = True, Echéc = False	*/
function getDir(&$link, $path){
	if($link !== null)
		return true;

	$link = opendir($path);
	if($link === false){
		$error = "Unable to open $path";
		// echo $error;
		return false;
	}

	return true;
}

/* Function listFile
	list la totalité des fichiers présents dans un dossier
	Param :
		- result : tableau récupérant la totalité des fichiers présents dans le dossier source (fonction récursive)
		- path : Chaine de caractère contenant le chemin vers le dossier à scan
	Return : En succes = True, Echéc = False	*/
function listFile(&$result, $path){
	if(!getDir($link, $path)){
		return false;
	}

	while (($entry = readdir($link)) !== false)
	{
		if($entry == "." || $entry == "..")
			continue;

		if(is_dir($path . '/' . $entry)){
			// recherche des fichiers dans le sous-dossier suivant
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

	return true;
}

/* Function getFilms
	Renvoie la totalité des films trouver si leur nom a été parse
	Param :
		- result : tableau récupérant l'ID d'insertion du fichier dans la DB et le nom du film parse
		- path : Chaine de caractère contenant le chemin vers le dossier à scan
	Return : En succes = True, Echéc = False	*/
function getFilms (&$return, $paths = NULL){
	$error = "";
	$db = connectDB();
	if(!isset($paths)){
		if(defined("PATHS")){
			$paths = unserialize(PATHS);
		}

		return false;
	}

	foreach($paths as $path){
		$path = str_replace('\\', '/', $path . '/'); //fucking windows

		$result = NULL;
		if(!listFile($result, $path)){
			$error .= "Erreur sur l'ouverture du répértoire ('$path')\n";
		}


		//base de donnée
		if(($source_id = getSourceId($db, $path)) === false){
			$source_id = insertSource($db, $path);
		}

		$return = array();
		foreach($result as $row)
		{
			if(!getInfoOfFilm($row[RESULT_NAME], $title, $file_type)){
				$error .= "Name is invalide " . $row[RESULT_NAME];
				continue;
			}

			//base de donnée
			if(($file_type_id = getTypeId($db, $file_type)) === false){
				//PARANOIA use default type id : 1
				$file_type_id = 1;
			}

			$row[RESULT_PATH_CLEAR] = str_replace($path, "", $row[RESULT_PATH]);

			if(empty($row[RESULT_PATH_CLEAR])){
				$row[RESULT_PATH_CLEAR] = ".";
			}

			//base de donnée
			if(($file_id = getFile($db, $source_id, $row[RESULT_PATH_CLEAR], $row[RESULT_NAME], $file_type_id)) === false){
				$file_id = insertFile($db, $source_id, $row[RESULT_PATH_CLEAR], $row[RESULT_NAME], $title, $file_type_id);
			}

			if($title !== false){
				// echo $error;
				$return[] = array($file_id, $title);
			}
			else{
				$error .= "Titre non trouvé pour le fichier ('${row[RESULT_PATH]}${row[RESULT_NAME]}')";
			}
		}
	}

	//echo $error;
	return true;
}
