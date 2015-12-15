<?php
	/* Function getSourceId
		Recupére l'id en base de donnée de la source demandé
		Param :
			- db : lien vers la base de donnée (PDO object)
			- path : chemin source demandé
		Return : En succes = l'id de la source, Echéc = False	*/
	function getSourceId($db, $path){
		$query  = 'SELECT sources.`idSources` FROM sources ';
		$query .= 'WHERE sources.`Name` LIKE ? ';
		$query .= 'LIMIT 1';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $path);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		if($req->rowCount() == 1){
			$result = $req->fetch(PDO::FETCH_LAZY);
			return $result["idSources"];
		}
		return false;
	}
	
	
	/* Function getAllSources
		Retourne tout les chemins sources de la base de donnée
		Param :
			- db : lien vers la base de donnée (PDO object)
		Return : En succes = retourne un tableau avec tout les résultats, Echéc = False	*/
	function getAllSources($db){
		$query  = 'SELECT sources.`idSources` FROM sources';
	
		$req = $db->prepare($query);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		if($req->rowCount() >= 1){
			$result = $req->fetchALL();
			return $result;
		}
		return false;
	}
	
	
	/* Function insertSource
		insert une nouvelle source et renvoie l'id après l'insert
		Param :
			- db : lien vers la base de donnée (PDO object)
			- path : chemin source à enregistré
		Return : En succes = l'id de la source, Echéc = False	*/
	function insertSource($db, $path){
		$query  = 'INSERT INTO sources';
		$query .= '(`Name`) VALUE (?)';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $path);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		if($req->rowCount() == 1){
			$result = $db->lastInsertId();
			//echo $error;
			return $result;
		}
		return false;
	}
	
	
	/* Function getTypeId
		Retourne l'id du type de fichier demandé
		Param :
			- db : lien vers la base de donnée (PDO object)
			- file_type : valeur du type à rechercher
		Return : En succes = l'id du type, Echéc = False	*/
	function getTypeId($db, $file_type){
		$query  = 'SELECT types.`idTypes` FROM types ';
		$query .= 'WHERE types.`Name` LIKE ? ';
		$query .= 'LIMIT 1';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $file_type);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		if($req->rowCount() == 1){
			$result = $req->fetch(PDO::FETCH_LAZY);
			return $result["idTypes"];
		}
	
		return false;
	}
	
	
	/* Function getFile
		Retourne le fichier vidéo dans la base de donnée s'il existe à l'emplacement demandé
		Param :
			- db : lien vers la base de donnée (PDO object)
			- source_id : id de la source
			- path : le chemin des sous dossiers à partir de la source
			- file_name : nom du fichier
			- file_type_id : id du type de fichier
		Return : En succes = l'id du fichier, Echéc = False	*/
	function getFile($db, $source_id, $path, $file_name, $file_type_id){
		$query  = 'SELECT files.`idFiles` FROM files ';
		$query .= 'INNER JOIN paths ON paths.`idPaths` = files.`fkPaths` ';
		$query .= 'INNER JOIN sources ON sources.`idSources` = paths.`fkSources` ';
		$query .= 'INNER JOIN types ON types.`idTypes` = files.`fkTypes` ';
		$query .= 'WHERE sources.`idSources` = ? ';
		$query .= "AND paths.`Name` LIKE ? ";
		$query .= "AND files.`Name` LIKE ? ";
		$query .= 'AND types.`idTypes` = ? ';
		$query .= 'LIMIT 1';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $source_id);
		$req->bindParam(2, $path);
		$req->bindParam(3, $file_name);
		$req->bindParam(4, $file_type_id);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		if($req->rowCount() == 1){
			$result = $req->fetch(PDO::FETCH_LAZY);
			return $result["idFiles"];
		}
	
		return false;
	}
	
	
	/* Function insertFile
		insert le nouveau fichier selon les informations données et retourne son id
		Param :
			- db : lien vers la base de donnée (PDO object)
			- source_id : id de la source
			- path : le chemin des sous dossiers à partir de la source
			- file_name : nom du fichier
			- title : nom du fichier propre
			- file_type_id : id du type de fichier
		Return : En succes = l'id du fichier, Echéc = False	*/
	function insertFile($db, $source_id, $path, $file_name, $title, $file_type_id){
		//Insertion du sous dossier table PATHS
		$query  = 'INSERT INTO paths ';
		$query .= '(`fkSources`, `Name`) VALUES (?, ?)';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $source_id);
		$req->bindParam(2, $path);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		$path_id = false;
		if($req->rowCount() == 1){
			$path_id = $db->lastInsertId();
		}
		else{
			$error = "Aucune insertion n'a été faite lors de la requête d'insertion du chemin !!";
			//echo $error;
			return false;
		}
	
		//Insertion du fichier selon l'insertion précedente
		$query  = 'INSERT INTO files ';
		$query .= '(`fkTypes`, `fkPaths`, `Name`, `FileTitle`) VALUE (?, ?, ?, ?)';
	
		$req = $db->prepare($query);
		$req->bindParam(1, $file_type_id);
		$req->bindParam(2, $path_id);
		$req->bindParam(3, $file_name);
		$req->bindParam(4, $title);
	
		if(!$req->execute()){
			$error = $req->errorCode();
			$error = "Erreur est survenu lors de l'execution de la requête ('$error')";
			//echo $error;
			return false;
		}
	
		$file_id = false;
		if($req->rowCount() == 1){
			$file_id = $db->lastInsertId();
		}
		else{
			$error = "Aucune insertion n'a été faite lors de la requête d'insertion du fichier !!";
			//echo $error;
			return false;
		}
	
		return $file_id;
	}
