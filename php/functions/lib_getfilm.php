<?php
 
define('PATHS', serialize(array('Z:/CPNV/Exemple/Video_jzaehrin', 'Z:/CPNV/Exemple/Video_Marco')));//Chemin static
define('VERBOSE', false); //Activation de la verbositי #DEBUG


/* Function getDir
	Permet de recuperer le pointeur sur le dossier voulu
	Param :
		- link : Conteneur du pointeur du fichier (Rempli א l'execution)
		- path : Chaine de caractטre contenant le chemin vers le dossier
	Return : En succes = True, Echיc = False	*/
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

function listFile(&$result, &$name, $path){
	if(!getDir($link, $path)){
		return false;
	}

	while (($entry = readdir($link)) !== false)
	{
		if($entry == "." || $entry == "..")
			continue;

		if(is_dir($path . '/' . $entry)){
			listFile($result, $name, $path . '/' . $entry);
		}
		else{
			if(is_file($path .'/' . $entry)){
				if(preg_match('/.*\.avi$/', $entry)){
					$name[] = $entry;
					$result[] = $path . '/' . $entry;
				}
			}
			continue;
		}
    }
}

function getFilms(&$films)
{
	$films = array();
	$paths = unserialize(PATHS);
	foreach ($paths as $path) {
		$result = array();
		$name = array();
		listFile($result, $name, $path);
		foreach ($name as $row) {
			$matches = array();
			preg_match('/^(\[.*\])?[_\.\s]?(([a-zA-Z0-9יטאכ]{1,})([_\.\s]([A-Z]?([a-zיטאכ]{1,}|[I]{1,})?|(?!(19|20|21)[0-9]{2})[0-9]{1,}|\%\![0-9]{1,}))*)([_\.\s]((\(?([0-9]){4}\)?|[A-Z]{4,}|(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})[\s-]*(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})?).*))?\.(avi|mp4|mov|mpg|mpa|wma)$/', $row, $matches);
			if(isset($matches[2])) {
				$tmp = preg_replace('/[_\.]/', ' ', $matches[2]);
				$tmp = strtolower($tmp);
				if(!array_search($tmp, $films)) {
					$films[] = $tmp;
				}
			}
		}
	}
	return true;
}
function getFilmsByPaths(&$films, $paths)
{
	$films = array();

	foreach ($paths as $path) {
		$result = array();
		$name = array();
		listFile($result, $name, $path);
		foreach ($name as $row) {
			$matches = array();
			preg_match('/^(\[.*\])?[_\.\s]?(([a-zA-Z0-9יטאכ]{1,})([_\.\s]([A-Z]?([a-zיטאכ]{1,}|[I]{1,})?|(?!(19|20|21)[0-9]{2})[0-9]{1,}|\%\![0-9]{1,}))*)([_\.\s]((\(?([0-9]){4}\)?|[A-Z]{4,}|(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})[\s-]*(([sSeE](aison|eason|pisode)?)[_\s]?[0-9]{1,})?).*))?\.(avi|mp4|mov|mpg|mpa|wma)$/', $row, $matches);
			if (isset($matches[2])) {
				$tmp = preg_replace('/[_\.]/', ' ', $matches[2]);
				$tmp = strtolower($tmp);
				if (!array_search($tmp, $films)) {
					$films[] = $tmp;
				}
			}
		}
	}
	return true;
}
?>